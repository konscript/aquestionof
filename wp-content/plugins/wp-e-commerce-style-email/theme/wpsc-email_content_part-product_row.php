<?php 
/**
 * Template for a product row in purchase transaction emails
 */
?>

<tr>
	<td valign="middle">
		<?php 
		/**
		 * Image
		 */
		$src=ECSE_purchase_product::get_the_image();
		if(!empty($src)) { ?>
			<a href="<?php echo ECSE_purchase_product::get_the_permalink() ?>" style="border:none"><img src="<?php echo $src ?>" style="border:solid 1px #ccc" /></a>
		<?php } ?>
	</td>
	<td valign="middle">
		<?php 
		/**
		 * SKU
		 */
		?>
		<a href="<?php echo ECSE_purchase_product::get_the_permalink() ?>" style="color:#444444; text-decoration:none"><?php echo ECSE_purchase_product::get_the_SKU() ?></a>
	</td>
	<td valign="middle">
		<?php 
		/**
		 * Name
		 */
		?>
		<a href="<?php echo ECSE_purchase_product::get_the_permalink() ?>" style="color:#444444; text-decoration:none"><?php echo ECSE_purchase_product::get_the_name(); ?></a>
	</td>
	<td valign="middle" align="right">
		<?php 
		/**
		 * QTY
		 */
		?>
		<?php echo ECSE_purchase_product::get_the_QTY() ?> x
	</td>
	<td valign="middle" align="right" width="60">
		<?php 
		/**
		 * Cost per item (currency pre-formatted)
		 */
		?>
		<?php echo ECSE_purchase_product::get_the_cost() ?>
	</td>
</tr>

<?php 
/**
 * Downloads for this product
 */
if(ecse_is_purchase_receipt_email()) { //don't send download links if order pending or payment required
	$downloads = ECSE_purchase_product::get_the_downloads();
	if(!empty($downloads)) { ?>
	<tr>
		<td colspan="2"></td>
		<td colspan="3" align="left">
		<?php foreach($downloads as $d) { ?>
			&rarr; <?php echo $d['name'] ?> <a href="<?php echo $d['url'] ?>" style="color:#999999; text-decoration:none">Download</a><br />
		<?php } ?>
		</td>
	</tr>
	<?php }
} ?>