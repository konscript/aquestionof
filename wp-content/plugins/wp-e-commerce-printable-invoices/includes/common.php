<?php
/*

Filename: common.php
Description: common.php loads commonly accessed functions across the Visser Labs suite.

- wpsc_vl_plugin_update_prepare
- wpsc_vl_migrate_prefix_options

- wpsc_vl_product_price
- wpsc_vl_get_product_price
- wpsc_vl_currency_display
- wpsc_vl_get_currency_display

- wpsc_get_action
- wpsc_get_major_version
- wpsc_get_minor_version

*/

if( is_admin() ) {

	/* Start of: WordPress Administration */

	include_once( 'common-update.php' );
	include_once( 'common-dashboard_widgets.php' );

	if( !function_exists( 'wpsc_vl_plugin_update_prepare' ) ) {

		function wpsc_vl_plugin_update_prepare( $action, $args ) {

			global $wp_version;

			return array(
				'body' => array(
					'action' => $action,
					'request' => serialize( $args ),
					'api-key' => md5( get_bloginfo( 'url' ) ),
					'site' => get_bloginfo( 'url' )
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' )
			);	

		}

	}

	if( !function_exists( 'wpsc_vl_migrate_prefix_options' ) ) {

		/**
		* Migrates outdated WordPress options to their new Plugin prefix
		*
		* @param array $options List of WordPress options.
		* @param string $old_prefix Existing Plugin prefix.
		* @param string $new_prefix Existing Plugin prefix.
		* @return boolean "No! Try not. Do. Or do not. There is no try." - Yoda
		*/
		function wpsc_vl_migrate_prefix_options( $options, $old_prefix, $new_prefix ) {

			foreach( $options as $option ) {
				if( $option['old_name'] && isset( $option['new_name'] ) ) {
					add_option( $new_prefix . '_' . $option['new_name'], get_option( $old_prefix . '_' . $option['old_name'] ) );
					$success = true;
				} else if( isset( $option['old_name'] ) ) {
					add_option( $new_prefix . '_' . $option['old_name'], get_option( $old_prefix . '_' . $option['old_name'] ) );
					$success = true;
				}
				delete_option( $old_prefix . '_' . $option['old_name'] );
			}
			if( $success )
				return true;

		}

	}

	/* End of: WordPress Administration */

}

if( !function_exists( 'wpsc_vl_product_price' ) ) {

	function wpsc_vl_product_price( $product_id = null, $echo = true ) {

		if( !$product_id )
			$product_id = wpsc_the_product_id();
		$args = array(
			'product_id' => $product_id,
			'currency_display' => $echo,
			'echo' => $echo
		);
		return wpsc_vl_get_product_price( $args );

	}

}

if( !function_exists( 'wpsc_vl_get_product_price' ) ) {

	function wpsc_vl_get_product_price( $args = null ) {

		$defaults = array(
			'product_id' => wpsc_the_product_id(),
			'currency_display' => true,
			'echo' => false
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		switch( wpsc_get_major_version() ) {

			case '3.7':
				$output = calculate_product_price( $product_id );
				break;

			case '3.8':
				$output = wpsc_calculate_price( $product_id );
				break;

		}
		if( $currency_display )
			$output = wpsc_vl_currency_display( $output, false );

		if( $echo )
			echo $output;
		else
			return $output;

	}

}

if( !function_exists( 'wpsc_vl_currency_display' ) ) {

	function wpsc_vl_currency_display( $price = null, $echo = true ) {

		if( !isset( $price ) )
			$price = wpsc_vl_product_price();
		$args = array(
			'price' => $price,
			'echo' => $echo
		);
		if( $echo )
			wpsc_vl_get_currency_display( $args );
		else
			return wpsc_vl_get_currency_display( $args );

	}

}

if( !function_exists( 'wpsc_vl_get_currency_display' ) ) {

	function wpsc_vl_get_currency_display( $args = null ) {

		$defaults = array(
			'price' => wpsc_vl_get_product_price( array( 'currency_display' => false ) ),
			'echo' => false
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );

		switch( wpsc_get_major_version() ) {

			case '3.7':
				$output = nzshpcrt_currency_display( $price, true, true );
				break;

			case '3.8':
				$output = wpsc_currency_display( $price );
				break;

		}
		if( $echo )
			echo $output;
		else
			return $output;

	}

}

if( !function_exists( 'wpsc_get_action' ) ) {

	function wpsc_get_action( $switch = false ) {

		if( $switch ) {

			if( isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else if( !isset( $action ) && isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else
				$action = false;

		} else {

			if( isset( $_POST['action'] ) )
				$action = $_POST['action'];
			else if( !isset( $action ) && isset( $_GET['action'] ) )
				$action = $_GET['action'];
			else
				$action = false;

		}
		return $action;

	}

}

if( !function_exists( 'wpsc_get_major_version' ) ) {

	function wpsc_get_major_version() {

		$version = get_option( 'wpsc_version' );
		return substr( $version, 0, 3 );

	}

}

if( !function_exists( 'wpsc_get_minor_version' ) ) {

	function wpsc_get_minor_version() {

		$version = WPSC_VERSION;
		return substr( $version, 0, 5 );

	}

}
?>