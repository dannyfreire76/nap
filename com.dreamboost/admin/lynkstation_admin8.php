<?php
// BME WMS
// Page: LynkStation Statistics page
// Path/File: /admin/lynkstation_admin8.php
// Version: 1.8
// Build: 1804
// Date: 01-30-2007

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
$manager = "lynkstation";
$page = "LynkStation Manager > LynkStation Statistics";
wms_manager_nav2($manager);
wms_page_nav2($manager);

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

<tr><td align="left"><font size="2">Welcome to LynkStation, where you manage the Links section of your website. On this page you can see all the statistics of your LynkStation.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

$total_counter = 0;
$total2_counter = 0;
$category0_counter = 0;
$category0a_counter = 0;
$category1_counter = 0;
$category1a_counter = 0;
$category2_counter = 0;
$category2a_counter = 0;
$category3_counter = 0;
$category3a_counter = 0;
$category4_counter = 0;
$category4a_counter = 0;
$category5_counter = 0;
$category5a_counter = 0;
$category6_counter = 0;
$category6a_counter = 0;
$category7_counter = 0;
$category7a_counter = 0;
$category8_counter = 0;
$category8a_counter = 0;
$category9_counter = 0;
$category9a_counter = 0;
$category10_counter = 0;
$category10a_counter = 0;
$category11_counter = 0;
$category11a_counter = 0;
$category12_counter = 0;
$category12a_counter = 0;
$category13_counter = 0;
$category13a_counter = 0;
$category14_counter = 0;
$category14a_counter = 0;
$category15_counter = 0;
$category15a_counter = 0;
$category16_counter = 0;
$category16a_counter = 0;
$category17_counter = 0;
$category17a_counter = 0;
$category18_counter = 0;
$category18a_counter = 0;
$category19_counter = 0;
$category19a_counter = 0;
$category20_counter = 0;
$category20a_counter = 0;
$category21_counter = 0;
$category21a_counter = 0;
$category22_counter = 0;
$category22a_counter = 0;
$category23_counter = 0;
$category23a_counter = 0;
$category24_counter = 0;
$category24a_counter = 0;
$category25_counter = 0;
$category25a_counter = 0;
$category26_counter = 0;
$category26a_counter = 0;
$category27_counter = 0;
$category27a_counter = 0;
$category28_counter = 0;
$category28a_counter = 0;
$category29_counter = 0;
$category29a_counter = 0;
$category30_counter = 0;
$category30a_counter = 0;
$category31_counter = 0;
$category31a_counter = 0;
$category32_counter = 0;
$category32a_counter = 0;
$category33_counter = 0;
$category33a_counter = 0;
$category34_counter = 0;
$category34a_counter = 0;
$category35_counter = 0;
$category35a_counter = 0;
$category36_counter = 0;
$category36a_counter = 0;
$category37_counter = 0;
$category37a_counter = 0;
$category38_counter = 0;
$category38a_counter = 0;
$category39_counter = 0;
$category39a_counter = 0;
$category40_counter = 0;
$category40a_counter = 0;
$category41_counter = 0;
$category41a_counter = 0;
$category42_counter = 0;
$category42a_counter = 0;
$category43_counter = 0;
$category43a_counter = 0;
$category44_counter = 0;
$category44a_counter = 0;
$category45_counter = 0;
$category45a_counter = 0;
$category46_counter = 0;
$category46a_counter = 0;
$category47_counter = 0;
$category47a_counter = 0;
$category48_counter = 0;
$category48a_counter = 0;
$category49_counter = 0;
$category49a_counter = 0;
$category50_counter = 0;
$category50a_counter = 0;
$category51_counter = 0;
$category51a_counter = 0;
$category52_counter = 0;
$category52a_counter = 0;
$category53_counter = 0;
$category53a_counter = 0;
$category54_counter = 0;
$category54a_counter = 0;
$category55_counter = 0;
$category55a_counter = 0;
$category56_counter = 0;
$category56a_counter = 0;
$category57_counter = 0;
$category57a_counter = 0;
$category58_counter = 0;
$category58a_counter = 0;
$category59_counter = 0;
$category59a_counter = 0;
$category60_counter = 0;
$category60a_counter = 0;
$category61_counter = 0;
$category61a_counter = 0;
$category61_counter = 0;
$category61a_counter = 0;
$category62_counter = 0;
$category62a_counter = 0;
$category63_counter = 0;
$category63a_counter = 0;
$category64_counter = 0;
$category64a_counter = 0;
$category65_counter = 0;
$category65a_counter = 0;
$category66_counter = 0;
$category66a_counter = 0;
$category67_counter = 0;
$category67a_counter = 0;
$category68_counter = 0;
$category68a_counter = 0;
$category69_counter = 0;
$category69a_counter = 0;
$to_review_counter = 0;
$rejected_counter = 0;
$bad_counter = 0;
$bad_reciprocal_counter = 0;
$no_reciprocal_counter = 0;

