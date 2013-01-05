<?php
// BME WMS
// Page: In The News
// Path/File: /news/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
//$line_hgt = 2700;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>In The News | <?php echo $website_title; ?></title>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent">
	<h2>DreamBoost - In The News</h2>
	<table width="90%" border="0" align="center" cellpadding="18" cellspacing="0">
		<tr>
			<td valign="top"><a href="http://www.smodcast.com/" title="SMODCAST" target="_blank"><img src="<?=$base_url?>images/imgmedia_smodcast.jpg" width="150" height="43" hspace="18" border="0" class="fltlft" /></a>Listen to a recent about Dreamboost<br />
				<object type="application/x-shockwave-flash" data="http://tinycomet.com/audio/player.swf" id="audioplayer1" height="24" width="290">
					<param name="movie" value="http://tinycomet.com/audio/player.swf">
					<param name="FlashVars" value="playerID=audioplayer1&soundFile=http://dreamboost.com/includes/DreamboostFull.mp3">
					<param name="quality" value="high">
					<param name="menu" value="false">
					<param name="wmode" value="transparent">
					</object></td>
		</tr>
		<tr>
			<td valign="top"><hr class="hrGold" />
				<a href="http://www.spins.com/assets/pdf/2009_Trend_Predictions.pdf" target="_blank"><img src="<?=$base_url?>images/imgmedia_spins.jpg" width="300" height="70" hspace="18" border="0" class="fltrt" /></a> Dream Boost has been cited by SPINS,  a market research and consulting firm for the Natural Products Industry, as a  hot trend in vitamins &amp; supplements for 2009.<br />
				<a href="http://www.spins.com/assets/pdf/2009_Trend_Predictions.pdf" target="_blank">Read more &gt;&gt;</a></td>
		</tr>
		<tr>
			<td valign="top"><hr class="hrGold" />
				<p><img src="<?=$base_url?>images/imgmedia_shakerattle.jpg" width="220" height="50" hspace="18" border="0" class="fltlft" />Use code <strong>rattle25</strong> for 25% off your order!</p>
				<p><u><a href="javascript:void(0)">Shake Rattle Showtime listeners click here! </a></u></p></td>
		</tr>
	</table>
</div>

<?php include '../includes/foot1.php'; ?>

</body>
</html>
