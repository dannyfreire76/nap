<?php
//Complete order for member page

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include_once('../includes/main1.php');
include_once('../includes/wc1.php');
include_once('../includes/common.php');
include_once('../includes/customer.php');
include_once('../includes/store_includes.php');
include_once('../includes/admin_orders_util.php');
include_once($base_path.'includes/authorize.net.php');

//set submitted variables to simple var names with global scope
foreach( $_POST as $n=>$v ){
	$$n = $v;
}
$member_id=$_REQUEST["member_id"];
$receipt_id = $_REQUEST["receipt_id"];

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}
$query = "SELECT product_line FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';

$query="SELECT * FROM members WHERE member_id=".$member_id." LIMIT 1";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	foreach($line as $col=>$val) {
		$$col = $val;
	}
}
$queryRec = "SELECT * FROM receipts WHERE receipt_id='".$receipt_id."' AND member_id='".$member_id."'";
$resultRec = mysql_query($queryRec) or die("queryRec in members_place_order2 failed : " . mysql_error());
while ($lineRec = mysql_fetch_array($resultRec, MYSQL_ASSOC)) {
	$recTotal = $lineRec["total"];
}
mysql_free_result($resultRec);

//set submitted variables to simple var names with global scope
foreach( $_POST as $n=>$v ){
	$$n = $v;
}

