<?php
include 'includes/main1.php';
include 'includes/wc1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
</head>
<body>
<?php
	$queryRep = "SELECT reps.* FROM reps WHERE status='1'";
	$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
	
	$wrCnt = 0;
	while ($lineRep = mysql_fetch_array($resultRep, MYSQL_ASSOC)) {
		echo 'rep_id: '.$lineRep["rep_id"].' - '.$lineRep["first_name"].' '.$lineRep["last_name"].' ('.$lineRep["rep_type_id"].')<br />';
		$this_rep_id = $lineRep["rep_id"];
		
		$startyr = date("Y", strtotime($lineRep["start_date"]));
		$startmo = date("m", strtotime($lineRep["start_date"]));
		$date2 = "AND wholesale_receipts.ordered >= '".$startyr."-".$startmo."-01 00:00:00'";
		if ( $_POST["report_date"] ) {
			$date = $_POST["report_date"];
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

				$date2 = "AND wholesale_receipts.ordered >= '".$query_year."-".$query_month."-01 00:00:00' AND wholesale_receipts.ordered <= '".$query_year_max."-".$query_month_max."-01 00:00:00' ";
			}
		}

		echo '<div style="padding-left:20px">';
		$queryRep2 = "SELECT wholesale_receipts.wholesale_order_number AS OrderNum, wholesale_receipts.*, retailer.* ";
		$queryRep2 .= " FROM wholesale_receipts, retailer ";
		$queryRep2 .= " RIGHT JOIN retailer_rep_types ON retailer_rep_types.rep_type_id=".$lineRep["rep_type_id"]." AND retailer_rep_types.retailer_id=retailer.retailer_id ";		
		$queryRep2 .= " WHERE wholesale_receipts.retailer_id=retailer.retailer_id AND wholesale_receipts.complete='1' ".$date2;
		$queryRep2 .= " AND wholesale_receipts.retailer_id IN ( ";
		$queryRep2 .= " SELECT retailer.retailer_id FROM retailer, reps_areas WHERE reps_areas.state=retailer.state ";
		$queryRep2 .= " AND reps_areas.rep_id='".$this_rep_id."' ";
		$queryRep2 .= " AND retailer.city LIKE reps_areas.city AND TRIM(LEADING '0' FROM retailer.zip) LIKE TRIM(LEADING '0' FROM reps_areas.zip) ";
		$queryRep2 .= " )";
		//echo $queryRep2.'<br />';
		$resultRep2 = mysql_query($queryRep2) or die("Query failed : " . mysql_error());
		echo '<table cellpadding="0" cellspacing="0">';

		while ($lineRep2 = mysql_fetch_array($resultRep2, MYSQL_ASSOC)) {
			$store_type = $lineRep2["store_type"];

			echo '<tr valign="top">';
			echo '<td>OrderNum: '.$lineRep2["OrderNum"].' :: '.stripslashes($lineRep2["store_name"]).' :: '.$lineRep2["state"].'  ('.$lineRep2["retailer_id"].')<br />'.($lineRep2["total"] - $lineRep2["shipping"]).' :: store_type: '.$store_type.'</td>';

		

			$price_vars = find_price_lvl_admin($lineRep2["retailer_id"], $lineRep2["retailer_status"], $lineRep2["wholesale_receipt_id"]);

			$this_price_lvl = $price_vars["comm_price_lvl"];
			$subtotal = $price_vars["subtotal"];

			//get commission pct for each level
			$thisComPct = 0;
			$queryCommLevels = "SELECT * FROM rep_comm_pct WHERE price_level='".$this_price_lvl."' AND rep_id=".$lineRep["rep_id"];
			$resultCommLevels = mysql_query($queryCommLevels) or die("Query failed : " . mysql_error());
			while ($lineCommLevels = mysql_fetch_array($resultCommLevels, MYSQL_ASSOC)) {
				$thisComPct = $lineCommLevels["rep_pct"];
			}

			if ( $thisComPct > 0 ) {
				$commEarned = $subtotal * ($thisComPct/100);
				$commEarned = sprintf("%01.2f", round($commEarned, 2));
				echo '<td>';
				//echo '***** '.$lineRep["rep_id"].' | '.$thisComPct.' | '.$commEarned;

				$queryComm = "INSERT INTO wholesale_commissions (rep_id, wholesale_order_number, commission_pct, commission_earned) VALUES ";
				$queryComm .=" ('".$lineRep["rep_id"]."', '".$lineRep2["OrderNum"]."', ".$thisComPct.", ".$commEarned.")";
				//mysql_query($queryComm) or die("Query failed : " . mysql_error());
	
				$wrCnt++;
				echo '**'.$wrCnt.'** '.$queryComm;

				echo '</td>';

			}
		
		echo '</tr>';

		echo '<tr><td><br /><br /></td></tr>';

		
		}
		echo '</table>';
		echo '</div>';
	}


?>
</body>
</html>
