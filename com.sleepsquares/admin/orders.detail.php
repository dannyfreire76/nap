<?php

header('Content-type: text/html; charset=utf-8');

include '../includes/main1.php';
include '../includes/st_and_co1.php';

include '../includes/common.php';
include '../includes/wc1.php';

include '../includes/db.class.php';
$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
$manager = "orders";
$page = "Orders > Order Detail";
wms_manager_nav2($manager);
wms_page_nav2($manager);

// get the payment processor for the active store
$cc_processor = $db->GetRecord("SELECT company FROM merchant_acct WHERE status='1' LIMIT 1");
//print_d($cc_processor,true);

$funds_received=$_REQUEST['funds_received'];

if(!empty($_REQUEST['update'])){

	//$_REQUEST['funds_received'] = $_REQUEST['funds_received']['year'] .'-'. $_REQUEST['funds_received']['month'] .'-'. $_REQUEST['funds_received']['day'];
	//if($_REQUEST['funds_received'] == "--"){
	//	$_REQUEST['funds_received'] = "";
	//}
	//

//print_d($_REQUEST);
//exit;

	if(!empty($_REQUEST['receipt_id'])){
		$table = 'receipts';
		$idFld = 'receipt_id';
		$idVal = $_REQUEST['receipt_id'];
	}
	elseif(!empty($_REQUEST['wholesale_receipt_id'])){
		$table = 'wholesale_receipts';
		$idFld = 'wholesale_receipt_id';
		$idVal = $_REQUEST['wholesale_receipt_id'];
	}
	else{
		exit("Cannot save order. No receipt_id or wholesale_receipt_id was passed.");
	}


	$_REQUEST["ordered"] = date("Y-m-d H:i:s",strtotime($_REQUEST["ordered"]) );
	// Update the receipt reccord
	$update_fields = $db->MakeUpdateFields($table,$_REQUEST,$idFld);
	$sql = "UPDATE $table SET $update_fields WHERE $idFld = '$idVal'";
	//print_d($sql);
	$db->Execute($sql);


	// update the line items for this receipt
	if(!empty($_REQUEST['receipt_item_id'])){
		$item_table = 'receipt_items';
		$item_id_fld = 'receipt_item_id';
		$receipt_id = $_REQUEST['receipt_id'];
		$receipt_id_fld = 'receipt_id';
	}
	elseif(!empty($_REQUEST['wholesale_receipt_item_id'])){
		$item_table = 'wholesale_receipt_items';
		$item_id_fld = 'wholesale_receipt_item_id';
		$receipt_id = $_REQUEST['wholesale_receipt_id'];
		$receipt_id_fld = 'wholesale_receipt_id';
	}
	else{
		exit("Cannot update order items. No receipt_item_id or wholesale_receipt_item_id was passed.");
	}

	if(!empty($_REQUEST[$item_id_fld]) && trim($receipt_id) != ''){

		$db->Execute("DELETE FROM $item_table WHERE $receipt_id_fld = '$receipt_id'");

		foreach($_REQUEST[$item_id_fld] as $i=>$flds){

			if(!empty($_REQUEST['sku'][$i])){

				$data[$receipt_id_fld] = $receipt_id;
				$data['sku']		  = $_REQUEST['sku'][$i];
				$data['quantity']	  = $_REQUEST['quantity'][$i];
				$data['price']		  = $_REQUEST['price'][$i];
				$data['line_total']   = $_REQUEST['line_total'][$i];
				$data['orig_price']	  = $_REQUEST['orig_price'][$i];
				$data['shipper']	  = $_REQUEST['shipper'][$i];
				$data['tracking_num'] = $_REQUEST['tracking_num'][$i];

				if(strstr($_REQUEST['name'][$i],'|')){
					$tmp_names = explode('|',$_REQUEST['name'][$i]);
					$data['name'] = $tmp_names[1];
				}
				else{
					$data['name'] = $_REQUEST['name'][$i];
				}

				list($fields,$values) = $db->MakeAddFields($item_table,$data,$item_id_fld);

				$sql = "INSERT INTO $item_table ($fields) VALUES ($values)";
				//print_d($sql);
				$db->Execute($sql);
			}
		}
	}
}


$receiptItemFld = "receipt_item_id";
$receiptTable = 'receipts';

