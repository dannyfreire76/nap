<?php 

function calcShippingGlobal($subtotal, $shipping) {
	global $dbh_master;

	$query = "SELECT free_shipping_offered, free_shipping FROM ship_global LIMIT 1";
	$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$free_shipping_offered = $line["free_shipping_offered"];
		$free_shipping = $line["free_shipping"];
	}
	mysql_free_result($result);

	if($free_shipping_offered == "1" && $subtotal >= $free_shipping) {
		$shipping = 0;
	}

	return $shipping;
}

function calcShipping($subtotal, $thisHandle=null) {
	global $dbh;

	if ( !$thisHandle ) {
		$thisHandle = $dbh;
	}

	$query = "SELECT free_shipping_offered, free_shipping FROM ship_main WHERE ship_main_id='1'";
	$result = mysql_query($query, $thisHandle) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$free_shipping_offered = $line["free_shipping_offered"];
		$free_shipping = $line["free_shipping"];
	}
	mysql_free_result($result);

	if($free_shipping_offered == "1" && $subtotal >= $free_shipping) {
		$shipping = 0;
	}
	else {
		$shipping_info = $_SESSION['shipping_info'];
		$cost_id = 9;

		if ( $shipping_info['ship_country'] == 'US' ) {
			if ( $shipping_info['ship_state'] == 'AK' ) {
				$cost_id = 2;
			}
			else if ( $shipping_info['ship_state'] == 'HI' ) {
				$cost_id = 3;
			}
			else {
				$cost_id = 1;
			}
		}
		else if ( $shipping_info['ship_country'] == 'PR' ) {
			$cost_id = 4;
		}
		else if ( $shipping_info['ship_country'] == 'VI' ) {
			$cost_id = 5;
		}
		else if ( $shipping_info['ship_country'] == 'CA' ) {
			$cost_id = 6;
		}
		else if ( $shipping_info['ship_country'] == 'MX' ) {
			$cost_id = 7;
		}
		else if ( $shipping_info['ship_state'] == 'AE' ) {//Europe
			$cost_id = 8;
		}
		
		$query = "SELECT method_1 FROM ship_cost WHERE ship_cost_id='".$cost_id."'";
		$result = mysql_query($query, $thisHandle) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$shipping = $line["method_1"];
		}
		mysql_free_result($result);
	}

	return $shipping;
}

function calcTax($subtotal) {
	$tax = 0;
	$query = "SELECT tax, instate_tax, state_tax FROM ship_main";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$main_tax = $line["tax"];
		$instate_tax = $line["instate_tax"];
		$state_tax = $line["state_tax"];
	}
	mysql_free_result($result);

	if($instate_tax == '1') {
		if($state_tax == $_SESSION['ship_state']) {
			$tax = $subtotal * $main_tax;
		} else {
			$tax = '0.00';
		}
	} else {
		$tax = $subtotal * $main_tax;
	}
	$tax = sprintf("%01.2f", $tax);
	return $tax;
}

function createReceipt($thisSite, $dbhHandle, $user_id) {
	global $user_id, $member_id, $dbh_master, $base_secure_url, $free_prods_arr, $percent_off;

	echo '
	<table>
	<tr><td align="left"><table border="0">
	<tr><td align="left"><b>Bill To:</b></td><td align="left"><b>Ship To:</b></td></tr>';

	//email receipt string
	$email_str = "";
	
	$query = "SELECT * FROM receipts WHERE user_id='$user_id'";
	$result = mysql_query($query, $dbhHandle) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ship_state = $line["ship_state"];
		$email_str .= "Dear " . $line["bill_name"] . ",\n\n";
		$email_str .= "Thank you for your ".$website_title." order. Your Order Confirmation Number and Order Number is " . $user_id . ". Please keep a copy of this email for your records. If you have any questions please call us toll free at ".$company_phone.".\n\n";
		
		if ( strpos( strtolower($website_title), "salvia") !==false ) {
			$email_str .= "Please use the discount code below to SAVE 35% off your next online purchase from ".$website_title.".\n";
			$email_str .= "DISCOUNT CODE: szr1\n\n";
		}		
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
			echo "<b>Delivery Information</b> ";
			$email_str .= "Delivery Information\n";
			
			echo $line["delivery"] . "<br><br>\n";
			$email_str .= $line["delivery"] . "\n\n";
		}
		$bill_email = $line["bill_email"];
	}
	mysql_free_result($result);

	echo '
	</font></td></tr>
	<tr><td align="left"><table border="0">
	<tr><td align="left"><b>Product</b></td><td align="center"><b>Quantity</b></td><td align="center"><b>Price</b></td><td align="center"><b>Sub-Total</b></td></tr>';

	//$email_str .= "Product                                               Quantity    Price    Sub-Total\n";
	$subtotal = 0;
		$query = "SELECT receipt_items.sku as sku, receipt_items.quantity as quantity, receipt_items.price as price, receipt_items.name as name FROM receipts, receipt_items WHERE receipts.receipt_id=receipt_items.receipt_id AND receipts.user_id='$user_id'";
		$result = mysql_query($query, $dbhHandle) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo "<tr><td align=\"left\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

			$tmp_sku = $line["sku"];
			
			//find product url
			$query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku'";
			$result2 = mysql_query($query2, $dbhHandle) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$tmp_prod_id = $line2["prod_id"];
			}
			mysql_free_result($result2);

			echo "<a href=\"product.php?prod_id=$tmp_prod_id\">";
			echo $line["name"];
			$email_str .= $line["name"] . " SKU: " . $tmp_sku . "       ";
			
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
	mysql_free_result($result);

	echo '
	<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><b>Sub-Total</b></td><td VALIGN="TOP" align="right"><b>$';

	$subtotal = sprintf("%01.2f", $subtotal);
	echo $subtotal;
	$email_str .= "Sub-Total $" . $subtotal ."\n";

	echo '</b></td></tr>
	<tr><td align="left">&nbsp;</td><td colspan="2" VALIGN="TOP" align="right"><b>Shipping and Handling</b></td><td VALIGN="TOP" align="right"><b>$';

	$shipping = calcShipping($subtotal);
	echo $shipping;
	$email_str .= "Shipping $".$shipping."\n";

	echo '
	</b></td></tr>
	<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><b>Tax</b></td><td VALIGN="TOP" align="right"><b>$';

	$tax = calcTax($subtotal);
	$tax = sprintf("%01.2f", $tax);
	echo $tax;
	$email_str .= "Tax $" . $tax . "\n";
	
	echo '
	</b></td></tr>
	<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" align="right"><b>Total</b></td><td VALIGN="TOP" align="right"><b>$';

	$total = $subtotal + $shipping + $tax;
	$total = sprintf("%01.2f", $total);
	echo $total;
	$email_str .= "Total $" . $total . "\n\n";
	
	echo '
	</b></td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	</table></td></tr></table>';

	return array("thisSubtotal"=>$subtotal, "emailStr"=>$email_str);

}//createReceipt
?>