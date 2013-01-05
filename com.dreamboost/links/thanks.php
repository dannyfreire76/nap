<?php
// BME WMS
// Page: LynkStation Link Submit Thank You page
// Path/File: /links/thanks.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Website Link Suggestion Confirmation | <?php echo $website_title; ?></title>
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

<tr><td align="left" class="style4">Thank You for submiting your Link</td></tr>

<tr><td align="left" class="style2">Your website submission is very important to us and we are grateful to you for sending it to us. We will be reviewing it shortly and hopefully sharing it with the world on our website. You will receive an email either way from our editors letting you know if we post it. You can always visit our <a href="./index.php">links page</a> to see related websites that have been posted.</td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>