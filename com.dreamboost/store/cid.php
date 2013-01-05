<?php
// BME WMS
// Page: CID page
// Path/File: /store/cid.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: What is a Security Code?</title>
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

<table border="0">
<tr><td align="left" class="style4">What is a Security Code?</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="left" class="style2">The Security Code is a three (3) or four (4) digit number listed on the back of your credit card immediately following your card number. (On American Express cards, the security code may be on the front.)<br>
<br>
This number prevents fraudulent charges to your credit card, such as someone stealing your credit card receipt and using that information to make a purchase.<br>
<br>
Note: Some older cards may not have a Security Code. In these cases, simply leave the Security Code field blank.<br>
<br>
<a href="javascript:window.close()">Close</a></td></tr>
</table>
<?php
mysql_close($dbh);
?>
</body>
</html>