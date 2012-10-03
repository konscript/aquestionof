<?php
if( wpsc_pi_is_admin_or_ajax() ) {

	/* Start of: WordPress Administration */

	function wpsc_pi_check_options_exist() {

		$phone = get_option( 'vl_wpscpi_phone' );
		if( $phone )
			return true;

	}

	function wpsc_pi_get_extension( $str ) {

		$i = strrpos( $str, '.' );
		if( !$i ) return '';
		$l = strlen( $str ) - $i;
		$ext = substr( $str, $i + 1, $l );
		return $ext;

	}

	function wpsc_pi_template_header() {

		global $wpsc_pi; ?>
<div id="profile-page" class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php echo $wpsc_pi['menu']; ?></h2>
<?php
	}

	function wpsc_pi_template_footer() { ?>
</div>
<?php
	}

	function wpsc_pi_get_header_logo_url() {

		global $wpsc_pi;

		$filename = get_option( $wpsc_pi['prefix'] . '_logo' );
		$upload_dir = wp_upload_dir();
		$url = $upload_dir['baseurl'];

		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			$url .= '/' . $filename;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			$url .= '/wpsc/upgrades/printable_invoices/' . $filename;

		return $url;

	}

	function wpsc_pi_get_footer_logo_url() {

		global $wpsc_pi;

		$filename = get_option( $wpsc_pi['prefix'] . '_footer_logo' );
		$upload_dir = wp_upload_dir();
		$url = $upload_dir['baseurl'];

		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			$url .= '/' . $filename;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			$url .= '/wpsc/upgrades/printable_invoices/' . $filename;

		return $url;

	}

	function wpsc_pi_cart_columns() {

		$columns = array();
		$columns[] = array( 'thumbnails', 'Thumbnails' );
		$columns[] = array( 'name', 'Name' );
		$columns[] = array( 'sku', 'SKU' );
		$columns[] = array( 'quantity', 'Quantity' );
		$columns[] = array( 'price', 'Price' );
		$columns[] = array( 'shipping', 'Shipping' );
		$columns[] = array( 'tax', 'Tax' );
		$columns[] = array( 'total', 'Total' );
		$columns = apply_filters( 'wpsc_pi_cart_columns', $columns );
		return $columns;

	}

	function wpsc_pi_has_previous_sale() {

		global $wpsc_pi;

		if( $wpsc_pi['template']['switch']['sale_previous'] )
			return true;

	}

	function wpsc_pi_has_next_sale() {

		global $wpsc_pi;

		if( $wpsc_pi['template']['switch']['sale_next'] )
			return true;

	}

	function wpsc_pi_get_wpsc_countries() {

		global $wpdb;

		$countries_sql = "SELECT `id` as ID, `country` FROM `" . WPSC_TABLE_CURRENCY_LIST . "` WHERE `visible` = '1' ORDER BY `country`";
		$countries = $wpdb->get_results( $countries_sql );
		return $countries;

	}

	/* End of: WordPress Administration */

} else {

	/* Start of: Storefront */

	function wpsc_pi_replace_wpsc_email() {

		$output = get_option( 'wpsc_pi_email_replace_wpsc' );
		return $output;

	}

	/* Replace WP e-Commerce Purchase Receipt */
	function wpsc_pi_email_integration( $message, $report_id, $product_list, $total_tax, $total_shipping_email, $total_price_email ) {

		global $wpdb, $wpsc_pi, $purchase_id, $purch_data, $purchlogitem, $cart, $cart_log, $sessionid;

		include_once( $wpsc_pi['abspath'] . '/includes/email.php' );
		include_once( $wpsc_pi['abspath'] . '/includes/template.php' );

		$cart = $cart_log;

		wpsc_pi_html_init();
		$email = $purchlogitem->userinfo['billingemail']['value'];
		ob_start();

		/* Compatibility with WP e-Commerce Style Email */
		$wpsc_pi['html_frame'] = true;
		if( function_exists( 'ecse_version' ) )
			$wpsc_pi['html_frame'] = false;

		if( $wpsc_pi['html_frame'] )
			wpsc_pi_email_header();
		switch( wpsc_get_major_version() ) {

			case '3.7':
				if( file_exists( STYLESHEETPATH . '/wpsc-printable_invoice.php' ) )
					include_once( STYLESHEETPATH . '/wpsc-printable_invoice.php' );
				else
					include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice.php' );
				break;

			case '3.8':
				if( file_exists( wpsc_get_template_file_path( 'wpsc-printable_invoice.php' ) ) )
					include_once( wpsc_get_template_file_path( 'wpsc-printable_invoice.php' ) );
				else
					include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice.php' );
				break;

		}
		if( $wpsc_pi['html_frame'] )
			wpsc_pi_email_footer();
		$output = ob_get_contents();
		ob_end_clean();

		/* Change Content Type to support HTML */
		if( $wpsc_pi['html_frame'] )
			add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html";' ) );
		if( !get_transient( $sessionid . '_pending_email_sent' ) || !get_transient( $sessionid . '_receipt_email_sent' ) )
			wp_mail( $email, wpsc_pi_email_subject(), $output );
		/* Change Content Type back to plain text */
		if( $wpsc_pi['html_frame'] )
			add_filter( 'wp_mail_content_type', create_function( '', 'return "text/plain";' ) );

		set_transient( $sessionid . '_pending_email_sent', true, 60 * 60 * 12 );
		set_transient( $sessionid . '_receipt_email_sent', true, 60 * 60 * 12 );

	}
	if( wpsc_pi_replace_wpsc_email() )
		add_filter( 'wpsc_email_message', 'wpsc_pi_email_integration', 9, 6 );

	/* End of: Storefront */

}

