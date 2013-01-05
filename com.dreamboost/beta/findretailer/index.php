<?php 
//BME WMS 
//Page: Find a Retailer page
// Path/File: /findretailer/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 600;

include '../includes/retailer1.php';

$query="SELECT product_line, page_display FROM retailer_main";
$result=mysql_query($query) or die("Query failed : " . mysql_error());
while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) { 
	$product_line=$line["product_line"]; 
	$page_display=$line["page_display"]; 
}
mysql_free_result($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Find a Retailer</title>
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

<tr><td align="left" class="style4">Find a Retailer</td></tr>

<tr><td align="left" class="style2">Retailers selling <?php echo $product_line; ?> around the world - so you can find a Retailer near you. We are currently upgrading this section of our website. Check back for updates coming soon.</td></tr>

<tr><td>&nbsp;</td></tr>

<?php 
if($page_display == "1") { 
echo "<tr><td align=\"left\"><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' ORDER BY store_name_website ASC";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
echo "</table></td></tr>\n";
} elseif($page_display == "2") {
/*
?>
<tr><td align="left" class="style2">Click on the area of the country for which you would like to see a list of retailers.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center"><img src="/images/retailer_map1.jpg" usemap="#retailer_map1" style="border-style:none"></td></tr>

<?php
*/
} 
/*
if($zone == "1") { 
echo "<tr><td align=\"left\"><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='AK' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td align=\"left\" class=\"style3\">ALASKA</td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='AZ' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>ARIZONA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='CA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>CALIFORNIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='CO' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>COLORADO</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='HI' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>HAWAII</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='ID' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>IDAHO</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MT' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MONTANA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NV' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEVADA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NM' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEW MEXICO</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='OR' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>OREGON</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='UT' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>UTAH</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='WA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>WASHINGTON</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='WY' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>WYOMING</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
echo "</table></td></tr>\n";
} elseif($zone == "2") {
echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='AR' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>ARKANSAS</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='IL' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>ILLINOIS</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='IA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>IOWA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='KS' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>KANSAS</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='LA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>LOUISIANA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MN' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MINNESOTA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MO' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MISSOURI</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NE' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEBRASKA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='ND' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NORTH DAKOTA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='OK' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>OKLAHOMA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='SD' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>SOUTH DAKOTA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='TX' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>TEXAS</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='WI' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>WISCONSIN</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
echo "</table></td></tr>\n";
} elseif($zone == "3") {
echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='CT' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>CONNECTICUT</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='IN' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>INDIANA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='KY' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>KENTUCKY</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='ME' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MAINE</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MASSACHUSETTS</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MI' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MICHIGAN</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NH' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEW HAMPSHIRE</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NJ' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEW JERSEY</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NY' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NEW YORK</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='OH' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>OHIO</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='PA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>PENNSYLVANIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='RI' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>RHODE ISLAND</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='VT' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>VERMONT</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='WV' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>WEST VIRGINIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
echo "</table></td></tr>\n";
} elseif($zone == "4") {
echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">\n";
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='AL' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>ALABAMA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='DE' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>DELAWARE</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='DC' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>DISTRICT OF COLUMBIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='FL' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>FLORIDA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='GA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>GEORGIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MD' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MARYLAND</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='MS' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>MISSISSIPPI</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='NC' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>NORTH CAROLINA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='SC' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>SOUTH CAROLINA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='TN' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>TENNESSEE</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
$query = "SELECT store_name_website, address1, address2, city, state, zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold FROM retailer WHERE retailer_status='1' AND list_store_website='1' AND state='VA' ORDER BY store_name_website ASC";
$title_run1 = 0;
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["store_name_website"] != "" && $title_run1 == 0) { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>VIRGINIA</b></td></tr>\n";
		$title_run1 = $title_run1 + 1;
	}
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	buildStore($line, $font, $fontcolor);
}
mysql_free_result($result);
echo "</table></td></tr>\n";
}
*/
?> 

<tr><td>&nbsp;</td></tr>
</table>
<div>
<map id="retailer_map1" name="retailer_map1">
<area shape="poly" alt="" coords="2,4,227,7,237,64,232,141,246,142,246,181,237,181,236,230,196,232,195,237,168,235,144,220,144,216,125,215,65,210,4,159" href="index.php?zone=1" title="" />
<area shape="poly" alt="" coords="237,64,277,64,289,60,293,56,296,59,298,66,305,65,316,69,327,70,324,75,314,82,320,85,324,88,332,91,340,96,344,97,349,158,345,175,337,176,340,180,338,189,334,195,329,209,331,225,328,235,329,236,342,235,343,245,347,246,351,257,303,257,288,290,264,282,230,260,206,232,236,230,237,181,247,181,246,143,232,141" href="index.php?zone=2" title="" />
<area shape="poly" alt="" coords="324,82,332,76,341,80,354,77,365,85,387,121,420,73,460,35,475,60,457,90,466,99,444,124,442,135,438,139,433,129,407,137,409,138,417,137,411,147,405,150,406,158,397,164,390,165,384,172,340,182,338,175,346,175,349,151,344,98" href="index.php?zone=3" title="" />
<area shape="poly" alt="" coords="441,140,441,140,474,235,420,292,391,248,353,255,345,245,342,236,329,235,331,224,328,207,340,182,384,172,391,165,406,159,405,149,411,147,417,137,408,138,432,128" href="index.php?zone=4" title="" />
<area shape="default" nohref="nohref" alt="" />
</map>
<?php 
include '../includes/foot1.php'; 
mysql_close($dbh); 
?>