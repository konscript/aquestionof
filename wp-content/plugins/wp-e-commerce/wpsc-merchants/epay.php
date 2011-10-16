<?php
/*
  Copyright (c) 2009. All rights reserved ePay - www.epay.dk.

  This program is free software. You are allowed to use the software but NOT allowed to modify the software. 
  It is also not legal to do any changes to the software and distribute it in your own name / brand. 
*/

$nzshpcrt_gateways[$num]['name'] = 'ePay';
$nzshpcrt_gateways[$num]['admin_name'] = 'ePay';
$nzshpcrt_gateways[$num]['internalname'] = 'epay';
$nzshpcrt_gateways[$num]['function'] = 'gateway_epay';
$nzshpcrt_gateways[$num]['form'] = "form_epay";
$nzshpcrt_gateways[$num]['submit_function'] = "submit_epay";
$nzshpcrt_gateways[$num]['payment_type'] = "credit_card";

//
// Extracts the current page url
//
function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

//
// Function used to convert from 100,95 to 10095
//
function trimAmountForEpay($amount) {
	$res = "";
	for ($i = 0; $i < strlen($amount); $i++) {
		$letter = substr($amount, $i, 1);
		if ($letter == "0" || $letter == "1" || $letter == "2" || $letter == "3" || $letter == "4" || $letter == "5" || $letter == "6" || $letter == "7" || $letter == "8" || $letter == "9") {
			$res = ($res . $letter);
		}
	}
	return $res;
}

//
// Generates the opener for the ePay standard payment window
//
function gateway_epay($seperator, $sessionid) {
  global $wpdb, $wpsc_cart;
  $purchase_log = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE `sessionid`= ".$sessionid." LIMIT 1",ARRAY_A) ;

	if ($purchase_log['totalprice']==0) {
		echo "INVALID ORDER!";
		exit();
	}
	
	$transact_url = get_option('transact_url');
  
  echo '<html>
  			<head>
  				<meta http-equiv="content-type" content="text/html; charset=UTF-8">
  				<script type="text/javascript" src="http://www.epay.dk/js/standardwindow.js"></script>
  			</head>
  			<body onload="open_ePay_window()">';
  
  echo '<form action="https://ssl.ditonlinebetalingssystem.dk/popup/default.asp" method="post" name="ePay" target="ePay_window" id="ePay">
  				<input type="hidden" name="merchantnumber" value="' . get_option('payment_epay_merchantnumber') . '">
  				<input type="hidden" name="amount" value="' . trimAmountForEpay(wpsc_cart_total(false) * 100). '">
  				<input type="hidden" name="currency" value="' . get_option('payment_epay_currency') . '">
  				<input type="hidden" name="orderid" value="' . $purchase_log['id'] . '">
  				<input type="hidden" name="windowstate" value="' . get_option('payment_epay_windowstate') . '">
  				<input type="hidden" name="instantcallback" value="1">
  				<input type="hidden" name="accepturl" value="' . $transact_url.$seperator."sessionid=".$sessionid."&gateway=epay&epay_accept=1" . '">
  				<input type="hidden" name="declineurl" value="' . curPageURL() . '">
  				<input type="hidden" name="callbackurl" value="' . get_option('siteurl').'/?epay_callback=1' . '">
  				<input type="hidden" name="md5key" value="' . MD5(get_option('payment_epay_currency') . trimAmountForEpay(wpsc_cart_total(false) * 100) . $purchase_log['id'] . get_option('payment_epay_md5key')) . '">
  				
  				<input type="hidden" name="addfee" value="' . get_option('payment_epay_addfee') . '">
  				<input type="hidden" name="splitpayment" value="' . get_option('payment_epay_splitpayment') . '">
  				<input type="hidden" name="authsms" value="' . get_option('payment_epay_authsms') . '">
  				<input type="hidden" name="authmail" value="' . get_option('payment_epay_authmail') . '">
  				<input type="hidden" name="group" value="' . get_option('payment_epay_group') . '">
  				<input type="hidden" name="instantcapture" value="' . get_option('payment_epay_instantcapture') . '">
				
				<input type="hidden" name="cms" value="wpecommerce">
  				
  			</form>';
  			

	echo get_option('payment_epay_text_1') . '<br><br>';
	echo get_option('payment_epay_text_2') . '<br><br>';
	echo '<input type="button" value="' . get_option('payment_epay_text_3') . '" onClick="open_ePay_window()"><br><br>';
				
	if($_POST['collected_data'][get_option('epay_form_first_name')] != '')
    {   
    	echo $_POST['collected_data'][get_option('epay_form_first_name')];
    }
				
	echo '</body>
				</html>';
	exit();
}

function submit_epay() {
  return true;
}

