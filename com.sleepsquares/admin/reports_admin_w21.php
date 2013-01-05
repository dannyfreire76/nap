<?php
// BME WMS
// Page: Reports Monthly Profit Analysis SKU Breakdown Sales page
// Path/File: /admin/reports_admin_w21.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once($base_path."includes/wc1.php");

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$prod_cost_lvl = $_POST["prod_cost_lvl"];
if($prod_cost_lvl == "") { $prod_cost_lvl = 5; }
if($_GET["date"] != "") {
	$date = $_GET["date"];
} else {
	$date = $_POST["date"];
}
if($date == "") { $date = date("m/Y"); }

$supplies_skus = array('1011','1012','1013','1027','1020','3010','1021','1025','1026');

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Wholesale > Monthly Profit Analysis By SKU Report";
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

<tr><td align="left"><font size="2">Welcome to the Reports Manager Monthly Wholesale Profit Analysis SKU Breakdown Sales Report. This page reports the revenue, cost, and profit of all the Monthly Wholesale sales broken down by SKU that have been transacted through the website.</font></td></tr>
<form action="./reports_admin_w21.php" method="POST">
<tr><td align="left"><font size="2">Product Cost Level: <select name="prod_cost_lvl" onChange="submit()">
<option value="1"<?php if($prod_cost_lvl == 1) { echo " SELECTED"; } ?>>Wholesale Level 1</option>
<option value="2"<?php if($prod_cost_lvl == 2) { echo " SELECTED"; } ?>>Wholesale Level 2</option>
<option value="3"<?php if($prod_cost_lvl == 3) { echo " SELECTED"; } ?>>Wholesale Level 3</option>
<option value="4"<?php if($prod_cost_lvl == 4) { echo " SELECTED"; } ?>>Distributor Level 1</option>
<option value="5"<?php if($prod_cost_lvl == 5) { echo " SELECTED"; } ?>>Distributor Level 2</option>
<option value="6"<?php if($prod_cost_lvl == 6) { echo " SELECTED"; } ?>>Distributor Level 3</option>
</select></font></td></tr>
<tr><td align="left"><font size="2">Month: <select name="date" onChange="submit()">
<?php
    for ( $y=2004; $y<=date("Y"); $y++  ) {
        for ( $m=1; $m < 13; $m++  ) {
            echo '<option value="'.$m.'|'.$y.'"';
            if ( $date==$m.'|'.$y ) {
                echo ' selected';
            }
            echo '>'.sprintf("%02d", $m).'/'.$y.'</option>';
        }
    }
?>
</select></font></td></tr>


</form>
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0" cellspacing="4" cellpadding="2">
<tr><td><font face="Verdana" size="+1"><b>SKU </b></font></td><td><font face="Verdana" size="+1"><b>Units Sold </b></font></td><td><font face="Verdana" size="+1"><b>Revenue </b></font></td><td><font face="Verdana" size="+1"><b>Cost </b></font></td><td><font face="Verdana" size="+1"><b>Profit </b></font></td><td><font face="Verdana" size="+1"><b>Revenue per Unit</b></font></td><td><font face="Verdana" size="+1"><b>Cost per Unit</b></font></td><td><font face="Verdana" size="+1"><b>Profit per Unit</b></font></td><td><font face="Verdana" size="+1"><b>Profit Margin</b></font></td></tr>

