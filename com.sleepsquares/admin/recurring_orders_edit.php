<?php

header('Content-type: text/html; charset=utf-8');

include '../includes/main1.php';
include '../includes/common.php';
include '../includes/db.class.php';
include_once($base_path.'includes/authorize.net.php');
include_once($base_path.'includes/admin_orders_util.php');
include_once($base_path.'includes/wc1.php');

$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';

//set submitted variables to simple var names with global scope
foreach( $_POST as $n=>$v ){
	$$n = $v;
	//echo '<br />'.$n.' = '.$v;
}

$recurring_orders_id = $_REQUEST["recurring_orders_id"];

if(isset($_REQUEST['delete']) && !empty($_REQUEST['id'])){
	$sql = "DELETE receipts, receipt_items FROM receipts, receipt_items WHERE receipts.receipt_id = $_REQUEST[id] AND receipts.receipt_id=receipt_items.receipt_id";

	$db->Execute($sql);
}

if(isset($_REQUEST['deactivate']) && !empty($_REQUEST['id'])){
	$sql = "UPDATE receipts SET active='0', complete='0' WHERE receipt_id = $_REQUEST[id] LIMIT 1";

	$db->Execute($sql);
}

$lastQry = "SELECT *
FROM receipts
WHERE recurring_orders_id='".$recurring_orders_id."'
ORDER BY ordered DESC LIMIT 1";

if ( isset($saveChanges) ) {	
	if ( $recurring_length ) {
		$final_order = date('Y-m-d', strtotime("+".$recurring_length));
	}

	$upQry = "UPDATE
			recurring_orders
			SET recurring_interval='$recurring_interval', 
			recurring_length='$recurring_length', 
			recurring_active='$recurring_active', 
			recurring_begin='".date("Y-m-d",strtotime($ordered) )."', 
			rotating_products='$rotating_products', 
			payment_profile_id='$payment_profile_id', 
			final_order='$final_order'
			WHERE recurring_orders_id='$recurring_orders_id'";

	$upResult = $db->Execute($upQry);

	if ( $upResult && $rebuildQueue==1 ) {//delete outstanding receipts for this recurring order
		$delQry = "DELETE receipts, receipt_items FROM 
				receipts, receipt_items
				WHERE receipts.complete!= '1'
				AND receipts.receipt_id=receipt_items.receipt_id
				AND receipts.recurring_orders_id='$recurring_orders_id'";

		$delResult = $db->Execute($delQry);

		$startDate = date("Y-m-d",strtotime($ordered) );
	}

	if ( $rebuildQueue==0 ) {
		$lastOrder = $db->GetRecord($lastQry);
		$startDate = $lastOrder["ordered"];
	}
}


$recurQry = "SELECT *
			FROM recurring_orders, members
			WHERE recurring_orders_id='".$recurring_orders_id."'
			AND recurring_orders.member_id=members.member_id
			LIMIT 1";

$thisRecurringOrder = $db->GetRecord($recurQry);

$memberQry = "SELECT *
			FROM members
			WHERE member_id='".$thisRecurringOrder["member_id"]."'
			LIMIT 1";

$memberRecord = $db->GetRecord($memberQry);


if( isset($_REQUEST['addNewRow']) ){
	$lastOrder = $db->GetRecord($lastQry);
	$startDate = $lastOrder["ordered"];

	$recCntQ = "SELECT count(*) as thisCount
	FROM receipts
	WHERE recurring_orders_id='".$recurring_orders_id."'
	AND complete!='1'";
	$recRecord = $db->GetRecord($recCntQ);
	$recCount = $recRecord["thisCount"] * 1 + 1;

	$addError = "";
	if ( $thisRecurringOrder["recurring_length"] ) {
		$nextOrderDate = date("Y-m-d",strtotime( "+".$thisRecurringOrder["recurring_interval"], strtotime($lastOrder["ordered"]) ));
		
		if ($nextOrderDate > date("Y-m-d",strtotime($thisRecurringOrder["final_order"]) )  ) {
			$addError = "Adding another order would extend this beyond the term specified.";
		}
	}

	if ( $addError=="" ) {
		cloneReceiptsAndItems($thisRecurringOrder["original_receipt_id"], $thisRecurringOrder["final_order"], $thisRecurringOrder["recurring_interval"], $thisRecurringOrder["recurring_length"], $recurring_orders_id, $thisRecurringOrder["rotating_products"], $startDate, $recCount);
	}
}

