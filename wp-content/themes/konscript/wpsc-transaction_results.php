<?php
/**
 * The Transaction Results Theme.
 *
 * Displays everything within transaction results. Hopefully much more useable than the previous implementation.
 */

	// Added $cart_log_id adn $wpdb for Google Analytics E-commerce tracking fix through Yoast plugin
	global $wpdb, $purchase_log, $errorcode, $sessionid, $echo_to_screen, $cart, $message_html, $cart_log_id;
	
?>
<div class="wrap">

<?php
	echo wpsc_transaction_theme();

	// Code to check whether transaction is processed, true if accepted false if pending or incomplete
	if ( ( true === $echo_to_screen ) && ( $cart != null ) && ( $errorcode == 0 ) && ( $sessionid != null ) ) {			

		echo "<br />" . wpautop(str_replace("$",'\$',$message_html));		
		
	} elseif ( true === $echo_to_screen && ( !isset($purchase_log) ) ) {
		_e('Oops, there is nothing in your cart.', 'wpsc') . "<a href=".get_option("product_list_url").">" . __('Please visit our shop', 'wpsc') . "</a>";
	}
	
	// Added to support Google Analytics E-commerce tracking through Yoast plugin
	$cart_log_id = $wpdb->get_var( "SELECT `id` FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `sessionid`= " . $sessionid . " LIMIT 1" );
	
?>	
	
</div>