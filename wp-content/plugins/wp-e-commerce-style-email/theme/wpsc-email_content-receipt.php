<?php 
/**
 * Template for a customer receipt's content in purchase transaction emails.
 * Only gets used during a WP e-Commerce purchase receipt
 */
?>

<table cellspacing="0" cellpadding="0" width="500" style="border:none; color:#444444">
	<tr>
		<td style="border-bottom: solid 1px #CCCCCC;">
			#<?php echo ECSE_purchase::get_the_purchase_ID() ?><br />
			<br />
			<br />
			Dear <?php echo ECSE_purchase::get_the_checkout_prop('billingfirstname') ?>,<br />
			<br />
			Thanks for making your purchase with <?php echo get_option( 'blogname' ) ?>!<br />
			<br />
			<?php if(ecse_is_order_pending_email() || ecse_is_order_pending_payment_required_email()) { ?>
			Your order is pending. We'll get back to you when payment has been confirmed.<br /><br />
			<?php } ?>
			Here's what you ordered:<br />
			<br />
		</td>
	</tr>
	
	<tr>
		<td>
			<table cellpadding="0" cellspacing="10" width="500" style="border:none; color:#444444">
				<?php 
					/*
					 * Do the product rows
					 * Multiplies out the template file for each product in the purchase
					 * Template file: wpsc-email_content_part-product_row.php
					 */
					echo ecse_get_the_product_list(); 
				?>
			</table>
		</td>
	</tr>
	
	<tr>
		<td style="border-top: solid 1px #CCCCCC;">
			<?php 
				/*
				 * Do the totals
				 * Template file: wpsc-email_content_part-totals.php
				 */
				echo ecse_get_the_totals(); 
			?>
		</td>
	</tr>
	
	<tr>
		<td>
			<?php 
				/*
				 * Do the billing & shipping addresses
				 * Template file: wpsc-email_content_part-addresses.php
				 */
				echo ecse_get_the_addresses(); 
			?>
		</td>
	</tr>
	
	<?php if(ECSE_purchase::get_the_purchase_prop('notes')!='') { ?>
	<tr>
		<td>
			Special request: <?php echo ECSE_purchase::get_the_purchase_prop('notes') ?>
		</td>
	</tr>
	<?php } ?>
	
</table>