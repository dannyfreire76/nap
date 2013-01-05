<?php
// BME WMS
// Page: FAQs Manager Manage Live FAQS page
// Path/File: /admin/faqs_admin2.php
// Version: 1.8
// Build: 1803
// Date: 01-23-2007

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
$manager = "faqs";
$page = "FAQs Manager > Manage Live FAQs";
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

<tr><td align="left"><font size="2">These are the Frequently Asked Questions being <a href="<?php echo $base_url; ?>faqs/index.php" TARGET="_BLANK">displayed on the site now</a> you can edit or remove them below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT faq_category_id, category_name FROM faqs_categories WHERE status='1' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category_name = $line["category_name"];
	$faq_category_id = $line["faq_category_id"];
	echo "<tr><td align=\"left\"><font size=\"2\">$category_name</font></td></tr>\n";
?>
	<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
	<tr><th scope="col">Position</th><th scope="col">Status</th><th scope="col">Category</th><th scope="col">Question</th><th scope="col">Answer</th><th scope="col">&nbsp;</th></tr>
	
	<?php
	$line_counter = 0;
	$query2 = "SELECT faqs_id, status, position, category, question, answer FROM faqs WHERE status='1' AND category='$faq_category_id' ORDER BY position";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$line_counter++;
		$line_this = $line_counter / 2;
		$answer = $line2["answer"];
		if(strlen($answer) > 110) {
			$answer = substr($answer, 0, 110);
			$answer .= "...";
		}
		
		echo "<FORM Method=\"POST\" ACTION=\"./faqs_admin2_edit.php\" class=\"wmsform\">\n";
		echo "<tr";
		if(is_int($line_this)) { echo " class=\"d\""; }
		echo "><td align=\"center\">";
		echo $line2["position"];
		echo "</td><td>";
		if($line2["status"] == "1") {
			echo "Active";
		} elseif($line["status"] == "0") {
			echo "Review";
		}
		echo "</td><td NOWRAP>";
		echo $category_name;
		echo "</td><td>";
		echo $line2["question"];
		echo "</td><td>";
		echo addslashes($answer);
		echo "</td><input type=\"hidden\" name=\"faqs_id\" value=\"";
		echo $line2["faqs_id"];
		echo "\"><td align=\"center\"><input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\"></td></tr>\n";
		echo "</form>\n";
	}
	mysql_free_result($result2);
	?>
	</table></td></tr>
	<tr><td>&nbsp;</td></tr>
<?php
}
mysql_free_result($result);
?>

</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>