<?php
// BME WMS
// Page: Shopping Cart
// Path/File: /wc/cart.php
// Version: 1.1
// Build: 1105
// Date: 12-06-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

check_wholesale_login();

$quantity = $_POST["quantity"];
$sku = $_POST["sku"];
$cart_update = $_POST["cart_update"];
$price_lvl = $_POST["price_lvl"];

function check_supplies($retailer_id) {
    $supplies_skus= array();
    $querySupplies = "SELECT skus.sku FROM product_skus skus, products WHERE skus.prod_id = products.prod_id AND products.prod_cat_id='6'";
    $resultSupplies = mysql_query($querySupplies) or die("Query failed : " . mysql_error());
    while ($lineSupplies = mysql_fetch_array($resultSupplies, MYSQL_ASSOC)) {
        $supplies_skus[] = $lineSupplies["sku"];
    }
    return $supplies_skus;
}

if ( $sku || $cart_update ) {
    //if we're changing the cart, get rid of promo items because they need to be recalculated later
    $supplies_skus = check_supplies($retailer_id);
    foreach($supplies_skus as $key=>$del_sku) {
        $queryDel = "DELETE FROM wc_cart WHERE retailer_id='$retailer_id' AND sku='$del_sku'";
        $resultDel = mysql_query($queryDel) or die("Query failed : " . mysql_error());
    }
}


if($sku) {
    //find product name
	$query = "SELECT name FROM product_skus WHERE sku='$sku'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   foreach ($line as $col_value) {
	       $name = "$col_value";
	   }
	}
	mysql_free_result($result);

    //check if SKU is already in cart
	$querySKU = "SELECT sku, cart_id, quantity FROM wc_cart WHERE retailer_id='$retailer_id' AND sku='$sku'";
	$resultSKU = mysql_query($querySKU) or die("Query failed : " . mysql_error());
	while ($lineSKU = mysql_fetch_array($resultSKU, MYSQL_ASSOC)) {
		$tmp_cart_id = $lineSKU["cart_id"];
		$tmp_cart_qty = $lineSKU["quantity"];
	}

	if ( mysql_num_rows($resultSKU)>0 ) {//already there, so update the amount instead of inserting
		$tmp_cart_qty = $tmp_cart_qty + $quantity;
		$queryQU = "UPDATE wc_cart SET quantity='$tmp_cart_qty' WHERE cart_id='$tmp_cart_id'";
		$resultQU = mysql_query($queryQU) or die("Query failed : " . mysql_error());	
	}
	else {//insert entry to cart table
        $now = date("Y-m-d H:i:s");
        $query = "INSERT INTO wc_cart SET created='$now', retailer_id='$retailer_id', quantity='$quantity', sku='$sku', name='$name', price_lvl='$price_lvl'";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
	}

} elseif ($sku == "" && $quantity != "" && $cart_update == "") {
	$error_txt = "Error: You did not select which size unit to purchase. Please click the <b>Back</b> button and select a size (1/2 gram or 1 gram)";
}

if($cart_update) {
	$query = "SELECT cart_id, quantity FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_cart_id = $line["cart_id"];
		$tmp_cart_id_del = $line["cart_id"] . "_del";
		$tmp_cart_id_del = $_POST["$tmp_cart_id_del"];
		$tmp_cart_id_qty = $line["cart_id"] . "_qty";
		$tmp_cart_id_qty = $_POST["$tmp_cart_id_qty"];
		if($tmp_cart_id_del == '1' || $tmp_cart_id_qty==0) {
			$query2 = "DELETE FROM wc_cart WHERE cart_id='$tmp_cart_id'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());	
		} else {
			if($tmp_cart_id_qty != $line["quantity"]) {
				$query3 = "UPDATE wc_cart SET quantity='$tmp_cart_id_qty' WHERE cart_id='$tmp_cart_id'";
				$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
			}
		}
	}
	mysql_free_result($result);
}

$price_vars = find_price_lvl($retailer_id, $retailer_status);

$disc_attr = get_wc_discount();
$discount_code = $disc_attr[0];
$percent_off = $disc_attr[1];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Online Wholesale Catalog - Shopping Cart</title>

<?php
include '../includes/meta1.php';
?>

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';

$query = "SELECT cart_id, quantity, sku, name FROM wc_cart WHERE retailer_id='$retailer_id' AND quantity > 0";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

?>

<table border="0" width="90%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><span class="two">Online Wholesale Catalog: Shopping Cart</span></td></tr>

<?php
if ( $percent_off != 0 && mysql_num_rows($result)>0 ) {
    echo '<tr><td class="error3" align="left"><br />Discount code '.$discount_code.' applied: '.($percent_off * 100).'% off<br /></td></tr>';
}

$percent_off = 1 - $percent_off;
?>

<tr><td align="right"><a href="<?=$base_url?>store/index.php">Continue Shopping</a></td></tr>

