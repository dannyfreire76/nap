<?php
// BME WMS
// Page: Reports Yearly Profit Analysis Sales page
// Path/File: /admin/reports_admin_r22.php
// Version: 1.8
// Build: 1803
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

$prod_cost_lvl = $_POST["prod_cost_lvl"];
if($prod_cost_lvl == "") { $prod_cost_lvl = 5; }

$supplies_skus = array('1011','1012','1013','1027','1020','3010','1021','1025','1026');

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Retail > Yearly Profit Analysis Report";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Yearly Retail Profit Analysis Sales page. This page reports the revenue, cost, and profit of all the Yearly Retail sales that have been transacted through the website.</font></td></tr>

<form action="./reports_admin_r22.php" method="POST">
<tr><td align="left"><font size="2">Product Cost Level: <select name="prod_cost_lvl" onChange="submit()">
<option value="1"<?php if($prod_cost_lvl == 1) { echo " SELECTED"; } ?>>Wholesale Level 1</option>
<option value="2"<?php if($prod_cost_lvl == 2) { echo " SELECTED"; } ?>>Wholesale Level 2</option>
<option value="3"<?php if($prod_cost_lvl == 3) { echo " SELECTED"; } ?>>Wholesale Level 3</option>
<option value="4"<?php if($prod_cost_lvl == 4) { echo " SELECTED"; } ?>>Distributor Level 1</option>
<option value="5"<?php if($prod_cost_lvl == 5) { echo " SELECTED"; } ?>>Distributor Level 2</option>
<option value="6"<?php if($prod_cost_lvl == 6) { echo " SELECTED"; } ?>>Distributor Level 3</option>
</select></font></td></tr>
</form>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" cellspacing="0">
<tr><th scope="col">Date</th><th scope="col">Units Sold</th><th scope="col">Revenue</th><th scope="col">Cost</th><th scope="col">Profit</th><th scope="col">Revenue per Unit</th><th scope="col">Cost per Unit</th><th scope="col">Profit per Unit</th><th scope="col">Profit Margin</th></tr>

<?php
$i = 1;
$subtotal = 0;
$tax_total = 0;
$ship_total = 0;
$grand_total = 0;
$order_total = 0;
$avg_total = 0;
$cost_total = 0;
$quantity_total = 0;
$profit_total = 0;
$tmp_quantity = 0;
$tmp_cost2 = 0;
$tmp_cost3 = 0;

