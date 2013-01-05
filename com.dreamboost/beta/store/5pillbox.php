<?php
// BME WMS
// Page: Online Store Product page
// Version: 1.1
// Build: 1103
// Date: 03-28-2006

include '../includes/main1.php';

$query = "SELECT name, url, sub_name, pricing, description, ordering_info, image, image_alt_text, display_on_website, active FROM products WHERE prod_id='1' AND display_on_website='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name = $line["name"];
	$url = $line["url"];
	$sub_name = $line["sub_name"];
	$pricing = $line["pricing"];
	$description = $line["description"];
	$ordering_info = $line["ordering_info"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
}
mysql_free_result($result);

$query = "SELECT name FROM product_categories WHERE prod_cat_id='1' AND display_on_website='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
}
mysql_free_result($result);
?>
<html>
<head>
<title><?php echo $website_title; ?>: Store - <?php echo $cat_name; ?>: <?php echo $name; ?></title>
<?php
include '../includes/meta1.php';
?>
<?php
include '../includes/js_funcs1.js';
?>
</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
navigation_head('store');
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Online Store: <?php echo $cat_name; ?> - <?php echo $name; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><table border="0" cellspacing="8" cellpadding="0">
<tr><td VALIGN="TOP"><img src="<?php echo $image; ?>" border="0" width="200" height="278" alt="<?php echo $image_alt_text; ?>"></td>
<form action="./cart.php" method="POST">
<td VALIGN="TOP" NOWRAP><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><?php echo $sub_name; ?><br>
<?php echo $pricing; ?><br>
Quantity <input type="text" name="quantity" size="4" maxlength="4" value="1"><br>
<?php
$query = "SELECT sku, drop_down FROM product_skus WHERE prod_id='1' AND display_on_website='1' AND active='1' ORDER BY prod_sku_id";
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
</font></td><td>&nbsp;</td><td VALIGN="TOP"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>"><?php echo $ordering_info; if($ordering_info != "") { echo "<br><br>"; } ?>
<a href="./shipping.php">Shipping Information</a>
<?php
$user_id = $_COOKIE["db_user"];
if($user_id) {
	echo "<br><br>\n";
	echo "<a href=\"./cart.php\">View Your Shopping Cart</a>";
}
?>
</font></td></tr>
</table></td></tr>

<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><?php echo $description; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Other <?php echo $cat_name; ?> Options</b></font></td></tr>
<tr><td><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><a href="./12pillbox.php" OnMouseOver="move_in('12tab','../images/but_lrg_12tab_hover.jpg')" OnMouseOut="move_out('12tab','../images/but_lrg_12tab_normal.jpg')"><img src="../images/but_lrg_12tab_normal.jpg" border="0" alt="12 Tablet Box" name="12tab"></a> <a href="./40pillbottle.php" OnMouseOver="move_in('40tab','../images/but_lrg_40tab_hover.jpg')" OnMouseOut="move_out('40tab','../images/but_lrg_40tab_normal.jpg')"><img src="../images/but_lrg_40tab_normal.jpg" border="0" alt="40 Tablet Bottle" name="40tab"></a></font></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>