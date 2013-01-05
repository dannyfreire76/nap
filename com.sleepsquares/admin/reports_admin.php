<?php
// BME WMS
// Page: Reports Manager Homepage
// Path/File: /admin/reports_admin.php
// Version: 1.8
// Build: 1804
// Date: 05-18-2007

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
$page = "Reports Manager > Homepage";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager, where you will find a collection of reports and statistical information about your website. Everything from sales, traffic, testimonials, and links.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><a href="./reports_admin_r.php">Retail Sales Reports</a><br>
<a href="./reports_admin_w.php">Wholesale Sales Reports</a><br>
<a href="./reports_admin_c.php">Combined Sales Reports</a><br>
<a href="./reports_admin_wt.php">Website Traffic Reports</a><br>
Products Reports<br>
Accounts Reports<br>
Website Content Reports<br>
LynkStation Reports<br>
Contact Us Reports<br>
Testimonials Reports<br>
Email Lists Reports<br>
Send To A Friend Reports<br>
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