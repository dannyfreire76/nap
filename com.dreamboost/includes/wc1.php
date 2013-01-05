<?php 
// BME WMS 
// Page: WC Include
// Path/File: /includes/wc1.php
// Version: 1.1
// Build: 1101
// Date: 12-18-2006

function calcAndShowCommission() {
	global $retailer_id;
	global $retailer_status;
	global $user_id;
	global $wholesale_receipt_id;

	if ( $_SESSION["rep_id"] || $user_id ) {//rep_id or admin user
		$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$city = stripslashes($line["city"]);
			$state = $line["state"];
			$zip = $line["zip"];
			$country = $line["country"];
			
			//set $county of retailer based on their address
			$queryCnty = "SELECT county FROM zip_codes WHERE city='".$city."' AND state='".$state."' AND zip='".$zip."'";
			$resultCnty = mysql_query($queryCnty) or die("Query failed : " . mysql_error());
			while ($lineCnty = mysql_fetch_array($resultCnty, MYSQL_ASSOC)) {
				$county = $lineCnty["county"];
			}
			
		}

		//*********** regardless of whether funds have been paid, set commissions (funds_received determines if it actually gets paid, though)
		$queryReps = "SELECT reps.* from reps, reps_areas WHERE reps_areas.rep_id=reps.rep_id AND reps.rep_id IN ( SELECT rep_id FROM reps_areas WHERE '".$state."' LIKE reps_areas.state AND '".$city."' LIKE reps_areas.city ";
		$queryReps .= " AND '".$county."' LIKE reps_areas.county ";
		$queryReps .= " AND '".$country."' LIKE reps_areas.country ";
		$queryReps .= " AND TRIM(LEADING '0' FROM '".$zip."') LIKE TRIM(LEADING '0' FROM '".$zip."') ) ";
		$queryReps .= " AND reps.status='1' AND reps.rep_type_id IN ( SELECT rep_type_id FROM retailer_rep_types WHERE retailer_id=".$retailer_id.") ";
		$queryReps .= " AND reps.rep_id IN (SELECT DISTINCT(rep_id) FROM retailer_type_link rtl, reps_industries ri WHERE rtl.retailer_id=".$retailer_id." AND rtl.retailer_type_id=ri.retailer_type_id) ";
		$queryReps .= " GROUP BY reps.rep_id";
		//echo "queryReps: ".$queryReps;
		$resultReps = mysql_query($queryReps) or die("Query failed : " . mysql_error());
		
		if ( mysql_num_rows($resultReps)>0 ) {
			if ( $_SESSION["rep_id"] || $_SESSION["wc_user"] ) {
				$price_vars = find_price_lvl($retailer_id, $retailer_status);
			} else if ( $user_id ) {
				$price_vars = find_price_lvl_admin($retailer_id, $retailer_status, $wholesale_receipt_id);
			}

			$this_price_lvl = $price_vars["comm_price_lvl"];
			$subtotal = $price_vars["subtotal"];

			//iterate through reps found a few queries above (even if a rep is logged in, we may need to give other reps commission too)
			while ($lineReps = mysql_fetch_array($resultReps, MYSQL_ASSOC)) {
			
				//get commission pct for each level
				$thisComPct = 0;
				$queryCommLevels = "SELECT * FROM rep_comm_pct WHERE price_level='".$this_price_lvl."' AND rep_id=".$lineReps["rep_id"];
				$resultCommLevels = mysql_query($queryCommLevels) or die("Query failed : " . mysql_error());
				while ($lineCommLevels = mysql_fetch_array($resultCommLevels, MYSQL_ASSOC)) {
					$thisComPct = $lineCommLevels["rep_pct"];
				}

				if ( $thisComPct > 0 ) {
					$commEarned = $subtotal * ($thisComPct/100);
					$commEarned = sprintf("%01.2f", round($commEarned, 2));
				
					echo '<input type="hidden" name="comm_calc_'.$lineReps["rep_id"].'" value="'.$lineReps["rep_id"].'|'.$thisComPct.'|'.$commEarned.'" />';

					if ( $lineReps["rep_id"]==$_SESSION["rep_id"] ) {
						echo 'When payment is received, you will earn '.$thisComPct.'% commission, for a total of $'.$commEarned.'.';
					}
				}
			}  
		}
		//***********
	}
}

