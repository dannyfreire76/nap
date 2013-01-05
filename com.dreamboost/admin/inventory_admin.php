<?php
// BME WMS
// Page: Inventory Manager Homepage
// Path/File: /admin/inventory_admin.php
// Version: 1.8
// Build: 1804
// Date: 05-14-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/pagination1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$page_this = $_GET["page_this"];
$sign = $_POST["sign"];
$stock = $_POST["stock"];
$threshold = $_POST["threshold"];
$prod_sku_id = $_POST["prod_sku_id"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "inventory";
$page = "Inventory Manager > Homepage";
$url = "inventory_admin.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($prod_sku_id != "" AND $stock != "") {
	$query = "UPDATE product_skus SET stock = stock $sign $stock, threshold='$threshold' WHERE prod_sku_id='$prod_sku_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

if($prod_sku_id != "" AND $threshold != "") {
	$query = "UPDATE product_skus SET threshold='$threshold' WHERE prod_sku_id='$prod_sku_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Product</th><th scope="col">SKU</th><th scope="col">Page</th><th scope="col">Stock</th><th scope="col">Change By</th><th scope="col">Threshold</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM product_skus WHERE active = '1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT prod_sku_id, sku, name, url, stock, threshold FROM product_skus WHERE active = '1' ORDER BY prod_sku_id LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["name"];
	echo "</td><td>";
	echo $line["sku"];
	echo "</td><td>";
	$tmp_url = substr($line["url"], 1);
	echo "<a href=\"../store" . $tmp_url . "\">" . $tmp_url . "</a>";
	echo "</td><FORM name=\"inventory-manage\" Method=\"POST\" ACTION=\"./inventory_admin.php?page_this=";
	echo $page_this;
	echo "\" class=\"wmsform\">\n";
	echo "<input type=\"hidden\" name=\"prod_sku_id\" value=\"";
	echo $line["prod_sku_id"];
	echo "\"><td>";
	echo $line["stock"];
	echo "</td><td>";
	echo "<select name=\"sign\"><option value=\"+\">+</option><option value=\"-\">-</option></select>";
	echo "&nbsp; <input type=\"text\" name=\"stock\" size=\"5\" value=\"\"></td><td>";
	echo "<input type=\"text\" name=\"threshold\" size=\"2\" maxlength=\"2\" value=\"";
	echo $line["threshold"];
	echo "\"></td><td align=\"center\"><input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr></form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

<?php
pagination_display($url, $page_this, $limit, $record_count);
?>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>