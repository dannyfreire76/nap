<?php
// BME WMS
// Page: Testimonial Manager Manage Submitted Testimonials page
// Path/File: /admin/testimonials_admin2.php
// Version: 1.8
// Build: 1805
// Date: 05-14-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/pagination1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$page_this = $_GET["page_this"];

$limit = 10;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "testimonials";
$page = "Testimonials Manager > Manage Submitted Testimonials";
$url = "testimonials_admin2.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);

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
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">These are the testimonials recently submitted on the site, you can edit and then approve or reject them below. An e-mail will be sent to the user as you complete each one. This is not all the information submitted by the user - this is the information used for display on the website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Created</th><th scope="col">Name</th><th scope="col">Displayed Testimonial</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM testimonials WHERE status='0'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT testimonial_id, created, name, email, address1, address2, city, state, zip, country, phone, fax, testimonial, product FROM testimonials WHERE status='0' ORDER BY created LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	list($created_date, $created_time) = split(' ', $line["created"]);
	list($created_yr, $created_mn, $created_dy) = split('-', $created_date);
	echo "<form name=\"testimonials-manage\" Method=\"POST\" action=\"./testimonials_admin2_edit.php\" class=\"wmsform\">";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo ">";
	echo "<td>";
	echo $created_mn . "/" . $created_dy . "/" . $created_yr . " " . $created_time;
	echo "</td><td>";
	echo $line["name"];
	echo "</td><td>";
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
		if($product_displayed && $product_display_pos == $i) {
			echo " - ";

			$this_prod_name = '';
			$queryProds = "SELECT * FROM products WHERE prod_id='".$line["product"]."'";
			$resultProds = mysql_query($queryProds) or die("Query failed : " . mysql_error());
			while ($lineProds = mysql_fetch_array($resultProds, MYSQL_ASSOC)) {
				$this_prod_name = $lineProds["name"];
			}

			if($line["product"] == "0") {
				echo 'All '.$product_name;
			} else {
				echo $product_name.' '.$this_prod_name;
			}

		}
	}
	echo "</td><input type=\"hidden\" name=\"testimonial_id\" value=\"";
	echo $line["testimonial_id"];
	echo "\"><td align=\"center\"><input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\"></td></tr>\n";
	echo "</form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

<?php
pagination_display($url, $page_this, $limit, $record_count);
?>
<tr><td>&nbsp;</td></tr>
</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>