function get_wc_discount() {
    global $retailer_id;

    $discount_code = "";
    $percent_off = 0;
    $query4 = 'SELECT discount_code FROM retailer WHERE retailer_id ="'.$retailer_id.'"';
    $result4 = mysql_query($query4) or die("Query failed : " . mysql_error());
    while ($line4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
       $discount_code = $line4["discount_code"];
    }
    mysql_free_result($result4);

    $query5 = "SELECT discount_code, percent_off FROM discount_codes WHERE status='1' AND discount_code='$discount_code'";
    $result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
    while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
        $percent_off = $line5["percent_off"];
        $discount_code = $line5["discount_code"];
    }
    mysql_free_result($result5);

    $wc_disc[0] = $discount_code;
    $wc_disc[1] = $percent_off;
    return $wc_disc;
}

function check_wholesale_login(){
	global $retailer_id, $base_url;
    if( !$retailer_id ) {
        header("Location: ".$base_url);
        exit;
    }
}

function check_email_address($retailer_id) {
	$retval = 0;
	$query = "SELECT email FROM retailer WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_email = $line["email"];
		
	}
	mysql_free_result($result);
	if($tmp_email == "") { $retval = 1; }
	else { $retval = -1; }
	return $retval;
}

function find_price_lvl_simple($retailer_id, $retailer_status) {
	$query = "SELECT price_lvl FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$price_lvl = $line["price_lvl"];
	}
	mysql_free_result($result);
	return $price_lvl;
}

function find_price_lvl($retailer_id, $retailer_status) {
	global $price_level_type;

	$tot_qty = 0;
	$query = "SELECT SUM(quantity)as total_q FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tot_qty = $line["total_q"];
	}
	mysql_free_result($result);

	$subtotal = 0;
	$recalc = 0;
	$slw_ct = 0;

	if ( $price_level_type=='1' ) {
		$querySLW = "SELECT slwid, slw_min, slw_max, slw_measure, cost_field, price_level FROM wholesale_price_levels ORDER BY price_level ASC";
		$resultSLW = mysql_query($querySLW) or die("Query failed : " . mysql_error());
		while ($lineSLW = mysql_fetch_array($resultSLW, MYSQL_ASSOC)) {
			if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {//only $retailer_status==1 get the distributor discounts
				$slw_arr[$slw_ct]['min'] = $lineSLW["slw_min"];
				$slw_arr[$slw_ct]['max'] = $lineSLW["slw_max"];
				$slw_arr[$slw_ct]['measure'] = $lineSLW["slw_measure"];
				$slw_arr[$slw_ct]['cost_field'] = $lineSLW["cost_field"];
				$slw_arr[$slw_ct]['price_level'] = $lineSLW["price_level"];
				$slw_ct++;
			}
		}
	}
	else {
		$slw_arr = array();
	}

	$disc_attr = get_wc_discount();
	$discount_code = $disc_attr[0];
	$percent_off = $disc_attr[1];
	$percent_off = 1 - $percent_off;

	$query = "SELECT quantity, sku FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_quantity = $line["quantity"];
		$tmp_sku = $line["sku"];
		
		$query3 = "SELECT * FROM product_skus WHERE sku='$tmp_sku' LIMIT 1";//should only be 1 SKU, but LIMIT 1, just in case
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {

			if ( $price_level_type=='1' ) {
				$units_found = false;
				for( $x=0; $x<count($slw_arr); $x++ ) {
					if ( $slw_arr[$x]['measure']=='units' ) {
						$units_found = true;
						if( $tot_qty >= $slw_arr[$x]['min'] && ($slw_arr[$x]['max']==0 || $tot_qty <= $slw_arr[$x]['max']) ) {
							$cost = $line3[ $slw_arr[$x]['cost_field'] ];
							$cost = $cost * $percent_off;
							$cost = sprintf("%01.2f", $cost);
							$price_lvl = $slw_arr[$x]['price_level'];
						}
					}
				}

				if ( $units_found ) {
					$item_subtotal = $tmp_quantity * $cost;
					$tmp_subtotal = $subtotal + $item_subtotal;
				}else {
					$item_subtotal = $tmp_quantity * $line3["cost"];
					$tmp_subtotal = $subtotal + $item_subtotal;
				}

				if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {
					for( $x=0; $x<count($slw_arr); $x++ ) {
						if ( $slw_arr[$x]['measure']=='dollars' ) {
							if( $tmp_subtotal >= $slw_arr[$x]['min'] ) {
								$cost = $line3[ $slw_arr[$x]['cost_field'] ];
								$cost = $cost * $percent_off;
								$price_lvl = $slw_arr[$x]['price_level'];
								
								$item_subtotal = $tmp_quantity * $cost;
								$tmp_subtotal = $subtotal + $item_subtotal;
							}
						}
					}
				}
			} else if ( $price_level_type=='2' ) {
				$price_lvl = $_SESSION["retailer_store_type"];
				$comm_price_lvl = $_SESSION["retailer_store_type"];

				$queryPL = "SELECT cost_field FROM wholesale_price_levels WHERE price_level='$price_lvl'";
				$resultPL = mysql_query($queryPL) or die("Query failed : " . mysql_error());
				while ($linePL = mysql_fetch_array($resultPL, MYSQL_ASSOC)) {
					$cost = $line3[ $linePL["cost_field"] ];
				}
			}

			//recalculate these, in case cost changed in dollars loop above
			$item_subtotal = $tmp_quantity * $cost;
			$subtotal = $subtotal + $item_subtotal;
		}
		
	}

	if ( $price_level_type=='1' ) {
		//this loop is just for commissions, which only care about the final subtotals (after the final discount has been applied)
		for( $x=0; $x<count($slw_arr); $x++ ) {
			if ( $slw_arr[$x]['measure']=='units' ) {
				$units_found = true;
				if( $tot_qty >= $slw_arr[$x]['min'] && ($slw_arr[$x]['max']==0 || $tot_qty <= $slw_arr[$x]['max']) ) {
					$comm_price_lvl = $slw_arr[$x]['price_level'];
				}
			}else if ( $slw_arr[$x]['measure']=='dollars' ) {
				if( $subtotal >= $slw_arr[$x]['min'] ) {
					$comm_price_lvl = $slw_arr[$x]['price_level'];
				}
			}
		}
	}

	$query = "SELECT cart_id, quantity, sku, price_lvl FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_cart_id = $line["cart_id"];

		if($price_lvl != $line["price_lvl"]) {
			$query4 = "UPDATE wc_cart SET price_lvl='$price_lvl' WHERE cart_id='$tmp_cart_id'";
			$result4 = mysql_query($query4) or die("Query failed : " . mysql_error());	
		}
	}
	mysql_free_result($result);
	return array("subtotal" => $subtotal, "tot_qty" => $tot_qty, "price_lvl" => $price_lvl, "slw_array" => $slw_arr, "comm_price_lvl" => $comm_price_lvl );		
}

