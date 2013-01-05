<?php
// BME WMS
// Page: Become Retailer Thank You page
// Path/File: /beretailer/thanks.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 600;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Retailer Contact Us Thanks</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css">
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td class="style4">Thank you for Contacting Us</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style2">Your interest in becoming a Dream Boost Retailer is very important to us. We will be responding to you shortly.</td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>