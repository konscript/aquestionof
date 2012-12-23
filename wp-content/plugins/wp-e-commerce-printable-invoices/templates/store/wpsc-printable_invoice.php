<div id="page" class="packing_slip <?php wpsc_pi_printable_invoice_classes(); ?>">

<header id="branding">

	<hgroup>

	<div id="header">
<?php if( wpsc_pi_has_invoice_header() ) { ?>
		<h1 id="site-title">
			<a href="#"><?php wpsc_pi_invoice_header(); ?></a>
		</h1>
		<div id="searchform">
<?php if( wpsc_pi_has_header_logo() ) { ?>
				<?php wpsc_pi_header_logo(); ?><br />
<?php } ?>
		</div>
		<h2 id="site-description">
<?php if( wpsc_pi_show_printed_on() ) { ?>
			<?php _e( 'Printed on', 'wpsc_pi' ); ?>: <?php wpsc_pi_printed_on(); ?><br />
<?php } ?>
			<?php _e( 'Invoice Number', 'wpsc_pi' ); ?>: <?php if( wpsc_pi_replace_order_number() && wpsc_pi_has_order_number() ) echo wpsc_pi_get_order_number(); else echo $purchase_id; ?>
		</h2>
<?php } ?>
<?php do_action( 'wpsc_pi_template_header' ); ?>
	</div>
	<!-- #header -->
	</hgroup>

</header>

<div id="content">

<?php if( wpsc_pi_has_header_text() ) { ?>
<div class="entry-summary">
	<p><?php wpsc_pi_header_text(); ?></p>
</div>
<?php } ?>

<article class="page">

	<table id="checkout" cellspacing="0">
		<tr>
			<td style="<?php wpsc_pi_template_checkout_form_styles(); ?>">
				<table cellspacing="0">
