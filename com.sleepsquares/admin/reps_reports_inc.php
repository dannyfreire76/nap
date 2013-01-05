<?php
include_once ($base_path.'includes/wc1.php');

$rep_start_date = "";
$this_rep_id = "";

if ( strpos($URL, '/admin') ) {
	$in_admin = true;
}
else {
	$in_admin = false;
}

if ( $in_admin ) {//only for admin site
	if ( $_POST["selected_rep"] ) {
		$this_rep_id = $_POST["selected_rep"];

		$queryRep = "SELECT start_date from reps WHERE rep_id='".$this_rep_id."'";
		$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
		while ($lineRep = mysql_fetch_array($resultRep, MYSQL_ASSOC)) {
			$rep_start_date = $lineRep["start_date"];
		}
	}
	else {
		$queryRep = "SELECT min(start_date) AS start_date from reps WHERE status=1";
		$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
		while ($lineRep = mysql_fetch_array($resultRep, MYSQL_ASSOC)) {
			$rep_start_date = $lineRep["start_date"];
		}
	}
}
else {//for when rep is logged in
	$rep_start_date = $_SESSION["rep_info"]["start_date"];
	$this_rep_id = $_SESSION["rep_id"];
}

$startyr = date("Y", strtotime($rep_start_date));
$startmo = date("m", strtotime($rep_start_date));
$date2 = "AND wholesale_receipts.ordered >= '".$startyr."-".$startmo."-01 00:00:00'";
if ( $_POST["report_date"] ) {
	$date = $_POST["report_date"];
	$date2 = "";
	if($date != "") {
//		$monthyear = split("\|", $date);				$monthyear = split("/", $date);		
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

		$date2 = "AND wholesale_receipts.ordered >= '".$query_year."-".$query_month."-01 00:00:00' AND wholesale_receipts.ordered <= '".$query_year_max."-".$query_month_max."-01 00:00:00' ";
	}
}

$store_type_criteria = " AND retailer.store_type=1";//default to normal retailers
if ( $_POST["store_type"] ) {
	$store_type = $_POST["store_type"];
	$store_type_criteria = " AND retailer.store_type=".$store_type;
}

$commCnt = 0;
if ( $_SESSION["rep_info"]["rep_type_id"]!=$_SESSION["rep_info"]["max_rep_type_id"] || $in_admin ) {//lowest level reps (indicated by highest sequence) or admin gets to see commissions
	$queryCnt = "SELECT count(*) AS count FROM wholesale_commissions, wholesale_receipts, retailer WHERE commission_earned > 0 AND wholesale_commissions.wholesale_order_number=wholesale_receipts.wholesale_order_number AND wholesale_receipts.retailer_id=retailer.retailer_id ".$date2.$store_type_criteria;
	if ( $this_rep_id ) {
		$queryCnt .= " AND rep_id='".$this_rep_id."'";
	}
	$resultCnt = mysql_query($queryCnt) or die("Query failed : " . mysql_error());
	while ($lineCnt = mysql_fetch_array($resultCnt, MYSQL_ASSOC)) {
		$commCnt = $lineCnt["count"];
	}
}

$query = "SELECT wholesale_receipts.wholesale_order_number AS OrderNum, ";
if ( !$this_rep_id ) {
	$query .= " reps.*, ";
}

$query .= " wholesale_receipts.*, retailer.*, wholesale_commissions.*, DATE_format(comm_paid, '%m/%d/%Y') as comm_paid_format FROM wholesale_receipts, retailer ";

if ( !($this_rep_id && $in_admin) ) {
	$query .= " LEFT ";
}

$query .= " JOIN wholesale_commissions ON wholesale_commissions.wholesale_order_number=wholesale_receipts.wholesale_order_number ";
if ( $this_rep_id ) {
	$query .= " AND wholesale_commissions.rep_id='".$this_rep_id."' ";
}
else {
	$query .= " LEFT JOIN reps ON reps.rep_id=wholesale_commissions.rep_id ";
}
$query .= " WHERE wholesale_receipts.retailer_id=retailer.retailer_id AND wholesale_receipts.complete='1' ".$date2.$store_type_criteria;

$query .= " AND wholesale_receipts.retailer_id IN ( ";
$query .= "SELECT retailer.retailer_id FROM retailer, reps_areas WHERE reps_areas.state=retailer.state ";
if ( $this_rep_id ) {
	if ( $in_admin || $store_type!=2 ) {//only add it to query when in admin or we're not looking at distributors (i.e. when rep logged in and looking at distributors, don't add this to the query so they get all)
		$query .= " AND reps_areas.rep_id='".$this_rep_id."' ";
	}
}

$query .= " AND retailer.city LIKE reps_areas.city AND TRIM(LEADING '0' FROM retailer.zip) LIKE TRIM(LEADING '0' FROM reps_areas.zip) ";