$result = array();
if(!empty($_REQUEST['wholesale']) OR !empty($_REQUEST['wholesale_receipt_id'])){

	if(!empty($_REQUEST['wholesale_receipt_id'])){
		$_REQUEST['id'] = $_REQUEST['wholesale_receipt_id'];
	}

	$sql = "SELECT w.*,r.store_name FROM wholesale_receipts w
			LEFT JOIN retailer r ON(w.retailer_id = r.retailer_id)
			WHERE w.wholesale_receipt_id = $_REQUEST[id]";

	$result = $db->GetRecord($sql);
	$refunds = $db->GetRecords("SELECT SUM(refund_amount) as refund_total FROM refunds WHERE wholesale_receipt_id = $_REQUEST[id]");
	$result['items'] = $db->GetRecords("SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id = $_REQUEST[id]");

	$receiptItemFld = "wholesale_receipt_item_id";
	$receiptTable = 'wholesale_receipts';

}
if(!empty($_REQUEST['retail']) OR !empty($_REQUEST['receipt_id'])){

	if(!empty($_REQUEST['receipt_id'])){
		$_REQUEST['id'] = $_REQUEST['receipt_id'];
	}

	$result = $db->GetRecord("SELECT * FROM receipts WHERE receipt_id = $_REQUEST[id]");
	$result['items'] = $db->GetRecords("SELECT * FROM receipt_items WHERE receipt_id = $_REQUEST[id]");
	$refunds = $db->GetRecords("SELECT SUM(refund_amount) as refund_total FROM refunds WHERE receipt_id = $_REQUEST[id]");
}

function createTrackingNumberLink($shipper,$tracking_number){

	$html = null;

	if($shipper == ""){
		return null;
	}

	switch(strtolower($shipper)){

		case "fedx":
			$html = "<a href=\"http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=$tracking_number\" target=\"_blank\">$tracking_number</a>";
			break;

		case "ups":
			$html = "<a href=\"http://wwwapps.ups.com/WebTracking/OnlineTool?UPS_HTML_License=CBFDAE072B810C21&UPS_HTML_Version=3.0&TypeOfInquiryNumber=T&IATA=us&Lang=eng&InquiryNumber1=$tracking_number\" target=\"_blank\">$tracking_number</a>";
			break;

		case "usps":
			$html = "<a href=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?CAMEFROM=OK&strOrigTrackNum=$tracking_number\" target=\"_blank\">$tracking_number</a>";
			break;

		case "dhl":
			$html = "<a href=\"http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber=$tracking_number\" target=\"_blank\">$tracking_number</a>";
			break;

		default:
			$html = $tracking_number;
			break;
	}
	return $html;
}


//print_d($db->GetRecords("SELECT * FROM products WHERE active = '1'"));

// get a list of products for new lines
$products = $db->GetRecords("SELECT * FROM product_skus WHERE active = '1'");

//print_d($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>

<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="../includes/admin_orders_grid.css">

<script type="text/javascript" src="/includes/jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="../includes/_.jquery.js"></script>
<script type="text/javascript" src="../includes/_.date.js"></script>

<script type="text/javascript" language="JavaScript">

	$('document').ready(function(){

	});

function addRow(){

	// clone last row
	var $newRow = $('#line-items tbody>tr:last').clone(true);

	// reset the new row form values
	$newRow.find(':input',':hidden').each(function() {
		if(this.type == 'text' || this.type == 'hidden'){
			$(this).attr('value',"");
		}
	});

	// append the row to the table
	$newRow.insertAfter('#line-items tbody>tr:last');

	// load in the product list
	var products = $('#product-select').html();
	$('#line-items tbody>tr:last>td:nth-child(3)').html(products);
	$('#line-items tbody>tr:last').find("select[name='name[]']").focus();

}


function populateFields(select){

	// get selected value
	var values = $(select).val().split('|');

	// get table row
	var row = $(select).parent().parent();

	// populate boxes
	row.find("input[name='sku[]']").val(values[0]);
	row.find("input[name='price[]']").val(values[2]);
	row.find("input[name='orig_price[]']").val(values[2]);

	// focus to quantity box
	row.find("input[name='quantity[]']").select();
}

function setLineTotal(fld){

	// get table row
	var row = $(fld).parent().parent();

	var qty = row.find("input[name='quantity[]']").val();

	// get current price
	var price = row.find("input[name='price[]']").val();

	var total = parseFloat(qty * price).toFixed(2);

	// set total
	row.find("input[name='line_total[]']").val(total);

	setTotals();

}

