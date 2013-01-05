<?php
// BME WMS
// Page: Shipping Homepage
// Path/File: /admin/shipping_admin.php
// Version: 1.8
// Build: 1806
// Date: 05-12-2007

header('Content-type: text/html; charset=utf-8');

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
$manager = "orders";
$page = "Orders > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);


$orClause = array();

$stashHoundQry = "SELECT wholesale_receipt_id, sku
				FROM wholesale_receipt_items
				WHERE sku >= 1200 AND sku < 1300;";
$result = mysql_query($stashHoundQry) or die("Query failed : " . mysql_error());

//echo $stashHoundQry;

$unShippedStashHoundOrders = $db->GetRecords($stashHoundQry);
//print_d($unShippedStashHoundOrders,false);

//echo __LINE__;

//print_d($result,false);
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$ws_receipt_id=$line["wholesale_receipt_id"];
	$orClause[]="wholesale_receipt_id = $ws_receipt_id";
}
mysql_free_result($result);

//echo __LINE__;

//print_d($orClause,false);
$orClause = implode(" OR ", $orClause);

$stashHoundQry = "SELECT wholesale_receipt_id AS receipt_id,
						wholesale_order_number AS order_number,
						bill_name,
						bill_phone,
						ordered AS order_date,
						total AS order_total,
						shipping_method,
						pay_type,
						cc_trans_id
				FROM wholesale_receipts
				WHERE complete='1'
				AND shipped='0'
				AND active='1'
				AND ($orClause)
				ORDER BY ordered DESC";


//echo $stashHoundQry;

//echo __LINE__;

$unShippedStashHoundOrders = $db->GetRecords($stashHoundQry);
//print_d($unShippedStashHoundOrders,false);

 //define column alignment for specific fields center or right. default is left
$columns = array(	'order_number' => 'center',
					'bill_phone'   => 'center',
					'order_total'  => 'right',
					'order_date'   => 'center',
					'start_date'   => 'center',
					'end_date'   => 'center',
					'pay_type'   => 'center');


//echo __LINE__;

$extraColumns = array(
				array('Edit','user_edit.png',"loadDetail('{id}',true)"),
				array('Set Inactive','user_delete.png',"deleteOrder('{id}',true)")
				);


 //This creates the table. The function is in the common.php

//echo __LINE__;

$stashHoundTable = createTable(	array_keys($unShippedStashHoundOrders[0]),
								$unShippedStashHoundOrders,
								array("id"=>"wholesale-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
								$columns,
								$extraColumns,
								'receipt_id');


//echo __LINE__;



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="../includes/admin_orders_grid.css">

<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="../includes/_.jquery.js"></script>
<script type="text/javascript" src="../includes/_.date.js"></script>
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

	//function loadSchedule(id,wholesale){
	//	var url = "orders.detail.php?schedule=1&id=" + id;
	//	if(wholesale){
	//		url += "&wholesale=1";
	//	}
	//	else{
	//		url += "&retail=1";
	//	}
	//	window.location = url;
	//}

</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
</div>
<div style="padding:5px;width:100%">

	<?php if(count($unShippedStashHoundOrders) == 0):?>
		<h3>There are no Stash Hound orders to display.<a href="orders.index.php"><img name="salviazone" id="salviazone" alt="salviazone" src="/favicon.ico" height="16" width="16" border="0" /></a></h3>
	<?php endif;?>


	<?php if(count($unShippedStashHoundOrders) > 0):?>

		<h3>Stash Hound Orders:<a href="orders.index.php"><img name="salviazone" id="salviazone" alt="salviazone" src="/favicon.ico" height="16" width="16" border="0" /></a></h3>

		<?php echo $stashHoundTable;?>

	<?php endif;?>


</div>

<?php include './includes/foot_admin1.php'; mysql_close($dbh); ?>
</body>
</html>