function find_price_lvl_admin($retailer_id, $retailer_status, $wholesale_receipt_id) {
	global $price_level_type;
	global $store_type;//from retailer_admin11.php loop thru retailer vars

	$tot_qty = 0;
	$query = "SELECT SUM(quantity)as total_q FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tot_qty = $line["total_q"];
	}
	mysql_free_result($result);

	$subtotal = 0;
	$query = "SELECT total, shipping FROM wholesale_receipts WHERE wholesale_receipt_id='$wholesale_receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$subtotal = ($line["total"] * 1) - ($line["shipping"] * 1);
	}
	mysql_free_result($result);

	$recalc = 0;
    $slw_ct = 0;

	if ( $price_level_type=='1' ) {
		$querySLW = "SELECT slwid, slw_min, slw_max, slw_measure, cost_field, price_level FROM wholesale_price_levels ORDER BY price_level ASC";
		$resultSLW = mysql_query($querySLW) or die("Query failed : " . mysql_error());
		while ($lineSLW = mysql_fetch_array($resultSLW, MYSQL_ASSOC)) {
			if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {//only $retailer_status==1 get the distributor discounts
				$slw_arr[$slw_ct]['min'] = $lineSLW["slw_min"];
				$slw_arr[$slw_ct]['max'] = $lineSLW["slw_max"];
				$slw_arr[$slw_ct]['measure'] = $lineSLW["slw_measure"];
				$slw_arr[$slw_ct]['cost_field'] = $lineSLW["cost_field"];
				$slw_arr[$slw_ct]['price_level'] = $lineSLW["price_level"];
				$slw_ct++;
			}
		}
	} else {
		$slw_arr = array();
	}



    $query = "SELECT * FROM wholesale_receipt_items WHERE wholesale_receipt_id='$wholesale_receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line3 = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_quantity = $line3["quantity"];
		$tmp_sku = $line3["sku"];
		$cost = $line3["price"];

		if ( $price_level_type=='1' ) {
			$units_found = false;
			for( $x=0; $x<count($slw_arr); $x++ ) {
				if ( $slw_arr[$x]['measure']=='units' ) {
					$units_found = true;
					if( $tot_qty >= $slw_arr[$x]['min'] && ($slw_arr[$x]['max']==0 || $tot_qty <= $slw_arr[$x]['max']) ) {
						$price_lvl = $slw_arr[$x]['price_level'];
					}
				}
			}

			if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {
				for( $x=0; $x<count($slw_arr); $x++ ) {
					if ( $slw_arr[$x]['measure']=='dollars' ) {
						if( $subtotal >= $slw_arr[$x]['min'] ) {
							$price_lvl = $slw_arr[$x]['price_level'];
						}
					}
				}
			}       
		} else if ( $price_level_type=='2' ) {
			$price_lvl = $store_type;
			$comm_price_lvl = $store_type;
		}		
	}

	if ( $price_level_type=='1' ) {
		//this loop is just for commissions, which only care about the final subtotals (after the final discount has been applied)
		for( $x=0; $x<count($slw_arr); $x++ ) {
			if ( $slw_arr[$x]['measure']=='units' ) {
				$units_found = true;
				if( $tot_qty >= $slw_arr[$x]['min'] && ($slw_arr[$x]['max']==0 || $tot_qty <= $slw_arr[$x]['max']) ) {
					$comm_price_lvl = $slw_arr[$x]['price_level'];
				}
			}else if ( $slw_arr[$x]['measure']=='dollars' ) {
				if( $subtotal >= $slw_arr[$x]['min'] ) {
					$comm_price_lvl = $slw_arr[$x]['price_level'];
				}
			}
		}
	}

    return array("subtotal" => $subtotal, "tot_qty" => $tot_qty, "price_lvl" => $price_lvl, "slw_array" => $slw_arr, "comm_price_lvl" => $comm_price_lvl );
}