$query = "SELECT name FROM lynkstation_cats WHERE name!='' ORDER BY lscats_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category[] = $line["name"];
}

$query = "SELECT category, reciprical_link FROM lynkstation_links WHERE approved='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["category"] == $category[0]) { $category0_counter++; if($line["reciprical_link"] != "") { $category0a_counter++; $total2_counter++; } }
	if($line["category"] == $category[1]) { $category1_counter++; if($line["reciprical_link"] != "") { $category1a_counter++; $total2_counter++; } }
	if($line["category"] == $category[2]) { $category2_counter++; if($line["reciprical_link"] != "") { $category2a_counter++; $total2_counter++; } }
	if($line["category"] == $category[3]) { $category3_counter++; if($line["reciprical_link"] != "") { $category3a_counter++; $total2_counter++; } }
	if($line["category"] == $category[4]) { $category4_counter++; if($line["reciprical_link"] != "") { $category4a_counter++; $total2_counter++; } }
	if($line["category"] == $category[5]) { $category5_counter++; if($line["reciprical_link"] != "") { $category5a_counter++; $total2_counter++; } }
	if($line["category"] == $category[6]) { $category6_counter++; if($line["reciprical_link"] != "") { $category6a_counter++; $total2_counter++; } }
	if($line["category"] == $category[7]) { $category7_counter++; if($line["reciprical_link"] != "") { $category7a_counter++; $total2_counter++; } }
	if($line["category"] == $category[8]) { $category8_counter++; if($line["reciprical_link"] != "") { $category8a_counter++; $total2_counter++; } }
	if($line["category"] == $category[9]) { $category9_counter++; if($line["reciprical_link"] != "") { $category9a_counter++; $total2_counter++; } }
	if($line["category"] == $category[10]) { $category10_counter++; if($line["reciprical_link"] != "") { $category10a_counter++; $total2_counter++; } }
	if($line["category"] == $category[11]) { $category11_counter++; if($line["reciprical_link"] != "") { $category11a_counter++; $total2_counter++; } }
	if($line["category"] == $category[12]) { $category12_counter++; if($line["reciprical_link"] != "") { $category12a_counter++; $total2_counter++; } }
	if($line["category"] == $category[13]) { $category13_counter++; if($line["reciprical_link"] != "") { $category13a_counter++; $total2_counter++; } }
	if($line["category"] == $category[14]) { $category14_counter++; if($line["reciprical_link"] != "") { $category14a_counter++; $total2_counter++; } }
	if($line["category"] == $category[15]) { $category15_counter++; if($line["reciprical_link"] != "") { $category15a_counter++; $total2_counter++; } }
	if($line["category"] == $category[16]) { $category16_counter++; if($line["reciprical_link"] != "") { $category16a_counter++; $total2_counter++; } }
	if($line["category"] == $category[17]) { $category17_counter++; if($line["reciprical_link"] != "") { $category17a_counter++; $total2_counter++; } }
	if($line["category"] == $category[18]) { $category18_counter++; if($line["reciprical_link"] != "") { $category18a_counter++; $total2_counter++; } }
	if($line["category"] == $category[19]) { $category19_counter++; if($line["reciprical_link"] != "") { $category19a_counter++; $total2_counter++; } }
	if($line["category"] == $category[20]) { $category20_counter++; if($line["reciprical_link"] != "") { $category20a_counter++; $total2_counter++; } }
	if($line["category"] == $category[21]) { $category21_counter++; if($line["reciprical_link"] != "") { $category21a_counter++; $total2_counter++; } }
	if($line["category"] == $category[22]) { $category22_counter++; if($line["reciprical_link"] != "") { $category22a_counter++; $total2_counter++; } }
	if($line["category"] == $category[23]) { $category23_counter++; if($line["reciprical_link"] != "") { $category23a_counter++; $total2_counter++; } }
	if($line["category"] == $category[24]) { $category24_counter++; if($line["reciprical_link"] != "") { $category24a_counter++; $total2_counter++; } }
	if($line["category"] == $category[25]) { $category25_counter++; if($line["reciprical_link"] != "") { $category25a_counter++; $total2_counter++; } }
	if($line["category"] == $category[26]) { $category26_counter++; if($line["reciprical_link"] != "") { $category26a_counter++; $total2_counter++; } }
	if($line["category"] == $category[27]) { $category27_counter++; if($line["reciprical_link"] != "") { $category27a_counter++; $total2_counter++; } }
	if($line["category"] == $category[28]) { $category28_counter++; if($line["reciprical_link"] != "") { $category28a_counter++; $total2_counter++; } }
	if($line["category"] == $category[29]) { $category29_counter++; if($line["reciprical_link"] != "") { $category29a_counter++; $total2_counter++; } }
	if($line["category"] == $category[30]) { $category30_counter++; if($line["reciprical_link"] != "") { $category30a_counter++; $total2_counter++; } }
	if($line["category"] == $category[31]) { $category31_counter++; if($line["reciprical_link"] != "") { $category31a_counter++; $total2_counter++; } }
	if($line["category"] == $category[32]) { $category32_counter++; if($line["reciprical_link"] != "") { $category32a_counter++; $total2_counter++; } }
	if($line["category"] == $category[33]) { $category33_counter++; if($line["reciprical_link"] != "") { $category33a_counter++; $total2_counter++; } }
	if($line["category"] == $category[34]) { $category34_counter++; if($line["reciprical_link"] != "") { $category34a_counter++; $total2_counter++; } }
	if($line["category"] == $category[35]) { $category35_counter++; if($line["reciprical_link"] != "") { $category35a_counter++; $total2_counter++; } }
	if($line["category"] == $category[36]) { $category36_counter++; if($line["reciprical_link"] != "") { $category36a_counter++; $total2_counter++; } }
	if($line["category"] == $category[37]) { $category37_counter++; if($line["reciprical_link"] != "") { $category37a_counter++; $total2_counter++; } }
	if($line["category"] == $category[38]) { $category38_counter++; if($line["reciprical_link"] != "") { $category38a_counter++; $total2_counter++; } }
	if($line["category"] == $category[39]) { $category39_counter++; if($line["reciprical_link"] != "") { $category39a_counter++; $total2_counter++; } }
	if($line["category"] == $category[40]) { $category40_counter++; if($line["reciprical_link"] != "") { $category40a_counter++; $total2_counter++; } }
	if($line["category"] == $category[41]) { $category41_counter++; if($line["reciprical_link"] != "") { $category41a_counter++; $total2_counter++; } }
	if($line["category"] == $category[42]) { $category42_counter++; if($line["reciprical_link"] != "") { $category42a_counter++; $total2_counter++; } }
	if($line["category"] == $category[43]) { $category43_counter++; if($line["reciprical_link"] != "") { $category43a_counter++; $total2_counter++; } }
	if($line["category"] == $category[44]) { $category44_counter++; if($line["reciprical_link"] != "") { $category44a_counter++; $total2_counter++; } }
	if($line["category"] == $category[45]) { $category45_counter++; if($line["reciprical_link"] != "") { $category45a_counter++; $total2_counter++; } }
	if($line["category"] == $category[46]) { $category46_counter++; if($line["reciprical_link"] != "") { $category46a_counter++; $total2_counter++; } }
	if($line["category"] == $category[47]) { $category47_counter++; if($line["reciprical_link"] != "") { $category47a_counter++; $total2_counter++; } }
	if($line["category"] == $category[48]) { $category48_counter++; if($line["reciprical_link"] != "") { $category48a_counter++; $total2_counter++; } }
	if($line["category"] == $category[49]) { $category49_counter++; if($line["reciprical_link"] != "") { $category49a_counter++; $total2_counter++; } }
	if($line["category"] == $category[50]) { $category50_counter++; if($line["reciprical_link"] != "") { $category50a_counter++; $total2_counter++; } }
	if($line["category"] == $category[51]) { $category51_counter++; if($line["reciprical_link"] != "") { $category51a_counter++; $total2_counter++; } }
	if($line["category"] == $category[52]) { $category52_counter++; if($line["reciprical_link"] != "") { $category52a_counter++; $total2_counter++; } }
	if($line["category"] == $category[53]) { $category53_counter++; if($line["reciprical_link"] != "") { $category53a_counter++; $total2_counter++; } }
	if($line["category"] == $category[54]) { $category54_counter++; if($line["reciprical_link"] != "") { $category54a_counter++; $total2_counter++; } }
	if($line["category"] == $category[55]) { $category55_counter++; if($line["reciprical_link"] != "") { $category55a_counter++; $total2_counter++; } }
	if($line["category"] == $category[56]) { $category56_counter++; if($line["reciprical_link"] != "") { $category56a_counter++; $total2_counter++; } }
	if($line["category"] == $category[57]) { $category57_counter++; if($line["reciprical_link"] != "") { $category57a_counter++; $total2_counter++; } }
	if($line["category"] == $category[58]) { $category58_counter++; if($line["reciprical_link"] != "") { $category58a_counter++; $total2_counter++; } }
	if($line["category"] == $category[59]) { $category59_counter++; if($line["reciprical_link"] != "") { $category59a_counter++; $total2_counter++; } }
	if($line["category"] == $category[60]) { $category60_counter++; if($line["reciprical_link"] != "") { $category60a_counter++; $total2_counter++; } }
	if($line["category"] == $category[61]) { $category61_counter++; if($line["reciprical_link"] != "") { $category61a_counter++; $total2_counter++; } }
	if($line["category"] == $category[62]) { $category62_counter++; if($line["reciprical_link"] != "") { $category62a_counter++; $total2_counter++; } }
	if($line["category"] == $category[63]) { $category63_counter++; if($line["reciprical_link"] != "") { $category63a_counter++; $total2_counter++; } }
	if($line["category"] == $category[64]) { $category64_counter++; if($line["reciprical_link"] != "") { $category64a_counter++; $total2_counter++; } }
	if($line["category"] == $category[65]) { $category65_counter++; if($line["reciprical_link"] != "") { $category65a_counter++; $total2_counter++; } }
	if($line["category"] == $category[66]) { $category66_counter++; if($line["reciprical_link"] != "") { $category66a_counter++; $total2_counter++; } }
	if($line["category"] == $category[67]) { $category67_counter++; if($line["reciprical_link"] != "") { $category67a_counter++; $total2_counter++; } }
	if($line["category"] == $category[68]) { $category68_counter++; if($line["reciprical_link"] != "") { $category68a_counter++; $total2_counter++; } }
	if($line["category"] == $category[69]) { $category69_counter++; if($line["reciprical_link"] != "") { $category69a_counter++; $total2_counter++; } }
	$total_counter++;
}
mysql_free_result($result);