if ($submit_order != "") {

	//Validate
	$error_txt = "";
	if($bill_name == "") { $error_txt .= "You must enter the Billing Name for this Member.<br>\n"; }
	if($bill_address1 == "") { $error_txt .= "You must enter the Billing Address for this Member.<br>\n"; }
	if($bill_city == "") { $error_txt .= "You must enter the Billing City for this Member.<br>\n"; }
	if($bill_country == "") { $error_txt .= "You must enter the Billing Country for this Member.<br>\n"; }
	if($bill_country=='US') {
        if($bill_state == "") { $error_txt .= "You must enter the Billing State for this Member.<br>\n"; }
        if($bill_zip == "") { $error_txt .= "You must enter the Billing Zip/Postal Code for this Member.<br>\n"; }
    }
	if($ship_name == "") { $error_txt .= "You must enter the Shipping Name for this Member.<br>\n"; }
	if($ship_address1 == "") { $error_txt .= "You must enter the Shipping Address for this Member.<br>\n"; }
	if($ship_city == "") { $error_txt .= "You must enter the Shipping City for this Member.<br>\n"; }
	if($ship_country == "") { $error_txt .= "You must enter the Shipping Country for this Member.<br>\n"; }
    if($ship_country=='US') {
        if($ship_state == "") { $error_txt .= "You must enter the Shipping State for this Member.<br>\n"; }
        if($ship_zip == "") { $error_txt .= "You must enter the Shipping Zip/Postal Code for this Member.<br>\n"; }
    }

	if($pay_type == "") { $error_txt .= "You must select a Payment Type.<br>\n"; }

	if($pay_type == "cc" && $newOrOldCC=='old' && !$payment_profile_id) {  $error_txt .= "You must select an existing Credit Card.<br>\n"; }

	if($pay_type == "cc" && !$newOrOldCC) {  $error_txt .= "You must select an existing Credit Card or enter a new one.<br>\n"; }

	if($pay_type == "cc" && $newOrOldCC == "new") {
		if($cc_type == "") { $error_txt .= "You must enter the Type of Credit Card for this Member.<br>\n"; }
		if($cc_first_name == "") { $error_txt .= "You must enter the First Name on the Credit Card for this Member.<br>\n"; }
		if($cc_last_name == "") { $error_txt .= "You must enter the Last Name on the Credit Card for this Member.<br>\n"; }
		if($cc_num == "") { $error_txt .= "You must enter the Credit Card Number on the Credit Card for this Member.<br>\n"; }
		if($cid == "") { $error_txt .= "You must enter the Security Code on the Credit Card for this Member.<br>\n"; }
		if($cc_exp_m == "") { $error_txt .= "You must enter the Expiration Date Month on the Credit Card for this Member.<br>\n"; }
		if($cc_exp_y == "") { $error_txt .= "You must enter the Expiration Date Year on the Credit Card for this Member.<br>\n"; }
	}

	if($error_txt == "") {
		$order_number = getNextUserID();
		$cc_trans_id = "";

		if($pay_type == "cc") {
			getMerchantCreds();//authorize.net.php

			if($company == 1) {//Send to Authorize.net Merchant Account for Verification
				if ( $newOrOldCC == "new") {
					$authOrCapture = 'AUTH_CAPTURE';
					if ( $recurringChk ) {//recurring orders aren't charged here, but in promoteNextRecurringOrders, admin_orders_util.php
						$authOrCapture = 'AUTH_ONLY';
					}
					$transResults = postAuthorizeTrans($authOrCapture, $merchant_url, $merchant_username, $merchant_password, $total, $cc_num, $cc_exp_m, $cc_exp_y, $cid, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_trans_id);

					$cc_auth_code = $transResults[0];
					$cc_trans_id = $transResults[1];
					$error_txt .= $transResults[2];
					
				} else {//existing payment profile used
					$authOrCapture = 'profileTransAuthCapture';
					if ( $recurringChk ) {//recurring orders aren't charged here, but in promoteNextRecurringOrders, admin_orders_util.php
						$authOrCapture = 'profileTransAuthOnly';
					}
					$profileTransResp = createCustomerProfileTransactionRequest($customer_profile_id, $payment_profile_id, $total, $authOrCapture, $order_number, $cc_auth_code, $cc_trans_id);
					if ( strtolower($profileTransResp->messages->resultCode)!="ok" ) {
						$error_txt .= "There was a problem submitting this transaction: ".$profileTransResp->messages->message->text."<br>\n";
					} else {
							$directResponse = explode(",", $profileTransResp->directResponse);
							$cc_auth_code = $directResponse[4];
							$cc_trans_id = $directResponse[6];
					}
				}

				if($error_txt == "") {
					$tranProfileResults = createTransactionProfiles($customer_profile_id, $payment_profile_id, $email, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y, $cc_exp_m, $bill_phone);

					$customer_profile_id = $tranProfileResults[0];
					$payment_profile_id = $tranProfileResults[1];
				}
			} else if($company == 2) {
				//Send to Sage Payments Merchant Account for Verification
				$c = curl_init($merchant_url);
				curl_setopt($c, CURLOPT_HEADER, 0);
				curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

				if ( $bill_state=='') {
				   $tmp_bill_state = '  ';
				}
				else {
					$tmp_bill_state = $bill_state;
				}

				if ( $bill_zip=='') {
				   $tmp_bill_zip = '11111';
				}
				else {
					$tmp_bill_zip = $bill_zip;
				}

				if ( $cid=='0' || strlen($cid)<3 ) {
				   $tmp_cid = '';
				}
				else {
					$tmp_cid = '&C_cvv=' . urlencode($cid);
				}
				curl_setopt($c, CURLOPT_POSTFIELDS, 'M_id='.$merchant_username.'&M_key='.$merchant_password.'&C_name=' . urlencode($cc_first_name) . urlencode(' ') . urlencode($cc_last_name) . '&C_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&C_city=' . urlencode($bill_city) . '&C_state=' . urlencode($tmp_bill_state) . '&C_zip=' . urlencode($tmp_bill_zip) . '&C_country=' . urlencode($bill_country) . '&C_email=' . urlencode($email) . '&C_cardnumber=' . urlencode($cc_num) . '&C_exp=' . urlencode($cc_exp_m) . urlencode(substr($cc_exp_y, 2, 2)) . '&T_amt=' . urlencode($total) . '&T_code=' . '01' . '&T_ordernum=' . urlencode($receipt_id) . $tmp_cid);
				$page = urldecode(curl_exec($c));
				if(curl_errno($c)) {
					$error_txt .= curl_error($c);
				}
				curl_close($c);

				// Debug Merchant Account
				//$error_txt .= "Results From Merchant Acct: " . $page . "<br>\n";

				//$page = substr($page, 1);
				//$page2 = explode("\"|\"", $page);

				if($page[1] != "A") {
					$error_txt .= "Error, There was a problem with the credit card information you entered, because \"" . substr($page, 8, 32) . "\"<br>\n";
				} else {
					$cc_auth_code = substr($page, 2, 6);
					$cc_trans_id = substr($page, 46, 10);
				}
			}

		}
	}

	if($error_txt == "") {
		$upOrInsQuery = "UPDATE members SET status='1', ";
		
		$upOrInsQuery .= " nickname='$nickname', first_name='$first_name', last_name='$last_name', email='$email', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', ship_name='";
		$upOrInsQuery .= $ship_name;
		$upOrInsQuery .= "', ship_address1='";
		$upOrInsQuery .= $ship_address1;
		$upOrInsQuery .= "', ship_address2='";
		$upOrInsQuery .= $ship_address2;
		$upOrInsQuery .= "', ship_city='";
		$upOrInsQuery .= $ship_city;
		$upOrInsQuery .= "', ship_state='";
		$upOrInsQuery .= $ship_state;
		$upOrInsQuery .= "', ship_zip='";
		$upOrInsQuery .= $ship_zip;
		$upOrInsQuery .= "', ship_country='";
		$upOrInsQuery .= $ship_country;
		$upOrInsQuery .= "', ship_phone='";
		$upOrInsQuery .= $ship_phone ."'";

		if ( $customer_profile_id ) {
			$upOrInsQuery .= ", customer_profile_id='".$customer_profile_id."'";
		}
		$resultUpOrIns = mysql_query($upOrInsQuery." WHERE member_id=$member_id") or die("Update members query failed: " . mysql_error().'<br /><br />'.$upOrInsQuery);


		//force member duplication/update on partner sites
		$querySites = "SELECT * FROM partner_sites WHERE site_url!='".$_SERVER["HTTP_HOST"]."'";
		$resultSites = mysql_query($querySites) or die("Query 2 failed: " . mysql_error());
		while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
			$thisDBHName = "dbh".$lineSites["site_key_name"];
			global $$thisDBHName;
			$thisHandle = $$thisDBHName;

			$member_email_test = check_dup_email($email, $thisHandle);

			if($member_email_test > 0) {//already exists so update with same info as referring site
				$updateQ = $upOrInsQuery." WHERE email='$email'";
				$resultQ = mysql_query($updateQ, $thisHandle) or die("Update Query failed : " . mysql_error().'<br /><br />'.$updateQ);

			} else {//no matching email in this site, so create new member record with same info as referring site
				duplicateMember($dbh, $email, $thisHandle);
			}
		}

		$recurrEmailStr = "";

		//START CREATE RECURRING ORDERS
		if ( $recurringChk ) {
			$hdrRecurSql = "SELECT * FROM recurring_orders WHERE ".
										" member_id = '".$member_id."' AND ".
										" original_receipt_id = '".$receipt_id."'";

			$resultHdr = mysql_query($hdrRecurSql) or die("hdrRecurSql failed : " . mysql_error() ."<br /><br />".$hdrRecurSql);

			if ( mysql_num_rows($resultHdr)==0 ) {//only write once
				$final_order = null;

				if ( $recurring_length ) {
					$final_order = date('Y-m-d', strtotime("+".$recurring_length));
				}

				$now = date("Y-m-d");

				$createRecurSql = "INSERT INTO recurring_orders SET ".
									" member_id = '".$member_id."', ".
									" recurring_created='".$now."', ".
									" recurring_active='1', ".
									" final_order = '".$final_order."', ".
									" recurring_length = '".$recurring_length."', ".
									" recurring_interval = '".$recurring_interval."', ".
									" original_receipt_id = '".$receipt_id."', ".
									" rotating_products = '".$rotating_products."', ".
									" recurring_begin = '".date("Y-m-d",strtotime($ordered) )."',".
									" payment_profile_id = '".$payment_profile_id."'";

				$resultRecurSql = mysql_query($createRecurSql) or die("createRecurSql failed : " . mysql_error() ."<br /><br />".$createRecurSql);

				if ( $resultRecurSql ) {//recurring_orders record written successfully
					$recurring_orders_id = mysql_insert_id();
				}
			}
		}
		//END CREATE RECURRING ORDERS


		$query = "UPDATE receipts SET ";
		$query .= " bill_name='".$bill_name."',";
		$query .= " bill_address1='".$bill_address1."',";
		$query .= " bill_address2='".$bill_address2."',";
		$query .= " bill_city='".$bill_city."',";
		$query .= " bill_state='$bill_state',";
		$query .= " bill_zip='$bill_zip',";
		$query .= " bill_country='$bill_country',";
		$query .= " bill_phone='$bill_phone',";
		$query .= " bill_email='$email',";
		$query .= " ship_name='".$ship_name."',";
		$query .= " ship_address1='".$ship_address1."',";
		$query .= " ship_address2='".$ship_address2."',";
		$query .= " ship_city='".$ship_city."',";
		$query .= " ship_state='".$ship_state."',";
		$query .= " ship_zip='$ship_zip',";
		$query .= " ship_country='$ship_country',";
		$query .= " ship_phone='$ship_phone',";
		$query .= " delivery='".$delivery."',";
		$query .= " notes='".$notes."',";
		$query .= " pay_type='$pay_type',";
		$query .= " cc_type='$cc_type',";
		$query .= " cc_first_name='".$cc_first_name."',";
		$query .= " cc_last_name='".$cc_last_name."',";
		$query .= " order_processed_by='".$order_processed_by."',";
		if ( $pay_type=='cc' && $newOrOldCC=='new' ) {
			$query .= " cc_num='"."XXXXXXXXXXXX".substr($cc_num, -4)."',";
		}
		else {
			$query .= " cc_num='',";
		}
		$query .= " cid='$cid',";
		$query .= " cc_exp_m='$cc_exp_m',";
		$query .= " cc_exp_y='$cc_exp_y',";
		$query .= " cc_auth_code='$cc_auth_code',";

		if($cc_trans_id != ""){
			$query .= " cc_trans_id='$cc_trans_id',";
		}

		if ( $pay_type=='cc' && $newOrOldCC=='old' && $payment_profile_id ) {
			$query .= " payment_profile_id='$payment_profile_id',";
		}

		if($recurring_orders_id != ""){//recurring orders are not marked complete and may have a different order date
			$query .= " recurring_orders_id='$recurring_orders_id',";
			$query .= " ordered='".date("Y-m-d H:i:s",strtotime($ordered) )."',";
		} else {
			$query .= " complete='1',";
		}

		$query .= "  user_id='$order_number' ";
		$query .= " WHERE receipt_id='$receipt_id'";
		$result = mysql_query($query) or die("Query failed 1 : " . mysql_error().'<br /><br />'.$query);

		checkInventory($receipt_id);

		if($recurring_orders_id != ""){//initial recurring order is written
			$success_str = '<br /><a href="recurring_orders_edit.php?edit=1&recurring_orders_id='.$recurring_orders_id.'"  title="edit recurring order">Recurring Order #'.$recurring_orders_id.'</a> established for <a href="members_admin2_edit.php?member_id='.$member_id.'"  title="edit member">'.$bill_name.'</a>';

			//process any recurring orders that were just created
			$recurringMsg = promoteNextRecurringOrders();
			
			if ( $recurringMsg!="" ) {
				$success_str .= '<br /><br />Recurring Orders settled and confirmation emails sent:<div style="padding-left:40px; margin-top: -15px;">'.$recurringMsg.'</div>';
			}
		} else {			
			$email_str = sendConfirmationEmail($email, $site_email, $receipt_id, $order_number, $company_phone, $website_title, $company_name, $company_address, $company_city_state_zip, $product_name, $total);
		}

	}

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>MyBWMS @ <?php echo $website_title.": Complete Member Order" ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
    <style type="text/css" media="all">
	.hidden {
		display:none;
	}
    </style>
    <script type="text/javascript" src="/includes/jquery.js"></script>
    <script type="text/javascript" src="../includes/_.jquery.js"></script>
    <script type="text/javascript" src="../includes/_.date.js"></script>
    <script type="text/javascript" src="/includes/wmsform.js"></script>
	<script type="text/javascript">
		$(function() { //on doc ready
			togglePayType();
			$('#pay_type').change(function(){ togglePayType(); });

			toggleRecurringOptions();
			$('#recurringChk').click( function(){ toggleRecurringOptions(); });
			
			if ( $(':input[@name=newOrOldCC]').size() > 1  ) {
				$('#newCCOptions').hide();
				$('#oldCCOptions').hide();

				toggleNewOldCC();
				$(':input[@name=newOrOldCC]').click( function(){ toggleNewOldCC(); });

				$('#payment_profile_id').change(function(){
					setBilling();	
				});

				$('#newCC').click(function(){
					$('#payment_profile_id').val('');
					setBilling();	
				});

			}

			$(':input:visible').each(function(){
				$(this).attr( 'origVal', $(this).val() );//store what the field originally had in an attribute
			});
		});

		function setBilling() {
			var thisProfileVal = $('#payment_profile_id').val();

			if ( !thisProfileVal ) {
				thisProfileVal = 'default';
			}

			$('div[@payID='+thisProfileVal+'] > div').each(function(){
				var thisFldVal = $(this).text();
				var thisFldName = $(this).attr('fldName');

				$(':input[@name='+thisFldName+']').val( thisFldVal )

				if ( thisProfileVal=='default' ) {
					$(':input[@name='+thisFldName+']')
							.removeClass('pretty_disabled')
							.unbind('focus')
					
					$('#useship').removeAttr('disabled');
				} else {
					$(':input[@name='+thisFldName+']')
						.addClass('pretty_disabled')
						.focus( function(){$(this).blur()} )
					
					$('#useship').attr('disabled', 'disabled');
				}
			});
		}

		function toggleNewOldCC() {
			if ( $('#oldCC').is(':checked') ) {
				if ( $('#oldCCOptions').is(':hidden') ) {
					$('#oldCCOptions').slideDown(200);
				}

				if ( $('#newCCOptions').is(':visible') ) {
					$('#newCCOptions').slideUp(200);
				}
			} else if ( $('#newCC').is(':checked') ) {
				if ( $('#newCCOptions').is(':hidden') ) {
					$('#newCCOptions').slideDown(200);
				}

				if ( $('#oldCCOptions').is(':visible') ) {
					$('#oldCCOptions').slideUp(200);
				}
			}
		}

		function togglePayType() {
			if ( $('#pay_type').val()=='cc' ) {
				if ( $('#ccInfoTable').is(':hidden') ) {
					$('#ccInfoTable').slideDown(300);
				}
			} else {
				$('#payment_profile_id').val('');
				setBilling();

				if ( $('#ccInfoTable').is(':visible') ) {
					$('#ccInfoTable').slideUp(300);
				}
			}
		}

		function toggleRecurringOptions() {
			if ( $('#recurringChk').is(':checked') ) {
				if ( $('#recurringOptions').is(':hidden') ) {
					$('#recurringOptions').slideDown(300);
				}
			} else {
				if ( $('#recurringOptions').is(':visible') ) {
					$('#recurringOptions').slideUp(300);
				}
			}
		}

		function useOther(useThis) {
			replaceThis = 'ship';

			if ( useThis=='ship' ) {
				replaceThis = 'bill';
			}

			$(':input:visible[@name*='+replaceThis+'_]').each(function(){
				var replaceName = $(this).attr('name').replace(replaceThis, useThis);
				var replacement = $(':input:visible[@id='+replaceName+']').val();
			
				if ( !replacement ) {
					replacement = '';
				}

				$(this).val( replacement );
			});
		}
	</script>

	<style type="text/css">
	 .pretty_disabled {
		 background-color: #eeeeee;
		 border: 1px solid #e0e0e0;
		 color: navy
	}
	</style>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<br />
