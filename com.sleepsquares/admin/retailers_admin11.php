<?php
// BME WMS
// Page: Retailers Manager Place Order page
// Path/File: /admin/retailers_admin11.php
// Version: 1.8
// Build: 1804
// Date: 02-06-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/admin_orders_util.php';
include '../includes/wc1.php';
include '../includes/st_and_co1.php';

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
$manager = "retailers";
$page = "Retailers Manager > Place Order Page 2";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($_GET["wholesale_receipt_id"] != "") {
	$wholesale_receipt_id = $_GET["wholesale_receipt_id"];
} else {
	$wholesale_receipt_id = $_POST["wholesale_receipt_id"];
}
$retailer_id = $_POST["retailer_id"];
$submit = $_POST["submit"];
$user_gets_commission = $_POST["user_gets_commission"];
$order_processed_by_person = $_POST["order_processed_by_person"];
$bill_name = $_POST["bill_name"];
$bill_address1 = $_POST["bill_address1"];
$bill_address2 = $_POST["bill_address2"];
$bill_city = $_POST["bill_city"];
$bill_state = $_POST["bill_state"];
$bill_zip = $_POST["bill_zip"];
$bill_country = $_POST["bill_country"];
$bill_phone = $_POST["bill_phone"];
$bill_email = $_POST["bill_email"];
$ship_name = $_POST["ship_name"];
$ship_address1 = $_POST["ship_address1"];
$ship_address2 = $_POST["ship_address2"];
$ship_city = $_POST["ship_city"];
$ship_state = $_POST["ship_state"];
$ship_zip = $_POST["ship_zip"];
$ship_country = $_POST["ship_country"];
$ship_phone = $_POST["ship_phone"];
$delivery = $_POST["delivery"];
$notes = $_POST["notes"];
$pay_type = $_POST["pay_type"];
$prev_used_cc = $_POST["prev_used_cc"];
$cc_type = $_POST["cc_type"];
$cc_first_name = $_POST["cc_first_name"];
$cc_last_name = $_POST["cc_last_name"];
$cc_num = $_POST["cc_num"];
$cid = $_POST["cid"];
$cc_exp_m = $_POST["cc_exp_m"];
$cc_exp_y = $_POST["cc_exp_y"];
$po = $_POST["po"];

$this_user_id = $_COOKIE["wms_user"];

$wholesale_receipt_id = $wholesale_receipt_id;

	$query = "SELECT retailer_id, subtotal, shipping, total FROM wholesale_receipts WHERE wholesale_receipt_id='$wholesale_receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$retailer_id = $line["retailer_id"];
		$subtotal = $line["subtotal"];
		$shipping = $line["shipping"];
		$total = $line["total"];
	}
	mysql_free_result($result);

	$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		foreach ($line as $col=>$val) {
			$$col = $val;
			//echo $col. ' : '.$val.'<br />';
		}
		/*
		$user_gets_commission = $line["user_gets_commission"];
		$store_name = stripslashes($line["store_name"]);
		$contact_name = stripslashes($line["contact_name"]);
		$address1 = stripslashes($line["address1"]);
		$address2 = stripslashes($line["address2"]);
		$city = stripslashes($line["city"]);
		$state = $line["state"];
		$zip = $line["zip"];
		$country = $line["country"];
		$email = $line["email"];
		$phone = $line["phone"];
		$retailer_status = $line["retailer_status"];
		$secure_funds_only = $line["secure_funds_only"];
		*/
	}
	mysql_free_result($result);
    
