<?php
// BME WMS
// Page: Checkout Step 1
// Path/File: /wc/step1.php
// Version: 1.1
// Build: 1103
// Date: 12-06-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

check_wholesale_login();

$supplies = $_GET["supplies"];
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
$discount_code_posted = $_POST["discount_code"];

$wholesale_receipt_id = $_SESSION['wholesale_receipt_id'];

if($_SESSION['ship_state'] != "") {
	unset($_SESSION['ship_state']);
}

/*
if($cart != 1 && $supplies != 1) {
	$retailer_id = $_COOKIE["wc_user"];
}

if(!$retailer_id) {
	header("Location: " . $base_url . "wc/");
	exit;
}

$result = setcookie("wc_user", $retailer_id, time()+60*60*24*30, "/~salviazo/wc/", ".ocservers.net", 1) or die ("Set Cookie failed : " . mysql_error());
*/

	$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$store_name = stripslashes($line["store_name"]);
		$contact_name = stripslashes($line["contact_name"]);
		$address1 = stripslashes($line["address1"]);
		$address2 = stripslashes($line["address2"]);
		$city = stripslashes($line["city"]);
		$state = $line["state"];
		$zip = $line["zip"];
		$country = $line["country"];
		$phone = $line["phone"];
		$discount_code = $line["discount_code"];
    }
	mysql_free_result($result);
	
