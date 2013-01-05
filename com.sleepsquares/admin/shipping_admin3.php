<?php
ob_start();

include '../includes/main1.php';
include_once("./includes/retailer1.php");

$receipt_id = $_REQUEST["receipt_id"];

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

<html>
<head>
<title><?php echo $website_title; ?>: Receipt</title>
<script type="text/javascript">

//$(function() {//on doc ready
//	textAreasExpandable();
//});

</script>
</head>
<body>
<div align="center">

<?php
include './includes/head_admin1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td><table border="0">

<?php
//email receipt string
$email_str = "";
	$query = "SELECT * FROM receipts WHERE receipt_id='$receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ship_state = $line["ship_state"];
		$tax = $line["tax"];

		$email_str .= "Dear " . $line["bill_name"] . ",\n\n";
		$email_str .= "Thank you for your ";
		$email_str .= $website_title;
		$email_str .= " order. Your Order Confirmation Number and Order ";
		$email_str .= "Number is " . $line["user_id"] . ". Your Order has shipped from our facility and is in ";
		$email_str .= "route to the shipping address you entered. If you have any questions please call us ";
		$email_str .= "toll free at ".$company_phone.".\n\n";
        echo "<tr><td><font face='Arial' size='+1'><b>Order Date: </b>".date( "m/d/Y", strtotime($line["ordered"]) )."</font></td></tr>";
		$email_str .= "Order Date: ".date( "m/d/Y", strtotime($line["ordered"]) )."\n\n";

        echo '<tr><td><font face="Arial" size="+1"><b>Bill To:</b></font></td><td><font face="Arial" size="+1"><b>Ship To:</b></font></td></tr>';

		echo "<tr><td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

		$email_str .= "Bill To:\n";
		
		echo $line["bill_name"]. "<br>\n";
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
		$email_str .= $line["bill_zip"] . ", " . $line["bill_country"] . "\n";

		echo $line['bill_email'] . "<br>\n";
		$email_str .=  $line['bill_email'] . "\n\n";
		
		echo "</font></td><td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

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
		echo "<tr><td><font face=\"Arial\" size=\"+1\">";
		echo "<table><tr valign=\"top\"><td width=\"300px\"><b>Payment Information:</b><br>\n";
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
			echo "Money Order<br><br>\n";
			$email_str .= "Money Order\n\n";
		}
				
		if ($line["delivery"] !== "") {
			echo "<b>Delivery Information</b> ";
			$email_str .= "Delivery Information\n";
			
			echo $line["delivery"] . "<br><br>\n";
			$email_str .= $line["delivery"] . "\n\n";
		}
		$bill_email = $line['bill_email'];
		echo '	</font>
				</td>
				<td>
					<b>Order Number:</b><br />'.$line["user_id"].'
				</td>
			</tr>
		</table>';
	}

	mysql_free_result($result);

?>

</td>
</tr>
<tr><td><table border="0" cellpadding="4" cellspacing="0" style="width:100%">
<tr><td colspan="2"><font face="Arial" size="+1"><b>Product</b></font></td><td align="center"><font face="Arial" size="+1"><b>Quantity</b></font></td><td align="center"><font face="Arial" size="+1"><b>Price</b></font></td><td align="center"><font face="Arial" size="+1"><b>Sub-Total</b></font></td><td align="center"><font face="Arial" size="+1"><b>Tracking Number</b></font></td></tr>

<?php
$subtotal = 0;
$shipping=0;

$main_table = 'receipt';
$orderUID = getOrderUserID($receipt_id);
$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
$line_counter = 0;

