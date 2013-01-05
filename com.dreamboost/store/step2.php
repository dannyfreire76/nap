<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /store/step2.php
// Version: 1.8
// Build: 1804
// Date: 03-11-2007

header('Content-type: text/html; charset=utf-8');
include_once('../includes/authorize.net.php');
include_once('../includes/main1.php');
include_once('../includes/cart1.php');
include_once('../includes/customer.php');
include_once('../includes/store_includes.php');
include_once('../includes/admin_orders_util.php');

$redirectNow = "";

$bill_name = $_SESSION["member_name"];
$bill_address1 = $_SESSION['address_info']["bill_address1"];
$bill_address2 = $_SESSION['address_info']["bill_address2"];
$bill_city = $_SESSION['address_info']["bill_city"];
$bill_state = $_SESSION['address_info']["bill_state"];
$bill_zip = $_SESSION['address_info']["bill_zip"];
$bill_country = $_SESSION['address_info']["bill_country"];
$bill_phone = $_SESSION['address_info']["bill_phone"];
$bill_email = $_SESSION['address_info']["email"];

//set submitted variables to simple var names with global scope, overriding ones above wherever found
foreach( $_POST as $n=>$v ){
	$$n = $v;
}

$warning_agree = $_POST["warning_agree"];
$disclaimer_agree = $_POST["disclaimer_agree"];
$age_agree = $_POST["age_agree"];

if(!$_COOKIE["nap_user"]) {
	header("Location: " . $base_url . "store/");
	exit;
}

function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please review the login details ";
		$email_str .= "for your " . $website_title . " account listed below (also good at our partner sites).\n\n";

		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url;
						
		$subject = "New  " . $website_title . " Login Info";
		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

// read promo main
$query = "SELECT buy_one_get_one_free FROM promo_main";
$result = mysql_query($query) or die("Query failed: " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$buy_one_get_one_free = $line["buy_one_get_one_free"];
}
mysql_free_result($result);

