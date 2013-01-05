<?php
// BME WMS
// Page: Testimonial Thank You page
// Path/File: /testimonials/thanks.php
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
<title><?php echo $website_title; ?>: Testimonial Submit Thanks</title>

<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">
	<h3>Thank you for submiting your Testimonial to <?php echo $website_title; ?>.</h3>
	<h4>Your testimonial is very important to us and we are grateful to you for sharing it with us. </h4>
	<p>We will be reviewing it shortly and hopefully sharing it with the world on our website. </p>
	<p>You will receive an email either way from our editors letting you know if we post it. </p>
	<p>You can always visit our <a href="/testimonials/index.php">testimonials page</a> to see what people are saying about Dream Boost.</p>
	<br>
</div>

<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
