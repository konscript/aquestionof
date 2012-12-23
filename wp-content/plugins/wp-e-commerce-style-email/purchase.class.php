<?php 


/**
 * To be used in purchase report/receipt/etc content replacement
 * @author JS
 *
 */
class ECSE_purchase {
	
	static $purchase_id;
	static $purchase_data; //all retrieved data
	static $loop_prod_rows; //product rows that have not been output yet
	
	/**
	 * Retrieve a purchase from WP e-Commerce's dbase table. Used to initialize the purchase wrapper.
	 * @param (int) $purchase_id
	 * @return array or null
	 */
	static function get_purchase($purchase_id) {
		self::$purchase_id = $purchase_id;
		
		//the default data structure:
		$returner = array(
				//the core miscellaneous data saved for a purchase, as spat out by WPEC
				'purchase_props' => array(
						'id' 				=> '',
						'totalprice' 		=> '',
						'statusno' 			=> '',
						'sessionid' 		=> '',
						'transactid' 		=> '',
						'authcode'			=> '',
						'processed'			=> '',
						'user_ID' 			=> '',
						'date' 				=> '',
						'gateway' 			=> '',
						'billing_country' 	=> '',
						'shipping_country' 	=> '',
						'base_shipping' 	=> '',
						'email_sent' 		=> '',
						'stock_adjusted'	=> '',
						'discount_value' 	=> '',
						'discount_data' 	=> '',
						'track_id' 			=> '',
						'billing_region' 	=> '',
						'shipping_region' 	=> '',
						'find_us' 			=> '',
						'engravetext' 		=> '',
						'shipping_method' 	=> '',
						'shipping_option' 	=> '',
						'affiliate_id' 		=> '',
						'plugin_version' 	=> '',
						'notes' 			=> '',
						'wpec_taxes_total' 	=> '',
						'wpec_taxes_rate' 	=> ''
						),
				//just the totals, recalculated for accuracy
				'total_props' => array(
						'products_total' 	=> '',
						'discount_total' 	=> '',
						'shipping_total' 	=> '',
						'tax_total' 		=> '',
						'grand_total' 		=> ''
				),
				//everything billing-wise
				'billing_props' => array(
						'gateway' 			=> '',
						'billingfirstname' 	=> '',
						'billinglastname' 	=> '',
						'billingaddress' 	=> '',
						'billingcity' 		=> '',
						'billingstate' 		=> '',
						'billingpostcode' 	=> '',
						'billingcountry' 	=> ''
				),
				//everything shipping-wise
				'shipping_props' => array(
						'track_id' 			=> '',
						'shipping_method' 	=> '',
						'shipping_option' 	=> '',
						'shipping_total' 	=> '',
						'shippingfirstname' => '',
						'shippinglastname' 	=> '',
						'shippingaddress' 	=> '',
						'shippingcity' 		=> '',
						'shippingstate' 	=> '',
						'shippingpostcode' 	=> '',
						'shippingcountry' 	=> ''
				),
				//a way to access all checkout fields
				'checkout_values' => array(
							'unique_name' 	=> array(), //search by unique names
							'name' 			=> array(), //search by names
							'id' 			=> array() //search by ID
						),
				//the products in the purchase
				'product_rows' => array(
							array(
									'SKU' 	=> '',
									'ID'	=> '',
									'name' 	=> '',
									'cost' 	=> '',
									'qty' 	=> ''
							)
						)
				);
	
		
		//let's get some data
		global $wpdb;
		$purch_sql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `id`=%d", $purchase_id );
		$purch_data = $wpdb->get_row( $purch_sql, ARRAY_A ) ;
	
		$cartsql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_CART_CONTENTS."` WHERE `purchaseid`=%d", $purchase_id );
		$cart_log = $wpdb->get_results($cartsql,ARRAY_A) ; 
		$j = 0;
		
