<?php
// BME WMS
// Page: Main 3 WMS Include file
// Path/File: /includes/main3.php
// Version: 1.1
// Build: 1114
// Date: 10-28-2006

$query = "SELECT * FROM wms_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$wms_id = $line["wms_id"];
	$website_title = $line["website_title"];
	$license = $line["license"];
	$base_url = $line["base_url"];
	$base_secure_url = $line["base_secure_url"];
	$site_email = $line["site_email"];
	$company_name = $line["company_name"];
	$product_name = $line["product_name"];
	$bgcolor = $line["bgcolor"];
	$font = $line["font"];
	$fontcolor = $line["fontcolor"];
}
mysql_free_result($result);
?>