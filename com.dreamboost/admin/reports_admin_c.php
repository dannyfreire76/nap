<?php
// BME WMS
// Page: Reports Manager Combined Sales page
// Path/File: /admin/reports_admin_c.php
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
$page = "Reports Manager > Combined Sales Reports";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Combined Sales section where you will find a collection of reports and statistical information about the combined sales on your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><!--<a href="./reports_admin_c2.php">-->Discount Code / Affiliate Combined Sales Report</a><br>
<!--<a href="./reports_admin_c3.php">-->Daily Combined Sales Report</a><br>
<!--<a href="./reports_admin_c13.php">-->Daily Combined By Category Sales Report</a><br>
<!--<a href="./reports_admin_c8.php">-->Daily Combined Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_c4.php">-->Weekly Combined Sales Report</a><br>
<!--<a href="./reports_admin_c14.php">-->Weekly Combined By Category Sales Report</a><br>
<!--<a href="./reports_admin_c9.php">-->Weekly Combined Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_c5.php">-->Monthly Combined Sales Report</a><br>
<!--<a href="./reports_admin_c15.php">-->Monthly Combined By Category Sales Report</a><br>
<!--<a href="./reports_admin_c10.php">-->Monthly Combined Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_c6.php">-->Yearly Combined Sales Report</a><br>
<!--<a href="./reports_admin_c16.php">-->Yearly Combined By Category Sales Report</a><br>
<!--<a href="./reports_admin_c11.php">-->Yearly Combined Complete Each Order Information Sales Report</a><br>
<!--<a href="./reports_admin_c7.php">-->Yearly (Just <?php echo $state_tax; ?>) Combined Sales Report</a><br>
<!--<a href="./reports_admin_c17.php">-->Yearly (Just <?php echo $state_tax; ?>) Combined By Category Sales Report</a><br>
<!--<a href="./reports_admin_c12.php">-->Yearly (Just <?php echo $state_tax; ?>) Combined Complete Each Order Information Sales Report</a><br>
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