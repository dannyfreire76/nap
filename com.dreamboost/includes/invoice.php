<?php
ob_start();

// 7/7/2009: Changed display to show all, regardless of shipped_out or not
include_once 'main1.php';
include_once ($base_path.'includes/wc1.php');


$wholesale_receipt_id = $_POST["wholesale_receipt_id"];


function DateAdd($interval) {
	$curdate = getdate();
	$cday = $curdate[’mday’]+$interval;
	$cmonth = $curdate[’mon’];
	$cyear = $curdate[’year’];

	if ($cday > 30){
		$cmonth = $cmonth + 1;
		$cday = $cday - 30;

		if ($cmonth == 13){
			$cyear = $cyear + 1;
			$cmonth = 1;
		}
	}

	$ourDate = array($cyear,$cmonth,$cday);

	return $ourDate;

}

function beginsWith($str, $sub) {
    return (strncmp($str, $sub, strlen($sub)) == 0);
}

?>

<html>
<head>
<title><?php echo $website_title; ?>: Receipt</title>
<?php
include $base_path.'includes/meta1.php';
?>
<script src="<?=$current_base?>includes/mousehold.js" type="text/javascript"></script>

<script type="text/javascript">

$(function() {//on doc ready
	//textAreasExpandable();
	var arrow_inc = 20;
	var max_slide = 580;

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
			$('#noteArea').BlindDown(200);
			$('#noteWrapper').removeClass('no_print');
		} else {
			$('#noteArea').BlindUp(200);
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
</style>

</head>
<body style="background-color: #ffffff !important; background-image: none !important;">
<center>
<div>

<?php
include_once $base_path.'admin/includes/head_admin1.php';
?>

<table border="0" style="width:677px">

<tr><td>&nbsp;</td></tr>

<tr><td><table border="0" width="100%">

<?php
//actual iteration through this resultset is done further down but we needed to do it here for tracking number code
$queryWRI = "SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
$resultWRI = mysql_query($queryWRI) or die("Query failed : " . mysql_error());


//email receipt string
$email_str = "";
	$query = "SELECT wholesale_receipts.*, retailer.store_name as store_name FROM wholesale_receipts, retailer WHERE wholesale_receipt_id='$wholesale_receipt_id' and retailer.retailer_id=wholesale_receipts.retailer_id";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$discount_code = $line["discount_code"];
		$discount_pct = $line["discount_pct"];
		$store_name = stripslashes($line["store_name"]);
		$order_date = $line["ordered"];
		$pay_type = $line["pay_type"];
		$order_total = $line["total"];
		$credit_used = $line["credit_used"];

		if ( $discount_pct && $discount_pct > 0 ) {
			$query3 = "SELECT * from discount_codes where discount_code = '".$discount_code."'";
			$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
			while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
				$discount_desc = $line3["location_target"];
			}
		}

		$email_str .= "Dear " . stripslashes($line["bill_name"]) . ",\n\n";
		$email_str .= "Thank you for your ";
		$email_str .= $website_title;
		$email_str .= " order. Your Order Confirmation Number and Order ";
		$email_str .= "Number is " . $line["wholesale_order_number"] . ". Your Order has shipped from our ";
		$email_str .= "facility and is in route to the shipping address you entered. If you have any questions ";
		$email_str .= "please call us toll free at ".$company_phone.".\n\n";
        echo "<tr valign=\"top\"><td><b>Order Date: </b>".date( "m/d/Y", strtotime($line["ordered"]) )."</td>";

		$email_str .= "Order Date: ".date( "m/d/Y", strtotime($order_date) )."\n";
		$email_str .= "Order Number: ".$line["wholesale_order_number"]."\n\n";

		$queryTrack = "SELECT tracking_num FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id' AND tracking_num != ''";
		$resultTrack = mysql_query($queryTrack) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultTrack)>0 ) {

			echo '<td rowspan="2">';

			if ( mysql_num_rows($resultTrack) < mysql_num_rows($resultWRI) ) {
				while ($lineTrack = mysql_fetch_array($resultTrack, MYSQL_ASSOC)) {
					$queryTrack2 = "SELECT sku FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id' AND tracking_num='".$lineTrack["tracking_num"]."'";
					$resultTrack2 = mysql_query($queryTrack2) or die("Query failed : " . mysql_error());
					while ($lineTrack2 = mysql_fetch_array($resultTrack2, MYSQL_ASSOC)) {
						echo '<b>Tracking #:</b> '.$lineTrack["tracking_num"].' (SKU '.$lineTrack2["sku"].')<br />';
						$email_str .= "Tracking #: ".$lineTrack["tracking_num"].' (SKU '.$lineTrack2["sku"].")\n";
					}
				}

			} else {
				while ($lineTrack = mysql_fetch_array($resultTrack, MYSQL_ASSOC)) {
					echo '<b>Tracking #:</b> '.$lineTrack["tracking_num"];
					$email_str .= "Tracking #: ".$lineTrack["tracking_num"]."\n";
					break;//just do it once
				}
			}
			$email_str .= "\n";
			echo '</td>';
		}
		
		echo "</tr>";
		echo '<tr>
				<td>
					<b>Order Number: </b>'.$line["wholesale_order_number"].'
				</td>';
		
		echo '</tr>';

		echo '</table>
		<br />';

		echo '<table border="0" width="100%">';
        echo '<tr><td><font face="Arial" size="+1"><b>Bill To:</b></font></td><td><font face="Arial" size="+1"><b>Ship To:</b></font></td></tr>';

		echo "<tr><td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

		$email_str .= "Bill To:\n";
		
		if($line["cc_first_name"] != "" || $line["cc_last_name"] != "") {
			echo stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]) . "<br>\n";
			$email_str .= stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]) . "\n";
		} else {
			echo stripslashes($line["bill_name"]) . "<br>\n";
			$email_str .= stripslashes($line["bill_name"]) . "\n";
		}
		
		echo stripslashes($line["bill_address1"]) . "<br>\n";
		$email_str .= stripslashes($line["bill_address1"]) . "\n";
		
		if ($line["bill_address2"]) {
			echo stripslashes($line["bill_address2"]) . "<br>\n";
			$email_str .= stripslashes($line["bill_address2"]) . "\n";
		}
		echo stripslashes($line["bill_city"]) . ", " . $line["bill_state"] . "<br>\n";
		$email_str .= stripslashes($line["bill_city"]) . ", " . $line["bill_state"] . "\n";
		
		echo $line["bill_zip"] . ", " . $line["bill_country"] . "<br>\n";
		$email_str .= $line["bill_zip"] . ", " . $line["bill_country"] . "\n\n";

		echo "</font></td><td VALIGN=\"TOP\" width=\"300\"><font face=\"Arial\" size=\"+1\">";

		$email_str .= "Ship To:\n";
		echo stripslashes($line["ship_name"]) . "<br>\n";
		$email_str .= stripslashes($line["ship_name"]) . "\n";
		
		echo stripslashes($line["ship_address1"]) . "<br>\n";
		$email_str .= stripslashes($line["ship_address1"]) . "\n";
		
		if ($line["ship_address2"]) {
			echo stripslashes($line["ship_address2"]) . "<br>\n";
			$email_str .= stripslashes($line["ship_address2"]) . "\n";
		}
		echo stripslashes($line["ship_city"]) . ", " . $line["ship_state"] . "<br>\n";
		$email_str .= stripslashes($line["ship_city"]) . ", " . $line["ship_state"] . "\n";
		
		echo $line["ship_zip"] . ", " . $line["ship_country"] . "<br>\n";
		$email_str .= $line["ship_zip"] . ", " . $line["ship_country"] . "\n\n";

		if ( $line["delivery"] ) {
			echo "<br />Delivery Information: ";			
			echo stripslashes($line["delivery"]);
		}

		echo "</font></td></tr>\n";
		
		echo "</table></td></tr>\n";
		echo "<tr><td>&nbsp;</td></tr>\n";
		echo "<tr><td><font face=\"Arial\" size=\"+1\">";
		//echo "<table><tr valign=\"top\"><td width=\"300px\"><b>Payment Information:</b><br>\n";
		$email_str .= "Payment Information:\n";
		
		if($line["pay_type"] == "cc") {
			//echo stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]) . "<br>\n";
			$email_str .= stripslashes($line["cc_first_name"]) . " " . stripslashes($line["cc_last_name"]) . "\n";
			
			if ($line["cc_type"] == "mc") {
				//echo "Mastercard";
				$email_str .= "Mastercard";
			} elseif ($line["cc_type"] == "vi") {
				//echo "Visa";
				$email_str .= "Visa";
			} elseif ($line["cc_type"] == "am") {
				//echo "American Express";
				$email_str .= "American Express";
			} elseif ($line["cc_type"] == "di") {
				//echo "Discover";
				$email_str .= "Discover";
			}
			//echo "<br>\n";
			$email_str .= "\n";
			
			$tmp_cc_num = $line["cc_num"];
			$tmp_cc_num = substr($tmp_cc_num, -4);
			//echo "XXXXXXXXXXXX" . $tmp_cc_num . "<br><br>\n";
			$email_str .= "XXXXXXXXXXXX" . $tmp_cc_num . "\n\n";
			
		} else {
			$email_str .= displayPayType($line["pay_type"])."\n\n";
		}
			
				
		if ($line["delivery"] !== "") {
			$email_str .= "Delivery Information\n";
			$email_str .= stripslashes($line["delivery"]) . "\n\n";
		}
		$bill_email = $line["bill_email"];
		$shipping = $line["shipping"];
		$discount = $line["discount"];
		
		//echo "<b>Shipping Method:</b><br>\n";
		$email_str .= "Shipping Method:\n";
		//echo $line["shipping_method"] . "<br><br>\n";
		$email_str .= $line["shipping_method"] . "\n\n";

		//echo '	</font>
		//		</td>
		//	</tr>
		//</table>';	

		if ( $discount_pct && $discount_pct > 0 ) {
			$email_str .= "Unit price(s) reflect a ";
			$email_str .= ( $discount_pct * 100 );
			$email_str .= "% discount\n";
		}

		echo '
		<table width="100%" cellpadding="4" cellspacing="0" class="price_chart" border="1" bordercolor="#000000" style="font-size:12px">
			<tr class="bg_highlighted white">
				<th>Date</th>
			';
		
		if ( $line["po"] ) {
			echo '<th>P.O.</th>';
		}

		echo '
				<th>Invoice&#160;#</th>
				<th>Sales&#160;Rep.</th>
				<th>FOB</th>
				<th>Ship&#160;Via</th>
				<th>Terms</th>
			</tr>
			<tr class="text_center">
				<td>'.date( "m/d/Y", strtotime($line["ordered"]) ).'</td>
			';
		
			if ( $line["po"] ) {
				echo '<td>'.$line["po"].'</td>';
			}

			echo '
				<td>'.$line["wholesale_order_number"].'</td>
				<td><input class="text_center" type="text" style="width:100%" /></td>
				<td>Destination</td>
				<td>'.$line["shipping_method"].'</td>
				<td>';
				echo displayPayType($line["pay_type"]);			
			echo '</td>

			</tr>
		</table>
		<br />';
	}
	mysql_free_result($result);

