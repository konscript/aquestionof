<?php
/**
 * Functions
 *
 * Will reach and require the necessary functions and libraries
 */

// Load Hybrid core theme framework.
require_once( trailingslashit( TEMPLATEPATH ) . 'hybrid-core/hybrid.php' );
$theme = new Hybrid();

// Load the core functions
require_once( trailingslashit( TEMPLATEPATH ) . 'functions/core.php' );

// Load the grid functions
require_once( trailingslashit( TEMPLATEPATH ) . 'functions/grid.php' );

// Load the shop functions
require_once( trailingslashit( TEMPLATEPATH ) . 'functions/shop.php' );

// Load the admin-only functions
if (is_admin()) {
	require_once( trailingslashit( TEMPLATEPATH ) . 'functions/admin.php' );
}
?>