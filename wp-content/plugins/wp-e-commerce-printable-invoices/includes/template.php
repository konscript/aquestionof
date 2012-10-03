<?php
function wpsc_pi_printable_invoice_classes() {

	$output = '';
	if( wpsc_pi_is_admin_or_ajax() ) {
		if( wpsc_pi_has_previous_sale() || wpsc_pi_has_next_sale() )
			$output .= 'packing_slip_has-switch';
	}
	echo $output;

}

function wpsc_pi_header_logo() {

	global $wpsc_pi;

	$filename = get_option( $wpsc_pi['prefix'] . '_logo' );
	$upload_dir = wp_upload_dir();
	$url = $upload_dir['baseurl'];
	if( $filename ) {
		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			$url .= '/' . $filename;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			$url .= '/wpsc/upgrades/printable_invoices/' . $filename;
		$output = '<img src="' . $url . '" alt=""';
		$width = get_option( $wpsc_pi['prefix'] . '_header_logo_width' );
		if( $width )
			$output .= ' width="' . $width . '"';
		$height = get_option( $wpsc_pi['prefix'] . '_header_logo_height' );
		if( $height )
			$output .= ' height="' . $height . '"';
		$output .= ' id="logo" />';
		echo $output;
	}

}

function wpsc_pi_footer_logo() {

	global $wpsc_pi;

	$filename = get_option( $wpsc_pi['prefix'] . '_footer_logo' );
	$upload_dir = wp_upload_dir();
	$url = $upload_dir['baseurl'];
	if( $filename ) {
		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			$url .= '/' . $filename;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			$url .= '/wpsc/upgrades/printable_invoices/' . $filename;
		$output = '<img src="' . $url . '" alt="" />';
		echo $output;
	}

}

function wpsc_pi_has_notes() {

	global $purch_data;

	if( $purch_data['notes'] )
		return true;

}

function wpsc_pi_notes() {

	global $purch_data;

	if( $purch_data['notes'] ) {
		$output = $purch_data['notes'];
		echo wpautop( stripslashes_deep( $output ) );
	}

}

function wpsc_pi_has_thumbnails() {

	global $wpsc_pi;

	if( $wpsc_pi['template']['cart']['has_thumbnails'] )
		return true;

}

function wpsc_pi_has_invoice_header() {

	global $wpsc_pi;

	$invoice_header = get_option( $wpsc_pi['prefix'] . '_header' );
	if( $invoice_header )
		return true;

}

function wpsc_pi_invoice_header() {

	global $wpsc_pi;

	$invoice_header = get_option( $wpsc_pi['prefix'] . '_header' );
	$invoice_header = stripslashes( $invoice_header );
	if( $invoice_header ) {
		$output = $invoice_header;
		$store_name = get_bloginfo();
		$output = str_replace( '%store_name%', $store_name, $output );
		echo $output;
	}

}

function wpsc_pi_has_header_text() {

	global $wpsc_pi;

	$header_text = get_option( $wpsc_pi['prefix'] . '_header_text' );
	if( $header_text )
		return true;

}

function wpsc_pi_header_text() {

	global $wpsc_pi;

	$header_text = get_option( $wpsc_pi['prefix'] . '_header_text' );
	$header_text = stripslashes( $header_text );
	if( $header_text ) {
		$output = $header_text;
		$store_name = get_bloginfo();
		$address = get_option( $wpsc_pi['prefix'] . '_address' );
		$city = get_option( $wpsc_pi['prefix'] . '_city' );
		$state = get_option( $wpsc_pi['prefix'] . '_state' );
		$postcode = get_option( $wpsc_pi['prefix'] . '_postcode' );
		$country = get_option( $wpsc_pi['prefix'] . '_country' );
		$phone = get_option( $wpsc_pi['prefix'] . '_phone' );
		$website = '<a href="' . get_bloginfo( 'url' ) . '">' . str_replace( 'http://', '', get_bloginfo( 'url' ) ) . '</a>';
		$email = '<a href="mailto:' . get_bloginfo( 'admin_email' ) . '">' . get_bloginfo( 'admin_email' ) . '</a>';
		$output = str_replace( '%store_name%', $store_name, $output );
		$output = str_replace( '%address%', $address, $output );
		$output = str_replace( '%city%', $city, $output );
		$output = str_replace( '%state%', $state, $output );
		$output = str_replace( '%postcode%', $postcode, $output );
		$output = str_replace( '%country%', $country, $output );
		$output = str_replace( '%phone%', $phone, $output );
		$output = str_replace( '%website%', $website, $output );
		$output = str_replace( '%email%', $email, $output );
		$output = str_replace( "\n", '<br />', $output );
		echo $output;
	}

}

