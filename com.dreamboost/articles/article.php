<?php
// BME WMS
// Page: DreamBoost Article Page
// Path/File: /articles/article.php
// Version: 1.8
// Build: 1803
// Date: 01-31-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$article_id = $_GET['article_id'];

$query = "SELECT category, article_name, headline, subheadline, description, by_line, body, parent_id, line_hgt, image, image_alt_text, image_width, image_height, image_align, image_thumbnail, image_thumbnail_width, image_thumbnail_height FROM article WHERE article_id='$article_id' AND status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$category = $line["category"];
	$article_name = $line["article_name"];
	$headline = $line["headline"];
	$subheadline = $line["subheadline"];
	$description = $line["description"];
	$by_line = $line["by_line"];
	$body = $line["body"];
	$parent_id = $line["parent_id"];
	$line_hgt = $line["line_hgt"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
	$image_width = $line["image_width"];
	$image_height = $line["image_height"];
	$image_align = $line["image_align"];
	$image_thumbnail = $line["image_thumbnail"];
	$image_thumbnail_width = $line["image_thumbnail_width"];
	$image_thumbnail_height = $line["image_thumbnail_height"];
}
mysql_free_result($result);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $headline; ?> | <?php echo $website_title; ?></title>

<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<table border="0" class="boxContent">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="categoryName"><?php echo $headline; ?></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
if($by_line != "") {
?>
<tr><td align="left" class="style3">By <?php echo $by_line; ?></td></tr>
<tr><td>&nbsp;</td></tr>
<?php
}
?>

<?php
if($subheadline != "") {
?>
<tr><td align="left" class="style3"><?php echo $subheadline; ?></td></tr>
<tr><td>&nbsp;</td></tr>
<?php
}
?>
<tr><td align="left" class="style2">
<?php
if($image != "") {
	echo "<img src=\"$image\" ";
	if($image_width != 0) {
		echo "width=\"$image_width\" ";
	}
	if($image_height != 0) {
		echo "height=\"$image_height\" ";
	}
	echo "border=\"0\" ";
	if($image_alt_text != "") {
		echo "alt=\"$image_alt_text\" ";
	}
	if($image_align == 1) {
		echo "class=\"article-left\">";
	} elseif($image_align == 2) {
		echo "class=\"article-right\">";
	}
}
echo $body;
?>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>