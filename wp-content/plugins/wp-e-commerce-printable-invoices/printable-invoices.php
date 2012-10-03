<?php
/*
Plugin Name: WP e-Commerce - Printable Invoices
Plugin URI: http://www.visser.com.au/wp-ecommerce/plugins/printable-invoices/
Description: Provide detailed sale invoices from WP e-Commerce to your customers in printed or electronic form.
Version: 1.6.2
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'wpsc_pi', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );
include_once( 'includes/template.php' );

include_once( 'includes/common.php' );

switch( wpsc_get_major_version() ) {

	case '3.7':
		include_once( 'includes/release-3_7.php' );
		break;

	case '3.8':
		include_once( 'includes/release-3_8.php' );
		break;

}

$wpsc_pi = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$wpsc_pi['prefix'] = 'wpsc_pi';
$wpsc_pi['name'] = __( 'Printable Invoices for WP e-Commerce', 'wpsc_pi' );
$wpsc_pi['menu'] = __( 'Printable Invoices', 'wpsc_pi' );

$upload_dir = wp_upload_dir();
if( $upload_dir )
	$wpsc_pi['uploads'] = $upload_dir['basedir'] . '/';

if( wpsc_pi_is_admin_or_ajax() ) {

	/* Start of: WordPress Administration */

	include_once( 'includes/install.php' );
	register_activation_hook( __FILE__, 'wpsc_pi_install' );

	include_once( 'includes/settings.php' );
	include_once( $wpsc_pi['abspath'] . '/includes/update.php' );

	function wpsc_pi_action() {

		switch( wpsc_get_major_version() ) {

			case '3.7':
				$directory = 'images';
				break;

			case '3.8':
				$directory = 'wpsc-core/images';
				break;

		}
		$output = '<img src="' . WPSC_URL . '/' . $directory . '/printer.png" alt="printer icon" />&ensp;<a href="' . add_query_arg( 'wpsc_admin_action', 'wpsc_print_invoice' ) . '">' . __( "Print Invoice", "wpsc_pi" ) . '</a><br /><br class="small" />';
		echo $output;

	}
	add_action( 'wpsc_purchlogitem_links_start', 'wpsc_pi_action' );

	function wpsc_pi_init() {

		$action = $_GET['wpsc_admin_action'];
		if( !$action )
			$action = $_POST['wpsc_admin_action'];

		if( $action ) {

			global $purchase_id;

			if( wpsc_get_minor_version() >= '3.8.8' )
				$purchase_id = (int)$_GET['id'];
			else
				$purchase_id = (int)$_GET['purchaselog_id'];
			switch( $action ) {

				case 'wpsc_print_invoice':
					add_action( 'admin_print_styles', 'wpsc_pi_load_styles' );
					require_once( ABSPATH . 'wp-admin/includes/media.php' );
					iframe_header( 'Purchase Receipt' );
					wpsc_pi_html_product( $purchase_id );
					iframe_footer();
					exit();
					break;

				case 'wpsc_update_order_number':

					global $wpdb;

					$order_number = $_POST['order_number'];
					if( isset( $order_number ) ) {
						$meta_id_sql = "SELECT `meta_id` FROM `" . $wpdb->prefix . "wpsc_meta` WHERE `object_type` = 'purchase_log' AND `object_id` = '" . $purchase_id . "' AND `meta_key` = 'order_number' LIMIT 1";
						$meta_id = $wpdb->get_var( $meta_id_sql );
						if( $meta_id ) {
							$wpdb->update( $wpdb->prefix . 'wpsc_meta', array(
								'meta_value' => $order_number
							), array( 'meta_id' => $meta_id, 'object_id' => $purchase_id, 'meta_key' => 'order_number' ) );
						} else {
							$wpdb->insert( $wpdb->prefix . 'wpsc_meta', array(
								'object_type' => 'purchase_log',
								'object_id' => $purchase_id,
								'meta_key' => 'order_number',
								'meta_value' => $order_number
							) );
						}
					}
					break;

			}
		}

	}
	add_action( 'admin_init', 'wpsc_pi_init', 9 );

	function wpsc_pi_load_styles() {

		global $wpsc_pi;

		/* Core stylesheet */
		wp_enqueue_style( 'wpsc_pi-style', plugins_url( '/templates/admin/style.css', __FILE__ ), false, '1.0.0', 'all' );

		if( wpsc_pi_show_email_theme_css() )
			wp_enqueue_style( 'wp-theme', get_bloginfo( 'stylesheet_url' ) );

		/* Default stylesheet */
		$stylesheet = get_option( $wpsc_pi['prefix'] . '_email_theme_style' );
		if( !$stylesheet )
			$stylesheet = 'default';

		switch( wpsc_get_major_version() ) {

			case '3.7':
				if( file_exists( get_stylesheet_directory() . '/wpsc-printable_invoice-' . $template . '.php' ) )
					wp_enqueue_style( 'wpsc_pi-theme', get_stylesheet_directory_uri() . '/wpsc-printable_invoice-' . $stylesheet . '.css', false, '1.0.0', 'all' );
				else
					
				break;

			case '3.8':
				if( file_exists( wpsc_get_template_file_path( 'wpsc-printable_invoice-' . $stylesheet . '.css' ) ) )
					wp_enqueue_style( 'wpsc_pi-theme', wpsc_get_template_file_path( 'wpsc-printable_invoice-' . $stylesheet . '.css', __FILE__ ), false, '1.0.0', 'all' );
				else
					wp_enqueue_style( 'wpsc_pi-theme', plugins_url( '/templates/store/wpsc-printable_invoice-' . $stylesheet . '.css', __FILE__ ), false, '1.0.0', 'all' );
				break;

		}

		/* Print stylesheet */
		wp_enqueue_style( 'wpsc_pi-print', plugins_url( '/templates/admin/wpsc-admin_printable_invoice-print.css', __FILE__ ), false, '1.0.0', 'print' );

	}

	/* End of: WordPress Administration */

} ?>