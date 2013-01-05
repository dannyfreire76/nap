<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /store/step1.php
// Version: 1.8
// Build: 1801
// Date: 01-30-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$cart = $_POST["cart"];
$step1 = $_POST["step1"];

$ship_name = $_SESSION["member_name"];
$ship_address1 = $_SESSION['address_info']["ship_address1"];
$ship_address2 = $_SESSION['address_info']["ship_address2"];
$ship_city = $_SESSION['address_info']["ship_city"];
$ship_state = $_SESSION['address_info']["ship_state"];
$ship_zip = $_SESSION['address_info']["ship_zip"];
$ship_country = $_SESSION['address_info']["ship_country"];
$ship_phone = $_SESSION['address_info']["ship_phone"];
$discount_code = $_SESSION['order_info']["discount_code"];

/* no idea why this was here:
if($_SESSION['ship_state'] != "") {
	unset($_SESSION['ship_state']);
}
*/

if(!$_COOKIE["nap_user"]) {
	header("Location: " . $base_url . "store/");
	exit;
}

if($step1) {
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


    //Validate Fields
	$error_txt = "";
	$disc_valid = "0";
	if(!$ship_name) { $error_txt .= "You must enter the name of the person the package is being shipped to in the <b>Addressee</b> field.<br>\n"; }
	if(!$ship_address1) { $error_txt .= "You must enter the address the package is being shipped to in the <b>Shipping Address 1</b> field.<br>\n"; }
	if(!$ship_city) { $error_txt .= "You must enter the city the package is being shipped to in the <b>Shipping City</b> field.<br>\n"; }
	if($ship_country=='US') {
		if(!$ship_state) { $error_txt .= "You must enter the state the package is being shipped to in the <b>Shipping State</b> field.<br>\n"; }
		if(!$ship_zip) { $error_txt .= "You must enter the zip/postal code the package is being shipped to in the <b>Zip/Postal Code</b> field.<br>\n"; }
	}
	if(!$ship_country) { $error_txt .= "You must enter the country the package is being shipped to in the <b>Shipping Country</b> field.<br>\n"; }

	if ( $discount_code != "" ) {
		$queryCode = "SELECT * FROM discount_codes WHERE status='1' AND discount_code='$discount_code'";
		$resultCode = mysql_query($queryCode) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultCode)==0 ) {
			$error_txt .= "The Discount Code you entered is invalid or expired.<br>\n";
		}
		else {
			while ($lineCode = mysql_fetch_array($resultCode, MYSQL_ASSOC)) {
				if ( $lineCode["expire_days"] && $lineCode["expire_days"]!=0 ) {
					$code_created = strtotime($lineCode["created"]);
					$todays_date = date("Y-m-d");
					$today = strtotime($todays_date);
					$exp_days = $lineCode["expire_days"];
					$exp_sec = $exp_days * 60 * 60 * 24;

					//this quiz discount code is older than expire_days (in seconds)
					if ( $today - $code_created > $exp_sec ) {
						$error_txt .= "The Discount Code you entered has expired.<br>\n";

						$queryExp = "UPDATE discount_codes SET status = '0' WHERE discount_code='$discount_code'";
						$resultExp = mysql_query($queryExp) or die("Query failed : " . mysql_error());
					}
				}
			}
		}
	}
	
	//if they're getting a special deal, check if this address has ordered from this site before

	$queryZ = "SELECT sku FROM cart WHERE (user_id='$user_id' OR (member_id='$member_id' AND member_id!=0)) AND site='".$_SERVER['HTTP_HOST']."'";
	$resultZ = mysql_query($queryZ, $dbh_master) or die("Query failed : " . mysql_error());

	while ($lineZ = mysql_fetch_array($resultZ, MYSQL_ASSOC)) {

		$tmp_prod_id="";
		$query2 = "SELECT prod_id FROM product_skus WHERE sku='".$lineZ["sku"]."'";
		$result2 = mysql_query($query2) or die("query2 failed: " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$tmp_prod_id = $line2["prod_id"];
		}

		if ( $tmp_prod_id!="" && in_array($tmp_prod_id, $free_prods_arr) ) {

			if ( $ship_country!='US' ) {

				$okay_to_insert = false;
				$error_txt = 'Sorry, our free trials are for new U.S. customers only!<br />Please <a href="'.$base_url.'store/cart.php">click here</a> to update your shopping cart.';

			} else {
				$queryZ = "SELECT * FROM receipts WHERE complete=1 AND member_id='$member_id' AND member_id!=0";
				$resultZ = mysql_query($queryZ) or die("Query failed : " . mysql_error());
				if ( mysql_num_rows($resultZ) > 0 ) {
					$okay_to_insert = false;
					$error_txt = 'Sorry, our free trials are for new customers only!<br />Please <a href="'.$base_url.'store/cart.php">click here</a> to update your shopping cart.';

				} else {//this actual member has not ordered from us before but check the addy just in case
				
					$queryX = "SELECT receipts.receipt_id FROM receipts, receipt_items, product_skus WHERE TRIM( REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UCASE(ship_address1),'ROAD','RD'),'STREET','ST'),'ST',''),'LANE','LN'),'AVENUE','AVE') )='".trim( str_replace('LANE','LN',str_replace('AVENUE','AVE',str_replace('ROAD','RD',str_replace('STREET','ST',strtoupper($ship_address1))))) )."' ";
					$queryX .= " AND TRIM(   REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UCASE(ship_address2),' ',''),'-',''),'UNIT',''),'APT',''),'APARTMENT',''),'FLOOR',''),'ND',''),'RD',''),'TH',''),'ST',''),'#',''),'.',''),'BOX',''),'POSTOFFICE','PO'),'SUITE','')  )=";
					$queryX .= "'".trim(   str_replace('SUITE',' ',str_replace('POSTOFFICE','PO',str_replace('BOX','',str_replace('.','',str_replace('#','',str_replace('ST','',str_replace('TH','',str_replace('RD','',str_replace('ND','',str_replace('FLOOR','',str_replace('APARTMENT','',str_replace('APT','',str_replace('UNIT','',str_replace('-','',str_replace(' ','',strtoupper($ship_address2))))))))))))))))   )."' ";
					$queryX .= " AND TRIM(UCASE(ship_city))='".strtoupper($ship_city)."' AND TRIM(UCASE(ship_state))='".strtoupper($ship_state)."' AND TRIM(UCASE(ship_zip))='".strtoupper($ship_zip)."' AND TRIM(UCASE(ship_country))='".strtoupper($ship_country)."' AND receipts.receipt_id=receipt_items.receipt_id AND receipts.complete='1' ";
					
					//checking for first time customers, regardless of whether or not they've gotten the free trial  before
					$resultX = mysql_query($queryX) or die("Query failed : " . mysql_error());
					if ( mysql_num_rows($resultX) > 0 ) {
						$okay_to_insert = false;
						$error_txt = 'Sorry, our free trials are limited to 1 per household for new customers only and our records indicate we\'ve shipped to this address before.<br />Please <a href="'.$base_url.'store/cart.php">click here</a> to update your shopping cart.';
					}
				}
			}
		}
	}
	//END if they're getting...

	//Write to DB and Move to Step 2 or display errors
	if(!$error_txt) {
		$_SESSION["order_info"]["discount_code"] = $discount_code;
		$_SESSION["order_info"]["delivery"] = $delivery;

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
		header("Location: " . $base_secure_url . "store/step2.php")|| die('Redirect failed.  Please start the checkout process again.');
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

<link rel="stylesheet" type="text/css" href="<?php echo $base_secure_url; ?>includes/site_styles.css" />

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Checkout: Step 1 - Shipping Address Information</font></td></tr>

<?php
if($member_id == "") {
    echo "<tr>
            <td>
                <br />
                <div>
                    Have you ordered from us before?  <a href=\"javascript:void(0)\" onClick=\"$('#login').trigger('click'); return false;\">Sign-in for quicker checkout</a>.
                </div>
                <br />
            </td>
        </tr>";
}
?>

<form action="<?php echo $base_secure_url; ?>store/step1.php" method="POST">
<input type="hidden" name="step1" value="1">

<?php
//Error Messages
if($step1) {
	if($error_txt) {
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\" class=\"error\">$error_txt</td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
	}
}
?>

<tr><td align="left"><table border="0">
<tr><td align="left" colspan="2"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Shipping Address</b></font></td></tr>
<tr><td align="left" colspan="2"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="-1"><i>(Required = *)</i></font></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Addressee *:</font></td><td align="left"><input type="text" name="ship_name" size="30" maxlength="200" value="<?php echo $ship_name; ?>"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Address 1 *:</font></td><td align="left"><input type="text" name="ship_address1" size="30" maxlength="200" value="<?php echo $ship_address1; ?>"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Address 2:</font></td><td align="left"><input type="text" name="ship_address2" size="30" maxlength="200" value="<?php echo $ship_address2; ?>"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">City *:</font></td><td align="left"><input type="text" name="ship_city" size="30" maxlength="200" value="<?php echo $ship_city; ?>"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">State/Province (* if applicable):</font></td><td align="left"><select name="ship_state">
<option value="">Select state/province</option>
<?php
$query = "SELECT * FROM states WHERE status='1' ORDER BY name";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<option value="'.$line["code"].'"';
	if($ship_state == $line["code"]) { echo " SELECTED"; }
	echo '>'.$line["name"].'</option>';
}

?>
</select></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Zip/Postal Code (* if applicable):</font></td><td align="left"><input type="text" name="ship_zip" size="10" maxlength="10" value="<?php echo $ship_zip; ?>"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Country *:</font></td><td align="left"><select name="ship_country">
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
</select></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Phone:</font></td><td align="left"><input type="text" name="ship_phone" size="30" maxlength="30" value="<?php echo $ship_phone; ?>"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Discount Code:</font></td><td align="left"><input type="text" name="discount_code" size="10" maxlength="10" value="<?php echo $discount_code; ?>"></td></tr>
<!--
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td align="left" colspan="2"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Delivery Instructions</b> - Directions for the delivery person</font><br><input type="text" name="delivery" size="50" maxlength="255" value="<?php echo $delivery; ?>"></td></tr>
-->
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
</div>
</body>
</html>