/* Common functions */

function wpsc_pi_show_email_theme_css() {

	$output = get_option( 'wpsc_pi_email_theme_css' );
	return $output;

}

function wpsc_pi_is_admin_or_ajax() {

	$status = false;

	if( is_admin() && !( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) )
		$status = true;

	return $status;

}

function wpsc_pi_printed_on_format() {

	global $wpsc_pi;

	$format = get_option( $wpsc_pi['prefix'] . '_printed_on_format' );

	if( $format )
		return $format;
	else
		return 'd/m/Y';

}

function wpsc_pi_html_init() {

	global $wpdb, $wpsc_pi, $purchase_id, $cart, $cart_log, $purch_data, $purchlogitem, $purchase_log;

	if( !isset( $purchase_id ) )
		$purchase_id = $purchase_log['id'];

	$cart = $cart_log;

	require_once( WPSC_FILE_PATH . '/wpsc-includes/purchaselogs.class.php' );

	$purchlogitem = new wpsc_purchaselogs_items( $purchase_id );

	/* Template: Switch */
	if( wpsc_pi_is_admin_or_ajax() ) {
		$sale_previous_sql = "SELECT `id` FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE `date` < " . $purchlogitem->extrainfo->date . " ORDER BY `date` DESC LIMIT 1";
		$sale_previous = $wpdb->get_var( $sale_previous_sql );
		if( $sale_previous ) {
			$wpsc_pi['template']['switch']['sale_previous'] = $sale_previous;
			if( wpsc_get_minor_version >= '3.8.8' )
				$wpsc_pi['template']['switch']['sale_previous_url'] = 'index.php?page=wpsc-purchase-logs&amp;c=item_details&amp;id=' . $sale_previous . '&amp;wpsc_admin_action=wpsc_print_invoice';
			else
				$wpsc_pi['template']['switch']['sale_previous_url'] = 'index.php?page=wpsc-sales-logs&amp;purchaselog_id=' . $sale_previous . '&amp;wpsc_admin_action=wpsc_print_invoice';
		}

		$sale_next_sql = "SELECT `id` FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE `date` > " . $purchlogitem->extrainfo->date . " LIMIT 1";
		$sale_next = $wpdb->get_var( $sale_next_sql );
		if( $sale_next ) {
			$wpsc_pi['template']['switch']['sale_next'] = $sale_next;
			if( wpsc_get_minor_version >= '3.8.8' )
				$wpsc_pi['template']['switch']['sale_next_url'] = 'index.php?page=wpsc-purchase-logs&amp;c=item_details&amp;id=' . $sale_next . '&amp;wpsc_admin_action=wpsc_print_invoice';
			else
				$wpsc_pi['template']['switch']['sale_next_url'] = 'index.php?page=wpsc-sales-logs&amp;purchaselog_id=' . $sale_next . '&amp;wpsc_admin_action=wpsc_print_invoice';
		}

		if( wpsc_get_minor_version >= '3.8.8' )
			$wpsc_pi['template']['switch']['return_to_sale_url'] = 'index.php?page=wpsc-purchase-logs&amp;c=item_details&amp;id=' . $purchase_id;
		else
			$wpsc_pi['template']['switch']['return_to_sale_url'] = 'index.php?page=wpsc-sales-logs&amp;purchaselog_id=' . $purchase_id;
	}

	$purch_sql = "SELECT * FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `id` = " . (int)$purchase_id;
	$purch_data = $wpdb->get_row( $purch_sql, ARRAY_A );
	$form_sql = "SELECT * FROM `" . WPSC_TABLE_SUBMITED_FORM_DATA . "` WHERE `log_id` = " . (int)$purchase_id;
	$purch_data['input_data'] = $wpdb->get_results( $form_sql, ARRAY_A );
	foreach( $purch_data['input_data'] as $input_row )
		$purch_data['rekeyed_input'][$input_row['form_id']] = str_replace( array( "\r\n", "\r", "\n" ), '<br />', htmlentities( $input_row['value'], ENT_QUOTES, 'UTF-8' ) );
	$form_data_sql = "SELECT * FROM `" . WPSC_TABLE_CHECKOUT_FORMS . "` WHERE `active` = '1' AND checkout_set = '0' ORDER BY checkout_order";
	$purch_data['form_data'] = $wpdb->get_results( $form_data_sql, ARRAY_A );
	if( $purch_data['form_data'] ) {
		$checkout_fields = get_option( $wpsc_pi['prefix'] . '_checkout_fields' );
/*
		foreach( $purch_data['form_data'] as $key => $form_field ) {
			if( !isset( $checkout_fields[$form_field['id']] ) && $form_field['type'] <> 'heading' )
				unset( $purch_data['form_data'][$form_field['id']] );
		}
*/
	}
	if( !isset( $cart ) ) {
		$cart_sql = "SELECT * FROM `" . WPSC_TABLE_CART_CONTENTS . "` WHERE `purchaseid` = " . (int)$purchase_id;
		$cart = $wpdb->get_results( $cart_sql, ARRAY_A );
	}
	if( $cart ) {
		$wpsc_pi['template']['cart'] = array(
			'all_donations' => true,
			'all_no_shipping' => true,
			'end_total' => 0,
			'total_tax' => 0,
			'total_shipping' => 0
		);
		if( wpsc_tax_isincluded() )
			$wpsc_pi['template']['cart']['tax_label'] = __( 'Tax Included', 'wpsc_pi' );
		else
			$wpsc_pi['template']['cart']['tax_label'] = __( 'Tax', 'wpsc_pi' );
		foreach( $cart as $key => $row ) {

			/* Template */
			if( $row['donation'] != 1 )
				$wpsc_pi['template']['cart']['all_donations'] = false;

			/* Shipping */
			if( $row['no_shipping'] != 1 ) {
				$cart[$key]['shipping'] = $row['pnp'] * $row['quantity'];
				if( isset( $cart[$key]['total_shipping'] ) )
					$wpsc_pi['template']['cart']['total_shipping'] += $cart[$key]['total_shipping'];
				$wpsc_pi['template']['cart']['all_no_shipping'] = false;
			} else {
				$wpsc_pi['template']['cart']['total_shipping'] = 0;
			}

			/* Product: SKU */
			$cart[$key]['sku'] = get_product_meta( $row['prodid'], 'sku', true );
			if( !$cart[$key]['sku'] )
				$cart[$key]['sku'] = '-';

			/* Product: Variation */
			switch( wpsc_get_major_version() ) {
			
				case '3.7':
					$variation_sql = "SELECT * FROM `" . WPSC_TABLE_CART_ITEM_VARIATIONS . "` WHERE `cart_id` = " . $row['id'];
					$variation_data = $wpdb->get_results( $variation_sql, ARRAY_A ); 
					$variation_count = count( $variation_data );
					if( $variation_count > 1 ) {
						$cart[$key]['variation_list'] = " (";
						$i = 0;
						foreach( $variation_data as $variation ) {
							if( $i > 0 )
								$cart[$key]['variation_list'] .= ", ";
							$value_id = $variation['value_id'];
							$value_data_sql = "SELECT * FROM `" . WPSC_TABLE_VARIATION_VALUES . "` WHERE `id` = " . $value_id . " LIMIT 1";
							$value_data = $wpdb->get_results( $value_data_sql, ARRAY_A );
							$cart[$key]['variation_list'] .= $value_data[0]['name'];
							$i++;
						}
						$cart[$key]['variation_list'] .= ")";
					} else if( $variation_count == 1 ) {
						$value_id = $variation_data[0]['value_id'];
						$value_data = $wpdb->get_results( "SELECT * FROM `" . WPSC_TABLE_VARIATION_VALUES . "` WHERE `id` = " . $value_id . " LIMIT 1", ARRAY_A );
						$cart[$key]['variation_list'] = " (" . $value_data[0]['name'] . ")";
					} else {
						$cart[$key]['variation_list'] = '';
					}
					break;
		
				case '3.8':
					$cart[$key]['variation_list'] = '';
					break;
		
			}
			if( $cart[$key]['variation_list'] )
				$cart[$key]['variation_list'] = stripslashes( $cart[$key]['variation_list'] );

			/* Product: Price */
			$cart[$key]['total_price'] = $cart[$key]['price'] * $cart[$key]['quantity'];

			/* Product: Customer Message */
			if( isset( $cart['custom_message'] ) )
				$cart[$key]['customer_message'] = wpautop( $cart['custom_message'] );

			/* Product: Customer File */
			$cart[$key]['customer_file'] = maybe_unserialize( $cart[$key]['files'] );
			$cart[$key]['files'] = null;

			/* Product: Thumbnail */
			$cart[$key]['thumbnail'] = wpsc_pi_get_product_image( $row['prodid'] );
			if( $cart[$key]['thumbnail'] )
				$wpsc_pi['template']['cart']['has_thumbnails'] = true;

			/* Product: Downloads */
			$has_files_sql = "SELECT `fileid`, `uniqueid` as guid FROM `" . $wpdb->prefix . "wpsc_download_status` WHERE `purchid` = '" . $purchase_id . "' AND `product_id` = '" . $row['prodid'] . "' AND `cartid` = '" . $row['id'] . "' AND active = '1'";
			$has_files = $wpdb->get_results( $has_files_sql );
			if( $has_files ) {
				$post_type = 'wpsc-product-file';
				$args = array(
					'post_type' => $post_type,
					'post_parent' => $row['prodid'],
					'numberposts' => -1,
					'post_status' => 'all'
				);
				$files = get_posts( $args );
				if( $files ) {
					$files_count = count( $files );
					$cart[$key]['files'] = array();
					if( $files_count == 1 ) {
						$file_download_sql = "SELECT `uniqueid` as guid FROM `" . $wpdb->prefix . "wpsc_download_status` WHERE `purchid` = '" . $purchase_id . "' AND `cartid` = '" . $row['id'] . "' AND active = '1'";
						$file_download = $wpdb->get_row( $file_download_sql );
						if( $file_download )
							$cart[$key]['name'] = '<a href="' . site_url( '?downloadid=' . $file_download->guid ) . '">' . $cart[$key]['name'] . '</a>';
					} else {
						foreach( $files as $file ) {
							foreach( $has_files as $has_file ) {
								if( $has_file->fileid == $file->ID ) {
									$cart[$key]['files'][] = array(
										'filename' => $file->post_title,
										'url' => site_url( '?downloadid=' . $has_file->guid ),
									);
								}
							}
						}
					}
				}
			}

			/* Product Tax */
			$wpsc_pi['template']['cart']['taxes'][$row['gst']] += $row['tax_charged'];
			if( wpsc_tax_isincluded() ) {
				$wpsc_pi['template']['cart']['total_tax'] += $row['tax_charged'];
			} else {
				if( $purch_data['wpec_taxes_rate'] != 0.00 )
					$cart[$key]['tax_charged'] = $cart[$key]['price'] / $purch_data['wpec_taxes_rate'];
				else
					$wpsc_pi['template']['cart']['total_tax'] += $row['tax_charged'];
			}
			$cart[$key]['total_gst'] = $cart[$key]['total_price'] - ( $row['total_price'] / ( 1 + ( $row['gst'] / 100 ) ) );
			if( $cart[$key]['total_gst'] > 0 )
				$cart[$key]['tax_per_item'] = $cart[$key]['total_gst'] / $row['quantity'];

			/* Product: Total */
			if( wpsc_tax_isincluded() )
				$cart[$key]['total'] = $cart[$key]['total_price'];
			else
				$cart[$key]['total'] = $cart[$key]['total_price'] + $row['tax_charged'];

		}
		if( $purch_data['wpec_taxes_total'] != 0.00 )
			$wpsc_pi['template']['cart']['total_tax'] = $purch_data['wpec_taxes_total'];
	}
	$j = 0;
	
	$i = 0;
	$header_group = get_option( 'wpsc_pi_header_group' );
	if( $header_group ) {
		$header_group_last = '';
		$purch_data['header_group'] = $header_group;
		$purch_data['header_group_columns'] = false;
		foreach( $header_group as $key => $header_group_item ) {
			if( $i <> 0 ) {
				if( $header_group[$header_group_last] <> $header_group_item ) {
					$purch_data['header_group_columns'] = true;
					break;
				}
			}
			$header_group_last = $key;
			$i++;
		}
	}

}

