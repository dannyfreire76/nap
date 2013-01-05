<?php
// BME WMS
// Page: Main WMS Include file
// Path/File: /includes/main1.php
// Version: 1.8
// Build: 1117
// Date: 01-15-2007

error_reporting(E_ERROR);

$URL = '';
$URL .= (($_SERVER['HTTPS'] != '') ? "https://" : "http://"); //get protocol
$URL .= (($www == true && !preg_match("/^www\./", $_SERVER['HTTP_HOST'])) ? 'www.'.$_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST']); //get host
$path = (($_SERVER['REQUEST_URI'] != '') ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']); //tell the function what path variable to use
$URL .= $path;
if ( strpos($URL, 'www') ) {
	header("Location: " . str_replace('www.', '', $URL) );
}

$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentFile); // TODO: Correct this code for both local file system (using backslashes), and remote (forward slashes).
$currentFile = $parts[count($parts) - 1]; 

session_start();

include_once('mysql_connect.php');


$queryA = "SELECT * FROM wms_main";
$resultA = mysql_query($queryA) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
	foreach($line as $col=>$val) {
		$$col = $val;
		//echo $col.' :: '.$val.'<br />';
	}
}
mysql_free_result($resultA);

//in admin site, set vars that should always be according to host site, not the site you're working on:
if ( strpos($URL, '/admin') ) {
	$queryAll = "SELECT * FROM sites";
	$resultAll = mysql_query($queryAll, $dbh_master) or die("Query failed : " . mysql_error());
	while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {
	
		$thisHandle = "dbh".$lineAll["site_key_name"];
		if ( strtolower($lineAll["site_url"])==strtolower($_SERVER["HTTP_HOST"]) ) {//just so we can have $dbh refer to the host dbh

			$queryA = "SELECT * FROM wms_main";
			$resultA = mysql_query($queryA, $$thisHandle) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
				foreach($line as $col=>$val) {
					if ( strpos($col,'base_')!==false || strpos($col,'_url')!==false ) {
						$$col = $val;
						//echo $col.' :: '.$val.'<br />';
					}
				}
			}

		}
	}
}



$current_base = $base_url;
if ( $_SERVER["HTTPS"] ) {
	$current_base = $base_secure_url;
}

$free_prods_arr = array();
$queryFree = "SELECT * FROM free_products";
$resultFree = mysql_query($queryFree) or die("Query failed : " . mysql_error());
while ($lineFree = mysql_fetch_array($resultFree, MYSQL_ASSOC)) {
	//TODO: only add to free_prods_arr if it's not already there
	$free_prods_arr[] = $lineFree["prod_id"];
	
	$free_prods_ship_arr[ $lineFree["prod_sku_id"] ] = $lineFree["free_prod_ship"];
}
mysql_free_result($resultFree);

$current_base = $base_url;
if ( $_SERVER["HTTPS"] ) {
	$current_base = $base_secure_url;
}

session_start();
ini_set("session.gc_maxlifetime", 120*60);
// DEBUG: display the timeout val:
//$currentTimeoutInSecs = ini_get("session.gc_maxlifetime");
//echo "test: ".$currentTimeoutInSecs;
if ( strpos($URL, '/admin') && !$_SESSION["pages_for_this_user"] && strpos($_SERVER["PHP_SELF"], 'admin/index.php')===false ) {
	header("Location: ".$current_base.'admin/' );
}

global $retailer_id;
global $retailer_status;
global $member_id;
global $member_name;
global $user_id;

//all of these might be set by a file before main1 is called via include, so check that it's not set before setting here
if (!$member_id) { $member_id = $_SESSION["member_id"]; }
if (!$member_name) { $member_name = $_SESSION["member_name"]; }
if (!$retailer_id) { $retailer_id = $_SESSION["wc_user"]; }
if (!$retailer_status) { $retailer_status = $_SESSION["wc_status"]; }
if (!$user_id) { 
	$user_id = $_COOKIE["nap_user"];//ALL SITES SHOULD USED THIS COOKIE NAME
}

//assign user_id if one doesn't exist
//NOTE we're getting everything from the master db
if(!$user_id) {
    $query = "INSERT INTO users SET user_id=NULL";
	$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
	$new_user_id = mysql_insert_id($dbh_master);

	//this loop synchronizes cookies
	$queryCookie = "SELECT * FROM sites";
	$resultCookie = mysql_query($queryCookie, $dbh_master) or die("Query failed : " . mysql_error());
	while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {
		//img src actually executes the code on the other server
		echo '<img style="display:none" src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineCookie["site_url"].'/'.(strpos($URL, '/staging') ? "staging/" : "").'includes/cookie_control.php?setcookie='.$new_user_id.'&referrer='.$_SERVER['HTTP_HOST'].'&timestamp='.time().'" />';
		
	}
    
	$user_id = $new_user_id;
}

//if user opted to stay logged in and isn't currently, log them in
if ( $_COOKIE["member_id"] & !$_SESSION["member_id"] ) {
	include_once('customer.php');

	doLogin( $_COOKIE["member_id"] );
	
	$imgStr = checkPartnerSiteMembers( $_SESSION["member_email"] );
	echo $imgStr;
}
?>