$recurringMsg = "";
//after update and then getting record above:
if ( isset($saveChanges) ) {	
	cloneReceiptsAndItems($thisRecurringOrder["original_receipt_id"], $thisRecurringOrder["final_order"], $thisRecurringOrder["recurring_interval"], $thisRecurringOrder["recurring_length"], $recurring_orders_id, $thisRecurringOrder["rotating_products"], $startDate);

	//process any recurring orders that were just created
	$recurringMsg = promoteNextRecurringOrders();
}

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

		$(':input', '#recurringChangeForm').change(function(){
			$('#saveChanges').removeAttr('disabled');
		});

		$('#addNewRow').click( function(){ addNewRow(); } );

		toggleRebuildOptions();
		$('#recurring_length').change(function(){ toggleRebuildOptions(); });
	});

	function toggleRebuildOptions() {
		if ( $('#recurring_length').val()=='' ) {
			$('#rebuildNo').attr('checked', 'checked');
			$('#rebuildOptions:hidden').slideDown(200);
		} else {
			$('#rebuildYes').attr('checked', 'checked');
			$('#rebuildOptions:visible').slideUp(200);
		}
	}

	function checkSaveChanges() {
		if ( $('#rebuildYes').is(':checked') ) {
			if ( !confirm("Saving these changes will rebuild the queue.\n\nAre you sure you want to do this?") ) {
				return false;
			}
		}

		return true;
	}

	function viewInvoice(id){
		var url = "shipping_admin3.php?show_only=1&receipt_id=" + id;
		window.location = url;
	}

	function loadDetail(id){
		var url = "orders.detail.php?edit=1&id=" + id + "&retail=1";
		window.location = url;
	}

	function deleteOrder(id){
		var answer = confirm("Deletion is permanent.  Are you sure you want to do this?");

		if(answer){
			var url = "recurring_orders_edit.php?delete=1&id=" + id + "&recurring_orders_id=<?=$recurring_orders_id?>";
			window.location = url;
		}
	}
	
	function deactivateOrder(id){
		var url = "recurring_orders_edit.php?deactivate=1&id=" + id + "&recurring_orders_id=<?=$recurring_orders_id?>";
		window.location = url;
	}

	function addNewRow() {
		var url = "recurring_orders_edit.php?addNewRow=1&recurring_orders_id=<?=$recurring_orders_id?>";
		window.location = url;
	}

</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
</div>
<br />
<a href="recurring_orders_admin.php">&lt;&lt; Back to Recurring Orders Homepage</a>
<?php
	if ( $recurringMsg!="" ) {
		echo '<br /><br /><div class="" style="background-color: #DDDDDD;">';
		echo 'Recurring Orders settled and confirmation emails sent:';
		echo '</div>';
		echo '<div style="padding-left:40px">';
		echo $recurringMsg;
		echo '</div>';
	}

	if ( $addError!="" ) {
		echo '<br /><br /><div class="error">';
		echo $addError;
		echo '</div>';
	}

