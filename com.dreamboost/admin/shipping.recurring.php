<?php
// BME WMS
// Page: Shipping Homepage
// Path/File: /admin/shipping_admin.php
// Version: 1.8
// Build: 1806
// Date: 05-12-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();

include '../includes/st_and_co1.php';
include '../includes/main1.php';
include '../includes/common.php';
include '../includes/db.class.php';
$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

if(isset($_REQUEST['delete']) && !empty($_REQUEST['id'])){
	if(!empty($_REQUEST['wholesale'])){
		$sql = "UPDATE wholesale_receipts SET `active` = '0', complete = '0' WHERE wholesale_receipt_id = $_REQUEST[id] LIMIT 1";
	}
	else{
		$sql = "UPDATE receipts SET `active` = '0', complete = '0' WHERE receipt_id = $_REQUEST[id] LIMIT 1";
	}

	$db->Execute($sql);
}



include './includes/wms_nav1.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="../includes/admin_orders_grid.css">

<script type="text/javascript" src="/includes/jquery.js"></script>
<script src="../includes/jquery.tablesorter.min.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript">

	var firstLoad = true;

	$('document').ready(function(){

		// this creates a way to sort the table in the browser
		$(".grid").tablesorter({widgets: ['zebra']});

		$(".grid tbody tr").hover(
			function () {
				$(this).addClass('rowHighlight');
			},
			function () {
				$(this).removeClass('rowHighlight');
			}
		);

	});

	function loadDetail(id,wholesale){
		var url = "orders.detail.php?edit=1&id=" + id;
		if(wholesale){
			url += "&wholesale=1";
		}
		else{
			url += "&retail=1";
		}
		window.location = url;
	}
	function deleteOrder(id,wholesale){

		var answer = confirm("Are you sure you want to set this order inactive?");

		if(answer){
			var url = "orders.index.php?delete=1&id=" + id;
			if(wholesale){
				url += "&wholesale=1";
			}
			else{
				url += "&retail=1";
			}
			window.location = url;
		}
	}

</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
</div>
    Coming soon!
<div>
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>
