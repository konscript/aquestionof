<?php
/**
 * Category Taxonomy Template
 *
 * This template is loaded when viewing a category archive and replaces the default 
 * category.php template.  It can also be overwritten for individual categories using
 * taxonomy-category-$term.php.
 */

get_header(); ?>

	<div id="content" class="hfeed content">	

		<div id="grid">
			
			<?php gridInit('category',single_cat_title( '', false )); ?>

		</div>
			
	</div><!-- .content .hfeed -->

<?php get_footer(); ?>