while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
	$thisDBHName = "dbh".$lineSites["site_key_name"];

	if ( $orderUID!="" ) {
		$query = "SELECT * FROM receipts t1, receipt_items t2 WHERE t1.user_id='$orderUID' AND t1.receipt_id=t2.receipt_id";
		if ( $lineSites["site_url"] != $_SERVER["HTTP_HOST"] ) {
			$query .= " AND t1.user_id >= ".$min_global_user_id;
		}
	} else {
		$query = "SELECT * FROM receipts t1, receipt_items t2 WHERE t1.receipt_id=t2.receipt_id AND receipt_id='$receipt_id'";
	}

	$siteItemCount = 0;
	$result = mysql_query($query, $$thisDBHName) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$siteItemCount++;
		echo "<tr><td VALIGN=\"TOP\">";

		$tmp_sku = $line["sku"];
		
		//find product url
		$query2 = "SELECT ps.*, pc.name AS prod_cat_name FROM product_skus ps, product_categories pc, products p WHERE ps.sku='$tmp_sku' AND ps.prod_id=p.prod_id AND p.prod_cat_id=pc.prod_cat_id";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   foreach ($line2 as $col_name2 => $col_val2) {
			   $this_sku[$col_name2] = $col_val2;
		   }
		}

		echo '<img src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineSites['site_url'].'/favicon.ico" align="absmiddle" style="padding-right: 6px; padding-top: 5px" /></td>';
		echo '<td>';
		echo $line["name"];
		$email_str .= "Product: ".$line["name"] . " SKU: " . $tmp_sku . "                      ";
		
		echo "<br><font size=\"-1\">SKU: ";
		echo $tmp_sku;
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\">";
		$tmp_quantity = $line["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		echo $tmp_quantity;
		$email_str .= "Qty: ".$tmp_quantity . "                      ";
		
		echo "</td><td align=\"center\" VALIGN=\"TOP\">$";
		$tmp_price = $line["price"];
		$tmp_price = sprintf("%01.2f", $tmp_price);
		echo $tmp_price;
		$email_str .= "Price: $" . $tmp_price . "                      ";
		
		echo "</td><td align=\"right\" VALIGN=\"TOP\">$";
		$tmp_subtotal = $line["quantity"] * $tmp_price;
		$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
		echo $tmp_subtotal;
		$email_str .= "Item Subtotal: $" . $tmp_subtotal . "                      ";
		
		echo "</td><td VALIGN=\"TOP\">";
		if ( $line["tracking_num"] ) {
			echo $line["tracking_num"];
			$email_str .= 'Tracking #: '.$line["tracking_num"];

		} else {
			echo "------";
		}
		echo "</td></tr>\n";
		$email_str .= "\n";
		
		$subtotal = $subtotal + $tmp_subtotal;

		if ( $siteItemCount==1 ) {//only add the shipping once, on the first item found for a site's cart
			$shipping += $line["shipping"];
		}

		//echo 'debug: '.$line["shipping"].'<br />';

	}
	mysql_free_result($result);
}
?>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>Sub-Total</b></font></td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>$<?php 
$subtotal = sprintf("%01.2f", $subtotal);
echo $subtotal;
$email_str .= "\nSub-Total $" . $subtotal ."\n";
?>
</b></font></td></tr>
<tr><td>&nbsp;</td><td colspan="2" VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>S & H</b></font></td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>$<?php
$shipping = sprintf("%01.2f", $shipping);
echo $shipping;
$email_str .= "Shipping $" . $shipping . "\n";
?>
</b></font></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>Tax</b></font></td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>$<?php
$tax = sprintf("%01.2f", $tax);
echo $tax;
$email_str .= "Tax $" . $tax . "\n";
?>
</b></font></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>Total</b></font></td><td VALIGN="TOP" align="right"><font face="Arial" size="+1"><b>$<?php 
$total = $subtotal + $shipping + $tax;
$total = sprintf("%01.2f", $total);
echo $total;
$email_str .= "Total $" . $total . "\n\n\n";
?>
</b></font></td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>
<tr><td>
<font face="Arial" size="+1"><b>Notes:</b><br />
<textarea style="height:200px" cols="70"></textarea>
<br /><br />
Note: All charges will appear as <?php echo $company_name; ?>
<?php
if ( strpos( strtolower($_SERVER["HTTP_HOST"]), "salvia")===false ) {
	echo ', the company that produces '.$product_name;
}
?>

</font></td></tr>
<tr><td>&nbsp;</td></tr>
</table>

<?php
if ( !$_REQUEST["show_only"] ) {
    $email_str .= "\n";
    $email_str .= "Note: All charges will appear as $company_name";

	if ( strpos( strtolower($_SERVER["HTTP_HOST"]), "salvia")===false ) {
		$email_str .= ", the company that produces $product_name.";
	}
	
	$email_str .= "\n\n\n";

    $email_subj = "Your " . $website_title . " Order has Shipped";
    $email_from = "FROM: $site_email";
    mail($bill_email, $email_subj, $email_str, $email_from);

	// Just wanted notice to be sent, so send back to referrer
	header("Location: ".$_SERVER["HTTP_REFERER"]."?receipt_id=" . $receipt_id);
	exit;
}
?>
</div>
</body>
</html>