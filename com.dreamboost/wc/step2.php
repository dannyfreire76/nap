<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /wc/step2.php
// Version: 1.1
// Build: 1106
// Date: 01-19-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/admin_orders_util.php';
include '../includes/wc1.php';

check_wholesale_login();

foreach($_POST as $fld_name=>$fld_val) {//declare new vars from POST vars
	$$fld_name = $fld_val;
	//echo $fld_name." = ".$fld_val.'<br />';
}

if (!$ajax) {
	$ship_vals = split( "\|", $shipping_method );
	$shipping_method = $ship_vals[0];
	$shipping = $ship_vals[1];
}

if ( !$wholesale_receipt_id ) {
	$wholesale_receipt_id = $_SESSION['wholesale_receipt_id'];
}

// read ship_main
$query = "SELECT * FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$main_tax = $line["tax"];
	$instate_tax = $line["instate_tax"];
	$state_tax = $line["state_tax"];
	$free_shipping_wholesale = $line["free_shipping_wholesale"];
	$free_wholesale_ship_limit = $line["free_wholesale_ship_limit"];
}
mysql_free_result($result);

// read promo main
$query = "SELECT buy_one_get_one_free FROM promo_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$buy_one_get_one_free = $line["buy_one_get_one_free"];
}
mysql_free_result($result);

$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
$result = mysql_query($query) or die("Query failed : $query" . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
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
    $secure_funds_only = $line["secure_funds_only"];
	$credit_in_db = $line["credit"];
}
mysql_free_result($result);

$total = $subtotal + $shipping + $tax;
if ( $apply_credit ) {
	$total -= $credit*1;
}
$total = sprintf("%01.2f", $total);
	
