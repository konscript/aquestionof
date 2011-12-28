<?php

// Setup custom theme for Hybrid.
add_action( 'after_setup_theme', 'custom_theme_setup', 10 );
function custom_theme_setup() {

	// Add theme support for core framework features.
	add_theme_support( 'hybrid-core-seo' );
	add_theme_support( 'hybrid-core-template-hierarchy' );
	add_theme_support( 'hybrid-core-menus' );
	add_theme_support( 'hybrid-core-theme-settings' ); // Enables settings page
	//add_theme_support( 'hybrid-core-sidebars' );
	//add_theme_support( 'hybrid-core-widgets' );
	//add_theme_support( 'hybrid-core-shortcodes' );
	//add_theme_support( 'hybrid-core-post-meta-box' );
	//add_theme_support( 'hybrid-core-drop-downs' );

	// Add theme support for framework extensions.
	add_theme_support( 'get-the-image' );
	add_theme_support( 'post-layouts' );
	add_theme_support( 'post-stylesheets' );
	//add_theme_support( 'breadcrumb-trail' );
	//add_theme_support( 'loop-pagination' );

	// Add theme support for WordPress features.
	add_theme_support( 'automatic-feed-links' );

	// Register navigation
	register_nav_menu( 'footer', 'Quicklinks in the footer' );
	register_nav_menu( 'masterbar', 'Masterbar in the top' );

}

// Register all resources (js and css)
add_action('init', 'register_resources');
function register_resources() {

	global $compress_scripts, $concatenate_scripts;
	$compress_scripts = 1;
	$concatenate_scripts = 1;
	define('ENFORCE_GZIP', true);
	define('COMPRESS_CSS', true);

	if( !is_admin()){
		// Main screen stylesheet
		wp_register_style( 'screen', THEME_URI . '/resources/css/screen.css', array(), false, 'all');
		wp_enqueue_style( 'screen' );
	
		// Fetch latest jQuery
    	wp_deregister_script( 'jquery' );
    	//wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js', array(), '1.6');
			wp_register_script('jquery', (THEME_URI . '/resources/js/jquery-1.7.1.min.js'), false, '1.7.1'); 
    	wp_enqueue_script( 'jquery' );
	
		// Javascript plugins
		wp_register_script( 'on_hash_change', THEME_URI . '/resources/js/jquery.onhashchange.min.js', array(), false, true);
		wp_enqueue_script( 'on_hash_change' );
		wp_register_script( 'jquery_masonry', THEME_URI . '/resources/js/jquery.masonry.min.js', array('jquery'), false, true);
		wp_enqueue_script( 'jquery_masonry' );
		wp_register_script( 'jquery_nivo_slider_pack', THEME_URI . '/resources/js/jquery.nivo.slider.pack.js', array('jquery'), false, true);
		wp_enqueue_script( 'jquery_nivo_slider_pack' );
		wp_register_script( 'jquery_tinysort', THEME_URI . '/resources/js/jquery.tinysort.min.js', array('jquery'), false, true);
		wp_enqueue_script( 'jquery_tinysort' );
		wp_register_script( 'jquery_lazyload', THEME_URI . '/resources/js/jquery.lazyload.min.js', array('jquery'), false, true);
		wp_enqueue_script( 'jquery_lazyload' );
	
		// Main screen javascript
		wp_register_script( 'screen', THEME_URI . '/resources/js/screen.js', array('jquery'));
		wp_enqueue_script( 'screen' );
	}
	
}    

// Create new multiple post thumbnails for the posts (requires the associated plugin)
add_action( 'init', 'multiple_post_thumbnail' );
function multiple_post_thumbnail() {
	if (class_exists('MultiPostThumbnails')) {
		$thumb = new MultiPostThumbnails(array(
			'label' => 'Sidebar Image',
			'id' => 'secondary-image',
			'post_type' => 'post'
			)
		);
		add_image_size('post-secondary-image-thumbnail', 230, 390);
	}
}

// Register the priority taxonomy
add_action( 'init', 'build_taxanomies' );
function build_taxanomies() {
	register_taxonomy(
		'priority',
		array(
			'post', 
			'wpsc-product'),
		array(
			'hierarchical' => true,
			'label' => 'Priority',
			'query_var' => true,
			'rewrite' => true)
	);	
}

// Create a nivo gallery from product pages
function nivo_get_images($product_id = null, $size = 'medium', $limit = '0', $offset = '0') {
	global $post;
 
	$images = get_children(array(
		'post_parent' => $product_id,
		'post_status' => 'inherit', 
		'post_type' => 'attachment', 
		'post_mime_type' => 'image', 
		'order' => 'ASC', 
		'orderby' => 'menu_order ID'));
 
	if ($images) {
 
		$num_of_images = count($images);
 
		if ($offset > 0) : $start = $offset--; else : $start = 0; endif;
		if ($limit > 0) : $stop = $limit+$start; else : $stop = $num_of_images; endif;
 
		$i = 0;
		foreach ($images as $image) {
			if ($start <= $i and $i < $stop) {
				$img_title = $image->post_title;   // title.
				$img_description = $image->post_content; // description.
				$img_caption = $image->post_excerpt; // caption.
				$img_url = wp_get_attachment_url($image->ID); // url of the full size image.
				$preview_array = image_downsize( $image->ID, $size );
	 			$img_preview = $preview_array[0]; // thumbnail or medium image to use for preview.
	 			?>
					<img id="product_image_<?php echo $product_id . '_' . $i ; ?>" class="product_image" src="<?php echo $img_preview; ?>" alt="<?php echo $img_caption; ?>" title="<?php echo $img_caption; ?>" />
				<?
				}
			$i++;
		} 
	}
}

