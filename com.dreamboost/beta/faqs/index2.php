<?php
// BME WMS
// Page: FAQ Entry page
// Path/File: /faqs/index.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 600;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Frequently Asked Questions and Answers</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css" />
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/images/warning_over.gif','/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT position, category, question, answer FROM faqs WHERE status='1' AND faqs_id='$faqs_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$position = $line["position"];
	$category = $line["category"];
	$question = $line["question"];
	$answer = $line["answer"];
}
?>
<tr><td align="left" class="style4"><?php echo $question; ?></td></tr>

<tr><td align="left" class="style2"><?php echo $answer; ?></td></tr>

<tr><td>&nbsp;</td></tr>
<?php
$prev = $position - 1;
$next = $position + 1;
$query = "SELECT faqs_id FROM faqs WHERE status='1' AND position='$prev' AND category='$category'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev_id = $line["faqs_id"];
}
$query = "SELECT faqs_id FROM faqs WHERE status='1' AND position='$next' AND category='$category'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next_id = $line["faqs_id"];
}

?>
<tr><td align="center" class="style3"><?php if($prev_id != '0' && $prev_id != '') { ?><a href="./index2.php?faqs_id=<?php echo $prev_id; ?>" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('button_previous','','/beta/images/button_previous_over.gif',1);"><img name="button_previous" src="/beta/images/button_previous.gif" width="110" height="27" border="0" alt="Previous"></a><?php } ?> <?php if($next_id != '0' && $next_id != '') { ?><a href="./index2.php?faqs_id=<?php echo $next_id; ?>" onMouseOut="MM_swapImgRestore();" onMouseOver="MM_swapImage('button_next','','/beta/images/button_next_over.gif',1);"><img name="button_next" src="/beta/images/button_next.gif" width="95" height="27" border="0" alt="Next"></a><?php } ?></td></tr>
<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>