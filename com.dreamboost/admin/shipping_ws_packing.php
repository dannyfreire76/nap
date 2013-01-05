<?php
include_once '../includes/main1.php';
include_once '../includes/wc1.php';


$wholesale_receipt_id = $_POST["wholesale_receipt_id"];


function beginsWith($str, $sub) {
    return (strncmp($str, $sub, strlen($sub)) == 0);
}

?>

<html>
<head>
<title><?php echo $website_title; ?>: Packing Slip</title>
<?php
include $base_path.'includes/meta1.php';
?>
<script src="<?=$current_base?>includes/mousehold.js" type="text/javascript"></script>

<script type="text/javascript">

$(function() {//on doc ready
	var arrow_inc = 20;
	var max_slide = 580;

	$('.printChk').click(function(){
		if ( $(this).is(":checked") ) {
			$(this).parents('tr:first').removeClass('no_print');
		} else {
			$(this).parents('tr:first').addClass('no_print');		
		}
	})

	$('#arrow_plus').mousehold(function(i) {
		var curr_padding = $('#slider').css('margin-left');
		curr_padding = curr_padding.substring(0, curr_padding.indexOf('px'));
		var new_padding = curr_padding * 1 + arrow_inc;
		if ( new_padding > max_slide ) {
			new_padding = max_slide;
		}
		$('#slider').css('margin-left', (new_padding) + 'px');
		$('#remit_padding').css('padding-top', (new_padding*3)+'px');
	})

	$('#arrow_minus').mousehold(function(i) {
		var curr_padding = $('#slider').css('margin-left');
		curr_padding = curr_padding.substring(0, curr_padding.indexOf('px'));
		var new_padding = curr_padding * 1 - arrow_inc;
		if ( new_padding < 0 ) {
			new_padding = 0;
		}
		$('#slider').css('margin-left', (new_padding) + 'px');
		$('#remit_padding').css('padding-top', (new_padding*3)+'px');
	})
	

	$('#ellipses').each( function(){
		var ellHTML = $(this).html();
		for (x=1; x<34; x++) {
			$(this).append( ellHTML )
		}
	} )

	$('#addNote').click(function(){
		if ( $(this).is(':checked') ) {
			$('#noteArea').slideDown(200);
			$('#noteWrapper').removeClass('no_print');
		} else {
			$('#noteArea').slideUp(200);
			$('#noteWrapper').addClass('no_print');
		}
	})
});

</script>

<style type="text/css" media="print">
	#printHelp,
	.no_print, 
	#addNote {
		display:none;
	}

	#remit_padding {display:block !important;}

	#noteArea {
		border:1px solid #ffffff;
	}
</style>

<style type="text/css">
	#remit_padding {display:none;}

	#admin_header_img {
		display: none;
	}

	.small_gray {
		font-size: 12px;
		font-weight: bold;
		color: #808080;
	}
</style>

</head>
<body style="background-color: #ffffff !important; background-image: none !important; border: 0px;">
<center>
<div>

<?php
include_once $base_path.'admin/includes/head_admin1.php';
?>

<table border="0" style="width:677px">
<tr><td>&nbsp;</td></tr>


<?php
	echo '<tr><td>';
		echo '<div class="left">';
			echo '<h2 style="padding: 0px; margin:0px;">'.$company_name.'</h2>';
			echo $company_address.'<br />';
			echo $company_city_state_zip.'<br />';
			echo 'Phone/Fax '.$company_phone;
		echo "</div>";

		echo '<div class="right" style="color: #e0e0e0; font-weight: bold; font-size: 28px;">Packing Slip</div>';
	echo '</td></tr>';
?>


<tr><td>&nbsp;</td></tr>

<tr><td><table border="0" width="100%">

<?php
//actual iteration through this resultset is done further down but we needed to do it here for tracking number code
$queryWRI = "SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
$resultWRI = mysql_query($queryWRI) or die("Query failed : " . mysql_error());


