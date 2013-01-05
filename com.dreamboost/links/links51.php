<?php
// BME WMS
// Page: LynkStation Link Category page
// Path/File: /links/links51.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$links_page = 51;

$query = "SELECT name, email, links_per_cat, colored_boxes FROM lynkstation_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$ls_name = $line["name"];
	$email = $line["email"];
	$links_per_cat = $line["links_per_cat"];
	$colored_boxes = $line["colored_boxes"];	
}
mysql_free_result($result);

$query = "SELECT name FROM lynkstation_cats WHERE position='$links_page' AND name!=''";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name = $line["name"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $name; ?> Links | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css">
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/images/warning_over.gif','/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<?php
if($colored_boxes == "1") {
?>
<tr><td align="center"><img src="/images/links_main_top1.gif" border="0"><br>
<table border="0" bgcolor="#CAA9DE" width="620">
<?php } ?>
<tr><?php if($colored_boxes == "1") { echo "<td>&nbsp;</td>"; } ?><td align="left" class="style4"><?php echo $ls_name; ?></td></tr>

<tr><?php if($colored_boxes == "1") { echo "<td>&nbsp;</td>"; } ?><td align="left" class="style2">Please find below links to other websites. These are related websites with related information you may find useful. This is one category of links in our LynkStation. To see all the categories please visit the <a href="./index.php">main LynkStation page</a>. We are always interested in more websites for this page. We invite you to <a href="./links_submit.php">send us your website's information</a> on this short form so we can list it on this page.</td></tr>
<?php
if($colored_boxes == "1") {
?>
</table>
<img src="/images/links_main_bot1.gif" border="0"></td></tr>
<?php } ?>

<tr><td>&nbsp;</td></tr>

<?php
	if($colored_boxes == "1") {
		echo "<tr><td align=\"center\"><img src=\"/images/links_box_top1.gif\" border=\"0\"><br>\n";
		echo "<table border=\"0\" width=\"620\" bgcolor=\"#78D2E6\">\n";
	}
	echo "<tr><td align=\"left\" class=\"style3\">";
	if($colored_boxes == "1") {
		echo "&nbsp;</td><td colspan=\"3\">";
	}
	echo $name . "</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style2\">";
	if($colored_boxes == "1") {
		echo "&nbsp;</td><td NOWRAP>&nbsp; &nbsp; &nbsp; &nbsp;</td><td>";
	} else {
		echo "<ul>";
	}
	$query2 = "SELECT title, website_url, description, email, image_url FROM lynkstation_links WHERE approved='1' AND category='$name' ORDER BY modified DESC LIMIT $links_per_cat";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		echo "<li><a href=\"";
		echo $line2["website_url"];
		echo "\" target=\"_BLANK\">";
		echo $line2["title"];
		echo "</a> - ";
		echo $line2["description"];
		echo "</li>\n";
	}
	mysql_free_result($result2);
	if($colored_boxes == "1") {
		echo "</td><td>&nbsp;";
	} else {
		echo "</ul>";
	}
	echo "</td></tr>\n";
	if($colored_boxes == "1") {
		echo "</table>\n";
		echo "<img src=\"/images/links_box_bot1.gif\" border=\"0\"></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
	}
?>

<tr><td>&nbsp;</td></tr>
<tr><td align="left" class="style2">Back to the <a href="./index.php">main LynkStation page</a></td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>