function wpsc_pi_has_footer_header() {

	global $wpsc_pi;

	$footer_header = get_option( $wpsc_pi['prefix'] . '_footer' );
	if( $footer_header )
		return true;

}

function wpsc_pi_footer_header() {

	global $wpsc_pi;

	$footer_header = get_option( $wpsc_pi['prefix'] . '_footer' );
	$footer_header = stripslashes( $footer_header );
	if( $footer_header ) {
		$output = $footer_header;
		$store_name = get_bloginfo();
		$output = str_replace( '%store_name%', $store_name, $output );
		echo $output;
	}

}

function wpsc_pi_has_footer_text() {

	global $wpsc_pi;

	$footer_text = get_option( $wpsc_pi['prefix'] . '_footer_text' );
	if( $footer_text )
		return true;

}

function wpsc_pi_footer_text() {

	global $wpsc_pi;

	$footer_text = get_option( $wpsc_pi['prefix'] . '_footer_text' );
	$footer_text = stripslashes( $footer_text );
	if( $footer_text ) {
		$output = $footer_text;
		$store_name = get_bloginfo();
		$address = get_option( $wpsc_pi['prefix'] . '_address' );
		$city = get_option( $wpsc_pi['prefix'] . '_city' );
		$state = get_option( $wpsc_pi['prefix'] . '_state' );
		$postcode = get_option( $wpsc_pi['prefix'] . '_postcode' );
		$country = get_option( $wpsc_pi['prefix'] . '_country' );
		$phone = get_option( $wpsc_pi['prefix'] . '_phone' );
		$website = '<a href="' . get_bloginfo( 'url' ) . '">' . str_replace( 'http://', '', get_bloginfo( 'url' ) ) . '</a>';
		$email = '<a href="mailto:' . get_bloginfo( 'admin_email' ) . '">' . get_bloginfo( 'admin_email' ) . '</a>';
		$output = str_replace( '%store_name%', $store_name, $output );
		$output = str_replace( '%address%', $address, $output );
		$output = str_replace( '%city%', $city, $output );
		$output = str_replace( '%state%', $state, $output );
		$output = str_replace( '%postcode%', $postcode, $output );
		$output = str_replace( '%country%', $country, $output );
		$output = str_replace( '%phone%', $phone, $output );
		$output = str_replace( '%website%', $website, $output );
		$output = str_replace( '%email%', $email, $output );
		$output = str_replace( "\n", '<br />', $output );
		echo $output;
	}

}

function wpsc_pi_show_total_tax() {

	global $wpsc_pi;

	$total_tax = get_option( $wpsc_pi['prefix'] . '_show_total_tax' );
	if( $total_tax )
		return true;

}

function wpsc_pi_show_printed_on() {

	global $wpsc_pi;

	$printed_on = get_option( $wpsc_pi['prefix'] . '_show_printed_on' );
	if( $printed_on )
		return true;

}

function wpsc_pi_printed_on( $format = null, $echo = true ) {

	if( !$format )
		$format = wpsc_pi_printed_on_format();
	$output = date( $format );
	if( $echo )
		echo $output;
	else
		return $output;

}

function wpsc_pi_purchase_date_format() {

	global $wpsc_pi;

	$format = get_option( $wpsc_pi['prefix'] . '_purchase_date_format' );
	if( !$format )
		$format = 'M d Y, h:i A';
	return $format;

}

function wpsc_pi_purchase_date( $date = null, $format = null, $echo = true ) {

	global $purch_data;

	if( !$date )
		$date = $purch_data['date'];
	if( !$format )
		$format = wpsc_pi_purchase_date_format();
	$output = date( $format, $date );
	if( $echo )
		echo $output;
	else
		return $output;

}

function wpsc_pi_show_total_shipping() {

	global $wpsc_pi;

	$total_shipping = get_option( $wpsc_pi['prefix'] . '_show_total_shipping' );
	if( $total_shipping )
		return true;

}
/**
 * Returns the payment gateway name when provided with the internal name of a payment method within a Sale
 *
 * @since 1.5.8
 *
 * @param string $payment_method Payment Method.
 */
