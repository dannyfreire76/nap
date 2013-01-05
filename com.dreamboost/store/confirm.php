<?php
// BME WMS
// Page: Checkout Confirm page
// Path/File: /store/confirm.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include_once('../includes/customer.php');

$order_number = $_SESSION["orderNum"];
if(!$order_number) {
	header("Location: " . $base_url . "store/");
	exit;
}

//empty cart
$queryD = "DELETE FROM cart WHERE user_id='$order_number'";
$resultD = mysql_query($queryD, $dbh_master) or die("Query failed : " . mysql_error());

//force login to partner sites
echo checkPartnerSiteMembers($_SESSION["member_email"]);


mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Checkout</title>

<?php
include '../includes/meta1.php';
include '../includes/store_includes.php';
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

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Store - Checkout: Confirmation</font></td></tr>


<?php
//email receipt string
$email_str = "";
	$query = "SELECT * FROM receipts WHERE user_id='$order_number'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$shipping = $line["shipping"];
		$tax = $line["tax"];
		$ship_state = $line["ship_state"];

		echo '<tr><td align="left"><font face="'.$font.'" color="#'.$fontcolor.'" size="+1">Thank you for your order. Your Order Confirmation Number and Order Number is <b>'.$order_number.'</b>.<br>';

		$email_str .= "Dear " . $line["bill_name"] . ",\n\n";
		$email_str .= "Thank you for your ".$website_title." order. Your Order Confirmation Number and Order Number is " . $order_number . ". Please keep a copy of this email for your records. If you have any questions please call us toll free at ".$company_phone.".\n\n";
		
		if ( $line["recurring_orders_id"] ) {
			$queryRecur = "SELECT * FROM recurring_orders WHERE recurring_orders_id='".$line["recurring_orders_id"]."'";
			$resultRecur = mysql_query($queryRecur) or die("Query failed : $queryRecur" . mysql_error());
			while ($lineRecur = mysql_fetch_array($resultRecur, MYSQL_ASSOC)) {

				$email_str .= 'This order is set to repeat every '.$lineRecur["recurring_interval"];
				echo 'This order is set to repeat every '.$lineRecur["recurring_interval"];
				if ( $lineRecur["recurring_length"] ) {
					$email_str .= " for the next ".$lineRecur["recurring_length"];
					echo " for the next ".$lineRecur["recurring_length"];
				}
				$email_str .= ".\n\n";
				echo '<br /><br />';
			}
		}

		if ( strpos( strtolower($website_title), "salvia") !==false ) {
			$email_str .= "Please use the discount code below to SAVE 35% off your next online purchase from ".$website_title.".\n";
			$email_str .= "DISCOUNT CODE: szr1\n\n";
		}		

		echo 'Please print this page as your receipt - you will also receive a copy by email.</font></td></tr>'.
			'<tr><td>&nbsp;</td></tr>'.
			'<tr><td align="left"><table border="0">'.
			'<tr><td align="left"><font face="'.$font.'" color="#'.$fontcolor.'" size="+1"><b>Bill To:</b></font></td><td align="left"><font face="'.$font.'" color="#'.$fontcolor.'" size="+1"><b>Ship To:</b></font></td></tr>';

		echo "<tr><td align=\"left\" VALIGN=\"TOP\" width=\"300\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

		$email_str .= "Bill To:\n";
		
		echo $line["bill_name"]."<br>\n";
		$email_str .= $line["bill_name"]."\n";
		
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

		echo "</font></td></tr>";
		
		echo "</table></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>Payment Information:</b><br>\n";
		$email_str .= "Payment Information:\n";
		
		if($line["pay_type"] == "cc") {
			if($line["payment_profile_id"]) {
				echo "Saved Card on file: ID ".$line["payment_profile_id"]."<br /><br />\n";
				$email_str .= "Saved Card on file: ID ".$line["payment_profile_id"]."\n\n";
			} else {

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
			}			
		} elseif ($line["pay_type"] == "mo") {
			echo "When paying by Money Order please print a second copy of this page, enclose with your Money Order and send to:<br>\n";
			$email_str .= "When paying by Money Order please print a second copy of your receipt, enclose with your Money Order and send to:\n";
			
			echo $company_name."<br>\n";
			$email_str .= $company_name."\n";
			
			echo $company_address."<br>\n";
			$email_str .= $company_address."\n";
			echo $company_city_state_zip."<br>\n";
			$email_str .= $company_city_state_zip."\n";
			
			echo "[Note: Your order will ship as soon as we receive your Money Order]<br><br>\n";
			$email_str .= "[Note: Your order will ship as soon as we receive your Money Order]\n\n";
		}
				
		if ($line["delivery"] !== "") {
			echo "<b>Delivery Information:</b> ";
			$email_str .= "Delivery Information:\n";
			
			echo $line["delivery"] . "<br><br>\n";
			$email_str .= $line["delivery"] . "\n\n";
		}
		$bill_email = $line["bill_email"];
	}
	mysql_free_result($result);

