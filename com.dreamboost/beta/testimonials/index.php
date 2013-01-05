<?php
// BME WMS
// Page: Testimonials Homepage
// Path/File: /testimonials/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 1800;

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='name'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name_displayed = $line["displayed"];
	$name_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='email'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$email_displayed = $line["displayed"];
	$email_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='address1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$address1_displayed = $line["displayed"];
	$address1_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='address2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$address2_displayed = $line["displayed"];
	$address2_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='city'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$city_displayed = $line["displayed"];
	$city_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='state'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$state_displayed = $line["displayed"];
	$state_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='zip'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$zip_displayed = $line["displayed"];
	$zip_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='country'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$country_displayed = $line["displayed"];
	$country_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='phone'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$phone_displayed = $line["displayed"];
	$phone_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='fax'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$fax_displayed = $line["displayed"];
	$fax_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='testimonial'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$testimonial_displayed = $line["displayed"];
	$testimonial_display_pos = $line["display_pos"];
}
mysql_free_result($result);

$query = "SELECT displayed, display_pos FROM testimonial_fields WHERE int_name='product'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_displayed = $line["displayed"];
	$product_display_pos = $line["display_pos"];
}
mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Testimonials</title>
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

<tr><td align="left" class="style4">Testimonials</td></tr>
<tr><td align="left" class="style2">Please find below stories from other people people just like you that have tried DreamBoost. We also invite you to <a href="./testimonial_submit.php">send us your testimonials</a> after trying DreamBoost so we can post them here to share with the world.</td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT name, email, address1, address2, city, state, zip, country, phone, fax, testimonial, product FROM testimonials WHERE status='1' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$counter = 1;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><td align=\"center\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	if($counter == 1 || $counter == 5 || $counter == 9 || $counter == 13 || $counter == 17 || $counter == 21 || $counter == 25 || $counter == 29 || $counter == 33 || $counter == 37 || $counter == 41 || $counter == 45) {
		echo "<tr><td><img src=\"/images/thoughts_box_top3.gif\" border=\"0\"></td></tr>\n";
		echo "<tr><td width=\"620\" bgcolor=\"#FDC689\"><table border=\"0\">\n";
	}
	if($counter == 2 || $counter == 6 || $counter == 10 || $counter == 14 || $counter == 18 || $counter == 22 || $counter == 26 || $counter == 30 || $counter == 34 || $counter == 38 || $counter == 42 || $counter == 46) {
		echo "<tr><td><img src=\"/images/thoughts_box_top4.gif\" border=\"0\"></td></tr>\n";
		echo "<tr><td width=\"620\" bgcolor=\"#FEB3BE\"><table border=\"0\">\n";
	}
	if($counter == 3 || $counter == 7 || $counter == 11 || $counter == 15 || $counter == 19 || $counter == 23 || $counter == 27 || $counter == 31 || $counter == 35 || $counter == 39 || $counter == 43 || $counter == 47) {
		echo "<tr><td><img src=\"/images/thoughts_box_top1.gif\" border=\"0\"></td></tr>\n";
		echo "<tr><td width=\"620\" bgcolor=\"#D37D7D\"><table border=\"0\">\n";
	}
	if($counter == 4 || $counter == 8 || $counter == 12 || $counter == 16 || $counter == 20 || $counter == 24 || $counter == 28 || $counter == 32 || $counter == 36 || $counter == 40 || $counter == 44 || $counter == 48) {
		echo "<tr><td><img src=\"/images/thoughts_box_top2.gif\" border=\"0\"></td></tr>\n";
		echo "<tr><td width=\"620\" bgcolor=\"#C4DF9B\"><table border=\"0\">\n";
	}
	echo "<tr><td>&nbsp;</td><td align=\"left\" class=\"style2\">";
	for($i = 1; $i <= 12; $i++) {
		if($testimonial_displayed == "1" && $testimonial_display_pos == $i && $line["testimonial"] != "") {
			echo "\"";
			echo $line["testimonial"];
			echo "\" -";
		}
		if($name_displayed == "1" && $name_display_pos == $i && $line["name"] != "") {
			echo " ";
			echo $line["name"];
			echo ",";
		}
		if($email_displayed == "1" && $email_display_pos == $i && $line["email"] != "") {
			echo " ";
			echo $line["email"];
			echo ",";
		}
		if($address1_displayed == "1" && $address1_display_pos == $i && $line["address1"] != "") {
			echo " ";
			echo $line["address1"];
		}
		if($address2_displayed == "1" && $address2_display_pos == $i && $line["address2"] != "") {
			echo " ";
			echo $line["address2"];
		}
		if($city_displayed == "1" && $city_display_pos == $i && $line["city"] != "") {
			echo " ";
			echo $line["city"];
			echo ",";
		}
		if($state_displayed && $state_display_pos == $i && $line["state"] != "") {
			echo " ";
			echo $line["state"];
		}
		if($zip_displayed && $zip_display_pos == $i && $line["zip"] != "") {
			echo " ";
			echo $line["zip"];
		}
		if($country_displayed && $country_display_pos == $i && $line["country"] != "") {
			echo " ";
			echo $line["country"];
		}
		if($phone_displayed && $phone_display_pos == $i && $line["phone"] != "") {
			echo " ";
			echo $line["phone"];
		}
		if($fax_displayed && $fax_display_pos == $i && $line["fax"] != "") {
			echo " ";
			echo $line["fax"];
		}
		if($product_displayed && $product_display_pos == $i && $line["product"] != "") {
			echo " - ";
			if($line["product"] == "1") {
				echo "SalviaZone Green";
			} elseif($line["product"] == "2") {
				echo "SalviaZone Yellow";
			} elseif($line["product"] == "3") {
				echo "SalviaZone Red";
			} elseif($line["product"] == "4") {
				echo "SalviaZone Purple";
			} elseif($line["product"] == "5") {
				echo "All SalviaZone";
			}
		}
	}
	echo "</td><td>&nbsp;</td></tr></table></td></tr>\n";
	if($counter == 1 || $counter == 5 || $counter == 9 || $counter == 13 || $counter == 17 || $counter == 21 || $counter == 25 || $counter == 29 || $counter == 33 || $counter == 37 || $counter == 41 || $counter == 45) {
		echo "<tr><td><img src=\"/images/thoughts_box_bot3.gif\" border=\"0\"></td></tr>\n";
	}
	if($counter == 2 || $counter == 6 || $counter == 10 || $counter == 14 || $counter == 18 || $counter == 22 || $counter == 26 || $counter == 30 || $counter == 34 || $counter == 38 || $counter == 42 || $counter == 46) {
		echo "<tr><td><img src=\"/images/thoughts_box_bot4.gif\" border=\"0\"></td></tr>\n";
	}
	if($counter == 3 || $counter == 7 || $counter == 11 || $counter == 15 || $counter == 19 || $counter == 23 || $counter == 27 || $counter == 31 || $counter == 35 || $counter == 39 || $counter == 43 || $counter == 47) {
		echo "<tr><td><img src=\"/images/thoughts_box_bot1.gif\" border=\"0\"></td></tr>\n";
	}
	if($counter == 4 || $counter == 8 || $counter == 12 || $counter == 16 || $counter == 20 || $counter == 24 || $counter == 28 || $counter == 32 || $counter == 36 || $counter == 40 || $counter == 44 || $counter == 48) {
		echo "<tr><td><img src=\"/images/thoughts_box_bot2.gif\" border=\"0\"></td></tr>\n";
	}
	echo "</table></td></tr>\n";
	$counter = $counter + 1;
}
mysql_free_result($result);
?>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>