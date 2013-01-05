<?php
// BME WMS
// Page: Newsletter Unsubscribe Thank You page
// Path/File: /newsletters/unsub_thanks.php
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
<title><?php echo $website_title; ?>: Newsletter Unsubscribe Thank You</title>
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">

	<h2><?php echo $website_title; ?> E-Mail Newsletter Unsubscribe Confirmation</h2>
	<h4>Thank you. </h4>
	<p>You successfully unsubscribed from the <?php echo $website_title; ?> E-Mail Newsletter. </p>
	<p>You will no longer receive the e-mail newsletter. If you accidently unsubscribed or you change your mind in the future you can always subscribe again.</p>
	<br>
</div>

<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
