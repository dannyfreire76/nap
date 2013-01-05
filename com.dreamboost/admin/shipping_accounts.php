<?php
// BME WMS
// Page: Shipping Accounts page
// Path/File: /admin/shipping_accounts.php
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
$manager = "shipping";
$page = "Shipping Manager > Shipping Accounts";
wms_manager_nav2($manager);
wms_page_nav2($manager);

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

<tr><td align="left"><font size="2">Enter your Account Numbers for each shipping company and control their status.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Shipper</th><th scope="col">Account Number</th><th scope="col">Status</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT shipping_account_id, shipper, account_number, status FROM shipping_accounts ORDER BY shipping_account_id";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"shipping\" Method=\"POST\" ACTION=\"./shipping_accounts_edit.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	if($line["shipper"] == "FEDX") {
		echo "FedEx";
	} elseif($line["shipper"] == "USPS") {
		echo "USPS";
	} elseif($line["shipper"] == "UPS") {
		echo "UPS";
	} elseif($line["shipper"] == "DHL") {
		echo "DHL";
	}
	echo "</td>";
	echo "<input type=\"hidden\" name=\"shipping_account_id\" value=\"";
	echo $line["shipping_account_id"];
	echo "\"><td>";
	echo $line["account_number"];
	echo "</td><td>";
	if($line["status"] == "1") { echo "Active"; }
	elseif($line["status"] == "0") { echo "Inactive"; }
	echo "</td><td><input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\"></td></tr>\n";
	echo "</form>\n";
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