?>

</font></td></tr>
<tr><td>
<table cellpadding="4" cellspacing="0" width="100%" class="price_chart" border="1" bordercolor="#000000" style="font-size:12px">
	<tr class="bg_highlighted white">
		<th>Quantity</th>
		<th>Item</th>
		<th>Units</th>
		<th>Description</th>
		<th>Product&#160;Description</th>
		<th>Unit&#160;CST</th>
		<th>Vend&#160;CS</th>
		<th align="right">Extension</th>
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
			$tmp_price = $line["price"];

			$tmp_subtotal = $line["quantity"] * $tmp_price;

			if ( $discount_pct && $discount_pct > 0  ) {
				$tmp_price2 = $line["orig_price"];
				$tmp_subtotal_before_disc = $line["quantity"] * $tmp_price2;

				$tmp_price = $tmp_price2;
			}
			else {
				$tmp_subtotal_before_disc = $line["quantity"] * $tmp_price;
			}


			$discount_total += ($tmp_subtotal_before_disc - $tmp_subtotal);


			$total_gross += $tmp_subtotal_before_disc;
			$total_gross = condDecimalFormat( $total_gross);

			$tmp_price = condDecimalFormat( $tmp_price);		
			$tmp_subtotal = $line["quantity"] * $tmp_price;
			$tmp_subtotal = condDecimalFormat( $tmp_subtotal);

			$subtotal = $subtotal + $tmp_subtotal;
			
			//find product url and props
			$query2 = "SELECT ps.*, pc.name AS prod_cat_name FROM product_skus ps, product_categories pc, products p WHERE ps.sku='$tmp_sku' AND ps.prod_id=p.prod_id AND p.prod_cat_id=pc.prod_cat_id";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			   foreach ($line2 as $col_name2 => $col_val2) {
				   $this_sku[$col_name2] = $col_val2;
			   }
			}
			mysql_free_result($result2);

			echo '<tr class="text_center" valign="top"><td>';
			echo $line["quantity"];
			echo "</td><td>";
			echo $tmp_sku;
			echo "</td>";

			echo '<td>';
			echo $this_sku["drop_down"];
			echo '</td>';
			
			echo '<td>';
			echo '<a href="'.$current_base.'store/product.php?prod_id='.$this_sku["prod_id"].'" target="_blank">';
			echo $line["name"];
			echo '</a>';
			echo '</td><td>'	;
			echo $this_sku["prod_cat_name"];
			echo "</td><td>";
			echo $tmp_price;
			echo "</td>";
			echo '<td>'.$tmp_subtotal.'</td>';
			echo '<td class="text_right">'.$tmp_subtotal.'</td>';
			echo "</tr>";

			if ( $discount_pct && $discount_pct > 0 ) {
				echo '<tr>';
				echo '<td colspan="5" class="text_center">';
					echo 'Discount: '.($discount_pct * 100).'%  - '.$discount_desc;
				echo '</td>';
				echo '<td class="text_center">'.condDecimalFormat( $line["price"]).'</td>';
				echo '<td class="text_center">'.condDecimalFormat( ($line["price"] * $line["quantity"])).'</td>';
				echo '<td class="text_right">'.condDecimalFormat( ($line["price"] * $line["quantity"])).'</td>';
				echo '</tr>';
			}

			$email_str .= $line["name"] . " SKU: " . $tmp_sku . "                     ";
			$email_str .= 'Qty: '.$tmp_quantity . "                     ";
			$email_str .= 'Price: '."$" . $tmp_price . "                     ";
			$email_str .= 'Subtotal: '."$".$tmp_subtotal . "\n";
		}
	//}

