<?php
function wpsc_pi_email_header() {

	global $wpsc_pi;

	$email_background_colour = get_option( $wpsc_pi['prefix'] . '_email_background_colour' );
	$email_content_colour = get_option( $wpsc_pi['prefix'] . '_email_content_colour' );
	$stylesheet = get_option( $wpsc_pi['prefix'] . '_email_theme_style' );
	if( !$stylesheet )
		$stylesheet = 'default'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo wpsc_pi_email_subject(); ?></title>
<?php if( wpsc_pi_show_email_theme_css() ) { ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php } ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url( 'wp-e-commerce-printable-invoices/templates/admin/style.css', $wpsc_pi['abspath'] ); ?>" />
<?php if( file_exists( wpsc_get_template_file_path( 'wpsc-printable_invoice-' . $stylesheet . '.css' ) ) ) { ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url( 'wp-e-commerce-printable-invoices/templates/store/wpsc-printable_invoice-' . $stylesheet . '.css', $wpsc_pi['abspath'] ); ?>" />
<?php } else { ?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url( 'wp-e-commerce-printable-invoices/templates/store/wpsc-printable_invoice-' . $stylesheet . '.css', $wpsc_pi['abspath'] ); ?>" />
<?php } ?>
	</head>
	<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="<?php if( $email_background_colour ) { ?>background-color:#<?php echo $email_background_colour; ?>;<?php } ?>">
		<div style="<?php if( $email_content_colour ) { ?>background-color:#<?php echo $email_content_colour; ?>;<?php } ?>">
<?php
}

function wpsc_pi_email_footer() { ?>
		</div>
	</body>
</html>
<?php
}

function wpsc_pi_email_subject() {

	$output = get_option( 'wpsc_pi_email_subject' );
	if( $output ) {
		$store_name = get_bloginfo();
		$output = str_replace( '%store_name%', $store_name, $output );
	} else {
		$output = __( 'Purchase Receipt', 'wpsc_pi' );
	}
	return $output;

}
?>