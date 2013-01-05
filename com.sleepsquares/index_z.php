<?php
header('Content-type: text/html; charset=utf-8');
include './includes/main1.php';
$new_design = true;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><!-- <?=__FILE__?> -->
<?php include './includes/meta1.php'; ?>
</head>
<body><!-- <?=__FILE__?> -->
<?php include './includes/head_z.php'; ?>
<!-- MAIN CONTENT GOES HERE -->
	<p><img src="images/stock/img_wmnchoc1-2.jpg" width="300" height="200" border="0" class="fltlft" /><strong>Chocolatey Sleep Squares from Slumberland Snacks</strong><br />
		are a delicious, sugar-free, chocolate supplement developed to help you fall asleep fast, maintain sleep throughout the night, and wake up feeling rested and recharged without any grogginess or side effects.</p>

	<p><strong>Sleep Squares contain all natural active ingredients,</strong> including traditional herbs that help relax the body, calm the mind and allow for an easy transition into sleep; as well as vitamins and minerals that optimize sleeping patterns and maintain sleep though out the night.</p>
	<table border="0" cellpadding="9" cellspacing="0">
		<tr>
			<td width="262" valign="top">
				<ul style="padding: 0px 18px ; margin:0px;">
					<li>100% Natural Active Ingredients</li>
					<li>No prescription needed</li>
					<li>No grogginess or side effects</li>
					<li>No addictive ingredients</li>
					<li>Non-habit forming</li>
					<li>Safe for Regular Use</li>
					<li>Manufactured in the USA</li>
				</ul>
			</td>
			<td width="407" valign="top">
				<h3>The synergy of Sleep Squares' active ingredients, working together in low-level doses, is the key to its effectiveness.</h3>
				<p><strong>Free shipping on orders over $40</strong></p>
			</td>
		</tr>
	</table>
<?php include './includes/foot_z.php'; mysql_close($dbh); ?>
</body>
</html>