function wpsc_pi_get_payment_method( $payment_method = null ) {

	global $nzshpcrt_gateways;

	if( $payment_method ) {
		$gateways = $nzshpcrt_gateways;
		$payment_gateway_names = '';
		$payment_gateway_names = get_option( 'payment_gateway_names' );
		foreach( (array)$payment_gateway_names as $payment_gateway_name ) {
			if( !empty( $payment_gateway_name ) ) {
				$display_name = $payment_gateway_name;
				break;
			} else {
				$gateways_count = count( $gateways );
				for( $i = 0; $i < $gateways_count; $i++ ) {
					if( $gateways[$i]['internalname'] == $payment_method ) {
						$display_name = $gateways[$i]['display_name'];
						$i = $gateways_count;
						break;
					}
				}
			}
		}
		return $display_name;
	}

}

function wpsc_pi_template_checkout_form_styles() {

	global $purch_data;

	$output = '';
	if( isset( $purch_data['header_group_columns'] ) ) {
		switch( $purch_data['header_group_columns'] ) {

			default:
			case '0':
				$output .= 'width:100%;';
				break;

			case '1':
				$header_group_count = count( $purch_data['header_group'] );
				$header_group_width = ( 100 / $header_group_count ) - 2;
				$output .= 'width:' . $header_group_width . '%;';
				break;

		}
	}
	echo $output;

}

function wpsc_pi_template_checkout_form_label_styles() {

	global $wpsc_pi;

	$output = '';
	$width = get_option( $wpsc_pi['prefix'] . '_checkout_field_width' );
	if( !$width )
		$width = '200';
	$output .= 'width:' . $width . 'px;';
	echo $output;

}

function wpsc_pi_load_template( $template = null ) {

	global $wpsc_pi, $wpdb, $purch_data, $cart_log, $purchlogitem, $wpsc_pi_template;

	$cart = $cart_log;

	if( $template ) {
		switch( wpsc_get_major_version() ) {

			case '3.7':
				if( file_exists( STYLESHEETPATH . '/wpsc-printable_invoice-' . $template . '.php' ) )
					include_once( STYLESHEETPATH . '/wpsc-printable_invoice-' . $template . '.php' );
				else
					include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice-' . $template . '.php' );
				break;

			case '3.8':
				if( file_exists( wpsc_get_template_file_path( 'wpsc-printable_invoice-' . $template . '.php' ) ) )
					include_once( wpsc_get_template_file_path( 'wpsc-printable_invoice-' . $template . '.php' ) );
				else
					include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice-' . $template . '.php' );
				break;

		}
	}

}

function wpsc_pi_replace_order_number() {

	global $wpsc_pi;

	$replace_order_number = get_option( $wpsc_pi['prefix'] . '_replace_order_number' );
	if( $replace_order_number )
		return true;

}

function wpsc_pi_has_order_number() {

	$replace_order_number = wpsc_pi_get_order_number();
	if( $replace_order_number )
		return true;

}

function wpsc_pi_get_order_number() {

	global $wpdb, $purchase_id;

	$output = '';
	if( $purchase_id ) {
		$order_number_sql = "SELECT `meta_value` FROM `" . $wpdb->prefix . "wpsc_meta` WHERE `object_type` = 'purchase_log' AND `object_id` = '" . $purchase_id . "' AND `meta_key` = 'order_number' LIMIT 1";
		$order_number = $wpdb->get_var( $order_number_sql );
		if( $order_number )
			$output = $order_number;
	}
	return $output;

}

function wpsc_pi_show_session_id() {

	global $wpsc_pi;

	$session_id = get_option( $wpsc_pi['prefix'] . '_show_session_id' );
	if( $session_id )
		return true;

}

function wpsc_pi_get_checkout_field_value( $identifier, $type = 'title' ) {

	global $purch_data;

	if( $purch_data['form_data'] ) {
		switch( $type ) {

			case 'ID':
				foreach( $purch_data['form_data'] as $checkout_field ) {
					if( $checkout_field['id'] == $identifier ) {
						if( $purch_data['rekeyed_input'][$checkout_field['id']] )
							$output = $purch_data['rekeyed_input'][$checkout_field['id']];
					}
				}
				break;

			case 'title':
				foreach( $purch_data['form_data'] as $checkout_field ) {
					if( $checkout_field['name'] == $identifier ) {
						if( $purch_data['rekeyed_input'][$checkout_field['id']] )
							$output = $purch_data['rekeyed_input'][$checkout_field['id']];
					}
				}
				break;

		}
	}
	return $output;

}
?>