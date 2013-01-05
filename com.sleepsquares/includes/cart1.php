<?php
// BME WMS
// Page: Cart Include
// Path/File: /includes/cart1.php
// Version: 1.1
// Build: 1102
// Date: 12-13-2006
require_once('wc1.php');

function get_cart_total($user_id) {
    global $member_id, $dbh, $dbh_master;
	$subtotal = 0;
	$total_qty = 0;

    $percent_off = 0;
    //if ( strpos($_SERVER["SCRIPT_NAME"], "store/step2.php") ) {
        $query5 = "SELECT discount_code, percent_off FROM discount_codes WHERE status='1'";
        $result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
        while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
            $discount_codes[] = $line5["discount_code"];
            $percent_offs[] = $line5["percent_off"];
        }
        mysql_free_result($result5);
        $discount_codes_count = count($discount_codes);
		$discount_code_match = strtolower($_SESSION["order_info"]["discount_code"]);
        for($i=0;$i<$discount_codes_count;$i++) {
            if($discount_code_match == $discount_codes[$i]) {
                $percent_off = $percent_offs[$i];
            }
        }
        //END find discount code
    //}

    $percent_off = 1 - $percent_off;

	$itm_cnt = 0;
	$queryAll = "SELECT * FROM partner_sites";
	$resultAll = mysql_query($queryAll) or die("Query failed: " . mysql_error());
	while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {
		$thisDBHName = "dbh".$lineAll["site_key_name"];
		global $$thisDBHName;
		$myHandle = $$thisDBHName;
		$query = "SELECT * FROM cart WHERE user_id='$user_id' AND site = '".$lineAll["site_url"]."'";
		$result = mysql_query($query, $dbh_master) or die("Query failed: " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$tmp_qty = $line["quantity"];
			$total_qty += $tmp_qty;
			$tmp_sku = $line["sku"];

			$query2 = "SELECT cost FROM product_skus WHERE sku='$tmp_sku'";
			$result2 = mysql_query($query2, $myHandle) or die("Query failed: " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$tmp_cost = $line2["cost"];
			}
			mysql_free_result($result2);

			$tmp_cost = $tmp_cost * $percent_off;
			$tmp_cost = sprintf("%01.2f", round($tmp_cost,2) );
			$tmp_subtotal = $tmp_qty * $tmp_cost;
			$tmp_subtotal = sprintf("%01.2f", round($tmp_subtotal,2) );
			$subtotal = $subtotal + $tmp_subtotal;
			$in_cart_array[$itm_cnt]['prod_sku'] = $tmp_sku;
			$in_cart_array[$itm_cnt]['prod_quantity'] = $tmp_qty;
			$in_cart_array[$itm_cnt]['prod_price'] = $tmp_cost;
			$in_cart_array[$itm_cnt]['prod_name'] = $line["name"];
			$itm_cnt++;
		}
	}
	mysql_free_result($result);
	$subtotal = sprintf("%01.2f", round($subtotal,2) );
	return array($subtotal, $total_qty, $in_cart_array);
}

function get_wc_cart_total($retailer_id) {
    global $retailer_id, $retailer_status;

	$subtotal = 0;
	$total_qty = 0;

    //find discount code
    $query4 = 'SELECT discount_code FROM retailer WHERE retailer_id ="'.$retailer_id.'"';
    $result4 = mysql_query($query4) or die("Query failed : " . mysql_error());
    while ($line4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
       $discount_code = $line4["discount_code"];
    }
    mysql_free_result($result4);

    $query5 = "SELECT percent_off FROM discount_codes WHERE status='1' AND discount_code='$discount_code'";
    $result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
    while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
        $percent_off = $line5["percent_off"];
    }
    mysql_free_result($result5);

    $percent_off = 1 - $percent_off;
    //END find discount code

    $price_lvl = find_price_lvl_simple($retailer_id, $retailer_status);
	$query = "SELECT quantity, sku FROM wc_cart WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_qty = $line["quantity"];
		$total_qty += $tmp_qty;
		$tmp_sku = $line["sku"];

		//find product cost based on price level
		$query3 = "SELECT wholesale_cost1, wholesale_cost2, wholesale_cost3, dist_cost1, dist_cost2, dist_cost3 FROM product_skus WHERE sku='$tmp_sku'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {

            $query3b = "SELECT cost_field FROM wholesale_price_levels WHERE price_level='$price_lvl'";
            $result3b = mysql_query($query3b) or die("Query failed : " . mysql_error());
            while ($line3b = mysql_fetch_array($result3b, MYSQL_ASSOC)) {
                $cost_field = $line3b["cost_field"];
            }
            $tmp_cost = $line3[$cost_field];
            mysql_free_result($result3b);
        }
        $tmp_cost = $tmp_cost * $percent_off;
		$tmp_cost = condDecimalFormat( $tmp_cost);
        $tmp_subtotal = $tmp_qty * $tmp_cost;
		$subtotal = $subtotal + $tmp_subtotal;
	}
	mysql_free_result($result);
	$subtotal = condDecimalFormat( $subtotal);
	return array($subtotal, $total_qty);
}

