<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /store/step2.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$step2 = $_POST["step2"];
$pay_type = $_POST["pay_type"];
$cc_type = $_POST["cc_type"];
$cc_first_name = $_POST["cc_first_name"];
$cc_last_name = $_POST["cc_last_name"];
$cc_num = $_POST["cc_num"];
$cid = $_POST["cid"];
$cc_exp_m = $_POST["cc_exp_m"];
$cc_exp_y = $_POST["cc_exp_y"];
$bill_name = $_POST["bill_name"];
$bill_address1 = $_POST["bill_address1"];
$bill_address2 = $_POST["bill_address2"];
$bill_city = $_POST["bill_city"];
$bill_state = $_POST["bill_state"];
$bill_zip = $_POST["bill_zip"];
$bill_country = $_POST["bill_country"];
$bill_phone = $_POST["bill_phone"];
$bill_email = $_POST["bill_email"];
$warning_agree = $_POST["warning_agree"];
$disclaimer_agree = $_POST["disclaimer_agree"];
$age_agree = $_POST["age_agree"];
$item_count = $_POST["item_count"];
$subtotal = $_POST["subtotal"];
$shipping = $_POST["shipping"];
$tax = $_POST["tax"];
$total = $_POST["total"];

for($i = 1; $i <= $item_count; $i++){
	$tmp_var = "prod_sku_" . $i;
	$$tmp_var = $_POST["$tmp_var"];
	$tmp_var2 = "prod_quantity_" . $i;
	$$tmp_var2 = $_POST["$tmp_var2"];
	$tmp_var3 = "prod_price_" . $i;
	$$tmp_var3 = $_POST["$tmp_var3"];
	$tmp_var4 = "prod_name_" . $i;
	$$tmp_var4 = $_POST["$tmp_var4"];
}

session_start();

$user_id = $_COOKIE["db_user"];
if(!$user_id) {
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
		$email_str .= "Please find the login details ";
		$email_str .= "for your My " . $website_title . " account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "my/\n\n";
						
		$subject = "New " . $website_title . " My " . $website_title . " Password";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

function check_dup_email($email) {
	$email_test = "";
	$query = "SELECT member_id, email FROM members WHERE email='$email'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$member_id = $line["member_id"];
		$email_test = $line["email"];
	}
	mysql_free_result($result);
	if($email_test == "") { return -1; }
	else { return $member_id; }
}

// read ship_main
$query = "SELECT tax, instate_tax, state_tax FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$main_tax = $line["tax"];
	$instate_tax = $line["instate_tax"];
	$state_tax = $line["state_tax"];
}
mysql_free_result($result);

// read promo main
$query = "SELECT buy_one_get_one_free FROM promo_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$buy_one_get_one_free = $line["buy_one_get_one_free"];
}
mysql_free_result($result);

