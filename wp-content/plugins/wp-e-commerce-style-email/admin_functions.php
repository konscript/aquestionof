<?php

//This file should only get loaded during the init action, when the user is doing something on the admin side of things
if(!current_user_can('manage_options')) wp_die( __('You do not have sufficient permissions to access this page.') );


/**
 * Add admin page to the settings menu
 * @return none
 */
function ecse_admin_panel() {
	add_options_page('Store Email Style', 'Store Email Style', 'manage_options', 'store-email-style-options', 'ecse_admin_options_page');
}

/**
 * Load Javascript libraries
 */
function ecse_admin_scripts() {
	if($_REQUEST['page']=='store-email-style-options') {
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-accordion');
	}
}

add_action('admin_menu', 'ecse_admin_panel');
add_action('admin_enqueue_scripts', 'ecse_admin_scripts');


/**
 * Add link to the settings page from the installed-plugins page
 * @param $links
 * @param $file
 * @return $links
 */
function ecse_add_settings_link($links,$file) {
	//$base = 'store-email-style-options';
	$base = plugin_basename(__FILE__);
	if($file==$base) {
		$links[] = '<a href="options-general.php?page=store-email-style-options">settings</a>';
	}
	return $links;
}

add_filter('plugin_row_meta', 'ecse_add_settings_link',10,2);


/**
 * Load admin options page
 */
function ecse_admin_options_page() {
	
	//load the easy-editor
	if(file_exists(dirname(__FILE__).'/easy-theme-file/options_page_additions.php')) require_once 'easy-theme-file/options_page_additions.php';
	
	// in case the AJAX actions aren't working
	// TODO: Can we get rid of these?
	if(!empty($_POST['send-email-submit'])) ecse_send_preview();
	if(!empty($_POST['save-options-submit'])) update_option('ecse_is_active',$_POST['style_emails_store']);
	if(!empty($_POST['send-wish-submit'])) ecse_send_feedback();
	
	//load the options page
	require_once('options_page.php');
	
}












/**
 * used in AJAX from the admin settings page
 * @return (string) "true" on success or "false" on failure
 */
function ecse_save_theme_file() {
	//check_ajax_referer();
	$template_file = get_stylesheet_directory().'/wpsc-email_style.php';
	$savedata = stripslashes(html_entity_decode($_POST['file_content']));
	file_put_contents($template_file,$savedata);
	if(file_get_contents( $template_file )==$savedata) {
		echo 'true';
	} else echo 'false';
	die();
}

add_action('wp_ajax_ecse_save_theme_file', 'ecse_save_theme_file');


/**
 * Used in AJAX from the admin settings page
 * retrieve the theme file for use in the admin settings page
 * @return (string) contents of file, or "false" if unavailable
 */
function ecse_get_theme_file() {
	//check_ajax_referer();
	$template_file = get_stylesheet_directory().'/wpsc-email_style.php';
	if(file_exists($template_file)) {
		$ecse_charset = get_option('ecse_charset');
		if(empty($ecse_charset)) $ecse_charset = get_bloginfo( 'charset' ); //by default, WP assigns the charset of the blog to the emails. Not sure we need to do this. Not even sure anyone would want to override it.
		if(empty($ecse_charset)) $ecse_charset = 'UTF-8';
		echo htmlentities(stripslashes(file_get_contents( $template_file )), ENT_COMPAT, $ecse_charset);
	} else echo 'false';
	die();
}

add_action('wp_ajax_ecse_get_theme_file', 'ecse_get_theme_file');



/**
 * Used in AJAX or POSTing from the admin settings page
 * Email a wish made using the options page make-a-wish form.
 * @return result of wp_mail attempt
 */
function ecse_send_feedback() {
	//check_ajax_referer();
	global $current_user;
		get_currentuserinfo();
		$msg = $_POST['send_wish_content'].' 
		
Sent from '.$current_user->display_name.' ('.$current_user->user_email.') of '.get_bloginfo('siteurl').' on plugin version '.ecse_version();
		
	return wp_mail('jacob@schwambell.com','ECSE plugin wish',$msg);
	
}


/**
 * Used in AJAX from the admin settings page
 * Wrapper for ecse_send_feedback()
 * @return none. echoes 'true' on success or 'false' on failure
 */
function ecse_ajax_feedback() {
	if(ecse_send_feedback()) {
		echo 'true';
	} else echo 'false';
	die();
}

add_action('wp_ajax_ecse_send_feedback', 'ecse_ajax_feedback');




/**
 * Used in AJAX or POSTing from the admin settings page
 * Send the style preview email
 * @return (bool) whether the email was sent correctly
 */
function ecse_send_preview() {
	return wp_mail( $_POST['send_email_address'], __('ECSE test email'), __('This is what a styled email will look like.') );
}


/**
 * Used in AJAX from the admin settings page
 * Wraper for ecse_send_preview()
 * @return none. echoes 'true' on success or 'false' on failure
 */
function ecse_ajax_send_preview() {
	if(ecse_send_preview()) {
		echo 'true';
	} else echo 'false';
	die();
}

add_action('wp_ajax_ecse_send_preview', 'ecse_ajax_send_preview');





/**
 * Used in AJAX or POSTing from the admin settings page
 * Save the settings
 * @return (bool) true after settings are updated
 */
function ecse_save_settings() {
	update_option('ecse_is_active',$_POST['style_emails_store']);
	update_option('ecse_is_other_active',$_POST['style_emails_other']);
	$to_ignore = explode("\n", $_POST['style_emails_ignore'] );
	update_option('ecse_subjects_to_ignore', serialize($to_ignore) );
	return true;
}


/**
 * Used in AJAX from the admin settings page
 * Wraper for ecse_save_settings()
 * @return none. echoes 'true' on success or 'false' on failure
 */
function ecse_ajax_save_settings() {
	
	if(ecse_save_settings()) {
		echo 'true';
	} else echo 'false';
	
	//echo $_POST['style_emails_other'];
	die();
}

add_action('wp_ajax_ecse_save_settings', 'ecse_ajax_save_settings');


/**
 * Used in preview-page loading
 */
function ecse_get_template_preview_on_own_page() {
	$options = array();
	
	//translate query string to options
	if( isset($_REQUEST['ecse_type']) ) {
		switch($_REQUEST['ecse_type']) {
			case 'receipt':
				$options['subject'] = __( 'Purchase Receipt', 'wpsc' );
				break;
			case 'report':
				$options['subject'] = __( 'Purchase Report', 'wpsc' );
				break;
			case 'order_pending':
				$options['subject'] = __( 'Order Pending', 'wpsc' );
				break;
			case 'order_pending_paymt_reqd':
				$options['subject'] = __( 'Order Pending: Payment Required', 'wpsc' );
				break;
			case 'tracking':
				$options['subject'] = get_option( 'wpsc_trackingid_subject' );
				break;
			case 'unlocked':
				$options['subject'] = __( 'The administrator has unlocked your file', 'wpsc' );
				break;
			case 'out_of_stock':
				$options['subject'] = sprintf(__('%s is out of stock', 'wpsc'),'PRODUCT X');
				break;
			default:
				$options['subject'] = 'ECSE test email';
		}
	}
	
	require_once 'mail.class.php';
	ECSE_mail::render_preview($options);
	die();
}

if( isset($_REQUEST['ecse_preview']) ) ecse_get_template_preview_on_own_page();


?>