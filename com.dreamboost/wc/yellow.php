<?php
// BME WMS
// Page: Wholesale Catalog Product page
// Path/File: /wc/yellow.php
// Version: 1.1
// Build: 1103
// Date: 12-13-2006

$retailer_id = $_COOKIE["wc_user"];
$retailer_status = $_COOKIE["wc_status"];
if(!$retailer_id) {
	header("Location: " . $base_url . "wc/index.php");
	exit;
}
header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$product_id = 2;

$query = "SELECT name, url, sub_name, pricing, wholesale_pricing1, wholesale_pricing2, wholesale_pricing3, dist_pricing1, dist_pricing2, dist_pricing3, description, ordering_info, image, image_alt_text, display_in_wc, active FROM products WHERE prod_id='$product_id' AND display_in_wc='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name = $line["name"];
	$url = $line["url"];
	$sub_name = $line["sub_name"];
	$pricing = $line["pricing"];
	$wholesale_pricing1 = $line["wholesale_pricing1"];
	$wholesale_pricing2 = $line["wholesale_pricing2"];
	$wholesale_pricing3 = $line["wholesale_pricing3"];
	$dist_pricing1 = $line["dist_pricing1"];
	$dist_pricing2 = $line["dist_pricing2"];
	$dist_pricing3 = $line["dist_pricing3"];
	$description = $line["description"];
	$ordering_info = $line["ordering_info"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
}
mysql_free_result($result);

$query = "SELECT name FROM product_categories WHERE prod_cat_id='1' AND display_in_wc='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog - <?php echo $cat_name; ?>: <?php echo $name; ?></title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head_wc1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="5">Wholesale Catalog: <?php echo $cat_name; ?> - <?php echo $name; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0" cellspacing="8" cellpadding="0">
<tr><td align="left" VALIGN="TOP"><img src="<?php echo $image; ?>" border="0" width="200" height="287" alt="<?php echo $image_alt_text; ?>"></td>
<form action="./cart.php" method="POST">
<td align="left" VALIGN="TOP" NOWRAP><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4"><?php echo $sub_name; ?></font><br>
<table border="0">
<tr><td>&nbsp;</td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2"><b>1/2 Gram</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2"><b>1 Gram</b></font></td></tr>

<?php
$query = "SELECT cost, wholesale_cost1, wholesale_cost2, wholesale_cost3, dist_cost1, dist_cost2, dist_cost3 FROM product_skus WHERE prod_id='$product_id' AND display_in_wc='1' AND active='1' ORDER BY cost";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cost[] = $line["cost"];
	$wholesale_cost1[] = $line["wholesale_cost1"];
	$wholesale_cost2[] = $line["wholesale_cost2"];
	$wholesale_cost3[] = $line["wholesale_cost3"];
	$dist_cost1[] = $line["dist_cost1"];
	$dist_cost2[] = $line["dist_cost2"];
	$dist_cost3[] = $line["dist_cost3"];
}
mysql_free_result($result);
?>

<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">Suggested Retail</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $cost[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $cost[1]; ?></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">1-99 units</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost1[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost1[1]; ?></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">100-149 units</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost2[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost2[1]; ?></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">150+ units</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost3[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $wholesale_cost3[1]; ?></font></td></tr>
<?php if($retailer_status == 1) {
?>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$2000+ per order</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost1[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost1[1]; ?></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$4000+ per order</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost2[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost2[1]; ?></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$7500+ per order</font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost3[0]; ?></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">$<?php echo $dist_cost3[1]; ?></font></td></tr>

<?php
} ?>
</table>
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="3">Quantity <input type="text" name="quantity" size="4" maxlength="4" value="1"><br>
<?php
$query = "SELECT sku, drop_down FROM product_skus WHERE prod_id='$product_id' AND display_in_wc='1' AND active='1' ORDER BY prod_sku_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$row_cnt = mysql_num_rows($result);
if($row_cnt == '1') {
	echo "<input type=\"hidden\" name=\"sku\" value=\"";
} elseif($row_cnt > '1') {
	echo "<select name=\"sku\">\n";
	echo "<option value=\"\">Please Select</option>\n";
}
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($row_cnt == '1') {
		echo $line["sku"];
		echo "\">\n";
	} elseif($row_cnt > '1') {
		echo "<option value=\"";
		echo $line["sku"];
		echo "\">";
		echo $line["drop_down"];
		echo "</option>\n";
	}
}
mysql_free_result($result);
if($row_cnt > '1') {
	echo "</select><br>\n";
}
?>
<input type="submit" value="Add to Cart">
</form>
</font></td><td align="left">&nbsp;</td><td align="left" VALIGN="TOP"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="3"><?php echo $ordering_info; if($ordering_info != "") { echo "<br><br>"; } ?>
<a href="./shipping.php">Shipping Information</a>
<?php
if($retailer_id) {
	echo "<br><br>\n";
	echo "<a href=\"./cart.php\">View Your Shopping Cart</a>";
}
?>
</font></td></tr>
</table></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4"><?php echo $description; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4"><b>Other <?php echo $cat_name; ?> Options</b></font></td></tr>
<tr><td align="left"><a href="./green.php"><img src="../images/but_store_green2.gif" border="0" alt="Green"></a> <a href="./red.php"><img src="../images/but_store_red2.gif" border="0" alt="Red"></a> <a href="./beyond.php"><img src="../images/but_store_purple2.gif" border="0" alt="Beyond"></a></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot_wc1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>