		$form_sql = $wpdb->prepare( "SELECT * FROM `".WPSC_TABLE_SUBMITED_FORM_DATA."` WHERE `log_id` = %d", $purchase_id );
		$input_data = $wpdb->get_results($form_sql,ARRAY_A);
		
			
		/* PROCESS CHECKOUT FIELD DATA */
		if($input_data != null) {
		
			//organise the checkout values for correllation with the checkout template
			foreach($input_data as $input_row) {
				$rekeyed_input[$input_row['form_id']] = $input_row;
			}
			$form_data = $wpdb->get_results("SELECT * FROM `".WPSC_TABLE_CHECKOUT_FORMS."` WHERE `active` = '1'",ARRAY_A);
		
			//correllate checkout values with the template
			$ecse_unique_values = array();
			$ecse_named_values = array();
			$ecse_id_values = array();
			foreach($form_data as $form_field) {
				if('x'.$form_field['unique_name']!='x') $ecse_unique_values[$form_field['unique_name']] = wp_kses($rekeyed_input[$form_field['id']]['value'], array() );
				$ecse_named_values[$form_field['name']] = wp_kses($rekeyed_input[$form_field['id']]['value'], array() );
				$ecse_id_values[$form_field['id']] = wp_kses($rekeyed_input[$form_field['id']]['value'], array() );
			}
			//WPEC apparently stores the shipping state using a numbering system. Convert to a name.
			if(isset($ecse_unique_values['shippingstate']) && is_numeric($ecse_unique_values['shippingstate'])) $ecse_unique_values['shippingstate']=wpsc_get_region($ecse_unique_values['shippingstate']);
			if(isset($ecse_unique_values['billingstate']) && is_numeric($ecse_unique_values['billingstate'])) $ecse_unique_values['billingstate']=wpsc_get_region($ecse_unique_values['billingstate']);
			
			$returner['checkout_values'] = array(
				'unique_name' => $ecse_unique_values, 	//search by unique names
				'name' => $ecse_named_values, 			//search by display names
				'id' => $ecse_id_values 				//search by IDs
				);
			$returner['shipping_props'] = array_merge(
					$returner['shipping_props'],
					array(
						'shippingfirstname' => $ecse_unique_values['shippingfirstname'],
						'shippinglastname' 	=> $ecse_unique_values['shippinglastname'],
						'shippingaddress' 	=> $ecse_unique_values['shippingaddress'],
						'shippingcity' 		=> $ecse_unique_values['shippingcity'],
						'shippingstate' 	=> $ecse_unique_values['shippingstate'],
						'shippingpostcode' 	=> $ecse_unique_values['shippingpostcode'],
						'shippingcountry' 	=> $ecse_unique_values['shippingcountry']
					)
			);
			$returner['billing_props'] = array_merge(
					$returner['billing_props'],
					array(
							'billingfirstname' 	=> $ecse_unique_values['billingfirstname'],
							'billinglastname' 	=> $ecse_unique_values['billinglastname'],
							'billingaddress' 	=> $ecse_unique_values['billingaddress'],
							'billingcity' 		=> $ecse_unique_values['billingcity'],
							'billingstate' 		=> $ecse_unique_values['billingstate'],
							'billingpostcode' 	=> $ecse_unique_values['billingpostcode'],
							'billingcountry' 	=> $ecse_unique_values['billingcountry']
					)
			);
		}
		
	
		/* PROCESS PURCHASE LOG & PRODUCT ROWS */
		//these are tied together because we recalculate totals as much as possible, rather than relying on what's recorded
		if( ($cart_log != null) && ($purch_data != null) ) {
		
			$ecse_cart_rows = array();
			$all_no_shipping = true;
			$total_shipping = 0;
			$subtotal = 0;
			foreach($cart_log as $cart_row) {
				$this_row = array(
					'SKU' 	=> wpsc_product_sku($cart_row['prodid']),
					'ID'	=> $cart_row['prodid'],
					'name' 	=> $cart_row['name'],
					'cost' 	=> $cart_row['price'],
					'qty' 	=> $cart_row['quantity']
				);
				
				$ecse_cart_rows[] = $this_row;
				
				if($cart_row['no_shipping'] != 1) {
					$shipping = $cart_row['pnp'];
					$total_shipping += $shipping;						
					$all_no_shipping = false;
				}
			
				$subtotal += $cart_row['price'] * $cart_row['quantity'];
			}
			$total_shipping += $purch_data['base_shipping'];
			$total_price = $subtotal - $purch_data['discount_value'] + $total_shipping + $purch_data['wpec_taxes_total'];
			
			
			/* update the structured data */
			
			//misc purchase props from WPEC
			$returner['purchase_props'] = array_merge(
					$returner['purchase_props'],
					$purch_data
				);
			//NOTE: We will not be putting currency signs in with the values, so the interface layer will need to add that.
			$returner['total_props'] = array_merge(
					$returner['total_props'],
					array(
						'products_total' 	=> $subtotal,
						'discount_total' 	=> $purch_data['discount_value'],
						'shipping_total' 	=> $total_shipping,
						'tax_total' 		=> $purch_data['wpec_taxes_total'],
						'grand_total' 		=> $total_price
					)
				);
			$returner['shipping_props'] = array_merge(
					$returner['shipping_props'],
					array(
						'track_id' 			=> $purch_data['track_id'],
						'shipping_method' 	=> $purch_data['shipping_method'],
						'shipping_option' 	=> $purch_data['shipping_option'],
						'shipping_total'	=> $total_shipping
					)
			);
			
			$returner['billing_props']['gateway'] = self::get_gateway_name($purch_data['gateway']);
			$returner['product_rows'] = $ecse_cart_rows;
		}
		
		
		/* STORE THE STRUCTURED DATA */
		self::$purchase_data = $returner;
		self::$loop_prod_rows = $returner['product_rows'];
		return $returner;
	}
	
	/**
	 * Convert the internal name for a gateway to a display name
	 * @param unknown_type $internal_name
	 */
	static function get_gateway_name($internal_name='') {
		$payment_gateway_names = get_option( 'payment_gateway_names' );
		if ( isset( $payment_gateway_names[$internal_name] ) && ( $payment_gateway_names[$internal_name] != '' ) ) {
			$display_name = $payment_gateway_names[$internal_name];
		} else {
			switch ( $internal_name ) {
				case "paypal":
				case "paypal_pro":
				case "wpsc_merchant_paypal_pro";
				$display_name = "PayPal";
				break;
		
				case "manual_payment":
					$display_name = "Manual Payment";
					break;
		
				case "google_checkout":
					$display_name = "Google Checkout";
					break;
		
				case "credit_card":
				default:
					$display_name = "Credit Card";
					break;
			}
		}
		if ( $display_name == '' ) {
			$display_name = 'Manual Payment';
		}
		return $display_name;
	}
	
	/**
	 * Retrieve the most recent purchase ID. Used in testing purposes to display the most recent purchase.
	 * @return string or null on failure
	 */
	static function get_recent_purchase_id() {
		global $wpdb;
		$purch_sql = $wpdb->prepare( "SELECT `id` FROM `".WPSC_TABLE_PURCHASE_LOGS."` ORDER BY `id` DESC LIMIT 1" );
		$purch_data = $wpdb->get_row( $purch_sql, ARRAY_A ) ;
		if(is_array($purch_data)) $purch_data = $purch_data['id'];
		return $purch_data;
	}
	
	/**
	 * Whether or not a purchase has been initiated for the template
	 */
	static function has_purchase() {
		return isset(self::$purchase_data);
	}
	
	/**
	 * Set up the current product row for use in a loop
	 */
	static function the_product() {
		if(empty(self::$loop_prod_rows)) {
			self::cleanup_the_product();
		} else {
			ECSE_purchase_product::$curr_prod_row = array_pop(self::$loop_prod_rows);
		}
	}
	
	/**
	 * Reset the product loop (only useful to do multiple loops)
	 */
	static function reset_products() {
		if(isset(self::$purchase_data)) self::$loop_prod_rows = self::$purchase_data['product_rows'];
	}
	
	/**
	 * Remove loop data for the product list
	 */
	private static function cleanup_the_product() {
		self::$loop_prod_rows=null;
		ECSE_purchase_product::$curr_prod_row=null;
	}
	
	
	
	
	/* TEMPLATE FUNCTIONS AND TAGS */
	
	/**
	 * Whether there are product rows to loop thru
	 */
	static function have_products() {
		$returner = ( isset(self::$loop_prod_rows) && (count(self::$loop_prod_rows)>0) );
		if($returner==false) self::cleanup_the_product();
		return $returner;
	}
	
	/**
	 * Get some purchase data from WPEC's core purchase record (contents are a bit random)
	 * @param (string) $label (optional. leave empty to get all the properties as a keyed array)
	 * @return the purchase data asked for, or false if the purchase data has not been retrieved.
	 * label values are per WPEC database column names
	 */
	static function get_the_purchase_prop($label=null) {
		return self::get_group_prop($label,'purchase_props');
	}

	/**
	 * Get some totals data
	 * @param (string) $label (optional. leave empty to get all the totals as a keyed array)
	 * @return the purchase data asked for, or false if the purchase data has not been retrieved.
	 * label values:
	 * 'products_total'
	 * 'discount_total'
	 * 'shipping_total'
	 * 'tax_total'
	 * 'grand_total'
	 */
	static function get_the_total_prop($label=null) {
		return self::get_group_prop($label,'total_props');
	}
	
	/**
	 * Get some billing data
	 * @param (string) $label (optional. leave empty to get all the billing info as a keyed array)
	 * @param (string) $append (optional. Will get added to the output if the output exists.
	 * @return the billing data asked for, or false if the data has not been retrieved.
	 * label values:
	 * 'gateway'
	 * 'billingfirstname'
	 * 'billinglastname'
	 * 'billingaddress'
	 * 'billingcity'
	 * 'billingstate'
	 * 'billingpostcode'
	 * 'billingcountry'
	 */
	static function get_the_billing_prop($label=null, $append='') {
		$returner = self::get_group_prop($label,'billing_props');
		if($returner) $returner.= $append;
		return $returner;
	}
	
	/**
	 * Get some shipping data
	 * @param (string) $label (optional. leave empty to get all the shipping info as a keyed array)
	 * @param (string) $append (optional. Will get added to the output if the output exists.
	 * @return the shipping data asked for, or false if the data has not been retrieved.
	 * label values:
	 * 'track_id'
	 * 'shipping_method'
	 * 'shipping_option'
	 * 'shipping_total'
	 * 'shippingfirstname'
	 * 'shippinglastname'
	 * 'shippingaddress'
	 * 'shippingcity'
	 * 'shippingstate'
	 * 'shippingpostcode'
	 * 'shippingcountry'
	 */
	static function get_the_shipping_prop($label=null, $append='') {
		$returner = self::get_group_prop($label,'shipping_props');
		if($returner) $returner.= $append;
		return $returner;
	}
	
	/**
	 * Get some purchase data
	 * @param (string) $label (optional. leave empty to get all the properties as a keyed array)
	 * @param string $group (optional. leave empty to get all groups as a multi-dimentional keyed array)
	 * @return the purchase data asked for, or false if the purchase data has not been retrieved.
	 */
	private static function get_group_prop($label=null,$group=null) {
		if(isset(self::$purchase_data)) {
			if($group==null) {
				return self::$purchase_data;
			} elseif($label==null) {
				return self::$purchase_data[$group];
			} elseif(empty(self::$purchase_data[$group][$label])) {
				return false;
			} else {
				return self::$purchase_data[$group][$label];
			}
		} else return false;
	}
	
	/**
	 * Get a checkout property by unique name, name or checkout ID.
	 * See WPEC store settings -> checkout tab for unique names, names and field IDs in use for your store.
	 * If a field name is used more than once in your checkout, only the last instance is returned using that retrieval type.
	 * @param string $key (optional. leave empty to get all the properties as a keyed array organised by type)
	 * @param string $type (optional. 'name'/'unique_name'/'id'. default is 'unique_name')
	 * @return the checkout data asked for, or false if the purchase data has not been retrieved
	 */
	static function get_the_checkout_prop($key=null,$type='unique_name') {
		if(isset(self::$purchase_data)) {
			if($key==null) {
				return self::$purchase_data['checkout_values'];
			} else {
				return trim(wp_kses(self::$purchase_data['checkout_values'][$type][$key],array()));
			}
		} else return false;
	}
	
	/**
	 * Get the purchase ID
	 */
	static function get_the_purchase_ID() {
		if(isset(self::$purchase_id)) {
			return self::$purchase_id;
		} else return false;
	}
	
}


