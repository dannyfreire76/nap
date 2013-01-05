<?php
// BME WMS
// Page: Online Store Product page
// Path/File: /store/book1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 950;

$query = "SELECT name, url, sub_name, pricing, description, ordering_info, image, image_alt_text, display_on_website, active FROM products WHERE prod_id='4' AND display_on_website='1' AND active='1'";
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

$query = "SELECT name FROM product_categories WHERE prod_cat_id='2' AND display_on_website='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - <?php echo $cat_name; ?>: <?php echo $name; ?></title>
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

<tr><td align="left" class="style4">Online Store: <?php echo $cat_name; ?> - <?php echo $name; ?></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0" cellspacing="8" cellpadding="0">
<tr><td VALIGN="TOP" align="left"><img src="<?php echo $image; ?>" border="0" width="190" height="262" alt="<?php echo $image_alt_text; ?>"></td>
<form action="./cart.php" method="POST">
<td VALIGN="TOP" align="left" NOWRAP class="style2"><?php echo $sub_name; ?><br>
<?php echo $pricing; ?><br>
Quantity <input type="text" name="quantity" size="4" maxlength="4" value="1"><br>
<?php
$query = "SELECT sku, drop_down FROM product_skus WHERE prod_id='4' AND display_on_website='1' AND active='1' ORDER BY prod_sku_id";
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
</form></td><td>&nbsp;</td><td VALIGN="TOP" align="left" class="style2"><?php echo $ordering_info; if($ordering_info != "") { echo "<br><br>"; } ?>
<a href="./shipping.php">Shipping Information</a>
<?php
$user_id = $_COOKIE["db_user"];
if($user_id) {
	echo "<br><br>\n";
	echo "<a href=\"./cart.php\">View Your Shopping Cart</a>";
}
?>
</td></tr>
</table></td></tr>

<tr><td align="left" class="style2"><?php echo $description; ?></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style3">Other <?php echo $cat_name; ?> Options</td></tr>
<tr><td align="left" class="style2">Coming Soon!</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>