//email receipt string
$email_str = "";
	$query = "SELECT wholesale_receipts.*, retailer.store_name as store_name, retailer.* FROM wholesale_receipts, retailer WHERE wholesale_receipt_id='$wholesale_receipt_id' and retailer.retailer_id=wholesale_receipts.retailer_id";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$discount_code = $line["discount_code"];
		$discount_pct = $line["discount_pct"];
		$store_name = stripslashes($line["store_name"]);
		$order_date = $line["ordered"];
		$pay_type = $line["pay_type"];
		$order_total = $line["total"];

		if ( $discount_pct && $discount_pct > 0 ) {
			$query3 = "SELECT * from discount_codes where discount_code = '".$discount_code."'";
			$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
			while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
				$discount_desc = $line3["location_target"];
			}
		}

        echo '<tr valign="top"><td><b>Order&nbsp;Date:&nbsp;</b></td><td>'.date( "m/d/Y", strtotime($line["ordered"]) ).'</td>';
		echo '<td width="100">&#160;</td>';
		echo '<td><b>Date:&nbsp;</b></td><td>'.Date("F d, Y").'</td>';
		
		echo "</tr>";
		echo '<tr valign="top">
				<td>
					<b>Order&nbsp;Number:&nbsp;</b>
				</td>
				<td>
					'.$line["wholesale_order_number"].'
				</td>
				<td>&nbsp;</td>
				<td>
					<b>Customer&nbsp;Contact:&nbsp;</b>
				</td>
				<td>
					'.$line["contact_name"].'
				</td>';
		
		echo '</tr>';
		
		echo
			'<tr valign="top">';

		if ( $line["po"] ) {
			echo '
				<td>
					<b>Purchase&nbsp;Order:&nbsp;</b>
				</td>
				<td>
					'.$line["po"].'
				</td>
				<td>&nbsp;</td>';
		} else {
			echo '
				<td>
					
				</td>
				<td>
					
				</td>
				<td width="100">&nbsp;</td>';		
		}


		echo '
			<td>
				<b>Customer&nbsp;Account:&nbsp;</b>
			</td>
			<td>
				'.$store_name.'
			</td>
		</tr>';

		echo '<tr valign="top"><td>&nbsp;</td></tr>';

        echo '<tr valign="top"><td><font face="Arial" size="+1"><b>Ship&nbsp;To:&nbsp;&nbsp;</b></font></td>';

		echo "<td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

		echo stripslashes($line["ship_name"]) . "<br>\n";
		
		echo stripslashes($line["ship_address1"]) . "<br>\n";
		
		if ($line["ship_address2"]) {
			echo stripslashes($line["ship_address2"]) . "<br>\n";
		}
		echo stripslashes($line["ship_city"]) . ", " . $line["ship_state"] . "<br>\n";
		
		echo $line["ship_zip"] . ", " . $line["ship_country"] . "<br>\n";

		if ( $line["delivery"] ) {
			echo "<br />Delivery Information: ";			
			echo stripslashes($line["delivery"]);
		}

		echo '</font></td><td>&nbsp;</td><td><font face="Arial" size="+1"><b>Bill&nbsp;To:&nbsp;&nbsp;</b></font></td>';
		
		echo "<td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

		
		if($line["cc_first_name"] != "" || $line["cc_last_name"] != "") {
			echo stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]) . "<br>\n";
		} else {
			echo stripslashes($line["bill_name"]) . "<br>\n";
		}
		
		echo stripslashes($line["bill_address1"]) . "<br>\n";
		
		if ($line["bill_address2"]) {
			echo stripslashes($line["bill_address2"]) . "<br>\n";
		}
		echo stripslashes($line["bill_city"]) . ", " . $line["bill_state"] . "<br>\n";
		
		echo $line["bill_zip"] . ", " . $line["bill_country"] . "<br>\n";

		echo "</font></td></tr>\n";
		
		echo "</table></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";

	}

?>

<tr><td>
<table cellpadding="4" cellspacing="0" width="100%" class="price_chart" border="1" bordercolor="#000000" style="font-size:12px">
	<tr class="bg_highlighted white">
		<th class="no_print">Print</th>
		<th>Item #</th>
		<th>Description</th>
		<th>Unit Type</th>
		<th>Order Quantity</th>
		<th>Ship Quantity</th>
		<th>Backorder Quantity</th>
	</tr>
<?php
$total_quantity = 0;
$total_gross = 0;
$discount_total = 0;
$subtotal = 0;

	while ($line = mysql_fetch_array($resultWRI, MYSQL_ASSOC)) {
		//if($line["shipped_out"] == "1") {

			$tmp_sku = $line["sku"];
			$tmp_quantity = $line["quantity"];
			$total_quantity += $tmp_quantity;

			//find product url and props
			$query2 = "SELECT ps.*, pc.name AS prod_cat_name FROM product_skus ps, product_categories pc, products p WHERE ps.sku='$tmp_sku' AND ps.prod_id=p.prod_id AND p.prod_cat_id=pc.prod_cat_id";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			   foreach ($line2 as $col_name2 => $col_val2) {
				   $this_sku[$col_name2] = $col_val2;
			   }
			}

			echo '<tr class="text_center no_print" valign="top">';

			echo '<td class="no_print">';
			echo '<input type="checkbox" class="printChk" />';
			echo "</td>";
			
			echo '<td>';
			echo $tmp_sku;
			echo "</td>";
			
			echo "<td>";
			echo $line["name"];
			echo "</td>";
			
			echo '<td>'	;
			echo $this_sku["drop_down"];
			echo "</td>";
			
			echo "<td>";
			echo $line["quantity"];
			echo "</td>";
			
			echo "<td>";
				if ( $line["shipped_out"] ) {
					echo $line["quantity"];
				} else {
					echo "0";
				}
			echo "</td>";

						
			echo "<td>";
				if ( !$line["shipped_out"] ) {
					echo $line["quantity"];
				} else {
					echo "0";
				}
			echo "</td>";

			echo "</tr>";

		}
	//}
?>

</table>

<center>
	<div id="noteWrapper" class="no_print text_center" style="width:677px">
		<label for="addNote" class="small_gray">Comments: <input type="checkbox" id="addNote" /></label>
		<div id="noteArea" class="no_display small_gray">
			<textarea style="height:100px; width: 100%;" id="noteArea" class="small_gray text_center"></textarea>
		</div>
	</div>
	<br />
	<div class="small_gray" style="padding-bottom: 10px">Please contact the Customer Service department at <?=$company_phone?> with any questions or concerns.</div>
	<b>Thank you for your order!</b>
</center>
<br />
</div>
</center>
</body>
</html>
