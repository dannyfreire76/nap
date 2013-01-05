<?php
// BME WMS
// Page: Newsletter Subscription Thank You page
// Path/File: /newsletters/thanks.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
//$line_hgt = 600;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Newsletter Subscription Thank You</title>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">
	<h2><?php echo $website_title; ?> Newsletter Subscription Thank You</h2>
	<h4>Thank you. You successfully subscribed to the <?php echo $website_title; ?> Website Newsletter.</h4>
	<h4>We'll be e-mailing you information regularly.</h4>
	<h4><a href="./unsubscribe.php">Click here to unsubscribe</a> from our newsletter.</h4>
	<br>
</div>

<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
