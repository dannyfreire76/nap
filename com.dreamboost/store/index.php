<?php
// BME WMS
// Page: Store Product List Page
// Path/File: /store/product_list.php
// Version: 1.8
// Build: 1805
// Date: 05-06-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$confirm = $_GET["clearit"];

if($confirm){
	$result = setcookie("db_user", $user_id, time()-3600, "/", ".dreamboost.com", 0) or die ("Set Cookie failed : " . mysql_error());
	exit();
}

include '../store/product_list.php';

?>

