<?php
/**
 * Template for totals in purchase transaction emails
 */
?>



<table width="100%">
	<tr>
		<?php if(ECSE_purchase::get_the_billing_prop('gateway')) { ?>
			<td width="50%" align="left" valign="top">
				Billing to:<br />
				<?php echo ECSE_purchase::get_the_billing_prop('billingfirstname') . ' ' . ECSE_purchase::get_the_billing_prop('billinglastname') ?><br />
				<?php echo ECSE_purchase::get_the_billing_prop('billingaddress', ',<br />') ?>
				<?php echo ECSE_purchase::get_the_billing_prop('billingcity', ', ') . ECSE_purchase::get_the_billing_prop('billingstate').' '.ECSE_purchase::get_the_billing_prop('billingpostcode').' '.ECSE_purchase::get_the_billing_prop('billingcountry') ?><br />
				<br />
				Payment method: <?php echo ECSE_purchase::get_the_billing_prop('gateway') ?>
			</td>
		<?php } ?>
		<td align="left" valign="top">
			<?php if(ECSE_purchase::get_the_shipping_prop('shipping_method')) { ?>
				Shipping to:<br />
				<?php echo ECSE_purchase::get_the_shipping_prop('shippingfirstname').' '.ECSE_purchase::get_the_shipping_prop('shippinglastname') ?><br />
				<?php echo ECSE_purchase::get_the_shipping_prop('shippingaddress', ',<br />') ?>
				<?php echo ECSE_purchase::get_the_shipping_prop('shippingcity', ',<br />') ?>
				<?php echo ECSE_purchase::get_the_shipping_prop('shippingstate', ',<br />') ?>
				<?php echo ECSE_purchase::get_the_shipping_prop('shippingpostcode').' '.ECSE_purchase::get_the_shipping_prop('shippingcountry') ?><br />
				<br />
				Shipping via:<br />
				<?php //echo ECSE_purchase::get_the_shipping_prop('shipping_method', ', ') ?>
				<?php echo ECSE_purchase::get_the_shipping_prop('shipping_option') ?>
				<?php if( ECSE_purchase::get_the_shipping_prop('tracking_id')!='' ) { ?>
					<br />
					Tracking: <?php echo ECSE_purchase::get_the_shipping_prop('tracking_id') ?>
				<?php } ?><br />
			<?php } ?>
		</td>
	</tr>
</table>
