<?php
// BME WMS
// Page: Products Manager - Import Product SKU page
// Path/File: /admin/products_admin_import.php
// Version: 1.8
// Build: 1801
// Date: 03-17-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$create = $_POST["create"];
if($_GET["prod_id"] != "") {
	$prod_id = $_GET["prod_id"];
} else {
	$prod_id = $_POST["prod_id"];
}
$sku = $_POST["sku"];
$name = $_POST["name"];
$drop_down = $_POST["drop_down"];
$ship_con_id = $_POST["ship_con_id"];
$cost = $_POST["cost"];
$wholesale_cost1 = $_POST["wholesale_cost1"];
$wholesale_cost2 = $_POST["wholesale_cost2"];
$wholesale_cost3 = $_POST["wholesale_cost3"];
$dist_cost1 = $_POST["dist_cost1"];
$dist_cost2 = $_POST["dist_cost2"];
$dist_cost3 = $_POST["dist_cost3"];
$weight = $_POST["weight"];
$stock_status = $_POST["stock_status"];
$stock = $_POST["stock"];
$display_on_website = $_POST["display_on_website"];
$display_in_wc = $_POST["display_in_wc"];
$active = $_POST["active"];
$threshold = $_POST["threshold"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Import Product SKUs";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
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

<tr><td align="left"><font size="2">Import Product SKUs</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
<?php

$prod_sku = array();

$query = "SELECT tbl_product_prf_product_form.prf_id, tbl_product_pro_product.pro_image2, tbl_product_prf_product_form.prf_title, tbl_product_prf_product_form.prf_sht_id, tbl_product_prf_product_form.prf_price, tbl_product_prf_product_form.prf_weight_lb, tbl_product_prf_product_form.prf_weight_oz, tbl_product_prf_product_form.prf_stock FROM tbl_product_pro_product, tbl_product_prf_product_form WHERE tbl_product_pro_product.pro_id=tbl_product_prf_product_form.prf_pro_id LIMIT 800";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$pro_id = $line["prf_id"];
	if(strlen($pro_id) == 1) {
		$prod_sku_data['sku'] = 100 . $pro_id;
	} else if(strlen($pro_id) == 2) {
		$prod_sku_data['sku'] = 10 . $pro_id;
	} else if(strlen($pro_id) == 3) {
		$prod_sku_data['sku'] = 1 . $pro_id;
	}
	$prod_sku_data['pro_image2'] = $line["pro_image2"];
	$prod_sku_data['name'] = $line["prf_title"];
	$prod_sku_data['drop_down'] = $line["prf_title"];
	$prod_sku_data['ship_con_id'] = $line["prf_sht_id"];
	$prod_sku_data['cost'] = $line["prf_price"];
	$prod_sku_data['wholesale_cost1'] = $line["prf_price"];
	$prod_sku_data['wholesale_cost2'] = $line["prf_price"];
	$prod_sku_data['wholesale_cost3'] = $line["prf_price"];
	$prod_sku_data['dist_cost1'] = $line["prf_price"];
	$prod_sku_data['dist_cost2'] = $line["prf_price"];
	$prod_sku_data['dist_cost3'] = $line["prf_price"];
	$weight_lb = $line["prf_weight_lb"];
	$weight_oz = $line["prf_weight_oz"];
	$weight_lb = $weight_lb * 454;
	$weight_oz = $weight_oz * 28;
	$prod_sku_data['weight'] = $weight_lb + $weight_oz;
	$prod_sku_data['stock_status'] = 1;
	$prod_sku_data['stock'] = $line["prf_stock"];
	$prod_sku_data['display_on_website'] = 1;
	$prod_sku_data['display_in_wc'] = 1;
	$prod_sku_data['active'] = 1;
	$prod_sku_data['threshold'] = 10;
	$prod_sku[] = $prod_sku_data;
}
mysql_free_result($result);

$prod_sku2 = array();

for($i=0; $i < count($prod_sku); $i++) {
	$query = "SELECT count(*) as count FROM product_skus WHERE sku='".$prod_sku[$i]['sku']."'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$count = $line["count"];
	}
	mysql_free_result($result);
	if($count == 1) {
		// already loaded into product_skus
		echo "Skipping SKU " . $prod_sku[$i]['sku'];
		echo "<br>\n";
	} elseif ($count > 1) {
		// already loaded into product_skus
		echo "Skipping SKU " . $prod_sku[$i]['sku'];
		echo " - found multiple SKUs<br>\n";
	} else {
		$prod_sku2[] = $prod_sku[$i];
	}
}

for($i=0; $i< count($prod_sku2); $i++) {
	$prod_id = "";
	$query = "SELECT prod_id FROM products WHERE image LIKE '%".$prod_sku2[$i]['pro_image2']."'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$prod_id = $line["prod_id"];
	}
	mysql_free_result($result);
	if($prod_id != ""){
		$prod_sku2[$i]['prod_id'] = $prod_id;
		
		$created = date("Y-m-d H:i:s");
		$query = "INSERT INTO product_skus SET created='$created', prod_id='".$prod_sku2[$i]['prod_id']."', sku='".$prod_sku2[$i]['sku']."', name='".mysql_real_escape_string($prod_sku2[$i]['name'])."', drop_down='".mysql_real_escape_string($prod_sku2[$i]['drop_down'])."', ship_con_id='".$prod_sku2[$i]['ship_con_id']."', cost='".$prod_sku2[$i]['cost']."', wholesale_cost1='".$prod_sku2[$i]['wholesale_cost1']."', wholesale_cost2='".$prod_sku2[$i]['wholesale_cost2']."', wholesale_cost3='".$prod_sku2[$i]['wholesale_cost3']."', dist_cost1='".$prod_sku2[$i]['dist_cost1']."', dist_cost2='".$prod_sku2[$i]['dist_cost2']."', dist_cost3='".$prod_sku2[$i]['dist_cost3']."', weight='".$prod_sku2[$i]['weight']."', stock_status='".$prod_sku2[$i]['stock_status']."', stock='".$prod_sku2[$i]['stock']."', display_on_website='".$prod_sku2[$i]['display_on_website']."', display_in_wc='".$prod_sku2[$i]['display_in_wc']."', active='".$prod_sku2[$i]['active']."', threshold='".$prod_sku2[$i]['threshold']."'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
	} else {
		echo "Error, cannot find product to attach this product sku to<br>\n";
		print_r($prod_sku2[$i]);
		echo "<br>\n";
	}
}


echo "</td></tr>\n";

//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
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