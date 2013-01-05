<?php
// BME WMS
// Page: LynkStation Homepage
// Path/File: /links/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/lynkstation1.php';
$line_hgt = 950;

$query = "SELECT name, email, links_per_cat, separate_pages, colored_boxes FROM lynkstation_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name = $line["name"];
	$email = $line["email"];
	$links_per_cat = $line["links_per_cat"];
	$separate_pages = $line["separate_pages"];
	$colored_boxes = $line["colored_boxes"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Links</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<?php
if($colored_boxes == "1") {
?>
<tr><td align="center"><img src="/images/links_main_top1.gif" border="0"><br>
<table border="0" bgcolor="#CAA9DE" width="620">
<?php } ?>
<tr><?php if($colored_boxes == "1") { echo "<td>&nbsp;</td>"; } ?><td class="style4"><?php echo $name; ?></td></tr>

<?php
if($separate_pages == 1) {
	//intro paragraph
	echo "<tr>";
	if($colored_boxes == "1") { echo "<td>&nbsp;</td>"; }
	echo "<td align=\"left\" class=\"style2\">Please find below a list of all the categories in this \n";
	echo "LynkStation. By clicking on any link you will go to a page which has all the links about that specific \n";
	echo "topic. We are always interested in more websites for this section. We invite you to \n";
	echo "<a href=\"./links_submit.php\">send us your website's information</a> on this short form so we can \n";
	echo "list it in this section.</td></tr>\n";
	if($colored_boxes == "1") { echo "</table>\n"; }
	if($colored_boxes == "1") { echo "<img src=\"/images/links_main_bot1.gif\" border=\"0\"></td></tr>\n"; }
	echo "<tr><td>&nbsp;</td></tr>\n";
	
	//generate list of links to pages with category specific links
	$row_counter = 1;
	echo "<tr><td align=\"center\">";
	if($colored_boxes == "1") {
		echo "<img src=\"/images/links_box_top1.gif\" border=\"0\"><br>\n";
	}

	echo "<table border=\"0\" cellspacing=\"4\" cellpadding=\"2\"";
	if($colored_boxes == "1") {
		echo " width=\"620\" bgcolor=\"#78D2E6\"";
	}
	
	echo ">\n";
	$query = "SELECT name, position FROM lynkstation_cats WHERE name!='' ORDER BY position";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($row_counter == 1 || $row_counter == 3 || $row_counter == 5 || $row_counter == 7 || $row_counter == 9 || $row_counter == 11 || $row_counter == 13 || $row_counter == 15 || $row_counter == 17 || $row_counter == 19 || $row_counter == 21 || $row_counter == 23 || $row_counter == 25 || $row_counter == 27 || $row_counter == 29 || $row_counter == 31 || $row_counter == 33 || $row_counter == 35 || $row_counter == 37 || $row_counter == 39 || $row_counter == 41 || $row_counter == 43 || $row_counter == 45 || $row_counter == 47 || $row_counter == 49 || $row_counter == 51 || $row_counter == 53 || $row_counter == 55 || $row_counter == 57 || $row_counter == 59 || $row_counter == 61 || $row_counter == 63 || $row_counter == 65 || $row_counter == 67 || $row_counter == 69 || $row_counter == 71 || $row_counter == 73 || $row_counter == 75 || $row_counter == 77 || $row_counter == 79) {
		echo "<tr><td align=\"right\" width=\"48%\" class=\"style3\"><a href=\"./links";
		echo $line["position"];
		echo ".php\">";
		echo $line["name"];
		echo "</a></td><td align=\"left\" width=\"4%\" class=\"style3\">&nbsp; &nbsp;</td>";
		} elseif($row_counter == 2 || $row_counter == 4 || $row_counter == 6 || $row_counter == 8 || $row_counter == 10 || $row_counter == 12 || $row_counter == 14 || $row_counter == 16 || $row_counter == 18 || $row_counter == 20 || $row_counter == 22 || $row_counter == 24 || $row_counter == 26 || $row_counter == 28 || $row_counter == 30 || $row_counter == 32 || $row_counter == 34 || $row_counter == 36 || $row_counter == 38 || $row_counter == 40 || $row_counter == 42 || $row_counter == 44 || $row_counter == 46 || $row_counter == 48 || $row_counter == 50 || $row_counter == 52 || $row_counter == 54 || $row_counter == 56 || $row_counter == 58 || $row_counter == 60 || $row_counter == 62 || $row_counter == 64 || $row_counter == 66 || $row_counter == 68 || $row_counter == 70 || $row_counter == 72 || $row_counter == 74 || $row_counter == 76 || $row_counter == 78 || $row_counter == 80) {
		echo "<td align=\"left\" width=\"48%\" class=\"style3\"><a href=\"./links";
		echo $line["position"];
		echo ".php\">";
		echo $line["name"];
		echo "</a></td></tr>\n";
		}		
		
		$row_counter = $row_counter + 1;
	}
	mysql_free_result($result);
	echo "</table>\n";
	if($colored_boxes == "1") {
		echo "<img src=\"/images/links_box_bot1.gif\" border=\"0\">";
	}
	echo "</td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
	
} else {
?>

<tr><?php if($colored_boxes == "1") { echo "<td>&nbsp;</td>"; } ?><td align="left" class="style2">Please find below links to other websites. These are related websites with related information you may find useful. We are always interested in more websites for this page. We invite you to <a href="./links_submit.php">send us your website's information</a> on this short form so we can list it on this page.</td></tr>
<?php
if($colored_boxes == "1") {
?>
</table>
<img src="/images/links_main_bot1.gif" border="0"></td></tr>
<?php } ?>

<tr><td>&nbsp;</td></tr>

<?php 
build_category('1',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('2',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('3',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('4',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('5',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('6',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('7',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('8',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('9',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('10',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('11',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('12',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('13',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('14',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('15',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('16',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('17',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('18',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('19',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('20',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('21',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('22',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('23',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('24',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('25',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('26',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('27',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('28',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('29',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('30',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('31',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('32',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('33',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('34',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('35',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('36',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('37',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('38',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('39',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('40',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('41',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('42',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('43',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('44',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('45',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('46',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('47',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('48',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('49',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('50',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('51',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('52',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('53',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('54',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('55',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('56',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('57',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('58',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('59',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('60',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('61',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('62',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('63',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('64',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('65',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('66',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('67',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('68',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('69',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('70',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('71',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('72',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('73',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('74',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('75',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('76',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('77',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('78',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('79',$colored_boxes,$links_per_cat, $font, $fontcolor);
build_category('80',$colored_boxes,$links_per_cat, $font, $fontcolor);

}
?>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>