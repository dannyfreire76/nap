<?php
// BME WMS
// Page: Shipping Costs page
// Path/File: /admin/ship_cost_admin.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Shipping Costs";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$ship_cost_id = $_REQUEST["ship_cost_id"];
$method_1 = $_REQUEST["method_1"];
$method_2 = $_REQUEST["method_2"];
$method_3 = $_REQUEST["method_3"];
$method_4 = $_REQUEST["method_4"];
$method_5 = $_REQUEST["method_5"];
$method_6 = $_REQUEST["method_6"];

if($ship_cost_id != "") {
	$query = "UPDATE ship_cost SET method_1='$method_1', method_2='$method_2', method_3='$method_3', method_4='$method_4', method_5='$method_5', method_6='$method_6' WHERE ship_cost_id='$ship_cost_id'";
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

<tr><td align="left"><font size="2">Use the below to associate fixed shipping costs to each Shipping Method and area.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Location</th>
<?php

$query = "SELECT name, active FROM ship_method ORDER BY ship_method_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$tmp_counter = 1;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["active"] == "1") { 
		echo "<th scope=\"col\">";
		echo $line["name"];
		echo "</th>";
		if($tmp_counter == 1) {
			$tmp_1 = "yes";
		} elseif($tmp_counter == 2) {
			$tmp_2 = "yes";
		} elseif($tmp_counter == 3) {
			$tmp_3 = "yes";
		} elseif($tmp_counter == 4) {
			$tmp_4 = "yes";
		} elseif($tmp_counter == 5) {
			$tmp_5 = "yes";
		} elseif($tmp_counter == 6) {
			$tmp_6 = "yes";
		}
	} elseif($line["active"] == "0") { 
		if($tmp_counter == 1) {
			$tmp_1 = "no";
		} elseif($tmp_counter == 2) {
			$tmp_2 = "no";
		} elseif($tmp_counter == 3) {
			$tmp_3 = "no";
		} elseif($tmp_counter == 4) {
			$tmp_4 = "no";
		} elseif($tmp_counter == 5) {
			$tmp_5 = "no";
		} elseif($tmp_counter == 6) {
			$tmp_6 = "no";
		}
	}
	$tmp_counter = $tmp_counter + 1;
}
mysql_free_result($result);

echo "<th scope=\"col\">&nbsp;</th></tr>\n";

$line_counter = 0;
$query = "SELECT ship_cost_id, name, method_1, method_2, method_3, method_4, method_5, method_6 FROM ship_cost ORDER BY ship_cost_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"shipping\" Method=\"POST\" ACTION=\"./ship_cost_admin.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["name"];
	echo "</td>";
	echo "<input type=\"hidden\" name=\"ship_cost_id\" value=\"";
	echo $line["ship_cost_id"];
	echo "\">";
	if($tmp_1 == "yes") {
		echo "<td><input type=\"text\" name=\"method_1\" size=\"10\" value=\"";
		echo $line["method_1"];
		echo "\"></td>";
	}
	if($tmp_2 == "yes") {
		echo "<td><input type=\"text\" name=\"method_2\" size=\"10\" value=\"";
		echo $line["method_2"];
		echo "\"></td>";
	}
	if($tmp_3 == "yes") {
		echo "<td><input type=\"text\" name=\"method_3\" size=\"10\" value=\"";
		echo $line["method_3"];
		echo "\"></td>";
	}
	if($tmp_4 == "yes") {
		echo "<td><input type=\"text\" name=\"method_4\" size=\"10\" value=\"";
		echo $line["method_4"];
		echo "\"></td>";
	}
	if($tmp_5 == "yes") {
		echo "<td><input type=\"text\" name=\"method_5\" size=\"10\" value=\"";
		echo $line["method_5"];
		echo "\"></td>";
	}
	if($tmp_6 == "yes") {
		echo "<td><input type=\"text\" name=\"method_6\" size=\"10\" value=\"";
		echo $line["method_6"];
		echo "\"></td>";
	}
	echo "<td><input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr>\n";
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