<?php
$subtotal = 0;
$tax_total = 0;
$ship_total = 0;
$discount_total = 0;
$grand_total = 0;
$order_total = 0;
$avg_total = 0;
$cost_total = 0;
$quantity_total = 0;
$profit_total = 0;
$tmp_cost2 = 0;

    $date2 = "";
    if($date != "") {
        $monthyear = split("\|", $date);
        $query_month = sprintf( "%02d", $monthyear[0] );
        $query_year = $monthyear[1];

        if ($query_month==12) {
            $query_month_max = "01";
            $query_year_max = $monthyear[1] + 1;
        }
        else {
            $query_month_max = sprintf( "%02d", ($monthyear[0] + 1) );
            $query_year_max = $monthyear[1];
        }

        $date2 = "AND ordered >= '".$query_year."-".$query_month."-01 00:00:00' AND ordered <= '".$query_year_max."-".$query_month_max."-01 00:00:00'";
    }

	$query = "SELECT wholesale_receipt_id, item_count, subtotal, complete, tax, shipping, discount FROM wholesale_receipts WHERE complete='1' $date2";
    echo $query;
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_item_count = $line["item_count"];
		$tmp_discount = $line["discount"];
		if($tmp_item_count != "" && $tmp_item_count != 0) {
			$tmp_discount_per_unit = $tmp_discount / $tmp_item_count;
		} else {
			$tmp_discount_per_unit = 0;
		}
		$wholesale_receipt_id = $line["wholesale_receipt_id"];
		$query2 = "SELECT wholesale_receipt_items.sku, wholesale_receipt_items.quantity, wholesale_receipt_items.price, product_skus.wholesale_cost1, product_skus.wholesale_cost2, product_skus.wholesale_cost3, product_skus.dist_cost1, product_skus.dist_cost2, product_skus.dist_cost3";
		$query2 .= " FROM wholesale_receipt_items, product_skus";
		$query2 .= " WHERE product_skus.sku=wholesale_receipt_items.sku";
		$query2 .= " AND wholesale_receipt_items.wholesale_receipt_id='$wholesale_receipt_id'";
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
			
			$sku_array[] = $line2["sku"];
			$tmp_sku = $line2["sku"];
			$tmp_price = $line2["price"] * $line2["quantity"];
			$tmp_discount2 = $tmp_discount_per_unit * $line2["quantity"];
			$tmp_price = $tmp_price - $tmp_discount2;
			
			if($tmp_subtotal["$tmp_sku"] != "") {
				$tmp_subtotal["$tmp_sku"] = $tmp_subtotal["$tmp_sku"] + $tmp_price;
			} else {
				$tmp_subtotal["$tmp_sku"] = 0;
				$tmp_subtotal["$tmp_sku"] = $tmp_subtotal["$tmp_sku"] + $tmp_price;
			}
			if($tmp_quantity["$tmp_sku"] != "") {
				$tmp_quantity["$tmp_sku"] = $tmp_quantity["$tmp_sku"] + $line2["quantity"];
			} else {
				$tmp_quantity["$tmp_sku"] = 0;
				$tmp_quantity["$tmp_sku"] = $tmp_quantity[$tmp_sku] + $line2["quantity"];
			}
			$tmp_cost2 = $tmp_cost * $line2["quantity"];
			if($tmp_cost3["$tmp_sku"] != "") {
				$tmp_cost3["$tmp_sku"] = $tmp_cost3["$tmp_sku"] + $tmp_cost2;
			} else {
				$tmp_cost3["$tmp_sku"] = 0;
				$tmp_cost3["$tmp_sku"] = $tmp_cost3["$tmp_sku"] + $tmp_cost2;
			}
		}
		mysql_free_result($result2);
	}
	mysql_free_result($result);
	
	$sku_array_count = count($sku_array);
	if($sku_array_count != 0) {
		sort($sku_array);
	}
	for($i=0;$i<$sku_array_count;$i++) {
		if($i == 0) {
			$last_item = $sku_array[$i];
			$sku_array2[] = $sku_array[$i];
		} else {
			if($last_item != $sku_array[$i]) {
				$last_item = $sku_array[$i];
				$sku_array2[] = $sku_array[$i];
			}
		}
	}
	
	$sku_array2_count = count($sku_array2);
	for($i=0;$i<$sku_array2_count;$i++) {
		$tmp_profit[$sku_array2[$i]] = $tmp_subtotal[$sku_array2[$i]] - $tmp_cost3[$sku_array2[$i]];
		$subtotal = $subtotal + $tmp_subtotal[$sku_array2[$i]];
		$discount_total = $discount_total + $tmp_discount[$sku_array2[$i]];
		$quantity_total = $quantity_total + $tmp_quantity[$sku_array2[$i]];
		$cost_total = $cost_total + $tmp_cost3[$sku_array2[$i]];
		$profit_total = $profit_total + $tmp_profit[$sku_array2[$i]];
		
		if($tmp_quantity[$sku_array2[$i]] != 0) {
			$tmp_revenue_per_unit[$sku_array2[$i]] = $tmp_subtotal[$sku_array2[$i]] / $tmp_quantity[$sku_array2[$i]];
			$tmp_cost_per_unit[$sku_array2[$i]] = $tmp_cost3[$sku_array2[$i]] / $tmp_quantity[$sku_array2[$i]];
			$tmp_profit_per_unit[$sku_array2[$i]] = $tmp_profit[$sku_array2[$i]] / $tmp_quantity[$sku_array2[$i]];
		} else {
			$tmp_revenue_per_unit[$sku_array2[$i]] = 0;
			$tmp_cost_per_unit[$sku_array2[$i]] = 0;
			$tmp_profit_per_unit[$sku_array2[$i]] = 0;
		}
		if($tmp_cost3[$sku_array2[$i]] != 0) {
			$tmp_margin[$sku_array2[$i]] = (($tmp_subtotal[$sku_array2[$i]] / $tmp_cost3[$sku_array2[$i]]) - 1) * 100;
		} else {
			$tmp_margin[$sku_array2[$i]] = 0;
		}
		
		if($tmp_quantity[$sku_array2[$i]] != 0) {
			echo "<tr><td><font face=\"Verdana\" size=\"+1\">";
			echo $sku_array2[$i];
			echo "</font></td><td align=\"center\"><font face=\"Verdana\" size=\"+1\">";
			echo $tmp_quantity[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_subtotal[$sku_array2[$i]] = condDecimalFormat( $tmp_subtotal[$sku_array2[$i]]);
			echo $tmp_subtotal[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_cost3[$sku_array2[$i]] = condDecimalFormat( $tmp_cost3[$sku_array2[$i]]);
			echo $tmp_cost3[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_profit[$sku_array2[$i]] = condDecimalFormat( $tmp_profit[$sku_array2[$i]]);
			echo $tmp_profit[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_revenue_per_unit[$sku_array2[$i]] = condDecimalFormat( $tmp_revenue_per_unit[$sku_array2[$i]]);
			echo $tmp_revenue_per_unit[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_cost_per_unit[$sku_array2[$i]] = condDecimalFormat( $tmp_cost_per_unit[$sku_array2[$i]]);
			echo $tmp_cost_per_unit[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">$";
			$tmp_profit_per_unit[$sku_array2[$i]] = condDecimalFormat( $tmp_profit_per_unit[$sku_array2[$i]]);
			echo $tmp_profit_per_unit[$sku_array2[$i]];
			echo "</font></td><td><font face=\"Verdana\" size=\"+1\">";
			$tmp_margin[$sku_array2[$i]] = condDecimalFormat( $tmp_margin[$sku_array2[$i]]);
			echo $tmp_margin[$sku_array2[$i]];
			echo "%</font></td></tr>\n";
		}
	}


	//$subtotal = $subtotal - $discount_total;
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

	echo "<tr><td><font face=\"Verdana\" size=\"+1\"><b>Totals</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>";
	echo $quantity_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$subtotal = condDecimalFormat( $subtotal);
	echo $subtotal;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$cost_total = condDecimalFormat( $cost_total);
	echo $cost_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$profit_total = condDecimalFormat( $profit_total);
	echo $profit_total;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$revenue_per_unit = condDecimalFormat( $revenue_per_unit);
	echo $revenue_per_unit;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$cost_per_unit = condDecimalFormat( $cost_per_unit);
	echo $cost_per_unit;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>$";
	$profit_per_unit = condDecimalFormat( $profit_per_unit);
	echo $profit_per_unit;
	echo "</b></font></td><td><font face=\"Verdana\" size=\"+1\"><b>";
	$margin = condDecimalFormat( $margin);
	echo $margin;
	echo "%</b></font></td></tr>";
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