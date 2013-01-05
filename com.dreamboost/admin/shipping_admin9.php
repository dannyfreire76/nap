<?php
// BME WMS
// Page: Shipping Recently Shipped Retail Orders page
// Path/File: /admin/shipping_admin9.php
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

$this_month = date("m");
$this_year = date("Y");
$month_arry[1] = "January";
$month_arry[2] = "February";
$month_arry[3] = "March";
$month_arry[4] = "April";
$month_arry[5] = "May";
$month_arry[6] = "June";
$month_arry[7] = "July";
$month_arry[8] = "August";
$month_arry[9] = "September";
$month_arry[10] = "October";
$month_arry[11] = "November";
$month_arry[12] = "December";

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Recently Shipped Retail Orders";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$shipped_orders = $_POST["shipped_orders"];
if($shipped_orders != "") {
	list($selected_year, $selected_month) = explode("-", $shipped_orders);
} else {
	$selected_year = $this_year;
	$selected_month = $this_month;
}

if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
	$main_table = "wholesale_receipt";
	$receipt_id = $_REQUEST["wholesale_receipt_id"];
	$receipt_item_id = $_REQUEST["wholesale_receipt_item_id"];
} else {
	$main_table = "receipt";
	$receipt_id = $_REQUEST["receipt_id"];
	$receipt_item_id = $_REQUEST["receipt_item_id"];
}