$query .= " ) ";
$query .= " AND retailer.retailer_id IN (SELECT retailer_id FROM retailer_rep_types)";
//echo $query;
$result = mysql_query($query) or die("Query failed : " . mysql_error());

echo '<form id="rep_reports" action="'.$currentFile.'" method="POST">';

if ( $in_admin ) {//only for admin site
?>
	Select a Rep: <select name="selected_rep" onChange="submit()">
		<option value="">ALL</option>
		<?php
		$query2 = "SELECT * FROM reps";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo '<option value="'.$line2["rep_id"].'"';
			if ( $this_rep_id==$line2["rep_id"] ) {
				echo ' selected ';
			}
			echo ' >';
			echo $line2["first_name"].' '.$line2["last_name"];
			echo '</option>';
		}
		?>
	</select>
	&#160;&#160;
<?php
}
?>
Select a Month: <select name="report_date" onChange="submit()">
<option value="">All</option>

<?php
	$startyr = date("Y", strtotime($rep_start_date));
	$startmo = date("m", strtotime($rep_start_date));
	$thisYr = date("Y");
	$thisMo = date("m");

    for ( $y=$startyr; $y<=$thisYr; $y++  ) {//start from the year the rep started
        for ( $m=1; $m < 13; $m++  ) {
			if ( ($y==$startyr && $m>=$startmo && !($startyr==$thisYr && $m>$thisMo) ) || ($y>$startyr && $y<=$thisYr && $m<=$thisMo) ) {
				echo '<option value="'.$m.'|'.$y.'"';
				if ( $date==$m.'|'.$y ) {
					echo ' selected';
				}
				echo '>'.sprintf("%02d", $m).'/'.$y.'</option>';
			}
        }
    }
?>
</select>
&#160;&#160;

<?php
if ( $_SESSION["rep_info"]["rep_type_id"]==$_SESSION["rep_info"]["min_rep_type_id"] || $in_admin ) {//only the highest level (indicated by lowest sequence) or admin gets to see distributors
?>
	Select a Store Type: <select name="store_type" onChange="submit()">
		<?php
		$query3 = "SELECT * FROM store_types";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
			echo '<option value="'.$line3["store_types_id"].'"';
			if ( $store_type==$line3["store_types_id"] ) {
				echo ' selected ';
			}
			echo ' >';
			echo $line3["store_types_desc"];
			echo '</option>';
		}
		?>
	</select>