if($step1) {
	//Validate Fields
	$error_txt = "";
	if(!$ship_name) { $error_txt .= "You must enter the name of the person the package is being shipped to in the <b>Addressee</b> field.<br>\n"; }
	if(!$ship_address1) { $error_txt .= "You must enter the address the package is being shipped to in the <b>Shipping Address 1</b> field.<br>\n"; }
	if(!$ship_city) { $error_txt .= "You must enter the city the package is being shipped to in the <b>Shipping City</b> field.<br>\n"; }
	if(!$ship_state) { $error_txt .= "You must enter the state the package is being shipped to in the <b>Shipping State</b> field.<br>\n"; }
	if(!$ship_zip) { $error_txt .= "You must enter the zip/postal code the package is being shipped to in the <b>Zip/Postal Code</b> field.<br>\n"; }
	if(!$ship_country) { $error_txt .= "You must enter the country the package is being shipped to in the <b>Shipping Country</b> field.<br>\n"; }
	
	//Write to DB and Move to Step 2 or display errors
	if(!$error_txt) {
		//Write to DB
		$query = "SELECT retailer_id FROM wholesale_receipts WHERE retailer_id='$retailer_id' AND complete='0'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   		foreach ($line as $col_value) {
	       		$db_retailer_id = "$col_value";
	   		}
		}
		
		if($wholesale_receipt_id != ""){
			$query = "UPDATE wholesale_receipts SET complete='0', retailer_id='$retailer_id', ship_name='$ship_name', ship_address1='$ship_address1', ship_address2='$ship_address2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', ship_phone='$ship_phone', delivery='$delivery', discount_code='$discount_code_posted' WHERE wholesale_receipt_id='$wholesale_receipt_id'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		} else {
			$now = date("Y-m-d H:i:s");
			$query = "INSERT INTO wholesale_receipts SET created='$now', complete='0', retailer_id='$retailer_id', ship_name='$ship_name', ship_address1='$ship_address1', ship_address2='$ship_address2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', ship_phone='$ship_phone', delivery='$delivery', discount_code='$discount_code_posted'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());

            $_SESSION['wholesale_receipt_id'] = mysql_insert_id();
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
		header("Location: " . $base_secure_url . "wc/step2.php");
		exit;
	}
}

	if($ship_name == "") {
		$ship_name = $store_name . " - Attn: " . $contact_name;
	}
	if($ship_address1 == "") {
		$ship_address1 = $address1;
	}
	if($ship_address2 == "") {
		$ship_address2 = $address2;
	}
	if($ship_city == "") {
		$ship_city = $city;
	}
	if($ship_state == "") {
		$ship_state = $state;
	}
	if($ship_zip == "") {
		$ship_zip = $zip;
	}
	if($ship_country == "") {
		$ship_country = $country;
	}
	if($ship_phone == "") {
		$ship_phone = $phone;
	}
	if($discount_code_posted == "") {
		$discount_code_posted = $discount_code;
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog - Checkout</title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" href="<?php echo $base_secure_url; ?>includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Checkout: Step 1 - Shipping Address Information</font></td></tr>

<form action="<?php echo $base_secure_url; ?>wc/step1.php" method="POST">
<input type="hidden" name="step1" value="1">

<?php
//Error Messages
if($step1) {
	if($error_txt) {
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td align=\"left\"><font face=\"$font\" size=\"+1\" color=\"red\">$error_txt</font></td></tr>\n";
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
<option value="US"<?php if($ship_country == "US") { echo " SELECTED"; } ?>>United States</option>
<option value="AF"<?php if($ship_country == "AF") { echo " SELECTED"; } ?>>Afghanistan</option>
<option value="AL"<?php if($ship_country == "AL") { echo " SELECTED"; } ?>>Albania</option>
<option value="DZ"<?php if($ship_country == "DZ") { echo " SELECTED"; } ?>>Algeria</option>
<option value="AS"<?php if($ship_country == "AS") { echo " SELECTED"; } ?>>American Samoa</option>
<option value="AD"<?php if($ship_country == "AD") { echo " SELECTED"; } ?>>Andorra</option>
<option value="AO"<?php if($ship_country == "AO") { echo " SELECTED"; } ?>>Angola</option>
<option value="AI"<?php if($ship_country == "AI") { echo " SELECTED"; } ?>>Anguilla</option>
<option value="AQ"<?php if($ship_country == "AQ") { echo " SELECTED"; } ?>>Antarctica</option>
<option value="AG"<?php if($ship_country == "AG") { echo " SELECTED"; } ?>>Antigua and Barbuda</option>
<option value="AR"<?php if($ship_country == "AR") { echo " SELECTED"; } ?>>Argentina</option>
<option value="AM"<?php if($ship_country == "AM") { echo " SELECTED"; } ?>>Armenia</option>
<option value="AW"<?php if($ship_country == "AW") { echo " SELECTED"; } ?>>Aruba</option>
<!-- Illegal <option value="AU">Australia</option> -->
<option value="AT"<?php if($ship_country == "AT") { echo " SELECTED"; } ?>>Austria</option>
<option value="AZ"<?php if($ship_country == "AZ") { echo " SELECTED"; } ?>>Azerbaijan</option>
<option value="BS"<?php if($ship_country == "BS") { echo " SELECTED"; } ?>>Bahamas</option>
<option value="BH"<?php if($ship_country == "BH") { echo " SELECTED"; } ?>>Bahrain</option>
<option value="BD"<?php if($ship_country == "BD") { echo " SELECTED"; } ?>>Bangladesh</option>
<option value="BB"<?php if($ship_country == "BB") { echo " SELECTED"; } ?>>Barbados</option>
<option value="BY"<?php if($ship_country == "BY") { echo " SELECTED"; } ?>>Belarus</option>
<option value="BE"<?php if($ship_country == "BE") { echo " SELECTED"; } ?>>Belgium</option>
<option value="BZ"<?php if($ship_country == "BZ") { echo " SELECTED"; } ?>>Belize</option>
<option value="BJ"<?php if($ship_country == "BJ") { echo " SELECTED"; } ?>>Benin</option>
<option value="BM"<?php if($ship_country == "BM") { echo " SELECTED"; } ?>>Bermuda</option>
<option value="BT"<?php if($ship_country == "BT") { echo " SELECTED"; } ?>>Bhutan</option>
<option value="BO"<?php if($ship_country == "BO") { echo " SELECTED"; } ?>>Bolivia</option>
<option value="BA"<?php if($ship_country == "BA") { echo " SELECTED"; } ?>>Bosnia and Herzegovina</option>
<option value="BW"<?php if($ship_country == "BW") { echo " SELECTED"; } ?>>Botswana</option>
<option value="BV"<?php if($ship_country == "BV") { echo " SELECTED"; } ?>>Bouvet Island</option>
<option value="BR"<?php if($ship_country == "BR") { echo " SELECTED"; } ?>>Brazil</option>
<option value="IO"<?php if($ship_country == "IO") { echo " SELECTED"; } ?>>British Indian Ocean Territory</option>
<option value="BN"<?php if($ship_country == "BN") { echo " SELECTED"; } ?>>Brunei Darussalam</option>
<option value="BG"<?php if($ship_country == "BG") { echo " SELECTED"; } ?>>Bulgaria</option>
<option value="BF"<?php if($ship_country == "BF") { echo " SELECTED"; } ?>>Burkina Faso</option>
<option value="BI">Burundi</option>
<option value="KH">Cambodia</option>
<option value="CM">Cameroon</option>
<option value="CA"<?php if($ship_country == "CA") { echo " SELECTED"; } ?>>Canada</option>
<option value="CB">Canary Islands</option>
<option value="CV">Cape Verde</option>
<option value="KY">Cayman Islands</option>
<option value="CF">Central African Republic</option>
<option value="TD">Chad</option>
<option value="CL">Chile</option>
<option value="CN">China</option>
<option value="CX">Christmas Island</option>
<option value="CC">Cocos (Keeling) Islands</option>
<option value="CO">Colombia</option>
<option value="KM">Comoros</option>
<option value="CG">Congo</option>
<option value="CD">Congo, The Democratic Republic of The</option>
<option value="CK">Cook Islands</option>
<option value="CE">Corsica</option>
<option value="CR">Costa Rica</option>
<option value="CI">Cote D'Ivoire</option>
<option value="HR">Croatia</option>
<option value="CU">Cuba</option>
<option value="CY">Cyprus</option>
<option value="CZ">Czech Republic</option>
<!-- Illegal <option value="DK">Denmark</option> -->
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="TP">East Timor</option>
<option value="EC">Ecuador</option>
<option value="EG">Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="ET">Ethiopia</option>
<option value="FK">Falkland Islands (Malvinas)</option>
<option value="FO">Faroe Islands</option>
<option value="FJ">Fiji</option>
<!-- Illegal <option value="FI">Finland</option> -->
<option value="CS">Former Czechoslovakia</option>
<option value="SU">Former Ussr</option>
<option value="FR"<?php if($ship_country == "FR") { echo " SELECTED"; } ?>>France</option>
<option value="FX">France (European Territories)</option>
<option value="GF">French Guiana</option>
<option value="PF">French Polynesia</option>
<option value="TF">French Southern Territories</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE"<?php if($bill_country == "DE") { echo " SELECTED"; } ?>>Germany</option>
<option value="GH">Ghana</option>
<option value="GI">Gibraltar</option>
<option value="GB"<?php if($ship_country == "GB") { echo " SELECTED"; } ?>>Great Britain</option>
<option value="GR">Greece</option>
<option value="GL">Greenland</option>
<option value="GD">Grenada</option>
<option value="GP">Guadeloupe</option>
<option value="GU">Guam</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GW">Guinea-Bissau</option>
<option value="GY">Guyana</option>
<option value="HT">Haiti</option>
<option value="HM">Heard Island and Mcdonald Islands</option>
<option value="VA">Holy See (Vatican City State)</option>
<option value="HN">Honduras</option>
<option value="HK">Hong Kong</option>
<option value="HU">Hungary</option>
<option value="IS">Iceland</option>
<option value="IN">India</option>
<option value="ID">Indonesia</option>
<option value="IR">Iran, Islamic Republic of</option>
<option value="IQ">Iraq</option>
<option value="IE">Ireland</option>
<option value="IL"<?php if($ship_country == "IL") { echo " SELECTED"; } ?>>Israel</option>
<!-- Illegal <option value="IT">Italy</option> -->
<option value="JM"<?php if($ship_country == "JM") { echo " SELECTED"; } ?>>Jamaica</option>
<option value="JP"<?php if($bill_country == "JP") { echo " SELECTED"; } ?>>Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KO">Korea</option>
<option value="KW"<?php if($ship_country == "KW") { echo " SELECTED"; } ?>>Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Lao People's Democratic Republic</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libyan Arab Jamahiriya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MO">Macau</option>
<option value="MK">Macedonia, The Former Yugoslav Republic of</option>
<option value="MG">Madagascar</option>
<option value="MI">Madeira Islands</option>
<option value="MW">Malawi</option>
<option value="MY">Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ">Martinique</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="YT">Mayotte</option>
<option value="MX">Mexico</option>
<option value="FM">Micronesia, Federated States of</option>
<option value="MD">Moldova, Republic of</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP">Nepal</option>
<option value="NL"<?php if($ship_country == "NL") { echo " SELECTED"; } ?>>Netherlands</option>
<option value="AN">Netherlands Antilles</option>
<option value="NT">Neutral Zone</option>
<option value="NC">New Caledonia</option>
<option value="NZ"<?php if($bill_country == "NZ") { echo " SELECTED"; } ?>>New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NU">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="KP">North Korea</option>
<option value="MP">Northern Mariana Islands</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PN">Pitcairn</option>
<option value="PL">Poland</option>
<option value="PT">Portugal</option>
<option value="PR">Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="RE">Reunion (French)</option>
<option value="RO">Romania</option>
<option value="RU">Russian Federation</option>
<option value="RW">Rwanda</option>
<option value="SH">Saint Helena</option>
<option value="KN">Saint Kitts and Nevis</option>
<option value="LC">Saint Lucia</option>
<option value="PM">Saint Pierre and Miquelon</option>
<option value="VC">Saint Vincent and The Grenadines</option>
<option value="SQ">Saipan</option>
<option value="WS">Samoa</option>
<option value="SM">San Marino</option>
<option value="ST">Sao Tome and Principe</option>
<option value="SA">Saudi Arabia</option>
<option value="SF">Scotland</option>
<option value="SN">Senegal</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovakia</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="SO">Somalia</option>
<option value="ZA">South Africa</option>
<option value="GS">South Georgia and The South Sandwich Islands</option>
<option value="KR">South Korea</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SJ">Svalbard and Jan Mayen</option>
<option value="SZ">Swaziland</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syrian Arab Republic</option>
<option value="TW">Taiwan, Province of China</option>
<option value="TJ">Tajikistan</option>
<option value="TZ">Tanzania, United Republic of</option>
<option value="TH">Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad and Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TC">Turks and Caicos Islands</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="UK"<?php if($ship_country == "UK") { echo " SELECTED"; } ?>>United Kingdom</option>
<option value="US">United States</option>
<option value="UM">United States Minor Outlying Islands</option>
<option value="UY">Uruguay</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VE">Venezuela</option>
<option value="VN">Viet Nam</option>
<option value="VG">Virgin Islands, British</option>
<option value="VI">Virgin Islands, U.S.</option>
<option value="WF">Wallis and Futuna</option>
<option value="EH">Western Sahara</option>
<option value="YE">Yemen</option>
<option value="YU">Yugoslavia</option>
<option value="ZR">Zaire</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
</select></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Phone:</font></td><td align="left"><input type="text" name="ship_phone" size="30" maxlength="30" value="<?php echo $ship_phone; ?>"></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>

<input type="hidden" name="discount_code" size="10" maxlength="10" value="<?=$discount_code?>">

<tr><td colspan="2">&nbsp;</td></tr>

<tr><td align="left" colspan="2"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1"><b>Delivery Instructions</b> - Directions for the delivery person</font><br><input type="text" name="delivery" size="50" maxlength="255" value="<?php echo $delivery; ?>"></td></tr>
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