if ($submit != "") {
	
	//Validate
	$error_txt = "";
	if($bill_name == "") { $error_txt .= "You must enter the Billing Name for this Retailer.<br>\n"; }
	if($bill_address1 == "") { $error_txt .= "You must enter the Billing Address for this Retailer.<br>\n"; }
	if($bill_city == "") { $error_txt .= "You must enter the Billing City for this Retailer.<br>\n"; }
    if($bill_country=='US') {
        if($bill_state == "") { $error_txt .= "You must enter the Billing State for this Retailer.<br>\n"; }
        if($bill_zip == "") { $error_txt .= "You must enter the Billing Zip/Postal Code for this Retailer.<br>\n"; }
    }

	if($ship_name == "") { $error_txt .= "You must enter the Shipping Name for this Retailer.<br>\n"; }
	if($ship_address1 == "") { $error_txt .= "You must enter the Shipping Address for this Retailer.<br>\n"; }
	if($ship_city == "") { $error_txt .= "You must enter the Shipping City for this Retailer.<br>\n"; }
    if($ship_country=='US') {
        if($ship_state == "") { $error_txt .= "You must enter the Shipping State for this Retailer.<br>\n"; }
        if($ship_zip == "") { $error_txt .= "You must enter the Shipping Zip/Postal Code for this Retailer.<br>\n"; }
    }
    
	if($pay_type == "") { $error_txt .= "You must select a Payment Type.<br>\n"; }
	if($pay_type == "cc" && $prev_used_cc == "") {
		if($cc_type == "") { $error_txt .= "You must enter the Type of Credit Card for this Retailer.<br>\n"; }
		if($cc_first_name == "") { $error_txt .= "You must enter the First Name on the Credit Card for this Retailer.<br>\n"; }
		if($cc_last_name == "") { $error_txt .= "You must enter the Last Name on the Credit Card for this Retailer.<br>\n"; }
		if($cc_num == "") { $error_txt .= "You must enter the Credit Card Number on the Credit Card for this Retailer.<br>\n"; }
		if($cid == "") { $error_txt .= "You must enter the Security Code on the Credit Card for this Retailer.<br>\n"; }
		if($cc_exp_m == "") { $error_txt .= "You must enter the Expiration Date Month on the Credit Card for this Retailer.<br>\n"; }
		if($cc_exp_y == "") { $error_txt .= "You must enter the Expiration Date Year on the Credit Card for this Retailer.<br>\n"; }
	}
	
	if($error_txt == "") {
		//Set order number
		$wholesale_order_number = getNextUserID();
        $_SESSION['wholesale_order_number'] = $wholesale_order_number;

		$cc_trans_id = "";

        //********************************************
		if($pay_type == "cc") { 
			$query = "SELECT status, company, url, username, password FROM merchant_acct WHERE status='1' LIMIT 1";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$status = $line["status"];
				$company = $line["company"];
				$merchant_url = $line["url"];
				$merchant_username = $line["username"];
				$merchant_password = $line["password"];
				$auth_net_tran_key = $line["password"];
			}
			mysql_free_result($result);

			if($company == 1) {
				//Send to Authorize.net Merchant Account for Verification
				
				$authnet_values				= array
				(
					"x_login"				=> $merchant_username,
					"x_version"				=> "3.1",
					"x_delim_char"			=> "|",
					"x_delim_data"			=> "TRUE",
					"x_url"					=> "FALSE",
					"x_type"				=> "AUTH_CAPTURE",
					"x_method"				=> "CC",
					"x_tran_key"			=> $auth_net_tran_key,
					"x_relay_response"		=> "FALSE",
					"x_card_num"			=> urlencode($cc_num),
					"x_exp_date"			=>  urlencode($cc_exp_m) . urlencode($cc_exp_y),
					//"x_description"			=> "Recycled Toner Cartridges",
					"x_amount"				=> urlencode($total),
					"x_first_name"			=> urlencode($cc_first_name),
					"x_last_name"			=> urlencode($cc_last_name),
					"x_address"				=> urlencode($bill_address1),
					"x_city"				=> urlencode($bill_city),
					"x_state"				=> urlencode($bill_state),
					"x_zip"					=> urlencode($bill_zip),
					"x_country"				=> urlencode($bill_country),
					//"CustomerBirthMonth"	=> "Customer Birth Month: 12",
					//"CustomerBirthDay"		=> "Customer Birth Day: 1",
					//"CustomerBirthYear"		=> "Customer Birth Year: 1959",
					//"SpecialCode"			=> "Promotion: Spring Sale",
				);

				$fields = "";
				foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

				$ch = curl_init($merchant_url); 
				###  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
				### $ch = curl_init("https://secure.authorize.net/gateway/transact.dll"); 
				curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
				curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
				### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
				$page = urldecode(curl_exec($ch)); //execute post and get results
				if(curl_errno($ch)) { 
					$error_txt .= curl_error($ch); 
				}
				curl_close($ch);
				
				$page2 = explode("|", $page);
				
				if($page2[0] == "3" || $page2[0] == "2") {
					$error_txt .= "Sorry, there was a problem with the credit card information you entered: \"" . $page2[3] . "\"<br>\n";
				}
				
				$cc_auth_code = $page2[4];
				$cc_trans_id = $page2[6];
				
			}
			else if($company == 2) {
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

				curl_setopt($c, CURLOPT_POSTFIELDS, 'M_id='.$merchant_username.'&M_key='.$merchant_password.'&C_name=' . urlencode($cc_first_name) . urlencode(' ') . urlencode($cc_last_name) . '&C_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&C_city=' . urlencode($bill_city) . '&C_state=' . urlencode($tmp_bill_state) . '&C_zip=' . urlencode($tmp_bill_zip) . '&C_country=' . urlencode($bill_country) . '&C_email=' . urlencode($bill_email) . '&C_cardnumber=' . urlencode($cc_num) . '&C_exp=' . urlencode($cc_exp_m) . urlencode(substr($cc_exp_y, 2, 2)) . '&T_amt=' . urlencode($total) . '&T_code=' . '01' . '&T_ordernum=' . urlencode($wholesale_order_number) . $tmp_cid);
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

        //********************************************
        
        if($error_txt == "") {

            $query = "UPDATE wholesale_receipts";
            $query .= " SET wholesale_order_number='$wholesale_order_number',";
            $query .= " complete='1',";
            $query .= " user_gets_commission='$user_gets_commission',";
            $query .= " order_processed_by_person='$order_processed_by_person',";
            $query .= " bill_name='".$bill_name."',";
            $query .= " bill_address1='".$bill_address1."',";
            $query .= " bill_address2='".$bill_address2."',";
            $query .= " bill_city='".$bill_city."',";
            $query .= " bill_state='$bill_state',";
            $query .= " bill_zip='$bill_zip',";
            $query .= " bill_country='$bill_country',";
            $query .= " bill_phone='$bill_phone',";
            $query .= " bill_email='$bill_email',";
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
            if ( $pay_type=='cc' ) {
                $query .= " cc_num='"."XXXXXXXXXXXX".substr($cc_num, -4)."',";
            }
            else {
                $query .= " cc_num='',";
            }
            $query .= " po='$po',";
			$query .= " cid='$cid',";
            $query .= " cc_exp_m='$cc_exp_m',";
            $query .= " cc_exp_y='$cc_exp_y',";
            $query .= " cc_auth_code='$cc_auth_code'";
			
			// added for refunds
			if($cc_trans_id != ""){
				$query .= ", cc_trans_id='$cc_trans_id'";
			}
			

            if ( $pay_type == "cc" || $pay_type == "scd" ) {
                $query .= ", funds_received=NOW()";
            }

            $query .= " WHERE wholesale_receipt_id='$wholesale_receipt_id'";
            $result = mysql_query($query) or die("Query failed 1 : " . mysql_error());
            
			if ( mysql_affected_rows()>0 ) {
				foreach($_POST as $fld_name=>$fld_val) {
					if ( strpos($fld_name, "comm_calc_")!==false )  {
						$vals = split( "\|", $fld_val);
						$queryComm = "INSERT INTO wholesale_commissions (rep_id, wholesale_order_number, commission_pct, commission_earned) VALUES ";
						$queryComm .=" ('".$vals[0]."', '".$wholesale_order_number."', ".$vals[1].", ".$vals[2].")";
						mysql_query($queryComm) or die("Query failed : " . mysql_error());
					}
				}
			}

            $query = "SELECT sku, quantity FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
            $result = mysql_query($query) or die("Query failed : " . mysql_error());
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $tmp_sku = $line["sku"];
                $tmp_quantity = $line["quantity"];
                    
                $query2 = "UPDATE product_skus SET stock = stock - $tmp_quantity WHERE sku='$tmp_sku'";
                $result2 = mysql_query($query2) or die("Query failed : " . mysql_error());

            }
            mysql_free_result($result);

            $query = "SELECT name, stock, threshold FROM product_skus";
            $result = mysql_query($query) or die("Query failed : " . mysql_error());
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $temp_val1 = $line["stock"] - $line["threshold"];
                if($temp_val1 < 0) {
                    //Send Email
                    $query2 = "SELECT content, subject, email, emailto FROM inventory_emails WHERE inemails_id='1'";
                    $result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
                    while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                        $content = $line2["content"];
                        $subject = $line2["subject"];
                        $email_tmp = $line2["email"];
                        $emailto_tmp = $line2["emailto"];
                    }
                    mysql_free_result($result2);

                    $email_str .= $content;
                    $email_str .= "\n\n";
                    $email_str .= "Product: ";
                    $email_str .= $line["name"];
                    $email_str .= " has only ";
                    $email_str .= $line["stock"];
                    $email_str .= " items remaining in stock. It is time to replenish the inventory ";
                    $email_str .= "for this item.";
                    $email_str .= "\n\n";
            
                    $email_subj = $subject;
                    $email_from = "FROM: " . $email_tmp;
                    mail($emailto_tmp, $email_subj, $email_str, $email_from);
                }
            }
            mysql_free_result($result);


            //*******************************
            //email receipt string
            $email_str = "";
                $query = "SELECT * FROM wholesale_receipts WHERE wholesale_receipt_id='$wholesale_receipt_id' AND retailer_id='$retailer_id' AND complete='1'";
                $result = mysql_query($query) or die("Query failed : $query" . mysql_error());
                while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $shipping = $line["shipping"];
                    $discount = $line["discount"];
					$credit_used = $line["credit_used"];

                    $ship_state = $line["ship_state"];
                    if($line["cc_first_name"] == "" || $line["cc_last_name"] == "") {
                        $email_str .= "Dear " . stripslashes($line["bill_name"]) . ",\n\n";
                    } else {
                        $email_str .= "Dear " . $line["cc_first_name"] . " " . $line["cc_last_name"] . ",\n\n";

                    }
                    $email_str .= "Thank you for your ".$website_title." Wholesale order. Your Order Confirmation Number and Order Number is " . $wholesale_order_number . ".  Please keep a copy of this email for your records. If you have any questions please call us toll free at ".$company_phone.".\n\n";
                    $email_str .= "Bill To:\n";
                    
                    $email_str .= stripslashes($line["bill_name"]) . "\n";
                    
                    $email_str .= $line["bill_address1"] . "\n";
                    
                    if ($line["bill_address2"]) {
                        $email_str .= $line["bill_address2"] . "\n";
                    }
                    $email_str .= $line["bill_city"] . ", " . $line["bill_state"] . "\n";
                    
                    $email_str .= $line["bill_zip"] . ", " . $line["bill_country"] . "\n\n";


                    $email_str .= "Ship To:\n";
                    $email_str .= stripslashes($line["ship_name"]) . "\n";
                    
                    $email_str .= $line["ship_address1"] . "\n";
                    
                    if ($line["ship_address2"]) {
                        $email_str .= $line["ship_address2"] . "\n";
                    }
                    $email_str .= $line["ship_city"] . ", " . $line["ship_state"] . "\n";
                    
                    $email_str .= $line["ship_zip"] . ", " . $line["ship_country"] . "\n\n";

                    if($line["pay_type"] == "cc") {
	                    $email_str .= "Payment Information:\n";
						$email_str .= $line["cc_first_name"] . " " . $line["cc_last_name"] . "\n";
                        
                        if ($line["cc_type"] == "mc") {
                            $email_str .= "Mastercard";
                        } elseif ($line["cc_type"] == "vi") {
                            $email_str .= "Visa";
                        } elseif ($line["cc_type"] == "am") {
                            $email_str .= "American Express";
                        } elseif ($line["cc_type"] == "di") {
                            $email_str .= "Discover";
                        }
                        $email_str .= "\n";
                        
                        //$tmp_cc_num = $line["cc_num"];
                        //$tmp_cc_num = substr($tmp_cc_num, -4);
                        //$email_str .= "XXXXXXXXXXXX" . $tmp_cc_num . "\n\n";
                        $email_str .= $line["cc_num"] . "\n\n";
                        
                    } elseif ($line["pay_type"] == "chk") {
	                    $email_str .= "Payment Information:\n";
                        $email_str .= "When paying by Check please print a second copy of your receipt, enclose with your Check and send to:\n";
                        $email_str .= $company_name."\n";
                        $email_str .= $company_address."\n";
                        $email_str .= $company_city_state_zip."\n";
                        $email_str .= "[Note: Your order will ship as soon as we receive your Check]\n\n";
                    }
                            
                    if ($line["delivery"] !== "") {
                        $email_str .= "Delivery Information\n";
                        $email_str .= $line["delivery"] . "\n\n";
                    }
                    $bill_email = $line["bill_email"];

					if ($line["discount_pct"]) {
						$email_str .= "Unit price(s) reflect a ";
						$email_str .= ( $line["discount_pct"] * 100 );
						$email_str .= "% discount\n";
					}
				}

                //$email_str .= "Product                                               Quantity    Price    Sub-Total\n";
                $subtotal = 0;
                $query = "SELECT wholesale_receipt_items.sku as sku, wholesale_receipt_items.quantity as quantity, wholesale_receipt_items.price as price, wholesale_receipt_items.name as name FROM wholesale_receipts, wholesale_receipt_items WHERE wholesale_receipts.wholesale_receipt_id=wholesale_receipt_items.wholesale_receipt_id AND wholesale_receipts.wholesale_receipt_id='$wholesale_receipt_id' AND wholesale_receipts.retailer_id='$retailer_id' AND wholesale_receipts.complete='1'";
                $resultItems = mysql_query($query) or die("Query failed : $query" . mysql_error());
                while ($line2 = mysql_fetch_array($resultItems, MYSQL_ASSOC)) {

                    $tmp_sku = $line2["sku"];
                    
                    //find product url
                    $query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
                    $result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
                    while ($line22 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                       foreach ($line22 as $col_value2) {
                           $url = "$col_value2";
                           $url = substr($url, 1);
                           $url = $base_url . "wc" . $url;
                       }
                    }
                    mysql_free_result($result2);

                    $email_str .= $line2["name"] . " SKU: " . $tmp_sku . "                     ";
                    
                    $tmp_quantity = $line2["quantity"];
                    if($buy_one_get_one_free == '1') {
                        $tmp_quantity = $tmp_quantity * 2;
                    }
                    $email_str .= $tmp_quantity;
                    
                    $tmp_price = $line2["price"];
                    $tmp_price = condDecimalFormat( $tmp_price);
                    $email_str .= " x $" . $tmp_price . "                     ";
                    
                    $tmp_subtotal = $line2["quantity"] * $tmp_price;
                    $tmp_subtotal = condDecimalFormat( $tmp_subtotal);
                    $email_str .= "Subtotal: $" . $tmp_subtotal . "\n";                   
                    $subtotal = $subtotal + $tmp_subtotal;
                }
                
                $email_str .= "Sub-Total: $" . condDecimalFormat( $subtotal) ."\n";
                if ( $discount != 0 )  {
                    $email_str .= "Additional Discount: -$" . condDecimalFormat( $discount)."\n";
                }

                if ( $credit_used != 0 )  {
                    $email_str .= "Credit Used: -$" . condDecimalFormat( $credit_used)."\n";

					$queryUpC = "UPDATE retailer SET credit=0 WHERE retailer_id='$retailer_id'";
					$resultUpC = mysql_query($queryUpC) or die("Query failed : $query" . mysql_error());
                }

                $email_str .= "Shipping: $".$shipping."\n";

                $total = condDecimalFormat( $total);
                $email_str .= "Total: $" . $total . "\n\n";

                $email_str .= "Note: All charges will appear as ".$company_name.", the company that produces $product_name.\n\n\n";
                $email_subj = "Your ".$website_title." Wholesale Catalog Order";
                $email_from = "FROM: ".$site_email;

                mail($bill_email, $email_subj, $email_str, $email_from);

            //*******************************

            // Send to retailers page
            header("Location: " . $base_url . "admin/retailers_admin4.php?retailer_id=" . $retailer_id);
            exit;
        }
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
    <script type="text/javascript" src="/includes/jquery.js"></script>
    <script type="text/javascript" src="/includes/wmsform.js"></script>
    
    <script language="JavaScript">
        $(function() {//on doc ready
            $('#ship_country').val( "<?=$country?>" );
            $('#bill_country').val( "<?=$country?>" );
        });
    </script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Complete Order for the selected Retailer.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><font size="2"><b>Place Retailer Wholesale Order Form</b></font></td></tr>

