<?php
// BME WMS
// Page: Shopping Cart
// Path/File: /store/cart.php
// Version: 1.8
// Build: 1802
// Date: 01-29-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 800;

$quantity = $_POST["quantity"];
$sku = $_POST["sku"];
$cart_update = $_POST["cart_update"];


//find or assign user_id
$user_id = $_COOKIE["db_user"];
if(!$user_id) {
	$query = "SELECT user_id FROM users";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   foreach ($line as $col_value) {
	       $user_id = "$col_value";
	   }
	}
	mysql_free_result($result);
	
	$new_user_id = $user_id + 1;
	$query = "INSERT INTO users SET user_id='$new_user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	
	$result = setcookie("db_user", $user_id, time()+60*60*24*30, "/store/", ".dreamboost.com", 0) or die ("Set Cookie failed : " . mysql_error());

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

	//insert entry to cart table
	$now = date("Y-m-d H:i:s");
	$query = "INSERT INTO cart SET created='$now', user_id='$user_id', quantity='$quantity', sku='$sku', name='$name'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());

} elseif ($sku == "" && $quantity != "" && $cart_update == "") {
	$error_txt = "Error: You did not select which size unit to purchase. Please click the <b>Back</b> button and select a size (1/2 gram or 1 gram)";
}

if($cart_update) {
	$query = "SELECT cart_id, quantity FROM cart WHERE user_id='$user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_cart_id = $line["cart_id"];
		$tmp_cart_id_del = $line["cart_id"] . "_del";
		$tmp_cart_id_del = $_POST["$tmp_cart_id_del"];
		$tmp_cart_id_qty = $line["cart_id"] . "_qty";
		$tmp_cart_id_qty = $_POST["$tmp_cart_id_qty"];
		if($tmp_cart_id_del == '1') {
			$query2 = "DELETE FROM cart WHERE cart_id='$tmp_cart_id'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());	
		} else {
			if($tmp_cart_id_qty !== $line["quantity"]) {
				$query3 = "UPDATE cart SET quantity='$tmp_cart_id_qty' WHERE cart_id='$tmp_cart_id'";
				$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());	
			}
		}
	}
	mysql_free_result($result);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Shopping Cart</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css" />
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/button_update_cart_over.gif','/images/button_checkout.gif','/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Online Store: Shopping Cart</td></tr>

<?php
/*
?>
<tr><td align="left"><font face="<?php echo $font; ?>" color="red" size="+1"><b>Note: Currently we are not able to accept <!--credit card--> orders. We are working to resolve this problem. If you would like to <!--use a credit card to--> place an order please come back in a couple days. We apologize for any inconvenience.</b></font></td></tr>
<?php
*/
?>

<tr><td align="right" class="style2"><a href="./index.php">Continue Shopping</a></td></tr>

<?php
if($error_txt) { 
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left" class="style2">
<table border="0">
<tr><td align="left" class="style3">Product</td><td align="center" class="style3">Quantity</td><td align="center" class="style3">Price</td><td align="center" class="style3">Sub-Total</td><td align="center" class="style3">Remove</td></tr>
<form action="./cart.php" method="POST">
<input type="hidden" name="cart_update" value="1">
<?php
$subtotal = 0;
	$query = "SELECT cart_id, quantity, sku, name FROM cart WHERE user_id='$user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" VALIGN=\"TOP\" class=\"style2\">";

		$tmp_sku = $line["sku"];

		//find product url
		$query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   foreach ($line2 as $col_value2) {
		       $url = "$col_value2";
		   }
		}
		mysql_free_result($result2);

		echo "<a href=\"$url\">";
		echo $line["name"];
		echo "</a><br>SKU: ";
		echo $tmp_sku;
		echo "</td><td align=\"center\" VALIGN=\"TOP\">";
		$tmp_quantity = $line["quantity"];
		$tmp_cart_id_qty = $line["cart_id"] . "_qty";
		echo "<input type=\"text\" name=\"$tmp_cart_id_qty\" size=\"4\" maxlength=\"4\" value=\"$tmp_quantity\">";
		echo "</td><td align=\"center\" VALIGN=\"TOP\" class=\"style2\">$";

		//find product cost
		$query3 = "SELECT cost FROM product_skus WHERE sku='$tmp_sku'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
		   foreach ($line3 as $col_value3) {
		       $cost = "$col_value3";
		   }
		}
		mysql_free_result($result3);

		echo $cost;
		echo "</td><td align=\"right\" VALIGN=\"TOP\" class=\"style2\">$";
		$tmp_subtotal = $line["quantity"] * $cost;
		$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
		echo $tmp_subtotal;
		echo "</td><td align=\"center\" VALIGN=\"TOP\">";
		$tmp_cart_id_del = $line["cart_id"] . "_del";
		echo "<input type=\"checkbox\" name=\"$tmp_cart_id_del\" value=\"1\"></td></tr>\n";
		
		$subtotal = $subtotal + $tmp_subtotal;

	}
	mysql_free_result($result);
?>
<tr><td VALIGN="middle" align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="middle" align="right" class="style3">Sub-Total</td><td VALIGN="middle" align="right" class="style3">$
<?php 
$subtotal = sprintf("%01.2f", $subtotal);
echo $subtotal; ?>
</td><td VALIGN="middle" align="center"><input type="image" src="/images/button_update_cart.gif" id="button_update_cart" name="button_update_cart" alt="Update Cart" onmouseover="MM_swapImage('button_update_cart','','/images/button_update_cart_over.gif',1)" onmouseout="MM_swapImgRestore()"></td></tr>
</form>
<tr><td colspan="5">&nbsp;</td></tr>

<?php
	$query = "SELECT free_shipping_offered, free_shipping FROM ship_main WHERE ship_main_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$free_shipping_offered = $line["free_shipping_offered"];
		$free_shipping = $line["free_shipping"];
	}
	mysql_free_result($result);
	
if($free_shipping_offered == "1") {
	echo "<tr><td colspan=\"5\" align=\"center\" class=\"style2\">Orders of $";
	echo $free_shipping;
	echo " or more receive FREE <a href=\"./shipping.php\">Shipping and Handling</a></td></tr>\n";
}
?>

<form action="<?php echo $base_secure_url; ?>store/step1.php" method="POST">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="hidden" name="cart" value="1">
<tr><td colspan="5" align="right"><input type="image" src="/images/button_checkout.gif" id="button_checkout" name="button_checkout" alt="Checkout" onmouseover="MM_swapImage('button_checkout','','/images/button_checkout_over.gif',1)" onmouseout="MM_swapImgRestore()"></td></tr>
</form>
</table>

</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>