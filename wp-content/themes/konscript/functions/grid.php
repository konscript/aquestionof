<?php

/**
 * gridInit
 * Main function, call to require and echo the grid
 */
function gridInit($type) {
	
	// Setup vars
	global $post;
	$items = array();
	$featured_items = array();
	
	// Only fetch posts if not limited to category fetch
	if ($type == 'all') {	
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
	
	if ($type == 'all') {	
				
		// Add Facebook like fanbox to items array
		$itemsFeatured[] = '
				<div id="post-fbbox" class="box col12 row12 category-updates category-updates-social priority-featured priority-frontpage">
					<a href="#">
						<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Faquestionof&amp;colorscheme=dark&amp;show_faces=true&amp;stream=true&amp;header=false&amp;width=468&amp;height=588" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:470px; height:590px;" allowTransparency="true"></iframe>					
					</a>
				</div>' . "\n";
				
		// Add Mailchimp newsletter signup form box
		$items[] = '
			<div id="post-mcbox" class="box col6 row4 category-updates category-updates-social priority-frontpage">
				<!-- Begin MailChimp Signup Form -->
				<div id="mc_embed_signup">
					<form action="http://aquestionof.us1.list-manage.com/subscribe/post?u=4b158f023b1059770a5f4ff44&amp;id=b1ea1a5e55" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
						<input type="text" value="Fill in e-mail" name="EMAIL" style="width: 100px">
						<input type="submit" class="emailbutton" value="" name="subscribe" id="mce-embedded-subscribe">
					</form>
				</div>
				<!--End mc_embed_signup-->
			</div>' . "\n";
	}	
	
	// Shuffle all items and merge them with the featured
	shuffle($items);
	$itemsOut = array_merge($itemsFeatured, $items);
	
	// Output all the items to the DOM
	if (!empty($itemsOut)) {	
		foreach ($itemsOut as $itemOut) {
			echo $itemOut;
		}
	}	
}

/**
 * postTaxonomies
 * Will return an array of flattened css-class friendly entries of taxonomies and their terms
 * E.g. Category and Priority
 */
function postTaxonomies($postId) {
	
	// Define the terms to run through
	$taxonomyList = array('category', 'priority','wpsc_product_category');
	$resultArray = array();
	
	// Run through the categories, the priority etc.
	foreach($taxonomyList as $taxonomy) {
		$taxonomyTerms = get_the_terms($postId, $taxonomy);
		// If the post/product has any selected items in the term, run through each item		
		if(!empty($taxonomyTerms)){
			foreach($taxonomyTerms as $term) {
				// If the current item has a parent (e.g. category), then fetch that and prepare for prefix
				if ($term->parent != 0) {
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
				// Add the entry to the resulting array in the current format: "category-concept-sustainability"
				$resultArray[] = $taxonomyOut . "-" . $termParentAdd . $term->slug;
			}
			// Condition for products in WPEC, adds a default shop category to the product
			if ($taxonomy == 'wpsc_product_category') {
				$resultArray[] = 'category-shop';
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
		$thumbnail = '<img src="' . wpsc_the_product_thumbnail(get_option('product_image_width', '', 'default'), get_option('product_image_height'), '', 'default') . '" />';
	} else {
		$thumbnail = get_the_post_thumbnail( $postId, 'large' );
	}
		
	// Begin output
	$o =  '<div id="' . $divId . '-' . $postId . '" class="box col' . $col . ' row' . $row . ' ' . $taxonomiesClass . '">';
		if (array_search('priority-video', $postTaxonomies) != false) { 
			$o .= get_the_content();
		} else {
			$o .= '<a href="' . $postFields['boxLink'] . '" alt="' . get_the_title() . '">';
			$o .= $thumbnail;
				$o .=  '<div class="meta">
							 <div class="' . $divId . '-title">
								' . get_the_title() . '
							 </div>';
				if ($postType == "wpsc-product") {		
					$o .=	'<div class="product-price">
								' . wpsc_product_variation_price_available($postId) . '
							 </div>';
				}
				$o .= '</div>
					</a>';
		}
	$o .= '</div>' . "\n";

	return $o;
}

?>