function setTotals(){

	var subtotal = parseFloat(0);

	$('#line-items').find("input[name='line_total[]']").each(function(){
		subtotal += parseFloat($(this).val());
	});

	$('#subtotal').val(subtotal.toFixed(2));

	var tax = parseFloat($('#tax').val());
	var shipping = parseFloat($('#shipping').val());

	$('#total').val(parseFloat(subtotal + tax + shipping).toFixed(2));

}

function deleteRow(ck){

	if($(ck).is(':checked')){
		var answer = confirm("Are you sure you want to delete this item?");
		if(answer){
			// get table row
			var row = $(ck).parent().parent();
			row.remove();
			setTotals();
		}
		else{
			$(ck).attr('checked',false);
		}
	}
}

function doRefund(){

	var amount = parseFloat($("#refund_amount").val()).toFixed(2);

	var total = parseFloat($("#total").val()).toFixed(2);

	if(amount > 0 && amount <= total){

		var answer = confirm("Are you sure you want to do this refund?");

		if(answer){

			var ccnum = $("#cc_num").val();

			var idfld = null;
			var id = null;

			if($("#wholesale_receipt_id").val() !== undefined){
				idfld = 'wholesale_receipt_id';
				id = $("#wholesale_receipt_id").val();
			}
			else if($("#receipt_id").val() !== undefined){
				idfld = 'receipt_id';
				id = $("#receipt_id").val();
			}

			if(id){
				var url = "orders.refund.php?" + idfld +"="+ + id;
				url += "&orig_total=" + total;
				url += "&x_Card_Num=";
				url += ccnum.substring(ccnum.length -4);
				url += "&x_Exp_Date=";
				url += $("#cc_exp_m").val();
				url += $("#cc_exp_y").val();
				url += "&x_Trans_ID=";
				url += $("#cc_trans_id").val();
				url += "&x_Amount=";
				url += parseFloat($("#refund_amount").val()).toFixed(2);
				window.location = url;
				//alert(url);
			}
			else{
				alert("Missing receipt id. Cannot credit order");
			}
		}
	}
	else{
		if(amount != 'NaN' && amount > total){
			alert("The refund amount you entered is invalid. The refund amount cannot exceed the order total.");
		}
		else if(amount == 0 || amount == 'NaN'){
			alert("The refund amount you entered is invalid. The refund amount must be greater than zero.");
		}
	}
}

</script>
<style>
#detail-table td{
	vertical-align:top;
}
#line-items th{
	background-color: #ddd;
}
#line-items td{
	text-align:center;
}

#new-item-table th{
	background-color: #ddd;
}
#new-item-table td{
	text-align:center;
}

.txtright{
	text-align:right;
}
.txtcenter{
	text-align:center;
}
.hidden{
	display:none;
}
</style>
<?php include '../includes/datepicker.php'; ?>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
</div>

