<form method="post" action="<?php the_permalink(); ?>" enctype="multipart/form-data" id="your-profile">

	<h3><?php _e( 'Store Details', 'wpsc_pi' ); ?></h3>
	<p><?php _e( 'Your store details appear in the order invoice.', 'wpsc_pi' ); ?></p>
	<table class="form-table">

		<tr>
			<th scope="row"><?php _e( 'Phone', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="phone" class="regular-text" value="<?php echo $options->store->phone; ?>" />
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Address', 'wpsc_pi' ); ?>:</th>
			<td>
				<textarea id="address" name="address" rows="3" cols="30"><?php echo $options->store->address; ?></textarea>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'City', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="city" class="regular-text" value="<?php echo $options->store->city; ?>" />
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'State', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="state" class="regular-text" value="<?php echo $options->store->state; ?>" />
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Postcode', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="postcode" class="small-text" value="<?php echo $options->store->postcode; ?>" />
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Country', 'wpsc_pi' ); ?>:</th>
			<td>
				<select id="country" name="country">
<?php foreach( $countries as $country ) { ?>
					<option value="<?php echo $country->country; ?>" <?php echo selected( $country->country, $options->store->country ); ?>><?php echo $country->country; ?></option>
<?php } ?>
				</select>
			</td>
		</tr>

	</table>

	<h3><?php _e( 'Invoice Theme', 'wpsc_pi' ); ?></h3>
	<table class="form-table">

		<tr>
			<th scope="row"><?php _e( 'Style', 'wpsc_pi' ); ?>:</th>
			<td>
				<select name="email_theme_style">
					<option value=""><?php _e( 'Default', 'wpsc_pi' ); ?></option>
<?php foreach( $stylesheets as $key => $stylesheet ) { ?>
					<option value="<?php echo $key; ?>" <?php echo selected( $key, $options->email->style_css ); ?>><?php echo $stylesheet; ?></option>
<?php } ?>
				</select>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="email_theme_css" value="1" <?php checked( $options->email->theme_css ); ?> /> <?php _e( 'Use WordPress Theme stylesheet for HTML e-mail styling', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

	</table>

	<h3><?php _e( 'E-mail Invoice', 'wpsc_pi' ); ?></h3>
	<table class="form-table">

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="email_replace_wpsc" value="1" <?php checked( $options->email->replace_wpsc ); ?> /> <?php _e( 'Replace WP e-Commerce Purchase Receipt', 'wpsc_pi' ); ?></label>
				<p class="description"><?php _e( 'Note: This option will override the default WP e-Commerce Purchase Receipt.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Subject', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="email_subject" class="regular-text" value="<?php echo $options->email->subject; ?>" />
				<p class="description">
					<?php _e( 'For instance, Purchase Receipt.', 'wpsc_pi' ); ?>
					<?php _e( 'Tags can be used', 'wpsc_pi' ); ?>: <code>%store_name%</code>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Background Colour', 'wpsc_pi' ); ?>:</th>
			<td>
				#<input type="text" name="email_background_colour" value="<?php echo $options->email->background_colour; ?>" size="6" maxlength="6" style="text-transform: uppercase;" />
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Content Colour', 'wpsc_pi' ); ?>:</th>
			<td>
				#<input type="text" name="email_content_colour" value="<?php echo $options->email->content_colour; ?>" size="6" maxlength="6" style="text-transform: uppercase;" />
			</td>
		</tr>

<!--
		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="email_map" value="1" <?php checked( $email_map ); ?> /> <?php _e( 'Show Google Maps alongside Store Details in the footer.', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

-->
	</table>

	<h3><?php _e( 'Invoice Header', 'wpsc_pi' ); ?></h3>
	<table class="form-table">
<?php if( $options->header->logo ) { ?>
	<?php if( wpsc_pi_has_header_logo() ) { ?>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<img src="<?php echo wpsc_pi_get_header_logo_url(); ?>" alt="" style="border:1px solid #ccc;" /><br />
				<a href="admin.php?page=wpsc_pi&action=remove-logo"><?php _e( 'Remove existing logo', 'wpsc_pi' ); ?></a>
			</td>
		</tr>

	<?php } ?>
<?php } ?>
		<tr>
			<th scope="row"><?php _e( 'Header Logo Size', 'wpsc_pi' ); ?>:</th>
			<td>
				<label><?php _e( 'Width', 'wpsc_pi' ); ?>:&nbsp;<input type="text" name="header_logo_width" value="<?php echo $options->header->logo_width; ?>" size="3" maxlength="4" /></label>&nbsp;px
				<label><?php _e( 'Height', 'wpsc_pi' ); ?>:&nbsp;<input type="text" name="header_logo_height" value="<?php echo $options->header->logo_height; ?>" size="3" maxlength="4" /></label>&nbsp;px
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Header Logo', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="file" name="header_logo" />
				<p class="description"><?php _e( 'The header logo will be displayed in the order invoice. Logos may be JPG, GIF, or PNG images with a maximum size of ' . $header_logo_width . 'x' . $header_logo_height . ' pixels. Images larger than this will be resized.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Invoice Header', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="header" class="regular-text" value="<?php echo $options->header->title; ?>" />
				<p class="description"><?php _e( 'For instance, Tax Invoice', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Header Text', 'wpsc_pi' ); ?>:</th>
			<td>
				<textarea id="description" name="header_text" rows="5" cols="30"><?php echo $options->header->text; ?></textarea>
				<p class="description"><?php _e( 'Tags can be used', 'wpsc_pi' ); ?>: <code>%store_name%</code>, <code>%address%</code>, <code>%city%</code>, <code>%state%</code>, <code>%postcode%</code>, <code>%country%</code>, <code>%phone</code>, <code>%website%</code>, <code>%email%</code></p>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="show_printed_on" value="1" <?php checked( $options->header->show_printed_on ); ?> /> <?php _e( 'Show Printed On...', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

	</table>

	<h3><?php _e( 'Invoice Body', 'wpsc_pi' ); ?></h3>
	<p><?php _e( 'Hide and show elements from the body of the invoice.', 'wpsc_pi' ); ?></p>
	<table class="form-table">

<?php if( $headers ) { ?>
		<tr>
			<th scope="row"><?php _e( 'Checkout Headers', 'wpsc_pi' ); ?></th>
			<td>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e( 'Heading', 'wpsc_pi' ); ?></th>
							<th><?php _e( 'Group', 'wpsc_pi' ); ?></th>
						</tr>
					</thead>
					<tbody>
	<?php foreach( $headers as $header ) { ?>
						<tr>
							<td>
								<label for="header-<?php echo $header['id']; ?>"><?php echo $header['name']; ?></label>
							</td>
							<td><input type="text" id="header-<?php echo $header['id']; ?>" name="header_group[<?php echo $header['id']; ?>]" value="<?php if( $header_group[$header['id']] ) echo $header_group[$header['id']]; else echo '0'; ?>" size="3" /></td>
						</tr>
	<?php } ?>
					</tbody>
				</table>
				<p class="description"><?php _e( 'Adjust the placement of Checkout Fields grouped by headers.', 'wpsc_pi' ); ?></p>
				<p class="description"><?php _e( 'For example: Different Group numbers (e.g. 0, 1, 2) will create a side-by-side layout, whereas the same Group numbers (e.g. 0, 0, 0) will create a single column layout.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

<!--
		<tr>
			<th scope="row"><?php _e( 'Checkout Fields', 'wpsc_pi' ); ?></th>
			<td>
				<table class="widefat">
					<thead>
						<th><?php _e( 'ID', 'wpsc_pi' ); ?></th>
						<th><?php _e( 'Label', 'wpsc_pi' ); ?></th>
						<th><?php _e( 'Type', 'wpsc_pi' ); ?></th>
						<th><?php _e( 'Unique Name', 'wpsc_pi' ); ?></th>
					</thead>
					<tbody>
		<?php foreach( $checkout_fields as $checkout_field ) { ?>
						<tr>
							<td><?php echo $checkout_field->id; ?></td>
							<td>
								<label><input type="checkbox" name="checkout_fields[<?php echo $checkout_field->id; ?>]"<?php checked( $options->checkout_fields[$checkout_field->id], 'on' ); ?> />&nbsp;<?php echo $checkout_field->name; ?></label>
							</td>
							<td><?php echo $checkout_field->type; ?></td>
							<td><?php echo $checkout_field->unique_name; ?></td>
						</tr>
		<?php } ?>
					</tbody>
				</table>
				<p class="description"><?php _e( 'Toggle the visibility of Checkout fields from the Printable Invoice.', 'wpsc_pi' ); ?></p>

			</td>
		</tr>
-->

<?php } ?>
		<tr>
			<th scope="row"><?php _e( 'Checkout Fields Width', 'wpsc_pi' ); ?></th>
			<td>
				<label><?php _e( 'Width', 'wpsc_pi' ); ?>:&nbsp;<input type="text" id="checkout_field_width" name="checkout_field_width" value="<?php echo $options->checkout_field_width; ?>" size="3" maxlength="4" /></label>&nbsp;px
				<p class="description"><?php _e( 'Control the width of the Checkout field columns.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="replace_order_number" value="1" <?php checked( $options->replace_order_number ); ?> />&nbsp;<?php _e( 'Replace Order ID with Order Number', 'wpsc_pi' ); ?></label>
				<p class="description"><?php _e( 'Remove the default Order Number (e.g. 4) and specify your own Order Number (e.g. INV-47265) to appear in the Printable Invoice.', 'wpsc_pi' ); ?></p>
				<p class="description"><?php _e( 'Fill in the Order Number field on the from the Sale detail page. If not filled, this defaults back to the default Order Number.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Cart Columns', 'wpsc_pi' ); ?></th>
			<td>
				<fieldset>
<?php foreach( $cart_columns as $cart_column ) { ?>
					<label><input type="checkbox" name="cart_columns[<?php echo $cart_column[0]; ?>]" value="1" <?php checked( wpsc_pi_show_column( $cart_column[0] ) ); ?> />&nbsp;<?php echo $cart_column[1]; ?></label><br />
<?php } ?>
				</fieldset>
				<p class="description"><?php _e( 'Manage the cart columns that are visible on the Purchase Receipt. Requires a minimum of 2 columns, or 3 columns if showing the \'Thumbnails\' column.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="show_session_id" value="1" <?php checked( $options->show_session_id ); ?> /> <?php _e( 'Show Session ID', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="show_total_tax" value="1" <?php checked( $options->show_total_tax ); ?> /> <?php _e( 'Show Total Tax', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<label><input type="checkbox" name="show_total_shipping" value="1" <?php checked( $options->show_total_shipping ); ?> /> <?php _e( 'Show Total Shipping', 'wpsc_pi' ); ?></label>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Printed On date format', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" id="printed_on_format" name="printed_on_format" value="<?php echo $options->header->printed_on_format; ?>" />
				<span class="description"><?php _e( 'Example', 'wpsc_pi' ); ?>: <?php echo date( wpsc_pi_printed_on_format(), '570369600' ); ?></span>
				<p class="description"><?php _e( 'Tags can be used', 'wpsc_pi' ); ?>: <code>d</code> = <?php _e( 'day', 'wpsc_pi' ); ?> (28), <code>m</code> = <?php _e( 'month', 'wpsc_pi' ); ?> (01), <code>Y</code> = <?php _e( 'year', 'wpsc_pi' ); ?> (1988).</p>
			</td>
		</tr>

	</table>

	<h3><?php _e( 'Invoice Footer', 'wpsc_pi' ); ?></h3>
	<table class="form-table">
<?php if( $options->footer->logo ) { ?>
	<?php if( wpsc_pi_has_footer_logo() ) { ?>

		<tr>
			<th scope="row">&nbsp;</th>
			<td>
				<img src="<?php echo wpsc_pi_get_footer_logo_url(); ?>" alt="" style="border:1px solid #ccc;" /><br />
				<a href="admin.php?page=wpsc_pi&action=remove-footer-logo"><?php _e( 'Remove existing logo', 'wpsc_pi' ); ?></a>
			</td>
		</tr>
	<?php } ?>
<?php } ?>

		<tr>
			<th scope="row"><?php _e( 'Footer Logo', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="file" name="footer_logo" />
				<p class="description"><?php _e( 'The footer logo will be displayed in the order invoice. Logos may be JPG, GIF, or PNG images with a maximum size of 292x75 pixels. Images larger than 292x75 will be resized.', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Footer Header', 'wpsc_pi' ); ?>:</th>
			<td>
				<input type="text" name="footer" value="<?php echo $options->footer->title; ?>" class="regular-text" />
				<p class="description"><?php _e( 'For instance, Have a nice day!', 'wpsc_pi' ); ?></p>
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e( 'Footer Text', 'wpsc_pi' ); ?>:</th>
			<td>
				<textarea id="description" name="footer_text" rows="5" cols="30"><?php echo $options->footer->text; ?></textarea>
				<p class="description"><?php _e( 'Tags can be used', 'wpsc_pi' ); ?>: <code>%store_name%</code>, <code>%address%</code>, <code>%city%</code>, <code>%state%</code>, <code>%postcode%</code>, <code>%country%</code>, <code>%phone%</code>, <code>%website%</code>, <code>%email%</code></p>
			</td>
		</tr>

	</table>

	<p class="submit">
		<input type="submit" value="<?php _e( 'Save Changes', 'wpsc_pi' ); ?>" class="button-primary" />
	</p>
	<input type="hidden" name="action" value="update" />

</form>