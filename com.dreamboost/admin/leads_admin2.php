<?php
// BME WMS
// Page: Leads Search Results page
// Path/File: /admin/leads_admin2.php
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

$store_name = $_POST["store_name"];
$contact_name = $_POST["contact_name"];
$city = $_POST["city"];
$state = $_POST["state"];
$phone = $_POST["phone"];
$zip = $_POST["zip"];
$country = $_POST["country"];
$where_store_found = addslashes($_POST["where_store_found"]);

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Search Leads";
wms_manager_nav2($manager);
wms_page_nav2($manager);

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

<tr><td align="left"><font size="2">Results from your Search of Leads</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form action="./leads_admin3.php" method="POST">
<input type="hidden" name="store_name" value="<?php echo $store_name; ?>">
<input type="hidden" name="contact_name" value="<?php echo $contact_name; ?>">
<input type="hidden" name="city" value="<?php echo $city; ?>">
<input type="hidden" name="state" value="<?php echo $state; ?>">
<input type="hidden" name="phone" value="<?php echo $phone; ?>">
<input type="hidden" name="zip" value="<?php echo $zip; ?>">
<tr><td align="center"><input type="submit" name="create_lead" value=" Create a New Lead "></td></tr>
</form>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0">
<tr><td><font face="Arial" size="+1"><b>Store Name</b></font></td><td><font face="Arial" size="+1"><b>Contact Name</b></font></td><td><font face="Arial" size="+1"><b>Address</b></font></td><td><font face="Arial" size="+1"><b>City</b></font></td><td><font face="Arial" size="+1"><b>State</b></font></td><td><font face="Arial" size="+1"><b>Zip</b></font></td><td><font face="Arial" size="+1"><b>Phone</b></font></td></tr>

<?php
	$query = "SELECT leads_id,";
	$query .= " store_name,";
	$query .= " contact_name,";
	$query .= " address1,";
	$query .= " city,";
	$query .= " state,";
	$query .= " zip,";
	$query .= " phone";
	$query .= " FROM leads";
	$query .= " WHERE";
	$query .= " leads_status='1'";

	if($store_name != "") {
		$query .= " AND store_name LIKE '%".addslashes($store_name)."%'";
	}
	if($contact_name != "") {
		$query .= " AND contact_name LIKE '%".addslashes($contact_name)."%'";
	}
	if($city != "") {
		$query .= " AND city LIKE '%".addslashes($city)."%'";
	}
	if($phone != "") {
		$query .= " AND (phone='$phone' OR fax_other_phone='$phone')";
	}
	if($zip != "") {
		$query .= " AND zip='$zip'";
	}
	if($state != "") {
		$query .= " AND state='$state'";
	}
	if($country != "") {
		$query .= " AND country='$country'";
	}
	if ( $where_store_found != "" ) {
		$query .= " AND where_store_found LIKE'%$where_store_found%'";
	}

	$query .= " ORDER BY zip DESC";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td><font face=\"Arial\" size=\"+1\">";
		echo "<a href=\"./leads_admin4.php?edit=";
		echo $line["leads_id"] . "\">";
		echo stripslashes($line["store_name"]);
		echo "</a></font></td><td><font face=\"Arial\" size=\"+1\">";
		echo stripslashes($line["contact_name"]);
		echo "</font></td><td><font face=\"Arial\" size=\"+1\">";
		echo stripslashes($line["address1"]);
		echo "</font></td><td><font face=\"Arial\" size=\"+1\">";
		echo stripslashes($line["city"]);
		echo "</font></td><td><font face=\"Arial\" size=\"+1\">";
		echo $line["state"];
		echo "</font></td><td><font face=\"Arial\" size=\"+1\">";
		echo $line["zip"];
		echo "</font></td><td NOWRAP><font face=\"Arial\" size=\"+1\">";
		echo $line["phone"];
		echo "</font></td></tr>\n";
	}
	mysql_free_result($result);
?>
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