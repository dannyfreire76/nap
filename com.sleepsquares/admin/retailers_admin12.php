<?php
//2/25/2009: Added fundsReceived checkbox and processing code

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include_once ($base_path.'includes/wc1.php');

$this_user_id = $_COOKIE["wms_user"];
$retailer_id = $_GET["retailer_id"];


$action = "";
$funds_received = array();
$spec_discs = array();
$hidden_sub_totals = array();
$old_spec_discs = array();
$shippings = array();
$discounts = array();

foreach($_REQUEST as $key => $value)
{
    if ( strpos($key, 'action')!==false ) {
        $action=$value;
    }
	else if ( strpos($key, 'funds_received_')!==false ) {
		$thisWRID = substr($key, 15);
        $funds_received[$thisWRID]=$value;
    }else if ( strpos($key, 'specdisc_')!==false ) {
		$thisWRID = substr($key, 9);
        $spec_discs[$thisWRID]=$value;
    }else if ( strpos($key, 'hiddenSubTotal_')!==false ) {
		$thisWRID = substr($key, 15);
        $hidden_sub_totals[$thisWRID]=$value;
    }else if ( strpos($key, 'oldSpecDisc_')!==false ) {
		$thisWRID = substr($key, 12);
        $old_spec_discs[$thisWRID]=$value;
    }else if ( strpos($key, 'hiddenShipping_')!==false ) {
		$thisWRID = substr($key, 15);
        $shippings[$thisWRID]=$value;
    }else if ( strpos($key, 'hiddenDiscount_')!==false ) {
		$thisWRID = substr($key, 15);
        $discounts[$thisWRID]=$value;
    }

    $$key = $value;
} 


if ( $action=='update' ) {
	$jsToExecute = "";
	
	if ( sizeof($funds_received)>0 ) {
		foreach($funds_received as $key=>$date) {
			$queryU = "UPDATE wholesale_receipts SET funds_received=str_to_date('".$date."', '%m/%d/%Y') WHERE wholesale_receipt_id='$key'";
			$resultU = mysql_query($queryU) or die("Query failed : " . mysql_error());
		}
	}

	if ( sizeof($spec_discs)>0 ) {
		foreach($spec_discs as $key=>$pct) {
			$queryU = "UPDATE wholesale_receipts SET special_discount=".$pct." WHERE wholesale_receipt_id='$key'";
			$resultU = mysql_query($queryU) or die("Query failed : " . mysql_error());

			if ( $old_spec_discs[$key] != $pct ) {//if the new pct doesn't equal the old
				if ( $pct>0 ) {
					$newSubTotal = number_format( ( $hidden_sub_totals[$key] * (100-$pct)/100 ), 2, ".", "" );//recalc the total
				} else {//user just unchecked the box, setting the total back to the original before the discount
					$newSubTotal = number_format( ( $hidden_sub_totals[$key] / ( (100-$old_spec_discs[$key])/100 ) ), 2, ".", "" );
				}

				$jsToExecute .= "$('#hiddenSubTotal_'+".$key.").val( ".$newSubTotal." );";
				$jsToExecute .= "$('#shownSubTotal_'+".$key.").html( '".$newSubTotal."' );";
				$jsToExecute .= "$('#oldSpecDisc_'+".$key.").val( ".$pct." );";

				$queryU = "UPDATE wholesale_receipts SET subtotal=".$newSubTotal." WHERE wholesale_receipt_id='$key'";
				$resultU = mysql_query($queryU) or die("Query failed : " . mysql_error());

				$newTotal = condDecimalFormat( $newSubTotal + ($shippings[$key]*1) - ($discounts[$key]*1) );
				$jsToExecute .= "$('#shownTotal_'+".$key.").html( '".$newTotal."' );";
				$queryU = "UPDATE wholesale_receipts SET total=".$newTotal." WHERE wholesale_receipt_id='$key'";
				$resultU = mysql_query($queryU) or die("Query failed : " . mysql_error());

				recalcAllComms();
			}
		}
	}


	echo 'ok|'.$jsToExecute;//if any update query fails it will add to the echo output so the js test on ok would fail
    exit();
}


