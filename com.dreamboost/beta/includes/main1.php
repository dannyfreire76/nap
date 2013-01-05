<?php
// BME WMS
// Page: Main WMS Include file
// Path/File: /includes/main1.php
// Version: 1.8
// Build: 1117
// Date: 01-15-2007

$dbh=mysql_connect("localhost", "dreamboo_dreambo", "mazatec") or die ('Could not connect to the database: ' . mysql_error());
mysql_select_db("dreamboo_store") or die("Could not select database");

$version="1.8";

$query = "SELECT wms_id, website_title, license, base_url, base_secure_url, wms_url, site_email, bgcolor, font, fontcolor FROM wms_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$wms_id = $line["wms_id"];
	$website_title = $line["website_title"];
	$license = $line["license"];
	$base_url = $line["base_url"];
	$base_secure_url = $line["base_secure_url"];
	$wms_url = $line["wms_url"];
	$site_email = $line["site_email"];
	$bgcolor = $line["bgcolor"];
	$font = $line["font"];
	$fontcolor = $line["fontcolor"];
}
mysql_free_result($result);
?>