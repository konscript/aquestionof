<?php
/**
 * @package wp_e_commerce_style_email
 * @version 0.6.2
 */
/*
Plugin Name: WP e-Commerce Style Email
Plugin URI: http://schwambell.com/wp-e-commerce-style-email-plugin/
Description: Style the emails that WP E-Commerce sends to your customers. Create a template file in your theme named wpsc-email_style.php that generates the email output, and use the template tag ecse_get_email_content() to dump WP E-Commerce's purchase report into it.
Author: Jacob Schwartz
Version: 0.6.2
Author URI: http://schwambell.com
*/


/**
 * Get the in-use plugin version
 * @return (float) plugin version
 */
function ecse_version($basename = null) {
	if($basename==null) $basename = plugin_basename(dirname(__FILE__));
	
	if ( ! function_exists( 'get_plugins' ) ) require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin = get_plugins( '/' . $basename );
	$plugin = array_shift($plugin);
	return $plugin['Version'];
}


/**
 * Load the mail operations through the wp_mail filter.
 * @param $vars - refer to the wp_mail filter
 * @return $vars - refer to the wp_mail filter
 */
function ecse_modify_mail($vars) {
	require_once('mail.class.php');
	return ECSE_mail::modify_mail_operation($vars);
}

add_filter('wp_mail','ecse_modify_mail',12,1);


/**
 * Hook into the WPEC transaction filter for email content, to add a temporary tag that stores the purchase ID.
 * The tag is in the format of a HTML comment, but it gets removed
 * @param (multiple) see the originator of the filter 'wpsc_email_message'.
 */
function ecse_add_purch_id_tag($message, $report_id, $product_list, $total_tax, $total_shipping_email, $total_price_email) {
	require_once('mail.class.php');
	$message = ECSE_mail::add_pid_tag($message,$report_id);
	return $message;
}

add_filter('wpsc_email_message','ecse_add_purch_id_tag',12,6);


/**
 * Filter the password reset email message. Remove the <> from around the password reset URL.
 * Only do it if we are styling all website emails.
 * @param unknown_type $message
 * @param unknown_type $key
 */
function ecse_modify_password_message($message, $key) {
	if(get_option('ecse_is_other_active')) {
		$message = str_replace('<', '', $message);
		$message = str_replace('>', '', $message);
	}
	return $message;
}

add_filter('retrieve_password_message','ecse_modify_password_message',12,2);



/**
 * Load admin module.
 */
function ecse_load_admin_functions() {
	if( is_admin() && is_user_logged_in() && current_user_can('manage_options') ) require_once 'admin_functions.php';
}

//add_action('admin_init','ecse_load_admin_functions');
add_action('init','ecse_load_admin_functions'); //because admin_init might be too late to use some hooks






?>