<?php
if( $purch_data['input_data'] ) {
	$i = 0;
	foreach( $purch_data['form_data'] as $key => $form_field ) {
		switch( $form_field['type'] ) {

			case 'country':
				$delivery_region_count = $wpdb->get_var( "SELECT COUNT(`regions`.`id`) FROM `" . WPSC_TABLE_REGION_TAX . "` AS `regions` INNER JOIN `" . WPSC_TABLE_CURRENCY_LIST . "` AS `country` ON `country`.`id` = `regions`.`country_id` WHERE `country`.`isocode` IN('" . $wpdb->escape( $purch_data['billing_country'] ) . "')" );
				if( is_numeric( $purch_data['billing_region'] ) && ( $delivery_region_count > 0 ) ) { ?>
					<tr id="form_uniquename-<?php echo $form_field['unique_name']; ?>" class="form-billing_state field_type-<?php echo $form_field['type']; ?>">
						<th style="<?php wpsc_pi_template_checkout_form_label_styles(); ?>"><strong><?php _e( 'State', 'wpsc_pi' ); ?>:</strong></th>
						<td><?php echo wpsc_get_region( $purch_data['billing_region'] ); ?></td>
					</tr>
				<?php
				} ?>
					<tr id="form_uniquename-<?php echo $form_field['unique_name']; ?>" class="form-billing_country field_type-<?php echo $form_field['type']; ?>">
						<th style="<?php wpsc_pi_template_checkout_form_label_styles(); ?>"><strong><?php echo wp_kses( $form_field['name'], array() ); ?>:</strong></th>
						<td><?php echo wpsc_get_country( $purch_data['billing_country'] ); ?></td>
					</tr>
				<?php
				break;

			case 'delivery_country': ?>
					<tr id="form_uniquename-<?php echo $form_field['unique_name']; ?>" class="form-delivery_country field_type-<?php echo $form_field['type']; ?>">
						<th style="<?php wpsc_pi_template_checkout_form_label_styles(); ?>"><strong><?php echo $form_field['name']; ?>:</strong></th>
						<td><?php echo wpsc_get_country( $purch_data['shipping_country'] ); ?></td>
					</tr>
				<?php
				break;

			case 'heading': ?>
	<?php if( $i <> 0 && $purch_data['header_group_columns'] ) { ?>
				</table>
			</td>
			<td style="<?php wpsc_pi_template_checkout_form_styles(); ?>">
				<table cellspacing="0">
	<?php } ?>
					<tr class="form-heading field_type-<?php echo $form_field['type']; ?>">
						<td class="heading" colspan="2">
							<h3><?php echo wp_kses( $form_field['name'], array() ); ?></h3>
						</td>
					</tr>
				<?php
				break;

			default:
				if( $form_field['unique_name'] == 'xxxshippingstate' ) { // Invalidated by KONSCRIPT, we just want regular output ?>
					<tr id="form_uniquename-<?php echo $form_field['unique_name']; ?>" class="form-shipping_state field_type-<?php echo $form_field['type']; ?>">
						<th style="<?php wpsc_pi_template_checkout_form_label_styles(); ?>"><strong><?php echo wp_kses( $form_field['name'], array() ); ?>:</strong></th>
						<td>
							<?php echo wpsc_get_region( $purch_data['shipping_region'] ); ?>
						</td>
					</tr>
				<?php
				} else { ?>
					<tr <?php if( $form_field['unique_name'] ) { ?>id="form_uniquename-<?php echo $form_field['unique_name']; ?>"  <?php } ?>class="form-default field_type-<?php echo $form_field['type']; ?>">
						<th style="<?php wpsc_pi_template_checkout_form_label_styles(); ?>"><strong><?php echo wp_kses( $form_field['name'], array() ); ?></strong>:</th>
						<td>
							<?php echo $purch_data['rekeyed_input'][$form_field['id']]; ?>
						</td>
					</tr>
				<?php
				}
				break;

		}
		$i++;
	}
} ?>
				</table>

			</td>
		</tr>
	</table>
	<!-- #checkout -->

	<table id="sale_summary">
		<tr>
			<td>
				<p><strong><?php _e( 'Invoice / Receipt for', 'wpsc_pi' ); ?>:</strong> <?php wpsc_pi_purchase_date(); ?></p>
				<p><strong><?php _e( 'Payment Method', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_display_purchlog_paymentmethod(); ?></p>
<?php if( wpsc_has_purchlog_shipping() ) { ?>
	<?php if( wpsc_display_purchlog_shipping_method() ) { ?>
				<p><strong><?php _e( 'Shipping Method', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_display_purchlog_shipping_method(); ?></p>
	<?php } ?>
	<?php if( wpsc_display_purchlog_shipping_option() ) { ?>
				<p><strong><?php _e( 'Shipping Option', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_display_purchlog_shipping_option(); ?></p>
	<?php } ?>
	<?php if( wpsc_purchlogs_has_tracking() ) { ?>
		<?php if( wpsc_purchlogitem_trackid() ) { ?>
				<p><?php _e( 'Tracking ID', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_purchlogitem_trackid(); ?></p>
		<?php } ?>
		<?php if( wpsc_purchlogitem_trackstatus() ) { ?>
				<p><?php _e( 'Shipping Status', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_purchlogitem_trackstatus(); ?></p>
		<?php } ?>
		<?php if( wpsc_purchlogitem_trackhistory() ) { ?>
				<p><?php _e( 'Track History', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_purchlogitem_trackhistory(); ?></p>
		<?php } ?>
	<?php } ?>
<?php } ?>
			</td>
			<td class="cell-right">
<?php if( wpsc_pi_show_session_id() ) { ?>
				<p><strong><?php _e( 'Session ID', 'wpsc_pi' ); ?>:</strong> <?php echo $purchlogitem->extrainfo->sessionid; ?></p>
<?php } ?>
				<p><strong><?php _e( 'Order Status', 'wpsc_pi' ); ?>:</strong> <?php echo wpsc_find_purchlog_status_name( $purchlogitem->extrainfo->processed ); ?></p>
			</td>
		</tr>
	</table>
	<!-- #sale_summary -->

	<table id="cart">

		<thead>
			<tr class="cart_header">
<?php if( wpsc_pi_show_column( 'thumbnails' ) && wpsc_pi_has_thumbnails() ) { ?>
				<th>&nbsp;</th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'name' ) ) { ?>
				<th class="cell-left"><?php _e( 'Description', 'wpsc_pi' ); ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'sku' ) ) { ?>
				<th class="cell-left"><?php _e( 'SKU', 'wpsc_pi' ); ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'price' ) ) { ?>
				<th class="cell-right" nowrap><?php _e( 'Unit Price', 'wpsc_pi' ); ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'quantity' ) ) { ?>
				<th class="cell-right"><?php _e( 'Quantity', 'wpsc_pi' ); ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'shipping' ) ) { ?>
				<th class="cell-right"><?php _e( 'Shipping', 'wpsc_pi' ); ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'tax' ) ) { ?>
				<th class="cell-right" nowrap><?php echo $wpsc_pi['template']['cart']['tax_label']; ?></th>
<?php } ?>
<?php if( wpsc_pi_show_column( 'total' ) ) { ?>
				<th class="cell-right"><?php _e( 'Total', 'wpsc_pi' ); ?></th>
<?php } ?>
			</tr>

		</thead>
		<tbody>

