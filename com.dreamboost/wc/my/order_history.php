<?php

header('Content-type: text/html; charset=utf-8');
include '../../includes/main1.php';
include '../../includes/wc1.php';

check_wholesale_login();

$query = "SELECT store_name, contact_name FROM retailer WHERE retailer_id='$retailer_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$store_name = stripslashes($line["store_name"]);
	$contact_name = stripslashes($line["contact_name"]);
}
mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: My <?php echo $website_title; ?> Order History</title>
<SCRIPT LANGUAGE="JavaScript">
<!--
function getTrackNum_validator()
{
	nospc(document.getTrackNum.origTrackNum.value, document.getTrackNum.origTrackNum);
	if (document.getTrackNum.origTrackNum.value.length == 0)
	{
		alert("You must enter a tracking number. Please try again.");
		document.getTrackNum.origTrackNum.focus();
		return false;
	}
	if (document.getTrackNum.origTrackNum.value.search("'") != -1)
	{
		alert("Invalid tracking number entered. Please try again.");
		document.getTrackNum.origTrackNum.focus();
		return false;
	}
	return true;
}
// -->
</SCRIPT>
<?php
include $base_path.'includes/meta1.php';
?>
    <script language="JavaScript">
        $(function() {//on doc ready
            OH.init();
        });

        var OH= new function() {
    
            this.init = function() {
                $('#step2_submit', '.payForm').each(function(){
					$(this).click( function() { OH.form=$(this).parents('.payForm'); OH.checkForm(); return false; } );
				})
					               
				$('.payForm').each(function(){
					$(this).submit( function() { return false; } );
				})
            }            
            
            this.checkForm = function() {
                var err_msg = '';
                var err_fld = '';
                
                $(':input:visible', OH.form).each(function() {
                    if ( !$(this).attr('optional') || $(this).val()!='' ) {
                        var $therow = $(this).parents('tr:first')
                        var field_name = $('td:first', $therow).html()
                        field_name = field_name.substring(field_name.indexOf('*')+1, field_name.indexOf(':'));

                        if ( $(this).attr('id')=='email') {
                            if ( !$(this).val().trim().match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\..{2,2}))$)\b/gi) ) {
                                err_msg = 'Please enter a valid ' + field_name + '.';
                                err_fld = $(this).attr('id');
                            }
                        }

                        if ( $(this).attr('minlength') && $(this).val().length < $(this).attr('minlength') ) {
                            err_msg = 'Please enter at least '+ $(this).attr('minlength') +' characters for ' + field_name + '.';
                            err_fld = $(this).attr('id');
                        }

                        if ( $(this).val()=='' ) {
                            err_msg = 'Please complete the ' + field_name + ' field.';
                            err_fld = $(this).attr('id');
                        }

                    }    
                    if ( err_fld != '' ) {
                        return false; //in this context, breaks out of each loop
                    }
                })

                if ( err_fld != '' ) {
                    $( '#floating_msg' ).html(err_msg);
                    return OH.showError( $('#'+err_fld, OH.form) );
                }
                else {
                    OH.submitUpdates();
                }
            }

            this.submitUpdates = function() {
                $( '.loading', OH.form ).small_spinner().slideDown(200);
                var post_url = $('#current_base').val()+ 'wc/step2.php';

				var post_data = {};
				$(':input', OH.form).each( function() {
					eval('post_data.' + $(this).attr("name")+' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
                })

				$( '#step2_submit', OH.form ).attr('disabled', 'true');
				$( '#cancel', OH.form ).attr('disabled', 'true');
                $.post(post_url, post_data, function(resp){
                    $( '.loading', OH.form ).ScrollTo(400);
                    if ( resp == 'ok' ) {
                        $( '.payDiv', OH.form ).hide();
						$( '#payNowWrapper_'+ $('#wholesale_order_number', OH.form).val() ).addClass('error3').html('Thank you for submitting payment.');
						$( '#payTypeWrapper_'+ $('#wholesale_order_number', OH.form).val() ).html('Credit Card');
                    }
                    else {
                        $( '.loading', OH.form ).addClass('error').html(resp);
						$( '#step2_submit', OH.form ).removeAttr('disabled');
						$( '#cancel', OH.form ).removeAttr('disabled');
                    }
                }) 
            }

            this.showError = function(elem) {
                $(elem).ScrollTo(400);

                var float_pos = findPos( $(elem).get(0) )

                var left_padding, right_padding, top_padding, bottom_padding = 0;
                if ( $('#floating_msg').css('padding-left') ) {
                  left_padding = 1* $('#floating_msg').css('padding-left').substring( 0, $('#floating_msg').css('padding-left').indexOf('px') );
                }

				if ( $('#floating_msg').css('padding-right') ) {
                  right_padding = 1 * $('#floating_msg').css('padding-right').substring( 0, $('#floating_msg').css('padding-right').indexOf('px') );
                }

                var new_left = float_pos[0];
                if ( $('#floating_msg').width() + float_pos[0] > OH.form.width() ) {                            
                    new_left = float_pos[0] - $('#floating_msg').width() + $(elem).width();
                    if ( !jQuery.browser.msie  ) {
                        new_left =  new_left - left_padding - right_padding;
                    }
                }

                if ( $(elem).css('padding-top') ) {
                    top_padding = 1* $(elem).css('padding-top').substring( 0, $(elem).css('padding-top').indexOf('px') );
                }
                if ( $(elem).css('padding-bottom') ) {  
                    bottom_padding = 1 * $(elem).css('padding-bottom').substring( 0, $(elem).css('padding-bottom').indexOf('px') );
                }
                var new_top = float_pos[1] +  $(elem).height() + top_padding + bottom_padding + 2;
                $('#floating_msg')
                    .css('left', new_left+'px')
                    .css('top', new_top+'px')
                    .fadeIn(300, function(){
                        if ( $(elem).attr('type')=='textbox' ) {
                            $(elem).focus();
                        }                
                    });

                setTimeout("$('#floating_msg').fadeOut(300)", 3500);
                $('button').removeAttr('disabled');
                return false;     
            }


        }
    </script>
