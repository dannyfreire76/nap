<?php

include '../includes/main1.php';
include $base_path.'includes/customer.php';

checkCustomerLogin();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: My <?php echo $website_title; ?></title>

<?php
include $base_path.'includes/meta1.php';
?>
</head>

<body>
<div align="center">

<?php
include $base_path.'includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">My <?php echo $website_title; ?> Order History</font></td></tr>

<tr><td>&#160;</td></tr>
</table>

<?php
	$query = "SELECT * FROM receipts WHERE complete='1' AND member_id='$member_id' ORDER BY ordered";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
    if ( mysql_num_rows($result) > 0 ) {
        $order_count = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $order_count++;
            echo '<table class="left" style="margin-right: 24px">';
            $receipt_id = $line["receipt_id"];
            $ordered = $line["ordered"];
            list($ordered_date, $junk) = split(' ', $ordered);
            list($ordered_yr, $ordered_mn, $ordered_dy) = split('-', $ordered_date);
            echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\"><b>Order Number: ";
            echo $line["user_id"];
            echo " on ";
            echo $ordered_mn . "/" . $ordered_dy . "/" . $ordered_yr;
            echo "</b></font></td></tr>\n";
            $query2 = "SELECT * FROM receipt_items WHERE receipt_id='$receipt_id'";
            $result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
            while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">";
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
            
            echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Payment Type: ";
			echo displayPayType($line["pay_type"]);			
			echo "<br />Subtotal: $";
            echo $line["subtotal"];
            echo "<br />Shipping: $";
            echo $line["shipping"];
            echo "<br /><b>Total</b>: $";
            echo $line["total"];
            echo "</font></td></tr>\n";
            
            echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Shipped: ";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">FedEx Tracking Number";
                        echo ": ";
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
                        echo "<form action=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do\" method=\"post\"  name=\"getTrackNum\" target=\"_blank\">\n";
                        echo "<INPUT TYPE=\"HIDDEN\" TABINDEX=\"5\" NAME=\"CAMEFROM\" VALUE=\"OK\">\n";
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">USPS Tracking Number";
                        echo ": ";
                        echo $tracking_num2[$i];
                        echo '<input type="hidden" name="strOrigTrackNum" value="'.$tracking_num2[$i].'">';
                        echo "&#160;<input TYPE=\"SUBMIT\" NAME=\'Go to Label/Receipt Number page\' VALUE=\"Track\">";
                        if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
                            echo ", ";
                        }
                        echo "</font></td></tr>\n";
                        echo "</form>\n";
                    } elseif($shipper2[$i] == "DHL") {
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">DHL Tracking Number";
                        echo ": ";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">UPS Tracking Number";
                        echo ": ";
                        echo $tracking_num2[$i];
                        echo "<INPUT type=\"hidden\" name=\"InquiryNumber1\" value=\"";
                        echo $tracking_num2[$i];
                        echo "\"> ";
                        echo "<input TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Track\">";
                        if($tracking_num2_count > 1 && $i < $tracking_num2_last_pos) {
                            echo ", ";
                        }
                        echo "</font></td></tr>\n";
                        echo "</form>\n";
                    } else {
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
            
            echo "<tr><td>&nbsp;</td></tr>\n";
            echo '</table>';
            if ( $order_count % 2 == 0 ) {
                echo '<br class="clear" />';
            }
        }
        echo '<br class="clear" />&#160;';
    }
    else {
        echo '<div class="error"><br />You have no orders in your history.</div>';
    }
?>

<?php
include $base_path.'includes/foot1.php';
?>

</div>
</body>
</html>