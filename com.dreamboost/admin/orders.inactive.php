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

if(isset($_REQUEST['active']) && !empty($_REQUEST['id'])){
	if(!empty($_REQUEST['wholesale'])){
		$sql = "UPDATE wholesale_receipts SET `active` = '1',complete = '1' WHERE wholesale_receipt_id = $_REQUEST[id] LIMIT 1";
	}
	else{
		$sql = "UPDATE receipts SET `active` = '1',complete = '1' WHERE receipt_id = $_REQUEST[id] LIMIT 1";
	}
	
	$db->Execute($sql);
}

include './includes/wms_nav1.php';
$manager = "orders";
$page = "Orders > Inactive";
wms_manager_nav2($manager);
wms_page_nav2($manager);
	
$wholesaleQry = "SELECT wholesale_receipt_id AS receipt_id,
						wholesale_order_number AS order_number,
						bill_name,
						bill_phone,
						ordered AS order_date,
						total AS order_total,
						shipping_method,
						pay_type,
						cc_trans_id
				FROM wholesale_receipts
				WHERE active='0'
				ORDER BY ordered DESC";

$retailQry = "SELECT r.receipt_id,
					 r.user_id AS order_number,
					 r.bill_name,
					 r.bill_phone,
					 r.ordered AS order_date,
					 r.total AS order_total,
					 (SELECT DISTINCT ri.shipper FROM receipt_items ri WHERE ri.receipt_id = r.receipt_id) AS shipping_method,
					 r.pay_type,
					 r.cc_trans_id
			FROM receipts r
			WHERE r.active='0'
			ORDER BY r.ordered DESC";
			
$unShippedWholesaleOrders = $db->GetRecords($wholesaleQry);

// define column alignment for specific fields center or right. default is left
$columns = array(	'order_number' => 'center',
					'bill_phone'   => 'center',
					'order_total'  => 'right',
					'order_date'   => 'center',
					'pay_type'	   => 'center');

$extraColumns = array(
				//array('Edit','user_edit.png',"loadDetail('{id}',true)"),
				array('Set Active','user_edit.png',"setActive('{id}',true)")
				);


// This creates the table. The function is in the common.php
$wholesaleTable = createTable(	array_keys($unShippedWholesaleOrders[0]),
								$unShippedWholesaleOrders,
								array("id"=>"wholesale-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
								$columns,
								$extraColumns,
								'receipt_id');


$extraColumns = array(
				//array('Edit','user_edit.png',"loadDetail('{id}')"),
				array('Set Active','user_edit.png',"setActive('{id}')")
				);

$unShippedRetailOrders = $db->GetRecords($retailQry);

$retailTable = createTable(	array_keys($unShippedRetailOrders[0]),
								$unShippedRetailOrders,
								array("id"=>"retail-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
								$columns,
								$extraColumns,
								'receipt_id');


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
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

	function setActive(id,wholesale){

		var answer = confirm("Are you sure you want to set this order Active?");
		
		if(answer){
			var url = "orders.inactive.php?active=1&id=" + id;
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
<div style="padding:5px;width:100%">

	<?php if(count($unShippedWholesaleOrders) == 0 && count($unShippedRetailOrders) == 0):?>
		<h3>There's no inactive orders to display.</h3>
	<?php endif;?>

	<?php if(count($unShippedWholesaleOrders) > 0):?>

		<h3>Inactive Wholesale Orders:</h3>

		<?php echo $wholesaleTable;?>

	<?php endif;?>

	<?php if(count($unShippedRetailOrders) > 0):?>

		<h3>Inactive Retail Orders:</h3>
		
		<?php echo $retailTable;?>

	<?php endif;?>
</div>

<?php include './includes/foot_admin1.php'; mysql_close($dbh); ?>
</body>
</html>