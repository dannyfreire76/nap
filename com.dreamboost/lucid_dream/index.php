<?php
// BME WMS
// Page: Lucid Dreaming page
// Path/File: /lucid_dream/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>An Initiation into the World of Lucid Dreaming | <?php echo $website_title; ?></title>

<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent">
<h3><em>The Upstate Dream Institute presents</em></h3>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td width="200" valign="top"><a href="<?=$base_url?>store/product.php?prod_id=4"><img src="<?=$base_url?>images/BookCover.jpg" alt="Lucid Dreaming Book" width="190" height="262" vspace="18" border="0" class="fltlft"></a></td>
		<td valign="top">
		<div>
			<h2>An Initiation into the World of Lucid Dreaming</h2>
			<h3>by Darien Simon, M.S.</h3>
			<h4 align="center">"Our truest life is when we are in dreams awake."<br>
				<i>~ Henry David Thoreau</i></h4>
			<hr size="3" class="hrGold" style="margin:18px 9px;">
			<p><strong><em>Read excerpts from &quot;An Initiation into the World of Lucid Dreaming&quot; below</em></strong></p>
			<h4><a href="<?=$base_url?>lucid_dream/introduction.php">Introduction</a></h4>
			<h4><a href="<?=$base_url?>lucid_dream/normal_sleep.php">Normal Sleep and Dreaming Processes</a></h4>
			<h4><a href="<?=$base_url?>lucid_dream/lucid_dreaming.php">Lucid Dreaming</a></h4>
			<h4><a href="<?=$base_url?>store/product.php?prod_id=4">Purchase this book in our Online Store!</a></h4>
			<h4><a href="<?=$base_url?>store/index.php">Purchase our Dream Boost pills in our Online Store!</a></h4>
		</div>
		</td>
	</tr>
</table>

</div>
<br clear="all">
<?php include '../includes/foot1.php'; ?>
</body>
</html>