function createCartTable($thisSite, $dbhHandle, $readonly=false) {
	global $user_id, $member_id, $dbh_master, $base_secure_url, $URL;
	$percent_off = $_SESSION["order_info"]["percent_off"];
	$str = "";
	$subtotal = 0;
	$total_qty = 0;
	$in_cart = false;
	//$query = "SELECT cart_id, quantity, sku, name FROM cart WHERE (user_id='$user_id' OR (member_id='$member_id' AND member_id!=0)) AND quantity>0 AND site='".$thisSite."'";
	//we don't use member_id anymore because we sync the cookie during login
	$query = "SELECT cart_id, quantity, sku, name FROM cart WHERE user_id='$user_id' AND quantity>0 AND site='".$thisSite."'";
	$result = mysql_query($query, $dbh_master) or die("Query failed: " . mysql_error());
	if ( mysql_num_rows($result)>0 )
	{
		$in_cart = true;
		if ( strtolower($thisSite)!=strtolower($_SERVER["HTTP_HOST"]) ) {
			$str .='<tr class="style3"><td colspan="5"><hr /><br />From our partners at <a href="http://'.$thisSite.(strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ?'/staging':'').'/">'.$thisSite.'</a>';

			$str .='<a href="http://'.$thisSite.(strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ?'/staging':'').'/" style="text-decoration:none; border:0px">&#160;<img src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$thisSite.'/favicon.ico" style="text-decoration:none; border: 0px" align="absmiddle" /></a>:</td></tr>';
		}
		$str .='
		<tr><td align="left"><b>Product</b></td><td class="text_right"><b>Quantity</b></td><td class="text_right"><b>Price</b></td><td class="text_right"><b>Sub-Total</b></td>';

		if (!$readonly) {
			$str .='<td class="text_center"><b>Remove</b></td>';
		}
		$str .='</tr>';

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$str .="<tr><td align=\"left\" VALIGN=\"TOP\">";
				$tmp_sku = $line["sku"];
				$query2 = "SELECT prod_id FROM product_skus WHERE sku='$tmp_sku'";
				$result2 = mysql_query($query2, $dbhHandle) or die("query2 failed: " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$tmp_prod_id = $line2["prod_id"];
				}
				mysql_free_result($result2);
				$str .='<a href="http://'.$thisSite.'/'.(strpos($URL, '/staging') ? "staging/" : "").'store/product.php?prod_id='.$tmp_prod_id.'">';
				$str .= $line["name"];
				$str .="</a><br>SKU: ";
				$str .=$tmp_sku;
				$str .="</td><td class=\"text_right\" VALIGN=\"TOP\">";
				$tmp_quantity = $line["quantity"];
				$total_qty += $tmp_quantity;
				$tmp_cart_id_qty = $line["cart_id"] . "_qty";
				$this_free_prods_arr = array();
				$queryFree = "SELECT * FROM free_products";
				$resultFree = mysql_query($queryFree, $dbhHandle) or die("Query failed : " . mysql_error());
				while ($lineFree = mysql_fetch_array($resultFree, MYSQL_ASSOC)) {
					//TODO: only add to free_prods_arr if it's not already there
					$this_free_prods_arr[] = $lineFree["prod_id"];
				}
				if ( in_array($tmp_prod_id, $this_free_prods_arr) || $readonly ) {
					$str .='<input type="hidden" name="'.$tmp_cart_id_qty.'" size="4" maxlength="4" value="'.$tmp_quantity.'"> '.$tmp_quantity;
				}
				else {
					$str .="<input type=\"text\" name=\"$tmp_cart_id_qty\" size=\"4\" maxlength=\"4\" value=\"$tmp_quantity\" class=\"text_right\">";
				}
				$str .="</td><td class=\"text_right\" VALIGN=\"TOP\">$";
				//find product cost
				$query3 = "SELECT cost FROM product_skus WHERE sku='$tmp_sku'";
				$result3 = mysql_query($query3, $dbhHandle) or die("query3 failed: " . mysql_error());
				while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
				   foreach ($line3 as $col_value3) {
					   $cost = "$col_value3";
				   }
				}
				mysql_free_result($result3);
				if ( !$percent_off ) {
					$percent_off = 1;
				}
				$cost = $cost * $percent_off;
				$cost = sprintf("%01.2f", round($cost, 2) );
				$str .=$cost;
				$str .="</td><td class=\"text_right\" VALIGN=\"TOP\">$";
				$tmp_subtotal = $line["quantity"] * $cost;
				$tmp_subtotal = sprintf("%01.2f", $tmp_subtotal);
				$str .=$tmp_subtotal;
				$str .="</td>";
				if (!$readonly) {
					$str .="<td class=\"text_center\" VALIGN=\"TOP\">";
					$tmp_cart_id_del = $line["cart_id"] . "_del";
					$str .="<input type=\"checkbox\" name=\"$tmp_cart_id_del\" value=\"1\"></td>";
				}

				$str .="</tr>\n";

				$subtotal = $subtotal + $tmp_subtotal;
				$this_cart_items[$tmp_sku]['prod_sku'] = $tmp_sku;
				$this_cart_items[$tmp_sku]['prod_id'] = $tmp_prod_id;
				$this_cart_items[$tmp_sku]['prod_quantity'] = $tmp_quantity;
				$this_cart_items[$tmp_sku]['prod_price'] = $cost;
				$this_cart_items[$tmp_sku]['prod_name'] = $line["name"];
		}

		mysql_free_result($result);
		$str .='
		<tr><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td VALIGN="TOP" class="text_right"><b>Sub-Total</b></td><td VALIGN="TOP" class="text_right"><b>$';
		$subtotal = sprintf("%01.2f", $subtotal);
		$str .=$subtotal;
		$str .='</b></td></tr>';
	}
	else  {
		if ( strtolower($thisSite)==strtolower($_SERVER["HTTP_HOST"]) ) {
			$str .='<div class="style3">Your '.$thisSite.' cart is currently empty.</div>';
		}
	}
	return array("thisSubtotal"=>$subtotal, "thisQty"=>$total_qty, "cartHTML"=>$str, "this_cart_items"=>$this_cart_items, "in_cart"=>$in_cart);
}
?>