if($step2 == "1") {

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
	if(!$warning_agree) { $error_txt .= "You must agree to the warning by checking the box under the <b>Warning</b> field.<br>\n"; }
	if(!$disclaimer_agree) { $error_txt .= "You must agree to the disclaimer by checking the box under the <b>Disclaimer</b> field.<br>\n"; }
	if(!$age_agree) { $error_txt .= "You must declare you are 18 years old or older by checking the box next to the <b>Age</b> field.<br>\n"; }


	//Check for Errors
	if($error_txt == "") {
		if($pay_type == "cc") { 
			$query = "SELECT status, company, url, username, password FROM merchant_acct WHERE merchant_acct_id='1'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$status = $line["status"];
				$company = $line["company"];
				$merchant_url = $line["url"];
				$merchant_username = $line["username"];
				$merchant_password = $line["password"];
			}
			mysql_free_result($result);

			if($company != 1 || $status != 1) {
				$error_txt .= "Error, Credit Card Processing is currently inactive. Please complete your order using a different Payment Method.<br>\n";
			} else {
				//Send to Merchant Account for Verification
				$c = curl_init($merchant_url);
				curl_setopt($c, CURLOPT_HEADER, 0);
				curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($c, CURLOPT_POSTFIELDS, 'x_version=3.1&x_delim_data=True&x_login='.$merchant_username.'&x_password='.$merchant_password.'&x_amount=' . urlencode($total) . '&x_card_num=' . urlencode($cc_num) . '&x_exp_date=' . urlencode($cc_exp_m) . urlencode($cc_exp_y) . '&x_card_code=' . urlencode($cid) . '&x_first_name=' . urlencode($cc_first_name) . '&x_last_name=' . urlencode($cc_last_name) . '&x_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&x_city=' . urlencode($bill_city) . '&x_state=' . urlencode($bill_state) . '&x_zip=' . urlencode($bill_zip) . '&x_country=' . urlencode($bill_country) . '&x_method=CC&x_type=AUTH_CAPTURE');
				$page = urldecode(curl_exec($c));
				if(curl_errno($c)) { 
					$error_txt .= curl_error($c); 
				}
				curl_close($c);
				
				// Debug Merchant Account
				//$error_txt .= "Results From Merchant Acct: " . $page . "<br>\n";
				
				$page = substr($page, 1);
				$page2 = explode("\"|\"", $page);
				
				if($page2[0] == "3" || $page2[0] == "2") {
					$error_txt .= "Error, There was a problem with the credit card information you entered, because \"" . $page2[3] . "\"<br>\n";
				}
				//if($page2[38] != "M") {
					//$error_txt .= "Error, There was a problem with the credit card security code you entered. Please //check the back of your card and re-enter.";
				//}
				//echo "5=" . $page2[5] . "<br>\n";
				//echo "38=" . $page2[38] . "<br>\n";
				
				$cc_auth_code = $page2[4];
			}
		}
	}

	if($error_txt == "") {
		$member_email_test = check_dup_email($bill_email);
		
		if($member_email_test < 0) {
			// Generate New Password
			include '../admin/includes/password/class.password.php';
			$pas = new password();
			$pas->specchar = true;
			$newpass = $pas->generate();
			
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
			
			$shipping_info = $_SESSION['shipping_info'];
	
			$now = date("Y-m-d H:i:s");
			$query = "INSERT INTO members SET created='$now', status='1', email='$bill_email', username='$bill_email', password='$newpass', nickname='$nickname', first_name='$first_name', last_name='$last_name', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', ship_name='";
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
			$result = mysql_query($query) or die("Query failed : " . mysql_error());

			$query = "SELECT member_id FROM members WHERE email='$bill_email'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$member_id = $line["member_id"];
			}
			mysql_free_result($result);

			//send_email_login($bill_email, $first_name, $last_name, $bill_email, $newpass);

		} else {
			$member_id = $member_email_test;
		}
	}
	
	//Check for Errors
	if($error_txt == "") {

		//Write to receipts DB
		$now = date("Y-m-d H:i:s");
		$query = "UPDATE receipts SET ordered='$now', complete='1', member_id='$member_id', pay_type='$pay_type', cc_type='$cc_type', cc_first_name='$cc_first_name', cc_last_name='$cc_last_name', cc_num='$cc_num', cid='$cid', cc_exp_m='$cc_exp_m', cc_exp_y='$cc_exp_y', cc_auth_code='$cc_auth_code', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', bill_email='$bill_email', warning_agree='$warning_agree', disclaimer_agree='$disclaimer_agree', age_agree='$age_agree', item_count='$item_count', subtotal='$subtotal', shipping='$shipping', tax='$tax', total='$total' WHERE user_id='$user_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$query = "SELECT receipt_id FROM receipts WHERE user_id='$user_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		   foreach ($line as $col_value) {
		       $receipt_id = "$col_value";
		   }
		}
		mysql_free_result($result);

		//Write to receipt_items DB
		$now = date("Y-m-d H:i:s");
		$i = 1;
		while ($i <= $item_count) {
			$tmp_sku = "prod_sku_" . $i;
			$tmp_sku = $$tmp_sku;
			$tmp_quantity = "prod_quantity_" . $i;
			$tmp_quantity = $$tmp_quantity;
			$tmp_price = "prod_price_" . $i;
			$tmp_price = $$tmp_price;
			$tmp_name = "prod_name_" . $i;
			$tmp_name = $$tmp_name;
			$query = "INSERT INTO receipt_items SET receipt_id='$receipt_id', created='$now', sku='$tmp_sku', quantity='$tmp_quantity', price='$tmp_price', name='$tmp_name'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			$i++;

			$query2 = "UPDATE product_skus SET stock = stock - $tmp_quantity WHERE sku='$tmp_sku'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		}

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


				
		//Goto Thanks
		unset($_SESSION['ship_state'], $_SESSION['shipping_info']);
		header("Location: " . $base_secure_url . "store/confirm.php");
		exit;
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
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Checkout: Step 2 - Review Order and Payment Information</td></tr>

<?php
if($step2 == "1") {
	if($error_txt != "") {
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
	}
}
?>

<tr><td align="left"><table border="0">
<tr><td align="left" class="style3">Product</td><td align="center" class="style3">Quantity</td><td align="center" class="style3">Price</td><td align="center" class="style3">Sub-Total</td></tr>

<form action="<?php echo $base_secure_url; ?>store/step2.php" method="POST">
<input type="hidden" name="step2" value="1">

<?php
$subtotal = 0;
$item_count = 0;
	$query = "SELECT cart_id, quantity, sku, name FROM cart WHERE user_id='$user_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" VALIGN=\"TOP\" class=\"style2\">";

		$tmp_sku = $line["sku"];

		//find product url
		$query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   foreach ($line2 as $col_value2) {
		       $url = "$col_value2";
		       $url = substr($url, 1);
		       $url = $base_url . "store" . $url;
		   }
		}
		mysql_free_result($result2);

		echo "<a href=\"$url\">";
		$tmp_name = $line["name"];
		echo $tmp_name;
		echo "</a><br>SKU: ";
		echo $tmp_sku;
		echo "</td><td align=\"center\" VALIGN=\"TOP\" class=\"style2\">";
		$tmp_quantity = $line["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		echo "$tmp_quantity";
		echo "</td><td align=\"center\" VALIGN=\"TOP\" class=\"style2\">$";

		//find product cost
		$query3 = "SELECT cost FROM product_skus WHERE sku='$tmp_sku'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
		   foreach ($line3 as $col_value3) {
		       $cost = "$col_value3";
		   }
		}
		mysql_free_result($result3);

		//find discount code
		$query4 = "SELECT discount_code FROM receipts WHERE user_id ='$user_id'";
		$result4 = mysql_query($query4) or die("Query failed : " . mysql_error());
		while ($line4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
		   foreach ($line4 as $col_value4) {
		       $discount_code = "$col_value4";
		   }
		}
		mysql_free_result($result4);

		if($discount_code == "DB0604") {
			$cost = $cost * 0.85;
		} elseif($discount_code == "db0604") {
			$cost = $cost * 0.85;
		} elseif($discount_code == "WHEPITT") {
			$cost = $cost * 0.50;
		} elseif($discount_code == "whepitt") {
			$cost = $cost * 0.50;
		} elseif($discount_code == "DREAM4") {
			$cost = $cost * 0.75;
		} elseif($discount_code == "dream4") {
			$cost = $cost * 0.75;
		} elseif($discount_code == "2DREAM") {
			$cost = $cost * 0.75;
		} elseif($discount_code == "2dream") {
			$cost = $cost * 0.75;
		} elseif($discount_code == "HOLISTIC") {
			$cost = $cost * 0.50;
		} elseif($discount_code == "holistic") {
			$cost = $cost * 0.50;
		} elseif($discount_code == "BACKPAGE") {
			$cost = $cost * 0.50;
		} elseif($discount_code == "backpage") {
			$cost = $cost * 0.50;
		}
		$cost = sprintf("%01.2f", $cost);
		echo $cost;
		echo "</td><td align=\"right\" VALIGN=\"TOP\" class=\"style2\">$";
		$tmp_subtotal = $line["quantity"] * $cost;
		$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
		echo $tmp_subtotal;
		echo "</td></tr>\n";
		
		$subtotal = $subtotal + $tmp_subtotal;
		$item_count = $item_count + 1;

		echo "<input type=\"hidden\" name=\"prod_sku_$item_count\" value=\"$tmp_sku\">\n";
		echo "<input type=\"hidden\" name=\"prod_quantity_$item_count\" value=\"$tmp_quantity\">\n";
		echo "<input type=\"hidden\" name=\"prod_price_$item_count\" value=\"$cost\">\n";
		echo "<input type=\"hidden\" name=\"prod_name_$item_count\" value=\"$tmp_name\">\n";

	}
	mysql_free_result($result);