<a href="members_admin2.php" class="left">&lt;&lt; Return to Members Homepage</a>
<br />

<?php
	if ( !$error_txt && $submit_order && $email_str ) {//order completed successfully
		echo '<br />';
		echo '<div class="text_left">';
			echo 'Order number <b><a href="orders.detail.php?edit=1&id='.$receipt_id.'&retail=1"  title="edit order">'.$order_number.'</a></b> completed successfully for <a href="members_admin2_edit.php?member_id='.$member_id.'"  title="edit member">'.$bill_name.'</a>. ';
		
		echo 'The following email has been sent to <a href="mailto:'.$email.'">'.$email.'</a>:<br /><br />';
			echo '<span style="font-style: italic">';
			echo str_replace("\n", "<br />", $email_str);
			echo '</span>';
		echo '</div>';
	} else if ( !$error_txt && $submit_order && $success_str ) {//order completed successfully
		echo '<br />';
		echo '<div class="text_left">';
			echo $success_str;
		echo '</div>';
	
	} else {
?>
		<table border="0" width="97%">

		<tr><td>&nbsp;</td></tr>

		<tr><td align="left">Complete this order for <b><?=$_SESSION["member_name"] ?></b>.</td></tr>

		<tr><td>&nbsp;</td></tr>

		<?php
		//Error Messages
		if($error_txt) {
			echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
		}
		?>

		<form name="form1" action="./members_place_order2.php" method="POST" class="wmsform">
		<input class="hidden" name="receipt_id" value="<?php echo $receipt_id; ?>">
		<input class="hidden" name="user_gets_commission" value="<?php echo $user_gets_commission; ?>">
		<!--<input class="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">-->
		<tr><td align="left"><table border="0">

		<tr valign="top">
		<td rowspan="2">
			<fieldset><legend>Payment Information</legend>
			<table>
			<tr><label><td colspan="2">Payment Type: 
			<?php
				echo displayPayOptions('pay_type', $secure_funds_only);	
			?>
			</td></label></tr>
			<tr><td colspan="2">
				<table id="ccInfoTable">
					<tr>
						<td colspan="2">
							<?php
								getMerchantCreds();//authorize.net.php
								if ( $company == 1 ) {
									if ( $customer_profile_id ) {

										echo '<input type="hidden" name="customer_profile_id" value="'.$customer_profile_id.'" />';
										$completeProfileRequest = getCustomerProfileRequest( $customer_profile_id );//get all payment profiles

										if ( count($completeProfileRequest->profile->paymentProfiles) > 0 ) {
											echo '<input type="radio" name="newOrOldCC" id="oldCC" value="old" '.($newOrOldCC=='old'?'checked="checked"': '').' /><label for="oldCC"> Card on file</label><br />';
											echo '<div id="oldCCOptions"><select name="payment_profile_id" id="payment_profile_id">';
											echo '<option val=""></option>';

											$payProfileStr = "";

											$payProfileStr .= '<div payID="default" style="display:none">';
											$payProfileStr .=	'<div fldName="bill_name">'.$bill_name.'</div>';
											$payProfileStr .=	'<div fldName="bill_address1">'.$bill_address1.'</div>';
											$payProfileStr .=	'<div fldName="bill_address2">'.$bill_address2.'</div>';
											$payProfileStr .=	'<div fldName="bill_city">'.$bill_city.'</div>';
											$payProfileStr .=	'<div fldName="bill_state">'.$bill_state.'</div>';
											$payProfileStr .=	'<div fldName="bill_zip">'.$bill_zip.'</div>';
											$payProfileStr .=	'<div fldName="bill_country">'.$bill_country.'</div>';
											$payProfileStr .=	'<div fldName="bill_phone">'.$bill_phone.'</div>';
											$payProfileStr .=	'<div fldName="email">'.$email.'</div>';
											$payProfileStr .= '</div>';

											foreach ( $completeProfileRequest->profile->paymentProfiles as $aProfile ) {
												echo '<option value="'.$aProfile->customerPaymentProfileId.'"';
												if ( $payment_profile_id==$aProfile->customerPaymentProfileId ) { echo ' selected '; }
												echo ' >'.$aProfile->billTo->firstName.' '.$aProfile->billTo->lastName.', '.$aProfile->billTo->address.' - '.$aProfile->payment->creditCard->cardNumber.'</option>';

												$payProfileStr .= '<div payID="'.$aProfile->customerPaymentProfileId.'" style="display:none">';
												$payProfileStr .=	'<div fldName="bill_name">'.$aProfile->billTo->firstName.' '.$aProfile->billTo->lastName.'</div>';
												$payProfileStr .=	'<div fldName="bill_address1">'.$aProfile->billTo->address.'</div>';
												$payProfileStr .=	'<div fldName="bill_address2"></div>';
												$payProfileStr .=	'<div fldName="bill_city">'.$aProfile->billTo->city.'</div>';
												$payProfileStr .=	'<div fldName="bill_state">'.$aProfile->billTo->state.'</div>';
												$payProfileStr .=	'<div fldName="bill_zip">'.$aProfile->billTo->zip.'</div>';
												$payProfileStr .=	'<div fldName="bill_country">'.$aProfile->billTo->country.'</div>';
												$payProfileStr .=	'<div fldName="bill_phone">'.$aProfile->billTo->phoneNumber.'</div>';
												$payProfileStr .=	'<div fldName="email">'.$completeProfileRequest->profile->email.'</div>';
												$payProfileStr .= '</div>';
											}
											echo '</select></div>';
											echo $payProfileStr;
										}
									}
								}
							?>
						</td>
					</tr>
					<?php
						if ( count($completeProfileRequest->profile->paymentProfiles) > 0 ) {
							echo '<tr><td colspan="2"><input type="radio" name="newOrOldCC" id="newCC" value="new" '.($newOrOldCC=='new'?'checked="checked"': '').' /><label for="newCC"> New card</label></td></tr>';
						} else {
							echo '<input type="hidden" name="newOrOldCC" value="new" />';
						}
					?>
					<tr><td colspan="2">
						<table id="newCCOptions"><tr><td>
							Credit Card Type:</td><td><select name="cc_type">
							<option value=""></option>
							<option <?php if($cc_type == 'vi') { echo " SELECTED"; } ?> value="vi">Visa</option>
							<option <?php if($cc_type == 'mc') { echo " SELECTED"; } ?> value="mc">Mastercard</option>
							<option <?php if($cc_type == 'am') { echo " SELECTED"; } ?> value="am">American Express</option>
							<option <?php if($cc_type == 'di') { echo " SELECTED"; } ?> value="di">Discover</option>
							<option value="jc" disabled="disabled">JCB</option>
							<option value="dc" disabled="disabled">Diners Club</option>
							</select></td></label></tr>
							<tr><label><td>First Name on Credit Card:</td><td><input name="cc_first_name" id="cc_first_name" value="<?=$cc_first_name?>" /></td></label></tr>
							<tr><label><td>Last Name on Credit Card:</td><td><input name="cc_last_name" id="cc_last_name" value="<?=$cc_last_name?>" /></td></label></tr>
							<tr><label><td>Credit Card Number:</td><td><input name="cc_num" id="cc_num" value="<?=$cc_num?>" /></td></label></tr>
							<tr><label><td>Security Code:</td><td><input name="cid" id="cid" size="4" value="<?=$cid?>" /></td></label></tr>
							<tr><label><td>Expiration Date:</td><td><SELECT name="cc_exp_m">
							<option value=""></option>
							<option <?php if($cc_exp_m == '01') { echo " SELECTED"; } ?> value="01">January - 01</option>
							<option <?php if($cc_exp_m == '02') { echo " SELECTED"; } ?> value="02">February - 02</option>
							<option <?php if($cc_exp_m == '03') { echo " SELECTED"; } ?> value="03">March - 03</option>
							<option <?php if($cc_exp_m == '04') { echo " SELECTED"; } ?> value="04">April - 04</option>
							<option <?php if($cc_exp_m == '05') { echo " SELECTED"; } ?> value="05">May - 05</option>
							<option <?php if($cc_exp_m == '06') { echo " SELECTED"; } ?> value="06">June - 06</option>
							<option <?php if($cc_exp_m == '07') { echo " SELECTED"; } ?> value="07">July - 07</option>
							<option <?php if($cc_exp_m == '08') { echo " SELECTED"; } ?> value="08">August - 08</option>
							<option <?php if($cc_exp_m == '09') { echo " SELECTED"; } ?> value="09">September - 09</option>
							<option <?php if($cc_exp_m == '10') { echo " SELECTED"; } ?> value="10">October - 10</option>
							<option <?php if($cc_exp_m == '11') { echo " SELECTED"; } ?> value="11">November - 11</option>
							<option <?php if($cc_exp_m == '12') { echo " SELECTED"; } ?> value="12">December - 12</option>
							</select>
							<select name="cc_exp_y">
							<option value=""></option>
							<?php
								for ($x=date('Y'); $x<=date('Y')+10; $x++) {
									echo '<option value="'.$x.'"';
									if( $cc_exp_y == $x ) {
										echo ' selected';
									}
									echo '>'.$x.'</option>';
								}
							?>
							</select>
							</td></label></tr>
						</table>
						</td></tr>
					</table>
				</td></tr>
				</table></fieldset>
				
				
				<fieldset><legend>Billing Information &#160;&#160;&#160;<button id="useship" onClick="useOther('ship'); return false;" />use Shipping Information</button></legend>
				<table>
				<tr><label><td>Name:</td><td><input type="text" name="bill_name" id="bill_name" value="<?=$bill_name?>" /></td></label></tr>
				<tr><label><td>Address1:</td><td><input name="bill_address1" id="bill_address1" value="<?=$bill_address1?>" /></td></label></tr>
				<tr><label><td>Address2:</td><td><input name="bill_address2" id="bill_address2" value="<?=$bill_address2?>" /></td></label></tr>
				<tr><label><td>City:</td><td><input name="bill_city" id="bill_city" value="<?=$bill_city?>" /></td></label></tr>
				<tr><label><td>State/Province:</td><td><select name="bill_state" id="bill_state">
				<option value="">Select a state</option>
				<?php
				//$query = "SELECT * FROM states WHERE status='1'";
				$query = "SELECT * FROM states";
				//print_d(array(__FILE__,__LINE__,$query));
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					//print_d($line,true);
					echo '<option value="'.$line["code"].'"';
					if($bill_state == $line["code"]) { echo " SELECTED"; }
					echo '>'.$line["name"].'</option>';
				}

				?>
				</select></td></label></tr>
				<tr><label><td>Zip/Postal Code:</td><td><input name="bill_zip" id="bill_zip" value="<?=$bill_zip?>" /></td></label></tr>
				<tr><label><td>Country:</td><td>
				<select name="bill_country" id="bill_country">
				<option value="">Select a country</option>
				<?php
				$query = "SELECT * FROM countries WHERE status='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					echo '<option value="'.$line["code"].'"';
					if($bill_country == $line["code"]) { echo " SELECTED"; }
					echo '>'.$line["name"].'</option>';
				}

				?>
				</select></td></label></tr>
				<tr><label><td>Phone:</td><td><input name="bill_phone" id="bill_phone" value="<?=$bill_phone?>" /></td></label></tr>
				<tr><label><td>E-Mail:</td><td><input name="email" id="email" value="<?=$email?>" /></td></label></tr>
				</table></fieldset>

		</td>
		<td><fieldset><legend>Shipping Information &#160;&#160;&#160;<button id="usebill" onClick="useOther('bill'); return false;" />use Billing Information</button></legend><table>
		<tr><label><td>Name:</td><td><input type="text" name="ship_name" id="ship_name" value="<?=$ship_name?>" /></td></label></tr>
		<tr><label><td>Address1:</td><td><input name="ship_address1" id="ship_address1" value="<?=$ship_address1?>" /></td></label></tr>
		<tr><label><td>Address2:</td><td><input name="ship_address2" id="ship_address2" value="<?=$ship_address2?>" /></td></label></tr>
		<tr><label><td>City:</td><td><input name="ship_city" id="ship_city" value="<?=$ship_city?>" /></td></label></tr>
		<tr><label><td>State/Province:</td><td><select name="ship_state" id="ship_state">
		<option value="">Select a state</option>
		<?php
		//$query = "SELECT * FROM states WHERE status='1'";
		$query = "SELECT * FROM states";
		//print_d(array(__FILE__,__LINE__,$query));
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			//print_d($line,true);
			echo '<option value="'.$line["code"].'"';
			if($ship_state == $line["code"]) { echo " SELECTED"; }
			echo '>'.$line["name"].'</option>';
		}

		?>
		</select></td></label></tr>
		<tr><label><td>Zip/Postal Code:</td><td><input name="ship_zip" id="ship_zip" value="<?=$ship_zip?>" /></td></label></tr>
		<tr><label><td>Country:</td><td>
		<select name="ship_country" id="ship_country">
			<option value="">Select a country</option>
			<?php
			$query = "SELECT * FROM countries WHERE status='1'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				echo '<option value="'.$line["code"].'"';
				if($ship_country == $line["code"]) { echo " SELECTED"; }
				echo '>'.$line["name"].'</option>';
			}

			?>
		</select>
		</td></label></tr>
		<tr><label><td>Phone:</td><td><input name="ship_phone" id="ship_phone" value="<?=$ship_phone?>" /></td></label></tr>
		<tr><label><td>Delivery Instructions:</td><td><input name="delivery" id="delivery" value="<?=$delivery?>" /></td></label></tr>
		</table></fieldset>
		
		</td>				
		</tr>
		<tr valign="top">
		<td><fieldset><legend>Internal Use Only</legend><table>
		<tr><label><td>Notes:</td><td><textarea name="notes" rows="4" cols="40"><?=$notes?></textarea></td></label></tr>
		<tr><label><td>Order Processed By:</td><td><select name="order_processed_by">
		<?php
		$query = "SELECT user_id, first_name, last_name FROM wms_users";
		$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			//print_d($line,true);
			$tmp_user_id = $line["user_id"];
			$first_name = $line["first_name"];
			$last_name = $line["last_name"];
			echo "<option value=\"" . $tmp_user_id . "\"";
			if($user_id == $tmp_user_id) { echo " SELECTED"; }
			echo ">" . $first_name . " " . $last_name . "</option>\n";
		}

		getMerchantCreds();
		?>
		</select></td></label></tr>
		<?php
		if($company == 1) {//Authorize.net
		?>
			<tr><td colspan="2">&#160;</td></tr>
			<tr><td>
					Make this a recurring order:
				</td>
				<td>
					<input type="checkbox" name="recurringChk" id="recurringChk" <?php if($recurringChk){echo ' checked="checked"';}?> />
				</td>
			</tr>
			<tr>
				<td colspan="2" width="550">
					<div id="recurringOptions" style="display:none; padding-top:8px; width: 525px;">
						<?php echo recurringFormOptions($recurring_interval, $recurring_length, $ordered, $rotating_products) ?>
					</div>
				</td>
			</tr>
		<?php
		}
		?>
		<tr><td colspan="2">&#160;</td></tr>
		<tr><label><td>&nbsp;</td><td>
			<input type="hidden" name="total" id="total" value="<?=$recTotal?>" />
			<input type="hidden" name="member_id" value="<?=$member_id?>" />
			<input type="hidden" name="submit_order" value="1" />
			<input type="submit" name="submit" value=" Finish Order " onClick="$(this).attr('disabled','disabled')" />
		</td></label></tr>
		</table></fieldset></td>
		</tr>
		</form>
		</table></td></tr>


		<tr><td>&nbsp;</td></tr>
		</table>
<?php
	}//END !$error_txt && $submit_order && $email_str
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>
