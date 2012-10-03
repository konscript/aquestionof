<?php
function wpsc_pi_html_page() {

	global $wpsc_pi;

	$action = wpsc_get_action();
	wpsc_pi_template_header();
	switch( $action ) {

		case 'remove-logo':
			wpsc_pi_options_remove_header_logo();

			$message = __( 'Header logo removed.', 'wpsc_pi' );
			$output = '<div class="updated settings-error"><p><strong>' . $message . '</strong></p></div>';
			echo $output;

			wpsc_pi_options_form();
			break;

		case 'remove-footer-logo':
			wpsc_pi_options_remove_footer_logo();

			$message = __( 'Footer logo removed.', 'wpsc_pi' );
			$output = '<div class="updated settings-error"><p><strong>' . $message . '</strong></p></div>';
			echo $output;

			wpsc_pi_options_form();
			break;

		case 'update':
			wpsc_pi_options_update();
			wpsc_pi_options_form();
			break;

		default:
			wpsc_pi_options_form();
			break;

	}
	wpsc_pi_template_footer();
}

function wpsc_pi_options_remove_header_logo() {

	global $wpsc_pi;

	$logo = get_option( $wpsc_pi['prefix'] . '_logo' );
	if( $logo ) {
		if( file_exists( $wpsc_pi['uploads'] . $logo ) )
			unlink( $wpsc_pi['uploads'] . $logo );
	}
	update_option( $wpsc_pi['prefix'] . '_logo', '' );

}

function wpsc_pi_options_remove_footer_logo() {

	global $wpsc_pi;

	$logo = get_option( $wpsc_pi['prefix'] . '_footer_logo' );
	if( $logo ) {
		if( file_exists( $wpsc_pi['uploads'] . $logo ) )
			unlink( $wpsc_pi['uploads'] . $logo );
	}
	update_option( $wpsc_pi['prefix'] . '_footer_logo', '' );

}

