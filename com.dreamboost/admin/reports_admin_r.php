<?php
// BME WMS
// Page: Reports Manager Retail Sales page
// Path/File: /admin/reports_admin_r.php
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
$page = "Reports Manager > Retail Sales Reports";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Retail Sales section, where you will find a collection of reports and statistical information about the retail sales on your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><a href="./reports_admin_r2.php">Discount Codes Retail Sales Report</a><br>

<!--<a href="./reports_admin_r3.php">-->Daily Retail Sales Report</a><br>
<!--<a href="./reports_admin_r13.php">-->Daily Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r8.php">-->Daily Retail Each Order Complete Information Sales Report</a><br>

<!--<a href="./reports_admin_r4.php">-->Weekly Retail Sales Report</a><br>
<!--<a href="./reports_admin_r14.php">-->Weekly Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r9.php">-->Weekly Retail Each Order Complete Information Sales Report</a><br>

<a href="./reports_admin_r5.php">Monthly Retail Sales Report</a><br>
<a href="./reports_admin_r21.php">Monthly Retail Profit Analysis (Revenue vs. Cost) Report</a><br>
<!--<a href="./reports_admin_r15.php">-->Monthly Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r10.php">-->Monthly Retail Each Order Complete Information Sales Report</a><br>
<a href="./reports_admin_r18.php">Monthly (Just <?php echo $state_tax; ?>) Retail Sales Report</a><br>
<!--<a href="./reports_admin_r19.php">-->Monthly (Just <?php echo $state_tax; ?>) Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r20.php">-->Monthly (Just <?php echo $state_tax; ?>) Retail Each Order Complete Information Sales Report</a><br>

<a href="./reports_admin_r6.php">Yearly Retail Sales Report</a><br>
<a href="./reports_admin_r22.php">Yearly Retail Profit Analysis (Revenue vs. Cost) Report</a><br>
<!--<a href="./reports_admin_r16.php">-->Yearly Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r11.php">-->Yearly Retail Each Order Complete Information Sales Report</a><br>
<a href="./reports_admin_r7.php">Yearly (Just <?php echo $state_tax; ?>) Retail Sales Report</a><br>
<!--<a href="./reports_admin_r17.php">-->Yearly (Just <?php echo $state_tax; ?>) Retail By Category Sales Report</a><br>
<!--<a href="./reports_admin_r12.php">-->Yearly (Just <?php echo $state_tax; ?>) Retail Each Order Complete Information Sales Report</a><br>
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