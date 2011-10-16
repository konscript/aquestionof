<?php

/**
 * Will create a meta box on the theme settings page and add validation/sanitization
 */
add_action( 'admin_menu', 'custom_theme_admin_setup' );
function custom_theme_admin_setup() {

	$prefix = hybrid_get_prefix();
	// Create a settings meta box only on the theme settings page.
	add_action( 'load-appearance_page_theme-settings', 'custom_theme_settings_meta_boxes' );
	// Add a filter to validate/sanitize your settings.
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'custom_theme_validate_settings' );
}

/**
 * Adds custom meta boxes to the theme settings page.
 */
function custom_theme_settings_meta_boxes() {

	add_meta_box(
		'color-theme-meta-box',								// Custom meta box ID
		__( 'Colors', hybrid_get_textdomain() ),			// Custom label
		'color_theme_meta_box',								// Custom callback function
		'appearance_page_theme-settings',					// Page to load on, leave as is
		'normal',											// normal / advanced / side
		'high'												// high / low
	);
}

/**
 * Will enable the farbtastic color picker for choosing custom colors in admin
 */
add_action( 'init', 'enable_farbtastic_color_picker' );
function enable_farbtastic_color_picker() {

	wp_register_script('farbtastic', (THEME_URI . '/resources/js/farbtastic/farbtastic.js')); 
	wp_enqueue_script('farbtastic');
	
	wp_register_style('farbtastic', (THEME_URI . '/resources/js/farbtastic/farbtastic.css'));
	wp_enqueue_style('farbtastic');
}


/**
 * Function for displaying the color meta box.
 */
function color_theme_meta_box() { ?>
	
	<?php // Setup the farbtastic color picker in jQuery ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#colorpicker-1').farbtastic('#<?php echo hybrid_settings_field_id( 'custom-color-1' ); ?>');
			jQuery('#colorpicker-2').farbtastic('#<?php echo hybrid_settings_field_id( 'custom-color-2' ); ?>');
		});
	</script>

	<table class="form-table">

		<!-- Custom color 1, input box -->
		<?php 
			$custom_color_1_value = esc_attr( hybrid_get_setting( 'custom-color-1' ) );			
			if ($custom_color_1_value == "") { $custom_color_1_value = "#000"; }
		?>
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'custom-color-1' ); ?>">
					<?php _e( 'Custom Color 1:', hybrid_get_textdomain() ); ?>
				</label>
			</th>
			<td>
				<p><?php _e( 'This color will be used for the background colors, e.g. in the masterbar', hybrid_get_textdomain() ); ?></p>
				<p><input type="text" id="<?php echo hybrid_settings_field_id( 'custom-color-1' ); ?>" name="<?php echo hybrid_settings_field_name( 'custom-color-1' ); ?>" value="<?php echo $custom_color_1_value; ?>" /></p>
				<p><div id="colorpicker-1"></div></p>
			</td>
		</tr>

		<!-- Custom color 2, input box -->
		<?php 
			$custom_color_2_value = esc_attr( hybrid_get_setting( 'custom-color-2' ) );			
			if ($custom_color_2_value == "") { $custom_color_2_value = "#fff"; }
		?>		
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'custom-color-2' ); ?>">
					<?php _e( 'Custom Color 2:', hybrid_get_textdomain() ); ?>
				</label>
			</th>
			<td>
				<p><?php _e( 'This color is for text and contrast to the background, e.g. the links in the masterbar', hybrid_get_textdomain() ); ?></p>
				<p><input type="text" id="<?php echo hybrid_settings_field_id( 'custom-color-2' ); ?>" name="<?php echo hybrid_settings_field_name( 'custom-color-2' ); ?>" value="<?php echo $custom_color_2_value; ?>" /></p>
				<p><div id="colorpicker-2"></div></p>
			</td>
		</tr>

	</table><!-- .form-table --><?php
}

/**
 * Validates theme settings.
 */
function custom_theme_validate_settings( $input ) {

	// Validate if the hex color is valid and reset to default if not
	if (!preg_match('/^#[a-f0-9]{6}$/i', $input['custom-color-1'])) { $input['custom-color-1'] = "#000"; }
	if (!preg_match('/^#[a-f0-9]{6}$/i', $input['custom-color-2'])) { $input['custom-color-2'] = "#fff"; }

	return $input;
}
?>