<?php
/**
 * Single Post Template
 *
 * This template is the default post template. It is used to display content when someone is viewing a
 * singular view of a post ('post' post_type) unless another template overrules this one.
 */

get_header(); ?>

	<div id="content" class="hfeed content">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" class="<?php hybrid_entry_class(); ?>">
				
				<div class="entry-wrapper">
					
					<div class="entry-image">
						<?php 
						if (class_exists('MultiPostThumbnails') && MultiPostThumbnails::has_post_thumbnail('post', 'secondary-image')) {
							MultiPostThumbnails::the_post_thumbnail('post', 'secondary-image', $post->ID, 'medium');	
						} else {
							the_post_thumbnail( 'medium' );
						} ?>
					</div>
				
					<div class="entry-content entry-with-sidebar">
					
						<div class="entry-header"><h1><?php the_title(); ?></h1></div>
						<?php the_content(); ?>					
					
						<?php $custom_fields = get_post_custom($post->ID); ?>

						<?php // If related boxes, output header 
							if (isset($custom_fields['related-post']) || isset($custom_fields['related-product'])) {
									echo '<div class="related-post-container"><div class="related-post-title">Related</div>';
							}
						?>

						<?php // Related Posts box						
							if (isset($custom_fields['related-post'])) {
								
								$tmp_post = $post;
								$related_post_field = $custom_fields['related-post'];
								$related_post = get_post($related_post_field[0]);
							
								$related_post_custom_fields = get_post_custom($related_post->ID);
								if (isset($related_post_custom_fields['height'])) { $item_height = $related_post_custom_fields['height'][0] * 4; }
								else { $item_height = 4; }
							
								$related_post = '
										<div class="related-post box row' . $item_height . '">
											<a href="' . get_permalink($related_post->ID) . '">
												'.  get_the_post_thumbnail($related_post->ID, 'large') .'
												<div class="meta">
													<div class="post-title">
														' . get_the_title($related_post->ID) . '
													</div>
												</div>
											</a>
										</div>' . "\n";
								echo $related_post;
								$post = $tmp_post;						
						} ?>
					
						<?php // Related Posts box						
							if (isset($custom_fields['related-product'])) {
								
								$tmp_post = $post;
								$related_post_field = $custom_fields['related-product'];
								$related_post = get_post($related_post_field[0]);
							
								$related_post_custom_fields = get_post_custom($related_post->ID);
								if (isset($related_post_custom_fields['height'])) { $item_height = $related_post_custom_fields['height'][0] * 4; }
								else { $item_height = 4; }
							
								$related_post = '
										<div class="related-product box row' . $item_height . '">
											<a href="' . get_permalink($related_post->ID) . '">
												'.  get_the_post_thumbnail($related_post->ID, 'default') .'
												<div class="meta">
													<div class="product-title">
														' . get_the_title($related_post->ID) . '
													</div>
													<div class="product-price">
														' . wpsc_product_variation_price_available($related_post->ID) . '
													</div>																			
												</div>
											</a>
										</div>' . "\n";
								echo $related_post;
								$post = $tmp_post;						
						} ?>
					
						<?php // If related boxes, output ending div to header 
							if (isset($custom_fields['related-post']) || isset($custom_fields['related-product'])) {
									echo '</div>';
							}
						?>					
													
					</div><!-- .entry-content -->
				
				</div>

			</div><!-- .hentry -->

			<?php //comments_template( '/comments.php', true ); ?>

			<?php endwhile; ?>

		<?php else : ?>

			<p class="no-data">
				<?php _e( 'Apologies, but no results were found.', 'hybrid' ); ?>
			</p><!-- .no-data -->

		<?php endif; ?>

	</div><!-- .content .hfeed -->

<?php get_footer(); ?>