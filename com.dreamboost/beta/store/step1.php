<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /store/step1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$user_id = $_POST["user_id"];
$cart = $_POST["cart"];
$step1 = $_POST["step1"];
$ship_name = $_POST["ship_name"];
$ship_address1 = $_POST["ship_address1"];
$ship_address2 = $_POST["ship_address2"];
$ship_city = $_POST["ship_city"];
$ship_state = $_POST["ship_state"];
$ship_zip = $_POST["ship_zip"];
$ship_country = $_POST["ship_country"];
$ship_phone = $_POST["ship_phone"];
$delivery = $_POST["delivery"];
$discount_code = $_POST["discount_code"];

session_start();

if($_SESSION['ship_state'] != "") {
	unset($_SESSION['ship_state']);
}

if(!$cart) {
	$user_id = $_COOKIE["db_user"];
}

if(!$user_id) {
	header("Location: " . $base_url . "store/");
	exit;
}

$result = setcookie("db_user", $user_id, time()+60*60*24*30, "/~dreamboo/store/", ".ocservers.net", 1) or die ("Set Cookie failed : " . mysql_error());

if($step1) {
	//Validate Fields
	$error_txt = "";
	$disc_valid = "0";
	if(!$ship_name) { $error_txt .= "You must enter the name of the person the package is being shipped to in the <b>Addressee</b> field.<br>\n"; }
	if(!$ship_address1) { $error_txt .= "You must enter the address the package is being shipped to in the <b>Shipping Address 1</b> field.<br>\n"; }
	if(!$ship_city) { $error_txt .= "You must enter the city the package is being shipped to in the <b>Shipping City</b> field.<br>\n"; }
	if(!$ship_state) { $error_txt .= "You must enter the state the package is being shipped to in the <b>Shipping State</b> field.<br>\n"; }
	if(!$ship_zip) { $error_txt .= "You must enter the zip/postal code the package is being shipped to in the <b>Zip/Postal Code</b> field.<br>\n"; }
	if(!$ship_country) { $error_txt .= "You must enter the country the package is being shipped to in the <b>Shipping Country</b> field.<br>\n"; }
	if($discount_code == "DB0604") {
		$disc_valid = "1";
	} elseif($discount_code == "db0604") {
		$disc_valid = "1";
	} elseif($discount_code == "WHEPITT") {
		$disc_valid = "1";
	} elseif($discount_code == "whepitt") {
		$disc_valid = "1";
	} elseif($discount_code == "DREAM4") {
		$disc_valid = "1";
	} elseif($discount_code == "dream4") {
		$disc_valid = "1";
	} elseif($discount_code == "2DREAM") {
		$disc_valid = "1";
	} elseif($discount_code == "2dream") {
		$disc_valid = "1";
	} elseif($discount_code == "HOLISTIC") {
		$disc_valid = "1";
	} elseif($discount_code == "holistic") {
		$disc_valid = "1";
	} elseif($discount_code == "BACKPAGE") {
		$disc_valid = "1";
	} elseif($discount_code == "backpage") {
		$disc_valid = "1";
	} elseif($discount_code == "") {
		$disc_valid = "0";
	} else {
		$error_txt .= "The Discount Code you entered is invalid.<br>\n";
	}
	
	//Write to DB and Move to Step 2 or display errors
	if(!$error_txt) {
		//Write to DB
		$query = "SELECT user_id FROM receipts WHERE user_id='$user_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   		foreach ($line as $col_value) {
	       			$db_user_id = "$col_value";
	   		}
		}
		mysql_free_result($result);
		
		if($db_user_id == $user_id){
			$query = "UPDATE receipts SET complete='0', user_id='$user_id', ship_name='$ship_name', ship_address1='$ship_address1', ship_address2='$ship_address2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', ship_phone='$ship_phone', delivery='$delivery', discount_code='$discount_code' WHERE user_id='$user_id'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		} else {
			$now = date("Y-m-d H:i:s");
			$query = "INSERT INTO receipts SET created='$now', complete='0', user_id='$user_id', ship_name='$ship_name', ship_address1='$ship_address1', ship_address2='$ship_address2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', ship_phone='$ship_phone', delivery='$delivery', discount_code='$discount_code'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		}
				
		$shipping_info['ship_name'] = $ship_name;
		$shipping_info['ship_address1'] = $ship_address1;
		$shipping_info['ship_address2'] = $ship_address2;
		$shipping_info['ship_city'] = $ship_city;
		$shipping_info['ship_state'] = $ship_state;
		$shipping_info['ship_zip'] = $ship_zip;
		$shipping_info['ship_country'] = $ship_country;
		$shipping_info['ship_phone'] = $ship_phone;
		
		//Goto Step2
		$_SESSION['ship_state'] = $ship_state;
		$_SESSION['shipping_info'] = $shipping_info;
		header("Location: " . $base_secure_url . "store/step2.php");
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

<tr><td align="left" class="style4">Checkout: Step 1 - Shipping Address Information</td></tr>

<form action="<?php echo $base_secure_url; ?>store/step1.php" method="POST">
<input type="hidden" name="step1" value="1">

<?php
//Error Messages
if($step1) {
	if($error_txt) {
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
	}
}
?>

<tr><td align="left"><table border="0">
<tr><td align="left" colspan="2" class="style3">Shipping Address</td></tr>
<tr><td align="left" colspan="2" class="style2"><i>(Required = *)</i></td></tr>
<tr><td align="right" class="style2">Addressee *:</td><td align="left"><input type="text" name="ship_name" size="30" maxlength="200" value="<?php echo $ship_name; ?>"></td></tr>
<tr><td align="right" class="style2">Address 1 *:</td><td align="left"><input type="text" name="ship_address1" size="30" maxlength="200" value="<?php echo $ship_address1; ?>"></td></tr>
<tr><td align="right" class="style2">Address 2:</td><td align="left"><input type="text" name="ship_address2" size="30" maxlength="200" value="<?php echo $ship_address2; ?>"></td></tr>
<tr><td align="right" class="style2">City *:</td><td align="left"><input type="text" name="ship_city" size="30" maxlength="200" value="<?php echo $ship_city; ?>"></td></tr>
<tr><td align="right" class="style2">State *:</td><td align="left"><select name="ship_state">
<option value="">Select a state</option>
<option value="AA"<?php if($ship_state == "AA") { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if($ship_state == "AE") { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if($ship_state == "AP") { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if($ship_state == "AL") { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if($ship_state == "AK") { echo " SELECTED"; } ?>>Alaska</option>
<!--<option value="AB">Alberta</option>-->
<option value="AZ"<?php if($ship_state == "AZ") { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if($ship_state == "AR") { echo " SELECTED"; } ?>>Arkansas</option>
<!--<option value="BC">British Columbia</option>-->
<option value="CA"<?php if($ship_state == "CA") { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if($ship_state == "CO") { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if($ship_state == "CT") { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if($ship_state == "DE") { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if($ship_state == "DC") { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if($ship_state == "FL") { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if($ship_state == "GA") { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if($ship_state == "HI") { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if($ship_state == "ID") { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if($ship_state == "IL") { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if($ship_state == "IN") { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if($ship_state == "IA") { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if($ship_state == "KS") { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if($ship_state == "KY") { echo " SELECTED"; } ?>>Kentucky</option>
<option value="LA"<?php if($ship_state == "LA") { echo " SELECTED"; } ?>>Louisiana</option>
<option value="ME"<?php if($ship_state == "ME") { echo " SELECTED"; } ?>>Maine</option>
<!--<option value="MB">Manitoba</option>-->
<option value="MD"<?php if($ship_state == "MD") { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if($ship_state == "MA") { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if($ship_state == "MI") { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if($ship_state == "MN") { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if($ship_state == "MS") { echo " SELECTED"; } ?>>Mississippi</option>
<option value="MO"<?php if($ship_state == "MO") { echo " SELECTED"; } ?>>Missouri</option>
<option value="MT"<?php if($ship_state == "MT") { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if($ship_state == "NE") { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if($ship_state == "NV") { echo " SELECTED"; } ?>>Nevada</option>
<!--<option value="NB">New Brunswick</option>-->
<option value="NH"<?php if($ship_state == "NH") { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if($ship_state == "NJ") { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if($ship_state == "NM") { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if($ship_state == "NY") { echo " SELECTED"; } ?>>New York</option>
<!--<option value="NF">Newfoundland</option>-->
<option value="NC"<?php if($ship_state == "NC") { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if($ship_state == "ND") { echo " SELECTED"; } ?>>North Dakota</option>
<!--<option value="NT">Northwest Territories</option>-->
<!--<option value="NS">Nova Scotia</option>-->
<option value="OH"<?php if($ship_state == "OH") { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if($ship_state == "OK") { echo " SELECTED"; } ?>>Oklahoma</option>
<!--<option value="ON">Ontario</option>-->
<option value="OR"<?php if($ship_state == "OR") { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if($ship_state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</option>
<!--<option value="PE">Prince Edward Island</option>-->
<!--<option value="QC">Quebec</option>-->
<option value="RI"<?php if($ship_state == "RI") { echo " SELECTED"; } ?>>Rhode Island</option>
<!--<option value="SK">Saskatchewan</option>-->
<option value="SC"<?php if($ship_state == "SC") { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if($ship_state == "SD") { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if($ship_state == "TN") { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if($ship_state == "TX") { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if($ship_state == "UT") { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if($ship_state == "VT") { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if($ship_state == "VA") { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if($ship_state == "WA") { echo " SELECTED"; } ?>>Washington</option>
<option value="DC">Washington DC</option>
<option value="WV"<?php if($ship_state == "WV") { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if($ship_state == "WI") { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if($ship_state == "WY") { echo " SELECTED"; } ?>>Wyoming</option>
<!--<option value="YT">Yukon</option>-->
</select></td></tr>
<tr><td align="right" class="style2">Zip/Postal Code *:</td><td align="left"><input type="text" name="ship_zip" size="10" maxlength="10" value="<?php echo $ship_zip; ?>"></td></tr>
<tr><td align="right" class="style2">Country *:</td><td align="left"><select name="ship_country">
<option value="">Select a country</option>
<option value="US"<?php if($ship_country == "US") { echo " SELECTED"; } ?>>United States</option>
</select></td></tr>
<tr><td align="right" class="style2">Phone:</td><td align="left"><input type="text" name="ship_phone" size="30" maxlength="30" value="<?php echo $ship_phone; ?>"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="right" class="style2">Discount Code:></td><td align="left"><input type="text" name="discount_code" size="10" maxlength="10" value="<?php echo $discount_code; ?>"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="left" colspan="2" class="style3">Delivery Instructions - Directions for the delivery person<br><input type="text" name="delivery" size="50" maxlength="255" value="<?php echo $delivery; ?>"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2" align="right"><input type="submit" value="Continue to Step 2"></td></tr>
</form>
</table></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>