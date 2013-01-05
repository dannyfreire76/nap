<?php
// BME WMS
// Page: Content Manager Create Content Categories page
// Path/File: /admin/content_admin6.php
// Version: 1.8
// Build: 1801
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

$article_category_id = $_POST["article_category_id"];
$position = $_POST["position"];
$status = $_POST["status"];
$category_name = $_POST["category_name"];
$site_path = $_POST["site_path"];
$submit = $_POST["submit"];

include './includes/wms_nav1.php';
$manager = "content";
$page = "Content Manager > Create Content Categories";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($article_category_id) {
	//Check for Errors
	$error_txt = "";
	if($category_name == "") { $error_txt .= "Error, the Category Name is blank. There needs to be a category name.<br>"; }
	if($site_path == "") { $error_txt .= "Error, the Site Path is blank. There needs to be a site path.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE article_categories SET position='$position', status='$status', category_name='$category_name', site_path='$site_path' WHERE article_category_id='$article_category_id'";
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

<tr><td align="left"><font size="2">Create Content categories - used by articles, article lists, and pages.</font></td></tr>

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