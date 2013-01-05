<?php
// BME WMS
// Page: DreamBoost Articles Main Page
// Path/File: /articles/index.php
// Version: 1.8
// Build: 1803
// Date: 01-31-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
//$line_hgt = 2600;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Dream Boost Articles | <?php echo $website_title; ?></title>

<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>


<table border="0">

<tr><td align="left"><h2>Dream Boost Articles</h2></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT article_category_id, category_name FROM article_categories WHERE status='1' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category_name = $line["category_name"];
	$article_category_id = $line["article_category_id"];
	echo "<tr><td align=\"left\" class=\"categoryName\"><strong>$category_name</strong></td></tr>\n";
	echo "<tr><td>&#160;</td></tr>\n";
	?>
	<?php
	$query2 = "SELECT article_id, category, article_name, headline, description, by_line, body, parent_id FROM article WHERE status='1' AND category='$article_category_id' ORDER BY position";
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
		
		echo "<tr><td align=\"left\" class=\"article\" style=\"padding-left:18px;\">";
		echo "<b><a href=\"/articles/article_id/";
		echo $article_id;
		echo "\">";
		echo $headline . "</a></b> - $description<br><br></td></tr>\n";
	}
	mysql_free_result($result2);
	?>
<?php
}
mysql_free_result($result);
?>
</table>

<br clear="all">
<?php include '../includes/foot1.php'; ?>
</body>
</html>
