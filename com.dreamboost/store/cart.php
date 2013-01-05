<?php
// BME WMS
// Page: Shopping Cart
// Path/File: /store/cart.php
// Version: 1.1
// Build: 1116
// Date: 12-13-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include_once '../includes/cart1.php';

$quantity = $_POST["quantity"];
$sku = $_POST["sku"];
$cart_update = $_POST["cart_update"];

if($sku) {
	$okay_to_insert = true;

	//find product name
	$query = "SELECT name, prod_id FROM product_skus WHERE sku='$sku'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	       $name = $line["name"];
	       $prod_id = $line["prod_id"];
	}
	mysql_free_result($result);

	//if it's one of the special deals, make sure quantity=1 and check cart for other special deals
	if ( in_array($prod_id, $free_prods_arr) ) {
		if ( $quantity > 1 ) {
			$okay_to_insert = false;
			$error_txt = "Sorry, you may only choose one of our free trials!";
		} else {

			$queryX = "SELECT sku FROM cart WHERE (user_id='$user_id' OR (member_id='$member_id' AND member_id!=0)) AND site='".$_SERVER['HTTP_HOST']."'";
			$resultX = mysql_query($queryX, $dbh_master) or die("Query failed : " . mysql_error());
			if ( mysql_num_rows($resultX) > 0 ) {
				while ($lineX = mysql_fetch_array($resultX, MYSQL_ASSOC)) {

					$query2 = "SELECT prod_id FROM product_skus WHERE sku='".$lineX["sku"]."'";
					$result2 = mysql_query($query2) or die("query2 failed: " . mysql_error());
					while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
						$tmp_prod_id = $line2["prod_id"];
					}

					if ( in_array($tmp_prod_id, $free_prods_arr) ) {
						$okay_to_insert = false;
						$error_txt = "Sorry, you already have one of our free trials in your cart!";
						break;
					}
				}
			}
		}
	}

	//check if SKU is already in cart
	$querySKU = "SELECT sku, cart_id, quantity FROM cart WHERE user_id='$user_id' AND site='".$_SERVER['HTTP_HOST']."' AND sku='$sku'";
	$resultSKU = mysql_query($querySKU, $dbh_master) or die("Query failed : " . mysql_error());
	while ($lineSKU = mysql_fetch_array($resultSKU, MYSQL_ASSOC)) {
		$tmp_cart_id = $lineSKU["cart_id"];
		$tmp_cart_qty = $lineSKU["quantity"];
	}

	if ( $okay_to_insert ) {
        if ( mysql_num_rows($resultSKU)>0 ) {//already there, so update the amount instead of inserting
            $tmp_cart_qty = $tmp_cart_qty + $quantity;
            $queryQU = "UPDATE cart SET quantity='$tmp_cart_qty' WHERE cart_id='$tmp_cart_id'";
            $resultQU = mysql_query($queryQU, $dbh_master) or die("Query failed : " . mysql_error());	
        }
        else {//insert entry to cart table
            $now = date("Y-m-d H:i:s");
            $query = "INSERT INTO cart SET created='$now', user_id='$user_id', member_id='$member_id', quantity='$quantity', sku='$sku', name='$name', site='".$_SERVER['HTTP_HOST']."'";
            $result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
        }
    }
} elseif ($sku == "" && $quantity != "" && $cart_update == "") {
	$error_txt = "Error: You did not select which size unit to purchase. Please click the <b>Back</b> button and select a size (1/2 gram or 1 gram)";
}