?>
<div>
	<form action="recurring_orders_edit.php" id="recurringChangeForm" method="post" onSubmit="return checkSaveChanges()">
		<input type="hidden" name="recurring_orders_id" value="<?=$recurring_orders_id?>" />
		<h2 style="background-color: #DDDDDD;">
			Recurring order ID #<?=$recurring_orders_id?> for <a href="members_admin2_edit.php?member_id=<?=$thisRecurringOrder["member_id"] ?>"><?=$thisRecurringOrder["first_name"].' '.$thisRecurringOrder["last_name"]?></a> is 	
			<select name="recurring_active">
				<option value="1" <?php if ($thisRecurringOrder["recurring_active"]==1){echo ' selected';} ?> >active</option>
				<option value="0" <?php if ($thisRecurringOrder["recurring_active"]==0){echo ' selected';} ?> >inactive</option>
			</select>
			<?php
				if ( $thisRecurringOrder["final_order"] > 0 ) {
					echo '&#160;&#160;&#160;<span style="font-size:12px">( final available order date: '.date("m/d/Y",strtotime($thisRecurringOrder["final_order"])).' )</span>';
				}
			?>
		</h2>
		<br />
		<div>
			<?php echo recurringFormOptions($thisRecurringOrder["recurring_interval"], $thisRecurringOrder["recurring_length"], date("m/d/Y",strtotime($thisRecurringOrder["recurring_begin"]) ), $thisRecurringOrder["rotating_products"]) ?>
		</div>
		<br />
		<div id="rebuildOptions" class="no_display">
			<input type="radio" name="rebuildQueue" id="rebuildNo" value="0" checked="checked" /> Change schedule starting from last order in queue&#160;&#160;&#160;&#160;&#160;&#160;&#160;
			<input type="radio" name="rebuildQueue" id="rebuildYes" value="1" <?php if ($rebuildQueue==1){echo ' selected';} ?> /> Rewrite all orders in queue
		</div>
		<?php
			getMerchantCreds();//authorize.net.php
			if ( $company == 1 ) {
				if ( $memberRecord["customer_profile_id"] ) {

					$completeProfileRequest = getCustomerProfileRequest( $memberRecord["customer_profile_id"] );//get all payment profiles

					if ( count($completeProfileRequest->profile->paymentProfiles) > 0 ) {
						echo '<br />Use credit card on file:<br />';
						echo '<select name="payment_profile_id" id="payment_profile_id">';

						foreach ( $completeProfileRequest->profile->paymentProfiles as $aProfile ) {
							echo '<option value="'.$aProfile->customerPaymentProfileId.'"';
							if ( $thisRecurringOrder["payment_profile_id"] == $aProfile->customerPaymentProfileId ) { echo ' selected '; }
							echo ' >'.$aProfile->billTo->firstName.' '.$aProfile->billTo->lastName.', '.$aProfile->billTo->address.' - '.$aProfile->payment->creditCard->cardNumber.'</option>';
						}
						echo '</select><br />';
					}
				}
			}
		?>
		<br />
		<input type="submit" name="saveChanges" id="saveChanges" value="Save Changes" disabled="disabled" />
	</form>
	<br />
	<br />
	<?php
	/******************************************************************/
	$receiptsQry = "SELECT r.receipt_id,
					 r.user_id AS order_number,
					 r.bill_name,
					 r.bill_phone,
					 DATE_FORMAT(ordered, '%m/%d/%Y') as order_date,
					 r.total AS order_total,
					 ship_method.name AS shipping_method,
					 r.pay_type,
					 r.cc_trans_id
				FROM receipts r, ship_method
				WHERE r.recurring_orders_id='".$recurring_orders_id."' 
				AND ship_method.ship_method_id=r.shipping_method
				AND r.complete=1
				AND r.shipped=1
				ORDER BY r.ordered ASC";
	$completeReceipts = $db->GetRecords($receiptsQry);

	// define column alignment for specific fields center or right. default is left
	$columns = array(	'order_number' => 'center',
					'bill_phone'   => 'center',
					'order_total'  => 'right',
					'order_date'   => 'center',
					'start_date'   => 'center',
					'end_date'   => 'center',
					'pay_type'	   => 'center',
					'shipping_method'  => 'center',
					'cc_trans_id' => 'right' );

