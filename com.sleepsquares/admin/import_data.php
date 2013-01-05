<?php
//Imports a csv file into recurring_orders, members, receipts, receipt_items
//For reference: $transaction_type=='2' means recurring order

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once("./includes/retailer1.php");
include_once($base_path.'includes/customer.php');
include_once($base_path.'/admin/includes/password/class.password.php');
include_once($base_path.'includes/authorize.net.php');
include_once($base_path.'includes/admin_orders_util.php');
include_once($base_path.'includes/wc1.php');

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

function createFakeEmail() {
    $queryFE = "INSERT INTO fake_emails SET fake_email_id=NULL";
	$resultFE = mysql_query($queryFE) or die("Query failed : " . mysql_error());
	$new_fake_id = mysql_insert_id();

	return 'fake'.$new_fake_id.'@NAPemail.com';
}

function sendMemberLoginEmail($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;

	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your " . $website_title . " account listed below.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n\n";
		$email_str .= $base_url;

		$subject = "New  " . $website_title . " Login Info";
		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

include './includes/wms_nav1.php';

if ( $_REQUEST["action"]=='import_now' && $_FILES['csvfile'] ) {
	$error_txt = "";

	$target = "upload/";
	$target = $target . basename( $_FILES['csvfile']['name']) ;

	$ok=1;

	//file already was uploaded previously
	if(file_exists($target)) {
		//$error_txt .=  "This file was already uploaded.  Processing it again with create duplicate transactions.";
	}
	
	if(move_uploaded_file($_FILES['csvfile']['tmp_name'], $target)) {
		//$msg .=  "The file ". basename( $_FILES['csvfile']['name']). " has been uploaded.";
	} else {
		$error_txt .=  "Sorry, there was a problem uploading your file.";
	}

	/********************************/
	$fieldseparator = $_REQUEST["fieldseparator"];
	$lineseparator = "\n";
	$csvfile = $_FILES['csvfile']['name'];
	/********************************/

	$csvfile = $target;
	if(!file_exists($csvfile)) {
		$error_txt .=  "<br />File not found. Make sure you specified the correct path.";
		exit;
	}

	$file = fopen($csvfile,"r");

	if(!$file) {
		$error_txt .=  "<br />Error opening data file.\n";
		exit;
	}

	$size = filesize($csvfile);

	if(!$size) {
		$error_txt .=  "<br />File is empty.\n";
		exit;
	}

	$csvcontent = trim(fread($file,$size));

	fclose($file);

	$lines = 0;
	$queries = "";
	$lineArray = array();
	$now = date("Y-m-d");

	/*
	To build a query dynamically from a line of data, uncomment the below and move the //build fields part to the first foreach loop below
	//put all field names in members table in array
	$fldsInMembersTable = array();
	$queryFields = "DESC members";
	$resultFields = mysql_query($queryFields) or die("queryFields failed: " . mysql_error());
	while ($lineFields = mysql_fetch_array($resultFields, MYSQL_ASSOC)) {
		$fldsInMembersTable[] = $lineFields["Field"];
	}

	//build fields for members table
	$membersFldsSQL = "";
	for( $cntA=0; $cntA<count($receiptFlds); $cntA++) {
		$thisRctFld = $receiptFlds[$cntA];
		$thisRctVal = $lineArray[$cntA];

		if ( in_array($thisRctFld, $fldsInMembersTable) ) {//this field  exists in members table
			$membersFldsSQL .= ', <br />'.$thisRctFld." = '$".$thisRctFld."'";
		
		}
	}

	*/

	$successfulLines = "";

	//fields in the csv appear in this order
	$receiptFlds = array("transaction_type", "recurring_interval", "recurring_length", "rotating_products", "ordered", "bill_name", "bill_address1", "bill_address2", "bill_city", "bill_state", "bill_zip", "bill_country", "bill_phone", "bill_email", "ship_name", "ship_address1", "ship_address2", "ship_city", "ship_state", "ship_zip", "ship_country", "ship_phone", "delivery", "cc_type", "cc_first_name", "cc_last_name", "cc_num", "cid", "cc_exp_m", "cc_exp_y", "cc_auth_code", "cc_trans_id", "item_count", "shipping_method", "subtotal", "shipping", "tax", "total", "receipt_items");

	//break down the last field in a csv line, receipt_items, into these fields
	$receiptItemFlds = array("sku", "quantity", "price", "name");

	if ( $error_txt=="" ) {

		getMerchantCreds();//authorize.net.php

		foreach(split($lineseparator,$csvcontent) as $line) {

			$line = trim($line," \t");
			
			$line = str_replace("\r","",$line);		

			/************************************
			This line escapes the special character. remove it if entries are already escaped in the csv file
			************************************/
			$line = str_replace("'","\'",$line);
			/*************************************/
			
			$lineArray = explode($fieldseparator,$line);


			if ( count($lineArray)!=count($receiptFlds) ) {
				$error_txt .= "<br />Line is not formatted correctly.<br />";
				$error_txt .= "You have ".count($lineArray)." fields defined instead of ".count($receiptFlds).":<br />".$line;
				continue;
			}
					

			//assign each value in this line to a corresponding variable
			for( $cntA=0; $cntA<count($receiptFlds); $cntA++) {
				$thisRctFld = $receiptFlds[$cntA];
				$thisRctVal = $lineArray[$cntA];

				$$thisRctFld = $thisRctVal;
				//echo '<br />'.$thisRctFld.' = '.$thisRctVal;
			}

			$receiptItemsArray = explode( ',', $receipt_items );

			//if no email, create a random fake one
			if ( $bill_email=='' ) {
				$bill_email = createFakeEmail();
			}

			//START CREATE OR UPDATE members RECORD
			$member_id = check_dup_email($bill_email, $dbh);//email already exists

			if ( $member_id<0 ) {
				$querySites = "SELECT * FROM partner_sites";
				$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
				while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {

					$thisDBHName = "dbh".$lineSites["site_key_name"];
					$thisHandle = $$thisDBHName;

					$member_email_test = check_dup_email($bill_email, $thisHandle);

					if ( $member_email_test>0 ) {//email doesn't exist in this site but  does in a partner site, so duplicate here

						duplicateMember($thisHandle, $bill_email);

						//duplicateMember automatically logs user in but we can't call logout or we'll lose our admin session
						//instead, just unset member_id
						unset( $_SESSION["member_id"] );

						//now that we've created the member here, we can grab their member_id
						$member_id = check_dup_email($bill_email);//email already exists
						break;
					}
				}
			}

			$thisMemberAttrs = array();
			$queryMember = "SELECT * FROM members where member_id='$member_id'";
			$resultMember = mysql_query($queryMember) or die("Query failed: " . mysql_error());
			while ($lineMember = mysql_fetch_array($resultMember, MYSQL_ASSOC)) {
				foreach($lineMember as $col=>$val) {
					$thisMemberAttrs[ $col ] = $val;
					//echo '<br />'.$col.' = '.$val;
				}
			}
			
			$tranProfileResults = createTransactionProfiles($thisMemberAttrs["customer_profile_id"], null, $bill_email, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y, $cc_exp_m, $bill_phone);

			$thisMemberAttrs["customer_profile_id"] = $tranProfileResults[0];
			$payment_profile_id = $tranProfileResults[1];
		
			if ( $tranProfileResults[2] != "" && $transaction_type=='2' ) {//error creating profiles, which are required for recurring transactions
				$error_txt .= '<br />'.$tranProfileResults[2].':<br />'.$line.'<br />';
			} else {
				$names = explode(" ", $bill_name);
				$names_count = count($names);
				$names_last = $names_count - 1;
				$first_name = $names[0];
				$last_name = $names[$names_last];

				//START CREATE OR UPDATE members RECORD			
				//build fields for members table
				$membersFldsSQL = "
					bill_name = '$bill_name',
					bill_address1 = '$bill_address1',
					bill_address2 = '$bill_address2',
					bill_city = '$bill_city',
					bill_state = '$bill_state',
					bill_zip = '$bill_zip',
					bill_country = '$bill_country',
					bill_phone = '$bill_phone',
					ship_name = '$ship_name',
					ship_address1 = '$ship_address1',
					ship_address2 = '$ship_address2',
					ship_city = '$ship_city',
					ship_state = '$ship_state',
					ship_zip = '$ship_zip',
					ship_country = '$ship_country',
					nickname = '$bill_name',
					first_name = '$first_name',
					last_name = '$last_name',
					ship_phone = '$ship_phone'";

				if ( $thisMemberAttrs["customer_profile_id"] ) {
					$membersFldsSQL .= ", customer_profile_id='".$thisMemberAttrs["customer_profile_id"]."'";
				}

				if ($member_id > 0)  {//user already in system
					$upOrInsQuery = "UPDATE members SET status='1', email='$bill_email', ".$membersFldsSQL. " WHERE member_id='$member_id'";
					
					//echo $upOrInsQuery;
					$resultUpOrIns = mysql_query($upOrInsQuery) or die("Update members query failed: " . mysql_error().'<br /><br />'.$upOrInsQuery);

				} else {//new user
					$pas = new password();
					$newpass = $pas->generate();

					$upOrInsQuery = "INSERT INTO members SET created='$now', status='1', email='$bill_email', ".$membersFldsSQL.", password='".md5($newpass)."'";
					$upOrInsQuery .= " , username='$bill_email' ";


					//echo '<br /><br />'.$upOrInsQuery;

					$resultUpOrIns = mysql_query($upOrInsQuery) or die("Insert members query failed: " . mysql_error());

					$member_id = mysql_insert_id();
					
					sendMemberLoginEmail($bill_email, $first_name, $last_name, $bill_email, $newpass);
				}
				//END CREATE OR UPDATE members RECORD

				//Make sure this receipt was not already written
				$query3 = "SELECT receipt_id, user_id FROM receipts WHERE ordered='".date("Y-m-d H:i:s",strtotime($ordered) )."' AND member_id = '$member_id' AND total = '$total' LIMIT 1";
				$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
				$receiptExists = mysql_fetch_row($result3);
				
								
				if ( $receiptExists ) {
					$error_txt .= '<br />Already uploaded previously as <a href="orders.detail.php?edit=1&id='.$receiptExists[0].'&retail=1"  title="edit order" target="_blank"> Order #'.$receiptExists[1].'</a><br />'.$line.'<br />';
				} else {
					$line_error = "";
					$transSuccess = false;

					$order_number = getNextUserID();

					//START imported orders all must have a trans_id already, but since recurring orders only get charged in promoteNextRecurringOrders, skip the below
					if ( $transaction_type=='2' ) {
						$transSuccess = true;
					} else {
						if ( $cc_trans_id ) {
							$authOrCapture = 'PRIOR_AUTH_CAPTURE';//All imported orders SHOULD be preapproved, transaction only uses cc_trans_id

							$transResults = postAuthorizeTrans($authOrCapture, $merchant_url, $merchant_username, $merchant_password, $total, $cc_num, $cc_exp_m, $cc_exp_y, $cid, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_trans_id);
							
							if (  $transResults[2]=="" ) {//no errors
								$transSuccess = true;
							}

							//PRIOR_AUTH_CAPTURE transactions, successul or not, don't change the below
							//$cc_auth_code = $transResults[0];
							//$cc_trans_id = $transResults[1];
						}

						if ( !$transSuccess ) {
							//using the preapproved cc_trans_id didn't work, so just try the cc itself with a normal transaction
							$transResultsTwo = postAuthorizeTrans('AUTH_CAPTURE', $merchant_url, $merchant_username, $merchant_password, $total, $cc_num, $cc_exp_m, $cc_exp_y, $cid, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_trans_id);

							$cc_auth_code = $transResultsTwo[0];
							$cc_trans_id = $transResultsTwo[1];

							if ( $transResultsTwo[2]=="" ) {//no errors

								$transSuccess = true;

							} else {//neither the preauth nor the cc itself worked
								$line_error .= '<br />'.$line;
								$line_error .= '<div style="padding-left: 40px">';
								$line_error .= $transResults[2]!="" ? $transResults[2].'<br />':'';
								$line_error .= $transResultsTwo[2].'</div>';
								$error_txt .= $line_error;
							}
						}
					}//END imported orders all must have a trans_id...

					if ( $transSuccess ) {
						$insertReceipts = "INSERT INTO receipts SET
							created= '$now',
							order_processed_by = '999',
							member_id = '$member_id',
							user_id = '$order_number',
							ordered = '".date("Y-m-d H:i:s",strtotime($ordered) )."',
							bill_name = '$bill_name',
							bill_address1 = '$bill_address1',
							bill_address2 = '$bill_address2',
							bill_city = '$bill_city',
							bill_state = '$bill_state',
							bill_zip = '$bill_zip',
							bill_country = '$bill_country',
							bill_phone = '$bill_phone',
							bill_email = '$bill_email',
							ship_name = '$ship_name',
							ship_address1 = '$ship_address1',
							ship_address2 = '$ship_address2',
							ship_city = '$ship_city',
							ship_state = '$ship_state',
							ship_zip = '$ship_zip',
							ship_country = '$ship_country',
							ship_phone = '$ship_phone',
							delivery = '$delivery',
							cc_type = '".strtolower($cc_type)."',
							cc_first_name = '$cc_first_name',
							cc_last_name = '$cc_last_name',
							cc_num = '"."XXXXXXXXXXXX".substr($cc_num, -4)."',
							cid = '$cid',
							cc_exp_m = '$cc_exp_m',
							cc_exp_y = '$cc_exp_y',
							cc_auth_code = '$cc_auth_code',
							cc_trans_id = '$cc_trans_id',
							item_count = '$item_count',
							shipping_method = '$shipping_method',
							subtotal = '$subtotal',
							shipping = '$shipping',
							tax = '$tax',
							pay_type = 'cc',
							total = '$total'";

						//imported orders don't have payment profile pre-defined, so we don't want it attached to the order itself, just to recurring orders for future use
						//if ( $payment_profile_id ) {
						//	$insertReceipts .= ", payment_profile_id='$payment_profile_id'";
						//}

						if($transaction_type!='2'){//recurring orders are not marked complete yet
							$insertReceipts .= ", complete='1'";
						}

						//echo '<br /><br />'.$insertReceipts;
						$resultRct = mysql_query($insertReceipts) or die("insertReceipts failed: " . mysql_error().'<br /><br />'.$insertReceipts);

						if ( $resultRct ) {//receipt written successfully
							$receipt_id = mysql_insert_id();

							foreach($receiptItemsArray as $thisReceiptItem ) {//iterate through each item ordered
								$thisRctItemCols = explode('#', $thisReceiptItem);

								for( $rCnt=0; $rCnt<count($receiptItemFlds); $rCnt++) {
									$thisRctItemFld = $receiptItemFlds[$rCnt];
									$thisRctItemVal = $thisRctItemCols[$rCnt];
						
									$$thisRctItemFld = $thisRctItemVal;
								}


								$insertSingleReceiptItem = "INSERT INTO receipt_items SET
									receipt_id= '$receipt_id',
									created= '$now',
									sku = '$sku',
									quantity = '$quantity',
									price = '$price',
									name = '$name'";

								//echo '<br /><br />'.$insertSingleReceiptItem;
								$resultRctItem = mysql_query($insertSingleReceiptItem) or die("insertSingleReceiptItem failed: " . mysql_error().'<br /><br />'.$insertSingleReceiptItem);

								if ( !$resultRctItem ) {//if any of the receipt_items don't get inserted, stop
									$line_error .= "<br />There was a problem inserting receipt SKU ".$sku.":<br />".$line.'<br />';
								}
							}

							if ( $line_error=="" ) {
								if ( $transaction_type=='2' ) {
									//START CREATE RECURRING ORDERS
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

											$updateToRecurring = mysql_query("UPDATE receipts SET recurring_orders_id='$recurring_orders_id' WHERE receipt_id='$receipt_id'") or die("insertReceipts failed: " . mysql_error().'<br /><br />Could not update receipt '.$receipt_id.' with a recurring_order_id.');

											if ( !$updateToRecurring ) {
												$line_error .= "<br />Could not update receipt '.$receipt_id.' with a recurring_order_id.'";
											}
										}
									}
									//END CREATE RECURRING ORDERS
								} else {
									sendConfirmationEmail($bill_email, $site_email, $receipt_id, $order_number, $company_phone, $website_title, $company_name, $company_address, $company_city_state_zip, $product_name, $total);
								}
							}

							if ($line_error=="" ) {
								if ( $transaction_type=='2' ) {
									$successfulLines.= '<br /><a href="recurring_orders_edit.php?edit=1&recurring_orders_id='.$recurring_orders_id.'"  title="edit recurring order">Recurring Order #'.$recurring_orders_id.'</a> established for <a href="members_admin2_edit.php?member_id='.$member_id.'"  title="edit member">'.stripslashes($bill_name).'</a>';

								} else {
									$successfulLines .= successfulRecur($receipt_id, $order_number, $member_id, $bill_name);
								}
							}

							$lines++;
						}//resultRct
					}
				}//receiptExists
			}//END error creating profiles
		}
	}

	if ( $lines > 0 ) {
		$msg = $successfulLines;
	}

	//call this just to make sure the queue remains full
	$recurringMsg = promoteNextRecurringOrders();

}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div>

<?php
include './includes/head_admin3.php';

if ( $error_txt!="" ) {
	echo '<br /><div class="error">';
	echo $error_txt;
	echo '</div>';
}

if ( $msg!="" )  {
	echo '<br /><div class="" style="background-color: #DDDDDD;">';
	echo 'Orders processed successfully';
	echo '</div>';
	echo '<div style="padding-left:40px">';
		echo $msg;
	echo '</div>';
}

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
<form enctype="multipart/form-data" action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />

<h2 style="background-color: #DDDDDD;">Import retail CC orders</h2>
<table cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td align="right"><div class="detailFlds">Select CSV file to upload:</div></td>
		<td><div class="detailFlds"><input name="csvfile" type="file" /></div></td>
	</tr>
	<tr>
		<td align="right"><div class="detailFlds">Field separator</div></td>
		<td><div class="detailFlds"><input type="text" size="1" maxlength="1" class="text_center" name="fieldseparator" id="fieldseparator" value="|" /></div></td>
	</tr>
	<tr>
		<td colspan="2" class="text_center">
			<div class="detailFlds">
				<input type="hidden" name="action" value="import_now" />
				<input type="submit" name="submitImport" value="Import Now" />
			</div>
		</td>
	</tr>
</table>
<br />
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>