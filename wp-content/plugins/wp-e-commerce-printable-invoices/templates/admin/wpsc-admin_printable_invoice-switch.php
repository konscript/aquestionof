<?php if( wpsc_pi_has_previous_sale() || wpsc_pi_has_next_sale() ) { ?>
<div id="wpadminbar" class="nojq nojs" role="navigation">
	<div class="quicklinks">
		<ul id="wp-admin-bar-root-default" class="ab-top-menu">
			<li><a href="<?php echo $wpsc_pi['template']['switch']['return_to_sale_url']; ?>" class="ab-item"><?php _e( 'Return to Sale', 'wpsc_pi' ); ?></a></li>
		</ul>
		<ul id="wp-admin-bar-top-secondary" class="ab-top-secondary ab-top-menu">
	<?php if( wpsc_pi_has_next_sale() ) { ?>
			<li><a href="<?php echo $wpsc_pi['template']['switch']['sale_next_url']; ?>" class="ab-item"><?php _e( 'Next Invoice', 'wpsc_pi' ); ?> &raquo;</a></li>
	<?php } ?>
	<?php if( wpsc_pi_has_previous_sale() ) { ?>
			<li><a href="<?php echo $wpsc_pi['template']['switch']['sale_previous_url']; ?>" class="ab-item">&laquo; <?php _e( 'Previous Invoice', 'wpsc_pi' ); ?></a></li>
	<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>