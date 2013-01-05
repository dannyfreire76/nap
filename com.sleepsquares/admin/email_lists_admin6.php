<?php
// BME WMS
// Page: Email Lists Manage Users Awaiting Confirmation page
// Path/File: /admin/email_lists_admin6.php
// Version: 1.8
// Build: 1804
// Date: 02-07-2007

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
$status = $_POST["status"];
$name = $_POST["name"];
$email = $_POST["email"];
$member_id = $_POST["member_id"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "email_lists";
$page = "E-Mail Lists Manager > Manage Users Awaiting Confirmation";
$url = "email_lists_admin6.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($member_id != "") {
	//Validate
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@(.)*$",$email) ){
		$error_txt .= "You did not enter your email address correctly. Please try again.<br>\n";
	}
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE news_member SET status='$status', name='$name', email='$email' WHERE member_id='$member_id'";
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

<tr><td align="left"><font size="2">Welcome to the E-Mail Lists Manager, where you manage the E-Mail Newsletter section of your website. On this page you manage the users awaiting confirmation to your newsletter.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Status</th><th scope="col">Name</th><th scope="col">E-Mail</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM news_member WHERE status='0'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT member_id, status, name, email FROM news_member WHERE status='0' ORDER BY modified LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM Method=\"POST\" ACTION=\"./email_lists_admin6.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo "<SELECT name=\"status\">\n";
	echo "<option value=\"0\"";
	if($line["status"] == "0") { echo " SELECTED"; }
	echo ">Awaiting Confirmation</option>\n";
	echo "<option value=\"1\"";
	if($line["status"] == "1") { echo " SELECTED"; }
	echo ">Subscribed</option>\n";
	echo "<option value=\"2\"";
	if($line["status"] == "2") { echo " SELECTED"; }
	echo ">Unsubscribed</option>\n";
	echo "<option value=\"3\"";
	if($line["status"] == "3") { echo " SELECTED"; }
	echo ">On Hold</option>\n";
	echo "</select></td>";
	echo "<td><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"100\" value=\"";
	echo $line["name"];
	echo "\"></td><td><input type=\"text\" name=\"email\" size=\"30\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td><td align=\"center\">";
	echo "<input type=\"hidden\" name=\"member_id\" value=\"";
	echo $line["member_id"];
	echo "\">";
	echo "<input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr>\n";
	echo "</form>\n";
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