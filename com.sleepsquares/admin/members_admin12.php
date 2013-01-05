<?php
// BME WMS
// Page: Members Manager Order History page
// Path/File: /admin/members_admin12.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

$member_id = $_REQUEST["member_id"];

$this_user_id = $_COOKIE["wms_user"];

	$query = "SELECT first_name, last_name FROM members WHERE member_id='$member_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$first_name = stripslashes($line["first_name"]);
		$last_name = stripslashes($line["last_name"]);
	}
	mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
</head>
<body>
<div align="center">

<table border="0" width="650">

<tr><td align="center"><font size="3">Members Order History for <?php echo $first_name . " " . $last_name; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
	$query = "SELECT receipt_id, user_id, ordered, shipped_date, pay_type, subtotal, shipping, total, shipped FROM receipts WHERE complete='1' AND member_id='$member_id' ORDER BY ordered";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$receipt_id = $line["receipt_id"];
		$ordered = $line["ordered"];
		list($ordered_date, $junk) = split(' ', $ordered);
		list($ordered_yr, $ordered_mn, $ordered_dy) = split('-', $ordered_date);
		echo "<tr><td align=\"left\"><font size=\"2\">";
        echo '<div style="float:left">';
        echo "Order Number: ";
		echo $line["user_id"];
		echo " on ";
		echo $ordered_mn . "/" . $ordered_dy . "/" . $ordered_yr;
        echo "</div>";

        echo '<div style="float:left"><form method="post" action="./shipping_admin3.php"><input type="hidden" name="receipt_id" value="'.$receipt_id.'" />';
        echo '<input type="hidden" name="show_only" id="show_only" value="1" />';
        echo '&#160;&#160;<input type="submit" value="view invoice" /></form></div>';
        

		echo "</font></td></tr>\n";
		$query2 = "SELECT quantity, price, name, shipper, tracking_num FROM receipt_items WHERE receipt_id='$receipt_id'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo "<tr><td align=\"left\"><font size=\"2\">";
			echo $line2["quantity"];
			echo " of ";
			echo $line2["name"];
			echo " for $";
			echo $line2["price"];
			echo " each.</font></td></tr>\n";
			unset($tracking_num, $tracking_num2, $shipper, $shipper2);
			$tracking_num[] = $line2["tracking_num"];
			$shipper[] = $line2["shipper"];
		}
		mysql_free_result($result2);
		
		echo "<tr><td align=\"left\"><font size=\"2\">Payment Type: ";
		echo displayPayType($line["pay_type"]);
		echo " Subtotal: $";
		echo $line["subtotal"];
		echo " Shipping: $";
		echo $line["shipping"];
		echo " Total: $";
		echo $line["total"];
		echo "</font></td></tr>\n";
		
		echo "<tr><td align=\"left\"><font size=\"2\">Shipped: ";
		if($line["shipped"] == 1) { echo "Yes"; }
		else { echo "No"; }
		if($line["shipped_date"] != "0000-00-00 00:00:00" && $line["shipped_date"] != "0" && $line["shipped_date"] != "") {
			echo " on ";
			echo $line["shipped_date"];
		}
		echo "</font></td></tr>\n";
		
		$tracking_num_count = count($tracking_num);
		if($tracking_num_count > 0) {
			for($i=0; $i < $tracking_num_count; $i++) {
				if($i == 0) {
					$last_tracking_num = $tracking_num[$i];
					if($tracking_num[$i] != "") {
						$tracking_num2[] = $tracking_num[$i];
						$shipper2[] = $shipper[$i];
					}
				}
				else {
					$this_tracking_num = $tracking_num[$i];
					if($last_tracking_num != $this_tracking_num) {
						$tracking_num2[] = $tracking_num[$i];
						$shipper2[] = $shipper[$i];
						$last_tracking_num = $tracking_num[$i];
					}
				}
				
			}
		}
		unset($tracking_num);
		
		$tracking_num2_count = count($tracking_num2);		
		$tracking_num2_last_pos = $tracking_num2_count - 1;
		if($tracking_num2_count > 0) {
			for($i=0; $i < $tracking_num2_count; $i++) {
				if($shipper2[$i] == "FEDX") {
					echo "<tr><td align=\"left\"><font size=\"2\">Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "FedEx";
					echo "=";
					echo "<a href=\"http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=";
					echo $tracking_num2[$i];
					echo "\" TARGET=\"_BLANK\">";
					echo $tracking_num2[$i];
					echo "</a>";
					if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
						echo ", ";
					}
					echo "</font></td></tr>\n";
				} elseif($shipper2[$i] == "USPS") {
					echo "<form action=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do\" method=\"post\"  name=\"getTrackNum\" onSubmit=\"return getTrackNum_validator(this)\" target=\"_blank\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" TABINDEX=\"5\" NAME=\"CAMEFROM\" VALUE=\"OK\">\n";
					echo "<tr><td align=\"left\"><font size=\"2\">Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "USPS";
					echo "=";
					echo "<input type=\"text\" id=\"Enter label number:\" size=\"22\" maxlength=\"34\" name=\"strOrigTrackNum\" value=\"";
					echo $tracking_num2[$i];
					echo "\"> ";
					echo "<input TYPE=\"SUBMIT\" NAME=\'Go to Label/Receipt Number page\' VALUE=\"Track\">";
					if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
						echo ", ";
					}
					echo "</font></td></tr>\n";
					echo "</form>\n";
				} elseif($shipper2[$i] == "DHL") {
					echo "<tr><td align=\"left\"><font size=\"2\">Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "DHL";
					echo "=";
					echo "<a href=\"http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber=";
					echo $tracking_num2[$i];
					echo "\" TARGET=\"_BLANK\">";
					echo $tracking_num2[$i];
					echo "</a>";
					if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
						echo ", ";
					}
					echo "</font></td></tr>\n";
				} elseif($shipper2[$i] == "UPS") {
					echo "<FORM method=\"post\" action=\"http://wwwapps.ups.com/WebTracking/OnlineTool\" target=\"_blank\">\n";
					echo "<INPUT type=\"hidden\" name=\"UPS_HTML_License\" value=\"CBFDAE072B810C21\">\n";
					echo "<INPUT type=\"hidden\" name=\"UPS_HTML_Version\" value=\"3.0\">\n";
					echo "<INPUT type=\"hidden\" name=\"TypeOfInquiryNumber\" value=\"T\">\n";
					echo "<INPUT type=\"hidden\" name=\"IATA\" value=\"us\">\n";
					echo "<INPUT type=\"hidden\" name=\"Lang\" value=\"eng\">\n";
					echo "<tr><td align=\"left\"><font size=\"2\">Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "UPS";
					echo "=";
					echo "<INPUT type=\"text\" size=\"22\" name=\"InquiryNumber1\" value=\"";
					echo $tracking_num2[$i];
					echo "\"> ";
					echo "<input TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Track\">";
					if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
						echo ", ";
					}
					echo "</font></td></tr>\n";
					echo "</form>\n";
				} else {
					echo "<tr><td align=\"left\"><font size=\"2\">Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo $tracking_num2[$i];
					if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
						echo ", ";
					}
					echo "</font></td></tr>\n";
				}
			}
		}
		unset($tracking_num2);
        echo "<tr><td>&#160;</td></tr>";
	}
	mysql_free_result($result);
?>
<tr><td>&nbsp;</td></tr>
<tr><td align="left"><font size="2"><a href="null" onClick="javascript:window.close()">Close Window</a></font></td></tr>
<tr><td>&nbsp;</td></tr>
</table>

<?php
mysql_close($dbh);
?>

</div>
</body>
</html>