?>

<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Sub-Total</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
$subtotal = sprintf("%01.2f", $subtotal);
echo $subtotal;
?>
</td></tr>
<tr><td>&nbsp;</td><td colspan="2" VALIGN="TOP" align="right" class="style3">Shipping and Handling</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
	$query = "SELECT free_shipping_offered, free_shipping FROM ship_main WHERE ship_main_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$free_shipping_offered = $line["free_shipping_offered"];
		$free_shipping = $line["free_shipping"];
	}
	mysql_free_result($result);

if($free_shipping_offered == "1") {
	if($subtotal >= $free_shipping) {
		echo "0.00";
		$shipping = 0;
	} else {
		echo "4.95";
		$shipping = 4.95;
	}
} else {
	echo "4.95";
	$shipping = 4.95;
}
?>
</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Tax</td><td VALIGN="TOP" align="right" class="style3">$
<?php
if($instate_tax == '1') {
	if($state_tax == $_SESSION['ship_state']) {
		$tax = $subtotal * $main_tax;
	} else {
		$tax = '0.00';
	}
} else {
	$tax = $subtotal * $main_tax;
}
$tax = sprintf("%01.2f", $tax);
echo $tax;
?>
</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td VALIGN="TOP" align="right" class="style3">Total</td><td VALIGN="TOP" align="right" class="style3">$
<?php 
$total = $subtotal + $shipping + $tax;
$total = sprintf("%01.2f", $total);
echo $total;
?>
</td></tr>
<tr><td colspan="4">&nbsp;</td></tr>
</table></td></tr>

