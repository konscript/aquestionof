<?php
$prefix = 'wpsc_pi';

/* Header */
delete_option( $prefix . '_header' );
delete_option( $prefix . '_header_logo' );
delete_option( $prefix . '_header_logo_height' );
delete_option( $prefix . '_header_logo_width' );
delete_option( $prefix . '_header_text' );
delete_option( $prefix . '_logo' );
delete_option( $prefix . '_show_printed_on' );
delete_option( $prefix . '_printed_on_format' );

/* Body */
delete_option( $prefix . '_replace_order_number' );
delete_option( $prefix . '_show_session_id' );
delete_option( $prefix . '_header_group' );
delete_option( $prefix . '_cart_columns' );
delete_option( $prefix . '_checkout_fields' );
delete_option( $prefix . '_checkout_field_width' );
delete_option( $prefix . '_show_total_shipping' );
delete_option( $prefix . '_show_total_tax' );

/* Store Details */
delete_option( $prefix . '_address' );
delete_option( $prefix . '_city' );
delete_option( $prefix . '_state' );
delete_option( $prefix . '_postcode' );
delete_option( $prefix . '_country' );
delete_option( $prefix . '_phone' );

/* E-mail */
delete_option( $prefix . '_email_background_colour' );
delete_option( $prefix . '_email_content_colour' );
delete_option( $prefix . '_email_map' );
delete_option( $prefix . '_email_replace_wpsc' );
delete_option( $prefix . '_email_subject' );
delete_option( $prefix . '_email_theme_css' );

/* Footer */
delete_option( $prefix . '_footer_logo' );
delete_option( $prefix . '_footer_title' );
delete_option( $prefix . '_footer' );
delete_option( $prefix . '_footer_text' );
?>