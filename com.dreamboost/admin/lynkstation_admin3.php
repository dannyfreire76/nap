<?php
// BME WMS
// Page: LynkStation Manage Categories page
// Path/File: /admin/lynkstation_admin3.php
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
$position = $_POST["position"];
$lscats_id = $_POST["lscats_id"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Manage Categories";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($lscats_id != "") {
	//Validate
	$error_txt = "";

	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE lynkstation_cats SET name='$name', position='$position' WHERE lscats_id='$lscats_id'";
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

<tr><td align="left"><font size="2">Welcome to LynkStation, where you manage the Links section of your website. On this page you manage the categories on your LynkStation. You can choose up to thirty categories and put them in any order.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left"><font size="2"><table border="0">
<tr><td><font face="Arial" size="+1"><b>Position</b></font></td><td><font face="Arial" size="+1"><b>Category Name</b></font></td><td>&nbsp;</td></tr>

<?php
$query = "SELECT lscats_id, name, position FROM lynkstation_cats ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><form action=\"./lynkstation_admin3.php\" method=\"POST\"><td><SELECT name=\"position\">\n";
	for($i=1;$i<81;$i++) {
		echo "<option value=\"$i\"";
		if($line["position"] == $i) { echo " SELECTED"; }
		echo ">$i</option>\n";
	}
	echo "</select></td><input type=\"hidden\" name=\"lscats_id\" value=\"";
	echo $line["lscats_id"];
	echo "\"><td><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"100\" value=\"";
	echo $line["name"];
	echo "\"></td><td><input type=\"submit\" value=\"Edit\"></td></form></tr>\n";
}
?>
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