/**
 * To be used in a purchase report product loop
 * @author JS
 *
 */
class ECSE_purchase_product {
	
	static $curr_prod_row;
	
	/**
	 * The current row product SKU
	 * @return (string) SKU
	 */
	static function get_the_SKU() {
		return self::$curr_prod_row['SKU'];
	}
	
	/**
	 * The current row product ID
	 * @return (int) ID
	 */
	static function get_the_ID() {
		return self::$curr_prod_row['ID'];
	}
	
	/**
	 * The current row product name
	 * @return (string) name
	 */
	static function get_the_name() {
		return self::$curr_prod_row['name'];
	}
	
	/**
	 * The current row product cost per item
	 * @return (string) cost with currency
	 */
	static function get_the_cost() {
		return wpsc_currency_display( self::$curr_prod_row['cost'] );
	}
	
	/**
	 * The current row product subtotal
	 * @return (string) cost with currency
	 */
	static function get_the_cost_subtotal() {
		return wpsc_currency_display( self::$curr_prod_row['cost'] * self::$curr_prod_row['qty'] );
	}
	
	/**
	 * The current row product QTY
	 * @return (int) QTY
	 */
	static function get_the_QTY() {
		return self::$curr_prod_row['qty'];
	}
	
	/**
	 * The URL of the current row product image
	 * @param (int) $w image with
	 * @param (int) $h image height
	 * @return string the URL of the image (HTML element needs to be constructed by the theme)
	 */
	static function get_the_image($w=30,$h=30) {
		if(!isset(self::$curr_prod_row['image'])) self::$curr_prod_row['image']=wpsc_the_product_thumbnail($w,$h,self::$curr_prod_row['ID']);
		return self::$curr_prod_row['image'];
	}
	
