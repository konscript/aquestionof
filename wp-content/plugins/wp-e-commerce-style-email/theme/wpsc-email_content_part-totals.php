<?php 
/**
 * Template for totals in purchase transaction emails
 */
?>


<table cellpadding="0" cellspacing="10" width="500" style="border:none; color:#444444">

	<tr>
		<td align="right">
			Subtotal:
		</td>
		<td width="60" align="right">
			<?php echo wpsc_currency_display(ECSE_purchase::get_the_total_prop('products_total')) ?>
		</td>
	</tr>

	<?php if(ECSE_purchase::get_the_total_prop('shipping_total')!=0): ?>
	<tr>
		<td align="right">
			Shipping:
		</td>
		<td align="right">
			<?php echo wpsc_currency_display(ECSE_purchase::get_the_total_prop('shipping_total')) ?>
		</td>
	</tr>
	<?php endif; ?>

	<?php if(ECSE_purchase::get_the_total_prop('discount_total')!=0): ?>
	<tr>
		<td align="right">
			Discount:
		</td>
		<td align="right">
			<?php echo wpsc_currency_display(ECSE_purchase::get_the_total_prop('discount_total')) ?>
		</td>
	</tr>
	<?php endif; ?>
	
	<?php if(ECSE_purchase::get_the_total_prop('tax_total')!=0): ?>
	<tr>
		<td align="right">
			Tax:
		</td>
		<td align="right">
			<?php echo wpsc_currency_display(ECSE_purchase::get_the_total_prop('tax_total')) ?>
		</td>
	</tr>
	<?php endif; ?>
	
	<?php if(ECSE_purchase::get_the_total_prop('grand_total')!=ECSE_purchase::get_the_total_prop('products_total')) : ?>
	<tr>
		<td align="right">
			Total:
		</td>
		<td align="right">
			<?php echo wpsc_currency_display(ECSE_purchase::get_the_total_prop('grand_total')) ?>
		</td>
	</tr>
	<?php endif; ?>
	
</table>
<br />