<form name="form1" action="./retailers_admin11.php" method="POST">
<input type="hidden" name="wholesale_receipt_id" value="<?php echo $wholesale_receipt_id; ?>">
<input type="hidden" name="user_gets_commission" value="<?php echo $user_gets_commission; ?>">
<input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">
<tr><td align="left"><table border="0">
<tr><td colspan="2"><font size="2"><b>Billing Information</b></font></td><td>&nbsp;</td><td colspan="2"><font size="2"><b>Shipping Information</b></font></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Name:</font></td><td><input type="text" name="bill_name" size="30" maxlength="200" value="<?php echo stripslashes($store_name) . " - Attn: " . $contact_name; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Name:</font></td><td><input type="text" name="ship_name" size="30" maxlength="200" value="<?php echo stripslashes($store_name) . " - Attn: " . $contact_name; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Address1:</font></td><td><input type="text" name="bill_address1" size="30" maxlength="30" value="<?php echo $address1; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Address1:</font></td><td><input type="text" name="ship_address1" size="30" maxlength="30" value="<?php echo $address1; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Address2:</font></td><td><input type="text" name="bill_address2" size="30" maxlength="30" value="<?php echo $address2; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Address2:</font></td><td><input type="text" name="ship_address2" size="30" maxlength="30" value="<?php echo $address2; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">City:</font></td><td><input type="text" name="bill_city" size="30" maxlength="40" value="<?php echo $city; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">City:</font></td><td><input type="text" name="ship_city" size="30" maxlength="40" value="<?php echo $city; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">State/Province:</font></td><td><select name="bill_state">
<option value="">Select a state</option>
<?php
//$query = "SELECT * FROM states WHERE status='1'";
$query = "SELECT * FROM states";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<option value="'.$line["code"].'"';
	if($state == $line["code"]) { echo " SELECTED"; }
	echo '>'.$line["name"].'</option>';
}