//
// Generates admin settings for the ePay module
//
function form_epay() {  
	$output = "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Merchantnumber:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_merchantnumber]' value='".( strlen(get_option('payment_epay_merchantnumber')) > 0 ? get_option('payment_epay_merchantnumber') : "ENTER EPAY MERCHANTNUMBER HERE")."'><br />\n\r";
	$output .= "<em>You ePay merchantnumber. Is to be found in the ePay administration from the menu \"Settings\"->\"Payment System\". Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#merchantnumber' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Currency:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_currency]' value='".(strlen(get_option('payment_epay_currency')) > 0 ? get_option('payment_epay_currency') : "208")."'><br />\n\r";
	$output .= "<em>The currency of which the payments are made. Danish (DKK) is 208. To view the complete list of currency codes please enter the ePay administration and enter the menu \"Support\" -> \"Currency codes\". Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#currency' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Windowstate:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_windowstate]' value='".(strlen(get_option('payment_epay_windowstate')) > 0 ? get_option('payment_epay_windowstate') : "2")."'><br />\n\r";
	$output .= "<em>How the payment window should behave. Set the value to 1 and the payment window will open up (as popup). Set the value to 2 and the payment window will be shown to the user in the same browser window. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#windowstate' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Popup text 1:</strong><br />\n\r";
	$output .= "<textarea cols='40' rows='9' name='wpsc_options[payment_epay_text_1]'>". (strlen(get_option('payment_epay_text_1')) > 0 ? get_option('payment_epay_text_1') : 'Hvis ikke Standard Betalingsvinduet &#229;bner op automatisk, s&#229; klik p&#229; knappen for at aktivere det.') . "</textarea><br />\n\r";
	$output .= "<em>Text when the payment window is opened.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Popup text 2:</strong><br />\n\r";
	$output .= "<textarea cols='40' rows='9' name='wpsc_options[payment_epay_text_2]'>".(strlen(get_option('payment_epay_text_2')) > 0 ? get_option('payment_epay_text_2') : 'Bem&#230;rk! Hvis I benytter en pop-up stopper, skal I holde CTRL tasten nede, mens I trykker p&#229; knappen.')."</textarea><br />\n\r";
	$output .= "<em>Text when the payment window is opened.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Popup text 3:</strong><br />\n\r";
	$output .= "<textarea cols='40' rows='9' name='wpsc_options[payment_epay_text_3]'>".(strlen(get_option('payment_epay_text_3')) > 0 ? get_option('payment_epay_text_3') : '&#197;ben betalingsvinduet')."</textarea><br />\n\r";
	$output .= "<em>Text when the payment window is opened.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Enable MD5 security:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_md5mode]' value='".( strlen(get_option('payment_epay_md5mode')) > 0 ? get_option('payment_epay_md5mode') : "0")."'><br />\n\r";
	$output .= "<em>If MD5 security is used. 0 and MD5 is disabled. 1 and Md5 is used on the callbackurl. 2 and MD5 is used on both data to ePay and on the callbackurl. Notice that you must enter the exact same MD5 key within the ePay administration. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#md5mode' target='_blank'>here</a></em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>MD5 security password:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_md5key]' value='".get_option('payment_epay_md5key')."'><br />\n\r";
	$output .= "<em>The password used to MD5 security stamp. Notice that you must enter the exact same MD5 key within the ePay administration. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#md5key' target='_blank'>here</a></em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Add fee:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_addfee]' value='".( strlen(get_option('payment_epay_addfee')) > 0 ? get_option('payment_epay_addfee') : "0")."'><br />\n\r";
	$output .= "<em>If the customer has to pay for the transactionfee set this value to 1. Otherwise 0. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#addfee' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Split payment:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_splitpayment]' value='".( strlen(get_option('payment_epay_splitpayment')) > 0 ? get_option('payment_epay_splitpayment') : "0")."'><br />\n\r";
	$output .= "<em>If the payments should be captured over several times (partly orders). Set the value to 1 in order to enable splitpayment. To disable splitpayment set the value to 0. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#splitpayment' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Auth sms:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_authsms]' value='".get_option('payment_epay_authsms')."'><br />\n\r";
	$output .= "<em>Receive an SMS as notification when the payments is made. Notice that this service is not free! Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#authsms' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Auth e-mail:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_authmail]' value='".get_option('payment_epay_authmail')."'><br />\n\r";
	$output .= "<em>Receive an e-mail as notification when the payments is made. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#authmail' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Group:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_group]' value='".get_option('payment_epay_group')."'><br />\n\r";
	$output .= "<em>Place the payment in a specific group within the ePay admin. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#group' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
	$output .= "<tr>\n\r";
	$output .= "	<td colspan='2'>\n\r";
	$output .= "<strong>Instant capture:</strong><br />\n\r";
	$output .= "<input type='text' name='wpsc_options[payment_epay_instantcapture]' value='".( strlen(get_option('payment_epay_instantcapture')) > 0 ? get_option('payment_epay_instantcapture') : "0")."'><br />\n\r";
	$output .= "<em>If the payment has to be captured as soon as it has been made. 1 and the payment will be captured. 0 will disable instant capture. Read more <a href='http://www.epay.dk/support/docs.asp?solution=1#instantcapture' target='_blank'>here</a>.</em>\n\r";
	$output .= "	</td>\n\r";
	$output .= "</tr>\n\r";
	
  return $output;
}