$query = "SELECT * FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
	$main_special_disc = $line["special_discount"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';
$manager = "retailers";
$page = "Retailers Manager > Order History";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "SELECT store_name FROM retailer WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$store_name = stripslashes($line["store_name"]);
	}
	mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/date_input.css">
<script type="text/javascript" src="<?=$current_base?>includes/jquery.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/interface.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.dimensions.min.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.date_input.min.js"></script>

<script type="text/javascript">
    $(function() {//on doc ready
        OHist.init();
    });

    var OHist = new function() {
        this.init = function() {

            $('[@wrID]').each(function(){
				$(this).date_input({ start_of_week: 0 });
            });

			$('.saveChanges').click(function(){
				var post_url = window.location.href;

				var post_data = {};
				post_data.action = 'update';
				
				$('input[@wrID]').each(function() {
					var wrID = $(this).attr('wrID');
					eval('post_data.funds_received_' + wrID +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				})

				$('input[@name*=specdisc_]').each(function() {
					var thisName = $(this).attr('name');
					if ( $(this).is(':checked') ) {
						eval('post_data.' + thisName +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
					} else {
						eval('post_data.' + thisName +' = "0"');//this is the only way to build up post params dynamically
					}
				})

				$('input[@name*=hiddenSubTotal_]').each(function() {
					var thisName = $(this).attr('name');
					eval('post_data.' + thisName +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				})

				$('input[@name*=oldSpecDisc_]').each(function() {
					var thisName = $(this).attr('name');
					eval('post_data.' + thisName +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				})

				$('input[@name*=hiddenShipping_]').each(function() {
					var thisName = $(this).attr('name');
					eval('post_data.' + thisName +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				})

				$('input[@name*=hiddenDiscount_]').each(function() {
					var thisName = $(this).attr('name');
					eval('post_data.' + thisName +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				})


				var $thisLoading = $(this).siblings('.loading');
				$thisLoading.html('').removeClass('error3').removeClass('error');
				
				$.post(post_url, post_data, function(resp){
					resp = resp.split("|")
					var result = resp[0];
					var execThis = resp[1];
					if (result=='ok') {
						$thisLoading.addClass('error3').html('Changes saved.');
						eval(execThis);
					} else {
						$thisLoading.addClass('error').html(resp);
					}
					//$thisLoading.parents('div:first').Pulsate(300,3);//some interface.js effects not supported by newer JQuery
				})
			})
        }
    }
</script>

<style type="text/css">
	.odd2 {background-color: #EDEDED}
</style>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="">

<table border="0" width="97%">

<tr><td align="center"><font size="2">Retailers Order History for <?php echo $store_name; ?></font></td></tr>

<tr><td>&nbsp;
<br /><div>
<button class="saveChanges">Save Changes</button> <span class="loading"></span><br /></div>
</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
echo '</table>';
echo '<table><tr><td>';
?>

<?php
	$query = "SELECT *, DATE_format(funds_received, '%m/%d/%Y') as funds_received_format FROM wholesale_receipts WHERE complete='1' AND retailer_id='$retailer_id' ORDER BY ordered";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	$invCount = 0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$wholesale_receipt_id = $line["wholesale_receipt_id"];
		$ordered = $line["ordered"];
		list($ordered_date, $junk) = split(' ', $ordered);
		list($ordered_yr, $ordered_mn, $ordered_dy) = split('-', $ordered_date);

		$invCount++;
		$class= "odd";
		if ( $invCount % 2 == 0 ) {
			$class="even";
		}

		echo '<table class="'.$class.'" style="border:1px solid #000" cellpadding="4" cellspacing="0" width="100%"><tr><td align="left"><font size="2">';        
        echo '<div style="float:left">';
            echo "Order Number: ";
            echo $line["wholesale_order_number"];
            echo " on ";
            echo $ordered_mn . "/" . $ordered_dy . "/" . $ordered_yr;
            echo "</font>";
        echo "</div>";
        echo '<div style="float:left"><form method="post" action="./shipping_admin7.php"><input type="hidden" name="wholesale_receipt_id" value="'.$wholesale_receipt_id.'" />';
        echo '<input type="hidden" name="show_only" id="show_only" value="1" />';
        echo '&#160;&#160;<input type="submit" value="view invoice" class="'.$class.'" style="color:blue; text-decoration:underline; border: none;" /></form></div>';
        
        echo "</td></tr>\n";
		$query2 = "SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());

		$thisItemCnt=0;
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$thisItemCnt++;
			
			if ( $thisItemCnt % 2==0) {
				$rowClass="even";
			} else {
				$rowClass="odd2";
			}

			echo '<tr class="'.$rowClass.'"><td align="left"><font size="2">';
			echo '&#149; '.$line2["quantity"];
			echo " of ";
			echo $line2["name"];
			echo " for $";
			echo condDecimalFormat($line2["price"]);
			echo " each.</font>";

			if( $line2["prod_batch_id"] ) {
				echo '<div style="padding-left: 20px; padding-top: 5px;">Batch: ';

				$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE prod_batch_id='".$line2["prod_batch_id"]."'";
				$resultBatch = mysql_query($queryBatch) or die("Query failed : " . mysql_error());
				if ( mysql_num_rows($resultBatch) > 0 ) {
					while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
						echo $lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"];
					}
				} else {
					echo 'none selected';
				}

				echo '</div>';
			}

			if( $line2["shipper"] && $line2["tracking_num"] ) {
				echo '<div style="padding-left: 20px;">';
				if( $line2["shipper"] == "FEDX" ) {
					echo "Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "FedEx";
					echo "=";
					echo "<a href=\"http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcom&cntry_code=us&language=english&tracknumbers=";
					echo $line2["tracking_num"];
					echo "\" TARGET=\"_BLANK\">";
					echo $line2["tracking_num"];
					echo "</a>";
				} elseif($line2["shipper"] == "USPS") {
					echo "<form action=\"http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do\" method=\"post\"  name=\"getTrackNum\" target=\"_blank\">\n";
					echo "<INPUT TYPE=\"HIDDEN\" TABINDEX=\"5\" NAME=\"CAMEFROM\" VALUE=\"OK\">\n";
					echo "Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "USPS";
					echo "=";
					echo "<input type=\"hidden\" id=\"Enter label number:\" size=\"22\" maxlength=\"34\" name=\"strOrigTrackNum\" value=\"";
					echo $line2["tracking_num"];
					echo "\"> ";
					echo $line2["tracking_num"].'&#160;&#160;';
					echo "<input TYPE=\"SUBMIT\" NAME=\'Go to Label/Receipt Number page\' VALUE=\"Track\">";
					echo "</form>\n";
				} elseif($line2["shipper"] == "DHL") {
					echo "Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "DHL";
					echo "=";
					echo "<a href=\"http://track.dhl-usa.com/TrackByNbr.asp?ShipmentNumber=";
					echo $line2["tracking_num"];
					echo "\" TARGET=\"_BLANK\">";
					echo $line2["tracking_num"];
					echo "</a>";
				} elseif($line2["shipper"] == "UPS") {
					echo "<FORM method=\"post\" action=\"http://wwwapps.ups.com/WebTracking/OnlineTool\" target=\"_blank\">\n";
					echo "<INPUT type=\"hidden\" name=\"UPS_HTML_License\" value=\"CBFDAE072B810C21\">\n";
					echo "<INPUT type=\"hidden\" name=\"UPS_HTML_Version\" value=\"3.0\">\n";
					echo "<INPUT type=\"hidden\" name=\"TypeOfInquiryNumber\" value=\"T\">\n";
					echo "<INPUT type=\"hidden\" name=\"IATA\" value=\"us\">\n";
					echo "<INPUT type=\"hidden\" name=\"Lang\" value=\"eng\">\n";
					echo "Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo "UPS";
					echo "=";
					echo "<INPUT type=\"hidden\" size=\"22\" name=\"InquiryNumber1\" value=\"";
					echo $line2["tracking_num"];
					echo "\"> ";
					echo $line2["tracking_num"].'&#160;&#160;';
					echo "<input TYPE=\"SUBMIT\" NAME=\"submit\" VALUE=\"Track\">";
					echo "</form>\n";
				} else {
					echo "Tracking Number";
					if($tracking_num2_count > 1) { echo "s"; }
					echo ": ";
					echo $line2["tracking_num"];
				}
				echo '</div>';
			}
		}
		mysql_free_result($result2);
		
		echo "<tr><td align=\"left\"><font size=\"2\">Payment Type: ";
		echo displayPayType($line["pay_type"]);
		echo " <br />Subtotal: $";
		echo '<span id="shownSubTotal_'.$wholesale_receipt_id.'">';
		echo condDecimalFormat($line["subtotal"]);
		echo '</span><input type="hidden" name="hiddenSubTotal_'.$wholesale_receipt_id.'" id="hiddenSubTotal_'.$wholesale_receipt_id.'" value="'.$line["subtotal"].'" />';
		echo "<br />Shipping: $";
		echo $line["shipping"];

		echo '<input type="hidden" name="hiddenShipping_'.$wholesale_receipt_id.'" id="hiddenShipping_'.$wholesale_receipt_id.'" value="'.$line["shipping"].'" />';

		echo '<input type="hidden" name="hiddenDiscount_'.$wholesale_receipt_id.'" id="hiddenDiscount_'.$wholesale_receipt_id.'" value="'.$line["discount"].'" />';

		if ( $line["discount"] > 0 ) {
			echo "<br />Additional Discount: -$";
			echo $line["discount"].'';
		}

		if ( $line["credit_used"] > 0 ) {
			echo "<br />Credit Used: -$";
			echo $line["credit_used"].'';
		}

		echo "<br /><b>Total: $";
		echo '<span id="shownTotal_'.$wholesale_receipt_id.'">'.$line["total"].'</span></b>';

        echo '<br />Funds Received: <input type="text" name="funds_received_'.$wholesale_receipt_id.'" id="funds_received_'.$wholesale_receipt_id.'" wrID="'.$wholesale_receipt_id.'" value="'.($line["funds_received_format"]!=0 ? $line["funds_received_format"]: '').'" size="10" />';
        echo '&#160;<span class="smaller">(mm/dd/yyyy)</span>';

        echo '<br />Special Discount (';
		echo '<input type="hidden" name="oldSpecDisc_'.$wholesale_receipt_id.'" id="oldSpecDisc_'.$wholesale_receipt_id.'" value="'.$line["special_discount"].'" />';

		if ( $line["special_discount"]>0 ) {
			echo $line["special_discount"];
		} else {
			echo $main_special_disc;
		}

		echo '%): <input type="checkbox" name="specdisc_'.$wholesale_receipt_id.'" id="specdisc_'.$wholesale_receipt_id.'" value="'.$main_special_disc.'" ';
		if ( $line["special_discount"]!=0 ) {
			echo 'checked="true"';
		}
		echo '/>';

		echo "</font></td></tr>\n";
		
		echo "<tr><td align=\"left\"><font size=\"2\">Shipped: ";
		if($line["shipped"] == 1) { echo "Yes"; }
		else { echo "No"; }
		if($line["shipped_date"] != "0000-00-00 00:00:00" && $line["shipped_date"] != "0" && $line["shipped_date"] != "") {
			echo " on ";
			echo $line["shipped_date"];
		}
		echo "</font></td></tr>\n";
		
		echo "<tr><td>&nbsp;</td></tr></table>\n";
	}
	mysql_free_result($result);
?>
<td></tr></table>

<table>
<tr><td><div><button class="saveChanges">Save Changes</button> <span class="loading"></span></div>
</td></tr>
<tr><td align="left"><font size="2"><a href="null" onClick="javascript:window.close()">Close Window</a></font></td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<?php
mysql_close($dbh);
?>

</div>
</body>
</html>