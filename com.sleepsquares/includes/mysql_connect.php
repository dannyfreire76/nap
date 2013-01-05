<?php

if ( $_REQUEST["newSiteKey"] ) {//switching sites in admin
	$_SESSION['active_site'] = $_REQUEST["newSiteKey"];
	exit();
}

//COMMON TO ALL SITES:
$dbh_master = mysql_connect("localhost","nap_nap","mazatec");
mysql_select_db("nap_store",$dbh_master) or die("Could not select master database");

$dbType = "store";
if ( strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ) {
	$dbType = "staging";
}

$min_global_user_id = 0;
$queryMinUser = "SELECT min(user_id) AS min_global_user_id FROM users";
$resultMinUser = mysql_query($queryMinUser, $dbh_master) or die("Query failed : " . mysql_error());
while ($lineMinUser = mysql_fetch_array($resultMinUser, MYSQL_ASSOC)) {
	$min_global_user_id = $lineMinUser["min_global_user_id"];
}

$thisSite = array();
//ALL SITES:
$queryAll = "SELECT * FROM sites";
$resultAll = mysql_query($queryAll, $dbh_master) or die("Query failed : " . mysql_error());
while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {

	$thisDBHName = "dbh".$lineAll["site_key_name"];
	$$thisDBHName=mysql_connect("localhost", $lineAll["site_key_name"]."_".$lineAll["site_db_user"], $lineAll["site_db_pw"]) or die ('Could not connect to the database: ' . $thisDBHName);
	mysql_select_db($lineAll["site_key_name"]."_".$dbType, $$thisDBHName) or die("Could not select database");

	if ( $_SESSION['active_site'] ) {
		if ( $_SESSION['active_site']==strtolower($lineAll["site_key_name"]) ) {
			$thisSite = $lineAll;
		}
	} else if ( strtolower($lineAll["site_url"])==strtolower($_SERVER["HTTP_HOST"]) ) {//just so we can have $dbh refer to the host dbh
		$thisSite = $lineAll;
	}
}
//set the default to this site's connection (but you can still refer to this site's connection dynamically
$dbh=mysql_connect("localhost", $thisSite["site_key_name"]."_".$thisSite["site_db_user"], $thisSite["site_db_pw"]) or die ('Could not connect to the database: '.$thisSite["site_key_name"]);
mysql_select_db($thisSite["site_key_name"]."_".$dbType, $dbh) or die("Could not select database");


?>