	/**
	 * The URL of the current row product
	 */
	static function get_the_permalink() {
		return get_permalink(self::$curr_prod_row['ID']);
	}
	
	/**
	 * An array of downloads for this product, or empty array if no active downloads available. Each row is in the format array('url','name')
	 */
	static function get_the_downloads() {
		global $wpdb;
		$download_data = $wpdb->get_results( $wpdb->prepare( "SELECT *
				FROM `" . WPSC_TABLE_DOWNLOAD_STATUS . "`
				WHERE `active`='1'
				AND `purchid` = %d
				AND `product_id` = %d", ECSE_purchase::$purchase_id, self::$curr_prod_row['ID'] ), ARRAY_A );
		
		$links = array( );
		if ( count( $download_data ) > 0 ) {
			foreach ( $download_data as $single_download ) {
				$file_data = get_post( $single_download['product_id'] );
				// if the uniqueid is not equal to null, its "valid", regardless of what it is
				$argsdl = array(
						'post_type' => 'wpsc-product-file',
						'post_parent' => $single_download['product_id'],
						'numberposts' => -1,
						'post_status' => 'all',
				);
		
				$download_file_posts = (array)get_posts( $argsdl );
		
				foreach((array)$download_file_posts as $single_file_post){
					if($single_file_post->ID == $single_download['fileid']){
						$current_Dl_product_file_post = $single_file_post;
						break;
					}
				}
				$file_name = $current_Dl_product_file_post->post_title;
		
				if ( $single_download['uniqueid'] == null )
					$links[] = array( "url" => site_url( "?downloadid=" . $single_download['id'] ), "name" => $file_name );
				else
					$links[] = array( "url" => site_url( "?downloadid=" . $single_download['uniqueid'] ), "name" => $file_name );
		
			}
		}
		return $links;
	}
	
}



?>