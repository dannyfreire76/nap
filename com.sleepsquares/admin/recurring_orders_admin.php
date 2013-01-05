<?php

header('Content-type: text/html; charset=utf-8');

include '../includes/main1.php';
include '../includes/common.php';
include_once('../includes/wc1.php');
include_once($base_path.'includes/admin_orders_util.php');
include_once($base_path.'includes/authorize.net.php');
include '../includes/db.class.php';
$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

if ( $_REQUEST['action']=='delete.gif' && !empty($_REQUEST['id']) && isset($_REQUEST['activeVal']) ){
	$sql = "UPDATE recurring_orders SET recurring_active=".$_REQUEST['activeVal']." WHERE recurring_orders_id = $_REQUEST[id] LIMIT 1";

	$delResult = $db->Execute($sql);
	if ( $delResult ) { 
		echo 'ok';
		exit(); 
	}
}

if ( $_REQUEST['action']=='processNow' ) {
	$recurringMsg = promoteNextRecurringOrders();
}

$activeOrInactive = isset($_REQUEST["activeOrInactive"]) ?  $_REQUEST["activeOrInactive"] : 1;

include './includes/wms_nav1.php';

$recurQry = "SELECT recurring_orders_id,
				DATE_FORMAT(recurring_created, '%m/%d/%Y') as created,
				CONCAT('<a href=\"members_admin2_edit.php?member_id=', members.member_id, '\" target=\"_blank\">',members.first_name, ' ', members.last_name, '</a>') as member_name,
				CASE final_order when 0 then '---' ELSE final_order END AS final_order_date,
				CASE recurring_length when '' then '<span class=\"infinity_symbol\">&#8734;</span>' ELSE recurring_length END AS length,
				recurring_interval as `interval`,
				original_receipt_id,
				CASE rotating_products when 1 then 'yes' ELSE 'no' END as rotating_products
			FROM recurring_orders, members
			WHERE recurring_active='".$activeOrInactive."'
			AND recurring_orders.member_id=members.member_id
			ORDER BY recurring_created DESC";
//echo $recurQry;
$recurringOrders = $db->GetRecords($recurQry);

// define column alignment for specific fields center or right. default is left
$columns = array(	'recurring_orders_id' => 'center',
					'created'  => 'center',
					'member_name'   => 'center',
					'final_order_date'   => 'center',
					'original_receipt_id'   => 'center',
					'length'   => 'center',
					'interval'   => 'center',
					'rotating_products'   => 'center'
				);

$extraColumns = array(
				array('Edit','edit.gif',"loadDetail('{id}')"),
				array('Deactivate','delete.gif',"deactivateOrder('{id}')")
				);

if ( $activeOrInactive==0 ) {
$extraColumns = array(
				array('Edit','edit.gif',"loadDetail('{id}')"),
				array('REactivate','recurring.png',"reactivateOrder('{id}')")
				);
}

// This creates the table. The function is in the common.php
$recurringOrdersTable = createTable(	array_keys($recurringOrders[0]),
		$recurringOrders,
		array("id"=>"recurring-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
		$columns,
		$extraColumns,
		'recurring_orders_id');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="../includes/admin_orders_grid.css">

<style type="text/css">
	.infinity_symbol {
		font-size: 23px;
	    line-height: 10px;
	}
</style>

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

	function loadDetail(id){
		var url = "recurring_orders_edit.php?edit=1&recurring_orders_id=" + id;
		window.location = url;
	}

	function deactivateOrder(thisID){

		var answer = confirm("Are you sure you want to deactivate this recurring order?");

		if(answer){
			var post_url = "recurring_orders_admin.php";
			
			$.post(post_url, { action:'delete.gif', activeVal:0, id:thisID }, function(resp){
				if ( $.trim(resp) == 'ok' ) {
					window.location = "recurring_orders_admin.php";
				}
				else {
					alert("There was a problem deactivating this order.");
				}
			})
		}
	}

	function reactivateOrder(thisID){

		var answer = confirm("Are you sure you want to REactivate this recurring order?");

		if(answer){
			var post_url = "recurring_orders_admin.php";
			
			$.post(post_url, { action:'delete.gif', activeVal:1, id:thisID }, function(resp){
				if ( $.trim(resp) == 'ok' ) {
					window.location = "recurring_orders_admin.php";
				}
				else {
					alert("There was a problem reactivating this order.");
				}
			})
		}
	}

</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
</div>

<?php
if ( $recurringMsg!="" )  {
	echo '<br /><div class="" style="background-color: #DDDDDD;">';
	echo 'Recurring Orders settled and confirmation emails sent:';
	echo '</div>';
	echo '<div style="padding-left:40px">';
	echo $recurringMsg;
	echo '</div>';
}
?>
<br />
<form method="post" action="recurring_orders_admin.php" style="display:inline-block">
	<select name="activeOrInactive" style="font-size:18px" onChange="this.form.submit()">
		<option value="1">Active Recurring Orders</option>
		<option value="0" <?php if ($activeOrInactive==0){echo ' selected';} ?> >Inactive Recurring Orders</option>
	</select>
</form>
<form method="post" action="recurring_orders_admin.php" style="display:inline-block">
	<?php if ( $activeOrInactive!=0 ) { ?>
		<input type="hidden" name="action" value="processNow" />
		<input type="submit" value="process (over)due orders" style="margin-left:50px" />
	<?php } ?>
</form>
<br /><br />

<?php if(count($recurringOrders) > 0):?>

	<?php echo $recurringOrdersTable;?>

<?php else:?>

		<h3>No records found</h3>

<?php endif;?>


<?php include './includes/foot_admin1.php'; mysql_close($dbh); ?>
</body>
</html>
