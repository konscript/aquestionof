<?php
if( wpsc_pi_is_admin_or_ajax() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration menu */
	function wpsc_pi_add_modules_admin_pages( $page_hooks, $base_page ) {

		$page_hooks[] = add_submenu_page( $base_page, __( 'Printable Invoices for WP e-Commerce', 'wpsc_pi' ), __( 'Printable Invoices', 'wpsc_pi' ), 7, 'wpsc_pi', 'wpsc_pi_html_page' );
		return $page_hooks;

	}
	add_filter( 'wpsc_additional_pages', 'wpsc_pi_add_modules_admin_pages', 10, 2 );

	function wpsc_pi_html_product( $purchase_id = null ) {

		global $wpdb, $wpsc_pi, $purchlogitem, $cart_log;

		$cart = $cart_log;

		wpsc_pi_html_init();

		include_once( $wpsc_pi['abspath'] . '/templates/admin/wpsc-admin_printable_invoice-switch.php' );

		if( !$cart_log ) {
			_e( 'This users cart was empty', 'wpsc_pi' );
		} else {
			if( file_exists( STYLESHEETPATH . '/wpsc-printable_invoice.php' ) )
				include_once( STYLESHEETPATH . '/wpsc-printable_invoice.php' );
			else
				include_once( $wpsc_pi['abspath'] . '/templates/store/wpsc-printable_invoice.php' );
		}

	}

	/* End of: WordPress Administration */

}
?>