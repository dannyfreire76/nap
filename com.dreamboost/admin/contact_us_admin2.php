<?php
// BME WMS
// Page: Contact Us Manager Unanswered Contacts page
// Path/File: /admin/contact_us_admin2.php
// Version: 1.8
// Build: 1805
// Date: 05-14-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/pagination1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$page_this = $_GET["page_this"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "contact_us";
$page = "Contact Us Manager > Unanswered Contacts";
$url = "contact_us_admin2.php";
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

<tr><td align="left"><font size="2">Welcome to the Contact Us Manager, where you manage all the incoming contact e-mail requiring answers. On this page you will find all the unanswered contacts in the system - starting with the oldest on top. You can click on any contact to view it's entire contents and to answer the contact.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Created</th><th scope="col">Name</th><th scope="col">Subject</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM contact_us WHERE answered='0000-00-00 00:00:00'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT contact_id, created, name, subject FROM contact_us WHERE answered='0000-00-00 00:00:00' ORDER BY created LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	list($created_date, $created_time) = split(' ', $line["created"]);
	list($created_yr, $created_mn, $created_dy) = split('-', $created_date);
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $created_mn . "/" . $created_dy . "/" . $created_yr . " " . $created_time;
	echo "</td><td>";
	echo $line["name"];
	echo "</td><td>";
	echo htmlentities($line["subject"]);
	echo "</td><FORM name=\"contact_us\" Method=\"POST\" ACTION=\"./contact_us_admin2_edit.php\" class=\"wmsform\">";
	echo "<input type=\"hidden\" name=\"contact_id\" value=\"";
	echo $line["contact_id"];
	echo "\"><td><input type=\"submit\" name=\"submit\" value=\"Answer\"> <input type=\"submit\" id=\"answer3\" name=\"answer3\" value=\"Mark As Answered\"></td></form></tr>\n";		
}
mysql_free_result($result);
?>
</table></td></tr>

<?php
pagination_display($url, $page_this, $limit, $record_count);
?>
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