//discount_total is just the discount from a coupon code, not including the additional discount
$discount_total_true = $discount_total + $discount;
 
?>
<tr>
	<td colspan="6" style="border-left:1px solid #fff; border-bottom:1px solid #fff;" rowspan="<?php echo ($discount_pct)?'5':'4' ?>">
		<table width="100%" class="text_center">
			<tr>
				<td>Total&#160;Pieces</td>
				<td>Gross&#160;$$$</td>
				<?php 
				if ( $discount_total_true > 0 ) {
					echo '<td>Discount&#160;Total</td>';
				}

				if ( $credit_used > 0 ) {
					echo '<td>Credit&#160;Applied&#160;$$</td>';
				}

				?>
				<td>Order&#160;Net&#160;$$</td>
			</tr>
			<tr>
				<td><?=$total_quantity?></td>
				<td><?=condDecimalFormat( $total_gross)?></td>
				<?php 
				if ( $discount_total_true > 0 ) {
					echo '<td>'.condDecimalFormat( $discount_total_true).'</td>';
				}
				if ( $credit_used > 0 ) {
					echo '<td>'.condDecimalFormat( $credit_used).'</td>';
				}
				?>
				<td><?=condDecimalFormat( ($total_gross - $discount_total_true - $credit_used))?></td>
			</tr>
		</table>
	</td>
	<td  class="highlighted text_right">Subtotal</td><td VALIGN="TOP" align="right">