// Outputs custom colors set in the admin
function customColors() {
	echo '
		<style type="text/css">

			.custom-color-1,
			div.custom-color-1,
			.wpsc_buy_button_container input.wpsc_buy_button,
			.shop-sale .product-price {
				background-color: ' . hybrid_get_setting( 'custom-color-1' ) . '; }
				
			.custom-color-2,
			a.custom-color-2,
			.master-shoppingbag a.custom-color-2,
			ul#masterbar-menu.custom-color-2 li a,
			.wpsc_buy_button_container input.wpsc_buy_button {
				color: ' . hybrid_get_setting( 'custom-color-2' ) . '; }

		</style>';
}

// For WPEC products: will provide a correct product image for the FB likes
function metaOpenGraph() {
	global $wp_query;
	$id = $wp_query->get_queried_object_id();	
	$post = $wp_query->post;
	echo "<meta property='fb:admins' content='526410768,1060831121' />";
	if ( is_singular() ) {
		echo "<meta property='og:title' content='" . $post->post_title . "' />";
		echo "<meta property='og:url' content='" . get_permalink( $id ) . "' />";	
		echo "<meta property='og:type' content='article' />";	
		echo "<meta property='og:description' content='" . strip_tags($post->post_content) . "' />";
		$postThumbs = wp_get_attachment_image_src(get_post_thumbnail_id( $id ));
		if ( wpsc_the_product_thumbnail() ) {
			echo "<meta property='og:image' content='" . wpsc_the_product_thumbnail(get_option('product_image_width'),get_option('product_image_height'),'','single') . "' />";	
		} else {
			echo "<meta property='og:image' content='".$postThumbs[0]."' />";
		}
	} else if ( is_front_page() && is_home() ) {
		echo "<meta property='og:title' content='" . get_bloginfo( 'name' ) . "' />";		
		echo "<meta property='og:description' content='" . get_bloginfo( 'description' ) . "' />";
		echo "<meta property='og:image' content='".THEME_URI . '/resources/images/logo-new.png'."' />";
	}
}

// Set own title on site
function custom_document_title() {
	
	global $wp_query;

	$domain = 'hybrid';
	$separator = ' - ';
	
	if ( is_front_page() && is_home() )
		$doctitle = get_bloginfo( 'name' ) . $separator . get_bloginfo( 'description' );

	elseif ( is_home() || is_singular() ) {
		$id = $wp_query->get_queried_object_id();

		$doctitle = get_post_meta( $id, 'Title', true );

		if ( !$doctitle && is_front_page() )
			$doctitle = get_bloginfo( 'name' ) . $separator . get_bloginfo( 'description' );
		elseif ( !$doctitle )
			$doctitle = get_post_field( 'post_title', $id );
	}

	elseif ( is_archive() ) {

		if ( is_category() || is_tag() || is_tax() ) {
			$term = $wp_query->get_queried_object();
			$doctitle = $term->name;
		}

		elseif ( is_author() )
			$doctitle = get_the_author_meta( 'display_name', get_query_var( 'author' ) );

		elseif ( is_date () ) {
			if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
				$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g:i a', $domain ) ) );

			elseif ( get_query_var( 'minute' ) )
				$doctitle = sprintf( __( 'Archive for minute %1$s', $domain ), get_the_time( __( 'i', $domain ) ) );

			elseif ( get_query_var( 'hour' ) )
				$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'g a', $domain ) ) );

			elseif ( is_day() )
				$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'F jS, Y', $domain ) ) );

			elseif ( get_query_var( 'w' ) )
				$doctitle = sprintf( __( 'Archive for week %1$s of %2$s', $domain ), get_the_time( __( 'W', $domain ) ), get_the_time( __( 'Y', $domain ) ) );

			elseif ( is_month() )
				$doctitle = sprintf( __( 'Archive for %1$s', $domain ), single_month_title( ' ', false) );

			elseif ( is_year() )
				$doctitle = sprintf( __( 'Archive for %1$s', $domain ), get_the_time( __( 'Y', $domain ) ) );
		}
	}

	elseif ( is_search() )
		$doctitle = sprintf( __( 'Search results for &quot;%1$s&quot;', $domain ), esc_attr( get_search_query() ) );

	elseif ( is_404() )
		$doctitle = __( '404 Not Found', $domain );

	/* If paged. */
	if ( ( ( $page = $wp_query->get( 'paged' ) ) || ( $page = $wp_query->get( 'page' ) ) ) && $page > 1 )
		$doctitle = sprintf( __( '%1$s Page %2$s', $domain ), $doctitle . $separator, $page );

	/* Apply the wp_title filters so we're compatible with plugins. */
	$doctitle = apply_filters( 'wp_title', $doctitle, $separator, '' );

	echo apply_atomic( 'document_title', esc_attr( $doctitle ) );
	
	/* Append site name at the end for pages */
	if ( !is_front_page() && !is_home() ) {
		$doctitle .= $separator . get_bloginfo( 'name' );
	}	
	
}
?>