if ( $_REQUEST["save"] ) {
	$batch_selected = $_REQUEST["batch_selected"];
	$tracking_num = $_REQUEST["hiddenTrackingNum"];

	$query = "UPDATE ".$main_table."_items SET tracking_num='$tracking_num', prod_batch_id='$batch_selected' WHERE ".$main_table."_item_id='$receipt_item_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
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

<tr><td>&nbsp;</td></tr>

<tr><FORM name="shipping" Method="POST" ACTION="" class="wmsform"><td align="left"><font size="2">Display Shipped Orders For <select id="shipped_orders" name="shipped_orders" onChange="submit()">
<?php
	for($i=0;$i<5;$i++) {
		for($j=0;$j<12;$j++) {
			$tmp_year = $this_year - $i;
			$tmp_month = $this_month - $j;
			if($tmp_month < 1) { $tmp_month = $tmp_month + 12; $tmp_year = $tmp_year - 1; }
			if($tmp_month < 10) { $tmp_month2 = "0" . $tmp_month; } else { $tmp_month2 = $tmp_month; }
			echo "<option value=\"";
			echo $tmp_year;
			echo "-";
			echo $tmp_month2;
			echo "\"";
			if(($tmp_month == $selected_month) && ($tmp_year == $selected_year)) { echo " SELECTED"; }
			echo ">";
			echo $month_arry[$tmp_month];
			echo " ";
			echo $tmp_year;
			echo "</option>\n";
		}
	}
?>
</select></font></td></form></tr>

<tr><td align="left">
<table class="maintable" width="100%" cellspacing="0">
	<tr>
		<th scope="col">Order Number</th>
		<th scope="col">Customer Name</th>
		<th scope="col">Ordered</th>
		<th scope="col">Shipped</th>
		<th scope="col">Order Total</th>
		<th scope="col">Shipper</th>
		<th scope="col">Tracking Number</th>
		<th scope="col">Batch</th>
		<th></th>
	</tr>
<?php
$line_counter = 0;
$totalCnt = 1;
$query = "SELECT DISTINCT ".$main_table."_items.*, ".$main_table."s.* FROM ".$main_table."s, ".$main_table."_items WHERE ".$main_table."s.".$main_table."_id=".$main_table."_items.".$main_table."_id AND ".$main_table."s.complete='1' AND ".$main_table."s.shipped='1' AND ".$main_table."s.ordered >= '$selected_year-$selected_month-01 00:00:00' AND ".$main_table."s.ordered <= '$selected_year-$selected_month-31 23:59:59' ORDER BY  ".$main_table."s.ordered, ".$main_table."s.".$main_table."_id";

$allReceipts = array();
$allReceiptsTracking = array();

$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

	if ( !in_array( $line[$main_table."_id"], $allReceipts ) ) {//just get the first item in this receipt
		$totalCnt++;

		if ($totalCnt % 40 == 0) {
			echo '
					<tr>
						<th scope="col">Order Number</th>
						<th scope="col">Customer Name</th>
						<th scope="col">Ordered</th>
						<th scope="col">Shipped</th>
						<th scope="col">Order Total</th>
						<th scope="col">Shipper</th>
						<th scope="col">Tracking Number</th>
						<th scope="col">Batch</th>
						<th></th>
					</tr>
			';
		}

		$allReceipts[ $line[$main_table."_id"] ] = $line[$main_table."_id"];
		$allReceiptsTracking[ $line[$main_table."_id"].$line["tracking_num"] ] = $line[$main_table."_id"].$line["tracking_num"];

		$line_counter++;
		$line_this = $line_counter / 2;
		echo "<tr";
		if(is_int($line_this)) { echo " class=\"d\""; }
		echo "><td>";
		if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
			echo $line["wholesale_order_number"];
		} else {
			echo $line["user_id"];
		}
		echo "</td><td>";
		if($line["cc_first_name"] != "" || $line["cc_last_name"] != "") {
			echo stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]);
		} elseif($line["bill_name"] != "") {
			echo stripslashes($line["bill_name"]);
		} else {
			echo stripslashes($line["ship_name"]);
		}
		echo "</td><td NOWRAP>";

		$ordered = $line["ordered"];
		list($ordered_date, $ordered_time) = explode(" ", $ordered);
		echo $ordered_date;
		echo "</td><td NOWRAP>";

		$shipped_date = $line["shipped_date"];
		list($shipped_date_date, $shipped_date_time) = explode(" ", $shipped_date);
		echo $shipped_date_date;
		echo "</td><td>";
		echo "$";
		echo $line["total"];
		echo "</td>";
		echo "<td NOWRAP>";

					if($line["shipper"] == "FEDX") {
						echo "FedEx";
						echo "</td><td NOWRAP>";
						echo "<a href=\"http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=";
						echo $line["tracking_num"];
						echo "\" TARGET=\"_BLANK\">";
						echo $line["tracking_num"];
						echo "</a>";
					} elseif($line["shipper"] == "USPS") {
						echo "USPS";
						echo "</td><td NOWRAP>";
						echo "<form action=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do\" method=\"post\"  name=\"getTrackNum\" onSubmit=\"return getTrackNum_validator(this)\" target=\"_blank\">\n";
						echo "<INPUT TYPE=\"HIDDEN\" TABINDEX=\"5\" NAME=\"CAMEFROM\" VALUE=\"OK\">\n";
						echo "<input type=\"text\" id=\"Enter label number:\" size=\"25\" maxlength=\"34\" name=\"strOrigTrackNum\" value=\"";
						echo $line["tracking_num"];
						echo '" onChange="$(\'#hiddenTrackingNum_'.$line["receipt_item_id"].'\').val( $(this).val() )"> ';
						echo "<input TYPE=\"SUBMIT\" NAME=\'Go to Label/Receipt Number page\' VALUE=\"Track\">";
						echo "</form>\n";
					} elseif($line["shipper"] == "DHL") {
						echo "DHL";
						echo "</td><td NOWRAP>";
						echo "<a href=\"http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber=";
						echo $line["tracking_num"];
						echo "\" TARGET=\"_BLANK\">";
						echo $line["tracking_num"];
						echo "</a>";
					} elseif($line["shipper"] == "UPS") {
						echo "UPS";
						echo "</td><td NOWRAP>";
						echo "<FORM method=\"post\" action=\"http://wwwapps.ups.com/WebTracking/OnlineTool\" target=\"_blank\">\n";
						echo "<INPUT type=\"hidden\" name=\"UPS_HTML_License\" value=\"CBFDAE072B810C21\">\n";
						echo "<INPUT type=\"hidden\" name=\"UPS_HTML_Version\" value=\"3.0\">\n";
						echo "<INPUT type=\"hidden\" name=\"TypeOfInquiryNumber\" value=\"T\">\n";
						echo "<INPUT type=\"hidden\" name=\"IATA\" value=\"us\">\n";
						echo "<INPUT type=\"hidden\" name=\"Lang\" value=\"eng\">\n";
						echo "<INPUT type=\"text\" size=\"22\" name=\"InquiryNumber1\" value=\"";
						echo $line["tracking_num"];
						echo '" onChange="$(\'#hiddenTrackingNum_'.$line["receipt_item_id"].'\').val( $(this).val() )" /> ';
						echo "<input TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Track\">";
						echo "</form>\n";
					} else {
						echo "N/A";
						echo "</td><td NOWRAP>";
						echo $line["tracking_num"];
					}
		//8/19/2009:
		echo '<form name="shippingChgs" Method="POST" ACTION="" onSubmit="$(\'#hiddenShippedOrders_'.$line[$main_table."_item_id"].'\', this.form).val( $(\'#shipped_orders\', this.form).val() );">';
		echo '<input type="hidden" name="shipped_orders" id="hiddenShippedOrders_'.$line[$main_table."_item_id"].'" value="" />';
		echo '<input type="hidden" name="'.$main_table.'_item_id" id="'.$main_table.'_item_id" value="'.$line[$main_table."_item_id"].'" />';
		echo '<input type="hidden" name="hiddenTrackingNum" id="hiddenTrackingNum_'.$line[$main_table."_item_id"].'" value="'.$line["tracking_num"].'" /> ';
		echo "</td>";
		echo '<td>';
			$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE batch_active='1' ORDER BY batch_active DESC, batch_created ASC";
			$resultBatch = mysql_query($queryBatch) or die("Query failed : " . mysql_error());
			if ( mysql_num_rows($resultBatch) > 0 ) {
				echo '<select name="batch_selected" id="batch_selected">';
				echo '<option value=""></option>';
				while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
					$selectedB="";
					if ( $line["prod_batch_id"]==$lineBatch["prod_batch_id"] ) {
						$selectedB= " selected";
					}
					echo '<option value="'.$lineBatch["prod_batch_id"].'" '.$selectedB.'>'.$lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"].'</option>';
				}
				echo '</select>';
			} else {
				echo 'no batches available';
			}
		echo '</td>';
		echo '<td><input type="submit" value="Save Changes" name="save" id="save" /></td>';
		echo "</form></tr>\n";
	} else if ( !in_array($line[$main_table."_id"].$line["tracking_num"],$allReceiptsTracking) ) {//list individual item without the receipt-level info
		echo "<tr";
		if(is_int($line_this)) { echo " class=\"d\""; }
		echo ">";		
		echo '<td colspan="5">&#160;</td>';
		echo "<td NOWRAP>";

					if($line["shipper"] == "FEDX") {
						echo "FedEx";
						echo "</td><td NOWRAP>";
						echo "<a href=\"http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=";
						echo $line["tracking_num"];
						echo "\" TARGET=\"_BLANK\">";
						echo $line["tracking_num"];
						echo "</a>";
					} elseif($line["shipper"] == "USPS") {
						echo "USPS";
						echo "</td><td NOWRAP>";
						echo "<form action=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do\" method=\"post\"  name=\"getTrackNum\" onSubmit=\"return getTrackNum_validator(this)\" target=\"_blank\">\n";
						echo "<INPUT TYPE=\"HIDDEN\" TABINDEX=\"5\" NAME=\"CAMEFROM\" VALUE=\"OK\">\n";
						echo "<input type=\"text\" id=\"Enter label number:\" size=\"25\" maxlength=\"34\" name=\"strOrigTrackNum\" value=\"";
						echo $line["tracking_num"];
						echo '" onChange="$(\'#hiddenTrackingNum_'.$line["receipt_item_id"].'\').val( $(this).val() )"> ';
						echo "<input TYPE=\"SUBMIT\" NAME=\'Go to Label/Receipt Number page\' VALUE=\"Track\">";
						echo "</form>\n";
					} elseif($line["shipper"] == "DHL") {
						echo "DHL";
						echo "</td><td NOWRAP>";
						echo "<a href=\"http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber=";
						echo $line["tracking_num"];
						echo "\" TARGET=\"_BLANK\">";
						echo $line["tracking_num"];
						echo "</a>";
					} elseif($line["shipper"] == "UPS") {
						echo "UPS";
						echo "</td><td NOWRAP>";
						echo "<FORM method=\"post\" action=\"http://wwwapps.ups.com/WebTracking/OnlineTool\" target=\"_blank\">\n";
						echo "<INPUT type=\"hidden\" name=\"UPS_HTML_License\" value=\"CBFDAE072B810C21\">\n";
						echo "<INPUT type=\"hidden\" name=\"UPS_HTML_Version\" value=\"3.0\">\n";
						echo "<INPUT type=\"hidden\" name=\"TypeOfInquiryNumber\" value=\"T\">\n";
						echo "<INPUT type=\"hidden\" name=\"IATA\" value=\"us\">\n";
						echo "<INPUT type=\"hidden\" name=\"Lang\" value=\"eng\">\n";
						echo "<INPUT type=\"text\" size=\"22\" name=\"InquiryNumber1\" value=\"";
						echo $line["tracking_num"];
						echo '" onChange="$(\'#hiddenTrackingNum_'.$line["receipt_item_id"].'\').val( $(this).val() )" /> ';
						echo "<input TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Track\">";
						echo "</form>\n";
					} else {
						echo "N/A";
						echo "</td><td NOWRAP>";
						echo $line["tracking_num"];
					}
		//8/19/2009:
		echo '<form name="shippingChgs" Method="POST" ACTION="" onSubmit="$(\'#hiddenShippedOrders_'.$line[$main_table."_item_id"].'\', this.form).val( $(\'#shipped_orders\', this.form).val() );">';
		echo '<input type="hidden" name="shipped_orders" id="hiddenShippedOrders_'.$line[$main_table."_item_id"].'" value="" />';
		echo '<input type="hidden" name="'.$main_table.'_item_id" id="'.$main_table.'_item_id" value="'.$line[$main_table."_item_id"].'" />';
		echo '<input type="hidden" name="hiddenTrackingNum" id="hiddenTrackingNum_'.$line[$main_table."_item_id"].'" value="'.$line["tracking_num"].'" /> ';
		echo "</td>";
		echo '<td>';
			$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE batch_active='1' ORDER BY batch_active DESC, batch_created ASC";
			$resultBatch = mysql_query($queryBatch) or die("Query failed : " . mysql_error());
			if ( mysql_num_rows($resultBatch) > 0 ) {
				echo '<select name="batch_selected" id="batch_selected">';
				echo '<option value=""></option>';
				while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
					$selectedB="";
					if ( $line["prod_batch_id"]==$lineBatch["prod_batch_id"] ) {
						$selectedB= " selected";
					}
					echo '<option value="'.$lineBatch["prod_batch_id"].'" '.$selectedB.'>'.$lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"].'</option>';
				}
				echo '</select>';
			} else {
				echo 'no batches available';
			}
		echo '</td>';
		echo '<td><input type="submit" value="Save Changes" name="save" id="save" /></td>';
		echo "</form></tr>\n";
		echo '</tr>';	
	}
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