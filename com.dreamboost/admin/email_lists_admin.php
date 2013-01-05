<?php
// BME WMS
// Page: Email Lists Admin Homepage
// Path/File: /admin/email_lists_admin.php
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
$manager = "email_lists";
$page = "E-Mail Lists Manager > Homepage";
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

<tr><td align="left"><font size="2">Welcome to the E-Mail Lists Manager, where you manage the E-Mail Lists section of your website. On this page you will find general statistics about your E-Mail Lists. As well, please click through to the other pages of the E-Mail Lists Manager to manage other portions.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT email, status FROM news_member";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$member_counter1 = 0;
$member_counter2 = 0;
$member_counter3 = 0;
$member_counter4 = 0;

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$email = $line["email"];
	$status = $line["status"];
	if($status == "0") { $member_counter1 = $member_counter1 + 1; }
	if($status == "1") { $member_counter2 = $member_counter2 + 1; }
	if($status == "2") { $member_counter3 = $member_counter3 + 1; }
	if($status == "3") { $member_counter4 = $member_counter4 + 1; }
}
mysql_free_result($result);
?>
<tr><td align="left"><font size="2">There are currently <b><?php echo $member_counter2; ?></b> Subscribed Users<br>
There are currently <b><?php echo $member_counter1; ?></b> Users Awaiting Confirmation<br>
There are currently <b><?php echo $member_counter3; ?></b> Unsubscribed Users<br>
There are currently <b><?php echo $member_counter4; ?></b> On Hold Users<br></font></td></tr>

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