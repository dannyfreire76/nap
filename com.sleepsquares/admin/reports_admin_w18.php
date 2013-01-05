<?php
// BME WMS
// Page: Reports Manager Wholesale By User Processing Order Sales page
// Path/File: /admin/reports_admin_w18.php
// Version: 1.8
// Build: 1804
// Date: 05-14-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$date = $_POST["date"];

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Wholesale > By Processor Sales Report";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "TRUNCATE TABLE order_processed_by_person_temp";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

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


<tr><td align="left"><font size="2">Welcome to the Reports Manager Wholesale By User Processing Order Sales Report. This page reports all the Wholesale By User Processing Order sales that have been transacted through the Retailer Manager. To breakdown this report by month please use this select menu.</font></td></tr>

<form action="./reports_admin_w18.php" method="POST">
<tr><td align="left"><font size="2">Month: <SELECT name="date" onChange="submit()">
<option value="">All</option>
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

<tr><td align="left"><table class="maintable" cellspacing="0">
<tr><th scope="col">Order Processed By</th><th scope="col">Amount</th><th scope="col">Orders</th></tr>

<?php
$query = "SELECT order_processed_by_person, subtotal FROM wholesale_receipts WHERE complete='1' $date2 ORDER BY order_processed_by_person";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$order_processed_by_person1 = $line["order_processed_by_person"];
	$subtotal = $line["subtotal"];
	$query2 = "SELECT order_processed_by_person_id, order_processed_by_person, amount, orders FROM order_processed_by_person_temp WHERE order_processed_by_person='$order_processed_by_person1'";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	$order_processed_by_person = "";
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$order_processed_by_person_id = $line2["order_processed_by_person_id"];
		$order_processed_by_person = $line2["order_processed_by_person"];
		$amount = $line2["amount"];
		$orders = $line2["orders"];
		$amount = $amount + $subtotal;
		$orders = $orders + 1;
		$query3 = "UPDATE order_processed_by_person_temp SET order_processed_by_person='$order_processed_by_person', amount='$amount', orders='$orders' WHERE order_processed_by_person_id='$order_processed_by_person_id'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
	}
	mysql_free_result($result2);
	
	if($order_processed_by_person == "") {
		$now = date("Y-m-d H:i:s");
		$query4 = "INSERT INTO order_processed_by_person_temp SET created='$now', order_processed_by_person='$order_processed_by_person1', amount='$subtotal', orders='1'";
		$result4 = mysql_query($query4) or die("Query failed : " . mysql_error());
	}
}
mysql_free_result($result);


$line_counter = 0;
$query5 = "SELECT order_processed_by_person, amount, orders FROM order_processed_by_person_temp ORDER BY order_processed_by_person";
$result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {

	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td align=\"center\">";
	$query6 = "SELECT first_name, last_name FROM wms_users WHERE user_id='".$line5["order_processed_by_person"]."'";
	$result6 = mysql_query($query6) or die("Query failed : " . mysql_error());
	while ($line6 = mysql_fetch_array($result6, MYSQL_ASSOC)) {
		echo $line6["first_name"];
		echo " ";
		echo $line6["last_name"];
	}
	mysql_free_result($result6);
	echo "</td><td align=\"center\">$";
	echo $line5["amount"];
	echo "</td><td align=\"center\">";
	echo $line5["orders"];
	echo "</td></tr>\n";
}
mysql_free_result($result5);
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