function condDecimalFormat($myValue) {
	$testVal = $myValue * 1000;

	if ( $testVal % 10 == 0 ) {
		$displayVal = number_format( $myValue, 2, ".", "" );//format to 2 places
	} else {
		$displayVal = number_format( $myValue, 3, ".", "" );//format to 3 places
	}
	
	return $displayVal;
}

function recalcAllComms() {
	$queryTrun = "TRUNCATE wholesale_commissions;";
	mysql_query($queryTrun) or die("Query failed : " . mysql_error());


	$queryRep = "SELECT reps.* FROM reps";// WHERE status='1' removed b/c rep might not be active anymore but was at the time of the transaction, so check start and end date below
	$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
	
	$wrCnt = 0;
	while ($lineRep = mysql_fetch_array($resultRep, MYSQL_ASSOC)) {
		//echo 'rep_id: '.$lineRep["rep_id"].' - '.$lineRep["first_name"].' '.$lineRep["last_name"].' ('.$lineRep["rep_type_id"].')<br />';
		$this_rep_id = $lineRep["rep_id"];
		
		$date2 = "";
		$startyr = date("Y", strtotime($lineRep["start_date"]));
		$startmo = date("m", strtotime($lineRep["start_date"]));
		$date2 .= "AND wholesale_receipts.ordered >= '".$startyr."-".$startmo."-01 00:00:00'";

		if ( $lineRep["end_date"] ) {
			$endyr = date("Y", strtotime($lineRep["end_date"]));
			$endmo = date("m", strtotime($lineRep["end_date"]));
			$enddy = date("d", strtotime($lineRep["end_date"]));
			$date2 .= " AND wholesale_receipts.ordered <= '".$endyr."-".$endmo."-".$enddy." 00:00:00'";
		}

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

		//echo '<div style="padding-left:20px">';
		$queryRep2 = "SELECT wholesale_receipts.wholesale_order_number AS OrderNum, wholesale_receipts.*, retailer.* ";
		$queryRep2 .= " FROM wholesale_receipts, retailer ";
		$queryRep2 .= " RIGHT JOIN retailer_rep_types ON retailer_rep_types.rep_type_id=".$lineRep["rep_type_id"]." AND retailer_rep_types.retailer_id=retailer.retailer_id ";		
		$queryRep2 .= " WHERE wholesale_receipts.retailer_id=retailer.retailer_id AND wholesale_receipts.complete='1' ";
		
		$queryRep2 .= $date2;		
		$queryRep2 .= " AND wholesale_receipts.retailer_id IN ( ";// IN
			$queryRep2 .= " SELECT retailer.retailer_id FROM retailer, reps_areas WHERE ";
			$queryRep2 .= " reps_areas.rep_id='".$this_rep_id."' ";
			$queryRep2 .= " AND retailer.country LIKE reps_areas.country ";
			$queryRep2 .= " AND ( ";
				
					$queryRep2 .= " ( "; 
						$queryRep2 .= " retailer.country = 'US' ";
						$queryRep2 .= " AND retailer.city LIKE reps_areas.city ";
						$queryRep2 .= " AND retailer.state LIKE reps_areas.state ";
						//get county from zip_codes
						$queryRep2 .= " AND (SELECT county FROM zip_codes WHERE city=retailer.city AND state=retailer.state AND zip=retailer.zip) LIKE reps_areas.county ";

						$queryRep2 .= " AND TRIM(LEADING '0' FROM retailer.zip) LIKE TRIM(LEADING '0' FROM reps_areas.zip) ";
					$queryRep2 .= " ) "; 

			$queryRep2 .= " OR retailer.country != 'US' ";
			$queryRep2 .= "    ) ";

		$queryRep2 .= " )";// END IN

		//only retailers with rep industry in common
		$queryRep2 .= " AND wholesale_receipts.retailer_id IN ";
		$queryRep2 .= " (SELECT rtl.retailer_id FROM retailer_type_link rtl, reps_industries ri WHERE rtl.retailer_type_id=ri.retailer_type_id AND ri.rep_id='".$this_rep_id."')";

		$resultRep2 = mysql_query($queryRep2) or die("Query failed : " . mysql_error());
		//echo '<table cellpadding="0" cellspacing="0">';

		while ($lineRep2 = mysql_fetch_array($resultRep2, MYSQL_ASSOC)) {
			$store_type = $lineRep2["store_type"];

			$GLOBALS["store_type"] = $lineRep2["store_type"];//find_price_lvl_admin reads this as the global store_type


			//echo '<tr valign="top">';
			//echo '<td>OrderNum: '.$lineRep2["OrderNum"].' :: '.stripslashes($lineRep2["store_name"]).' :: '.$lineRep2["state"].'  ('.$lineRep2["retailer_id"].')<br />'.($lineRep2["total"] - $lineRep2["shipping"]).' :: store_type: '.$store_type.'</td>';

		

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
				//echo '<td>';
				//echo '***** '.$lineRep["rep_id"].' | '.$thisComPct.' | '.$commEarned;

				$queryComm = "INSERT INTO wholesale_commissions (rep_id, wholesale_order_number, commission_pct, commission_earned) VALUES ";
				$queryComm .=" ('".$lineRep["rep_id"]."', '".$lineRep2["OrderNum"]."', ".$thisComPct.", ".$commEarned.")";
				mysql_query($queryComm) or die("Query failed : " . mysql_error());
	
				$wrCnt++;
				//echo '**'.$wrCnt.'** '.$queryComm;

				//echo '</td>';

			}
		
		//echo '</tr>';

		//echo '<tr><td><br /><br /></td></tr>';

		
		}
		//echo '</table>';
		//echo '</div>';
	}
}