<div style="padding:5px;width:100%">

	<h3 style="padding-top:10px;padding-left:32px;">Order Details<?php if ( $result['recurring_orders_id'] ) {echo ' (part of <a href="recurring_orders_edit.php?edit=1&recurring_orders_id='.$result['recurring_orders_id'].'"  title="edit recurring order">Recurring Order #'.$result['recurring_orders_id'].'</a>)';} ?>:</h3>
	<div style="position:absolute;right:32px;top:50px;">
		<a href="orders.index.php" style="font-size:14px;font-weight:bold;">Go back to order list</a>
	</div>

	<form action="orders.detail.php" method="post" id="order-form">

	<?php if(isset($result['wholesale_receipt_id'])):?>
		<input type="hidden" id="wholesale_receipt_id" name="wholesale_receipt_id" value="<?php echo $result['wholesale_receipt_id'];?>">
		<input type="hidden" name="wholesale" value="1">
	<?php else:?>
		<input type="hidden" id="receipt_id" name="receipt_id" value="<?php echo $result['receipt_id'];?>">
		<input type="hidden" name="retail" value="1">
	<?php endif;?>


	<table id="detail-table" border="0" cellpadding="3" cellspacing="0" width="95%" align="center">

		<tr style="background-color: #ddd;">
			<th align="left" id="widget">Order Date: <?php 
				if ( $result['recurring_orders_id'] && $result['complete']!='1' ) {
					echo '<input type="hidden" name="recurring" id="recurring" value="1" />';
					echo '<input type="text" name="ordered" id="ordered" size="10" maxlength="10" value="'.date("m/d/Y",strtotime($result['ordered']) ).'" /> <span style="font-weight:normal">(mm/dd/yyyy)</span>';
				} else {
					echo date("m/d/Y",strtotime($result['ordered']) );
					echo '<input type="hidden" name="ordered" id="ordered" value="'.date("m/d/Y",strtotime($result['ordered']) ).'" />';
				}
			?></th>
			<th align="right">
				Order Number:
				<?php if(!empty($result['wholesale_order_number'])):?>
					<?php echo $result['wholesale_order_number'];?>
				<?php else:?>
					<?php echo $result['user_id'];?>
				<?php endif;?>
			</th>
		</tr>


		<tr>
			<th style="padding-top:10px;padding-bottom:10px;" align="left">Billing Information:</th>
			<th style="padding-top:10px;padding-bottom:10px;" align="left">Shipping Information:</th>
		</tr>
		<tr>
			<td>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">

					<?php if(isset($result['store_name'])):?>
					<tr>
						<td align="right">Store Name:</td>
						<td>
							<input type="text" name="store_name" value="<?php echo stripslashes($result['store_name']);?>" size="45">
						</td>
					</tr>
					<?php endif;?>

					<tr>
						<td align="right">Bill Name:</td>
						<td>
							<input type="text" name="bill_name" value="<?php echo $result['bill_name'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill Address 1:</td>
						<td>
							<input type="text" name="bill_address1" value="<?php echo $result['bill_address1'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill Address 2:</td>
						<td>
							<input type="text" name="bill_address2" value="<?php echo $result['bill_address2'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill City:</td>
						<td>
							<input type="text" name="bill_city" value="<?php echo $result['bill_city'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill State:</td>
						<td>
							<?php
								$states[0] = array('code'=>"",'name'=>"");
								$data = $db->getRecords("SELECT `code`,`name` FROM states WHERE status = '1' ORDER BY `name`");
								$states = array_merge($states,$data);
								print makeSelectBox("bill_state",$states,$result['bill_state']);
							?>
						</td>
					</tr>
					<tr>
						<td align="right">Bill Zip:</td>
						<td>
							<input type="text" name="bill_zip" value="<?php echo $result['bill_zip'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill Country:</td>
						<td>
							<?php
								$data = $db->getRecords("SELECT `code`,`name` FROM countries WHERE status = '1'");
								print makeSelectBox("bill_country",$data,$result['bill_country']);
							?>
						</td>
					</tr>
					<tr>
						<td align="right">Bill Phone:</td>
						<td>
							<input type="text" name="bill_phone" value="<?php echo $result['bill_phone'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Bill Email:</td>
						<td>
							<input type="text" name="bill_email" value="<?php echo $result['bill_email'];?>" size="45">
						</td>
					</tr>
					<?php if(isset($result['receipt_id'])):?>
					<tr><td colspan="2" align="center">&nbsp;</td></tr>
					<?php endif;?>
					<tr><td colspan="2" align="center">&nbsp;</td></tr>
					<?php
						if ( !($result['recurring_orders_id'] && $result['complete']!=1) ) {
					?>
						<tr><td colspan="2" align="center"><hr size="1" width="70%"></td></tr>
						<tr>
							<td align="right">Payment Type:</td>
							<td>
								<?php
									echo displayPayOptions('pay_type');
								?>
							</td>
						</tr>
						<tr>
							<td align="right">PO Number:</td>
							<td>
								<input type="text" name="po" value="<?php echo $result['po'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">Card Type:</td>
							<td>
								<?php
									$types = array(	array("","Select Card Type"),
													array('mc','MasterCard'),
													array('vi','Visa'),
													array('am','American Express'),
													array('di','Discover'));
									print makeSelectBox("cc_type",$types,$result['cc_type']);
								?>
							</td>
						</tr>
						<tr>
							<td align="right">Card First Name:</td>
							<td>
								<input type="text" name="cc_first_name" value="<?php echo $result['cc_first_name'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">Card Last Name:</td>
							<td>
								<input type="text" name="cc_last_name" value="<?php echo $result['cc_last_name'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">Card Number:</td>
							<td>
								<input type="text" id="cc_num" name="cc_num" value="<?php echo $result['cc_num'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">CID:</td>
							<td>
								<input type="text" name="cid" value="<?php echo $result['cid'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">Expire Date:</td>
							<td>
								Month: <?php print makeCCMonths('cc_exp_m',$result['cc_exp_m']);?>&nbsp;
								Year: <?php print makeCCYears('cc_exp_y',$result['cc_exp_y']);?>
							</td>
						</tr>
						<tr>
							<td align="right">Authorization Code:</td>
							<td>
								<input type="text" name="cc_auth_code" value="<?php echo $result['cc_auth_code'];?>" size="45">
							</td>
						</tr>
						<tr>
							<td align="right">Transaction ID:</td>
							<td>
								<input type="text" id="cc_trans_id" name="cc_trans_id" value="<?php echo $result['cc_trans_id'];?>" size="45">
							</td>
						</tr>
					<?php 
					}
					if(isset($result['funds_received'])):?>
					<tr>
						<td align="right">Funds Received:</td>
						<td>
							<input type="text" name="funds_received" id="funds_received" value="<?=$funds_received?>" class="datepicker" />
							<?php
							//@list($date,$time) = explode(" ",$result['funds_received']);
							//@list($y,$m,$d) = explode("-",$date);
							//print makeCalendar('funds_received[month]','funds_received[day]','funds_received[year]',$m,$d,$y);
							?>
						</td>
					</tr>
					<?php endif;?>

					<?php if(!empty($cc_processor) && $cc_processor['company'] == '1' && $result['cc_trans_id'] != ""):?>
					<tr>
						<td align="right" style="vertical-align:middle">Refund:</td>
						<td style="padding-left: 10px;">
							Refund Amount: <input type="text" id="refund_amount" name="refund_amount" size="5" onBlur="if(this.value != ''){this.value = parseFloat(this.value).toFixed(2);}" />&nbsp;
							<input type="button" value="Submit Refund" id="refund_btn" onClick="doRefund();">
						</td>
					</tr>
					<?php endif;?>
				</table>
			</td>
			<td>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td align="right">Ship Name:</td>
						<td>
							<input type="text" name="ship_name" value="<?php echo $result['ship_name'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Ship Address 1:</td>
						<td>
							<input type="text" name="ship_address1" value="<?php echo $result['ship_address1'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Ship Address 2:</td>
						<td>
							<input type="text" name="ship_address2" value="<?php echo $result['ship_address2'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Ship City:</td>
						<td>
							<input type="text" name="ship_city" value="<?php echo $result['ship_city'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Ship State:</td>
						<td>
							<?php
								$states[0] = array('code'=>"",'name'=>"");
								$data = $db->getRecords("SELECT `code`,`name` FROM states WHERE status = '1' ORDER BY `name`");
								$states = array_merge($states,$data);
								print makeSelectBox("ship_state",$states,$result['ship_state']);
							?>
						</td>
					</tr>
					<tr>
						<td align="right">Ship Zip:</td>
						<td>
							<input type="text" name="ship_zip" value="<?php echo $result['ship_zip'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Ship Country:</td>
						<td>
							<?php
								$data = $db->getRecords("SELECT `code`,`name` FROM countries WHERE status = '1'");
								print makeSelectBox("ship_country",$data,$result['ship_country']);
							?>
						</td>
					</tr>
					<tr>
						<td align="right">Ship Phone:</td>
						<td>
							<input type="text" name="ship_phone" value="<?php echo $result['ship_phone'];?>" size="45">
						</td>
					</tr>
					<tr>
						<td align="right">Delivery:</td>
						<td>
							<input type="text" name="delivery" value="<?php echo $result['delivery'];?>" size="45">
						</td>
					</tr>

					<tr>
						<td align="right">Ship Method:</td>
						<td>
							<?php
								if ( $receiptTable=='wholesale_receipts' ) {
									$shipQry = "SELECT name as name1, name FROM ship_method_wholesale WHERE active='1'";
								} else {
									$shipQry = "SELECT name as name1, name FROM ship_method WHERE active='1'";
								}
								$shipMethods = $db->GetRecords($shipQry);

								print makeSelectBox("shipping_method",$shipMethods,$result['shipping_method']);

							?>
						</td>
					</tr>
					<tr><td colspan="2" align="center"><hr size="1" width="70%"></td></tr>
					<tr>
						<td align="right" rowspan="3">Notes:</td>
						<td rowspan="3">
							<textarea name="notes" cols="35" rows="10"><?php echo $result['notes'];?></textarea>
						</td>
					</tr>

				</table>

			</td>
		</tr>
		<tr><td colspan="2" align="left">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="left">
				<h3>Line Items:</h3>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center">
				<table id="line-items" border="1" cellpadding="3" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Delete</th>
							<th>SKU</th>
							<th>Description</th>
							<th>Original Price</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Total</th>
							<th>Shipper</th>
							<th>Tracking Number</th>
						</tr>
					</thead>
					<tbody
						<?php foreach($result['items'] as $i=>$item):?>
						<tr>
							<td>
								<input type="hidden" name="<?php echo $receiptItemFld;?>[]" value="<?php echo $item[$receiptItemFld];?>"/>
								<input type="checkbox" name="delete[]" value="true" onclick="deleteRow(this);" />
							</td>
							<td>
								<input class="txtcenter" type="text" size="5" name="sku[]" value="<?php echo $item['sku'];?>" />
							</td>
							<td>
								<input type="text" size="45" name="name[]" value="<?php echo $item['name'];?>" />
							</td>
							<td>
								<input class="txtright" type="text" size="8" name="orig_price[]" value="<?php echo number_format($item['orig_price'],2,'.','');?>" />
							</td>
							<td>
								<input class="txtright" type="text" size="3" name="quantity[]" value="<?php echo $item['quantity'];?>" onChange="setLineTotal(this);" />
							</td>
							<td>
								<input class="txtright" type="text" size="8" name="price[]" value="<?php echo number_format($item['price'],2,'.','');?>" onChange="setLineTotal(this);" />
							</td>
							<td>
								<input class="txtright" type="text" size="8" name="line_total[]" value="<?php echo number_format(($item['price'] * $item['quantity']),2,'.','');?>" />
							</td>
							<td>
								<input class="txtcenter" type="text" size="10" name="shipper[]" value="<?php echo $item['shipper'];?>" />
							</td>
							<td>
								<input class="txtcenter" type="text" size="30" name="tracking_num[]" value="<?php echo createTrackingNumberLink($item['shipper'],$item['tracking_num']);?>" />
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="6" style="text-align:right">Subtotal: </td>
							<td style="text-align:right">
								<input class="txtright" type="text" size="8" name="subtotal" id="subtotal" value="<?php echo number_format($result['subtotal'],2,'.','');?>" />
							</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="6" style="text-align:right">Tax: </td>
							<td style="text-align:right">
								<input class="txtright" type="text" size="8" name="tax" id="tax" value="<?php echo $result['tax'];?>" onchange="setTotals();" />
							</td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="6" style="text-align:right">Shipping: </td>
							<td style="text-align:right">
								<input class="txtright" type="text" size="8" name="shipping" id="shipping" value="<?php echo $result['shipping'];?>" onchange="setTotals();" />
							</td>
							<td colspan="2">&nbsp;</td>
						</tr>

						<?php if(isset($refunds['refund_total'])):?>
						<tr>
							<td colspan="6" style="text-align:right">Total Refunds: </td>
							<td style="text-align:right"><?php echo $refunds['refund_total'];?> </td>
							<td colspan="2">&nbsp;</td>
						</tr>
						<?php endif;?>

						<tr>
							<td colspan="6" style="text-align:right"><b>Total:</b> </td>
							<td style="text-align:right">
								<input class="txtright" type="text" size="8" name="total" id="total" value="<?php echo $result['total'];?>" />
							</td>
							<td colspan="2">&nbsp;</td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>

	</table>
	<div style="padding-top:20px;text-align:center;">
		<input type="button" id="add-row" name="add-row" value=" Add a Row " onclick="addRow();"> &nbsp;
		<input type="submit" name="update" value="Update Order"><br /><br />
		<a href="orders.index.php" style="font-size:14px;font-weight:bold;">Go back to order list</a>
	</div>
	</form>


	<div id="bottom-spacer" style="height:200px;">&nbsp;<?php //echo print_d($result);?></div>

	<div id="product-select" style="display:none;">
		<select name="name[]" ID="name[]" onChange="populateFields(this);">
			<option value="">Select Product</option>
			<?php foreach($products as $i=>$line):?>
				<?php $tmp_name_val = $line['sku'] ."|". $line['name'] . "|" . $line['cost']; ?>
				<option value="<?php echo $tmp_name_val;?>"><?php echo $line['name'];?></option>
			<?php endforeach;?>
		</select>
	</div>

</div>

<?php include './includes/foot_admin1.php'; mysql_close($dbh); ?>
</body>
</html>