?>
</select></td><td>&nbsp;</td>

<td NOWRAP><font face="Arial" size="+1">State/Province:</font></td><td><select name="ship_state">
<option value="">Select a state</option>
<?php
//$query = "SELECT * FROM states WHERE status='1'";
$query = "SELECT * FROM states";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<option value="'.$line["code"].'"';
	if($state == $line["code"]) { echo " SELECTED"; }
	echo '>'.$line["name"].'</option>';
}

?>
</select></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Zip/Postal Code:</font></td><td><input type="text" name="bill_zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Zip/Postal Code:</font></td><td><input type="text" name="ship_zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Country:</font></td><td><select name="bill_country" id="bill_country">
<?php
	country_build_all($country);
?>
</select></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Country:</font></td><td><select name="ship_country" id="ship_country">
<?php
	country_build_all($country);
?>
</select></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">Phone:</font></td><td><input type="text" name="bill_phone" size="30" maxlength="30" value="<?php echo $phone; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Phone:</font></td><td><input type="text" name="ship_phone" size="30" maxlength="30" value="<?php echo $phone; ?>"></td></tr>
<tr><td NOWRAP><font face="Arial" size="+1">E-Mail:</font></td><td><input type="text" name="bill_email" size="30" maxlength="200" value="<?php echo $email; ?>"></td><td>&nbsp;</td><td NOWRAP><font face="Arial" size="+1">Delivery Instructions:</font></td><td><input type="text" name="delivery" size="30" maxlength="255" value="<?php echo $delivery; ?>"></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>

