<?php
// BME WMS
// Page: Leads Mailing Labels page
// Path/File: /admin/leads_admin13.php
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

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Leads Mailing Labels";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if(isset($_POST['Generate'])) {
	$show_results = "1";
	$location_state = $_POST['location_state'];
	if($location_state == "all") { $location_state_text = "state!=''"; }
	elseif($location_state == "none") { $location_state_text = "state=''"; }
	else { $location_state_text = "state='" . $location_state . "'"; }
	$location = $_POST['location'];
	if($location == "us") { $location_text = "country='US'"; }
	elseif($location == "intl") { $location_text = "country!='' AND country!='US'"; }
	elseif($location == "all") { $location_text = "country!=''"; }
	$type_retailer = $_POST['type_retailer'];
	if($type_retailer != "") {
		$retailer_type_text = "leads.leads_id=leads_type_link.leads_id AND leads_type_link.retailer_type_id='$type_retailer'";
	}
	$order_by = $_POST['order_by'];
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

<tr><td align="left"><font size="2">Use the form below to select which mailing labels to generate.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form action="./leads_admin13.php" method="POST">
<tr><td align="left"><table border="0">
<tr><td colspan="2"><font size="2"><b>Select Criteria</b></font></td></tr>
<tr><td><font face="Arial" size="+1">Location - State/Province</font></td><td><select name="location_state">
<option value="all"<?php if($location_state == "all") { echo " SELECTED"; } ?>>All</option>
<option value="none"<?php if($location_state == "none") { echo " SELECTED"; } ?>>None</option>
<option value="AA"<?php if($location_state == "AA") { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if($location_state == "AE") { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if($location_state == "AP") { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if($location_state == "AL") { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if($location_state == "AK") { echo " SELECTED"; } ?>>Alaska</option>
<option value="AB"<?php if($location_state == "AB") { echo " SELECTED"; } ?>>Alberta</option>
<option value="AZ"<?php if($location_state == "AZ") { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if($location_state == "AR") { echo " SELECTED"; } ?>>Arkansas</option>
<option value="BC"<?php if($location_state == "BC") { echo " SELECTED"; } ?>>British Columbia</option>
<option value="CA"<?php if($location_state == "CA") { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if($location_state == "CO") { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if($location_state == "CT") { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if($location_state == "DE") { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if($location_state == "DC") { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if($location_state == "FL") { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if($location_state == "GA") { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if($location_state == "HI") { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if($location_state == "ID") { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if($location_state == "IL") { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if($location_state == "IN") { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if($location_state == "IA") { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if($location_state == "KS") { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if($location_state == "KY") { echo " SELECTED"; } ?>>Kentucky</option>
<!-- Illegal <option value="LA">Louisiana</option>-->
<option value="ME"<?php if($location_state == "ME") { echo " SELECTED"; } ?>>Maine</option>
<option value="MB"<?php if($location_state == "MB") { echo " SELECTED"; } ?>>Manitoba</option>
<option value="MD"<?php if($location_state == "MD") { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if($location_state == "MA") { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if($location_state == "MI") { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if($location_state == "MN") { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if($location_state == "MS") { echo " SELECTED"; } ?>>Mississippi</option>
<!-- Illegal <option value="MO">Missouri</option>-->
<option value="MT"<?php if($location_state == "MT") { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if($location_state == "NE") { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if($location_state == "NV") { echo " SELECTED"; } ?>>Nevada</option>
<option value="NB"<?php if($location_state == "NB") { echo " SELECTED"; } ?>>New Brunswick</option>
<option value="NH"<?php if($location_state == "NH") { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if($location_state == "NJ") { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if($location_state == "NM") { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if($location_state == "NY") { echo " SELECTED"; } ?>>New York</option>
<option value="NF"<?php if($location_state == "NF") { echo " SELECTED"; } ?>>Newfoundland</option>
<option value="NC"<?php if($location_state == "NC") { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if($location_state == "ND") { echo " SELECTED"; } ?>>North Dakota</option>
<option value="NT"<?php if($location_state == "NT") { echo " SELECTED"; } ?>>Northwest Territories</option>
<option value="NS"<?php if($location_state == "NS") { echo " SELECTED"; } ?>>Nova Scotia</option>
<option value="OH"<?php if($location_state == "OH") { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if($location_state == "OK") { echo " SELECTED"; } ?>>Oklahoma</option>
<option value="ON"<?php if($location_state == "ON") { echo " SELECTED"; } ?>>Ontario</option>
<option value="OR"<?php if($location_state == "OR") { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if($location_state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</option>
<option value="PE"<?php if($location_state == "PE") { echo " SELECTED"; } ?>>Prince Edward Island</option>
<option value="QC"<?php if($location_state == "QC") { echo " SELECTED"; } ?>>Quebec</option>
<option value="RI"<?php if($location_state == "RI") { echo " SELECTED"; } ?>>Rhode Island</option>
<option value="SK"<?php if($location_state == "SK") { echo " SELECTED"; } ?>>Saskatchewan</option>
<option value="SC"<?php if($location_state == "SC") { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if($location_state == "SD") { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if($location_state == "TN") { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if($location_state == "TX") { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if($location_state == "UT") { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if($location_state == "VT") { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if($location_state == "VA") { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if($location_state == "WA") { echo " SELECTED"; } ?>>Washington</option>
<option value="DC">Washington DC</option>
<option value="WV"<?php if($location_state == "WV") { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if($location_state == "WI") { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if($location_state == "WY") { echo " SELECTED"; } ?>>Wyoming</option>
<option value="YT"<?php if($location_state == "YT") { echo " SELECTED"; } ?>>Yukon</option>
</select></td></tr>
<tr><td><font face="Arial" size="+1">Location - Country</font></td><td><select name="location">
<option value="all"<?php if($location == "all") { echo " SELECTED"; } ?>>All</option>
<option value="us"<?php if($location == "us") { echo " SELECTED"; } ?>>US</option>
<option value="intl"<?php if($location == "intl") { echo " SELECTED"; } ?>>International</option>
</select></td></tr>
<tr><td><font face="Arial" size="+1">Type of Retailer</font></td><td><select name="type_retailer">
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
<tr><td><font face="Arial" size="+1">Order Results By</font></td><td><select name="order_by">
<option value="zip"<?php if($order_by == "zip") { echo " SELECTED"; } ?>>Zip/Postal Code</option>
<option value="store_name"<?php if($order_by == "store_name") { echo " SELECTED"; } ?>>Store Name</option>
<option value="where_store_found"<?php if($order_by == "where_store_found") { echo " SELECTED"; } ?>>Where Store was Found</option>
</select></td></tr>

<tr><td colspan="2" align="center"><input type="submit" name="Generate" value="Generate Mailing Labels"></td></tr>
</table></td></tr>
</form>

<?php
if($show_results == "1") {
	$query = "SELECT leads.store_name, leads.contact_name, leads.address1, ";
	$query .= "leads.address2, leads.city, leads.state, leads.zip ";
	$query .= "FROM leads";
	if($retailer_type_text != "") {
		$query .= ", leads_type_link";
	}
	$query .= " WHERE ";
	if($retailer_type_text != "") {
		$query .= "$retailer_type_text AND ";
	}
	$query .= "$location_state_text AND $location_text ";
	$query .= "ORDER BY ";
	$query .= $order_by;
	//echo "Query: " . $query . "<br>\n";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td align=\"left\" NOWRAP><font face=\"Arial\" size=\"3\">";
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