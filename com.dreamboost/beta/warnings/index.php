<?php
// BME WMS
// Page: Warnings and Disclaimer page
// Path/File: /warnings/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 1050;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Warnings and Disclaimer</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Warnings & Disclaimer</td></tr>

<tr><td align="left">
<table border="0">
<tr><td align="left" class="style2">Dream Boost is a brain support supplement specifically designed to promote lucid dreaming and enhance dream recall. Its patent pending formula helps relaxes the body while increasing the brain's activity during REM sleep.<br>
<br>
<b><u>Warning</u>, This Product:</b><br>
- is not intended for use by persons under the age of 18.<br>
- is not for use by pregnant or nursing women.<br>
- may increase the effects of sedative medications.<br>
- may counteract the effectiveness of blood-thinning medications.<br>
- may cause drowsiness.<br>
- is not recommended for use with MAO-inhibiting antidepressant medications.<br>
<br>
Do not exceed recommended dose. Excessive intake may cause adverse reactions. Never attempt to operate any form of heavy machinery or moving vehicle while using this product.<br>
<br>
Consult your doctor before use if you have, or have had, any health condition or if you are taking 
any medications or remedies including OTC medications, or are planning any medical procedure. Discontinue use or consult your doctor if any adverse reactions occur, such as gastrointestinal discomfort, headache, dizziness, heart palpitations, anxiety, dry mouth, insomnia, drowsiness, skin flushing, or changes in blood pressure.<br>
<br>
The statements on this website have not been evaluated by the Food and Drug Administration. This 
product is not intended to diagnose, treat, cure, or prevent any disease.<br>
<br>
Keep out of reach of children. Store in a dry place and avoid excessive heat. Do not use if tamper resistant seal is broken. Carefully Manufactured for The Upstate Dream Institute.</font>
</td><td><IMG height="283" alt="woman" src="/beta/images/woman.jpg" width="212" border="0"></td></tr>
</table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>