<?php 
$subtotal = condDecimalFormat( $subtotal);
echo $subtotal;
$email_str .= "Subtotal $" . $subtotal ."\n";
?></td></tr>

<?php 
	if ( $discount_pct && $discount_pct > 0 ) {
		echo '<tr><td  class="highlighted text_right">Discount</td><td VALIGN="TOP" align="right">';
			echo condDecimalFormat( $discount_total);
		echo '</td></tr>';
	}

	if($discount != "" && $discount > 0) {
		?>
		<tr><td  class="highlighted text_right">Additional Discount</td><td VALIGN="TOP" align="right">
		<?php
			echo $discount;
		$email_str .= "Discount " . $discount . "\n";
		?>
		</td></tr>
		<?php 
	} elseif($discount == "") { 
		$discount = "0.00"; 
	}

	if($credit_used != "" && $credit_used > 0) {
		?>
		<tr><td  class="highlighted text_right">Credit Applied</td><td VALIGN="TOP" align="right">
		<?php
			echo '-'.$credit_used;
		$email_str .= "Credit Applied -" . $credit_used . "\n";
		?>
		</td></tr>
		<?php 
	}
?>
<tr><td  class="highlighted text_right">S&H</td><td VALIGN="TOP" align="right">
<?php 
echo $shipping;
$email_str .= "Shipping ". $shipping . "\n";
?>
</td></tr>
<tr><td  class="highlighted text_right">Grand&#160;Total</td><td VALIGN="TOP" align="right">
<?php