if($step2_submit != "") {
	//Validate Fields
	$error_txt = "";
	if(!$pay_type) { $error_txt .= "You must enter the payment type in the <b>Payment Type</b> field.<br>\n"; }
	if($pay_type == "cc") { 
		if(!$cc_type) { $error_txt .= "You must enter the credit card type in the <b>Credit Card Type</b> field.<br>\n"; }
		if(!$cc_first_name) { $error_txt .= "You must enter your first name in the <b>Your First Name on Credit Card</b> field.<br>\n"; }
		if(!$cc_last_name) { $error_txt .= "You must enter your last name in the <b>Your Last Name on Credit Card</b> field.<br>\n"; }
		if(!$cc_num) { $error_txt .= "You must enter the credit card number in the <b>Credit Card Number</b> field.<br>\n"; }
		if(!$cid) { $error_txt .= "You must enter your credit card's security code in the <b>Security Code</b> field.<br>\n"; }
		if(!$cc_exp_m) { $error_txt .= "You must enter the credit card expiration month in the <b>Credit Card Expiration Month</b> field.<br>\n"; }
		if(!$cc_exp_y) { $error_txt .= "You must enter the credit card expiration year in the <b>Credit Card Expiration Year</b> field.<br>\n"; }
	}
	if(!$bill_name) { $error_txt .= "You must enter the billing name in the <b>Billing Name</b> field.<br>\n"; }
	if(!$bill_address1) { $error_txt .= "You must enter the billing address in the <b>Billing Address1</b> field.<br>\n"; }
	if(!$bill_city) { $error_txt .= "You must enter the billing city in the <b>Billing City</b> field.<br>\n"; }
	if(!$bill_state) { $error_txt .= "You must enter the billing state in the <b>Billing State</b> field.<br>\n"; }
	if(!$bill_zip) { $error_txt .= "You must enter the billing zip/postal code in the <b>Billing Zip/Postal Code</b> field.<br>\n"; }
	if(!$bill_country) { $error_txt .= "You must enter the billing country in the <b>Billing Country</b> field.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$bill_email) ){
		$error_txt .= "You must enter your email address in the <b>Email Address</b> field.<br>\n";
	}

	if ( $apply_credit ) {//check that user didn't change hidden field's value			
		if ( $credit != $credit_in_db ) {
			$error_txt .= "You don't appear to have the amount of credit you tried to apply.<br>\n";			
		}
	}



	//Check for Errors
	if($error_txt == "") {
		//Set order number

		if ( !$ajax ) {//the ajax call from wc/my/order_history.php already has the wholesale_order_number
			$wholesale_order_number = getNextUserID();
		}
        $_SESSION['wholesale_order_number'] = $wholesale_order_number;

		foreach($_POST as $fld_name=>$fld_val) {
			if ( strpos($fld_name, "comm_calc_")!==false )  {
				$vals = split( "\|", $fld_val);
				$queryComm = "INSERT INTO wholesale_commissions (rep_id, wholesale_order_number, commission_pct, commission_earned) VALUES ";
				$queryComm .=" ('".$vals[0]."', '".$wholesale_order_number."', ".$vals[1].", ".$vals[2].")";
				mysql_query($queryComm) or die("Query failed : " . mysql_error());
			}
		}

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
					$error_txt .= "Sorry, there was a problem with the credit card information you entered: " . $page2[3] . "<br>\n";
				}
				
				$cc_auth_code = $page2[4];
			} else if($company == 2) {
				//Send to Sage Payments Merchant Account for Verification
				$c = curl_init($merchant_url);
				curl_setopt($c, CURLOPT_HEADER, 0);
				curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($c, CURLOPT_POSTFIELDS, 'M_id='.$merchant_username.'&M_key='.$merchant_password.'&C_name=' . urlencode($cc_first_name) . urlencode(' ') . urlencode($cc_last_name) . '&C_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&C_city=' . urlencode($bill_city) . '&C_state=' . urlencode($bill_state) . '&C_zip=' . urlencode($bill_zip) . '&C_country=' . urlencode($bill_country) . '&C_email=' . urlencode($bill_email) . '&C_cardnumber=' . urlencode($cc_num) . '&C_exp=' . urlencode($cc_exp_m) . urlencode(substr($cc_exp_y, 2, 2)) . '&T_amt=' . urlencode($total) . '&T_code=' . '01' . '&T_ordernum=' . urlencode($wholesale_order_number) . '&C_cvv=' . urlencode($cid));
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
					$error_txt .= "Error, There was a problem with the credit card information you entered, because " . substr($page, 8, 32) . "<br>\n";
				} else {
					$cc_auth_code = substr($page, 2, 6);
				}
			}
		}
	}

	if($error_txt == "") {//check for Errors
        //Write to receipts DB
		if (!$ajax) {
			$shipping_method_desc = 'unspecified';
			$queryShipDesc = "SELECT name FROM ship_method_wholesale WHERE ship_method_id='$shipping_method'";
			$resultShip = mysql_query($queryShipDesc) or die("Query failed : " . mysql_error());
			while ($lineShip = mysql_fetch_array($resultShip, MYSQL_ASSOC)) {
				$shipping_method_desc = $lineShip["name"];
			}
		}

		$now = date("Y-m-d H:i:s");
		$query = "UPDATE wholesale_receipts SET wholesale_order_number='$wholesale_order_number', ordered='$now', complete='1', pay_type='$pay_type', cc_type='$cc_type', cc_first_name='$cc_first_name', cc_last_name='$cc_last_name', cc_num='".( $cc_num!='' ? "XXXXXXXXXXXX".substr($cc_num, -4):'' )."', cid='$cid', cc_exp_m='$cc_exp_m', cc_exp_y='$cc_exp_y', cc_auth_code='$cc_auth_code', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', bill_email='$bill_email', shipping_method='$shipping_method_desc', item_count='$item_count', subtotal='$subtotal', shipping='$shipping', tax='$tax', total='$total', discount_code='$discount_code', discount_pct=$discount_pct ";
		
        if ( $pay_type == "cc" || $pay_type == "scd" ) {
            $query .= ", funds_received=NOW()";
        }

		if ( $apply_credit ) {
			$query .= ", credit_used=$credit_in_db";
		}

		$query .= " WHERE wholesale_receipt_id='$wholesale_receipt_id'";
		$result = mysql_query($query) or die("Query failed : $query" . mysql_error());

		if ( $apply_credit ) {//retailer just used their credits up
			$queryUpC = "UPDATE retailer SET credit=0 WHERE retailer_id='$retailer_id'";
			$resultUpC = mysql_query($queryUpC) or die("Query failed : $query" . mysql_error());
		}


		if ( !$ajax ) {//the ajax call from wc/my/order_history.php already has all this info, so skip it
			//Write to receipt_items DB
			$now = date("Y-m-d H:i:s");
			$i = 1;
			while ($i <= $item_count) {
				$tmp_sku = $_POST["prod_sku_".$i];
				$tmp_quantity =  $_POST["prod_quantity_".$i];
				$tmp_price = $_POST["prod_price_".$i];
				$tmp_orig_price = $_POST["prod_orig_price_".$i];
				$tmp_name = $_POST["prod_name_".$i];
				$query = "INSERT INTO wholesale_receipt_items SET wholesale_receipt_id='$wholesale_receipt_id', created='$now', sku='$tmp_sku', quantity='$tmp_quantity', price='$tmp_price', orig_price=$tmp_orig_price, name='$tmp_name'";
				$result = mysql_query($query) or die("Query failed : $query<br /><br />" . mysql_error());
				$i++;

				$query2 = "UPDATE product_skus SET stock = (stock - $tmp_quantity) WHERE sku='$tmp_sku'";
				$result2 = mysql_query($query2) or die("Query failed : $query2" . mysql_error());
			}

			$query = "SELECT name, stock, threshold FROM product_skus";
			$result = mysql_query($query) or die("Query failed : $query" . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$temp_val1 = $line["stock"] - $line["threshold"];
				if($temp_val1 < 0) {
					//Send Email
					$query2 = "SELECT content, subject, email, emailto FROM inventory_emails WHERE inemails_id='1'";
					$result2 = mysql_query($query2) or die("Query failed : $query2" . mysql_error());
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
					
			//Goto Thanks
			unset($_SESSION['ship_state']);
			header("Location: " . $base_secure_url . "wc/confirm.php");
			exit;
		}
	}

	if ( $ajax ) {
		if($error_txt == "") {
			echo 'ok';
		}
		else {
			echo $error_txt;
		}
		exit();
	}

}

if($bill_name == "") {
	$bill_name = $store_name . " - Attn: " . $contact_name;
}
if($bill_address1 == "") {
	$bill_address1 = $address1;
}
if($bill_address2 == "") {
	$bill_address2 = $address2;
}
if($bill_city == "") {
	$bill_city = $city;
}
if($bill_state == "") {
	$bill_state = $state;
}
if($bill_zip == "") {
	$bill_zip = $zip;
}
if($bill_country == "") {
	$bill_country = $country;
}
if($bill_email == "") {
	$bill_email = $email;
}
if($bill_phone == "") {
	$bill_phone = $phone;
}

$disc_attr = get_wc_discount();
$discount_code = $disc_attr[0];
$percent_off = $disc_attr[1];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Online Wholesale Catalog - Checkout</title>

<?php
include '../includes/meta1.php';
?>

<script type="text/javascript">
    $(function() {//on doc ready
        WC.init();
    });

    var WC = new function() {
        this.init = function() {
            $('#shipQuotesLoad').BlindUp(200);

            if ( $('#shipping_method').size()>0 ) {
                if ( $('#shipping_method').get(0).options.length==0 ) {
                    $('#sh_wrapper').html('<span class="error">Unable to calculate, we will charge you later.</span>');
                }

        		var ship_url = $('#more_ship_options').attr('ship_url');
                $('#more_ship_options').click(function(){
                    $(this).small_spinner_on_bg();
                    $.get( ship_url, function(data){
			            var selected_option = $("select[@id=shipping_method] option[@selected]");
			            var selected_val = $(selected_option).val();

						$('#shipping_method').append(data)
						//b/c of some wackiness, we have to reselect the correct option:
						$("select[@id=shipping_method] option").each(function(){
							if ( $(this).val()==selected_val ) {
								$(this).attr('selected', 'true');
							}
							else {
								$(this).removeAttr('selected');
							}
						})

                        WC.reCalcTotal();

                        $('#more_ship_options').BlindUp(300, function(){
							$('select[@id=shipping_method]').addClass('highlighted');
							setTimeout("$('select[@id=shipping_method]').removeClass('highlighted').addClass('black')", 3000)
						});
                    } );            
                })
            }

			$('#apply_credit').each(function(){
				if ( $(this).is(':checked') ) {
					$('#credit_shown').removeClass('gray');
					WC.reCalcTotal();
				}

				$(this).click ( function() {
					if ( $(this).is(':checked') ) {
						$('#credit_shown').removeClass('gray');
					} else {
						$('#credit_shown').addClass('gray');
					}
					WC.reCalcTotal();
				});
			});
        }

        this.reCalcTotal = function() {
            var selected_option = $("select[@id=shipping_method] option[@selected]");
            var selected_valarr = $(selected_option).val().split("|");
            var selected_val = r2(selected_valarr[1]);
            //$('#shipping_shown').html( '$'+selected_val );
            
            var total_val = ($('#tax').val() * 1) + ($('#subtotal').val() * 1) + (selected_val *1);
		
			if ( $('#apply_credit').is(':checked') ) {
				total_val -= $('#credit').val() * 1;
			}

			total_val = r2(total_val);
            $('#total').val( total_val );
            $('#total_shown').html( '' );
            $('#total_shown').html( '$'+total_val );
        }
    }

    function r2(n) {

      ans = n * 1000
      ans = Math.round(ans /10) + ""
      while (ans.length < 3) {ans = "0" + ans}
      len = ans.length
      ans = ans.substring(0,len-2) + "." + ans.substring(len-2,len)
      return ans
    } 

</script>

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
?>

<div id="shipQuotesLoad" class="bold">
    Retrieving shipping quotes...
</div>

<table border="0">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Checkout: Step 2 - Review Order and Payment Information</font></td></tr>

<?php

$query = "SELECT cart_id, quantity, sku, name, price_lvl FROM wc_cart WHERE retailer_id='$retailer_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

if ( $percent_off != 0 && mysql_num_rows($result)>0 ) {
    echo '<tr><td class="error3" align="left"><br />Discount code '.$discount_code.' applied: '.($percent_off * 100).'% off<br /></td></tr>';
}

if($step2_submit != "") {
	if($error_txt != "") {
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\"><span class=\"error\">$error_txt</span></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
	}
}
?>

<tr><td align="left"><table border="0" id="checkout_table">
<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Product</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Quantity</b></font></td><td align="center"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Price</b></font></td><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Sub-Total</b></font></td></tr>

<form action="<?php echo $base_secure_url; ?>wc/step2.php" method="POST" id="step2_form" name="step2_form">

<?php
echo '<input type="hidden" name="discount_pct" id="discount_pct" value="'.$percent_off.'" />';

$percent_off = 1 - $percent_off;


echo '<input type="hidden" name="discount_code" id="discount_code" value="'.$discount_code.'" />';
$subtotal = 0;
$item_count = 0;
$tot_weight = 0;
$tot_qty = 0;

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_sku = $line["sku"];
		$tmp_name = $line["name"];
        $tmp_quantity = $line["quantity"];
		$query2 = "SELECT weight FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$tmp_weight = $line2["weight"];
		}
		mysql_free_result($result2);
		
		$tmp_weight = $tmp_weight * $tmp_quantity;
		$tot_weight = $tot_weight + $tmp_weight;
		$tot_qty = $tot_qty + $tmp_quantity;
		
		echo "<tr><td align=\"left\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";

		$price_lvl = $line["price_lvl"];


        $prod_id = '';
        $query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku' AND (display_on_website='1' OR display_in_wc='1')";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$prod_id = $line2["prod_id"];
		}
		mysql_free_result($result2);

		$url = ($prod_id ? $base_url.'store/product.php?prod_id='.$prod_id : '' );
        $tmp_link = ( $url!='' ? '<a href="'.$url.'">'.$line["name"].'</a>' :  $line["name"] );

		echo $tmp_link;

		echo "<br><font size=\"-1\">SKU: ";
		echo $tmp_sku;
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
		$tmp_quantity = $line["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		echo "$tmp_quantity";
		echo "</font></td><td align=\"center\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";

		//find product cost
		$query3 = "SELECT wholesale_cost1, wholesale_cost2, wholesale_cost3, dist_cost1, dist_cost2, dist_cost3 FROM product_skus WHERE sku='$tmp_sku'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
            $query3b = "SELECT cost_field FROM wholesale_price_levels WHERE price_level='$price_lvl'";
            $result3b = mysql_query($query3b) or die("Query failed : " . mysql_error());
            while ($line3b = mysql_fetch_array($result3b, MYSQL_ASSOC)) {
                $cost_field = $line3b["cost_field"];
            }
            $cost = $line3[$cost_field];
            mysql_free_result($result3b);
        }
		mysql_free_result($result3);

        $orig_sub = $cost * $line["quantity"];
		$orig_cost = $cost;
		$cost = $cost * $percent_off;
        
		echo condDecimalFormat($cost);
		$cost = condDecimalFormat( $cost);
        echo "</font></td><td align=\"right\" VALIGN=\"TOP\"><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">$";
		$tmp_subtotal = $line["quantity"] * $cost;
		$tmp_subtotal = condDecimalFormat( $tmp_subtotal);
		echo condDecimalFormat($tmp_subtotal);
		echo "</font></td></tr>\n";
		
        $discount = $orig_sub - $tmp_subtotal;
        $discount = condDecimalFormat( $discount);

		$subtotal = $subtotal + $tmp_subtotal;
		$item_count = $item_count + 1;

		echo "<input type=\"hidden\" name=\"prod_sku_$item_count\" value=\"$tmp_sku\">\n";
		echo "<input type=\"hidden\" name=\"prod_quantity_$item_count\" value=\"$tmp_quantity\">\n";
		echo "<input type=\"hidden\" name=\"prod_price_$item_count\" value=\"$cost\">\n";
		echo "<input type=\"hidden\" name=\"prod_orig_price_$item_count\" value=\"$orig_cost\">\n";
		echo "<input type=\"hidden\" name=\"prod_name_$item_count\" value=\"$tmp_name\">\n";

	}
	mysql_free_result($result);
