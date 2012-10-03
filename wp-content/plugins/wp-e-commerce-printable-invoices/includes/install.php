<?php
function wpsc_pi_install() {

	wpsc_pi_update_options();
	wpsc_pi_create_options();

}

function wpsc_pi_create_options() {

	$prefix = 'wpsc_pi';

	/* Header */
	if( !get_option( $prefix . '_logo' ) )
		add_option( $prefix . '_logo', '' );
	if( !get_option( $prefix . '_header' ) )
		add_option( $prefix . '_header', __( 'Tax Invoice' ) );
	if( !get_option( $prefix . '_header_text' ) )
		add_option( $prefix . '_header_text', '' );
	if( !get_option( $prefix . '_header_logo_width' ) )
		add_option( $prefix . '_header_logo_width', 292 );
	if( !get_option( $prefix . '_header_logo_height' ) )
		add_option( $prefix . '_header_logo_height', 75 );
	if( !get_option( $prefix . '_printed_on_format' ) )
		add_option( $prefix . '_printed_on_format', 'd/m/Y' );

	/* Body */
	if( !get_option( $prefix . '_checkout_field_width' ) )
		add_option( $prefix . '_checkout_field_width', 200 );
	if( !get_option( $prefix . '_cart_columns' ) ) {
		$cart_columns = array(
			'images' => 1,
			'name' => 1,
			'sku' => 1,
			'quantity' => 1,
			'price' => 1,
			'shipping' => 1,
			'tax' => 1,
			'total' => 1
		);
		add_option( $prefix . '_cart_columns', $cart_columns );
	}

	/* Store Details */
	if( !get_option( $prefix . '_phone' ) )
		add_option( $prefix . '_phone', '' );
	if( !get_option( $prefix . '_address' ) )
		add_option( $prefix . '_address', '' );
	if( !get_option( $prefix . '_city' ) )
		add_option( $prefix . '_city', '' );
	if( !get_option( $prefix . '_state' ) )
		add_option( $prefix . '_state', '' );
	if( !get_option( $prefix . '_postcode' ) )
		add_option( $prefix . '_postcode', '' );
	if( !get_option( $prefix . '_country' ) )
		add_option( $prefix . '_country', '' );

	/* E-mail */
	if( !get_option( $prefix . '_email_subject' ) )
		add_option( $prefix . '_email_subject', __( 'Purchase Receipt' ) );
	if( !get_option( $prefix . '_email_background_colour' ) )
		add_option( $prefix . '_email_background_colour', __( 'ffffff' ) );
	if( !get_option( $prefix . '_email_content_colour' ) )
		add_option( $prefix . '_email_content_colour', __( 'ffffff' ) );

	/* Footer */
	if( !get_option( $prefix . '_footer_logo' ) )
		add_option( $prefix . '_footer_logo', '' );
	if( !get_option( $prefix . '_footer' ) )
		add_option( $prefix . '_footer', __( 'Have a nice day!', 'wpsc_pi' ) );
	if( !get_option( $prefix . '_footer_text' ) )
		add_option( $prefix . '_footer_text', __( 'Thanks for shopping with %store_name%.

Phone: %phone%
E-mail: %email%
Website: %website%
Address: %address%, %city%, %state% %postcode%, %country%', 'wpsc_pi' ) );

}
?>