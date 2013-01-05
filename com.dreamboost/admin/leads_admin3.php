<?php
// BME WMS
// Page: Leads Create Lead page
// Path/File: /admin/leads_admin3.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$submit = $_POST["submit"];
$store_name = $_POST["store_name"];
$contact_name = $_POST["contact_name"];
$city = $_POST["city"];
$state = $_POST["state"];
$phone = $_POST["phone"];
$zip = $_POST["zip"];
$country = $_POST["country"];
$address1 = $_POST["address1"];
$address2 = $_POST["address2"];
$email = $_POST["email"];
$fax_other_phone = $_POST["fax_other_phone"];
$website = $_POST["website"];
$where_store_found = $_POST["where_store_found"];
$comments = $_POST["comments"];
$retailer_type1 = $_POST["retailer_type1"];
$retailer_type2 = $_POST["retailer_type2"];
$retailer_type3 = $_POST["retailer_type3"];
$retailer_type4 = $_POST["retailer_type4"];
$retailer_type5 = $_POST["retailer_type5"];
$retailer_type6 = $_POST["retailer_type6"];
$retailer_type7 = $_POST["retailer_type7"];
$retailer_type8 = $_POST["retailer_type8"];
$retailer_type9 = $_POST["retailer_type9"];
$retailer_type10 = $_POST["retailer_type10"];
$retailer_type11 = $_POST["retailer_type11"];
$retailer_type12 = $_POST["retailer_type12"];
$retailer_type13 = $_POST["retailer_type13"];
$retailer_type14 = $_POST["retailer_type14"];
$retailer_type15 = $_POST["retailer_type15"];
$retailer_type16 = $_POST["retailer_type16"];
$retailer_type17 = $_POST["retailer_type17"];
$retailer_type18 = $_POST["retailer_type18"];
$retailer_type19 = $_POST["retailer_type19"];
$retailer_type20 = $_POST["retailer_type20"];

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Create Leads";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ($submit != "") {
	//prepare data
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	$phone = str_replace("-", "", $phone);
	$phone = str_replace(".", "", $phone);
	$phone = str_replace("+", "", $phone);
	$phone = str_replace(" ", "", $phone);
	$phone = str_replace("\\", "", $phone);
	$phone = str_replace("/", "", $phone);
	$phone = str_replace(":", "", $phone);
	$phone = str_replace(";", "", $phone);
	$phone = str_replace("<", "", $phone);
	$phone = str_replace(">", "", $phone);
	$phone = str_replace("#", "", $phone);
	$phone = str_replace("@", "", $phone);

	$fax_other_phone = str_replace("(", "", $fax_other_phone);
	$fax_other_phone = str_replace(")", "", $fax_other_phone);
	$fax_other_phone = str_replace("-", "", $fax_other_phone);
	$fax_other_phone = str_replace(".", "", $fax_other_phone);
	$fax_other_phone = str_replace("+", "", $fax_other_phone);
	$fax_other_phone = str_replace(" ", "", $fax_other_phone);
	$fax_other_phone = str_replace("\\", "", $fax_other_phone);
	$fax_other_phone = str_replace("/", "", $fax_other_phone);
	$fax_other_phone = str_replace(":", "", $fax_other_phone);
	$fax_other_phone = str_replace(";", "", $fax_other_phone);
	$fax_other_phone = str_replace("<", "", $fax_other_phone);
	$fax_other_phone = str_replace(">", "", $fax_other_phone);
	$fax_other_phone = str_replace("#", "", $fax_other_phone);
	$fax_other_phone = str_replace("@", "", $fax_other_phone);
	
	if($website == "http://") { $website = ""; }
	
	//write info to comments field
	
	//entered_by and last_mod_by to this_user_id
	$entered_by = $this_user_id;
	$last_mod_by = $this_user_id;
	
	//leads_status to on
	$leads_status = 1;
	
	//checks for duplication of entries - hard with no required fields
	
	$now = date("Y-m-d H:i:s");
	$query = "INSERT INTO leads SET";
	$query .= " created='$now',";
	$query .= " entered_by='$entered_by',";
	$query .= " last_mod_by='$last_mod_by',";
	$query .= " store_name='".addslashes($store_name)."',";
	$query .= " contact_name='".addslashes($contact_name)."',";
	$query .= " address1='".addslashes($address1)."',";
	$query .= " address2='".addslashes($address2)."',";
	$query .= " city='".addslashes($city)."',";
	$query .= " state='$state',";
	$query .= " zip='$zip',";
	$query .= " country='$country',";
	$query .= " email='$email',";
	$query .= " phone='$phone',";
	$query .= " fax_other_phone='$fax_other_phone',";
	$query .= " website='$website',";
	$query .= " where_store_found='".addslashes($where_store_found)."',";
	$query .= " leads_status='$leads_status',";
	$query .= " comments='".addslashes($comments)."'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	
	$query2 = "SELECT leads_id FROM leads WHERE created='$now'";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$leads_id = $line["leads_id"];
	}
	mysql_free_result($result2);
	
	if($retailer_type1 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type2 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='2'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type3 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='3'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type4 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='4'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type5 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='5'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type6 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='6'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type7 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='7'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type8 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='8'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type9 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='9'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type10 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='10'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type11 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='11'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type12 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='12'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type13 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='13'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type14 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='14'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type15 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$retailer_id', retailer_type_id='15'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type16 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='16'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type17 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='17'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type18 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='18'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type19 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='19'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	if($retailer_type20 == 1) {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_type_link SET created='$now', leads_id='$leads_id', retailer_type_id='20'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}

	// Send to main leads admin page
	header("Location: " . $base_url . "admin/leads_admin.php");
	exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Create new leads.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><font size="2"><b>Create New Lead Form</b></font></td></tr>

<form name="form1" action="./leads_admin3.php" method="POST">
<tr><td align="left"><table border="0">
<tr><td align="right"><font face="Arial" size="+1">Store Name:</font></td><td><input type="text" name="store_name" size="30" maxlength="255" value="<?php echo stripslashes($store_name); ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Contact Name:</font></td><td><input type="text" name="contact_name" size="30" maxlength="150" value="<?php echo stripslashes($contact_name); ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Address1:</font></td><td><input type="text" name="address1" size="30" maxlength="150" value="<?php echo stripslashes($address1); ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Address2:</font></td><td><input type="text" name="address2" size="30" maxlength="150" value="<?php echo stripslashes($address2); ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">City:</font></td><td><input type="text" name="city" size="30" maxlength="100" value="<?php echo stripslashes($city); ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">State/Province:</font></td><td><select name="state">
<option value="">Select a state</option>
<option value="AA"<?php if($state == "AA") { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if($state == "AE") { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if($state == "AP") { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if($state == "AL") { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if($state == "AK") { echo " SELECTED"; } ?>>Alaska</option>
<option value="AB"<?php if($state == "AB") { echo " SELECTED"; } ?>>Alberta</option>
<option value="AZ"<?php if($state == "AZ") { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if($state == "AR") { echo " SELECTED"; } ?>>Arkansas</option>
<option value="BC"<?php if($state == "BC") { echo " SELECTED"; } ?>>British Columbia</option>
<option value="CA"<?php if($state == "CA") { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if($state == "CO") { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if($state == "CT") { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if($state == "DE") { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if($state == "DC") { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if($state == "FL") { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if($state == "GA") { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if($state == "HI") { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if($state == "ID") { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if($state == "IL") { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if($state == "IN") { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if($state == "IA") { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if($state == "KS") { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if($state == "KY") { echo " SELECTED"; } ?>>Kentucky</option>
<option value="LA"<?php if($state == "LA") { echo " SELECTED"; } ?>>Louisiana</option>
<option value="ME"<?php if($state == "ME") { echo " SELECTED"; } ?>>Maine</option>
<option value="MB"<?php if($state == "MB") { echo " SELECTED"; } ?>>Manitoba</option>
<option value="MD"<?php if($state == "MD") { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if($state == "MA") { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if($state == "MI") { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if($state == "MN") { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if($state == "MS") { echo " SELECTED"; } ?>>Mississippi</option>
<option value="MO"<?php if($state == "MO") { echo " SELECTED"; } ?>>Missouri</option>
<option value="MT"<?php if($state == "MT") { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if($state == "NE") { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if($state == "NV") { echo " SELECTED"; } ?>>Nevada</option>
<option value="NB"<?php if($state == "NB") { echo " SELECTED"; } ?>>New Brunswick</option>
<option value="NH"<?php if($state == "NH") { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if($state == "NJ") { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if($state == "NM") { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if($state == "NY") { echo " SELECTED"; } ?>>New York</option>
<option value="NF"<?php if($state == "NF") { echo " SELECTED"; } ?>>Newfoundland</option>
<option value="NC"<?php if($state == "NC") { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if($state == "ND") { echo " SELECTED"; } ?>>North Dakota</option>
<option value="NT"<?php if($state == "NT") { echo " SELECTED"; } ?>>Northwest Territories</option>
<option value="NS"<?php if($state == "NS") { echo " SELECTED"; } ?>>Nova Scotia</option>
<option value="OH"<?php if($state == "OH") { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if($state == "OK") { echo " SELECTED"; } ?>>Oklahoma</option>
<option value="ON"<?php if($state == "ON") { echo " SELECTED"; } ?>>Ontario</option>
<option value="OR"<?php if($state == "OR") { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if($state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</option>
<option value="PE"<?php if($state == "PE") { echo " SELECTED"; } ?>>Prince Edward Island</option>
<option value="QC"<?php if($state == "QC") { echo " SELECTED"; } ?>>Quebec</option>
<option value="RI"<?php if($state == "RI") { echo " SELECTED"; } ?>>Rhode Island</option>
<option value="SK"<?php if($state == "SK") { echo " SELECTED"; } ?>>Saskatchewan</option>
<option value="SC"<?php if($state == "SC") { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if($state == "SD") { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if($state == "TN") { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if($state == "TX") { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if($state == "UT") { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if($state == "VT") { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if($state == "VA") { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if($state == "WA") { echo " SELECTED"; } ?>>Washington</option>
<option value="DC">Washington DC</option>
<option value="WV"<?php if($state == "WV") { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if($state == "WI") { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if($state == "WY") { echo " SELECTED"; } ?>>Wyoming</option>
<option value="YT"<?php if($state == "YT") { echo " SELECTED"; } ?>>Yukon</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Zip/Postal Code:</font></td><td><input type="text" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Country:</font></td><td><select name="country">
<option value="">Select a country</option>
<option value="US"<?php if($country == "US") { echo " SELECTED"; } elseif($country == "") { echo " SELECTED"; } ?>>United States</option>
<option value="AF"<?php if($country == "AF") { echo " SELECTED"; } ?>>Afghanistan</option>
<option value="AL"<?php if($country == "AL") { echo " SELECTED"; } ?>>Albania</option>
<option value="DZ"<?php if($country == "DZ") { echo " SELECTED"; } ?>>Algeria</option>
<option value="AS"<?php if($country == "AS") { echo " SELECTED"; } ?>>American Samoa</option>
<option value="AD"<?php if($country == "AD") { echo " SELECTED"; } ?>>Andorra</option>
<option value="AO"<?php if($country == "AO") { echo " SELECTED"; } ?>>Angola</option>
<option value="AI"<?php if($country == "AI") { echo " SELECTED"; } ?>>Anguilla</option>
<option value="AQ"<?php if($country == "AQ") { echo " SELECTED"; } ?>>Antarctica</option>
<option value="AG"<?php if($country == "AG") { echo " SELECTED"; } ?>>Antigua and Barbuda</option>
<option value="AR"<?php if($country == "AR") { echo " SELECTED"; } ?>>Argentina</option>
<option value="AM"<?php if($country == "AM") { echo " SELECTED"; } ?>>Armenia</option>
<option value="AW"<?php if($country == "AW") { echo " SELECTED"; } ?>>Aruba</option>
<option value="AU"<?php if($country == "AU") { echo " SELECTED"; } ?>>Australia</option>
<option value="AT"<?php if($country == "AT") { echo " SELECTED"; } ?>>Austria</option>
<option value="AZ"<?php if($country == "AZ") { echo " SELECTED"; } ?>>Azerbaijan</option>
<option value="BS"<?php if($country == "BS") { echo " SELECTED"; } ?>>Bahamas</option>
<option value="BH"<?php if($country == "BH") { echo " SELECTED"; } ?>>Bahrain</option>
<option value="BD"<?php if($country == "BD") { echo " SELECTED"; } ?>>Bangladesh</option>
<option value="BB"<?php if($country == "BB") { echo " SELECTED"; } ?>>Barbados</option>
<option value="BY"<?php if($country == "BY") { echo " SELECTED"; } ?>>Belarus</option>
<option value="BE"<?php if($country == "BE") { echo " SELECTED"; } ?>>Belgium</option>
<option value="BZ"<?php if($country == "BZ") { echo " SELECTED"; } ?>>Belize</option>
<option value="BJ"<?php if($country == "BJ") { echo " SELECTED"; } ?>>Benin</option>
<option value="BM"<?php if($country == "BM") { echo " SELECTED"; } ?>>Bermuda</option>
<option value="BT"<?php if($country == "BT") { echo " SELECTED"; } ?>>Bhutan</option>
<option value="BO"<?php if($country == "BO") { echo " SELECTED"; } ?>>Bolivia</option>
<option value="BA"<?php if($country == "BA") { echo " SELECTED"; } ?>>Bosnia and Herzegovina</option>
<option value="BW"<?php if($country == "BW") { echo " SELECTED"; } ?>>Botswana</option>
<option value="BV"<?php if($country == "BV") { echo " SELECTED"; } ?>>Bouvet Island</option>
<option value="BR"<?php if($country == "BR") { echo " SELECTED"; } ?>>Brazil</option>
<option value="IO"<?php if($country == "IO") { echo " SELECTED"; } ?>>British Indian Ocean Territory</option>
<option value="BN"<?php if($country == "BN") { echo " SELECTED"; } ?>>Brunei Darussalam</option>
<option value="BG"<?php if($country == "BG") { echo " SELECTED"; } ?>>Bulgaria</option>
<option value="BF"<?php if($country == "BF") { echo " SELECTED"; } ?>>Burkina Faso</option>
<option value="BI"<?php if($country == "BI") { echo " SELECTED"; } ?>>Burundi</option>
<option value="KH"<?php if($country == "KH") { echo " SELECTED"; } ?>>Cambodia</option>
<option value="CM"<?php if($country == "CM") { echo " SELECTED"; } ?>>Cameroon</option>
<option value="CA"<?php if($country == "CA") { echo " SELECTED"; } ?>>Canada</option>
<option value="CB"<?php if($country == "CB") { echo " SELECTED"; } ?>>Canary Islands</option>
<option value="CV"<?php if($country == "CV") { echo " SELECTED"; } ?>>Cape Verde</option>
<option value="KY"<?php if($country == "KY") { echo " SELECTED"; } ?>>Cayman Islands</option>
<option value="CF"<?php if($country == "CF") { echo " SELECTED"; } ?>>Central African Republic</option>
<option value="TD"<?php if($country == "TD") { echo " SELECTED"; } ?>>Chad</option>
<option value="CL"<?php if($country == "CL") { echo " SELECTED"; } ?>>Chile</option>
<option value="CN"<?php if($country == "CN") { echo " SELECTED"; } ?>>China</option>
<option value="CX"<?php if($country == "CX") { echo " SELECTED"; } ?>>Christmas Island</option>
<option value="CC"<?php if($country == "CC") { echo " SELECTED"; } ?>>Cocos (Keeling) Islands</option>
<option value="CO"<?php if($country == "CO") { echo " SELECTED"; } ?>>Colombia</option>
<option value="KM"<?php if($country == "KM") { echo " SELECTED"; } ?>>Comoros</option>
<option value="CG"<?php if($country == "CG") { echo " SELECTED"; } ?>>Congo</option>
<option value="CD"<?php if($country == "CD") { echo " SELECTED"; } ?>>Congo, The Democratic Republic of The</option>
<option value="CK"<?php if($country == "CK") { echo " SELECTED"; } ?>>Cook Islands</option>
<option value="CE"<?php if($country == "CE") { echo " SELECTED"; } ?>>Corsica</option>
<option value="CR"<?php if($country == "CR") { echo " SELECTED"; } ?>>Costa Rica</option>
<option value="CI"<?php if($country == "CI") { echo " SELECTED"; } ?>>Cote D'Ivoire</option>
<option value="HR"<?php if($country == "HR") { echo " SELECTED"; } ?>>Croatia</option>
<option value="CU"<?php if($country == "CU") { echo " SELECTED"; } ?>>Cuba</option>
<option value="CY"<?php if($country == "CY") { echo " SELECTED"; } ?>>Cyprus</option>
<option value="CZ"<?php if($country == "CZ") { echo " SELECTED"; } ?>>Czech Republic</option>
<option value="DK"<?php if($country == "DK") { echo " SELECTED"; } ?>>Denmark</option>
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="TP">East Timor</option>
<option value="EC">Ecuador</option>
<option value="EG"<?php if($country == "EG") { echo " SELECTED"; } ?>>Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="ET">Ethiopia</option>
<option value="FK">Falkland Islands (Malvinas)</option>
<option value="FO">Faroe Islands</option>
<option value="FJ"<?php if($country == "FJ") { echo " SELECTED"; } ?>>Fiji</option>
<option value="FI">Finland</option>
<option value="CS">Former Czechoslovakia</option>
<option value="SU">Former Ussr</option>
<option value="FR"<?php if($country == "FR") { echo " SELECTED"; } ?>>France</option>
<option value="FX">France (European Territories)</option>
<option value="GF">French Guiana</option>
<option value="PF">French Polynesia</option>
<option value="TF">French Southern Territories</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE"<?php if($country == "DE") { echo " SELECTED"; } ?>>Germany</option>
<option value="GH">Ghana</option>
<option value="GI">Gibraltar</option>
<option value="GB"<?php if($country == "GB") { echo " SELECTED"; } ?>>Great Britain</option>
<option value="GR"<?php if($country == "GR") { echo " SELECTED"; } ?>>Greece</option>
<option value="GL">Greenland</option>
<option value="GD">Grenada</option>
<option value="GP">Guadeloupe</option>
<option value="GU"<?php if($country == "GU") { echo " SELECTED"; } ?>>Guam</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GW">Guinea-Bissau</option>
<option value="GY">Guyana</option>
<option value="HT"<?php if($country == "HT") { echo " SELECTED"; } ?>>Haiti</option>
<option value="HM">Heard Island and Mcdonald Islands</option>
<option value="VA">Holy See (Vatican City State)</option>
<option value="HN">Honduras</option>
<option value="HK"<?php if($country == "HK") { echo " SELECTED"; } ?>>Hong Kong</option>
<option value="HU"<?php if($country == "HU") { echo " SELECTED"; } ?>>Hungary</option>
<option value="IS"<?php if($country == "IS") { echo " SELECTED"; } ?>>Iceland</option>
<option value="IN"<?php if($country == "IN") { echo " SELECTED"; } ?>>India</option>
<option value="ID"<?php if($country == "ID") { echo " SELECTED"; } ?>>Indonesia</option>
<option value="IR">Iran, Islamic Republic of</option>
<option value="IQ">Iraq</option>
<option value="IE"<?php if($country == "IE") { echo " SELECTED"; } ?>>Ireland</option>
<option value="IL"<?php if($country == "IL") { echo " SELECTED"; } ?>>Israel</option>
<option value="IT"<?php if($country == "IT") { echo " SELECTED"; } ?>>Italy</option>
<option value="JM"<?php if($country == "JM") { echo " SELECTED"; } ?>>Jamaica</option>
<option value="JP"<?php if($country == "JP") { echo " SELECTED"; } ?>>Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KO"<?php if($country == "KO") { echo " SELECTED"; } ?>>Korea</option>
<option value="KW"<?php if($country == "KW") { echo " SELECTED"; } ?>>Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Lao People's Democratic Republic</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libyan Arab Jamahiriya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU"<?php if($country == "LU") { echo " SELECTED"; } ?>>Luxembourg</option>
<option value="MO"<?php if($country == "MO") { echo " SELECTED"; } ?>>Macau</option>
<option value="MK">Macedonia, The Former Yugoslav Republic of</option>
<option value="MG">Madagascar</option>
<option value="MI">Madeira Islands</option>
<option value="MW">Malawi</option>
<option value="MY"<?php if($country == "MY") { echo " SELECTED"; } ?>>Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ"<?php if($country == "MQ") { echo " SELECTED"; } ?>>Martinique</option>
<option value="MR"<?php if($country == "MR") { echo " SELECTED"; } ?>>Mauritania</option>
<option value="MU"<?php if($country == "MU") { echo " SELECTED"; } ?>>Mauritius</option>
<option value="YT"<?php if($country == "YT") { echo " SELECTED"; } ?>>Mayotte</option>
<option value="MX"<?php if($country == "MX") { echo " SELECTED"; } ?>>Mexico</option>
<option value="FM"<?php if($country == "FM") { echo " SELECTED"; } ?>>Micronesia, Federated States of</option>
<option value="MD"<?php if($country == "MD") { echo " SELECTED"; } ?>>Moldova, Republic of</option>
<option value="MC"<?php if($country == "MC") { echo " SELECTED"; } ?>>Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP"<?php if($country == "NP") { echo " SELECTED"; } ?>>Nepal</option>
<option value="NL"<?php if($country == "NL") { echo " SELECTED"; } ?>>Netherlands</option>
<option value="AN">Netherlands Antilles</option>
<option value="NT">Neutral Zone</option>
<option value="NC">New Caledonia</option>
<option value="NZ"<?php if($country == "NZ") { echo " SELECTED"; } ?>>New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NU">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="KP"<?php if($country == "KP") { echo " SELECTED"; } ?>>North Korea</option>
<option value="MP"<?php if($country == "MP") { echo " SELECTED"; } ?>>Northern Mariana Islands</option>
<option value="NO"<?php if($country == "NO") { echo " SELECTED"; } ?>>Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PN">Pitcairn</option>
<option value="PL"<?php if($country == "PL") { echo " SELECTED"; } ?>>Poland</option>
<option value="PT"<?php if($country == "PT") { echo " SELECTED"; } ?>>Portugal</option>
<option value="PR"<?php if($country == "PR") { echo " SELECTED"; } ?>>Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="RE">Reunion (French)</option>
<option value="RO">Romania</option>
<option value="RU"<?php if($country == "RU") { echo " SELECTED"; } ?>>Russian Federation</option>
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
<option value="SF"<?php if($country == "SF") { echo " SELECTED"; } ?>>Scotland</option>
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
<option value="KR"<?php if($country == "KR") { echo " SELECTED"; } ?>>South Korea</option>
<option value="ES"<?php if($country == "ES") { echo " SELECTED"; } ?>>Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SJ">Svalbard and Jan Mayen</option>
<option value="SZ">Swaziland</option>
<option value="SE"<?php if($country == "SE") { echo " SELECTED"; } ?>>Sweden</option>
<option value="CH"<?php if($country == "CH") { echo " SELECTED"; } ?>>Switzerland</option>
<option value="SY">Syrian Arab Republic</option>
<option value="TW"<?php if($country == "TW") { echo " SELECTED"; } ?>>Taiwan, Province of China</option>
<option value="TJ">Tajikistan</option>
<option value="TZ">Tanzania, United Republic of</option>
<option value="TH"<?php if($country == "TH") { echo " SELECTED"; } ?>>Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT"<?php if($country == "TT") { echo " SELECTED"; } ?>>Trinidad and Tobago</option>
<option value="TN"<?php if($country == "TN") { echo " SELECTED"; } ?>>Tunisia</option>
<option value="TR"<?php if($country == "TR") { echo " SELECTED"; } ?>>Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TC">Turks and Caicos Islands</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="UK"<?php if($country == "UK") { echo " SELECTED"; } ?>>United Kingdom</option>
<option value="US">United States</option>
<option value="UM">United States Minor Outlying Islands</option>
<option value="UY"<?php if($country == "UY") { echo " SELECTED"; } ?>>Uruguay</option>
<option value="UZ"<?php if($country == "UZ") { echo " SELECTED"; } ?>>Uzbekistan</option>
<option value="VU"<?php if($country == "VU") { echo " SELECTED"; } ?>>Vanuatu</option>
<option value="VE"<?php if($country == "VE") { echo " SELECTED"; } ?>>Venezuela</option>
<option value="VN"<?php if($country == "VN") { echo " SELECTED"; } ?>>Viet Nam</option>
<option value="VG"<?php if($country == "VG") { echo " SELECTED"; } ?>>Virgin Islands, British</option>
<option value="VI"<?php if($country == "VI") { echo " SELECTED"; } ?>>Virgin Islands, U.S.</option>
<option value="WF"<?php if($country == "WF") { echo " SELECTED"; } ?>>Wallis and Futuna</option>
<option value="EH"<?php if($country == "EH") { echo " SELECTED"; } ?>>Western Sahara</option>
<option value="YE"<?php if($country == "YE") { echo " SELECTED"; } ?>>Yemen</option>
<option value="YU"<?php if($country == "YU") { echo " SELECTED"; } ?>>Yugoslavia</option>
<option value="ZR"<?php if($country == "ZR") { echo " SELECTED"; } ?>>Zaire</option>
<option value="ZM"<?php if($country == "ZM") { echo " SELECTED"; } ?>>Zambia</option>
<option value="ZW"<?php if($country == "ZW") { echo " SELECTED"; } ?>>Zimbabwe</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Email:</font></td><td><input type="text" name="email" size="30" maxlength="255" value="<?php echo $email; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Phone:</font></td><td><input type="text" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Fax/Other Phone:</font></td><td><input type="text" name="fax_other_phone" size="30" maxlength="30" value="<?php echo $fax_other_phone; ?>"></td></tr>
<?php if($website == "") { $website = "http://"; } ?>
<tr><td align="right"><font face="Arial" size="+1">Website:</font></td><td><input type="text" name="website" size="30" maxlength="255" value="<?php echo $website; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Type of Retailer:</font></td><td><table border="0">
<tr>
<?php
$query = "SELECT retailer_type_id FROM leads_type_link WHERE leads_id='$leads_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["retailer_type_id"] == 1) { $temp_retailer_type_id1 = 1; }
	if($line["retailer_type_id"] == 2) { $temp_retailer_type_id2 = 1; }
	if($line["retailer_type_id"] == 3) { $temp_retailer_type_id3 = 1; }
	if($line["retailer_type_id"] == 4) { $temp_retailer_type_id4 = 1; }
	if($line["retailer_type_id"] == 5) { $temp_retailer_type_id5 = 1; }
	if($line["retailer_type_id"] == 6) { $temp_retailer_type_id6 = 1; }
	if($line["retailer_type_id"] == 7) { $temp_retailer_type_id7 = 1; }
	if($line["retailer_type_id"] == 8) { $temp_retailer_type_id8 = 1; }
	if($line["retailer_type_id"] == 9) { $temp_retailer_type_id9 = 1; }
	if($line["retailer_type_id"] == 10) { $temp_retailer_type_id10 = 1; }
	if($line["retailer_type_id"] == 11) { $temp_retailer_type_id11 = 1; }
	if($line["retailer_type_id"] == 12) { $temp_retailer_type_id12 = 1; }
	if($line["retailer_type_id"] == 13) { $temp_retailer_type_id13 = 1; }
	if($line["retailer_type_id"] == 14) { $temp_retailer_type_id14 = 1; }
	if($line["retailer_type_id"] == 15) { $temp_retailer_type_id15 = 1; }
	if($line["retailer_type_id"] == 16) { $temp_retailer_type_id16 = 1; }
	if($line["retailer_type_id"] == 17) { $temp_retailer_type_id17 = 1; }
	if($line["retailer_type_id"] == 18) { $temp_retailer_type_id18 = 1; }
	if($line["retailer_type_id"] == 19) { $temp_retailer_type_id19 = 1; }
	if($line["retailer_type_id"] == 20) { $temp_retailer_type_id20 = 1; }
}
mysql_free_result($result);

$retailer_chkbox1_counter = 1;
$query = "SELECT retailer_type_id, name FROM retailer_type";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$retailer_type_id = $line["retailer_type_id"];
	$name = $line["name"];
	echo "<td NOWRAP><input type=\"checkbox\" name=\"retailer_type" . $retailer_type_id . "\" value=\"1\"";
	if($retailer_chkbox1_counter == 1 && $temp_retailer_type_id1 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 2 && $temp_retailer_type_id2 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 3 && $temp_retailer_type_id3 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 4 && $temp_retailer_type_id4 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 5 && $temp_retailer_type_id5 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 6 && $temp_retailer_type_id6 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 7 && $temp_retailer_type_id7 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 8 && $temp_retailer_type_id8 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 9 && $temp_retailer_type_id9 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 10 && $temp_retailer_type_id10 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 11 && $temp_retailer_type_id11 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 12 && $temp_retailer_type_id12 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 13 && $temp_retailer_type_id13 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 14 && $temp_retailer_type_id14 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 15 && $temp_retailer_type_id15 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 16 && $temp_retailer_type_id16 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 17 && $temp_retailer_type_id17 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 18 && $temp_retailer_type_id18 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 19 && $temp_retailer_type_id19 == 1) { echo " CHECKED"; }
	if($retailer_chkbox1_counter == 20 && $temp_retailer_type_id20 == 1) { echo " CHECKED"; }
	echo "> <font face=\"Arial\" size=\"+1\">" . $name . "</font></td>";
	if($retailer_chkbox1_counter == 3 || $retailer_chkbox1_counter == 6 || $retailer_chkbox1_counter == 9 || $retailer_chkbox1_counter == 12 || $retailer_chkbox1_counter == 15 || $retailer_chkbox1_counter == 18) { echo "</tr><tr>"; }
$retailer_chkbox1_counter++;
}
mysql_free_result($result);

?>
</tr>
</table>
</td></tr>
<tr><td align="right"><font face="Arial" size="+1">Where did you find this Retailer:</font></td><td><input type="text" name="where_store_found" size="30" maxlength="150" value="<?php echo stripslashes($where_store_found); ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Comment and Notes Section:</font></td><td><TEXTAREA name="comments" cols="40" rows="7"><?php echo stripslashes($comments); ?></TEXTAREA></td></tr>

<tr><td colspan="2" align="center"><input type="submit" name="submit" value=" Create New Lead "></td></tr>
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