function wpsc_pi_options_update() {

	global $wpsc_pi;

	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$postcode = $_POST['postcode'];
	$country = $_POST['country'];
	$email_subject = $_POST['email_subject'];
	$email_background_colour = $_POST['email_background_colour'];
	$email_content_colour = $_POST['email_content_colour'];
	$email_theme_style = $_POST['email_theme_style'];
	$email_theme_css = $_POST['email_theme_css'];
	$email_map = $_POST['email_map'];
	$email_replace_wpsc = $_POST['email_replace_wpsc'];
	$header_logo_width = $_POST['header_logo_width'];
	$header_logo_height = $_POST['header_logo_height'];
	$header_header = $_POST['header'];
	$header_text = $_POST['header_text'];
	$header_group = $_POST['header_group'];
	//$checkout_fields = $_POST['checkout_fields'];
	$checkout_field_width = $_POST['checkout_field_width'];
	$show_printed_on = $_POST['show_printed_on'];
	$show_session_id = $_POST['show_session_id'];
	$show_total_tax = $_POST['show_total_tax'];
	$show_total_shipping = $_POST['show_total_shipping'];
	$replace_order_number = $_POST['replace_order_number'];
	$cart_columns = $_POST['cart_columns'];
	$printed_on_format = $_POST['printed_on_format'];
	$footer_header = $_POST['footer'];
	$footer_text = $_POST['footer_text'];
	if( $_FILES['header_logo']['name'] ) {
		$header_logo = get_option( $wpsc_pi['prefix'] . '_logo' );
		if( $header_logo ) {
			if( file_exists( $wpsc_pi['uploads'] . $header_logo ) )
				unlink( $wpsc_pi['uploads'] . $header_logo );
		}
		@is_uploaded_file( $_FILES['header_logo']['tmp_name'] );
		list( $width, $height ) = @getimagesize( $_FILES['header_logo']['tmp_name'] );
		$upload_filename = $wpsc_pi['uploads'] . 'wpsc-printable_invoice_header.' . wpsc_pi_get_extension( $_FILES['header_logo']['name'] );
		@move_uploaded_file( $_FILES['header_logo']['tmp_name'], $upload_filename );
		if( $width > $header_logo_width || $height > $header_logo_height ) {
			include_once( 'includes/SimpleImage.php' );
			$image = new SimpleImage();
			$image->load( $upload_filename );
			$image->resize( $header_logo_width, $header_logo_height );
			$image->save( $upload_filename );
		}
		update_option( $wpsc_pi['prefix'] . '_logo', 'wpsc-printable_invoice_header.' . wpsc_pi_get_extension( $_FILES['header_logo']['name'] ) );
	}
	if( $_FILES['footer_logo']['name'] ) {
		$footer_logo = get_option( $wpsc_pi['prefix'] . '_footer_logo' );
		if( $footer_logo ) {
			if( file_exists( $wpsc_pi['uploads'] . $footer_logo ) )
				unlink( $wpsc_pi['uploads'] . $footer_logo );
		}
		@is_uploaded_file( $_FILES['footer_logo']['tmp_name'] );
		list( $width, $height ) = @getimagesize( $_FILES['footer_logo']['tmp_name'] );
		$upload_filename = $wpsc_pi['uploads'] . 'wpsc-printable_invoice_footer.' . wpsc_pi_get_extension( $_FILES['footer_logo']['name'] );
		@move_uploaded_file( $_FILES['footer_logo']['tmp_name'], $upload_filename );
		if( $width > 322 || $height > 179 ) {
			include_once( 'includes/SimpleImage.php' );
			$image = new SimpleImage();
			$image->load( $upload_filename );
			$image->resize( 322, 179 );
			$image->save( $upload_filename );
		}
		update_option( $wpsc_pi['prefix'] . '_footer_logo', 'wpsc-printable_invoice_footer.' . wpsc_pi_get_extension( $_FILES['footer_logo']['name'] ) );
	}
	update_option( $wpsc_pi['prefix'] . '_phone', $phone );
	update_option( $wpsc_pi['prefix'] . '_address', $address );
	update_option( $wpsc_pi['prefix'] . '_city', $city );
	update_option( $wpsc_pi['prefix'] . '_state', $state );
	update_option( $wpsc_pi['prefix'] . '_postcode', $postcode );
	update_option( $wpsc_pi['prefix'] . '_country', $country );
	update_option( $wpsc_pi['prefix'] . '_email_subject', $email_subject );
	update_option( $wpsc_pi['prefix'] . '_email_background_colour', $email_background_colour );
	update_option( $wpsc_pi['prefix'] . '_email_content_colour', $email_content_colour );
	update_option( $wpsc_pi['prefix'] . '_email_theme_style', $email_theme_style );
	update_option( $wpsc_pi['prefix'] . '_email_theme_css', $email_theme_css );
	update_option( $wpsc_pi['prefix'] . '_email_map', $email_map );
	update_option( $wpsc_pi['prefix'] . '_email_replace_wpsc', $email_replace_wpsc );
	update_option( $wpsc_pi['prefix'] . '_header', $header_header );
	update_option( $wpsc_pi['prefix'] . '_header_logo_width', $header_logo_width );
	update_option( $wpsc_pi['prefix'] . '_header_logo_height', $header_logo_height );
	update_option( $wpsc_pi['prefix'] . '_header_text', $header_text );
	if( $header_group )
		update_option( $wpsc_pi['prefix'] . '_header_group', $header_group );
	//update_option( $wpsc_pi['prefix'] . '_checkout_fields', $checkout_fields );
	update_option( $wpsc_pi['prefix'] . '_checkout_field_width', $checkout_field_width );
	update_option( $wpsc_pi['prefix'] . '_show_printed_on', $show_printed_on );
	update_option( $wpsc_pi['prefix'] . '_show_session_id', $show_session_id );
	update_option( $wpsc_pi['prefix'] . '_show_total_tax', $show_total_tax );
	update_option( $wpsc_pi['prefix'] . '_show_total_shipping', $show_total_shipping );
	update_option( $wpsc_pi['prefix'] . '_replace_order_number', $replace_order_number );
	if( count( $cart_columns ) < 3 && $cart_columns['thumbnails'] ) {
		$error = true;
		$message = '<strong>' . __( 'Cart Columns requires at least 3 columns when showing the Thumbnail column.', 'wpsc_pi' ) . '</strong>';
		$output = '<div class="error settings-error"><p>' . $message . '</p></div>';
	} else if( count( $cart_columns ) < 2 ) {
		$error = true;
		$message = '<strong>' . __( 'Cart Columns requires at least 2 columns.', 'wpsc_pi' ) . '</strong>';
		$output = '<div class="error settings-error"><p>' . $message . '</p></div>';
	} else {
		update_option( $wpsc_pi['prefix'] . '_cart_columns', $cart_columns );
	}
	update_option( $wpsc_pi['prefix'] . '_printed_on_format', $printed_on_format );
	update_option( $wpsc_pi['prefix'] . '_footer', $footer_header );
	update_option( $wpsc_pi['prefix'] . '_footer_text', $footer_text );

	if( !$error ) {
		$message = '<strong>' . __( 'Settings saved.', 'wpsc_pi' ) . '</strong>';
		$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
	}
	echo $output;

}

