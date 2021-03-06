<?php

/**
 * flush the cache and delete all persistent copies (rebuild next load)
 */
add_action('save_post', 'flushGridCache');
function flushGridCache() {
	$dir = TEMPLATEPATH . '/cache';
	$objects = scandir($dir); 
	foreach ($objects as $object) { 
		if ($object != "." && $object != ".." && $object != ".gitignore") { 
			if (filetype($dir."/".$object) == "dir") {
				// do nothing, shouldn't be there
			} else {
				unlink($dir."/".$object);
			}
	  } 
	} 
	reset($objects);
	//createGridCache('home');
}

/**
 * will rebuild cache every half hour, and if user is logged in
 */
function gridInit($type, $tax = 'All'){

	$cache_file = TEMPLATEPATH.'/cache/gridCache_'.$type.'_'.$tax;
	$cache_life = '3600'; //caching time, in seconds
	$filemtime = @filemtime($cache_file);  // returns FALSE if file does not exist
	$cache_expired = (time() - $filemtime >= $cache_life);
	
	if($_GET["cache_debug"]){
		echo "cache debug:";
		echo $cache_file."<br>";
		echo $cache_life."<br>";	
		echo $filemtime."<br>";	
		var_dump($cache_expired);
		var_dump(is_user_logged_in());
	}
		
	// rebuild grid cache
	if (!$filemtime || $cache_expired){
		echo"<!-- rebuilding cache (expired) -->";
		flushGridCache();
		createGridCache($type, $tax);
	}
	
	// load grid from cache
	$serialized_array = file_get_contents($cache_file);
	$itemsOut = unserialize($serialized_array);
	
	// output grid
	foreach ($itemsOut as $itemOut) {
		echo $itemOut;
	}	
}

/**
 * createGridCache
 * Main function, call to require and echo the grid
 */
function createGridCache($type, $tax = 'All') {
	
	// Setup vars
	global $post;
	$items = array();
	$featured_items = array();
	
	// Only fetch posts if not limited to category fetch
	if ($type == 'home') {	
		$queryArgs = array(
			'post_type' 		=> array('post','wpsc-product'),
			'post_parent' 		=> 0,
			'posts_per_page' 	=> 9999
		);
		query_posts($queryArgs);
	} 

	// Get all posts from WP and loop through each post/product
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		// Save meta values and references
		$postId = $post->ID;
		$postType = $post->post_type;
		$postTaxonomies = postTaxonomies($postId);
		$postFields = postFields($postId, $postType);

		// Generate output-ready post template
		$postOutTemplate = processPostOutput($postId, $postType, $postTaxonomies, $postFields);
		
		// Determine sorting rank based on featured-flag
		if (array_search('priority-featured', $postTaxonomies)) {
			$itemsFeatured[] = $postOutTemplate;
		} else {
			$items[] = $postOutTemplate;
		}

	endwhile; endif;
	
	// Shuffle all items and merge them with the featured
	shuffle($items);
	if (is_array($itemsFeatured)) {
		$itemsOut = array_merge($itemsFeatured, $items);
	} else {
		$itemsOut = $items;
	}
	
	// Output all the items to the DOM
	if (!empty($itemsOut)) {	
	
		$filename = TEMPLATEPATH.'/cache/gridCache_'.$type.'_'.$tax;
		$serialized_array = serialize($itemsOut);
		file_put_contents($filename, $serialized_array);
	}	
}

/**
 * postTaxonomies
 * Will return an array of flattened css-class friendly entries of taxonomies and their terms
 * E.g. Category and Priority
 */