</head>

<body>
<div id="floating_msg" class="no_display absolute"></div>

<div align="center">

<?php
include '../../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font class="style4" face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">My <?php echo $website_title; ?> Order History</font></td></tr>
<tr><td>&#160;</td></tr>
<?php
//Error Messages
if($error_txt) {
	echo "<tr><td><font face=\"Arial\" size=\"+1\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>

<?php
	$query = "SELECT * FROM wholesale_receipts WHERE complete='1' AND retailer_id='$retailer_id' ";
	if ( $_SESSION["rep_id"] ) {
		$query .= " AND ordered > '".$_SESSION["rep_info"]["start_date"]."' ";
	}
	$query .= " ORDER BY ordered";

	$result = mysql_query($query) or die("Query failed : " . mysql_error());
    if ( mysql_num_rows($result) > 0 ) {
		$order_cnt = 0;
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			foreach($line as $col_name=>$col_val) {
				$$col_name = $col_val;
			}

			$order_cnt++;
            $wholesale_receipt_id = $line["wholesale_receipt_id"];
            $ordered = $line["ordered"];
            list($ordered_date, $junk) = split(' ', $ordered);
            list($ordered_yr, $ordered_mn, $ordered_dy) = split('-', $ordered_date);
            echo "<tr><td align=\"left\" class=\"bold\">Order Number: ";
            echo $line["wholesale_order_number"];
            echo " on ";
            echo $ordered_mn . "/" . $ordered_dy . "/" . $ordered_yr;
            echo "</td></tr>\n";
            $query2 = "SELECT quantity, price, name, shipper, tracking_num FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
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
            if($line["pay_type"] == "ona") { echo "<span id=\"payTypeWrapper_".$line["wholesale_order_number"]."\">". displayPayType($line["pay_type"])."</span>&#160;&#160;&#160;<span id=\"payNowWrapper_".$line["wholesale_order_number"]."\"><button onClick=\"$('#payNow_".$line["wholesale_order_number"]."').show()\">Pay Now</button></span>"; }
            echo displayPayType($line["pay_type"]);
            echo "<br /> Subtotal: $";
            echo $line["subtotal"];
            echo "&#160;&#160;&#160;&#160;Shipping: $";
            echo $line["shipping"];

			if ( $line["credit_used"] > 0 ) {
				echo "&#160;&#160;&#160;&#160;Credit: -$";
	            echo $line["credit_used"];
			}

            echo "&#160;&#160;&#160;&#160;Total: $";
            echo $line["total"];
            echo "</font></td></tr>\n";
            
            echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Shipped: ";
            if($line["shipped"] == 1) { echo "Yes"; }
            else { echo "No"; }
            if($line["shipped_date"] != "0000-00-00 00:00:00" && $line["shipped_date"] != "0" && $line["shipped_date"] != "") {
                echo " on ";
                echo $line["shipped_date"];
            }
            echo "</font>";
			echo "</td></tr>\n";
            
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
                        echo "<tr><td align=\"left\"><font face=\"Arial\" size=\"+1\">Tracking Number";
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
  			if ( $line["pay_type"] == "ona" ) {
			?>
				<tr><td>
					<form name="submitPayForm_<?=$line["wholesale_order_number"]?>" id="submitPayForm_<?=$line["wholesale_order_number"]?>" class="payForm" style="margin: 0px; padding: 0px;" method="POST">
						<input type="hidden" name="wholesale_order_number" id="wholesale_order_number" value="<?=$line["wholesale_order_number"]?>" />
						<input type="hidden" name="ajax" id="ajax" value="1" />
						<input type="hidden" name="pay_type" id="pay_type" value="cc" />
						<input type="hidden" name="shipping_method_desc" id="shipping_method_desc" value="<?=$line["shipping_method"]?>" />
						<input type="hidden" name="item_count" id="item_count" value="<?=$line["item_count"]?>" />
						<input type="hidden" name="subtotal" id="subtotal" value="<?=$line["subtotal"]?>" />
						<input type="hidden" name="shipping" id="shipping" value="<?=$line["shipping"]?>" />
						<input type="hidden" name="tax" id="tax" value="<?=$line["tax"]?>" />
						<input type="hidden" name="total" id="total" value="<?=$line["total"]?>" />
						<input type="hidden" name="wholesale_receipt_id" id="wholesale_receipt_id" value="<?=$line["wholesale_receipt_id"]?>" />

						<input type="hidden" name="discount_code" id="discount_code" value="<?=$line["discount_code"]?>" />
						<input type="hidden" name="discount_pct" id="discount_pct" value="<?=$line["discount_pct"]?>" />

						<div class="no_display payDiv" id="payNow_<?=$line["wholesale_order_number"]?>">
							<table>
							<?php
								include('../../includes/cc_fields.php');
							?>
								<tr>
									<td colspan="2" align="center">
										<br />
										<input type="submit" name="step2_submit" id="step2_submit" style="margin-left: 0px" value="Submit Payment" />
										&#160;&#160;
										<input type="button" name="cancel" id="cancel" value="Cancel" onClick="$('#payNow_<?=$line["wholesale_order_number"]?>').BlindUp(200); return false;" />
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										<div class="loading no_display" style="width: 500px"></div>
									</td>
								</tr>
							</table>
						</div>
					</form>
				</td></tr>
			<?php
			}          
			if ( $order_cnt!=mysql_num_rows($result) ) {
	            echo "<tr><td>&#160;";
				echo '<hr />';
				echo "</td></tr>\n";
			}
        }
    }
    else {
        echo '<tr><td class="error"><br />You have no orders in your history.</td></tr>';
    }
?>
<tr><td>&nbsp;</td></tr>
</table>

<?php
include '../../includes/foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>