?>

<tr><td colspan="3" VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Sub-Total</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php 
echo condDecimalFormat($subtotal);

include '../includes/retailer2.php';

$queryBoxes = "SELECT * FROM ship_containers";
$resultBoxes = mysql_query($queryBoxes) or die("Query failed : " . mysql_error());
$boxrowct=0;
while ($lineBoxes = mysql_fetch_array($resultBoxes, MYSQL_ASSOC)) {
   $boxes[$boxrowct] = new ShipBox;
   foreach ($lineBoxes as $col => $val) {
        $boxes[$boxrowct]->$col = $val;//add properties to box
   }
   $boxrowct++;
}

$box_weight_and_count = calc_box_weight($tot_qty, $boxes);
$box_weight = $box_weight_and_count[0];
$boxes = $box_weight_and_count[1];//boxes array has been updated with counts of each box
$box_count_num = 0;
foreach($boxes as $box) {
    $box_count_num += $box->counter;
}

?>
</b></font></td></tr>
<tr><td colspan="4" VALIGN="TOP" align="right"><b>Shipping and Handling&#160;&#160;</b>
<?php
// Add Box Weight to Total Weight
$tot_weight = $tot_weight + $box_weight;
echo '<span id="sh_wrapper"><select id="shipping_method" class="text_right" name="shipping_method" onChange="WC.reCalcTotal()">';

