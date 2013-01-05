<?php
// BME WMS
// Page: Checkout Confirm page
// Path/File: /store/confirm.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$user_id = $_COOKIE["db_user"];
if(!$user_id) {
	header("Location: " . $base_url . "store/");
	exit;
}

$result = setcookie("db_user", $user_id, time()-3600, "/~dreamboo/store/", ".ocservers.net", 1) or die ("Set Cookie failed : " . mysql_error());

// read ship_main
$query = "SELECT tax, instate_tax, state_tax FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$main_tax = $line["tax"];
	$instate_tax = $line["instate_tax"];
	$state_tax = $line["state_tax"];
}
mysql_free_result($result);

// read promo main
$query = "SELECT buy_one_get_one_free FROM promo_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$buy_one_get_one_free = $line["buy_one_get_one_free"];
}
mysql_free_result($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Checkout</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Store - Checkout: Confirmation</td></tr>

<tr><td align="left" class="style2">Thank you for your order. Your Order Confirmation Number and Order Number is <b><?php echo $user_id; ?></b><br>
Please print this page as your receipt - you will also receive a copy by email.</td></tr>
<form action="<?php echo $base_url; ?>store/index.php" method="POST">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" name="confirm" value="1">
<tr><td align="center"><input type="submit" value="Please Click Here To Leave Our Secure Server When Done"></td></tr>
</form>
<tr><td>&nbsp;</td></tr>
<tr><td align="left"><table border="0">
<tr><td align="left" class="style3">Bill To:</td><td align="left" class="style3">Ship To:</td></tr>

<?php
//email receipt string
$email_str = "";
	$query = "SELECT bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, bill_phone, bill_email, ship_name, ship_address1, ship_address2, ship_city, ship_state, ship_zip, ship_country, ship_phone, delivery, pay_type, cc_type, cc_first_name, cc_last_name, cc_num FROM receipts WHERE user_id='$user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ship_state = $line["ship_state"];
		$email_str .= "Dear " . $line["cc_first_name"] . " " . $line["cc_last_name"] . ",\n\n";
		$email_str .= "Thank you for your Dream-Boost.com order. Your Order Confirmation Number and Order Number is " . $user_id . " Please keep a copy of this email for your records. If you have any questions please call us toll free at 1-888-725-9663.\n\n";
		echo "<tr><td VALIGN=\"TOP\" width=\"300\" align=\"left\" class=\"style2\">";

		$email_str .= "Bill To:\n";
		
		echo $line["cc_first_name"] . " " . $line["cc_last_name"] . "<br>\n";
		$email_str .= $line["cc_first_name"] . " " . $line["cc_last_name"] . "\n";
		
		echo $line["bill_address1"] . "<br>\n";
		$email_str .= $line["bill_address1"] . "\n";
		
		if ($line["bill_address2"]) {
			echo $line["bill_address2"] . "<br>\n";
			$email_str .= $line["bill_address2"] . "\n";
		}
		echo $line["bill_city"] . ", " . $line["bill_state"] . "<br>\n";
		$email_str .= $line["bill_city"] . ", " . $line["bill_state"] . "\n";
		
		echo $line["bill_zip"] . ", " . $line["bill_country"] . "<br>\n";
		$email_str .= $line["bill_zip"] . ", " . $line["bill_country"] . "\n\n";

		echo "</td><td VALIGN=\"TOP\" width=\"300\" align=\"left\" class=\"style2\">";

		$email_str .= "Ship To:\n";
		echo $line["ship_name"] . "<br>\n";
		$email_str .= $line["ship_name"] . "\n";
		
		echo $line["ship_address1"] . "<br>\n";
		$email_str .= $line["ship_address1"] . "\n";
		
		if ($line["ship_address2"]) {
			echo $line["ship_address2"] . "<br>\n";
			$email_str .= $line["ship_address2"] . "\n";
		}
		echo $line["ship_city"] . ", " . $line["ship_state"] . "<br>\n";
		$email_str .= $line["ship_city"] . ", " . $line["ship_state"] . "\n";
		
		echo $line["ship_zip"] . ", " . $line["ship_country"] . "<br>\n";
		$email_str .= $line["ship_zip"] . ", " . $line["ship_country"] . "\n\n";

		echo "</font></td></tr>\n";
		
		echo "</table></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\" class=\"style2\"><b>Payment Information:</b><br>\n";
		$email_str .= "Payment Information:\n";
		
		if($line["pay_type"] == "cc") {
			echo $line["cc_first_name"] . " " . $line["cc_last_name"] . "<br>\n";
			$email_str .= $line["cc_first_name"] . " " . $line["cc_last_name"] . "\n";
			
			if ($line["cc_type"] == "mc") {
				echo "Mastercard";
				$email_str .= "Mastercard";
			} elseif ($line["cc_type"] == "vi") {
				echo "Visa";
				$email_str .= "Visa";
			} elseif ($line["cc_type"] == "am") {
				echo "American Express";
				$email_str .= "American Express";
			} elseif ($line["cc_type"] == "di") {
				echo "Discover";
				$email_str .= "Discover";
			}
			echo "<br>\n";
			$email_str .= "\n";
			
			$tmp_cc_num = $line["cc_num"];
			$tmp_cc_num = substr($tmp_cc_num, -4);
			echo "XXXXXXXXXXXX" . $tmp_cc_num . "<br><br>\n";
			$email_str .= "XXXXXXXXXXXX" . $tmp_cc_num . "\n\n";
			
		} elseif ($line["pay_type"] == "mo") {
			echo "When paying by Money Order please print a second copy of this page, enclose with your Money Order and send to:<br>\n";
			$email_str .= "When paying by Money Order please print a second copy of your receipt, enclose with your Money Order and send to:\n";
			
			echo "The Upstate Dream Institute<br>\n";
			$email_str .= "The Upstate Dream Institute\n";
			
			echo "P.O. Box 4077<br>\n";
			$email_str .= "P.O. Box 4077\n";
			echo "Ithaca, NY 14852 US<br>\n";
			$email_str .= "Ithaca, NY 14852 US\n";
			
			echo "[Note: Your order will ship as soon as we receive your Money Order]<br><br>\n";
			$email_str .= "[Note: Your order will ship as soon as we receive your Money Order]\n\n";
		}
				
		if ($line["delivery"] !== "") {
			echo "<b>Delivery Information</b> ";
			$email_str .= "Delivery Information\n";
			
			echo $line["delivery"] . "<br><br>\n";
			$email_str .= $line["delivery"] . "\n\n";
		}
		$bill_email = $line["bill_email"];
	}
	mysql_free_result($result);

