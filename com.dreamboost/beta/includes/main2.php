<?php
// BME WMS
// Page: Main 2 WMS Include file
// Path/File: /includes/main2.php
// Version: 1.1
// Build: 1113
// Date: 01-15-2007

$dbh=mysql_connect("localhost", "dreamboo_dreambo", "mazatec") or die ('Could not connect to the database: ' . mysql_error());
mysql_select_db("dreamboo_store") or die("Could not select database");

$version="1.8";
?>