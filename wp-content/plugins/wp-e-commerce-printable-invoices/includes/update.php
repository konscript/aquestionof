<?php
function wpsc_pi_admin_notice() {

	if( wpsc_pi_check_options_exist() )
		wpsc_pi_update_options();

}
add_action( 'admin_notices', 'wpsc_pi_admin_notice' );

function wpsc_pi_update_options() {

	$options = array();
	$options[] = array( 'old_name' => 'logo', 'new_name' => 'header_logo' );
	$options[] = array( 'old_name' => 'phone' );
	$options[] = array( 'old_name' => 'address' );
	$options[] = array( 'old_name' => 'city' );
	$options[] = array( 'old_name' => 'state' );
	$options[] = array( 'old_name' => 'postcode' );
	$options[] = array( 'old_name' => 'country' );
	$options[] = array( 'old_name' => 'footer_logo' );
	$options[] = array( 'old_name' => 'footer', 'new_name' => 'footer_title' );
	$options[] = array( 'old_name' => 'footer_text' );

	$old_prefix = 'vl_wpscpi';
	$new_prefix = 'wpsc_pi';

	wpsc_vl_migrate_prefix_options( $options, $old_prefix, $new_prefix );

}
?>