?>

</font></td></tr>
<tr><td align="left"><table border="0">
<tr><td align="left" class="style3">Product</td><td align="center" class="style3">Quantity</td><td align="center" class="style3">Price</td><td align="center" class="style3">Sub-Total</td></tr>

<?php
$email_str .= "Product                                               Quantity    Price    Sub-Total\n";
$subtotal = 0;
	$query = "SELECT receipt_items.sku as sku, receipt_items.quantity as quantity, receipt_items.price as price, receipt_items.name as name FROM receipts, receipt_items WHERE receipts.receipt_id=receipt_items.receipt_id AND receipts.user_id='$user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td VALIGN=\"TOP\" class=\"style2\">";

		$tmp_sku = $line["sku"];
		
		//find product url
		$query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   foreach ($line2 as $col_value2) {
		       $url = "$col_value2";
		       $url = substr($url, 1);
		       $url = $base_url . "store" . $url;
		   }
		}
		mysql_free_result($result2);

		echo "<a href=\"$url\">";
		echo $line["name"];
		$email_str .= $line["name"] . " SKU: " . $tmp_sku . "       ";
		
		echo "</a><br><font size=\"-1\">SKU: ";
		echo $tmp_sku;
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\" class=\"style2\">";
		$tmp_quantity = $line["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		echo $tmp_quantity;
		$email_str .= $tmp_quantity . "      ";
		
		echo "</td><td align=\"center\" VALIGN=\"TOP\" class=\"style2\">$";
		$tmp_price = $line["price"];
		$tmp_price = sprintf("%01.2f", $tmp_price);
		echo $tmp_price;
		$email_str .= "$" . $tmp_price . "   ";
		
		echo "</td><td align=\"right\" VALIGN=\"TOP\" class=\"style2\">$";
		$tmp_subtotal = $line["quantity"] * $tmp_price;
		$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
		echo $tmp_subtotal;
		$email_str .= "$" . $tmp_subtotal . "\n";
		
		echo "</td></tr>\n";
		
		$subtotal = $subtotal + $tmp_subtotal;
	}
	mysql_free_result($result);

?>

<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Sub-Total</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
$subtotal = sprintf("%01.2f", $subtotal);
echo $subtotal;
$email_str .= "Sub-Total $" . $subtotal ."\n";
?>
</td></tr>
<tr><td>&nbsp;</td><td colspan="2" VALIGN="TOP" align="right" class="style3">Shipping and Handling</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
	$query = "SELECT free_shipping_offered, free_shipping FROM ship_main WHERE ship_main_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$free_shipping_offered = $line["free_shipping_offered"];
		$free_shipping = $line["free_shipping"];
	}
	mysql_free_result($result);

if($free_shipping_offered == "1") {
	if($subtotal >= $free_shipping) {
		echo "0.00";
		$shipping = 0;
		$email_str .= "Shipping $0.00\n";
	} else {
		echo "4.95";
		$shipping = 4.95;
		$email_str .= "Shipping $4.95\n";
	} 
} else {
	echo "4.95";
	$shipping = 4.95;
	$email_str .= "Shipping $4.95\n";
}
?>
</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Tax</td><td VALIGN="TOP" align="right" class="style3">$
<?php
if($instate_tax == '1') {
	if($state_tax == $ship_state) {
		$tax = $subtotal * $main_tax;
	} else {
		$tax = '0.00';
	}
} else {
	$tax = $subtotal * $main_tax;
}
$tax = sprintf("%01.2f", $tax);
echo $tax;
$email_str .= "Tax $" . $tax . "\n";
?>
</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Total</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
$total = $subtotal + $shipping + $tax;
$total = sprintf("%01.2f", $total);
echo $total;
$email_str .= "Total $" . $total . "\n\n";
?>
</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>

<tr><td align="left" class="style2">Note: All charges will appear as NAP & Associates, the company that produces Dream Boost.</td></tr>
<tr><td>&nbsp;</td></tr>

<?php
$email_str .= "Note: All charges will appear as NAP & Associates, the company that produces Dream-Boost.\n\n\n";

echo "</table>";

$email_subj = "Your DreamBoost.com Order";
$email_from = "FROM: info@dreamboost.com";
mail($bill_email, $email_subj, $email_str, $email_from);

include '../includes/foot1.php';
mysql_close($dbh);
?>