?>
	<h3>Completed and shipped orders</h3>

	<?php if(count($completeReceipts) > 0):?>

		<?php
			$extraColumns = array(
			array('View','history.png',"viewInvoice('{id}')"),
			);
		
			// This creates the table. The function is in the common.php
			$completedOrdersTable = createTable(	array_keys($completeReceipts[0]),
				$completeReceipts,
				array("id"=>"shipped-receipts-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
				$columns,
				$extraColumns,
				'receipt_id');

			echo $completedOrdersTable;
		
		?>

	<?php else:?>

			<b>No records found</b>

	<?php endif;?>
	
	<?php
	/******************************************************************/
	$receiptsQry = "SELECT r.receipt_id,
					 r.user_id AS order_number,
					 r.bill_name,
					 r.bill_phone,
					 DATE_FORMAT(ordered, '%m/%d/%Y') as order_date,
					 r.total AS order_total,
					 ship_method.name AS shipping_method,
					 r.pay_type,
					 r.cc_trans_id
				FROM receipts r, ship_method
				WHERE r.recurring_orders_id='".$recurring_orders_id."' 
				AND ship_method.ship_method_id=r.shipping_method
				AND r.complete=1
				AND r.shipped=0
				ORDER BY r.ordered ASC";
	$unshippedReceipts = $db->GetRecords($receiptsQry);

	$extraColumns = array(
			array('Edit','edit.gif',"loadDetail('{id}')"),
			array('Deactivate','delete.gif',"deactivateOrder('{id}')")
			);

	?>
	<br /><br />
	<h3>Completed but unshipped orders</h3>

	<?php if(count($unshippedReceipts) > 0):?>

		<?php 
			// This creates the table. The function is in the common.php
			$unshippedOrdersTable = createTable(	array_keys($unshippedReceipts[0]),
				$unshippedReceipts,
				array("id"=>"complete-receipts-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
				$columns,
				$extraColumns,
				'receipt_id');
		
			echo $unshippedOrdersTable;
		?>

	<?php else:?>

			<b>No records found</b>

	<?php endif;?>

	<?php
	/******************************************************************/
	$receiptsQry = "SELECT r.receipt_id,
					 r.user_id AS order_number,
					 r.bill_name,
					 r.bill_phone,
					 DATE_FORMAT(ordered, '%m/%d/%Y') as order_date,
					 r.total AS order_total,
					 ship_method.name AS shipping_method,
					 r.pay_type,
					 r.cc_trans_id
				FROM receipts r, ship_method
				WHERE r.recurring_orders_id='".$recurring_orders_id."' 
				AND ship_method.ship_method_id=r.shipping_method
				AND r.complete=0
				ORDER BY r.ordered ASC";
	$queuedReceipts = $db->GetRecords($receiptsQry);

	$extraColumns = array(
			array('Edit','edit.gif',"loadDetail('{id}')"),
			array('Delete','delete.gif',"deleteOrder('{id}')")
			);

	?>
	<br /><br />
	<h3>Orders in the queue <?php if (!$thisRecurringOrder["recurring_length"]) { echo '(recurring orders with no end maintain 12 orders in the queue)'; }?></h3>

	<?php if(count($queuedReceipts) > 0):?>

		<?php 
		// This creates the table. The function is in the common.php
		$queuedOrdersTable = createTable(	array_keys($queuedReceipts[0]),
			$queuedReceipts,
			array("id"=>"complete-receipts-table","class"=>'grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
			$columns,
			$extraColumns,
			'receipt_id');

			echo $queuedOrdersTable;	
		?>
	<button class="bold" id="addNewRow">+ Add new row</button>

	<?php else:?>

			<b>No records found</b>

	<?php endif;?>

</div>
<br />
<?php include './includes/foot_admin1.php'; mysql_close($dbh); ?>
</body>
</html>