<?php
$j = 0;
foreach( $cart as $row ) {
	$purchlogitem->the_purch_item();
	$alternate = '';
	$j++;
	if( ( $j % 2 ) != 0 )
		$alternate = ' alt'; ?>
			<tr id="product-<?php echo $row['prodid']; ?>" class="cart_item<?php echo $alternate; ?>">
	<?php if( wpsc_pi_show_column( 'thumbnails' ) && wpsc_pi_has_thumbnails() ) { ?>
				<td class="cart-item_thumbnail">
					<?php echo $row['thumbnail']; ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'name' ) ) { ?>
				<td class="cart-item_name">
					<?php echo $row['name']; ?>
					<?php echo $row['variation_list']; ?>
		<?php if( $row['files'] ) { ?>
					<br /><?php _e( 'Download Files', 'wpsc_pi' ); ?>:
			<?php foreach( $row['files'] as $file ) { ?>
					<br />- <a href="<?php echo $file['url']; ?>"><?php echo $file['filename']; ?></a>
			<?php } ?>
		<?php } ?>
		<?php if( $row['customer_message'] ) { ?>
					<br /><?php _e( 'Customer Note', 'wpsc_pi' ); ?>: <?php echo $row['customer_message']; ?>
		<?php } ?>
		<?php if( $row['customer_file'] ) { ?>
					<br /><?php _e( 'Customer File', 'wpsc_pi' ); ?>:
					<a id="<?php echo $row['customer_file']['unique_id']; ?>" href="<?php echo WPSC_USER_UPLOADS_URL . $row['customer_file']['file_name']; ?>"><?php echo $row['customer_file']['file_name']; ?></a></li>
		<?php } ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'sku' ) ) { ?>
				<td class="cart-item_sku">
					<?php echo $row['sku']; ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'price' ) ) { ?>
				<td class="cart-item_price cell-right">
					<?php wpsc_vl_currency_display( $row['price'] ); ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'quantity' ) ) { ?>
				<td class="cart-item_quantity cell-right">
					<?php echo $row['quantity']; ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'shipping' ) ) { ?>
				<td class="cart-item_shipping cell-right">
					<?php wpsc_vl_currency_display( $row['shipping'] ); ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'tax' ) ) { ?>
				<td class="cart-item_tax cell-right">
					<?php wpsc_vl_currency_display( $row['tax_charged'] ); ?>
				</td>
	<?php } ?>
	<?php if( wpsc_pi_show_column( 'total' ) ) { ?>
				<td class="cart-item_total cell-right">
					<?php wpsc_vl_currency_display( $row['total'] ); ?>
				</td>
	<?php } ?>
			</tr>
<?php } ?>
<?php if( wpsc_purchlog_has_discount_data() ) { ?>
			<tr>
				<th class="cell-right" colspan="<?php wpsc_pi_total_colspan(); ?>"><?php _e( 'Discount', 'wpsc_pi' ); ?></th>
				<td class="cell-right"><?php echo wpsc_display_purchlog_discount(); ?></td>
			</tr>
<?php } ?>
<?php if( wpsc_pi_show_total_tax() ) { ?>
	<?php foreach( $wpsc_pi['template']['cart']['taxes'] as $tax_band => $tax_value ) { ?>
			<tr class="subtotal subtotal_tax">
				<th class="cell-right" colspan="<?php wpsc_pi_total_colspan(); ?>"><?php _e( 'Tax Band', 'wpsc_pi' ); ?>: <?php echo (int)$tax_band; ?>%</th>
				<td class="cell-right"><?php wpsc_vl_currency_display( $tax_value ); ?></td>
			</tr>
	<?php } ?>
			<tr class="subtotal subtotal_tax">
				<th class="cell-right" colspan="<?php wpsc_pi_total_colspan(); ?>"><?php _e( 'Total Tax', 'wpsc_pi' ); ?></th>
				<td class="cell-right"><?php wpsc_vl_currency_display( $wpsc_pi['template']['cart']['total_tax'] ); ?></td>
			</tr>
<?php } ?>
<?php if( wpsc_pi_show_total_shipping() ) { ?>
			<tr class="subtotal subtotal_shipping">
				<th class="cell-right" colspan="<?php wpsc_pi_total_colspan(); ?>"><?php _e( 'Total Shipping', 'wpsc_pi' ); ?></th>
				<td class="cell-right"><?php wpsc_vl_currency_display( $purch_data['base_shipping'] ); ?></td>
			</tr>
<?php } ?>
			<tr class="subtotal subtotal_total">
				<th class="cell-right" colspan="<?php wpsc_pi_total_colspan(); ?>"><?php _e( 'Invoice Total', 'wpsc_pi' ); ?></th>
				<td class="cell-right"><?php wpsc_vl_currency_display( $purch_data['totalprice'] ); ?></td>
			</tr>

		</tbody>
	</table>
	<!-- #cart -->

<?php if( wpsc_pi_has_notes() ) { ?>
	<div class="entry_content">
		<div id="notes">
			<p><strong><?php _e( 'Note', 'wpsc_pi' ); ?>:</strong></p>
			<?php wpsc_pi_notes(); ?>
		</div>
		<!-- #notes -->
	</div>

<?php } ?>

</article>

</div>
<!-- #content -->

<footer id="site-generator">

	<div id="footer">
<?php if( wpsc_pi_has_footer_logo() ) { ?>
		<div class="footer_logo">
			<?php wpsc_pi_footer_logo(); ?>
		</div>
<?php } ?>
		<div class="footer_addons">
			<?php do_action( 'wpsc_pi_footer_addons' ); ?>
		</div>
<?php if( wpsc_pi_has_footer_header() ) { ?>
		<h3 class=""><?php wpsc_pi_footer_header(); ?></h3>
<?php } ?>
<?php if( wpsc_pi_has_footer_text() ) { ?>
		<p class=""><?php wpsc_pi_footer_text(); ?></p>
<?php } ?>
	</div>
	<!-- #footer -->

</footer>

</div>
<!-- .packing_slip -->