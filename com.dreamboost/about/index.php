<?php
// BME WMS
// Page: About Us Homepage
// Path/File: /about/index.php
// Version: 1.8
// Build: 1802
// Date: 01-31-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
//$line_hgt = 700;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>About Dream Boost and The Upstate Dream Institute | <?php echo $website_title; ?></title>

<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">
	<h2>About Us - UDI and Slumberland Snacks</h2>
	<p>The Upstate Dream Institute (UDI), located in scenic Ithaca, NY, was founded in January 2007 and is sanctioned by New York State as an official research institute, specializing in the development, marketing, and distribution of natural herbal dietary supplements. The company's founder and president, Jeff Luciano, has been studying and working with traditional herbs and botanicals for over 15 years and has strong ties with indigenous cultures throughout the world. UDI's initial product, Dream Boost, an all-natural sleep and dream enhancer and first of its kind in the natural products arena, was one of five finalists for best new supplement at the <a href="http://www.expoeast.com/" target="_blank">2007 Natural Products Expo East</a> in Baltimore, MD and a finalist for Best New Product at the <a href="http://expoeast.a2zinc.net/expoeast2008/Public/Content.aspx?ID=3408" target="_blank">2008 Natural Products Expo East</a> in Boston, MA.</p>
    <p>As a member of the Natural Product Association (NPA), UDI strives for excellence and adheres to the highest quality standards set by the cGMPs, SOP, USP, and the FDA. UDI's two manufacturing facilities, one on the east coast in East Farmingdale, NY and one on the west coast in Ontario, California, total more than 10,000 square feet in size and are furnished with state-of-the-art processing equipment and machinery.  Originally run by the 3 principles partners, UDI has grown to over 20 people and employs some of the most skilled and qualified professionals with decades of experience in both research and manufacturing.</p>
<p> With the high percentage of people worldwide suffering from various sleep disorders, UDI is dedicated to providing healthy alternatives to this ever-growing epidemic. In the U.S. alone, over 145 million adults suffer from some form of sleep-related problem on a regular basis, with almost half reporting these problems occurring every night.  For many, prescription sleep medications are too powerful, addictive, and can produce a variety of morning-after side effects. With the success of Dream Boost, UDI is moving forward in the research and development of other supplement products to meet this expanding global demand.</p>
    <p>In addition to providing better sleep naturally, UDI is also a pioneer in the field of dreaming, including lucid dreaming, and the positive benefits that can be achieved through serious dream study. Dreams have always been believed to be sacred/spiritual experiences in cultures around the world and across the centuries. However, the science of how and why we dream is still in its infancy. The Upstate Dream Institute is committed to expanding the understanding of dreaming through ongoing and intensive research and firmly believes that, with better sleep, comes better dreams, which leads ultimately to better living. </p>

	<?php
	/*
	echo "<tr><td align=\"left\" class=\"style3\">Dream Boost Press Releases</td></tr>\n";

	$query2 = "SELECT article_id, category, article_name, headline, description, by_line, body, parent_id FROM article WHERE status='1' AND category='4' ORDER BY position";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$article_id = $line2["article_id"];
		$category = $line2["category"];
		$article_name = $line2["article_name"];
		$headline = $line2["headline"];
		$description = $line2["description"];
		$by_line = $line2["by_line"];
		$body = $line2["body"];
		$parent_id = $line2["parent_id"];
		
		echo "<tr><td align=\"left\" class=\"style2\">";
		echo "<a href=\"/articles/article_id/";
		echo $article_id;
		echo "\">";
		echo $headline . "</a> - $description<br><br></td></tr>\n";
	}
	mysql_free_result($result2);
	*/
	?>
</div>
<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