$order_total = condDecimalFormat( $order_total);

echo $order_total;
$email_str .= "Total $".$order_total."\n\n\n";
?>
</td></tr>
</table>
<br />	

<?php
/*
	$tmp_yes = 0;
	$query = "SELECT sku, quantity, price, name, shipped_out FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($line["shipped_out"] == "0") {
			$tmp_yes = 1;
		}
	}
	mysql_free_result($result);

	if($tmp_yes == 1) {

		echo '<span class="smaller">Back Ordered Items (will ship separately):</span>';
		$email_str .= "Back Ordered Items (will ship separately):\n";
		
		echo '<table width="100%" cellpadding="3" cellspacing="0" class="price_chart" border="1" bordercolor="#000000">';
		echo '<tr><th align="left">Description</th><th>Quantity</th><th>Price</th><th align="right">Extension</th></tr>';
		$subtotal = 0;
		$query = "SELECT sku, quantity, price, name, shipped_out FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($line["shipped_out"] == "0") {
				echo "<tr><td VALIGN=\"TOP\">";
				$tmp_sku = $line["sku"];
			
				//find product url
				$query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
				$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				   foreach ($line2 as $col_value2) {
						$url = "$col_value2";
						$url = substr($url, 1);
						$url = $base_url ."store" . $url;
				   }
				}
				mysql_free_result($result2);

				echo "<a href=\"$url\">";
				echo $line["name"];
				$email_str .= $line["name"] . " SKU: " . $tmp_sku . "       ";
			
				echo "</a><br><font size=\"-1\">SKU: ";
				echo $tmp_sku;
				echo "</font></td><td align=\"center\" VALIGN=\"TOP\">";
				$tmp_quantity = $line["quantity"];
				echo $tmp_quantity;
				$email_str .= $tmp_quantity . "      ";
			
				echo "</td><td align=\"center\" VALIGN=\"TOP\">$";
				$tmp_price = $line["price"];
			$tmp_price = condDecimalFormat( $tmp_price);
			echo $tmp_price;
			$email_str .= "$" . $tmp_price . "   ";
			
			echo "</td><td align=\"right\" VALIGN=\"TOP\">$";
			$tmp_subtotal = $line["quantity"] * $tmp_price;
			$tmp_subtotal = condDecimalFormat( $tmp_subtotal);
			echo $tmp_subtotal;
			$email_str .= "$" . $tmp_subtotal . "\n";
			
			echo "</td></tr>\n";
			
			$subtotal = $subtotal + $tmp_subtotal;
			}
		}
		echo '</table>';
		mysql_free_result($result);
	}

mysql_close($dbh);
*/
?>
</td></tr>
<tr><td>
<br />
Note: All charges will appear as <?php echo $company_name; ?>, the company that produces <?php echo $product_name; ?>.</font></td></tr>
</table>

