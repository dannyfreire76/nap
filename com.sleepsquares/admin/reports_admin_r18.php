<?php
// BME WMS
// Page: Reports Monthly Sales page
// Path/File: /admin/reports_admin_r18.php
// Version: 1.8
// Build: 1804
// Date: 05-14-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once("./includes/date_functions.php");

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$query = "SELECT state_tax FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$state_tax = $line["state_tax"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Retail > Monthly (Just ".$state_tax.") Sales Report";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Monthly (Just <?php echo $state_tax; ?>) Retail Sales Report. This page reports all the Monthly Retail sales for orders shipping to <?php echo $state_tax; ?> that have been transacted through the website.</font></td></tr>
</form>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" cellspacing="0">
<tr><th scope="col">Date</th><th scope="col">Sub-Total</th><th scope="col">Tax</th><th scope="col">Shipping</th><th scope="col">Grand Total</th><th scope="col">Orders</th><th scope="col">Average Order</th></tr>

<?php
$i = 1;
$subtotal = 0;
$tax_total = 0;
$ship_total = 0;
$grand_total = 0;
$order_total = 0;
$avg_total = 0;

for ( $query_year=2004; $query_year<=date("Y"); $query_year++  ) {
	for ( $query_month=1; $query_month < 13; $query_month++  ) {

		$date_criteria = getDateCriteria($query_month, $query_year);
		$date = $date_criteria["date"];
		$date2 = $date_criteria["date2"];

		$line_counter = 0;
		$query = "SELECT subtotal, complete, tax, shipping FROM receipts WHERE complete='1' AND bill_state='$state_tax' $date2";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$line_counter++;
			$line_this = $line_counter / 2;
			$tmp_subtotal = $tmp_subtotal + $line["subtotal"];
			$tmp_complete = $tmp_complete + $line["complete"];
			$tmp_tax = $tmp_tax + $line["tax"];
			$tmp_shipping = $tmp_shipping + $line["shipping"];
		}

		$tmp_grand = $tmp_subtotal + $tmp_tax + $tmp_shipping;

		$subtotal = $subtotal + $tmp_subtotal;
		$tax_total = $tax_total + $tmp_tax;
		$ship_total = $ship_total + $tmp_shipping;
		$grand_total = $grand_total + $tmp_grand;
		$order_total = $order_total + $tmp_complete;

		if($order_total != 0 || $order_total != "") { 
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
			echo "<tr";
			if(is_int($line_this)) { echo " class=\"d\""; }
			echo "><td align=\"center\">";
			echo $date;
			echo "</td><td align=\"center\">$";
			$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
			echo $tmp_subtotal;
			echo "</td><td align=\"center\">$";
			$tmp_tax = sprintf("%01.2f", $tmp_tax);
			echo $tmp_tax;
			echo "</td><td align=\"center\">$";
			$tmp_shipping = sprintf("%01.2f", $tmp_shipping);
			echo $tmp_shipping;
			echo "</td><td align=\"center\">$";
			$tmp_grand = sprintf("%01.2f", $tmp_grand);
			echo $tmp_grand;
			echo "</td><td align=\"center\">";
			echo $tmp_complete;
			echo "</td><td align=\"center\">$";
			$avg_order = sprintf("%01.2f", $avg_order);
			echo $avg_order;
			echo "</td></tr>\n";
		}
		$i++;
		$tmp_subtotal = 0;
		$tmp_complete = 0;
		$tmp_tax = 0;
		$tmp_shipping = 0;
		$tmp_grand = 0;
		$avg_order = 0;
	}	
}
mysql_free_result($result);
	echo "<tr><th align=\"center\" scope=\"col\">Totals</th><th align=\"center\" scope=\"col\">$";
	$subtotal = sprintf("%01.2f", $subtotal);
	echo $subtotal;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$tax_total = sprintf("%01.2f", $tax_total);
	echo $tax_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$ship_total = sprintf("%01.2f", $ship_total);
	echo $ship_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$grand_total = sprintf("%01.2f", $grand_total);
	echo $grand_total;
	echo "</th><th align=\"center\" scope=\"col\">";
	echo $order_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$avg_total = sprintf("%01.2f", $avg_total);
	echo $avg_total;
	echo "</th></tr>";
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