function postTaxonomies($postId) {
	
	// Define the terms to run through
	$taxonomyList = array('category','priority','wpsc_product_category');
	$resultArray = array();
	
	// Run through the categories, the priority etc.
	foreach($taxonomyList as $taxonomy) {
		$taxonomyTerms = get_the_terms($postId, $taxonomy);
		// If the post/product has any selected items in the term, run through each item		
		if(!empty($taxonomyTerms)){
			// Condition for products in WPEC, adds a default shop category to the product
			if ($taxonomy == 'wpsc_product_category') {
				$resultArray[] = 'category-shop';
			}			
			foreach($taxonomyTerms as $term) {
				// If the current item has a parent (e.g. category), then fetch that and prepare for prefix
				if ($term->parent != 0 && $term->taxonomy != 'wpsc_product_category') {
					$termParent = get_term( $term->parent, $taxonomy );
					$termParentAdd = $termParent->slug . "-";
				} else {
					$termParentAdd = "";
				}
				// Condition for products in WPEC, rewrites the category name
				$taxonomyOut = $taxonomy;
				if ($taxonomy == 'wpsc_product_category') {
					$taxonomyOut = 'shop';
				}
				if ($term->slug == 'sale') {
					array_splice($resultArray, array_search('category-shop', $resultArray), 1);
				}			
				// Add the entry to the resulting array in the current format: "category-concept-sustainability"
				$resultArray[] = $taxonomyOut . "-" . $termParentAdd . $term->slug;
			}
		}
	}
	return $resultArray;
}

/**
 * postFields
 * Will return an array of flattened css-class friendly entries of custom fields values
 */
function postFields($postId, $postType) {
	
	$postPermalink = get_permalink($postId);
	$postCustomFields = get_post_custom($postId);
	$resultArray = array(
		'boxLink' => $postPermalink,
		'height' => '4',
		'width' => '6'
	);
	
	if($postType == "post") {
		// Anchor Mode: Check if post is in Link Mode and set anchor href accordingly
		if (isset($postCustomFields['link-mode'][0]) && $postCustomFields['link-mode'][0] == "enabled") {
			$resultArray['boxLink'] = $postCustomFields['link-destination'][0];
		}
		// Set width/height if specified
		if (isset($postCustomFields['width'])) { 
			$resultArray['width'] = $postCustomFields['width'][0] * 6; }
		if (isset($postCustomFields['height'])) { 
			$resultArray['height'] = $postCustomFields['height'][0] * 4; }
	}
	
	if($postType == "wpsc-product") {
		// Set width/height to product defaults
		$resultArray['height'] = "8";
		$resultArray['width'] = "6";		
	}
	return $resultArray;
	
}

/**
 * processPostOutput
 * Create the output ready item-set in HTML 
 */
function processPostOutput($postId, $postType, $postTaxonomies, $postFields) {
	
	// Flatten the array to a string that
	$taxonomiesClass = join( ' ', $postTaxonomies );	
	
	// Div #ID
	$divId = 'post';
	if ($postType == 'wpsc-product') { $divId = 'product'; }
	
	// Width and Height
	$col = $postFields['width'];
	$row = $postFields['height'];
	
	// Thumbnail
	if ($postType == 'wpsc-product') {
		$thumbnail = wpsc_the_product_thumbnail(get_option('product_image_width', '', 'default'), get_option('product_image_height'), '', 'default');
	} else {
		$post_thumbnail_id = get_post_thumbnail_id($postId);
		$post_thumbnail_src = wp_get_attachment_image_src( $post_thumbnail_id, 'large');	
		$thumbnail = $post_thumbnail_src[0];
	}
		
	// Begin output
	$o =  '<div id="' . $divId . '-' . $postId . '" rel="' . get_the_time('ymd') . '" class="box col' . $col . ' row' . $row . ' ' . $taxonomiesClass . '">';
		if (in_array('priority-video', $postTaxonomies)) { 
			$o .= get_the_content();
		} else {
			$o .= '<a href="' . $postFields['boxLink'] . '" alt="' . get_the_title() . '">';
			$o .= '<noscript><img src="'.$thumbnail.'" alt="'. get_the_title() .'" /></noscript>';
			$o .= '<img src="'. THEME_URI .'/resources/images/loader-image.gif'.'" alt="'. get_the_title() .'" data-original="' . $thumbnail . '" />';			
			$o .=  '<div class="meta">
							 <div class="' . $divId . '-title">
								' . get_the_title() . '
							 </div>';
				if ($postType == "wpsc-product") {		
					$o .=	'<div class="product-price">';
						if (in_array('shop-sale', $postTaxonomies)) {
							$o .= 'SALE ';
						} 
					$o .= wpsc_product_variation_price_available($postId) . '
							 </div>';
				}
				$o .= '</div>
					</a>';
		}
	$o .= '</div>' . "\n";

	return $o;
}

?>