if($cart_update) {
	$queryAll = "SELECT * FROM partner_sites";//only consolidate partner site carts
	$resultAll = mysql_query($queryAll) or die("Query failed: " . mysql_error());
	while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {

		$query = "SELECT cart_id, quantity FROM cart WHERE user_id='$user_id' AND site='".$lineAll["site_url"]."'";
		$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$tmp_cart_id = $line["cart_id"];
			$tmp_cart_id_del = $line["cart_id"] . "_del";

			$tmp_cart_id_del = $_POST["$tmp_cart_id_del"];
			$tmp_cart_id_qty = $line["cart_id"] . "_qty";
			$tmp_cart_id_qty = $_POST["$tmp_cart_id_qty"];

			if($tmp_cart_id_del == '1' || $tmp_cart_id_qty==0) {
				$query2 = "DELETE FROM cart WHERE cart_id='$tmp_cart_id'";
				$result2 = mysql_query($query2, $dbh_master) or die("Query failed : " . mysql_error());	
			} else {
				if($tmp_cart_id_qty !== $line["quantity"]) {
					$query3 = "UPDATE cart SET quantity='$tmp_cart_id_qty' WHERE cart_id='$tmp_cart_id'";
					$result3 = mysql_query($query3, $dbh_master) or die("Query failed : " . mysql_error());	
				}
			}
		}
		mysql_free_result($result);
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Shopping Cart</title>

<?php
include '../includes/meta1.php';
?>

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">

<?php
include '../includes/head1.php';

?>

<table border="0" width="90%">

<tr><td align="left" class="style4"><img alt="Online Store" 
	  src="<?=$current_base?>images/OnlineStore.gif" /></td></tr>

<tr><td align="left" class="style4"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2"><a href="index.php">Online Store</a> > Shopping Cart</font></td></tr>

<?php
/*
?>
<tr><td align="left"><font face="<?php echo $font; ?>" color="red" size="+1"><b>Note: Currently we are not able to accept <!--credit card--> orders. We are working to resolve this problem. If you would like to <!--use a credit card to--> place an order please come back in a couple days. We apologize for any inconvenience.</b></font></td></tr>
<?php
*/
?>

<tr><td class="text_right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><a href="./index.php">Continue Shopping</a></font></td></tr>

<?php
if($error_txt) { 
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo '<tr><td class="error text_left">'.$error_txt.'</td></tr>';
	echo "<tr><td>&nbsp;</td></tr>\n";
}

if ( $_SESSION["order_info"]["percent_off"] && $_SESSION["order_info"]["percent_off"] != 1) {
	echo '<tr><td><span class="error3">Discount code '.$_SESSION["order_info"]["discount_code"].' applied: '.( (1 - $_SESSION["order_info"]["percent_off"]*1) * 100).'% off</span></td></tr>';
}

?>

<tr><td align="left">

<?php
$thisSubTotal = 0;
$thisQtyTotal = 0;
echo '
<form action="./cart.php" method="POST">
<input type="hidden" name="cart_update" value="1" />

<table border="0" id="cart_table">';

	$thisCart = createCartTable( $_SERVER["HTTP_HOST"], $dbh );
	echo $thisCart["cartHTML"];
	$thisSubTotal += $thisCart["thisSubtotal"];
	$thisQtyTotal += $thisCart["thisQty"];

	$querySites = "SELECT * FROM partner_sites WHERE site_url != '".$_SERVER["HTTP_HOST"]."'";

	$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
	while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
		$thisDBHName = "dbh".$lineSites["site_key_name"];

		$thisCart = createCartTable( $lineSites["site_url"], $$thisDBHName );
		echo $thisCart["cartHTML"];
		$thisSubTotal += $thisCart["thisSubtotal"];
		$thisQtyTotal += $thisCart["thisQty"];

		if ( !$thisCart["in_cart"] ) {
			$not_in_cart[] = $lineSites["site_url"];
		}
	}

	if ( $thisQtyTotal > 0 ) {
		echo '
		<tr class="style3"><td colspan="5"><hr /></tr>
		<tr>
			<td class="text_right" colspan="3"><b>SUB-TOTAL</b></td>
			<td class="text_right"><b>$'.condDecimalFormat($thisSubTotal).'</b></td>
			<td>&#160;</td>
		</tr>';

		// START free shipping for this site
		$query = "SELECT free_shipping_offered, free_shipping FROM ship_main WHERE ship_main_id='1'";
		$result = mysql_query($query, $dbh) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$free_shipping_offered = $line["free_shipping_offered"];
			$free_shipping = $line["free_shipping"];
		}
		mysql_free_result($result);
			
		if($free_shipping_offered == "1") {
			echo "<tr><td colspan=\"5\" class=\"text_center\">Retail orders totaling <b>$";
			echo $free_shipping;
			echo " or more on ".$product_name." products receive FREE <a href=\"./shipping.php\">Shipping and Handling</a></b></td></tr>\n";
		}
		// END free shipping for this site

		// START free shipping for all sites
		$query = "SELECT free_shipping_offered, free_shipping FROM ship_global LIMIT 1";
		$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$free_shipping_offered_g = $line["free_shipping_offered"];
			$free_shipping_g = $line["free_shipping"];
		}
		mysql_free_result($result);
			
		if($free_shipping_offered_g == "1") {
			echo "<tr><td colspan=\"5\" class=\"text_center\">Retail orders totaling <b>$";
			echo $free_shipping_g;
			echo " or more on products from this and any of our partner sites receive FREE <a href=\"./shipping.php\">Shipping and Handling</a></b></td></tr>\n";
		}
		// END free shipping for all sites
		

		echo '
		<tr><td colspan="4">&#160;</td><td VALIGN="TOP" class="text_center"> <input type="submit" value="Update Cart"> </td></tr>
		</form>
		<tr><td colspan="5">&nbsp;</td></tr>';

		echo '<form action="'.$base_secure_url.'store/step1.php" method="POST">
		<input type="hidden" name="user_id" value="'.$user_id.'">
		<input type="hidden" name="cart" value="1">
		<tr><td colspan="5" class="text_right"><input type="submit" value="Secure Checkout"></td></tr>';
	}
echo '
</table><!--cart_table -->
</form>';

?>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<?php
if ( count($not_in_cart) > 0 ) {
	echo '<div class="style3">Don\'t forget to shop at our great partner sites (you still only have to check out once!):<br />';
	foreach($not_in_cart as $a_site_key=>$a_site_url) {
		echo '<a href="http://'.$a_site_url.(strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ?'/staging':'').'/">'.$a_site_url.'</a>';

		echo '<a href="http://'.$a_site_url.(strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ?'/staging':'').'/" style="text-decoration:none; border:0px">&#160;<img src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$a_site_url.'/favicon.ico" style="text-decoration:none; border: 0px" align="absmiddle" /></a>';
	}
	echo '</div>';
}

include '../includes/foot1.php';
mysql_close($dbh);
?>
</body>
</html>