<?php
if($error_txt) { 
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\"><span class=\"error\">$error_txt</span></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

echo '<tr><td align="left">';

if ( mysql_num_rows($result)>0 ) {
?>

<table border="0">
<tr><td align="left"><b>Product</b></td><td align="center"><b>Quantity</b></td><td align="center"><b>Price</b></td><td align="center" NOWRAP><b>Sub-Total</b></td><td align="center"><b>Remove</b></td></tr>
<form action="./cart.php" method="POST">
<input type="hidden" name="cart_update" value="1">
<?php
$subtotal = $price_vars["subtotal"];
$subtotal3 = 0;
$tot_qty = $price_vars["tot_qty"];
$price_lvl = $price_vars["price_lvl"];
$slw_array = $price_vars["slw_array"];

$supplies_skus = check_supplies($retailer_id);
$has_supplies = false;

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" VALIGN=\"TOP\" NOWRAP>";

		$tmp_sku = $line["sku"];
        $prod_id = '';
        $query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku' AND (display_on_website='1' OR display_in_wc='1')";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$prod_id = $line2["prod_id"];
		}
		mysql_free_result($result2);

		$url = ($prod_id ? $base_url.'store/product.php?prod_id='.$prod_id : '' );
        $tmp_link = ( $url!='' ? '<a href="'.$url.'">'.$line["name"].'</a>' :  $line["name"] );

		echo $tmp_link;
        echo "<br>SKU: ";
		echo $tmp_sku;
		echo "</td><td align=\"center\" VALIGN=\"TOP\">";
		
		$tmp_quantity = $line["quantity"];
		$tmp_cart_id_qty = $line["cart_id"] . "_qty";
		
		if( in_array($tmp_sku, $supplies_skus) ) {
            $has_supplies = true;
			echo "<input type=\"hidden\" name=\"$tmp_cart_id_qty\" value=\"$tmp_quantity\">";
			echo "";
			echo $tmp_quantity;
			echo "";
		} else {
			echo "<input type=\"text\" class=\"text_right\" name=\"$tmp_cart_id_qty\" size=\"5\" maxlength=\"7\" value=\"$tmp_quantity\">";
		}
		
		echo "</td><td align=\"center\" VALIGN=\"TOP\">$";


		//find product cost based on price level
		$query3 = "SELECT * FROM product_skus WHERE sku='$tmp_sku'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
		
            $query3b = "SELECT cost_field FROM wholesale_price_levels WHERE price_level='$price_lvl'";
            $result3b = mysql_query($query3b) or die("Query failed : " . mysql_error());
            while ($line3b = mysql_fetch_array($result3b, MYSQL_ASSOC)) {
                $cost_field = $line3b["cost_field"];
            }
            $cost = $line3[$cost_field];
            mysql_free_result($result3b);       
        }
		mysql_free_result($result3);

		$cost = $cost * $percent_off;
		echo condDecimalFormat($cost, 2);
		echo "</td><td align=\"right\" VALIGN=\"TOP\">$";
		$cost = condDecimalFormat( $cost);
		$tmp_subtotal = $line["quantity"] * $cost;
		echo condDecimalFormat( $tmp_subtotal) ;
        $tmp_subtotal = condDecimalFormat( $tmp_subtotal);
		echo "</td><td align=\"center\" VALIGN=\"TOP\">";
		$tmp_cart_id_del = $line["cart_id"] . "_del";
		echo "<input type=\"checkbox\" name=\"$tmp_cart_id_del\" value=\"1\"></td></tr>\n";
		
		$subtotal3 = $subtotal3 + $tmp_subtotal;
	}
	mysql_free_result($result);
?>
<tr valign="middle"><td align="right"><b>Total Quantity</b></td><td align="center"><b>
<?php 
echo $tot_qty; ?>
</b></td><td align="right" NOWRAP><b>Sub-Total</b></td><td align="right" NOWRAP><b>$<?php 
echo condDecimalFormat($subtotal3);
$subtotal3 = condDecimalFormat( $subtotal3);
?>
</b></td><td align="center"> <input type="submit" value="Update Cart"> </td></tr>
<tr><td colspan="5">&#160;</td></tr>
<tr><td align="left" colspan="5">
<?php
if ( $price_level_type=='1' ) {
	if ( $price_lvl < count($slw_array) ) {//if we're not at the maximum level yet
		echo '<span class="error">';
		if ( $slw_array[$price_lvl]['measure']=='units' ) {//test the next level's measure type
			$next_level = $slw_array[$price_lvl]['min'] - $tot_qty;
			$plural = ($next_level==1? "" : "s");
			echo "If ".$next_level." more item".$plural." are purchased, an additional discount will be applied.";
		}
		else {//next level is in dollars
			$next_level = $slw_array[$price_lvl]['min'] - $subtotal3;
			echo "If an additional $".condDecimalFormat($next_level)." of product is ordered, an additional discount will be applied.";
		}
		echo '</span>';
	}
	else {
		echo "<b>This order is receiving the maximum discount (based on cost and quantity) available.</b>";
	}
}
?></td></tr>

<?php
echo '<tr><td class="error3" colspan="5">';
	calcAndShowCommission();
echo '</td></tr>';
?>

<input type="hidden" id="price_level" name="price_lvl" value="<?php echo $price_lvl; ?>">
</form>
<tr><td colspan="5">&nbsp;</td></tr>

<?php
if( $has_supplies ) {
	?>
	<form action="step1.php" method="POST">
	<?php
} else {
	?>
	<form action="supplies.php" method="POST">
	<?php	
}
?>

<input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">
<input type="hidden" name="cart" value="1">
<tr><td colspan="5" align="right"><input type="submit" value="Secure Checkout"></td></tr>
</form>
</table>
<?php
}
else {
    echo 'Your cart is currently empty.';
}
?>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>