?>
</font></td></tr>
<tr><td align="left"><table border="0">

<?php
$subtotal = 0;

$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";//this ORDER BY makes sure this site is first
$resultSites = mysql_query($querySites) or die("Query 2 failed: " . mysql_error());

while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
	$thisDBHName = "dbh".$lineSites["site_key_name"];
	$thisHandle = $$thisDBHName;

	$query = "SELECT receipt_items.sku as sku, receipt_items.quantity as quantity, receipt_items.price as price, receipt_items.name as name FROM receipts, receipt_items WHERE receipts.receipt_id=receipt_items.receipt_id AND receipts.user_id='$order_number'";
	$result = mysql_query($query, $thisHandle) or die("Query failed : " . mysql_error());

	if ( mysql_num_rows($result)>0 ) {
		echo '<tr><td colspan="4"><b>From '.$lineSites["site_title"].':</b></td></tr>';
		echo '<tr><td align="left"><b>Product</b></td><td align="center"><b>Quantity</b></td><td align="center"><b>Price</b></td><td align="center"><b>Sub-Total</b></td></tr>';

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<tr><td align=\"left\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

			$tmp_sku = $line["sku"];
			
			//find product url
			$query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$tmp_prod_id = $line2["prod_id"];
			}
			mysql_free_result($result2);

			echo "<a href=\"product.php?prod_id=$tmp_prod_id\">";
			echo $line["name"];

			$email_name_sku = $line["name"] . " SKU: " . $tmp_sku;
			$paddingLen = 60 - strlen($email_name_sku);
			$padding = "";
			for ($padCnt=1; $padCnt<$paddingLen; $padCnt++) {
				$padding .= " ";
			}
			
			$email_str .= $email_name_sku.$padding;
			
			echo "</a><br><font size=\"-1\">SKU: ";
			echo $tmp_sku;
			echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
			$tmp_quantity = $line["quantity"];
			if($buy_one_get_one_free == '1') {
				$tmp_quantity = $tmp_quantity * 2;
			}
			echo $tmp_quantity;
			$email_str .= "Quantity: ".$tmp_quantity . "      ";
			
			echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";
			$tmp_price = $line["price"];
			$tmp_price = sprintf("%01.2f", $tmp_price);
			echo $tmp_price;
			$email_str .= "Price: $" . $tmp_price . "   ";
			
			echo "</font></td><td align=\"right\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";
			$tmp_subtotal = $line["quantity"] * $tmp_price;
			$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
			echo $tmp_subtotal;
			$email_str .= "Subtotal: $" . $tmp_subtotal . "\n";
			
			echo "</font></td></tr>\n";
			
			$subtotal = $subtotal + $tmp_subtotal;
		}
	}
	mysql_free_result($result);
}
?>

<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Sub-Total</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php 
$subtotal = sprintf("%01.2f", $subtotal);
echo $subtotal;
$email_str .= "Sub-Total $" . $subtotal ."\n";
?>
</b></font></td></tr>
<tr><td align="left">&nbsp;</td><td colspan="2" VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Shipping and Handling</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php 
echo $shipping;
$email_str .= "Shipping $".$shipping."\n";

?>
</b></font></td></tr>
<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Tax</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php
echo $tax;
$email_str .= "Tax $" . $tax . "\n";
?>
</b></font></td></tr>
<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Total</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php 
$total = $subtotal + $shipping + $tax;
$total = sprintf("%01.2f", $total);
echo $total;
$email_str .= "Total $" . $total . "\n\n";
?>
</b></font></td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Note: All charges will appear as <?=$company_name?>, the company that produces <?=$product_name?>.</font></td></tr>
<tr><td>&nbsp;</td></tr>

<?php
$email_str .= "Note: All charges will appear as ".$company_name.", the company that produces ".$product_name.".\n\n\n";

echo "</table>";

$email_subj = "Your ".$website_title." Order";
$email_from = "FROM: ".$site_email;

if ( $_SESSION["sendMail"] ) {//makes sure mail only gets sent once
	mail($bill_email, $email_subj, $email_str, $email_from);
	$_SESSION["sendMail"]=false;
}

unset($_SESSION['ship_state'], $_SESSION['shipping_info']);//clear session info
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>