<tr><td colspan="2" align="right"><font face="Arial" size="+1">Purchase Order #:</font></td><td>&nbsp;</td><td colspan="2"><input type="text" name="po" size="30" maxlength="20"></td></tr>

<tr valign="top"><td colspan="2" align="right"><font face="Arial" size="+1">Notes (Internal Use Only):</font></td><td>&nbsp;</td><td colspan="2"><textarea name="notes" rows="4" cols="40"></textarea></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>

<tr><td colspan="5" align="center"><font face="Arial" size="+1"><b>Payment Information</b></font></td></tr>
<tr><td>&#160;</td></tr>
<tr valign="top"><td colspan="2" align="right"><font face="Arial" size="+1">Payment Type:</font></td><td>&nbsp;</td><td colspan="2">
<?php
	echo displayPayOptions('pay_type', $secure_funds_only);
?>
<div>If retailer is not paying by Credit Card, you do not need to fill out the Credit Card information fields below.</div><br />
</td></tr>
<?php
/*
    $fnd_cc_type_count = count($fnd_cc_type);
    if($fnd_cc_type_count > 0) {
    ?>
        <tr><td colspan="5" align="center"><font face="Arial" size="-1">If retailer previously paid by Credit Card,<br>then you may select to use the same Credit Card for this order.</font></td></tr>
        <tr><td colspan="2" align="right"><font face="Arial" size="+1">Previously Used Credit Card(s):</font></td><td>&nbsp;</td><td colspan="2"><select name="prev_used_cc">
        <option value="">Select a Credit Card</option>
        <?php
        for($i = 0; $i < $fnd_cc_type_count; $i++) {
            $tmp_fnd_cc_num = substr($fnd_cc_num[$i], -4);
            echo "<option value=\"$i\">";
            echo $fnd_cc_type[$i];
            //echo " ****";
            //echo $tmp_fnd_cc_num;
            echo " ";
            echo $fnd_cc_num[$i];
            echo " ";
            echo $fnd_cc_first_name[$i];
            echo " ";
            echo $fnd_cc_last_name[$i];
            echo " ";
            echo $fnd_cid[$i];
            echo " ";
            echo $fnd_cc_exp_m[$i];
            echo "/";
            echo $fnd_cc_exp_y[$i];
            echo "</option>\n";
        }
        ?>
        </select></td></tr>
        <tr><td colspan="5" align="center"><font face="Arial" size="-1">If you select a previously used Credit Card,<br>then you do not need to fill out the Credit Card information fields below.</font></td></tr>
    <?php
    }
*/
?>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Credit Card Type:</font></td><td>&nbsp;</td><td colspan="2"><select name="cc_type">
<option value=""></option>
<option value="mc">Mastercard</option>
<option value="vi">Visa</option>
<option value="am">American Express</option>
<option value="di">Discover</option>
</select></td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">First Name on Credit Card:</font></td><td>&nbsp;</td><td colspan="2"><input type="text" name="cc_first_name" size="30" maxlength="50"></td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Last Name on Credit Card:</font></td><td>&nbsp;</td><td colspan="2"><input type="text" name="cc_last_name" size="30" maxlength="50"></td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Credit Card Number:</font></td><td>&nbsp;</td><td colspan="2"><input type="text" name="cc_num" size="16" maxlength="20"></td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Security Code:</font></td><td>&nbsp;</td><td colspan="2"><input type="text" name="cid" size="4" maxlength="4"></td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Expiration Date:</font></td><td>&nbsp;</td><td colspan="2"><SELECT name="cc_exp_m">
<option value=""></option>
<option value="01">January - 01</option>
<option value="02">February - 02</option>
<option value="03">March - 03</option>
<option value="04">April - 04</option>
<option value="05">May - 05</option>
<option value="06">June - 06</option>
<option value="07">July - 07</option>
<option value="08">August - 08</option>
<option value="09">September - 09</option>
<option value="10">October - 10</option>
<option value="11">November - 11</option>
<option value="12">December - 12</option>
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
</select></td></tr>

<tr><td colspan="5">&nbsp;</td></tr>
<tr><td colspan="2" align="right"><font face="Arial" size="+1">Order Processed By:</font></td><td>&nbsp;</td><td colspan="2"><select name="order_processed_by_person">
<?php
$query = "SELECT user_id, first_name, last_name FROM wms_users WHERE status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$user_id = $line["user_id"];
	$first_name = $line["first_name"];
	$last_name = $line["last_name"];
	echo "<option value=\"" . $user_id . "\"";
	if($this_user_id == $user_id) { echo " SELECTED"; }
	echo ">" . $first_name . " " . $last_name . "</option>\n";
}
?>
</select></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td colspan="5" align="center">
<div class="">
<?php
	calcAndShowCommission();
?>
</div>
<input type="submit" name="submit" value=" Finish Order "></td></tr>
</form>
</table></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>