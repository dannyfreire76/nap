<?php
// BME WMS
// Page: DreamBoost Article Page
// Path/File: /store/articles/sex-dream.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../../includes/main1.php';
$article_id = 13;

$query = "SELECT category, article_name, headline, subheadline, description, by_line, body, parent_id FROM article WHERE article_id='$article_id' AND status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category = $line["category"];
	$article_name = $line["article_name"];
	$headline = $line["headline"];
	$subheadline = $line["subheadline"];
	$description = $line["description"];
	$by_line = $line["by_line"];
	$body = $line["body"];
	$parent_id = $line["parent_id"];
}
mysql_free_result($result);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: <?php echo $headline; ?></title>
<?php
include '../../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" topmargin="0" bottommargin="0" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4"><?php echo $headline; ?></td></tr>

<tr><td align="left" class="style3"><?php echo $subheadline; ?></td></tr>

<<<<<<< .mine
<tr><td align="left" class="style2">
<?php
echo $body;
?>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../../includes/foot1.php';
mysql_close($dbh);
=======
$article_id = 13;

$query = "SELECT category, article_name, headline, subheadline, description, by_line, body, parent_id FROM article WHERE article_id='$article_id' AND status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category = $line["category"];
	$article_name = $line["article_name"];
	$headline = $line["headline"];
	$subheadline = $line["subheadline"];
	$description = $line["description"];
	$by_line = $line["by_line"];
	$body = $line["body"];
	$parent_id = $line["parent_id"];
}
mysql_free_result($result);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: <?php echo $headline; ?></title>
<?php
include '../../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css">
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" topmargin="0" bottommargin="0" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4"><?php echo $headline; ?></td></tr>

<tr><td align="left" class="style3"><?php echo $subheadline; ?></td></tr>

<tr><td align="left" class="style2">
<?php
echo $body;
?>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../../includes/foot1.php';
mysql_close($dbh);
>>>>>>> .r236
?>