if ( $free_shipping_wholesale==1 && $subtotal >= $free_wholesale_ship_limit ) {
	$queryFree = "SELECT * FROM ship_method_wholesale WHERE method='method_fr'";
	$resultFree = mysql_query($queryFree) or die("Query failed : " . mysql_error());
	while ($lineFree = mysql_fetch_array($resultFree, MYSQL_ASSOC)) {
	   $shipping_method = $lineFree["ship_method_id"];
	   echo '<option selected="true" value="'.$lineFree["ship_method_id"].'|0.00">'.$lineFree["name"].' - $0.00</option>';
	}
}

$_SESSION["boxes"] = $boxes;//store in session so we don't have to pass it around
$shipping_active = loadShipping($tot_weight, $shipping_method, $box_count_num, $subtotal, 'FedEx');

echo '</select><br>
    <a href="javascript:void(0)" id="more_ship_options" ship_url="'.$current_base.'includes/retailer2.php?recalc_ship=1&tot_weight='.$tot_weight.'&shipping_method='.$shipping_method.'&box_count_num='.$box_count_num.'&subtotal='.$subtotal.'">more S&H options</a>
    </span><!-- sh_wrapper -->';
?>
</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Tax</b></font></td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>$
<?php
$tax = '0.00';
echo $tax;
?>
</b></font></td></tr>

