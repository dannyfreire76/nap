<?php
// BME WMS
// Page: Content Manager Homepage
// Path/File: /admin/content_admin.php
// Version: 1.8
// Build: 1804
// Date: 01-26-2007

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
$manager = "content";
$page = "Content Manager > Homepage";
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

<tr><td align="left"><font size="2">Welcome to the Content Manager, where you manage the content on your website. These are basic statistics and settings for Content on your site.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT status FROM article";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$article_counter1 = 0;
$article_counter2 = 0;
$article_counter3 = 0;

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line["status"];
	if($status == "0") { $article_counter1 = $article_counter1 + 1; }
	if($status == "1") { $article_counter2 = $article_counter2 + 1; }
	if($status == "2") { $article_counter3 = $article_counter3 + 1; }
}
mysql_free_result($result);
?>
<tr><td align="left"><font size="2">There are currently <b><?php echo $article_counter2; ?></b> Live Articles<br>
There are currently <b><?php echo $article_counter3; ?></b> Articles To Be Reviewed<br>
There are currently <b><?php echo $article_counter1; ?></b> Inactive Articles<br>
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT status FROM article_categories";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$cat_counter1 = 0;
$cat_counter2 = 0;
$cat_counter3 = 0;

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line["status"];
	if($status == "0") { $cat_counter1 = $cat_counter1 + 1; }
	if($status == "1") { $cat_counter2 = $cat_counter2 + 1; }
	if($status == "2") { $cat_counter3 = $cat_counter2 + 1; }
}
mysql_free_result($result);
?>
<tr><td align="left"><font size="2">There are currently <b><?php echo $cat_counter2; ?></b> Live Content Categories<br>
There are currently <b><?php echo $cat_counter3; ?></b> Content Categories To Be Reviewed<br>
There are currently <b><?php echo $cat_counter1; ?></b> Inactive Content Categories<br>
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