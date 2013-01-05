<?php
// BME WMS
// Page: Shipping FUTURE recurring retail orders Queue page
// File: /admin/shipping.recurring.php (based on /admin/shipping_admin4.php)
// 6/8/2011

//die('...development in progress!');

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once("./includes/retailer1.php"); //TODO: Make this work for CONSUMER orders, namely function check_user($username, $retailer_id), function getOrderUserID($receipt_id), function send_email_login($email, $contact_name, $username, $password)
$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}
include './includes/wms_nav1.php';

// Uses retail receipt table only (at this time)
$main_table = "receipt";
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
<script type="text/javascript" src="../includes/_.jquery.js"></script>
<script type="text/javascript" src="../includes/_.date.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php
include './includes/head_admin3.php';
?>
<table border="0" width="97%">
<tr><td>&nbsp;</td></tr>
<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Order Number</th><th scope="col">Date Ordered</th><th scope="col">Customer Name</th><th scope="col">Payment Type</th><th scope="col">Order Total</th><th scope="col">&nbsp;</th></tr>
<?php
/*
Build an array of unshipped orders.  To qualify:
- Order is from host site
- Order is NOT from host site but the same order # (in the new NAP db system) also exists as a completed order from the host site, regardless of whether the items from the host site have shipped
*/
$unShippedOrders = array();
$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url!='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
	$thisDBHName = "dbh".$lineSites["site_key_name"];
	$query = "SELECT * FROM ".$main_table."s WHERE complete='1' AND shipped='0' ";
	// added for inactive orders on the orders screens
	$query .= " AND active = '1'";
	$query .= " ORDER BY ordered";
	$result = mysql_query($query, $$thisDBHName) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$includeMe="";
		$includeDate = 0;
		if ( $lineSites['site_url']==$_SERVER["HTTP_HOST"] ) {
			$includeMe = $line["".$main_table."_id"];
			$includeDate = $line['ordered'];
		} else {
			if ( $main_table=="receipt" ) {
				$orderUID = getOrderUserID( $line["".$main_table."_id"], $$thisDBHName );
				if ( $orderUID ) {//found unshipped order in a partner site, so query HOST site for same order number.  if there, include it
					$querySiteRct = "SELECT receipt_id FROM ".$main_table."s WHERE complete='1' AND user_id='$orderUID' AND user_id > ".$min_global_user_id;
					$resultSiteRct = mysql_query($querySiteRct) or die("Query failed: " . mysql_error());
					if ( mysql_num_rows($resultSiteRct) > 0 ) {
						while ($lineSiteRct = mysql_fetch_array($resultSiteRct, MYSQL_ASSOC)) {
							if ( $_REQUEST['debug'] ) {
								echo $lineSiteRct['receipt_id'].' :: '.$lineSites["site_key_name"].'<br />';
								foreach($line as $col=>$val) {
									echo $col.': '.$val.'<br />';
								}
							}
							$includeMe = $lineSiteRct['receipt_id'];
							$includeDate = $lineSiteRct['ordered'];
						}
					}
				}
			}
		}
		if ( $includeMe!="" && !array_key_exists($includeMe, $unShippedOrders) ) {
			$unShippedOrders[$includeMe] = $includeDate;
		}
	}
}
asort($unShippedOrders, SORT_NUMERIC);
$line_counter = 0;
foreach($unShippedOrders as $this_receipt_id=>$ordered) {
  $query = "SELECT * FROM ".$main_table."s WHERE ".$main_table."_id='".$this_receipt_id."'";
  $result = mysql_query($query) or die("Query failed : " . mysql_error());
  while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo '<FORM name="shipping" Method="POST" ACTION="';
	echo './shipping_admin6.php';
	echo '" class="wmsform">';
	echo "<tr";
	$rowClass="";
	if (is_int($line_this)) {
		$rowClass .= "d ";
	}
	if($line["back_ordered"] == "1") {
		$rowClass .= " error";
	}
	echo ' class="'.$rowClass.'"';
	echo "><td>";
	// changed for retail-only
	echo $line["user_id"];
	echo "</td><td>";
	echo $line["ordered"];
	echo "</td><td>";
	if($line["cc_first_name"] != "" || $line["cc_last_name"] != "") {
		echo stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]);
	} elseif($line["bill_name"] != "") {
		echo stripslashes($line["bill_name"]);
	} else {
		echo stripslashes($line["ship_name"]);
	}
	echo "</td><td>";
	if($line["pay_type"] == "mo") { echo "Money Order"; }
	if($line["pay_type"] == "cc") { echo "Credit Card"; }
	if($line["pay_type"] == "chk") { echo "Check"; }
	if($line["pay_type"] == "csh") { echo "Cash"; }
	if($line["pay_type"] == "cod") { echo "COD"; }
	if($line["pay_type"] == "scd") { echo "Secure COD"; }
	if($line["pay_type"] == "ona") { echo "On Account"; }
	if($line["pay_type"] == "n15") { echo "Net 15"; }
	if($line["pay_type"] == "n30") { echo "Net 30"; }
	if($line["pay_type"] == "n60") { echo "Net 60"; }
	if($line["pay_type"] == "n90") { echo "Net 90"; }
	$relatedRcts = array();
	if ( $main_table=="receipt" ) {
		$querySites = "SELECT * FROM partner_sites WHERE LOWER(site_url) != '".strtolower( $website_title )."'";
		$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
		while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
			$thisDBHName = "dbh".$lineSites["site_key_name"];
			$queryOtherRec = "SELECT * FROM ".$main_table."s WHERE complete='1' AND ";
			$queryOtherRec .= " user_id= '".$line["user_id"]."'";
			$resultOtherRec = mysql_query($queryOtherRec, $$thisDBHName) or die("Query failed: " . mysql_error());
			if ( mysql_num_rows($resultOtherRec)>0 ) {
				echo '<img src="/favicon.ico" align="absmiddle" style="float:right; padding-bottom: 4px;" />';
				while ($lineOtherRec = mysql_fetch_array($resultOtherRec, MYSQL_ASSOC)) {
					$relatedRcts[ $lineSites["site_url"] ] = $lineOtherRec;
				}
			}
		}
	}
	echo "</td><td>";
	echo "$";
	echo $line["total"];
	echo "</td><input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
	echo $line["".$main_table."_id"];
	echo "\"><td><input type=\"submit\" value=\"Ship\"></td></tr>\n";
	echo "</form>\n";
	foreach( $relatedRcts as $site=>$lineArr ) {
		echo '<tr class="'.$rowClass.'">';
		echo	'<td>&#160;</td>';
		echo	'<td>&#160;</td>';
		echo	'<td>&#160;</td>';
		echo	'<td>';
		echo		'<img src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$site.'/favicon.ico" align="absmiddle" style="float:right; padding-bottom: 4px;" />';
		echo	'</td>';
		echo	'<td>';
		echo		$lineArr["total"];
		echo	'</td>';
		echo	'<td>&#160;</td>';
		echo '</tr>';
	}
  }
}
mysql_free_result($result);
?>
</table></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
//mysql_close($dbh);
?>
</div>
</body>
</html>
