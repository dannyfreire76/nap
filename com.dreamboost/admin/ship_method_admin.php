<?php
// BME WMS
// Page: Shipping Methods page
// Path/File: /admin/ship_method_admin.php
// Version: 1.8
// Build: 1804
// Date: 03-18-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$retail_submit = $_POST["retail_submit"];
$wholesale_submit = $_POST["wholesale_submit"];
$name = $_POST["name"];
$active = $_POST["active"];
$ship_method_id = $_POST["ship_method_id"];
$retail_default_method = $_POST["retail_default_method"];
$wholesale_default_method = $_POST["wholesale_default_method"];

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Shipping Methods";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($retail_submit != "") {
	$query = "UPDATE ship_method SET name='$name', active='$active' WHERE ship_method_id='$ship_method_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

if($wholesale_submit != "") {
	$query = "UPDATE ship_method_wholesale SET name='$name', active='$active' WHERE ship_method_id='$ship_method_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

if($retail_default_method != "") {
	$query = "UPDATE ship_main SET default_retail_ship_method='$retail_default_method' WHERE ship_main_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	
}

if($wholesale_default_method != "") {
	$query = "UPDATE ship_main SET default_wholesale_ship_method='$wholesale_default_method' WHERE ship_main_id='1'";
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

<tr><td align="left"><font size="2">You can control the Shipping Methods offered in your main Online Store as well as your Wholesale Catalog.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Online Store (Retail) Shipping Methods</font></td></tr>
<FORM name="shipping2" Method="POST" ACTION="./ship_method_admin.php" class="wmsform">
<tr><td align="left"><font size="2">Default: <select id="retail_default_method" name="retail_default_method" onChange="submit()">
<?php

$query = "SELECT default_retail_ship_method FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$retail_default_method = $line["default_retail_ship_method"];
}
mysql_free_result($result);

$query = "SELECT ship_method_id, name FROM ship_method WHERE active='1' ORDER BY ship_method_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<option value=\"";
	echo $line["ship_method_id"];
	echo "\"";
	if($retail_default_method == $line["ship_method_id"]) { echo " SELECTED"; }
	echo ">";
	echo $line["name"];
	echo "</option>\n";
}
mysql_free_result($result);
?>
</select></font></td></tr>
</form>
<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">#</th><th scope="col">Shipping Method</th><th scope="col">Status</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT ship_method_id, method, name, active FROM ship_method ORDER BY ship_method_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"shipping\" Method=\"POST\" ACTION=\"./ship_method_admin.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["ship_method_id"];
	echo "</td>";
	echo "<input type=\"hidden\" name=\"ship_method_id\" value=\"";
	echo $line["ship_method_id"];
	echo "\"><td><input type=\"text\" name=\"name\" size=\"35\" maxlength=\"255\" value=\"";
	echo $line["name"];
	echo "\"></td><td>";
	echo "<select name=\"active\"><option value=\"1\"";
	if($line["active"] == "1") { echo " SELECTED"; }
	echo ">Active</option><option value=\"0\"";
	if($line["active"] == "0") { echo " SELECTED"; }
	echo ">Inactive</option></select>";
	echo "</td><td><input type=\"image\" src=\"/images/wms/save.gif\" id=\"retail_submit\" name=\"retail_submit\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr>\n";
	echo "</form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Wholesale Catalog (Wholesale) Shipping Methods</font></td></tr>
<FORM name="shipping2" Method="POST" ACTION="./ship_method_admin.php" class="wmsform">
<tr><td align="left"><font size="2">Default: <select id="wholesale_default_method" name="wholesale_default_method" onChange="submit()">
<?php

$query = "SELECT default_wholesale_ship_method FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$wholesale_default_method = $line["default_wholesale_ship_method"];
}
mysql_free_result($result);

$query = "SELECT ship_method_id, name FROM ship_method_wholesale WHERE active='1' ORDER BY ship_method_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<option value=\"";
	echo $line["ship_method_id"];
	echo "\"";
	if($wholesale_default_method == $line["ship_method_id"]) { echo " SELECTED"; }
	echo ">";
	echo $line["name"];
	echo "</option>\n";
}
mysql_free_result($result);
?>
</select></font></td></tr>
</form>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">#</th><th scope="col">Shipping Method</th><th scope="col">Status</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT ship_method_id, method, name, active FROM ship_method_wholesale ORDER BY ship_method_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"shipping\" Method=\"POST\" ACTION=\"./ship_method_admin.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["ship_method_id"];
	echo "</td>";
	echo "<input type=\"hidden\" name=\"ship_method_id\" value=\"";
	echo $line["ship_method_id"];
	echo "\"><td><input type=\"text\" name=\"name\" size=\"35\" maxlength=\"255\" value=\"";
	echo $line["name"];
	echo "\"></td><td>";
	echo "<select name=\"active\"><option value=\"1\"";
	if($line["active"] == "1") { echo " SELECTED"; }
	echo ">Active</option><option value=\"0\"";
	if($line["active"] == "0") { echo " SELECTED"; }
	echo ">Inactive</option></select>";
	echo "</td><td><input type=\"image\" src=\"/images/wms/save.gif\" id=\"wholesale_submit\" name=\"wholesale_submit\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr>\n";
	echo "</form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

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