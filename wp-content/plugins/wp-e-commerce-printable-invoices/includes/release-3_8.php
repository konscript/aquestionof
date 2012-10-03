<?php
if( wpsc_pi_is_admin_or_ajax() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration menu */
	function wpsc_pi_admin_menu() {

		add_options_page( __( 'Printable Invoices for WP e-Commerce', 'wpsc_pi' ), __( 'Printable Invoices', 'wpsc_pi' ), 'manage_options', 'wpsc_pi', 'wpsc_pi_html_page' );

	}
	add_action( 'admin_menu', 'wpsc_pi_admin_menu' );

	function wpsc_pi_sales_print_header( $columns ) {

		$columns['print_invoice'] = '';
		return $columns;

	}
	add_filter( 'wpsc_manage_sales_custom_headers', 'wpsc_pi_sales_print_header' );

	function wpsc_pi_sales_print_column( $sale ) {

		$link = '<a href="index.php?page=wpsc-sales-logs&purchaselog_id=' . $sale . '&wpsc_admin_action=wpsc_print_invoice" target="_blank" class="button">' . __( 'Print Invoice', 'wpsc_pi' ) . '</a>';

		$output = '';
		$output = '<td class="wpsc_print_invoice">' . $link . '</td>';
		echo $output;

	}
	add_action( 'wpsc_manage_sales_custom_column', 'wpsc_pi_sales_print_column' );

	function wpsc_pi_html_product( $purchase_id = null ) {

		global $wpdb, $wpsc_pi, $purchlogitem, $cart, $purchase_id;

		$cart = $cart_log;
		wpsc_pi_html_init();
		include_once( $wpsc_pi['abspath'] . '/templates/admin/wpsc-admin_printable_invoice-switch.php' );
		include_once( $wpsc_pi['abspath'] . '/includes/template.php' );

		if( !$cart ) {
			echo '<div class="packing_slip ';
			wpsc_pi_printable_invoice_classes();
			echo '">';
			echo '<p>' . __( 'This users cart was empty', 'wpsc_pi' ) . '</p>';
			echo '</div>';
		} else {

			global $purch_data;

			if( file_exists( wpsc_get_template_file_path( 'wpsc-printable_invoice.php' ) ) )
				include_once( wpsc_get_template_file_path( 'wpsc-printable_invoice.php' ) );
			else
				include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice.php' );

		}

	}

	function wpsc_pi_sales_order_number() {

		global $wpdb;
		
		if( wpsc_get_minor_version() >= '3.8.8' )
			$purchase_id = $_GET['id'];
		else
			$purchase_id = $_GET['purchaselog_id'];
		$order_number_sql = "SELECT `meta_value` FROM `" . $wpdb->prefix . "wpsc_meta` WHERE `object_type` = 'purchase_log' AND `object_id` = '" . $purchase_id . "' AND `meta_key` = 'order_number' LIMIT 1";
		$order_number = $wpdb->get_var( $order_number_sql ); ?>

<form method="post" action="">
	<p>
		<label for="order_number"><strong><?php _e( 'Order Number', 'wpsc_pi' ); ?>:</strong></label>
		<input type="text" id="order_number" name="order_number" value="<?php echo $order_number; ?>" />
		<input type="submit" id="button" name="button" value="<?php _e( 'Save', 'wpsc_pi' ); ?>" class="button" />
	</p>
	<input type="hidden" name="wpsc_admin_action" value="wpsc_update_order_number" />
</form>
<?php
	}
	add_action( 'wpsc_billing_details_bottom', 'wpsc_pi_sales_order_number' );

	/* End of: WordPress Administration */

}

function wpsc_pi_get_product_image( $product_id ) {

	$output = get_the_post_thumbnail( $product_id, 'admin-product-thumbnails' );

	return $output;

}
?>