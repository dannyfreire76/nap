<?php
// BME WMS
// Page: Photos Admin Homepage
// Path/File: /admin/photos_admin.php
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

$submit = $_POST["submit"];

include './includes/wms_nav1.php';
$manager = "photos";
$page = "Photos Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$error_txt = "";
	if($url == "") { $error_txt .= "Error, you did not enter a URL for your Merchant Account. Please enter your Merchant Account URL.<br>\n"; }
	if($username == "") { $error_txt .= "Error, you did not enter a Username for your Merchant Account. Please enter your Merchant Account Username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a Password for your Merchant Account. Please enter your Merchant Account Password.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE merchant_acct SET status='$status', company='$company', url='$url', username='$username', password='$password' WHERE merchant_acct_id='1'";
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

<tr><td align="left"><font size="2">Welcome to the Photos Manager, where you manage the Photos for the public pages of your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
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