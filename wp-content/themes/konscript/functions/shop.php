<?php 

// Provides tracking for sales in WPEC, outputs correctly formatted Google Analytics push-command
add_filter('yoast-ga-push-after-pageview', 'wpsc_analyticstrack_transaction');
function wpsc_analyticstrack_transaction( $push ) {
	global $wpdb, $purchlogs, $purchase_log, $cart_log_id;

	// Make sure that WPEC is on the transaction finished page, otherwise exit
	if( !isset( $cart_log_id ) || empty($cart_log_id) )
		return $push;

	// Get all cart items and prepare a few variables
	$cart_items = $wpdb->get_results ("SELECT * FROM ".WPSC_TABLE_CART_CONTENTS." WHERE purchaseid = ".$cart_log_id, ARRAY_A);
	$total_shipping = $purchase_log['base_shipping'];
	$total_tax = 0;
	$addItems = array();
	
	// Loop through all the cart items
	foreach( $cart_items as $item ) {
					
		// Fetch values
		$item_name = str_replace( "'", "", $item['name'] );		
		$item['sku'] = $item_name; // Use the product name for SKU for now
/*		$item['sku'] = $wpdb->get_var("SELECT meta_value 
									     FROM ".WPSC_TABLE_META." 
								   	    WHERE meta_key = 'sku' 
										  AND product_id = '".$item['prodid']."' 
										LIMIT 1" ); */
		$item['category'] = "";	
/*		$item['category'] = $wpdb->get_var("SELECT pc.name 
											  FROM ".WPSC_TABLE_PRODUCT_CATEGORIES." pc 
										 LEFT JOIN ".WPSC_TABLE_ITEM_CATEGORY_ASSOC." ca 
										        ON pc.id = ca.category_id 
										     WHERE pc.group_id = '1' 
										       AND ca.product_id = '".$item['prodid']."'" ); */
		// Construct push-string for item
		$addItem = array(
			"_addItem",
			$cart_log_id,			// Order ID
			$item['sku'],			// Item SKU
			$item_name,				// Item Name
			$item['category'],		// Item Category
			$item['price'],			// Item Price
			$item['quantity']);		// Item Quantity

		// Workaround for removing json-brackets before returning (i blame the original plugin)
		$addItem = str_replace("[", "", json_encode($addItem));
		$addItems[] = str_replace("]", "", $addItem);
		
		// Save to total shipping and tax, we'll use it later
		$total_shipping += $item['pnp'];
		$total_tax		+= $item['tax_charged'];
	}

	// Fetch values
	$city = $wpdb->get_var ("SELECT tf.value
                               FROM ".WPSC_TABLE_SUBMITED_FORM_DATA." tf
                          LEFT JOIN ".WPSC_TABLE_CHECKOUT_FORMS." cf
                                 ON cf.id = tf.form_id
                              WHERE cf.type = 'city'
                                AND log_id = ".$cart_log_id );
	$country = $wpdb->get_var ("SELECT tf.value
                                  FROM ".WPSC_TABLE_SUBMITED_FORM_DATA." tf
                             LEFT JOIN ".WPSC_TABLE_CHECKOUT_FORMS." cf
                                    ON cf.id = tf.form_id
                                 WHERE cf.type = 'country'
                                   AND log_id = ".$cart_log_id );	
	$store_name = GA_Filter::ga_str_clean(get_bloginfo('name'));
	$total_price = str_replace(" ", "", wpsc_currency_display($purchase_log['totalprice'], array('display_currency_symbol' => false, 'display_as_html' => false, 'display_decimal_point' => false, 'display_currency_code' => false)));
	$total_tax = str_replace(" ", "", wpsc_currency_display($total_tax, array('display_currency_symbol' => false, 'display_as_html' => false, 'display_decimal_point' => false, 'display_currency_code' => false)));
	$total_shipping = str_replace(" ", "", wpsc_currency_display($total_shipping, array('display_currency_symbol' => false, 'display_as_html' => false, 'display_decimal_point' => false, 'display_currency_code' => false)));

	// Construct push-string for transaction
	$addTrans = array(
		"_addTrans",
		$cart_log_id,		// Order ID
		$store_name,		// Store name
		$total_price,		// Total price
		$total_tax,			// Tax
		$total_shipping,	// Shipping
		$city,				// City
		"",					// State
		$country);			// Country
	
	// Workaround for removing json-brackets before returning (i blame the original plugin)
	$addTrans = str_replace("[", "", json_encode($addTrans));
	$addTrans = str_replace("]", "", $addTrans);
	
	// Add all data to the returned push-array
	$push[] = $addTrans;
	$push[] = "'_trackTrans'";	
	foreach ($addItems as $addItem) {
		$push[] = $addItem;		
	}
	return $push;
}

// Override WPEC's registration/queuing of scripts and css
// wp-content/plugins/wp-e-commerce/wpsc-includes/theme.functions.php
add_action( 'wpsc_enqueue_user_script_and_css', 'wpsc_jscss_override', 10);
function wpsc_jscss_override() {
	
	$version_identifier = WPSC_VERSION . "." . WPSC_MINOR_VERSION;
	$category_id = '';
	if (isset( $wp_query ) && isset( $wp_query->query_vars['taxonomy'] ) && ('wpsc_product_category' ==  $wp_query->query_vars['taxonomy'] ) || is_numeric( get_option( 'wpsc_default_category' ) )
	) {
		if ( isset($wp_query->query_vars['term']) && is_string( $wp_query->query_vars['term'] ) ) {
			$category_id = wpsc_get_category_id($wp_query->query_vars['term'], 'slug');
		} else {
			$category_id = get_option( 'wpsc_default_category' );
		}
	}

	$siteurl = get_option( 'siteurl' );

	if ( is_ssl() )
		$siteurl = str_replace( "http://", "https://", $siteurl );
	if( get_option( 'wpsc_share_this' ) == 1 )
		wp_enqueue_script( 'sharethis', 'http://w.sharethis.com/button/buttons.js', array(), false, true );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'wp-e-commerce',					WPSC_CORE_JS_URL	. '/wp-e-commerce.js',					array( 'jquery' ), $version_identifier, true );
	wp_enqueue_script( 'infieldlabel',					WPSC_CORE_JS_URL	. '/jquery.infieldlabel.min.js',        array( 'jquery' ), $version_identifier, true );
	wp_enqueue_script( 'wp-e-commerce-ajax-legacy',		WPSC_CORE_JS_URL	. '/ajax.js',                          	false,             $version_identifier, true );
	wp_enqueue_script( 'wp-e-commerce-dynamic',			$siteurl			. "/index.php?wpsc_user_dynamic_js=true", false,             $version_identifier, true );
	wp_localize_script( 'wp-e-commerce-dynamic', 'wpsc_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); 
	wp_enqueue_script( 'livequery',						WPSC_URL 			. '/wpsc-admin/js/jquery.livequery.js',	array( 'jquery' ), '1.0.3', true );
//	wp_enqueue_script( 'jquery-rating',               	WPSC_CORE_JS_URL 	. '/jquery.rating.js',                 	array( 'jquery' ), $version_identifier );
	wp_enqueue_script( 'wp-e-commerce-legacy',        	WPSC_CORE_JS_URL 	. '/user.js',                          	array( 'jquery' ), WPSC_VERSION . WPSC_MINOR_VERSION, true );
/*	$lightbox = get_option('wpsc_lightbox', 'thickbox');
	if( $lightbox == 'thickbox' ) {
		wp_enqueue_script( 'wpsc-thickbox',				WPSC_CORE_JS_URL 	. '/thickbox.js',                      	array( 'jquery' ), 'Instinct_e-commerce' );
		wp_enqueue_style( 'wpsc-thickbox',				WPSC_CORE_JS_URL 	. '/thickbox.css',						false, $version_identifier, 'all' );
	} elseif( $lightbox == 'colorbox' ) {
		wp_enqueue_script( 'colorbox-min',				WPSC_CORE_JS_URL 	. '/jquery.colorbox-min.js',			array( 'jquery' ), 'Instinct_e-commerce' );
		wp_enqueue_script( 'wpsc_colorbox',				WPSC_CORE_JS_URL 	. '/wpsc_colorbox.js',					array( 'jquery', 'colorbox-min' ), 'Instinct_e-commerce' );
		wp_enqueue_style( 'wpsc-colorbox-css',			WPSC_CORE_JS_URL 	. '/wpsc_colorbox.css',					false, $version_identifier, 'all' );
	} */
	wp_enqueue_style( 'wpsc-theme-css',               	wpsc_get_template_file_url( 'wpsc-' . get_option( 'wpsc_selected_theme' ) . '.css' ), false, $version_identifier, 'all' );
//	wp_enqueue_style( 'wpsc-theme-css-compatibility', 	WPSC_CORE_THEME_URL . 'compatibility.css',                 	false, $version_identifier, 'all' );
//	wp_enqueue_style( 'wpsc-product-rater',           	WPSC_CORE_JS_URL 	. '/product_rater.css',                 false, $version_identifier, 'all' );
	wp_enqueue_style( 'wp-e-commerce-dynamic',        	$siteurl 			. "/index.php?wpsc_user_dynamic_css=true&category=$category_id", false, $version_identifier, 'all' );

}

// Needed to activate the above action
add_filter( 'wpsc_mobile_scripts_css_filters', 'wpsc_dummymobile_activate' );
function wpsc_dummymobile_activate() {
	return true;
}

?>