<center>
	<div id="noteWrapper" class="no_print text_left" style="width:677px">
		<label for="addNote">Additional note: <input type="checkbox" id="addNote" /></label>
		<div id="noteArea" class="no_display">
			<textarea style="height:100px" cols="70" id="noteArea"></textarea>
		</div>
	</div>
</center>
<br />
<?php
if ( $pay_type !="cc" && $pay_type !="cod" && $pay_type !="scd" ) {
?>
	<span class="smaller" id="printHelp">If Print Preview shows that this section does not line up with page perforations, try adding space with the arrows below:
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td width="25">
					<img id="arrow_minus" class="hand" src="<?php echo $current_base.'images/arrow_left.gif'; ?>" />
				</td>
				<td style="background-color: #eeeeee; width: 600px">
					<img id="slider" src="<?php echo $current_base.'images/slide_button.gif'; ?>" />
				</td>
				<td align="right" width="25">
					<img id="arrow_plus" class="hand" src="<?php echo $current_base.'images/arrow_right.gif'; ?>" />
				</td>
			</tr>
		</table>
	</span>

	<div id="remit_padding">&#160;</div>
	<table border="0" cellspacing="0" cellpadding="0" id="remittance" style="text-align:left; position: relative; width: 626px;">
		<tr>
			<!--<td style="background:transparent url(<?=$current_base?>images/ellipse.gif) repeat top left !important; height: 10px;"></td>-->
			<td id="ellipses"><img src="<?=$current_base?>images/ellipse.gif" /></td>
		</tr>
		<tr><td>&#160;</td></tr>
		<tr valign="bottom">
			<td>
					REMITTANCE
					<table width="100%"><tr><td width="60%">
						<table width="100%">
							<tr>
								<td class="bold"><i>Customer&#160;ID:</i></td>
								<td><?=$store_name?></td>
							</tr>
							<tr>
								<td class="bold"><i>Due Date:</i></td>
								<td>
									<?php 
									$due_date = date( "m/d/Y", strtotime($order_date) );

									if ( beginsWith($pay_type,'n') ) {//net* gets that many days added
										$days_to_add = substr($pay_type, 1);
									
										$timeStmp = strtotime($due_date) + $days_to_add * 24 * 60 * 60; //generate a timestamp for the new date
										$due_date = gmdate ('m/d/Y', $timeStmp);
									}

									echo $due_date;
									?>
								</td>
							</tr>
							<tr>
								<td class="bold"><i>Amount&#160;Due:</i></td>
								<td><?=$order_total?></td>
							</tr>
							<tr>
								<td class="bold"><i>Amount&#160;Enclosed:</i></td>
								<td></td>
							</tr>
						</table>
					</td>
					<td style="border: 1px solid #000; padding:2px; padding-left: 10px;">
						Mail Payment to:<br />
						<?php
							echo $company_name.'<br />';
							echo $company_address.'<br />';
							echo $company_city_state_zip.'<br />';
						?>
					</td></tr></table>
				</div>
			</td>
		</tr>
	</table>
<?php
}

if ( !$_REQUEST["show_only"] ) {
    $email_str .= "\n";
    $email_str .= "Note: All charges will appear as $company_name, the company that produces $product_name.\n\n\n";

    $email_subj = "Your " . $website_title . " Order has Shipped";
    $email_from = "FROM: $site_email";
    mail($bill_email, $email_subj, $email_str, $email_from);

	// Just wanted notice to be sent, so send back to referrer
	header("Location: ".$_SERVER["HTTP_REFERER"]."?wholesale_receipt_id=" . $wholesale_receipt_id);
	exit;
}
?>

</div>
</center>
</body>
</html>