for ( $query_year=2004; $query_year<=date("Y"); $query_year++  ) {
	$date_criteria = getDateCriteriaYrOnly($query_year);
	$date = $date_criteria["date"];
	$date2 = $date_criteria["date2"];

	$line_counter = 0;
	$query = "SELECT receipt_id, subtotal, complete, tax, shipping FROM receipts WHERE complete='1' $date2";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_subtotal = $tmp_subtotal + $line["subtotal"];
		$receipt_id = $line["receipt_id"];
		$query2 = "SELECT receipt_items.sku, receipt_items.quantity, product_skus.wholesale_cost1, product_skus.wholesale_cost2, product_skus.wholesale_cost3, product_skus.dist_cost1, product_skus.dist_cost2, product_skus.dist_cost3";
		$query2 .= " FROM receipt_items, product_skus";
		$query2 .= " WHERE product_skus.sku=receipt_items.sku";
		$query2 .= " AND receipt_items.receipt_id='$receipt_id'";
		$supplies_skus_count = count($supplies_skus);
		for($j=0;$j<$supplies_skus_count;$j++) {
			$query2 .= " AND product_skus.sku!='".$supplies_skus[$j]."'";
		}
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			if($prod_cost_lvl == 1) {
				$tmp_cost = $line2["wholesale_cost1"];
			} elseif($prod_cost_lvl == 2) {
				$tmp_cost = $line2["wholesale_cost2"];
			} elseif($prod_cost_lvl == 3) {
				$tmp_cost = $line2["wholesale_cost3"];
			} elseif($prod_cost_lvl == 4) {
				$tmp_cost = $line2["dist_cost1"];
			} elseif($prod_cost_lvl == 5) {
				$tmp_cost = $line2["dist_cost2"];
			} elseif($prod_cost_lvl == 6) {
				$tmp_cost = $line2["dist_cost3"];
			}
			$tmp_quantity = $tmp_quantity + $line2["quantity"];
			$tmp_cost2 = $tmp_cost * $line2["quantity"];
			$tmp_cost3 = $tmp_cost3 + $tmp_cost2;
		}
		mysql_free_result($result2);
	}
	mysql_free_result($result);
	

	$line_counter++;
	$line_this = $line_counter / 2;
	$tmp_profit = $tmp_subtotal - $tmp_cost3;

	$subtotal = $subtotal + $tmp_subtotal;
	$quantity_total = $quantity_total + $tmp_quantity;
	$cost_total = $cost_total + $tmp_cost3;
	$profit_total = $profit_total + $tmp_profit;
	
	if($tmp_quantity != 0) {
		$tmp_revenue_per_unit = $tmp_subtotal / $tmp_quantity;
		$tmp_cost_per_unit = $tmp_cost3 / $tmp_quantity;
		$tmp_profit_per_unit = $tmp_profit / $tmp_quantity;
	} else {
		$tmp_revenue_per_unit = 0;
		$tmp_cost_per_unit = 0;
		$tmp_profit_per_unit = 0;
	}
	if($tmp_cost3 != 0) {
		$tmp_margin = (($tmp_subtotal / $tmp_cost3) - 1) * 100;
	} else {
		$tmp_margin = 0;
	}
	
	if($tmp_quantity != 0) {
		echo "<tr";
		if(is_int($line_this)) { echo " class=\"d\""; }
		echo "><td align=\"center\">";
		echo $date;
		echo "</td><td align=\"center\">";
		echo $tmp_quantity;
		echo "</td><td align=\"center\">$";
		$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
		echo $tmp_subtotal;
		echo "</td><td align=\"center\">$";
		$tmp_cost3 = sprintf("%01.2f", $tmp_cost3);
		echo $tmp_cost3;
		echo "</td><td align=\"center\">$";
		$tmp_profit = sprintf("%01.2f", $tmp_profit);
		echo $tmp_profit;
		echo "</td><td align=\"center\">$";
		$tmp_revenue_per_unit = sprintf("%01.2f", $tmp_revenue_per_unit);
		echo $tmp_revenue_per_unit;
		echo "</td><td align=\"center\">$";
		$tmp_cost_per_unit = sprintf("%01.2f", $tmp_cost_per_unit);
		echo $tmp_cost_per_unit;
		echo "</td><td align=\"center\">$";
		$tmp_profit_per_unit = sprintf("%01.2f", $tmp_profit_per_unit);
		echo $tmp_profit_per_unit;
		echo "</td><td align=\"center\">";
		$tmp_margin = sprintf("%01.2f", $tmp_margin);
		echo $tmp_margin;
		echo "%</td></tr>\n";
	}
	$i++;
	$tmp_subtotal = 0;
	$tmp_quantity = 0;
	$tmp_cost3 = 0;
	$tmp_profit = 0;
}
	if($quantity_total != 0) {
		$revenue_per_unit = $subtotal / $quantity_total;
		$cost_per_unit = $cost_total / $quantity_total;
		$profit_per_unit = $profit_total / $quantity_total;
	} else {
		$revenue_per_unit = 0;
		$cost_per_unit = 0;
		$profit_per_unit = 0;
	}
	if($cost_total != 0) {
		$margin = (($subtotal / $cost_total) - 1) * 100;
	} else {
		$margin = 0;
	}

	echo "<tr><th scope=\"col\">Totals</th><th align=\"center\" scope=\"col\">";
	echo $quantity_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$subtotal = sprintf("%01.2f", $subtotal);
	echo $subtotal;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$cost_total = sprintf("%01.2f", $cost_total);
	echo $cost_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$profit_total = sprintf("%01.2f", $profit_total);
	echo $profit_total;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$revenue_per_unit = sprintf("%01.2f", $revenue_per_unit);
	echo $revenue_per_unit;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$cost_per_unit = sprintf("%01.2f", $cost_per_unit);
	echo $cost_per_unit;
	echo "</th><th align=\"center\" scope=\"col\">$";
	$profit_per_unit = sprintf("%01.2f", $profit_per_unit);
	echo $profit_per_unit;
	echo "</th><th align=\"center\" scope=\"col\">";
	$margin = sprintf("%01.2f", $margin);
	echo $margin;
	echo "%</th></tr>";
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