<?php 
	if ( $credit!==0 ) {
		echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" size="+1"><input type="checkbox" name="apply_credit" id="apply_credit"';
		if ($apply_credit) {
			echo ' checked';
		}
		echo '/><label for="apply_credit" class="bold">Apply Credit</label></font></td><td VALIGN="TOP" align="right">';
		echo '<span class="bold gray" id="credit_shown">- $'.condDecimalFormat($credit_in_db).'</span>';
		echo '<input type="hidden" name="credit" id="credit" value="'.$credit_in_db.'" />';
		echo '</td></tr>';
	}
?>

<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Total</b></font></td><td VALIGN="TOP" align="right">
<?php 
$total = $subtotal + $shipping_active + $tax;
$total = sprintf("%01.2f", $total);
echo '<div id="total_shown" class="bold">$'.condDecimalFormat($total).'<div>';
?>
</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>

<input type="hidden" name="item_count" value="<?php echo $item_count; ?>">
<input type="hidden" name="subtotal" id="subtotal" value="<?php echo $subtotal; ?>">
<input type="hidden" name="tax" id="tax" value="<?php echo $tax; ?>">
<input type="hidden" name="total" id="total" value="<?php echo $total; ?>">
<input type="hidden" name="wholesale_receipt_id" value="<?php echo $wholesale_receipt_id; ?>">

<?php
echo '<tr><td class="error3">';
	calcAndShowCommission();
echo '</td></tr>';
?>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Please double check your order, if there are any problems please <a href="<?php echo $base_url; ?>wc/cart.php">return to the Shopping Cart</a> to make adjustments. If everything is okay then please complete your order below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0">
<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Payment Information</b></font></td></tr>
<tr><td align="right" VALIGN="TOP"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Payment Type:</font></td><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">
<?php
	echo displayPayOptions('pay_type', $secure_funds_only);
?>
<br>
<font size="-1">If you wish to pay by a payment method other than Credit Card please contact a Sales Representative at <?=$company_phone?>.</font></font></td></tr>
<?php
	include_once('../includes/cc_fields.php');
?>
</table></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center"><input type="submit" name="step2_submit" value="Submit Order"></td></tr>
</form>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>