<?php
// BME WMS
// Page: LynkStation Manager Homepage
// Path/File: /admin/lynkstation_admin.php
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

$name = $_POST["name"];
$email = $_POST["email"];
$approval_reqd = $_POST["approval_reqd"];
$links_per_cat = $_POST["links_per_cat"];
$notify_owner = $_POST["notify_owner"];
$separate_pages = $_POST["separate_pages"];
$colored_boxes = $_POST["colored_boxes"];
$website_dups = $_POST["website_dups"];
$reciprocal_dups = $_POST["reciprocal_dups"];
$title = $_POST["title"];
$url = $_POST["url"];
$description = $_POST["description"];
$lsmain_id = $_POST["lsmain_id"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($lsmain_id != "") {
	//Validate
	$error_txt = "";
	if($name == "") { $error_txt .= "The LynkStation Name field is blank. Please complete this field.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$email) ){
		$error_txt .= "The LynkStation Email Address field is blank or you entered the address incorrectly. ";
		$error_txt .= "Please complete this field.<br>\n";
	}
	if($links_per_cat == "") { $error_txt .= "The Links Allowed Per Category field is blank. Please complete this field.<br>\n"; }
	if($title == "") { $error_txt .= "The Your Link Title field is blank. Please complete this field.<br>\n"; }
	if($url == "") { $error_txt .= "The Your Link URL field is blank. Please complete this field.<br>\n"; }
	if($description == "") { $error_txt .= "The Your Link Description field is blank. Please complete this field.<br>\n"; }

	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE lynkstation_main SET name='$name', email='$email', approval_reqd='$approval_reqd', links_per_cat='$links_per_cat', notify_owner='$notify_owner', separate_pages='$separate_pages', colored_boxes='$colored_boxes', website_dups='$website_dups', reciprocal_dups='$reciprocal_dups', title='$title', url='$url', description='$description' WHERE lsmain_id='$lsmain_id'";
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

<tr><td align="left"><font size="2">Welcome to LynkStation, where you manage the Links section of your website. On this page you will find general statistics and variables to control for your LynkStation - you can edit them below. As well, please click through to the other pages of the LynkStation to manage other portions.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
$total_counter = 0;
$total_counter2 = 0;

$query = "SELECT category FROM lynkstation_links WHERE approved='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$total_counter++;
}
mysql_free_result($result);

echo "<tr><td align=\"left\"><font size=\"2\">Total Active Links in LynkStation <b>";
echo $total_counter . "</b></font></td></tr>\n";

$query = "SELECT category FROM lynkstation_links WHERE approved='0'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$total_counter2++;
}
mysql_free_result($result);

echo "<tr><td align=\"left\"><font size=\"2\">Links To Be Approved in LynkStation <b>";
echo $total_counter2 . "</b></font></td></tr>\n";

echo "<tr><td>&nbsp;</td></tr>\n";

$query = "SELECT lsmain_id, name, email, approval_reqd, links_per_cat, notify_owner, separate_pages, colored_boxes, website_dups, reciprocal_dups, title, url, description FROM lynkstation_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$lsmain_id = $line["lsmain_id"];
	$name = $line["name"];
	$email = $line["email"];
	$approval_reqd = $line["approval_reqd"];
	$links_per_cat = $line["links_per_cat"];
	$notify_owner = $line["notify_owner"];
	$separate_pages = $line["separate_pages"];
	$colored_boxes = $line["colored_boxes"];
	$website_dups = $line["website_dups"];
	$reciprocal_dups = $line["reciprocal_dups"];
	$title = $line["title"];
	$url = $line["url"];
	$description = $line["description"];
}
mysql_free_result($result);
?>
<form action="./lynkstation_admin.php" method="POST">
<input type="hidden" name="lsmain_id" value="<?php echo $lsmain_id; ?>">
<tr><td align="left"><font size="2"><table border="0">
<tr><td><font face="Arial" size="+1">LynkStation Name:</font></td><td><input type="text" name="name" size="30" maxlength="255" value="<?php echo $name; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">LynkStation Email Address:</font></td><td><input type="text" name="email" size="30" maxlength="100" value="<?php echo $email; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">Your Approval Required to Post Links:</font></td><td><SELECT name="approval_reqd"><option value="1"<?php if($approval_reqd == "1") { echo " SELECTED"; } ?>>Yes</option><option value="0"<?php if($approval_reqd == "0") { echo " SELECTED"; } ?>>No</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Links Allowed Per Category:</font></td><td><input type="text" name="links_per_cat" size="3" maxlength="3" value="<?php echo $links_per_cat; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">Notify Owner When Links Submitted:</font></td><td><SELECT name="notify_owner"><option value="0"<?php if($notify_owner == "0") { echo " SELECTED"; } ?>>No</option><option value="1"<?php if($notify_owner == "1") { echo " SELECTED"; } ?>>Yes</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Categories Displayed on Separate Pages:</font></td><td><SELECT name="separate_pages"><option value="0"<?php if($separate_pages == "0") { echo " SELECTED"; } ?>>No</option><option value="1"<?php if($separate_pages == "1") { echo " SELECTED"; } ?>>Yes</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Display Colored Boxes:</font></td><td><SELECT name="colored_boxes"><option value="0"<?php if($colored_boxes == "0") { echo " SELECTED"; } ?>>No</option><option value="1"<?php if($colored_boxes == "1") { echo " SELECTED"; } ?>>Yes</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Allow Website URL Duplicates:</font></td><td><SELECT name="website_dups"><option value="0"<?php if($website_dups == "0") { echo " SELECTED"; } ?>>No</option><option value="1"<?php if($website_dups == "1") { echo " SELECTED"; } ?>>Yes</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Allow Reciprocal Link Duplicates:</font></td><td><SELECT name="reciprocal_dups"><option value="0"<?php if($reciprocal_dups == "0") { echo " SELECTED"; } ?>>No</option><option value="1"<?php if($reciprocal_dups == "1") { echo " SELECTED"; } ?>>Yes</option></select></td></tr>
<tr><td><font face="Arial" size="+1">Your Link Title:</font></td><td><input type="text" name="title" size="30" maxlength="150" value="<?php echo $title; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">Your Link URL:</font></td><td><input type="text" name="url" size="30" maxlength="150" value="<?php echo $url; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">Your Link Description:</font></td><td><input type="text" name="description" size="30" maxlength="255" value="<?php echo $description; ?>"></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Submit"></td></tr>
</form>
</table></td></tr>

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