//
// Calculates the cardtype from the ePay internal card identifiers
//
function calcCardtype($cardid)
{
	$res = "UNKNOWN!";
	switch(((int)$cardid)) {
		case 1: $res = "DANKORT"; break;
		case 2: $res = "VISA_DANKORT"; break;
		case 3: $res = "VISA_ELECTRON_FOREIGN"; break;
		case 4: $res = "MASTERCARD"; break;
		case 5: $res = "MASTERCARD_FOREIGN"; break;
		case 6: $res = "VISA_ELECTRON"; break;
		case 7: $res = "JCB"; break;
		case 8: $res = "DINERS"; break;
		case 9: $res = "MAESTRO"; break;
		case 10: $res = "AMERICAN_EXPRESS"; break;
		case 11: $res = "EDK"; break;
		case 12: $res = "DINERS_FOREIGN"; break;
		case 14: $res = "AMERICAN_EXPRESS_FOREIGN"; break;
		case 15: $res = "MAESTRO_FOREIGN"; break;
		case 16: $res = "FORBRUGSFORENINGEN"; break;
		case 17: $res = "EWIRE"; break;
		case 18: $res = "VISA"; break;
		case 19: $res = "IKANO"; break;
		case 21: $res = "Nordea e-betaling"; break;
		case 22: $res = "Danske Netbetaling"; break;
		case 23: $res = "LIC_MASTERCARD"; break;
		case 24: $res = "LIC_MASTERCARD_FOREIGN"; break;
	}
	return $res;
}

//
// Function which handles the callback from ePay
//
function nzshpcrt_epay_callback()
{
	global $wpdb;
	if ((isset($_GET['epay_callback']) && $_GET['epay_callback'] == '1') || (isset($_GET['epay_accept']) && $_GET['epay_accept'] == '1')) {
		if (isset($_GET['amount']) && isset($_GET['orderid']) && isset($_GET['tid']) && isset($_GET['cur']) && isset($_GET['cardid'])) {
			
			//
			// Extract the order
			//
			$purchase_log = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PURCHASE_LOGS."` WHERE id = ".$_GET['orderid'] ." LIMIT 1",ARRAY_A) ;
			if ($purchase_log['totalprice']==0) {
				echo "<h1>INVALID ORDER!</h1>";
				exit();
			}
			
			//
			// Validate the MD5 answer from ePay
			//
			if (get_option('payment_epay_md5mode') == '1' || get_option('payment_epay_md5mode') == '2') {
				$ekey = "";
				$genkey = MD5($_GET['amount'] . $_GET['orderid'] . $_GET['tid'] . get_option('payment_epay_md5key'));
				if (isset($_GET['eKey'])) $ekey = $_GET['eKey'];
				if ($ekey != $genkey) {
					echo "<h1>Error in MD5! The key " . $ekey . " does not match the local generated " . $genkey . "</h1>";
					exit();
				}
			}
			
			$sql = "UPDATE ".WPSC_TABLE_PURCHASE_LOGS." set transactid = " . $_GET['tid'] . ", processed = 3 where id = " . $_GET['orderid'];
			$wpdb->query($sql);	
			
			//
			// Only break if the answer is from callback
			//
			if (isset($_GET['epay_callback']) && $_GET['epay_callback'] == '1') {
				$sql = "UPDATE ".WPSC_TABLE_PURCHASE_LOGS." set notes = '" . $purchase_log['notes'] . "\nPayment approved at ePay with transactionid " . $_GET['tid'] . ", cardnopostfix " . $_GET['cardnopostfix'] . ", orderid " . $_GET['orderid'] . ", amount " . $_GET['amount'] . ", currency " . $_GET['cur'] . ", cardtype " . calcCardtype($_GET['cardid']) . "', processed = 3 where id = " . $_GET['orderid'];
				$wpdb->query($sql);	
				
				echo "CALLBACK OK";
				exit();
			}
		}
	}
}

function nzshpcrt_epay_accept()
{
	if (isset($_GET['epay_accept'])) {
		if ($_GET['epay_accept'] == '1') {
			//
			// Empty the shopping cart
			//
			$_SESSION['nzshpcrt_cart'] = null;
			$_SESSION['nzshpcrt_serialized_cart'] = null;
			
			//
			// Reflect the call to the callback handler
			//
			nzshpcrt_epay_callback();
		}
	}
}

//
// Add action scripts
//
add_action('init', 'nzshpcrt_epay_callback');
add_action('init', 'nzshpcrt_epay_accept');
?>
