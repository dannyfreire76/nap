<?php
// BME WMS
// Page: WC Checkout Confirm page
// Path/File: /wc/confirm.php
// Version: 1.1
// Build: 1102
// Date: 12-05-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

check_wholesale_login();

/*
$retailer_id = $_COOKIE["wc_user"];
if(!$retailer_id) {
	header("Location: " . $base_url . "store/");
	exit;
}
*/

$wholesale_receipt_id = $_SESSION['wholesale_receipt_id'];

$order_num = $_SESSION['wholesale_order_number'];

//$result = setcookie("wc_user", $retailer_id, time()-3600, "/~salviazo/store/", ".ocservers.net", 1) or die ("Set Cookie failed : " . mysql_error());

function clean_cart($retailer_id) {
	$query = "DELETE FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	unset($_SESSION['wholesale_receipt_id']);
}

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

// Clean WC Cart
clean_cart($retailer_id);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Online Retailer Catalog - Checkout</title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Online Wholesale Catalog - Checkout: Confirmation</font></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Thank you for your order. Your Order Confirmation Number and Order Number is <b><?php echo $order_num; ?></b>. <br>
Please print this page as your receipt - you will also receive a copy by email.</font></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="left"><table border="0">
<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Bill To:</b></font></td><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Ship To:</b></font></td></tr>

<?php
//email receipt string
$email_str = "";
	$query = "SELECT * FROM wholesale_receipts WHERE wholesale_receipt_id='$wholesale_receipt_id' AND retailer_id='$retailer_id' AND complete='1'";
	$result = mysql_query($query) or die("Query failed : $query" . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $shipping = $line["shipping"];
		$ship_state = $line["ship_state"];
		$total = $line["total"];

		if($line["cc_first_name"] == "" || $line["cc_last_name"] == "") {
			$email_str .= "Dear " . $line["bill_name"] . ",\n\n";
		} else {
			$email_str .= "Dear " . $line["cc_first_name"] . " " . $line["cc_last_name"] . ",\n\n";

		}
		$email_str .= "Thank you for your ".$website_title." Retailer order. Your Order Confirmation Number and Order Number is " . $order_num . ".  Please keep a copy of this email for your records. If you have any questions please call us toll free at ".$company_phone.".\n\n";
		echo "<tr><td align=\"left\" VALIGN=\"TOP\" width=\"300\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

		$email_str .= "Bill To:\n";
		echo $line["bill_name"] . "<br>\n";
		
		$email_str .= $line["bill_name"] . "\n";
		
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

		echo "</font></td><td align=\"left\" VALIGN=\"TOP\" width=\"300\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

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
		echo "<tr><td align=\"left\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>Payment Information:</b><br>\n";
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
			
            //$tmp_cc_num = $line["cc_num"];
            //$tmp_cc_num = substr($tmp_cc_num, -4);
            //$email_str .= "XXXXXXXXXXXX" . $tmp_cc_num . "\n\n";
            $email_str .= $line["cc_num"] . "\n\n";
			
		} elseif ($line["pay_type"] == "chk") {
			echo "When paying by Check please print a second copy of this page, enclose with your Check and send to:<br>\n";
			$email_str .= "When paying by Check please print a second copy of your receipt, enclose with your Check and send to:\n";
			
			echo $company_name."<br>";
			$email_str .= $company_name."\n";
			
			echo $company_address."<br>\n";
			$email_str .= $company_address."\n";

			echo $company_city_state_zip."<br>\n";
			$email_str .= $company_city_state_zip."\n";
			
			echo "[Note: Your order will ship as soon as we receive your Check]<br><br>\n";
			$email_str .= "[Note: Your order will ship as soon as we receive your Check]\n\n";
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
<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Product</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Quantity</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Price</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Sub-Total</b></font></td></tr>

<?php
//$email_str .= "Product                                               Quantity    Price    Sub-Total\n";
$subtotal = 0;
	$query = "SELECT wholesale_receipt_items.sku as sku, wholesale_receipt_items.quantity as quantity, wholesale_receipt_items.price as price, wholesale_receipt_items.name as name FROM wholesale_receipts, wholesale_receipt_items WHERE wholesale_receipts.wholesale_receipt_id=wholesale_receipt_items.wholesale_receipt_id AND wholesale_receipts.wholesale_receipt_id='$wholesale_receipt_id' AND wholesale_receipts.retailer_id='$retailer_id' AND wholesale_receipts.complete='1'";
	$result = mysql_query($query) or die("Query failed : $query" . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

		$tmp_sku = $line["sku"];
		
		//find product url
		$query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   $url = $current_base.'store/product.php?prod_id='.$line2["prod_id"];
		}
		mysql_free_result($result2);

		echo '<a href="'.$url.'" target="_blank">';
		echo $line["name"];
		$email_str .= $line["name"] . " SKU: " . $tmp_sku . "       ";
		
		echo "</a><br><font size=\"-1\">SKU: ";
		echo $tmp_sku;
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
		$email_str .= "         Qty: ";
        $tmp_quantity = $line["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		echo $tmp_quantity;
		$email_str .= $tmp_quantity . "         Price: ";
		
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";
		$tmp_price = $line["price"];
		$tmp_price = condDecimalFormat( $tmp_price);
		echo $tmp_price;
		$email_str .= "$" . $tmp_price . "         Subtotal: ";
		
		echo "</font></td><td align=\"right\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";
		$tmp_subtotal = $line["quantity"] * $tmp_price;
		$tmp_subtotal = condDecimalFormat( $tmp_subtotal);
		echo $tmp_subtotal;
		$email_str .= "$" . $tmp_subtotal . "\n";
		
		echo "</font></td></tr>\n";
		
		$subtotal = $subtotal + $tmp_subtotal;
	}
	mysql_free_result($result);

?>

<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Sub-Total</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$<?php 
$subtotal = condDecimalFormat( $subtotal);
echo $subtotal;
$email_str .= "Subtotal $" . $subtotal ."\n";
?>
</b></font></td></tr>
<tr><td align="left">&nbsp;</td><td colspan="2" VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Shipping and Handling</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$<?php 

echo $shipping;
$email_str .= "Shipping $".$shipping."\n";

?>
</b></font></td></tr>
<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Total</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$<?php 
echo $total;
$email_str .= "Total $" . $total . "\n\n";
?>
</b></font></td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Note: All charges will appear as <?=$company_name?>, the company that produces <?=$product_name?>.</td></tr>
<tr><td>&nbsp;</td></tr>

<?php
$email_str .= "Note: All charges will appear as ".$company_name.", the company that produces ".$product_name.".\n\n\n";

echo "</table>";

$email_subj = "Your ".$website_title." Retailer Order";
$email_from = "FROM: ".$site_email;
mail($bill_email, $email_subj, $email_str, $email_from);

include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>