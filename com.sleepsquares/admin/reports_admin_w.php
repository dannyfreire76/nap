<?php
// BME WMS
// Page: Reports Manager Wholesale Sales page
// Path/File: /admin/reports_admin_w.php
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
$manager = "reports";
$page = "Reports Manager > Wholesale Sales Reports";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "SELECT state_tax FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$state_tax = $line["state_tax"];
}
mysql_free_result($result);
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Wholesale Sales section where you will find a collection of reports and statistical information about the wholesale sales on your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><!--<a href="./reports_admin_w2.php">-->Affiliate Wholesale Sales Report</a><br>
<a href="./reports_admin_w18.php">Wholesale By Processor Sales Report</a><br>
<!--<a href="./reports_admin_w3.php">-->Daily Wholesale Sales Report</a><br>
<!--<a href="./reports_admin_w13.php">-->Daily Wholesale By Category Sales Report</a><br>
<!--<a href="./reports_admin_w8.php">-->Daily Wholesale Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_w4.php">-->Weekly Wholesale Sales Report</a><br>
<!--<a href="./reports_admin_w14.php">-->Weekly Wholesale By Category Sales Report</a><br>
<!--<a href="./reports_admin_w9.php">-->Weekly Wholesale Complete Each Order Information Sales Report</a><br>
<a href="./reports_admin_w5.php">Monthly Wholesale Sales Report</a><br>
<a href="./reports_admin_w19.php">Monthly Wholesale Profit Analysis (Revenue vs. Cost) Sales Report</a><br>
<a href="./reports_admin_w21.php">Monthly Wholesale Profit Analysis (Revenue vs. Cost) SKU Breakdown Sales Report</a><br>
<!--<a href="./reports_admin_w15.php">-->Monthly Wholesale By Category Sales Report</a><br>
<!--<a href="./reports_admin_w10.php">-->Monthly Wholesale Complete Each Order Information Sales Report</a><br>
<a href="./reports_admin_w6.php">Yearly Wholesale Sales Report</a><br>
<a href="./reports_admin_w20.php">Yearly Wholesale Profit Analysis (Revenue vs. Cost) Sales Report</a><br>
<!--<a href="./reports_admin_w16.php">-->Yearly Wholesale By Category Sales Report</a><br>
<!--<a href="./reports_admin_w11.php">-->Yearly Wholesale Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_w7.php">-->Yearly (Just <?php echo $state_tax; ?>) Wholesale Sales Report</a><br>
<!--<a href="./reports_admin_w17.php">-->Yearly (Just <?php echo $state_tax; ?>) Wholesale By Category Sales Report</a><br>
<!--<a href="./reports_admin_w12.php">-->Yearly (Just <?php echo $state_tax; ?>) Wholesale Complete Each Order Information Sales Report</a><br>
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