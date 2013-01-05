<?php
// BME WMS
// Page: Members Manager Homepage
// Path/File: /admin/members_admin.php
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
$manager = "members";
$page = "Members Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($main_id) {
	//Check for Errors
	$error_txt = "";
	if($email == "") { $error_txt .= "Error, the email address is blank. There needs to be an email address.<br>\n"; }
	if($entries_per_page == "") { $error_txt .= "Error, the Testimonials per Page entry is blank. Please complete this.<br>\n"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE testimonials_main SET email='$email', notify_owner='$notify_owner', entries_per_page='$entries_per_page' WHERE main_id='$main_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
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

<tr><td align="left"><font size="2">Welcome to the Members Manager, where you manage the members of your website. Below are the general settings and statistics for your Members Manager. On the following pages is where you control the heart of the system.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$mem_counter1 = 0;
$mem_counter2 = 0;
$mem_counter3 = 0;
$query = "SELECT status FROM members";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line["status"];
	if($status == "0") { $mem_counter2 = $mem_counter2 + 1; }
	if($status == "1") { $mem_counter3 = $mem_counter3 + 1; }
}
$mem_counter1 = $mem_counter2 + $mem_counter3;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $mem_counter1; ?></b> Members of this site<br>
There are <b><?php echo $mem_counter3; ?></b> Members active<br>
There are <b><?php echo $mem_counter2; ?></b> Members inactive</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$hour_counter = 0;
$day_counter = 0;
$week_counter = 0;
$month_counter = 0;
$year_counter = 0;

$now = date("Y-m-d H:i:s");
$lasthour = date("Y-m-d H:i:s", mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"),  date("Y")));
$lastday = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1,  date("Y")));
$lastweek = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d")-7,  date("Y")));
$lastmonth = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m")-1, date("d"),  date("Y")));
$lastyear = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d"),  date("Y")-1));

$query = "SELECT count(*) as count FROM members WHERE last_login >= '$lasthour' AND last_login <= '$now'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$hour_counter = $line["count"];
}
mysql_free_result($result);

$query = "SELECT count(*) as count FROM members WHERE last_login >= '$lastday' AND last_login <= '$now'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$day_counter = $line["count"];
}
mysql_free_result($result);

$query = "SELECT count(*) as count FROM members WHERE last_login >= '$lastweek' AND last_login <= '$now'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$week_counter = $line["count"];
}
mysql_free_result($result);

$query = "SELECT count(*) as count FROM members WHERE last_login >= '$lastmonth' AND last_login <= '$now'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$month_counter = $line["count"];
}
mysql_free_result($result);

$query = "SELECT count(*) as count FROM members WHERE last_login >= '$lastyear' AND last_login <= '$now'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$year_counter = $line["count"];
}
mysql_free_result($result);

?>

<tr><td align="left"><font size="2">
<b><?php echo $hour_counter; ?></b> Users have Logged-In in the last Hour<br>
<b><?php echo $day_counter; ?></b> Users have Logged-In in the last Day<br>
<b><?php echo $week_counter; ?></b> Users have Logged-In in the last Week<br>
<b><?php echo $month_counter; ?></b> Users have Logged-In in the last Month<br>
<b><?php echo $year_counter; ?></b> Users have Logged-In in the last Year
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