function wpsc_get_theme_styles() {

	$stylesheets = array();
	$stylesheets['twentyeleven'] = 'Twenty Eleven';
	//$stylesheets['visserlabs'] = 'Visser Labs';
	$stylesheets = apply_filters( 'wpsc_pi_theme_styles', $stylesheets );
	return $stylesheets;

}

function wpsc_pi_has_header_logo() {

	global $wpsc_pi;

	$filename = get_option( $wpsc_pi['prefix'] . '_logo' );
	$upload_dir = wp_upload_dir();

	if( $filename ) {
		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			return true;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			return true;
	}

}

function wpsc_pi_has_footer_logo() {

	global $wpsc_pi;

	$filename = get_option( $wpsc_pi['prefix'] . '_footer_logo' );
	$upload_dir = wp_upload_dir();

	if( $filename ) {
		if( file_exists( $upload_dir['basedir'] . '/' . $filename ) )
			return true;
		else if( file_exists( $wpsc_pi['uploads'] . 'wpsc/upgrades/printable_invoices/' . $filename ) )
			return true;
	}

}

function wpsc_pi_total_colspan() {

	$cart_columns = wpsc_pi_count_columns();
	$colspan = 0;
	if( $cart_columns > 2 ) {
		$colspan = $cart_columns - 1;
		if( wpsc_pi_show_column( 'thumbnails' ) && !wpsc_pi_has_thumbnails() )
			$colspan = $colspan - 1;
	} else {
		$colspan = 0;
	}
	$output = $colspan;
	echo $output;

}

function wpsc_pi_count_columns() {

	global $wpsc_pi;

	$cart_columns = get_option( $wpsc_pi['prefix'] . '_cart_columns' );
	if( $cart_columns )
		return count( $cart_columns );

}

function wpsc_pi_show_column( $column ) {

	global $wpsc_pi;

	$cart_columns = get_option( $wpsc_pi['prefix'] . '_cart_columns' );
	if( $cart_columns ) {
		foreach( $cart_columns as $key => $cart_column ) {
			if( $key == $column ) {
				return true;
				break;
			}
		}
	}

}
?>