$query = "SELECT website_url FROM lynkstation_links WHERE approved='0'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$to_review_counter++;
}
mysql_free_result($result);

$query = "SELECT website_url FROM lynkstation_links WHERE approved='2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$rejected_counter++;
}
mysql_free_result($result);

$query = "SELECT website_url FROM lynkstation_links WHERE approved='3'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bad_counter++;
}
mysql_free_result($result);

$query = "SELECT website_url FROM lynkstation_links WHERE approved='4'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$no_reciprocal_counter++;
}
mysql_free_result($result);

$query = "SELECT website_url FROM lynkstation_links WHERE approved='5'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$bad_reciprocal_counter++;
}
mysql_free_result($result);

echo "<tr><td align=\"left\"><font size=\"2\">Total Active Links in LynkStation &nbsp; <b>";
echo $total_counter . "</b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total Reciprocal Links in LynkStation &nbsp; <b><font color=\"green\">";
echo $total2_counter . "</font></b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total To Be Reviewed Links in LynkStation &nbsp; <b>";
echo $to_review_counter . "</b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total Rejected Links in LynkStation &nbsp; <b><font color=\"red\">";
echo $rejected_counter . "</font></b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total Bad Links in LynkStation &nbsp; <b><font color=\"red\">";
echo $bad_counter . "</font></b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total No Reciprocal Links in LynkStation &nbsp; <b><font color=\"red\">";
echo $no_reciprocal_counter . "</font></b></font></td></tr>\n";
echo "<tr><td align=\"left\"><font size=\"2\">Total Bad Reciprocal Links in LynkStation &nbsp; <b><font color=\"red\">";
echo $bad_reciprocal_counter . "</font></b></font></td></tr>\n";