if($step2 == "1") {
	$error_fields = array();
	$new_username = $_POST["new_username"];
	$new_pw = $_POST["new_pw"];
	$confirm_new_pw = $_POST["confirm_new_pw"];

	//Validate Fields
	$error_txt = "";

	if ( $_SESSION['shipping_info'] ) {
		$shipping_info = $_SESSION['shipping_info'];
	} else {
		$error_txt .= 'Sorry, your session expired due to inactivity.  Please return to <a href="step1.php">Step 1</a>.<br>'; 
	}


	if(!$pay_type) { 
		$error_txt .= "You must enter the payment type in the <b>Payment Type</b> field.<br>\n"; 
		array_push($error_fields, "pay_type");
	}
	if($pay_type == "cc") { 
		if (  !$newOrOldCC ) {

			$error_txt .= "You must select an existing Credit Card or enter a new one..<br>\n"; 
			array_push($error_fields, "newOrOldCC");

		} else if($pay_type == "cc" && $newOrOldCC=='old' && !$payment_profile_id) { 

			$error_txt .= "You must select an existing Credit Card.<br>\n"; 
			array_push($error_fields, "newOrOldCC");

		} else if (  $newOrOldCC == "new" ) {
			if(!$cc_type) { 
				$error_txt .= "You must enter the credit card type in the <b>Credit Card Type</b> field.<br>\n"; 
				array_push($error_fields, "cc_type");
			}
			if(!$cc_first_name) { 
				$error_txt .= "You must enter your first name in the <b>Your First Name on Credit Card</b> field.<br>\n"; 
				array_push($error_fields, "cc_first_name");
			}
			if(!$cc_last_name) { 
				$error_txt .= "You must enter your last name in the <b>Your Last Name on Credit Card</b> field.<br>\n"; 
				array_push($error_fields, "cc_last_name");
			}
			if(!$cc_num) { 
				$error_txt .= "You must enter the credit card number in the <b>Credit Card Number</b> field.<br>\n"; 
				array_push($error_fields, "cc_num");		
			}
			if(!$cid) { 
				$error_txt .= "You must enter your credit card's security code in the <b>Security Code</b> field.<br>\n"; 
				array_push($error_fields, "cid");		
			}
			if(!$cc_exp_m) { 
				$error_txt .= "You must enter the credit card expiration month in the <b>Credit Card Expiration Month</b> field.<br>\n"; 
				array_push($error_fields, "cc_exp_m");		
			}
			if(!$cc_exp_y) { 
				$error_txt .= "You must enter the credit card expiration year in the <b>Credit Card Expiration Year</b> field.<br>\n"; 
				array_push($error_fields, "cc_exp_y");
			}
		}
	}

	if(!$bill_name) { 
		$error_txt .= "You must enter the billing name in the <b>Billing Name</b> field.<br>\n"; 
		array_push($error_fields, "bill_name");
	}

	if(!$bill_address1) { 
		$error_txt .= "You must enter the billing address in the <b>Billing Address1</b> field.<br>\n"; 
		array_push($error_fields, "bill_address1");
	}
	if(!$bill_city) { 
		$error_txt .= "You must enter the billing city in the <b>Billing City</b> field.<br>\n"; 
		array_push($error_fields, "bill_city");
	}

    if($bill_country=='US') {
        if($bill_state == "") { 
			$error_txt .= "You must enter the <b>Billing State</b> for this Retailer.<br>\n";
			array_push($error_fields, "bill_state");
		}

        if($bill_zip == "") { 
			$error_txt .= "You must enter the <b>Billing Zip/Postal Code</b> for this Retailer.<br>\n";
			array_push($error_fields, "bill_zip");
		}
    }
	if(!$bill_country) { 
		$error_txt .= "You must enter the billing country in the <b>Billing Country</b> field.<br>\n";
		array_push($error_fields, "bill_country");
	}

	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$bill_email) ){
		$error_txt .= "You must enter a valid email address in the <b>Email Address</b> field.<br>\n";
		array_push($error_fields, "bill_email");
	} else {

		if ( !$member_id ) {//member not logged in
			$this_member_email_test = check_dup_email($bill_email);

			$querySites = "SELECT * FROM partner_sites";
			$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
			while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {

				$thisDBHName = "dbh".$lineSites["site_key_name"];
				$thisHandle = $$thisDBHName;

				$member_email_test = check_dup_email($bill_email, $thisHandle);

				if ( $member_email_test>0 ) {//email already exists
					if ( $this_member_email_test<0 ) {//but not in this site, so duplicate it here
						duplicateMember($thisHandle, $bill_email);

						//duplicateMember automatically logs user in so log them out in case this person is trying to use an email that's not theirs
						$imgStr = logoutMemberPartnerSite();
					}

					$error_txt .= "There is already an account with this email address.  If this is your email, please <a onclick=\"$('#login').trigger('click'); return false;\" href=\"javascript:void(0)\">log in now</a>.<br/>";
					array_push($error_fields, "bill_email");
					break;
				}
			}
		}
	}

    if (!$member_id) {
        if ( !$new_username ) {
            $error_txt .= "You must enter a Username.<br>\n";
            array_push($error_fields, "new_username");
        }
        else {
			$querySites = "SELECT * FROM partner_sites";
			$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
			while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {

				$thisDBHName = "dbh".$lineSites["site_key_name"];
				$thisHandle = $$thisDBHName;

				$queryName = "SELECT * FROM members WHERE username='".$new_username."'";
				//echo $queryName;
				$resultName = mysql_query($queryName, $thisHandle) or die("Query failed : " . mysql_error());
				
				if ( mysql_num_rows($resultName)>0 ) {
					$error_txt .= "That Username is already taken.  Please try again.<br/>";
					array_push($error_fields, "new_username");
					break;
				}

			}
        }


        if ( !$new_pw || $new_pw != $confirm_new_pw || strlen($new_pw)<7 ) {
            $error_txt .= "You must enter a valid 7-10 character password that matches in both fields.<br>\n";
            array_push($error_fields, "new_pw");
            array_push($error_fields, "confirm_new_pw");
        }
    }

	if(!$warning_agree) { 
		$error_txt .= "You must agree to the warning by checking the box under the <b>Warning</b> field.<br>\n"; 
		array_push($error_fields, "warning_agree");
	}

	if(!$disclaimer_agree) { 
		$error_txt .= "You must agree to the disclaimer by checking the box under the <b>Disclaimer</b> field.<br>\n"; 
		array_push($error_fields, "disclaimer_agree");
	}

	if(!$age_agree) { 
		$error_txt .= "You must declare you are 18 years old or older by checking the box next to the <b>Age</b> field.<br>\n"; 
		array_push($error_fields, "age_agree");
	}


	//Check for Errors
	if($error_txt == "") {
		$cc_trans_id = "";

		if($pay_type == "cc") {
			$query = "SELECT * FROM merchant_acct WHERE status='1' LIMIT 1";
			$result = mysql_query($query) or die("Query failed: " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$company = $line["company"];
				$api_host = $line["api_host"];
				$api_path = $line["api_path"];
				$merchant_url = $line["url"];
				$merchant_username = $line["username"];
				$merchant_password = $line["password"];
			}
			mysql_free_result($result);

			if($company == 1) {//Send to Authorize.net Merchant Account for Verification
				
				if ( $newOrOldCC == "new") {
					$authOrCapture = 'AUTH_CAPTURE';
					if ( $recurringChk ) {//recurring orders aren't charged here, but in promoteNextRecurringOrders, admin_orders_util.php
						$authOrCapture = 'AUTH_ONLY';
					}
					$transResults = postAuthorizeTrans($authOrCapture, $merchant_url, $merchant_username, $merchant_password, $_SESSION["order_info"]["total"], $cc_num, $cc_exp_m, $cc_exp_y, $cid, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_trans_id);

					$cc_auth_code = $transResults[0];
					$cc_trans_id = $transResults[1];
					$error_txt .= $transResults[2];
					
				} else {//existing payment profile used

					$authOrCapture = 'profileTransAuthCapture';
					if ( $recurringChk ) {//recurring orders aren't charged here, but in promoteNextRecurringOrders, admin_orders_util.php
						$authOrCapture = 'profileTransAuthOnly';
					}
					
					$profileTransResp = createCustomerProfileTransactionRequest($_SESSION["profile_id"], $payment_profile_id, $_SESSION["order_info"]["total"], 'profileTransAuthCapture', null, null, null);

					if ( strtolower($profileTransResp->messages->resultCode)!="ok" ) {
						$error_txt .= "There was a problem submitting this transaction: ".$profileTransResp->messages->message->text."<br>\n";
					} else {
							$directResponse = explode(",", $profileTransResp->directResponse);
							$cc_auth_code = $directResponse[4];
							$cc_trans_id = $directResponse[6];
					}
				}

				if($error_txt == "") {
					$tranProfileResults = createTransactionProfiles($_SESSION["profile_id"], $payment_profile_id, $bill_email, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y, $cc_exp_m, $bill_phone);

					$_SESSION["profile_id"] = $tranProfileResults[0];
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

				curl_setopt($c, CURLOPT_POSTFIELDS, 'M_id='.$merchant_username.'&M_key='.$merchant_password.'&C_name=' . urlencode($cc_first_name) . urlencode(' ') . urlencode($cc_last_name) . '&C_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&C_city=' . urlencode($bill_city) . '&C_state=' . urlencode($tmp_bill_state) . '&C_zip=' . urlencode($tmp_bill_zip) . '&C_country=' . urlencode($bill_country) . '&C_email=' . urlencode($bill_email) . '&C_cardnumber=' . urlencode($cc_num) . '&C_exp=' . urlencode($cc_exp_m) . urlencode(substr($cc_exp_y, 2, 2)) . '&T_amt=' . urlencode($_SESSION["order_info"]["total"]) . '&T_code=' . '01' . '&T_ordernum=' . urlencode($user_id) . $tmp_cid);

				$page = urldecode(curl_exec($c));

				if(curl_errno($c)) { 
					$error_txt .= curl_error($c);
					array_push($error_fields, "cc_num");
				}
				curl_close($c);				
				
				// Debug Merchant Account
				//$error_txt .= "Results From Merchant Acct: " . $page . "<br>\n";
				
				//$page = substr($page, 1);
				//$page2 = explode("\"|\"", $page);
				
				if($page[1] != "A") {
					$error_txt .= "Error, There was a problem with the credit card information you entered, because \"" . substr($page, 8, 32) . "\"<br>\n";
					array_push($error_fields, "cc_num");
				} else {
					$cc_auth_code = substr($page, 2, 6);
					$cc_trans_id = substr($page, 46, 10);
				}
			}
		}
	}
	
	if($error_txt == "") {
		
        if($bill_name != "") {
			$nickname = $bill_name;
		} else {
			if($ship_name != "") {
				$nickname = $ship_name;
			}
		}
		$names = explode(" ", $nickname);
		$names_count = count($names);
		$names_last = $names_count - 1;
		$first_name = $names[0];
		$last_name = $names[$names_last];
		
		//let's reassign user_id to a new variable so we can run code which changes the original user_id
		$active_user_id = $user_id;

		 if ($member_id)  {//user is already logged in
			$this_member_id = $member_id;

			$upOrInsQuery = "UPDATE members SET status='1', ";
			
			$upOrInsQuery .= " nickname='$nickname', first_name='$first_name', last_name='$last_name', email='$bill_email', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', ship_name='";
			$upOrInsQuery .= $shipping_info['ship_name'];
			$upOrInsQuery .= "', ship_address1='";
			$upOrInsQuery .= $shipping_info['ship_address1'];
			$upOrInsQuery .= "', ship_address2='";
			$upOrInsQuery .= $shipping_info['ship_address2'];
			$upOrInsQuery .= "', ship_city='";
			$upOrInsQuery .= $shipping_info['ship_city'];
			$upOrInsQuery .= "', ship_state='";
			$upOrInsQuery .= $shipping_info['ship_state'];
			$upOrInsQuery .= "', ship_zip='";
			$upOrInsQuery .= $shipping_info['ship_zip'];
			$upOrInsQuery .= "', ship_country='";
			$upOrInsQuery .= $shipping_info['ship_country'];
			$upOrInsQuery .= "', ship_phone='";
			$upOrInsQuery .= $shipping_info['ship_phone'] ."'";

			if ( $_SESSION["profile_id"] ) {
				$upOrInsQuery .= ", customer_profile_id='".$_SESSION["profile_id"]."'";
			}
			$resultUpOrIns = mysql_query($upOrInsQuery." WHERE member_id=$this_member_id") or die("Update members query failed: " . mysql_error().'<br /><br />'.$upOrInsQuery);

		} else  {//user not logged in
			$now = date("Y-m-d H:i:s");
			$upOrInsQuery = "INSERT INTO members SET created='$now', status='1', email='$bill_email', username='$new_username', password='".md5($new_pw)."', nickname='$nickname', first_name='$first_name', last_name='$last_name', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', ship_name='";
			$upOrInsQuery .= $shipping_info['ship_name'];
			$upOrInsQuery .= "', ship_address1='";
			$upOrInsQuery .= $shipping_info['ship_address1'];
			$upOrInsQuery .= "', ship_address2='";
			$upOrInsQuery .= $shipping_info['ship_address2'];
			$upOrInsQuery .= "', ship_city='";
			$upOrInsQuery .= $shipping_info['ship_city'];
			$upOrInsQuery .= "', ship_state='";
			$upOrInsQuery .= $shipping_info['ship_state'];
			$upOrInsQuery .= "', ship_zip='";
			$upOrInsQuery .= $shipping_info['ship_zip'];
			$upOrInsQuery .= "', ship_country='";
			$upOrInsQuery .= $shipping_info['ship_country'];
			$upOrInsQuery .= "', ship_phone='";
			$upOrInsQuery .= $shipping_info['ship_phone'];
			$upOrInsQuery .= "'";

			if ( $_SESSION["profile_id"] ) {
				$upOrInsQuery .= ", customer_profile_id='".$_SESSION["profile_id"]."'";
			}
			
			$resultUpOrIns = mysql_query($upOrInsQuery) or die("Insert members query failed: " . mysql_error());

			$this_member_id = mysql_insert_id();

			send_email_login($bill_email, $first_name, $last_name, $new_username, $new_pw);
		}

		//log this user in (even if they're already logged in, b/c we want anything changed during the order to be updated in session, too
		doLogin( $this_member_id );

		//force member duplication/update on partner sites
		$querySites = "SELECT * FROM partner_sites WHERE site_url!='".$_SERVER["HTTP_HOST"]."'";
		$resultSites = mysql_query($querySites) or die("Query 2 failed: " . mysql_error());
		while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
			$thisDBHName = "dbh".$lineSites["site_key_name"];
			global $$thisDBHName;
			$thisHandle = $$thisDBHName;

			$member_email_test = check_dup_email($bill_email, $thisHandle);

			if($member_email_test > 0) {//already exists so update with same info as referring site
				$updateQ = $upOrInsQuery." WHERE email='$bill_email'";
				$resultQ = mysql_query($updateQ, $thisHandle) or die("Update Query failed : " . mysql_error().'<br /><br />'.$updateQ);

			} else {//no matching email in this site, so create new member record with same info as referring site
				duplicateMember($dbh, $bill_email, $thisHandle);
			}
		}

		//iterate through this and partner sites that have something in this cart, updating receipt tables
		$queryCartSites = "SELECT DISTINCT(site) FROM cart WHERE user_id='$active_user_id'";// ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
		//echo "queryCartSites: ".$queryCartSites."<br />";

		$resultCartSites = mysql_query($queryCartSites, $dbh_master) or die("Query 1 failed: " . mysql_error().'<br /><br />'.$queryCartSites);
		while ($lineCartSites = mysql_fetch_array($resultCartSites, MYSQL_ASSOC)) {

			$querySites = "SELECT * FROM partner_sites WHERE site_url='".$lineCartSites["site"]."'";

			$resultSites = mysql_query($querySites) or die("Query 2 failed: " . mysql_error());
			while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {

					$thisDBHName = "dbh".$lineSites["site_key_name"];
					$thisHandle = $$thisDBHName;

					if ( $lineCartSites["site"]==$_SERVER["HTTP_HOST"] ) {
						$receipt_member_id = $this_member_id;
					} else {
						$receipt_member_id = check_dup_email($bill_email, $thisHandle);//at this point, there has to be the same email in partner sites
					}

					//check if the receipt already exists, because it only gets created for the HOST site in step1
					$checkRptSql = "SELECT  * FROM receipts WHERE user_id='$active_user_id'";
					$resultRptSql = mysql_query($checkRptSql, $thisHandle) or die("<br />--Query 3 failed : ".mysql_error().'<br /><br />'.$checkRptSql);

					$updateOrInsert = "";
					if ( mysql_num_rows($resultRptSql) > 0 ) { 
						$query = "UPDATE receipts SET ";
						$updateOrInsert = "UPDATE";
					} else {
						$query = "INSERT INTO receipts SET user_id='$active_user_id', created='".date("Y-m-d H:i:s")."', ";
						$updateOrInsert = "INSERT";
					}

					//Write to receipts DB
					$now = date("Y-m-d H:i:s");
					$query .= " ordered='$now', member_id='$receipt_member_id', pay_type='$pay_type', cc_type='$cc_type', cc_first_name='$cc_first_name', cc_last_name='$cc_last_name', cc_num='XXXXXXXXXXXX".substr($cc_num, -4)."', cid='$cid', cc_exp_m='$cc_exp_m', cc_exp_y='$cc_exp_y', cc_auth_code='$cc_auth_code', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', bill_email='$bill_email', warning_agree='$warning_agree', disclaimer_agree='$disclaimer_agree', age_agree='$age_agree', payment_profile_id='$payment_profile_id', ".
					" item_count=".$_SESSION["order_info"][ $lineSites["site_url"] ]["qty"].
					", subtotal=".$_SESSION["order_info"][ $lineSites["site_url"] ]["subtotal"].
					", delivery='".$_SESSION["order_info"]["delivery"]."', discount_code='".$_SESSION["order_info"]["discount_code"]."'".
					", ship_name='";
					$query .= $shipping_info['ship_name'];
					$query .= "', ship_address1='";
					$query .= $shipping_info['ship_address1'];
					$query .= "', ship_address2='";
					$query .= $shipping_info['ship_address2'];
					$query .= "', ship_city='";
					$query .= $shipping_info['ship_city'];
					$query .= "', ship_state='";
					$query .= $shipping_info['ship_state'];
					$query .= "', ship_zip='";
					$query .= $shipping_info['ship_zip'];
					$query .= "', ship_country='";
					$query .= $shipping_info['ship_country'];
					$query .= "', ship_phone='";
					$query .= $shipping_info['ship_phone'];
					$query .= "'";

					if($recurring_orders_id != ""){//recurring orders are not marked complete and may have a different order date
						//different than members_place_order2.php because receipt is created at a different point in that process
						//$query .= " recurring_orders_id='$recurring_orders_id',";
						//$query .= " ordered='".date("Y-m-d H:i:s",strtotime($ordered) )."',";
					} else {
						$query .= ", complete='1'";
					}
					
					// added for refunds
					if($cc_trans_id != ""){
						$query .= ", cc_trans_id='$cc_trans_id'";
					}

					//only THIS site gets the shipping and tax
					if ( $lineSites["site_url"]==$_SERVER["HTTP_HOST"] ) {
						$query .= ", shipping=".$_SESSION["order_info"]["shipping"].
						", tax=".$_SESSION["order_info"]["tax"].
						", total=". (   ($_SESSION["order_info"][ $lineSites["site_url"] ]["subtotal"] * 1) + ($_SESSION["order_info"]["tax"] * 1) + ($_SESSION["order_info"]["shipping"] * 1)   );
					} else {
						$query .= ", total=".$_SESSION["order_info"][ $lineSites["site_url"] ]["subtotal"];
					}

					if ( $updateOrInsert=="UPDATE" ) {
						$query .= " WHERE user_id='$active_user_id' LIMIT 1";
					}
					//echo "<br /><br />query: ".$query;
					$result = mysql_query($query, $thisHandle) or die("Query 3 failed : ".mysql_error().'<br /><br />'.$query);


					$query = "SELECT receipt_id FROM receipts WHERE user_id='$active_user_id'";
					$result = mysql_query($query, $thisHandle) or die("Query 4 failed : " . mysql_error().'<br /><br />'.$query);
					while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
						$receipt_id = $line["receipt_id"];
					}
					mysql_free_result($result);

					//Write to receipt_items DB but get rid of any existing items, just in case reqest gets sent twice
					$queryDel = "DELETE FROM receipt_items WHERE receipt_id='$receipt_id'";
					mysql_query($queryDel, $thisHandle) or die("Query 5 failed : " . mysql_error().'<br /><br />'.$query);

					$now = date("Y-m-d H:i:s");

					$thisSitesCart = createCartTable( $lineSites["site_url"], $thisHandle, true );
					$this_cart_items = $thisSitesCart["this_cart_items"];
					
					foreach( $this_cart_items as $thisSku ) {
						$tmp_sku = $thisSku['prod_sku'];
						$tmp_quantity = $thisSku['prod_quantity'];
						$tmp_price = $thisSku['prod_price'];
						$tmp_name = $thisSku['prod_name'];

						$query = "INSERT INTO receipt_items SET receipt_id='$receipt_id', created='$now', sku='$tmp_sku', quantity='$tmp_quantity', price='$tmp_price', name='$tmp_name'";
						$result = mysql_query($query, $thisHandle) or die("Query 6 failed : " . mysql_error().'<br /><br />'.$query);
						$i++;
					}
							
					//START CREATE RECURRING ORDERS
					if ( $recurringChk ) {
						$hdrRecurSql = "SELECT * FROM recurring_orders WHERE ".
													" member_id = '".$member_id."' AND ".
													" original_receipt_id = '".$receipt_id."'";

						$resultHdr = mysql_query($hdrRecurSql, $thisHandle) or die("hdrRecurSql failed : " . mysql_error() ."<br /><br />".$hdrRecurSql);

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

							$resultRecurSql = mysql_query($createRecurSql, $thisHandle) or die("createRecurSql failed : " . mysql_error() ."<br /><br />".$createRecurSql);

							if ( $resultRecurSql ) {//recurring_orders record written successfully, so update original receipt now
								$recurring_orders_id = mysql_insert_id();
								
								$queryUpR .= "UPDATE receipts SET recurring_orders_id='$recurring_orders_id',";
								$queryUpR .= " ordered='".date("Y-m-d H:i:s",strtotime($ordered) )."' WHERE receipt_id='$receipt_id' LIMIT 1";

								$resultUpR = mysql_query($queryUpR, $thisHandle) or die("queryUpR failed : " . mysql_error().'<br /><br />'.$query);

								if ( $resultUpR ) {
									promoteNextRecurringOrders();
								}
							}
						}
					}
					//END CREATE RECURRING ORDERS


					//checkInventory
					checkInventory($receipt_id);

			}
		}//END iterate through this and partner sites...


		//remove old cookie on all sites (next page will set a new one)
		echo removeCookiePartnerSites("nap_user");

		$_SESSION["loginJustDone"] = true;
		$_SESSION["orderNum"] = $active_user_id;
		$_SESSION["sendMail"] = true;

		//Go to confirm
		$redirectNow = $base_secure_url . "store/confirm.php";

		//don't use header to redirect so that echo above actually renders and executes code
		//header("Location: " . $base_secure_url . "store/confirm.php");
		//exit;
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Checkout</title>