<input type="hidden" name="item_count" value="<?php echo $item_count; ?>">
<input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
<input type="hidden" name="shipping" value="<?php echo $shipping; ?>">
<input type="hidden" name="tax" value="<?php echo $tax; ?>">
<input type="hidden" name="total" value="<?php echo $total; ?>">

<tr><td align="left" class="style2">Please double check your order, if there are any problems please <a href="<?php echo $base_url; ?>store/cart.php">return to the Shopping Cart</a> to make adjustments. If everything is okay then please complete your order below.</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0">
<tr><td align="left" class="style3">Payment Information</td></tr>
<tr><td align="right" VALIGN="TOP" class="style2">Payment Type:</td><td align="left" class="style2"><input type="radio" name="pay_type" value="cc"<?php if($pay_type == "cc") { echo " CHECKED"; } ?>> Credit Card &nbsp; <input type="radio" name="pay_type" value="mo"<?php if($pay_type == "mo") { echo " CHECKED"; } ?>> Money Order<br>
<font size="-1">If paying by Money Order you do not need to fill out the following Credit Card fields. Your order will be shipped as soon as we receive your money order.</font></td></tr>
<tr><td align="right" class="style2">Credit Card Type:</td><td align="left"><SELECT name="cc_type">
<OPTION value=""></option>
<option value="mc"<?php if($cc_type == "mc") { echo " SELECTED"; } ?>>Mastercard</option>
<option value="vi"<?php if($cc_type == "vi") { echo " SELECTED"; } ?>>Visa</option>
<option value="am"<?php if($cc_type == "am") { echo " SELECTED"; } ?>>American Express</option>
<option value="di"<?php if($cc_type == "di") { echo " SELECTED"; } ?>>Discover</option>
</select>&nbsp; <img src="../images/store_ccs.gif" border="0"></td></tr>
<tr><td align="right" NOWRAP class="style2">Your First Name on Credit Card:</td><td align="left"><input type="text" name="cc_first_name" size="30" maxlength="50"<?php if($cc_first_name) { echo " value=\"$cc_first_name\""; } ?>></td></tr>
<tr><td align="right" NOWRAP class="style2">Your Last Name on Credit Card:</td><td align="left"><input type="text" name="cc_last_name" size="30" maxlength="50"<?php if($cc_last_name) { echo " value=\"$cc_last_name\""; } ?>></td></tr>
<tr><td align="right" class="style2">Credit Card Number:</td><td align="left"><input type="text" name="cc_num" size="16" maxlength="20"<?php if($cc_num) { echo " value=\"$cc_num\""; } ?>></td></tr>
<tr><td align="right" class="style2">Security Code:</td><td align="left" class="style2"><input type="text" name="cid" size="4" maxlength="4"<?php if($cid) { echo " value=\"$cid\""; } ?>> &nbsp; <font size="-1"><a href="./cid.php" TARGET="_BLANK">What is this?</a></font></td></tr>
<tr><td align="right" class="style2">Expiration Date:</td><td align="left"><SELECT name="cc_exp_m">
<option value=""></option>
<option value="01"<?php if($cc_exp_m == "01") { echo " SELECTED"; } ?>>January - 01</option>
<option value="02"<?php if($cc_exp_m == "02") { echo " SELECTED"; } ?>>February - 02</option>
<option value="03"<?php if($cc_exp_m == "03") { echo " SELECTED"; } ?>>March - 03</option>
<option value="04"<?php if($cc_exp_m == "04") { echo " SELECTED"; } ?>>April - 04</option>
<option value="05"<?php if($cc_exp_m == "05") { echo " SELECTED"; } ?>>May - 05</option>
<option value="06"<?php if($cc_exp_m == "06") { echo " SELECTED"; } ?>>June - 06</option>
<option value="07"<?php if($cc_exp_m == "07") { echo " SELECTED"; } ?>>July - 07</option>
<option value="08"<?php if($cc_exp_m == "08") { echo " SELECTED"; } ?>>August - 08</option>
<option value="09"<?php if($cc_exp_m == "09") { echo " SELECTED"; } ?>>September - 09</option>
<option value="10"<?php if($cc_exp_m == "10") { echo " SELECTED"; } ?>>October - 10</option>
<option value="11"<?php if($cc_exp_m == "11") { echo " SELECTED"; } ?>>November - 11</option>
<option value="12"<?php if($cc_exp_m == "12") { echo " SELECTED"; } ?>>December - 12</option>
</select> <SELECT name="cc_exp_y">
<option value=""></option>
<option value="2007"<?php if($cc_exp_y == "2007") { echo " SELECTED"; } ?>>2007</option>
<option value="2008"<?php if($cc_exp_y == "2008") { echo " SELECTED"; } ?>>2008</option>
<option value="2009"<?php if($cc_exp_y == "2009") { echo " SELECTED"; } ?>>2009</option>
<option value="2010"<?php if($cc_exp_y == "2010") { echo " SELECTED"; } ?>>2010</option>
<option value="2011"<?php if($cc_exp_y == "2011") { echo " SELECTED"; } ?>>2011</option>
<option value="2012"<?php if($cc_exp_y == "2012") { echo " SELECTED"; } ?>>2012</option>
<option value="2013"<?php if($cc_exp_y == "2013") { echo " SELECTED"; } ?>>2013</option>
<option value="2014"<?php if($cc_exp_y == "2014") { echo " SELECTED"; } ?>>2014</option>
<option value="2015"<?php if($cc_exp_y == "2015") { echo " SELECTED"; } ?>>2015</option>
</select></td></tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="left" class="style3">Billing Address&nbsp; &nbsp;<font size="-1">This must be the billing address exactly as it appears on your billing statement for the credit card or money order entered above.</font></td></tr>
<tr><td colspan="2" align="left" class="style2"><i>(Required = *)</i></td></tr>
<tr><td align="right" class="style2">Billing Name *:</td><td align="left"><input type="text" name="bill_name" size="30" maxlength="200" value="<?php echo $bill_name; ?>"></td></tr>
<tr><td align="right" class="style2">Address 1 *:</td><td align="left"><input type="text" name="bill_address1" size="30" maxlength="30" value="<?php echo $bill_address1; ?>"></td></tr>
<tr><td align="right" class="style2">Address 2:</td><td align="left"><input type="text" name="bill_address2" size="30" maxlength="30" value="<?php echo $bill_address2; ?>"></td></tr>
<tr><td align="right" class="style2">City *:</td><td align="left"><input type="text" name="bill_city" size="30" maxlength="40" value="<?php echo $bill_city; ?>"></td></tr>
<tr><td align="right" class="style2">State *:</td><td align="left"><select name="bill_state">
<option value="">Select a state</option>
<option value="AA"<?php if($bill_state == "AA") { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if($bill_state == "AE") { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if($bill_state == "AP") { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if($bill_state == "AL") { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if($bill_state == "AK") { echo " SELECTED"; } ?>>Alaska</option>
<!--<option value="AB">Alberta</option>-->
<option value="AZ"<?php if($bill_state == "AZ") { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if($bill_state == "AR") { echo " SELECTED"; } ?>>Arkansas</option>
<!--<option value="BC">British Columbia</option>-->
<option value="CA"<?php if($bill_state == "CA") { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if($bill_state == "CO") { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if($bill_state == "CT") { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if($bill_state == "DE") { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if($bill_state == "DC") { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if($bill_state == "FL") { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if($bill_state == "GA") { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if($bill_state == "HI") { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if($bill_state == "ID") { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if($bill_state == "IL") { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if($bill_state == "IN") { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if($bill_state == "IA") { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if($bill_state == "KS") { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if($bill_state == "KY") { echo " SELECTED"; } ?>>Kentucky</option>
<option value="LA"<?php if($bill_state == "LA") { echo " SELECTED"; } ?>>Louisiana</option>
<option value="ME"<?php if($bill_state == "ME") { echo " SELECTED"; } ?>>Maine</option>
<!--<option value="MB">Manitoba</option>-->
<option value="MD"<?php if($bill_state == "MD") { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if($bill_state == "MA") { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if($bill_state == "MI") { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if($bill_state == "MN") { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if($bill_state == "MS") { echo " SELECTED"; } ?>>Mississippi</option>
<option value="MO"<?php if($bill_state == "MO") { echo " SELECTED"; } ?>>Missouri</option>
<option value="MT"<?php if($bill_state == "MT") { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if($bill_state == "NE") { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if($bill_state == "NV") { echo " SELECTED"; } ?>>Nevada</option>
<!--<option value="NB">New Brunswick</option>-->
<option value="NH"<?php if($bill_state == "NH") { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if($bill_state == "NJ") { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if($bill_state == "NM") { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if($bill_state == "NY") { echo " SELECTED"; } ?>>New York</option>
<!--<option value="NF">Newfoundland</option>-->
<option value="NC"<?php if($bill_state == "NC") { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if($bill_state == "ND") { echo " SELECTED"; } ?>>North Dakota</option>
<!--<option value="NT">Northwest Territories</option>-->
<!--<option value="NS">Nova Scotia</option>-->
<option value="OH"<?php if($bill_state == "OH") { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if($bill_state == "OK") { echo " SELECTED"; } ?>>Oklahoma</option>
<!--<option value="ON">Ontario</option>-->
<option value="OR"<?php if($bill_state == "OR") { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if($bill_state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</option>
<!--<option value="PE">Prince Edward Island</option>-->
<!--<option value="QC">Quebec</option>-->
<option value="RI"<?php if($bill_state == "RI") { echo " SELECTED"; } ?>>Rhode Island</option>
<!--<option value="SK">Saskatchewan</option>-->
<option value="SC"<?php if($bill_state == "SC") { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if($bill_state == "SD") { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if($bill_state == "TN") { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if($bill_state == "TX") { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if($bill_state == "UT") { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if($bill_state == "VT") { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if($bill_state == "VA") { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if($bill_state == "WA") { echo " SELECTED"; } ?>>Washington</option>
<option value="DC">Washington DC</option>
<option value="WV"<?php if($bill_state == "WV") { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if($bill_state == "WI") { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if($bill_state == "WY") { echo " SELECTED"; } ?>>Wyoming</option>
<!--<option value="YT">Yukon</option>-->
</select></td></tr>
<tr><td align="right" class="style2">Zip/Postal Code *:</td><td align="left"><input type="text" name="bill_zip" size="10" maxlength="10" value="<?php echo $bill_zip; ?>"></td></tr>
<tr><td align="right" class="style2">Country *:</td><td align="left"><select name="bill_country">
<option value="">Select a country</option>
<option value="US"<?php if($bill_country == "US") { echo " SELECTED"; } ?>>United States</option>
</select></td></tr>
<tr><td align="right" class="style2">Phone:</td><td align="left"><input type="text" name="bill_phone" size="30" maxlength="30" value="<?php echo $bill_phone; ?>"></td></tr>
<tr><td align="right" class="style2">E-Mail Address *:</td><td align="left"><input type="text" name="bill_email" size="30" maxlength="200" value="<?php echo $bill_email; ?>"></td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class="style3">Warning<br>
<TEXTAREA name="warning" cols="60" rows="3">Warning, This Product:
- is not intended for use by persons under the age of 18.
- is not for use by pregnant or nursing women.
- may increase the effects of sedative medications.
- may counteract  the effectiveness of blood-thinning medications.
- may cause drowsiness.
- is not recommended for use with MAO-inhibiting antidepressant medications.

Do not exceed recommended dose. Excessive intake may cause adverse reactions. Never attempt to operate any form of heavy machinery or moving vehicle while using this product.

Consult your doctor before use if you have, or have had, any health condition or if you are taking any medications or remedies including OTC medications, or are planning any medical procedure. Discontinue use or consult your doctor if any adverse reactions occur, such as gastrointestinal discomfort, headache, dizziness, heart palpitations, anxiety, dry mouth, insomnia, drowsiness, skin flushing, or changes in blood pressure.

The statements on this website have not been evaluated by the Food and Drug Administration. This 
product is not intended to diagnose, treat, cure, or prevent any disease.</TEXTAREA></td></tr>
<tr><td class="style2"><input type="checkbox" name="warning_agree" value="1"<?php if($warning_agree == "1") { echo " CHECKED"; } ?>> I have read and understand these warnings.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class="style3">Disclaimer<br>
<TEXTAREA name="disclaimer" cols="60" rows="3">By purchasing this product, the buyer acknowledges that they have read and understood all warnings and suggestions provided by The Upstate Dream Institute. The buyer takes full responsibility for any and all actions/reactions that may occur while using this product and will not hold The Upstate Dream Institute or any of its affiliates liable in any way.</TEXTAREA></td></tr>
<tr><td class="style2"><input type="checkbox" name="disclaimer_agree" value="1"<?php if($disclaimer_agree == "1") { echo " CHECKED"; } ?>> I agree to this disclaimer.</td></tr>
<tr><td colspan="2" class="style2"><input type="checkbox" name="age_agree" value="1"<?php if($age_agree == "1") { echo " CHECKED"; } ?>> I am 18 years old or older and declare all information I provided to be true and accurate.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="right"><input type="submit" value="Submit Order"></td></tr>
</form>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>