<?php
}
?>
<br /><br />
	<?php
	if ( mysql_num_rows($result)>0 ) {
	?>
	<span class="error">funds not received</span>
	<br />
	<?php
		if ( $commCnt>0 ) {
			echo '<span class="error3">* commission not yet paid</span>';
		}
	?>
	<table class="report_table maintable" cellspacing="0" cellpadding="0">
		<tr>
			<th align="right">
				Order Number
			</th>
		<?php
			if ( !$this_rep_id ) {
				echo '
				<th align="center">
					Rep
				</th>
				';
			}
		?>
			<th align="center">
				Date
			</th>
			<th align="center">
				Store Name
			</th>
			<th align="right">
				Total (minus shipping)
			</th>
			<?php
				if ( $commCnt>0 ) {
					echo '
					<th align="right">
						Commission %
					</th>
					<th align="right">
						Commission Earned
					</th>
					<th align="right">
						Commission Paid
					</th>';
				}
			?>
		</tr>
		<?php
		$rowcnt = 0;
		$comm_total = 0;
		$sales_total = 0;

		$orderIDS = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ( !in_array( $line["OrderNum"], $orderIDS ) ) {
				$sales_total += ($line["total"]*1 - $line["shipping"]*1);
			}

			$orderIDS[] = $line["OrderNum"];

			if ( $line["comm_paid"]!=0 ) {
				$comm_total += $line["commission_earned"];
			}
			
			$rowcnt++;
			echo '
				<tr class="';
			$rclass="odd";
			if ( $rowcnt % 2==0 ) {
				$rclass='even';
			}
						
			if ( $line["funds_received"]==0 ) {
				echo ' error ';
			}

			echo $rclass.'" valign="top">
					<td align="right">
						<a href="javascript:void(0)" onClick="$(\'#orderDet_'.$line["OrderNum"].$line["rep_id"].'\').slideToggle(300); $(this).parents(\'td:first\').toggleClass(\'override\')">'.$line["OrderNum"].'</a>
					</td>';
				if ( !$this_rep_id ) {
						echo '
						<td align="center">
							'.$line["first_name"].' '.$line["last_name"].'
						</td>
						';
					}
					echo '
					<td>
						'.date("m / d / y", strtotime($line["ordered"] )).'
					</td>
					<td align="right"><a href="javascript:void(0)" onClick="$(\'#storeDet_'.$line["OrderNum"].$line["rep_id"].'\').slideToggle(300); $(this).parents(\'td:first\').toggleClass(\'override\')">
						'.stripslashes( $line["store_name"] ).'</a>
						<div class="no_display" id="storeDet_'.$line["OrderNum"].$line["rep_id"].'">
							'.$line["address1"].'<br />
							'.($line["address12"] ? $line["address2"]: '').
							$line["city"].', '.$line["state"].' '.$line["zip"].'<br />
						</div>
					</td>
					<td align="right">
						$'.sprintf( "%01.2f", ($line["total"]*1 - $line["shipping"]*1) ).'
					</td>';
 				if ( $commCnt>0 ) {
					echo '
						<td align="right">
							'.($line["commission_pct"]? $line["commission_pct"].'%':'--').'
						</td>
						<td align="right">';
							if ($line["commission_earned"]) {
								if ($line["comm_paid"]==0) {
									echo '<span class="error3">* </span>';
								}
								echo '$'.$line["commission_earned"];
							} else {
								echo '--';
							}
							
						echo '</td>';
						echo '<td>';
						if ($line["comm_paid"]==0) {
							echo ' --- ';
						} else {
							echo $line["comm_paid_format"];
						}
						echo '</td>';
				}
				echo'
				</tr>
				<tr class="'.$rclass.'" style="border:none">
					<td style="border:none; padding: 0px" colspan="'.($this_rep_id ? '4' : '5').'">
						<div id="orderDet_'.$line["OrderNum"].$line["rep_id"].'" class="no_display" style="padding: 5px 5px 7px 0px;">
							';
							$query2 = "SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id='".$line["wholesale_receipt_id"]."'";
							$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
							//echo $query2;
							echo '<table class="no_borders" align="right">';
							while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
								echo '<tr>';
								echo '<td>'.$line2["name"].'&#160;&#160;&#160;&#160;</td>';
								echo '<td align="right">Price: $'.condDecimalFormat($line2["price"]).'&#160;&#160;&#160;&#160;</td>';
								echo '<td align="right">Qty: '.$line2["quantity"].'&#160;&#160;&#160;&#160;</td>';
								echo '<td align="right">Subtotal: $'.condDecimalFormat( ($line2["quantity"] * $line2["price"]) ).'</td>';
								echo '<tr>';
							}

							if ( $line["discount"]!=0 ) {
								echo '<tr><td align="right" colspan="4">Additional Discount: -$'.condDecimalFormat( $line["discount"] ).'</td></tr>';
							}

							if ( $line["special_discount"]!=0 ) {
								echo '<tr><td align="right" colspan="4">Special Discount: -'.$line["special_discount"].'%</td></tr>';
							}

							echo '<tr><td colspan="2">';
							echo 'Payment Type: '; 
							if($line["pay_type"] == "cc") { echo "Credit Card"; }
							if($line["pay_type"] == "chk") { echo "Check"; }
							if($line["pay_type"] == "csh") { echo "Cash"; }
							if($line["pay_type"] == "scd") { echo "Secure COD"; }
							if($line["pay_type"] == "cod") { echo "COD"; }
							if($line["pay_type"] == "ona") { echo "On Account"; }
							if($line["pay_type"] == "n15") { echo "Net 15"; }
							if($line["pay_type"] == "n30") { echo "Net 30"; }
							if($line["pay_type"] == "n60") { echo "Net 60"; }
							if($line["pay_type"] == "n90") { echo "Net 90"; }
							echo '</td><td colspan="2">
								<form action="';
								if ( strpos($URL, '/admin') ) {
									echo 'shipping_admin7.php';
								} else {
									echo 'rep_invoice.php';
								}
								
								echo '" method="post" target="_blank">
									<input type="hidden" value="'.$line["wholesale_receipt_id"].'" name="wholesale_receipt_id"/>
									<input id="show_only" type="hidden" value="1" name="show_only"/>
									<input type="submit" value="view invoice" style="font-size: 11px" />
								</form></td></tr></table>';
							echo	'
						</div>
					</td>
					<td colspan="2" style="border:none; padding: 0px"></td>
				</tr>
			';
		}
		$comm_total = condDecimalFormat( $comm_total);
		$sales_total = condDecimalFormat( $sales_total);
		
		echo '
			<tr class="report_totals bold">';
		if ( !$this_rep_id ) {
			echo '<td>&#160;</td>';
		}
		echo '
				<td colspan="3" align="right">
					Total
				</td>
				<td align="right">
					$'.$sales_total.'
				</td>';
		if ( $commCnt>0 ) {
			echo '
				<td align="right">
					&#160;
				</td>
				<td align="right">
					$'.$comm_total.'
				</td>';
		}
			echo '
			</tr>
		';
		?>
	</table>
	<?php
		} else {
			echo 'No transactions found.';
		}
	?>
</form>