echo "<tr><td>&nbsp;</td></tr>\n";

echo "<tr><td align=\"left\"><font size=\"2\">";
if($category[0] != "") {
	echo "Total Links in " . $category[0] . " category &nbsp; <b>" . $category0_counter . " &nbsp; <font color=\"green\">" . $category0a_counter . "</font></b><br>\n";
}
if($category[1] != "") {
	echo "Total Links in " . $category[1] . " category &nbsp; <b>" . $category1_counter . " &nbsp; <font color=\"green\">" . $category1a_counter . "</font></b><br>\n";
}
if($category[2] != "") {
	echo "Total Links in " . $category[2] . " category &nbsp; <b>" . $category2_counter . " &nbsp; <font color=\"green\">" . $category2a_counter . "</font></b><br>\n";
}
if($category[3] != "") {
	echo "Total Links in " . $category[3] . " category &nbsp; <b>" . $category3_counter . " &nbsp; <font color=\"green\">" . $category3a_counter . "</font></b><br>\n";
}
if($category[4] != "") {
	echo "Total Links in " . $category[4] . " category &nbsp; <b>" . $category4_counter . " &nbsp; <font color=\"green\">" . $category4a_counter . "</font></b><br>\n";
}
if($category[5] != "") {
	echo "Total Links in " . $category[5] . " category &nbsp; <b>" . $category5_counter . " &nbsp; <font color=\"green\">" . $category5a_counter . "</font></b><br>\n";
}
if($category[6] != "") {
	echo "Total Links in " . $category[6] . " category &nbsp; <b>" . $category6_counter . " &nbsp; <font color=\"green\">" . $category6a_counter . "</font></b><br>\n";
}
if($category[7] != "") {
	echo "Total Links in " . $category[7] . " category &nbsp; <b>" . $category7_counter . " &nbsp; <font color=\"green\">" . $category7a_counter . "</font></b><br>\n";
}
if($category[8] != "") {
	echo "Total Links in " . $category[8] . " category &nbsp; <b>" . $category8_counter . " &nbsp; <font color=\"green\">" . $category8a_counter . "</font></b><br>\n";
}
if($category[9] != "") {
	echo "Total Links in " . $category[9] . " category &nbsp; <b>" . $category9_counter . " &nbsp; <font color=\"green\">" . $category9a_counter . "</font></b><br>\n";
}
if($category[10] != "") {
	echo "Total Links in " . $category[10] . " category &nbsp; <b>" . $category10_counter . " &nbsp; <font color=\"green\">" . $category10a_counter . "</font></b><br>\n";
}
if($category[11] != "") {
	echo "Total Links in " . $category[11] . " category &nbsp; <b>" . $category11_counter . " &nbsp; <font color=\"green\">" . $category11a_counter . "</font></b><br>\n";
}
if($category[12] != "") {
	echo "Total Links in " . $category[12] . " category &nbsp; <b>" . $category12_counter . " &nbsp; <font color=\"green\">" . $category12a_counter . "</font></b><br>\n";
}
if($category[13] != "") {
	echo "Total Links in " . $category[13] . " category &nbsp; <b>" . $category13_counter . " &nbsp; <font color=\"green\">" . $category13a_counter . "</font></b><br>\n";
}
if($category[14] != "") {
	echo "Total Links in " . $category[14] . " category &nbsp; <b>" . $category14_counter . " &nbsp; <font color=\"green\">" . $category14a_counter . "</font></b><br>\n";
}
if($category[15] != "") {
	echo "Total Links in " . $category[15] . " category &nbsp; <b>" . $category15_counter . " &nbsp; <font color=\"green\">" . $category15a_counter . "</font></b><br>\n";
}
if($category[16] != "") {
	echo "Total Links in " . $category[16] . " category &nbsp; <b>" . $category16_counter . " &nbsp; <font color=\"green\">" . $category16a_counter . "</font></b><br>\n";
}
if($category[17] != "") {
	echo "Total Links in " . $category[17] . " category &nbsp; <b>" . $category17_counter . " &nbsp; <font color=\"green\">" . $category17a_counter . "</font></b><br>\n";
}
if($category[18] != "") {
	echo "Total Links in " . $category[18] . " category &nbsp; <b>" . $category18_counter . " &nbsp; <font color=\"green\">" . $category18a_counter . "</font></b><br>\n";
}
if($category[19] != "") {
	echo "Total Links in " . $category[19] . " category &nbsp; <b>" . $category19_counter . " &nbsp; <font color=\"green\">" . $category19a_counter . "</font></b><br>\n";
}
if($category[20] != "") {
	echo "Total Links in " . $category[20] . " category &nbsp; <b>" . $category20_counter . " &nbsp; <font color=\"green\">" . $category20a_counter . "</font></b><br>\n";
}
if($category[21] != "") {
	echo "Total Links in " . $category[21] . " category &nbsp; <b>" . $category21_counter . " &nbsp; <font color=\"green\">" . $category21a_counter . "</font></b><br>\n";
}
if($category[22] != "") {
	echo "Total Links in " . $category[22] . " category &nbsp; <b>" . $category22_counter . " &nbsp; <font color=\"green\">" . $category22a_counter . "</font></b><br>\n";
}
if($category[23] != "") {
	echo "Total Links in " . $category[23] . " category &nbsp; <b>" . $category23_counter . " &nbsp; <font color=\"green\">" . $category23a_counter . "</font></b><br>\n";
}
if($category[24] != "") {
	echo "Total Links in " . $category[24] . " category &nbsp; <b>" . $category24_counter . " &nbsp; <font color=\"green\">" . $category24a_counter . "</font></b><br>\n";
}
if($category[25] != "") {
	echo "Total Links in " . $category[25] . " category &nbsp; <b>" . $category25_counter . " &nbsp; <font color=\"green\">" . $category25a_counter . "</font></b><br>\n";
}
if($category[26] != "") {
	echo "Total Links in " . $category[26] . " category &nbsp; <b>" . $category26_counter . " &nbsp; <font color=\"green\">" . $category26a_counter . "</font></b><br>\n";
}
if($category[27] != "") {
	echo "Total Links in " . $category[27] . " category &nbsp; <b>" . $category27_counter . " &nbsp; <font color=\"green\">" . $category27a_counter . "</font></b><br>\n";
}
if($category[28] != "") {
	echo "Total Links in " . $category[28] . " category &nbsp; <b>" . $category28_counter . " &nbsp; <font color=\"green\">" . $category28a_counter . "</font></b><br>\n";
}
if($category[29] != "") {
	echo "Total Links in " . $category[29] . " category &nbsp; <b>" . $category29_counter . " &nbsp; <font color=\"green\">" . $category29a_counter . "</font></b><br>\n";
}
if($category[30] != "") {
	echo "Total Links in " . $category[30] . " category &nbsp; <b>" . $category30_counter . " &nbsp; <font color=\"green\">" . $category30a_counter . "</font></b><br>\n";
}
if($category[31] != "") {
	echo "Total Links in " . $category[31] . " category &nbsp; <b>" . $category31_counter . " &nbsp; <font color=\"green\">" . $category31a_counter . "</font></b><br>\n";
}
if($category[32] != "") {
	echo "Total Links in " . $category[32] . " category &nbsp; <b>" . $category32_counter . " &nbsp; <font color=\"green\">" . $category32a_counter . "</font></b><br>\n";
}
if($category[33] != "") {
	echo "Total Links in " . $category[33] . " category &nbsp; <b>" . $category33_counter . " &nbsp; <font color=\"green\">" . $category33a_counter . "</font></b><br>\n";
}
if($category[34] != "") {
	echo "Total Links in " . $category[34] . " category &nbsp; <b>" . $category34_counter . " &nbsp; <font color=\"green\">" . $category34a_counter . "</font></b><br>\n";
}
if($category[35] != "") {
	echo "Total Links in " . $category[35] . " category &nbsp; <b>" . $category35_counter . " &nbsp; <font color=\"green\">" . $category35a_counter . "</font></b><br>\n";
}
if($category[36] != "") {
	echo "Total Links in " . $category[36] . " category &nbsp; <b>" . $category36_counter . " &nbsp; <font color=\"green\">" . $category36a_counter . "</font></b><br>\n";
}
if($category[37] != "") {
	echo "Total Links in " . $category[37] . " category &nbsp; <b>" . $category37_counter . " &nbsp; <font color=\"green\">" . $category37a_counter . "</font></b><br>\n";
}
if($category[38] != "") {
	echo "Total Links in " . $category[38] . " category &nbsp; <b>" . $category38_counter . " &nbsp; <font color=\"green\">" . $category38a_counter . "</font></b><br>\n";
}
if($category[39] != "") {
	echo "Total Links in " . $category[39] . " category &nbsp; <b>" . $category39_counter . " &nbsp; <font color=\"green\">" . $category39a_counter . "</font></b><br>\n";
}
if($category[40] != "") {
	echo "Total Links in " . $category[40] . " category &nbsp; <b>" . $category40_counter . " &nbsp; <font color=\"green\">" . $category40a_counter . "</font></b><br>\n";
}
if($category[41] != "") {
	echo "Total Links in " . $category[41] . " category &nbsp; <b>" . $category41_counter . " &nbsp; <font color=\"green\">" . $category41a_counter . "</font></b><br>\n";
}
if($category[42] != "") {
	echo "Total Links in " . $category[42] . " category &nbsp; <b>" . $category42_counter . " &nbsp; <font color=\"green\">" . $category42a_counter . "</font></b><br>\n";
}
if($category[43] != "") {
	echo "Total Links in " . $category[43] . " category &nbsp; <b>" . $category43_counter . " &nbsp; <font color=\"green\">" . $category43a_counter . "</font></b><br>\n";
}
if($category[44] != "") {
	echo "Total Links in " . $category[44] . " category &nbsp; <b>" . $category44_counter . " &nbsp; <font color=\"green\">" . $category44a_counter . "</font></b><br>\n";
}
if($category[45] != "") {
	echo "Total Links in " . $category[45] . " category &nbsp; <b>" . $category45_counter . " &nbsp; <font color=\"green\">" . $category45a_counter . "</font></b><br>\n";
}
if($category[46] != "") {
	echo "Total Links in " . $category[46] . " category &nbsp; <b>" . $category46_counter . " &nbsp; <font color=\"green\">" . $category46a_counter . "</font></b><br>\n";
}
if($category[47] != "") {
	echo "Total Links in " . $category[47] . " category &nbsp; <b>" . $category47_counter . " &nbsp; <font color=\"green\">" . $category47a_counter . "</font></b><br>\n";
}
if($category[48] != "") {
	echo "Total Links in " . $category[48] . " category &nbsp; <b>" . $category48_counter . " &nbsp; <font color=\"green\">" . $category48a_counter . "</font></b><br>\n";
}
if($category[49] != "") {
	echo "Total Links in " . $category[49] . " category &nbsp; <b>" . $category49_counter . " &nbsp; <font color=\"green\">" . $category49a_counter . "</font></b><br>\n";
}
if($category[50] != "") {
	echo "Total Links in " . $category[50] . " category &nbsp; <b>" . $category50_counter . " &nbsp; <font color=\"green\">" . $category50a_counter . "</font></b><br>\n";
}
if($category[51] != "") {
	echo "Total Links in " . $category[51] . " category &nbsp; <b>" . $category51_counter . " &nbsp; <font color=\"green\">" . $category51a_counter . "</font></b><br>\n";
}
if($category[52] != "") {
	echo "Total Links in " . $category[52] . " category &nbsp; <b>" . $category52_counter . " &nbsp; <font color=\"green\">" . $category52a_counter . "</font></b><br>\n";
}
if($category[53] != "") {
	echo "Total Links in " . $category[53] . " category &nbsp; <b>" . $category53_counter . " &nbsp; <font color=\"green\">" . $category53a_counter . "</font></b><br>\n";
}
if($category[54] != "") {
	echo "Total Links in " . $category[54] . " category &nbsp; <b>" . $category54_counter . " &nbsp; <font color=\"green\">" . $category54a_counter . "</font></b><br>\n";
}
if($category[55] != "") {
	echo "Total Links in " . $category[55] . " category &nbsp; <b>" . $category55_counter . " &nbsp; <font color=\"green\">" . $category55a_counter . "</font></b><br>\n";
}
if($category[56] != "") {
	echo "Total Links in " . $category[56] . " category &nbsp; <b>" . $category56_counter . " &nbsp; <font color=\"green\">" . $category56a_counter . "</font></b><br>\n";
}
if($category[57] != "") {
	echo "Total Links in " . $category[57] . " category &nbsp; <b>" . $category57_counter . " &nbsp; <font color=\"green\">" . $category57a_counter . "</font></b><br>\n";
}
if($category[58] != "") {
	echo "Total Links in " . $category[58] . " category &nbsp; <b>" . $category58_counter . " &nbsp; <font color=\"green\">" . $category58a_counter . "</font></b><br>\n";
}
if($category[59] != "") {
	echo "Total Links in " . $category[59] . " category &nbsp; <b>" . $category59_counter . " &nbsp; <font color=\"green\">" . $category59a_counter . "</font></b><br>\n";
}
if($category[60] != "") {
	echo "Total Links in " . $category[60] . " category &nbsp; <b>" . $category60_counter . " &nbsp; <font color=\"green\">" . $category60a_counter . "</font></b><br>\n";
}
if($category[61] != "") {
	echo "Total Links in " . $category[61] . " category &nbsp; <b>" . $category61_counter . " &nbsp; <font color=\"green\">" . $category61a_counter . "</font></b><br>\n";
}
if($category[62] != "") {
	echo "Total Links in " . $category[62] . " category &nbsp; <b>" . $category62_counter . " &nbsp; <font color=\"green\">" . $category62a_counter . "</font></b><br>\n";
}
if($category[63] != "") {
	echo "Total Links in " . $category[63] . " category &nbsp; <b>" . $category63_counter . " &nbsp; <font color=\"green\">" . $category63a_counter . "</font></b><br>\n";
}
if($category[64] != "") {
	echo "Total Links in " . $category[64] . " category &nbsp; <b>" . $category64_counter . " &nbsp; <font color=\"green\">" . $category64a_counter . "</font></b><br>\n";
}
if($category[65] != "") {
	echo "Total Links in " . $category[65] . " category &nbsp; <b>" . $category65_counter . " &nbsp; <font color=\"green\">" . $category65a_counter . "</font></b><br>\n";
}
if($category[66] != "") {
	echo "Total Links in " . $category[66] . " category &nbsp; <b>" . $category66_counter . " &nbsp; <font color=\"green\">" . $category66a_counter . "</font></b><br>\n";
}
if($category[67] != "") {
	echo "Total Links in " . $category[67] . " category &nbsp; <b>" . $category67_counter . " &nbsp; <font color=\"green\">" . $category67a_counter . "</font></b><br>\n";
}
if($category[68] != "") {
	echo "Total Links in " . $category[68] . " category &nbsp; <b>" . $category68_counter . " &nbsp; <font color=\"green\">" . $category68a_counter . "</font></b><br>\n";
}
if($category[69] != "") {
	echo "Total Links in " . $category[69] . " category &nbsp; <b>" . $category69_counter . " &nbsp; <font color=\"green\">" . $category69a_counter . "</font></b><br>\n";
}
echo "</font></td></tr>\n";

echo "<tr><td>&nbsp;</td></tr>\n";

$filter_status0 = 0;
$filter_status1 = 0;
$filter_status2 = 0;
$filter_total = 0;

$query = "SELECT status FROM lynkstation_filters";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["status"] == 0) {
		$filter_status0++;
	} elseif($line["status"] == 1) {
		$filter_status1++;
	} elseif($line["status"] == 2) {
		$filter_status2++;
	}
	$filter_total++;
}
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $filter_total; ?></b> Total Filters in LynkStation<br>
There are <b><?php echo $filter_status1; ?></b> Active Filters in LynkStation<br>
There are <b><?php echo $filter_status2; ?></b> To Review Filters in LynkStation<br>
There are <b><?php echo $filter_status0; ?></b> Inactive Filters in LynkStation<br>
</font></td></tr>

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