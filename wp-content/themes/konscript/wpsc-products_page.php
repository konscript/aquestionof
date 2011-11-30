<?php
/**
 * Template Name: Shop
 */

/*
$location = get_bloginfo('siteurl').'/category/shop';
wp_redirect( $location, 301 );
exit;
*/

get_header(); ?>

<div id="content" class="hfeed content">	

	<div id="grid">
		
		<?php gridInit('category', 'Shop'); ?>

	</div>
		
</div><!-- .content .hfeed -->

<?php get_footer(); ?>