function wpsc_pi_options_form() {

	global $wpsc_pi, $wpdb;

	$options = (object)array(
		'store' => (object)array(
			'phone' => get_option( $wpsc_pi['prefix'] . '_phone' ),
			'address' => get_option( $wpsc_pi['prefix'] . '_address' ),
			'city' => get_option( $wpsc_pi['prefix'] . '_city' ),
			'state' => get_option( $wpsc_pi['prefix'] . '_state' ),
			'postcode' => get_option( $wpsc_pi['prefix'] . '_postcode' ),
			'country' => get_option( $wpsc_pi['prefix'] . '_country' )
		),
		'email' => (object)array(
			'subject' => get_option( $wpsc_pi['prefix'] . '_email_subject' ),
			'background_colour' => get_option( $wpsc_pi['prefix'] . '_email_background_colour' ),
			'content_colour' => get_option( $wpsc_pi['prefix'] . '_email_content_colour' ),
			'style_css' => get_option( $wpsc_pi['prefix'] . '_email_theme_style' ),
			'theme_css' => get_option( $wpsc_pi['prefix'] . '_email_theme_css' ),
			'map' => get_option( $wpsc_pi['prefix'] . '_email_map' ),
			'replace_wpsc' => get_option( $wpsc_pi['prefix'] . '_email_replace_wpsc' )
		),
		'header' => (object)array(
			'logo' => get_option( $wpsc_pi['prefix'] . '_logo' ),
			'logo_width' => get_option( $wpsc_pi['prefix'] . '_header_logo_width' ),
			'logo_height' => get_option( $wpsc_pi['prefix'] . '_header_logo_height' ),
			'title' => stripslashes( get_option( $wpsc_pi['prefix'] . '_header' ) ),
			'text' => stripslashes( get_option( $wpsc_pi['prefix'] . '_header_text' ) ),
			'show_printed_on' => get_option( $wpsc_pi['prefix'] . '_show_printed_on' ),
			'printed_on_format' => get_option( $wpsc_pi['prefix'] . '_printed_on_format' )
		),
		'show_session_id' => get_option( $wpsc_pi['prefix'] . '_show_session_id' ),
		'show_total_tax' => get_option( $wpsc_pi['prefix'] . '_show_total_tax' ),
		'show_total_shipping' => get_option( $wpsc_pi['prefix'] . '_show_total_shipping' ),
		'replace_order_number' => get_option( $wpsc_pi['prefix'] . '_replace_order_number' ),
		'visible_columns' => get_option( $wpsc_pi['prefix'] . '_cart_columns' ),
		'footer' => (object)array(
			'logo' => get_option( $wpsc_pi['prefix'] . '_footer_logo' ),
			'title' => stripslashes( get_option( $wpsc_pi['prefix'] . '_footer' ) ),
			'text' => stripslashes( get_option( $wpsc_pi['prefix'] . '_footer_text' ) )
		),
		//'checkout_fields' => get_option( $wpsc_pi['prefix'] . '_checkout_fields' ),
		'checkout_field_width' => get_option( $wpsc_pi['prefix'] . '_checkout_field_width' )
	);

	$countries = wpsc_pi_get_wpsc_countries();
	$stylesheets = wpsc_get_theme_styles();
	$headers_sql = "SELECT * FROM `" . $wpdb->prefix . "wpsc_checkout_forms` WHERE `type` = 'heading' AND `active` = 1 ORDER BY checkout_order";
	$headers = $wpdb->get_results( $headers_sql, ARRAY_A );
	if( $headers )
		$header_group = get_option( $wpsc_pi['prefix'] . '_header_group' );
	$checkout_fields_sql = "SELECT * FROM `" . $wpdb->prefix . "wpsc_checkout_forms` WHERE `type` <> 'heading' AND `active` = 1 ORDER BY checkout_order";
	$checkout_fields = $wpdb->get_results( $checkout_fields_sql );
	$cart_columns = wpsc_pi_cart_columns();

	include( $wpsc_pi['abspath'] . '/templates/admin/wpsc-pi_admin-settings.php' );

}
?>