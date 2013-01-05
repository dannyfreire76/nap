<?php
// BME WMS
// Page: FAQ Homepage
// Path/File: /faqs/index.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 1950;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sleep, Dreaming, Health, and Ordering Frequently Asked Questions and Answers | <?php echo $website_title; ?></title>
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent">
	<h3><img src="<?=$base_url?>images/stock/img_sleepwoman1-3.jpg" width="270" height="154" border="0" class="fltrt" /><strong>Better Sleep and Dreams are Just the Start</strong></h3>
	<p>Dream Boost is an all-natural dietary supplement developed by the Upstate Dream Institute as a sleep and dream enhancer. Dream Boost's patent-pending formula optimizes sleep patterns for restful and productive sleep while increasing dreaming ability, vividness, and recall. The synergy of Dream Boost's active ingredients, working together in low level doses, is the key to its effectiveness, thus making the product gentle and safe for regular use. Benefits to a quality night's sleep with Dream Boost include reduced stress, enhanced creativity, improved concentration, and a restful feeling with a positive outlook. Dream Boost contains no addictive ingredients and is non-habit forming.</p>
	<h3><strong>Sleep and Dream Enhancer</strong></h3>
	<p>Healthy and productive sleep rejuvenates the body and mind for the following day; Unhealthy and unproductive sleep does not. The human body and mind require a certain amount of undisturbed time in which to recharge. If this is not achieved, the human body will not function properly or effectively. Dream Boost works together with a person's regular sleep cycle to help &quot;tune up&quot; their sleeping patterns for a more restful and beneficial sleep.</p>
	<p>When achieving quality sleep, our normal biological function is to dream. During these times, our mind is allowed to process all of the days activities and &quot;file&quot; them away for future use. Unproductive sleep disrupts dreaming ability thus preventing this function. Dream Boost enhances dreaming ability, producing more colorful and vivid dreams with better dream recall the following day. Dream Boost may also help to promote lucid dreaming. Lucid dreaming is the ability to consciously realize that one is dreaming while sleeping. Once this ability is developed, the dreamer can control the events of the dream, for fun, for learning, for healing, and even to put an end to nightmares, fears, and problems.</p>
</div>

<table border="0">

<tr><td align="left"><h2>Frequently Asked Questions and Answers</h2></td></tr>

<?php
$query = "SELECT faq_category_id, category_name FROM faqs_categories WHERE status='1' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category_name = $line["category_name"];
	$faq_category_id = $line["faq_category_id"];
	echo "<tr><td align=\"left\" class=\"categoryName\"><strong>$category_name</strong></td></tr>\n";
	?>
	<?php
	$query2 = "SELECT faqs_id, question FROM faqs WHERE status='1' AND category='$faq_category_id' ORDER BY position";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" class=\"article\" style=\"padding-left:18px;\"><a href=\"./index2.php?faqs_id=";
		echo $line2["faqs_id"];
		echo "\">";
		echo $line2["question"];
		echo "</a></td></tr>\n";
	}
	mysql_free_result($result2);
	?>
	<tr><td>&nbsp;</td></tr>
<?php
}
mysql_free_result($result);
?>

</table>

<br clear="all">
<?php include '../includes/foot1.php'; ?>
</body>
</html>
