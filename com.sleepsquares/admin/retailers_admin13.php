<?php
// BME WMS
// Page: Retailers Mailing Labels page
// Path/File: /admin/retailers_admin13.php
// Version: 1.8
// Build: 1806
// Date: 07-12-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

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
$page = "Retailers Manager > Retailers Mailing Labels";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if(isset($_POST['Generate'])) {
	$show_results = "1";
	$retailer_status = $_POST['retailer_status'];
	$retailer_status_text = '';
	if ( !$retailer_status || in_array("all", $retailer_status) ) {
		$retailer_status_text = "(retailer_status='1' OR retailer_status='0' OR retailer_status='2' OR retailer_status='3' OR retailer_status='4' OR retailer_status='5' OR retailer_status='6')";
		$retailer_status = 'all';
	}
	else {
		foreach ($retailer_status as $this_rs) {
			$retailer_status_text .= ($retailer_status_text=='' ? "":" OR ")."retailer_status='".$this_rs."'";
		}
		$retailer_status_text = '('.$retailer_status_text.')';
		$retailer_status = '|'.join('|', $retailer_status);
	}

	$location_state = $_POST['location_state'];
	$location_state_text = '';
	if ( !$location_state || in_array("all", $location_state) ) {
		$location_state_text = "(state!='' OR state='')";
		$location_state = 'all';
	}
	else if ( in_array("none", $location_state) ) {
		$location_state_text = "state=''";
		$location_state = 'none';
	}
	else {
		foreach ($location_state as $this_ls){
			$location_state_text .= ($location_state_text=='' ? "":" OR ")."state='".$this_ls."'";
		}
		$location_state_text = '('.$location_state_text.')';
		$location_state = '|'.join('|', $location_state);
	}
	
	$location = $_POST['location'];
	if($location == "us") { $location_text = "country='US'"; }
	elseif($location == "intl") { $location_text = "country!='' AND country!='US'"; }
	elseif($location == "all") { $location_text = "(country!='' OR country='')"; }
	$next_contact_by_person = $_POST['next_contact_by_person'];
	if($next_contact_by_person == "all") { $next_contact_by_person_text = "(next_contact_by_person!='' OR next_contact_by_person='')"; }
	else { $next_contact_by_person_text = "next_contact_by_person='" . $next_contact_by_person . "'"; }
	$next_contact_by = $_POST['next_contact_by'];
	if($next_contact_by == "all") { $next_contact_by_text = "(next_contact_by!='' OR next_contact_by='')"; }
	else { $next_contact_by_text = "next_contact_by='" . $next_contact_by . "'"; }
	$type_retailer = $_POST['type_retailer'];
	if($type_retailer != "") {
		$retailer_type_text = "retailer.retailer_id=retailer_type_link.retailer_id AND retailer_type_link.retailer_type_id='$type_retailer'";
	}
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

<tr><td align="left"><font size="2">Generate Mailing Labels for the selected Retailers.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form action="./retailers_admin13.php" method="POST">
<tr valign="top"><td align="left"><table border="0">
<tr valign="top"><td colspan="2"><font size="2"><b>Select Criteria</b></font></td></tr>

<tr valign="top"><td><font face="Arial" size="+1">Retailer For <?php echo $product_line; ?> Status:</font></td><td>
(hold Shift to select multiple)<br />
<select name="retailer_status[]" multiple="multiple" size="4">
<option value="all"<?php if($retailer_status == "all") { echo " SELECTED"; } ?>>All</option>
<option value="1"<? if( strpos($retailer_status,"1") ) { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if( strpos($retailer_status,"0") ) { echo " SELECTED"; } ?>>No</option>
<option value="2"<? if( strpos($retailer_status,"2") ) { echo " SELECTED"; } ?>>Pending</option>
<option value="3"<? if( strpos($retailer_status,"3") ) { echo " SELECTED"; } ?>>Not Contacted Yet</option>
<option value="4"<? if( strpos($retailer_status,"4") ) { echo " SELECTED"; } ?>>Hold - Past Due Account</option>
<option value="5"<? if( strpos($retailer_status,"5") ) { echo " SELECTED"; } ?>>Inactive</option>
<option value="6"<? if( strpos($retailer_status,"6") ) { echo " SELECTED"; } ?>>Invalid</option>
</select></td></tr>

<tr valign="top"><td><font face="Arial" size="+1">Location - State/Province</font></td><td>
(hold Shift to select multiple)<br />
<select name="location_state[]" multiple="multiple" size="10">
<option value="all"<?php if($location_state == "all") { echo " SELECTED"; } ?>>All</option>
<option value="none"<?php if($location_state == "none") { echo " SELECTED"; } ?>>None</option>
<option value="AA"<?php if( strpos($location_state,"AA") ) { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if( strpos($location_state,"AE") ) { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if( strpos($location_state,"AP") ) { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if( strpos($location_state,"AL") ) { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if( strpos($location_state,"AK") ) { echo " SELECTED"; } ?>>Alaska</option>
<option value="AB"<?php if( strpos($location_state,"AB") ) { echo " SELECTED"; } ?>>Alberta</option>
<option value="AZ"<?php if( strpos($location_state,"AZ") ) { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if( strpos($location_state,"AR") ) { echo " SELECTED"; } ?>>Arkansas</option>
<option value="BC"<?php if( strpos($location_state,"BC") ) { echo " SELECTED"; } ?>>British Columbia</option>
<option value="CA"<?php if( strpos($location_state,"CA") ) { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if( strpos($location_state,"CO") ) { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if( strpos($location_state,"CT") ) { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if( strpos($location_state,"DE") ) { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if( strpos($location_state,"DC") ) { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if( strpos($location_state,"FL") ) { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if( strpos($location_state,"GA") ) { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if( strpos($location_state,"HI") ) { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if( strpos($location_state,"ID") ) { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if( strpos($location_state,"IL") ) { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if( strpos($location_state,"IN") ) { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if( strpos($location_state,"IA") ) { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if( strpos($location_state,"KS") ) { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if( strpos($location_state,"KY") ) { echo " SELECTED"; } ?>>Kentucky</option>
<option value="LA"<?php if( strpos($location_state,"LA") ) { echo " SELECTED"; } ?>>Louisiana</option>
<option value="ME"<?php if( strpos($location_state,"ME") ) { echo " SELECTED"; } ?>>Maine</option>
<option value="MB"<?php if( strpos($location_state,"MB") ) { echo " SELECTED"; } ?>>Manitoba</option>
<option value="MD"<?php if( strpos($location_state,"MD") ) { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if( strpos($location_state,"MA") ) { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if( strpos($location_state,"MI") ) { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if( strpos($location_state,"MN") ) { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if( strpos($location_state,"MS") ) { echo " SELECTED"; } ?>>Mississippi</option>
<option value="MO"<?php if( strpos($location_state,"MO") ) { echo " SELECTED"; } ?>>Missouri</option>
<option value="MT"<?php if( strpos($location_state,"MT") ) { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if( strpos($location_state,"NE") ) { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if( strpos($location_state,"NV") ) { echo " SELECTED"; } ?>>Nevada</option>
<option value="NB"<?php if( strpos($location_state,"NB") ) { echo " SELECTED"; } ?>>New Brunswick</option>
<option value="NH"<?php if( strpos($location_state,"NH") ) { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if( strpos($location_state,"NJ") ) { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if( strpos($location_state,"NM") ) { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if( strpos($location_state,"NY") ) { echo " SELECTED"; } ?>>New York</option>
<option value="NF"<?php if( strpos($location_state,"NF") ) { echo " SELECTED"; } ?>>Newfoundland</option>
<option value="NC"<?php if( strpos($location_state,"NC") ) { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if( strpos($location_state,"ND") ) { echo " SELECTED"; } ?>>North Dakota</option>
<option value="NT"<?php if( strpos($location_state,"NT") ) { echo " SELECTED"; } ?>>Northwest Territories</option>
<option value="NS"<?php if( strpos($location_state,"NS") ) { echo " SELECTED"; } ?>>Nova Scotia</option>
<option value="OH"<?php if( strpos($location_state,"OH") ) { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if( strpos($location_state,"OK") ) { echo " SELECTED"; } ?>>Oklahoma</option>
<option value="ON"<?php if( strpos($location_state,"ON") ) { echo " SELECTED"; } ?>>Ontario</option>
<option value="OR"<?php if( strpos($location_state,"OR") ) { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if( strpos($location_state,"PA") ) { echo " SELECTED"; } ?>>Pennsylvania</option>
<option value="PE"<?php if( strpos($location_state,"PE") ) { echo " SELECTED"; } ?>>Prince Edward Island</option>
<option value="QC"<?php if( strpos($location_state,"QC") ) { echo " SELECTED"; } ?>>Quebec</option>
<option value="RI"<?php if( strpos($location_state,"RI") ) { echo " SELECTED"; } ?>>Rhode Island</option>
<option value="SK"<?php if( strpos($location_state,"SK") ) { echo " SELECTED"; } ?>>Saskatchewan</option>
<option value="SC"<?php if( strpos($location_state,"SC") ) { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if( strpos($location_state,"SD") ) { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if( strpos($location_state,"TN") ) { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if( strpos($location_state,"TX") ) { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if( strpos($location_state,"UT") ) { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if( strpos($location_state,"VT") ) { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if( strpos($location_state,"VA") ) { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if( strpos($location_state,"WA") ) { echo " SELECTED"; } ?>>Washington</option>
<option value="DC">Washington DC</option>
<option value="WV"<?php if( strpos($location_state,"WV") ) { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if( strpos($location_state,"WI") ) { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if( strpos($location_state,"WY") ) { echo " SELECTED"; } ?>>Wyoming</option>
<option value="YT"<?php if( strpos($location_state,"YT") ) { echo " SELECTED"; } ?>>Yukon</option>
</select></td></tr>
<tr valign="top"><td><font face="Arial" size="+1">Location - Country</font></td><td><select name="location">
<option value="all"<?php if($location == "all") { echo " SELECTED"; } ?>>All</option>
<option value="us"<?php if($location == "us") { echo " SELECTED"; } ?>>US</option>
<option value="intl"<?php if($location == "intl") { echo " SELECTED"; } ?>>International</option>
</select></td></tr>
<tr valign="top"><td><font face="Arial" size="+1">User Who Next Contacts Retailer</font></td><td><select name="next_contact_by_person">
<option value="all"<?php if($next_contact_by_person == "all") { echo " SELECTED"; } ?>>All</option>
<option value="0"<?php if($next_contact_by_person == "0") { echo " SELECTED"; } ?>>Nobody</option>
<?php
$query = "SELECT user_id, first_name, last_name FROM wms_users";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$user_id = $line["user_id"];
	$first_name = $line["first_name"];
	$last_name = $line["last_name"];
	echo "<option value=\"" . $user_id . "\"";
	if($next_contact_by_person == $user_id) { echo " SELECTED"; }
	echo ">" . $first_name . " " . $last_name . "</option>\n";
}
mysql_free_result($result);
?>
</select></td></tr>
<tr valign="top"><td><font face="Arial" size="+1">Next contact this Retailer by:</font></td><td><select name="next_contact_by">
<option value="all"<? if($next_contact_by == "all") { echo " SELECTED"; } ?>>All</option>
<option value="None"<? if($next_contact_by == "None") { echo " SELECTED"; } ?>>None</option>
<option value="None Yet"<? if($next_contact_by == "None Yet") { echo " SELECTED"; } ?>>None Yet</option>
<option value="Phone"<? if($next_contact_by == "Phone") { echo " SELECTED"; } ?>>Phone</option>
<option value="Mail"<? if($next_contact_by == "Mail") { echo " SELECTED"; } ?>>Mail</option>
<option value="In Person"<? if($next_contact_by == "In Person") { echo " SELECTED"; } ?>>In Person</option>
<option value="Email"<? if($next_contact_by == "Email") { echo " SELECTED"; } ?>>Email</option>
<option value="Fax"<? if($next_contact_by == "Fax") { echo " SELECTED"; } ?>>Fax</option>
</select></td></tr>

<tr valign="top"><td><font face="Arial" size="+1">Type of Retailer</font></td><td><select name="type_retailer">
<option value="">All</option>
<?php
	$query = "SELECT retailer_type_id, name FROM retailer_type";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$retailer_type[] = $line["retailer_type_id"] . "|" . $line["name"];
	}
	mysql_free_result($result);

	$retailer_type_count = count($retailer_type);	
	for($i=0;$i<$retailer_type_count;$i++) {
		list($retailer_type_id, $retailer_type_name) = explode('|', $retailer_type[$i]);

		echo "<option value=\"$retailer_type_id\"";
		if($type_retailer == $retailer_type_id) { echo " SELECTED"; }
		echo ">";
		echo $retailer_type_name;
		echo "</option>\n";
	}
?>
</select></td></tr>

<tr valign="top"><td colspan="2" align="center"><input type="submit" name="Generate" value="Generate Mailing Labels"></td></tr>
</table></td></tr>
</form>

<?php
if($show_results == "1") {
	$result_counter = 0;
	$query = "SELECT store_name, contact_name, address1, address2, city, state, zip FROM retailer";
	if($retailer_type_text != "") {
		$query .= ", retailer_type_link";
	}
	$query .= " WHERE ";
	if($retailer_type_text != "") {
		$query .= "$retailer_type_text AND ";
	}

	$query .= "$retailer_status_text AND ";
	$query .= "$location_state_text AND ";
	$query .= "$location_text AND $next_contact_by_person_text AND $next_contact_by_text";
	//echo "Query: " . $query . "<br>\n";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$result_counter++;
		echo "<tr valign=\"top\"><td align=\"left\" NOWRAP><font face=\"Arial\" size=\"3\">";
		echo stripslashes($line["store_name"]) . "|";
		echo "Attn: " . stripslashes($line["contact_name"]);
		if($line["contact_name"] != "") {
			echo " Or ";
		}
		echo "Current Manager/Buyer|";
		echo stripslashes($line["address1"]) . "|";
		echo stripslashes($line["address2"]) . "|";
		echo stripslashes($line["city"]) . "|";
		echo $line["state"] . "|";
		echo $line["zip"];
		echo "</font></td></tr>\n";
	}
	mysql_free_result($result);
	echo "<tr valign=\"top\"><td align=\"left\" NOWRAP><font face=\"Arial\" size=\"3\">";
	echo "Results Count = " . $result_counter;
	echo "</font></td></tr>\n";
}
?>

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