function displayPayOptions($selectName, $secure_funds_only=0) {
	$str = '<select name="'.$selectName.'" id="'.$selectName.'">';

	$queryPayOptions = "SELECT * FROM admin_pay_types ORDER BY apt_secured_payment DESC, apt_desc ASC";
	$resultPO= mysql_query($queryPayOptions) or die("Query failed : " . mysql_error());
	while ($linePO = mysql_fetch_array($resultPO, MYSQL_ASSOC)) {
		
		if ( $secure_funds_only!=1 || $linePO["apt_secured_payment"]==1 ) {
			$str .= '<option value="'.$linePO["apt_code"].'" '.($_REQUEST["$selectName"]==$linePO["apt_code"] ? ' selected':'').'>'.$linePO["apt_desc"].'</option>';
		}
		
	}

	$str .= '</select>';

	return $str;
}

function displayPayType($valueUsed) {
	$str = "";

	$queryPayOptions = "SELECT * FROM admin_pay_types WHERE apt_code='".$valueUsed."' LIMIT 1";//LIMIT 1 should be redundant
	$resultPO= mysql_query($queryPayOptions) or die("Query failed : " . mysql_error());
	while ($linePO = mysql_fetch_array($resultPO, MYSQL_ASSOC)) {
		
		$str .= $linePO["apt_desc"];
		
	}

	return $str;
}

?>