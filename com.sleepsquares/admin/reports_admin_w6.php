<?php
// BME WMS
// Page: Reports Yearly Sales page
// Path/File: /admin/reports_admin_w5.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once("./includes/date_functions.php");
include_once($base_path."includes/wc1.php");

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Wholesale > Yearly Sales Report";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Yearly Wholesale Sales Report. This page reports all the Yearly Wholesale sales that have been transacted through the website.</font></td></tr>
</form>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0" cellspacing="4" cellpadding="2">
<tr><td><font face="Verdana" size="+1"><b>Date </b></font></td><td><font face="Verdana" size="+1"><b>Sub-Total </b></font></td><td><font face="Verdana" size="+1"><b>Tax </b></font></td><td><font face="Verdana" size="+1"><b>Shipping </b></font></td><td><font face="Verdana" size="+1"><b>Discount </b></font></td><td><font face="Verdana" size="+1"><b>Grand Total </b></font></td><td><font face="Verdana" size="+1"><b>Orders </b></font></td><td><font face="Verdana" size="+1"><b>Average Order </b></font></td></tr>

<?php
$i = 1;
$subtotal = 0;
$tax_total = 0;
$ship_total = 0;
$grand_total = 0;
$order_total = 0;
$avg_total = 0;

for ( $query_year=2004; $query_year<=date("Y"); $query_year++  ) {
	$date_criteria = getDateCriteriaYrOnly($query_year);
	$date = $date_criteria["date"];
	$date2 = $date_criteria["date2"];

$query = "SELECT subtotal, complete, tax, shipping, discount FROM wholesale_receipts WHERE complete='1' $date2";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
	$tmp_subtotal = $tmp_subtotal + $line["subtotal"];
	$tmp_complete = $tmp_complete + $line["complete"];
	$tmp_tax = $tmp_tax + $line["tax"];
	$tmp_shipping = $tmp_shipping + $line["shipping"];
	$tmp_discount = $tmp_discount + $line["discount"];
}
	$tmp_grand = $tmp_subtotal + $tmp_tax + $tmp_shipping - $tmp_discount;

	$subtotal = $subtotal + $tmp_subtotal;
	$tax_total = $tax_total + $tmp_tax;
	$ship_total = $ship_total + $tmp_shipping;
	$discount_total = $discount_total + $tmp_discount;
	$grand_total = $grand_total + $tmp_grand;
	$order_total = $order_total + $tmp_complete;
	if($order_total != 0) {
		$avg_total = $grand_total / $order_total;
	} else {
		$avg_total = 0;
	}

	if($tmp_complete != 0 || $tmp_complete != "") { 
		$avg_order = $tmp_grand / $tmp_complete;
	} else { 
		$avg_order = 0;
	}
	
	if($tmp_subtotal != 0) {
		echo "<tr><td><font face=\"Verdana\" size=\"+1\">";
		echo $date;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$tmp_subtotal = condDecimalFormat( $tmp_subtotal);
		echo $tmp_subtotal;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$tmp_tax = condDecimalFormat( $tmp_tax);
		echo $tmp_tax;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$tmp_shipping = condDecimalFormat( $tmp_shipping);
		echo $tmp_shipping;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$tmp_discount = condDecimalFormat( $tmp_discount);
		echo $tmp_discount;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$tmp_grand = sprintf("%01.2f", $tmp_grand);
		echo $tmp_grand;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">";
		echo $tmp_complete;
		echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
		$avg_order = sprintf("%01.2f", $avg_order);
		echo $avg_order;
		echo "</font></td></tr>\n";
	}
	$i++;
	$tmp_subtotal = 0;
	$tmp_complete = 0;
	$tmp_tax = 0;
	$tmp_shipping = 0;
	$tmp_discount = 0;
	$tmp_grand = 0;
	$avg_order = 0;
}
mysql_free_result($result);
	echo "<tr><td><font face=\"Verdana\" size=\"+1\"><b>Totals</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$subtotal = condDecimalFormat( $subtotal);
	echo $subtotal;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$tax_total = condDecimalFormat( $tax_total);
	echo $tax_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$ship_total = condDecimalFormat( $ship_total);
	echo $ship_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$discount_total = condDecimalFormat( $discount_total);
	echo $discount_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$grand_total = sprintf("%01.2f", $grand_total);
	echo $grand_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>";
	echo $order_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$avg_total = sprintf("%01.2f", $avg_total);
	echo $avg_total;
	echo "</b></font></td></tr>";
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