<?php
include '../includes/meta1.php';
?>
<style type="text/css">
	.cc_info {display: none}
	#ccRadio {display:none}

	 .pretty_disabled {
		 background-color: #e0e0e0;
		 border: 1px solid #e0e0e0;
	}

</style>

<script language="JavaScript">
	$(function() {//on doc ready
		toggleCC();
		$('input[@name=pay_type]').click( function(){toggleCC()} );
		$('input[@name=newOrOldCC]').click( function(){toggleCC()} );

		$('#payment_profile_id').change(function(){
			setBilling();	
		});

		toggleRecurringOptions();
		$('#recurringChk').click( function(){ toggleRecurringOptions(); });
	});

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

	function useShip() {
		if ( $('#useShippingAddress').is(':checked') )  {
			$(':input:visible[@name*=bill_]').each(function(){
				var replaceName = "hidden_" +$(this).attr('name').replace('bill', 'ship');
				var hiddenReplacement = $('div[@id='+replaceName+']').text();
				if ( hiddenReplacement != '' ) {
					$(this).val( hiddenReplacement );
				}
			});
		} else {
			$(':input:visible[@name*=bill_]').each(function(){
				var origVal = $(this).attr('origValue');
				if ( !origVal ) { origVal="" };//catch for undefined
				$(this).val( origVal );
			});
		}
	}

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
				
			} else {
				$(':input[@name='+thisFldName+']')
					.addClass('pretty_disabled')
					.focus( function(){$(this).blur()} )
				
			}
		});
	}


	function toggleCC() {
		if ( $(':input[@name=newOrOldCC]').size() > 1  ) {
			if ( $('#newCC').is(':checked') ) {
				$('#newCCOptions').show();
				$('#oldCCOptions').hide();

				$('option', '#payment_profile_id').removeAttr('selected');
				setBilling();	

			} else if ( $('#oldCC').is(':checked') ) {
				$('#newCCOptions').hide();
				$('#oldCCOptions').show();
			}

			if ( $('#pay_typeCC').is(':checked') ) {
				$('#ccRadio').show();
				if ( $('#recurringOptionsWrapper').is(':hidden') ) {
					$('#recurringOptionsWrapper').slideDown(300);
				}

			} else if ( $('#pay_typeMO').is(':checked') ) {
				$('#ccRadio').hide();
				$('#newCCOptions').hide();

				if ( $('#recurringChk').is(':checked') ) {
					$('#recurringChk').removeAttr('checked');
					toggleRecurringOptions();
				}
				
				$('#recurringOptionsWrapper').hide();
				
				$('option', '#payment_profile_id').removeAttr('selected');
				setBilling();	

			}
		} else {//no card on file

			if ( $('#pay_typeCC').is(':checked') ) {
				if ( $('#recurringOptionsWrapper').is(':hidden') ) {
					$('#recurringOptionsWrapper').slideDown(300);
				}
				$('#newCCOptions').show();

			} else if ( $('#pay_typeMO').is(':checked') ) {
				if ( $('#recurringChk').is(':checked') ) {
					$('#recurringChk').removeAttr('checked');
					toggleRecurringOptions();
				}
				$('#recurringOptionsWrapper').hide();
				$('#newCCOptions').hide();
			}
		}
	}
