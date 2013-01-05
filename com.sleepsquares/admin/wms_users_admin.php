<?php
// BME WMS
// Page: WMS Users Homepage
// Path/File: /admin/wms_users_admin.php
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
$manager = "users";
$page = "phpWMS Users Manager > Homepage";
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

<tr><td align="left"><font size="2">Welcome to the MyBWMS Users Manager, where you manage the users of your MyBWMS. Below are the general settings and statistics for your MyBWMS Users Manager.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$user_counter1 = 0;
$user_counter2 = 0;
$user_counter3 = 0;
$query = "SELECT status FROM wms_users";
$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line["status"];
	if($status == "0") { $user_counter2 = $user_counter2 + 1; }
	if($status == "1") { $user_counter3 = $user_counter3 + 1; }
}
$user_counter1 = $user_counter2 + $user_counter3;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $user_counter1; ?></b> Users across all sites<br>
There are <b><?php echo $user_counter3; ?></b> Users active<br>
There are <b><?php echo $user_counter2; ?></b> Users inactive</font></td></tr>

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