</script>


<?php
if ( $redirectNow != "") {
?>
	<script language="JavaScript">
		$(function() {//on doc ready
			$('#loading').large_spinner();
			setTimeout("window.location.href='<?=$redirectNow?>'", 2000);//give it a sec to load images from other servers
		});
	</script>
	</head>
	<body id="container" style="background-image: none; border-left: 0px; border-right: 0px;">
		<br /><br /><br />
		<div id="loading" style="margin-auto; text-align: center"></div>
	</body>
<?php

} else {

?>

	<script language="JavaScript">
		<?php
		if ($error_fields) {
			echo "$(function() {//on doc ready\n";
			foreach ($error_fields as $bad_field) {
				if ( $bad_field=='newOrOldCC' ) {
					echo "$(':input[@name=".$bad_field."]').parents('td:first').addClass('error');";
				} else {
					echo "
						if ( $(':input[@name=".$bad_field."]').parents('td:first').siblings(':first').size()>0 ) {
							$(':input[@name=".$bad_field."]').parents('td:first').siblings(':first').addClass('error');
						}
						else {
							$(':input[@name=".$bad_field."]').parents('td:first').addClass('error');
						}
					";
				}
			}
			echo "})";
		}
		?>
	</script>

	</head>
	<body bgcolor="#<?php echo $bgcolor; ?>">
	<div align="center">

	<?php
	include '../includes/head1.php';


	$query5 = "SELECT discount_code, percent_off FROM discount_codes WHERE status='1'";
	$result5 = mysql_query($query5) or die("Query failed: " . mysql_error());
	while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
		$discount_codes[] = $line5["discount_code"];
		$percent_offs[] = $line5["percent_off"];
	}
	mysql_free_result($result5);

	$discount_codes_count = count($discount_codes);
	$discount_code_match = strtolower($_SESSION["order_info"]["discount_code"]);

	$percent_off = 0;
	for($i=0;$i<$discount_codes_count;$i++) {
		if($discount_code_match == $discount_codes[$i]) {
			$percent_off = $percent_offs[$i];
		}
	}

	?>

	<table border="0" width="677">

	<tr><td>&nbsp;</td></tr>

	<tr><td align="left"><font class="two">Checkout: Step 2 - Review Order and Payment Information</font></td></tr>

	<?php

	if($step2 == "1") {
		if($error_txt != "") {
			echo "<tr><td>&nbsp;</td></tr>\n";
			echo "<tr><td align=\"left\" class=\"error\">$error_txt</td></tr>\n";
			//echo "<tr><td align=\"left\" class=\"error\">Please complete the highlighted fields correctly.</td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
		}
	}


	if ( $percent_off != 0) {
		echo '<tr><td><span class="error3">Discount code '.$discount_code_match.' applied: '.($percent_off * 100).'% off</span></td></tr>';
	}

	$percent_off = 1 - $percent_off;
	$_SESSION["order_info"]["percent_off"] = $percent_off;

	?>

	<tr><td align="left">


	<?php
	$itemsTotal=0;
	$shippingTotal=0;
	$qtyTotal=0;
	$freeMet = false;
	$host_cart_items = array();

	echo '
	<table border="0" id="cart_table">';

		$thisCart  = createCartTable( $_SERVER["HTTP_HOST"], $dbh, true );
		echo $thisCart["cartHTML"];

		$thisSubTotal = $thisCart["thisSubtotal"];
		$host_cart_items[] = $thisCart["this_cart_items"];

		$thisQty = $thisCart["thisQty"];
		if ( $thisQty > 0 ) {
			$thisShipping = calcShipping($thisSubTotal);
			if ( $thisShipping==0 ) {//once a free shipping threshold for any site has been met, the whole order's shipping is free
				$freeMet = true;
			}

			$shippingFromHostSite = $thisShipping;

			$itemsTotal += $thisSubTotal;
			$qtyTotal += $thisQty;

			$_SESSION["order_info"][ $_SERVER["HTTP_HOST"] ]["subtotal"] = $thisSubTotal;
			$_SESSION["order_info"][ $_SERVER["HTTP_HOST"] ]["qty"] = $thisQty;
		}

		$querySites = "SELECT * FROM partner_sites WHERE site_url != '".$_SERVER["HTTP_HOST"]."'";
		$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
		while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
				
				$thisDBHName = "dbh".$lineSites["site_key_name"];

				$thisCart = createCartTable( $lineSites["site_url"], $$thisDBHName, true );	
				echo $thisCart["cartHTML"];

				$thisSubTotal = $thisCart["thisSubtotal"];

				$thisQty = $thisCart["thisQty"];
				if ( $thisQty > 0 ) {
					$thisShipping = calcShipping($thisSubTotal, $$thisDBHName);

					if ( $thisShipping==0 ) {//once a free shipping threshold for any site has been met, the whole order's shipping is free
						$freeMet = true;
					}

					$itemsTotal += $thisSubTotal;
					$qtyTotal += $thisQty;
			
					$_SESSION["order_info"][ $lineSites["site_url"] ]["subtotal"] = $thisSubTotal;
					$_SESSION["order_info"][ $lineSites["site_url"] ]["qty"] = $thisQty;
				}
		}
		echo '
		<tr class="style3"><td colspan="5"><hr /></tr>
		<tr>
			<td class="text_right" colspan="3"><b>SUB-TOTAL</b></td>
			<td class="text_right"><b>$'.condDecimalFormat($itemsTotal).'</b></td>
			<td>&#160;</td>
		</tr>';

		$free_prod_ship_active = false;
		if ( $qtyTotal==1 ) {
			foreach( $host_cart_items as $thisCartsArr ) {//only check host cart for special items (since special prod_id might exist for a different site as a diff prod)
				foreach( $thisCartsArr as $thisItem ) {

					$tmp_sku = $thisItem['prod_sku'];
					$tmp_prod_id = $thisItem['prod_id'];

					if ( in_array($tmp_prod_id, $free_prods_arr) ) {//there's only 1 item and it's a special, so use the shipping cost for just that one
						$shippingTotal = $free_prods_ship_arr[ $tmp_sku ];
						$free_prod_ship_active = true;
					}
				}
			}
		}

		if ( !$free_prod_ship_active) {
			if ( !$freeMet ) {//if no free thresholds have been met, check if global free ship threshold has been met
				$shippingTotal = calcShippingGlobal($itemsTotal, $shippingFromHostSite);
			} else {
				$shippingTotal = 0;
			}
		}

		if ( $shippingTotal==0 ) {
			$displayShip = "FREE";
		} else {
			$displayShip = '$'.sprintf("%01.2f", $shippingTotal);
		}

		echo '<tr><td>&nbsp;</td><td colspan="2" VALIGN="TOP" align="right"><b>Shipping and Handling</b></td><td VALIGN="TOP" align="right"><b>'.$displayShip.'</b></td></tr>';
		
		$tax = calcTax($itemsTotal);
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><b>Tax</b></td><td VALIGN="TOP" align="right"><b>$';
		echo $tax.'</b></td></tr>';
		
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><b>Grand Total</b></td><td VALIGN="TOP" align="right"><b>$';

		$total = $itemsTotal + $shippingTotal + $tax;
		$total = sprintf("%01.2f", $total);
		echo $total.'</b></td></tr><tr><td colspan="4">&nbsp;</td></tr>';

		echo '
	</table>';
	/*
	$cart_vars = get_cart_total($user_id);
	$in_cart_arr = $cart_vars[2];

	$item_count = sizeof($in_cart_arr);//not total quantity, but number of unique items
	$subtotal = $cart_vars[0];
	$shipping = calcShipping($subtotal);
	$tax = calcTax($subtotal);
	$total = $subtotal + $shipping + $tax;
	*/

	//save cart info as session vars to use when this form is submitted
	$_SESSION["order_info"]["item_count"] = $qtyTotal;
	$_SESSION["order_info"]["shipping"] = $shippingTotal;
	$_SESSION["order_info"]["tax"] = $tax;
	$_SESSION["order_info"]["total"] = $total;

	/*
	echo '<pre>';
	echo var_dump($_SESSION["order_info"]);
	echo '</pre>';
	*/

	echo '</td></tr>';
	echo '
	<form action="'.$base_secure_url.'store/step2.php" method="POST" onSubmit="$(\'#submitBtn\').attr(\'disabled\', \'disabled\');">
		<input type="hidden" name="step2" value="1">';

	/*
	echo '<input type="hidden" name="item_count" value="'.$item_count.'">';
	echo '<input type="hidden" name="subtotal" value=""'.$subtotal.'">';
	echo '<input type="hidden" name="shipping" value=""'.$shipping.'">';
	echo '<input type="hidden" name="tax" value=""'.$tax.'">';
	echo '<input type="hidden" name="total" value=""'.$total.'">';
	*/

	echo '<tr><td align="left">Please double check your order, if there are any problems please <a href="'.$base_url.'store/cart.php">return to the Shopping Cart</a> to make adjustments. If everything is okay then please complete your order below.</td></tr><tr><td>&nbsp;</td></tr><tr><td align="left">
	';
	echo '<table border="0"><tr><td align="left"><b>Payment Information</b></td></tr>';

	echo '<tr><td align="right" VALIGN="TOP">Payment Type:</td><td align="left"><input type="radio" name="pay_type" id="pay_typeCC" value="cc"'.($pay_type == "cc" ? " CHECKED": "").'><label for="pay_typeCC">Credit Card </label>&nbsp;&nbsp;&nbsp; <input type="radio" name="pay_type" id="pay_typeMO" value="mo"'.($pay_type == "mo" ? " CHECKED": "").'><label for="pay_typeMO">Money Order</label><br><font size="-1">If paying by Money Order, your order will be shipped as soon as we receive your money order.</font></td></tr>';

	getMerchantCreds();//authorize.net.php
	if ( $company == 1 ) {
		if ( $_SESSION["profile_id"] ) {

			$completeProfileRequest = getCustomerProfileRequest( $_SESSION["profile_id"] );//get all payment profiles
			
			if ( count($completeProfileRequest->profile->paymentProfiles) > 0 ) {
				echo '<tr><td></td><td>';
				echo '<table id="ccRadio" cellpadding="0" cellspacing="0"><tr><td>';
				echo '</td><td>';
				echo '<input type="radio" name="newOrOldCC" id="oldCC" value="old" '.($newOrOldCC=='old'?'checked="checked"': '').' /><label for="oldCC"> Card on file</label>&#160;&#160;&#160;&#160;&#160;&#160;';
				echo '<input type="radio" name="newOrOldCC" id="newCC" value="new" '.($newOrOldCC=='new'?'checked="checked"': '').' /><label for="newCC"> New card</label>';
				echo '</td></tr>';

				echo '<tr><td align="right">';
				echo '</td><td>';
				echo '<div id="oldCCOptions" class="no_display"><select name="payment_profile_id" id="payment_profile_id">';
				echo '<option value=""> -- please select a card -- </option>';

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
				echo '</select></div></td></tr></table></td></tr>';
				echo $payProfileStr;
			}
		}
	}

	if ( count($completeProfileRequest->profile->paymentProfiles)==0 ) {
		echo "\n".'<input type="hidden" name="newOrOldCC" value="new" />';
	}
	?>	
	<tr><td align="center" colspan="2">
		<table id="newCCOptions" class="no_display"><tr><td>		
			Credit Card Type:</td><td align="left"><SELECT name="cc_type"><option value=""></option>
			<option value="mc"<?php if($cc_type == "mc") { echo " SELECTED"; } ?>>Mastercard</option>
			<option value="vi"<?php if($cc_type == "vi") { echo " SELECTED"; } ?>>Visa</option>
			<option value="am"<?php if($cc_type == "am") { echo " SELECTED"; } ?>>American Express</option>
			<option value="di"<?php if($cc_type == "di") { echo " SELECTED"; } ?>>Discover</option>
			</select>&nbsp; <img src="../images/store_ccs.gif" align="absmiddle" border="0"></td></tr>
			<tr><td align="right" NOWRAP>Your First Name on Credit Card:</td>
				<td align="left"><input type="text" name="cc_first_name" size="30" maxlength="50"<?php if($cc_first_name) { echo " value=\"$cc_first_name\""; } ?>></td></tr>
			<tr><td align="right" NOWRAP>Your Last Name on Credit Card:</td><td align="left"><input type="text" name="cc_last_name" size="30" maxlength="50"<?php if($cc_last_name) { echo " value=\"$cc_last_name\""; } ?>></td></tr>
			<tr><td align="right">Credit Card Number:</td><td align="left"><input type="text" name="cc_num" size="16" maxlength="20"<?php if($cc_num) { echo " value=\"$cc_num\""; } ?>></td></tr>
			<tr><td align="right">Security Code:</td><td align="left"><input type="text" name="cid" size="4" maxlength="4"<?php if($cid) { echo " value=\"$cid\""; } ?>> &nbsp; <a href="javascript:void(0)" class="cid_link smaller">What is this?</a>
			<div class="no_display absolute cid_pop">
				<img src="/images/close.gif" class="right hand" onClick="$(this).parents('.cid_pop').fadeOut(300)" />
				The Security Code is a three (3) or four (4) digit number listed on the back of your credit card immediately following your card number. (On American Express cards, the security code may be on the front.)<br>
				<br>
				This number prevents fraudulent charges to your credit card, such as someone stealing your credit card receipt and using that information to make a purchase.<br>
				<br>
				Note: Some older cards may not have a Security Code. In these cases, simply leave the Security Code field blank.
			</div></td></tr>
			<tr><td align="right">Expiration Date:</td><td align="left"><SELECT name="cc_exp_m">
			<option value=""></option>
			<option value="01"<?php if($cc_exp_m == "01") { echo " SELECTED"; } ?> class="text_right">January - 01</option>
			<option value="02"<?php if($cc_exp_m == "02") { echo " SELECTED"; } ?> class="text_right">February - 02</option>
			<option value="03"<?php if($cc_exp_m == "03") { echo " SELECTED"; } ?> class="text_right">March - 03</option>
			<option value="04"<?php if($cc_exp_m == "04") { echo " SELECTED"; } ?> class="text_right">April - 04</option>
			<option value="05"<?php if($cc_exp_m == "05") { echo " SELECTED"; } ?> class="text_right">May - 05</option>
			<option value="06"<?php if($cc_exp_m == "06") { echo " SELECTED"; } ?> class="text_right">June - 06</option>
			<option value="07"<?php if($cc_exp_m == "07") { echo " SELECTED"; } ?> class="text_right">July - 07</option>
			<option value="08"<?php if($cc_exp_m == "08") { echo " SELECTED"; } ?> class="text_right">August - 08</option>
			<option value="09"<?php if($cc_exp_m == "09") { echo " SELECTED"; } ?> class="text_right">September - 09</option>
			<option value="10"<?php if($cc_exp_m == "10") { echo " SELECTED"; } ?> class="text_right">October - 10</option>
			<option value="11"<?php if($cc_exp_m == "11") { echo " SELECTED"; } ?> class="text_right">November - 11</option>
			<option value="12"<?php if($cc_exp_m == "12") { echo " SELECTED"; } ?> class="text_right">December - 12</option>
			</select> <SELECT name="cc_exp_y">
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
		</td></tr></table>
	</td></tr>
	<?php
	if($company == 1) {//Authorize.net
	?>
		<tr><td colspan="2">
			<table id="recurringOptionsWrapper" style="display:none">
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr><td>
						<b>Make this a recurring order:</b> <input type="checkbox" name="recurringChk" id="recurringChk" <?php if($recurringChk){echo ' checked="checked"';}?> />
					</td>
				</tr>
				<tr>
					<td colspan="2" width="550">
						<div id="recurringOptions" style="display:none; padding-top:8px;">
							<?php echo recurringFormOptions($recurring_interval, $recurring_length, $ordered, $rotating_products) ?>
						</div>
					</td>
				</tr>
			</table>
		</td></tr>
	<?php
	}
	?>
	<tr><td colspan="2">&nbsp;</td></tr>

	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td align="left" colspan="2"><b>Billing Address</b>&nbsp; &nbsp;<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="-1">This must be the billing address exactly as it appears on your billing statement for the credit card or money order entered above.</font></td></tr>
	<tr><td align="left" colspan="2">
	<input type="checkbox" name="useShippingAddress" id="useShippingAddress" onClick="useShip()" /><label for="useShippingAddress"> use shipping address</label><br /><br />
	<?php
		foreach( $_SESSION['shipping_info'] as $ship_n=>$ship_v ) {
			echo '<div class="no_display" id="hidden_'.$ship_n.'">'.$ship_v.'</div>';
		}
	?>
	<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="-1"><i>(Required = *)</i></font></td></tr>
	<tr><td align="right">Billing Name *:</td><td align="left"><input type="text" name="bill_name" size="30" maxlength="200" origValue="<?php echo $bill_name; ?>" value="<?php echo $bill_name; ?>"></td></tr>
	<tr><td align="right">Address 1 *:</td><td align="left"><input type="text" name="bill_address1" size="30" maxlength="30" origValue="<?php echo $bill_address1; ?>" value="<?php echo $bill_address1; ?>"></td></tr>
	<tr><td align="right">Address 2:</td><td align="left"><input type="text" name="bill_address2" size="30" maxlength="30" origValue="<?php echo $bill_address2; ?>" value="<?php echo $bill_address2; ?>"></td></tr>
	<tr><td align="right">City *:</td><td align="left"><input type="text" name="bill_city" size="30" maxlength="40" origValue="<?php echo $bill_city; ?>" value="<?php echo $bill_city; ?>"></td></tr>
	<tr><td align="right">State/Province *:</td><td align="left"><select name="bill_state" origValue="<?php echo $bill_state; ?>">
	<option value="">Select state/province</option>
	<?php
	$query = "SELECT * FROM states WHERE status='1' ORDER BY name";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<option value="'.$line["code"].'"';
		if($bill_state == $line["code"]) { echo " SELECTED"; }
		echo '>'.$line["name"].'</option>';
	}

	?>

	</select></td></tr>
	<tr><td align="right">Zip/Postal Code *:</td><td align="left"><input type="text" name="bill_zip" size="10" maxlength="10" origValue="<?php echo $bill_zip; ?>" value="<?php echo $bill_zip; ?>"></td></tr>
	<tr><td align="right">Country *:</td><td align="left"><select name="bill_country" origValue="<?php echo $bill_country; ?>">
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
	</select></td></tr>
	<tr><td align="right" class="style2" width="250">Phone:</td><td align="left"><input type="text" name="bill_phone" size="30" maxlength="30" origValue="<?php echo $bill_phone; ?>" value="<?php echo $bill_phone; ?>"></td></tr>

	<?php
	if (!$member_id) {
	?>
		<tr><td align="right" class="style2">E-Mail Address *:</td><td align="left"><input type="text" name="bill_email" size="30" maxlength="200" value="<?php echo $bill_email; ?>"></td></tr>

		<tr><td colspan="2">&#160;</td></tr>
		<tr><td align="left" colspan="2"><b>Set Your Username &amp; Password</b>&nbsp; &nbsp;<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="-1">You can use this information to log into <?=$website_title?> in the future.</font></td></tr>
		<tr><td align="right">Username *:</td><td align="left"><input type="text" name="new_username" id="new_username" size="30" value="<?=$new_username;?>" /></td></tr>
		<tr><td align="right">New Password *:</td><td align="left"><input type="password" name="new_pw" id="new_pw" size="11" value="<?=$new_pw;?>" minlength="7" maxlength="10" /> <span class="smaller">(7-10 characters)</span></td></tr>
		<tr><td align="right">Confirm New Password *:</td><td align="left"><input type="password" name="confirm_new_pw" id="confirm_new_pw" value="<?=$confirm_new_pw;?>" size="11" minlength="7" maxlength="10" /></td></tr>
	<?php
	}
	else {
		echo '<input type="hidden" name="bill_email" size="30" maxlength="200" value="'.$bill_email.'" />';
	}
	?>


	</table></td></tr>
	<tr><td>&nbsp;</td></tr>

	<?php 
		if ( strpos( strtolower($website_title), "salvia") !==false ) {
	?>
			<tr><td align="left"><b>Warning</b><br>
			<TEXTAREA name="warning" cols="60" rows="3">Warning, This Product:
			-is not intended for use by pregnant/nursing women or any individuals under the age of 18.
			-is intended for use by the buyer only. If the product is shared, it is the buyer's responsibility to see whoever uses the product understands and complies with all warnings and suggestions.
			-is not intended to diagnose, treat, cure, or prevent disease.
			-has not been fully clinically tested or researched.
			-can produce a variety of effects/side effects depending on the individual. These include, but are not exclusive to headache, disassociation, and an altered sense of perception.
			-should be discussed with your physician prior to use.
			-should be used at one's own risk.
			-has not been evaluated by the FDA.

			NOT MEANT FOR CONSUMPTION OR INGESTION. INHALATION OF SMOKE IN ANY FORM IS HARMFUL TO THE LUNGS AND BODY AND MAY CAUSE DAMAGE.</TEXTAREA></td></tr>
			<tr><td align="left"><input type="checkbox" name="warning_agree" id="warning_agree" value="1"<?php if($warning_agree == "1") { echo " CHECKED"; } ?>><label for="warning_agree"> I have read and understand these warnings.</label></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td align="left"><b>Disclaimer</b><br>
			<TEXTAREA name="disclaimer" cols="60" rows="3">By purchasing this product, the buyer acknowledges that they have read and understood all warnings and suggestions provided by <?=$company_name?>. The buyer takes full responsibility for any and all actions/reactions that may occur while using this product and will not hold <?=$company_name?> or any of its affiliates liable in any way.</TEXTAREA></td></tr>
			<tr><td align="left"><input type="checkbox" name="disclaimer_agree" id="disclaimer_agree" value="1"<?php if($disclaimer_agree == "1") { echo " CHECKED"; } ?>><label for="disclaimer_agree"> I agree to this disclaimer.</label></td></tr>
			<tr><td align="left" colspan="2"><input type="checkbox" name="age_agree" id="age_agree" value="1"<?php if($age_agree == "1") { echo " CHECKED"; } ?>><label for="age_agree"> I am 18 years old or older and declare all information I provided to be true and accurate.</label></td></tr>
			<tr><td>&nbsp;</td></tr>
	<?php
		} else {
			echo '<input type="hidden" name="warning_agree" value="1" />';
			echo '<input type="hidden" name="disclaimer_agree" value="1" />';
			echo '<input type="hidden" name="age_agree" value="1" />';
		}
	?>

	<tr><td align="center"><input type="submit" id="submitBtn" value="Submit Order"></td></tr>
	</form>

	<tr><td>&nbsp;</td></tr>
	</table>
	<?php
	include '../includes/foot1.php';
	mysql_close($dbh);

	echo $imgStr;
	?>
	</div>
	</body>
<?php

}//end if $redirectNow

?>
</html>