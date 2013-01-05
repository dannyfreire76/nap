<?php
function getNextUserID() {
	global $dbh_master;

	$queryForUser = "INSERT INTO users SET user_id=NULL";
	$resultForUser = mysql_query($queryForUser, $dbh_master) or die("Query failed : " . mysql_error());
	$new_user_id = mysql_insert_id($dbh_master);
	return $new_user_id;
}

function promoteNextRecurringOrders() {
	global $merchant_url, $merchant_username, $merchant_password, $site_email, $company_phone, $website_title, $company_name, $company_address, $company_city_state_zip, $product_name;
	getMerchantCreds();
	$promoteRecurringStr = "";

	$today = date("Y-m-d");
	$query1 = "SELECT ro.*, members.* FROM recurring_orders ro, members WHERE ro.recurring_active='1' AND ro.member_id=members.member_id";
	$result1 = mysql_query($query1) or die("query1 failed: " . mysql_error());
	while ($LINE1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
		$this_member_id = $LINE1["member_id"];
		$this_payment_profile_id = $LINE1["payment_profile_id"];
		$this_final_order = $LINE1["final_order"];
		$this_customer_profile_id = $LINE1["customer_profile_id"];
		$this_recurring_interval = $LINE1["recurring_interval"];
		$this_recurring_length = $LINE1["recurring_length"];
		$this_recurring_orders_id = $LINE1["recurring_orders_id"];
		$this_original_receipt_id = $LINE1["original_receipt_id"];
		$this_rotating_products = $LINE1["rotating_products"];

		//promote recurring orders with today's date or earlier to complete
		$query2 = "SELECT * FROM receipts WHERE complete!='1' AND pay_type='cc' AND recurring_orders_id='".$this_recurring_orders_id."' AND ordered <= '".$today."'";
		$result2 = mysql_query($query2) or die("query2 failed : " . mysql_error());

		if ( $result2 ) {
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$error_txt = "";
				$transSuccess = false;
				$paymentProfileUsed = false;

				$receipt_id = $line2["receipt_id"];
				$cc_auth_code = $line2["cc_auth_code"];
				$cc_trans_id = $line2["cc_trans_id"];
				$order_number = $line2["user_id"];
				$order_processed_by = $line2["order_processed_by"];
				$ordered = $line2["ordered"];
				$order_number = $line2["user_id"];
				$email = $line2["bill_email"];
				$cc_first_name = $line2["cc_first_name"];
				$cc_last_name = $line2["cc_last_name"];
				$bill_name = $line2["bill_name"];
				$bill_address1 = $line2["bill_address1"];
				$bill_address2 = $line2["bill_address2"];
				$bill_city = $line2["bill_city"];
				$bill_state = $line2["bill_state"];
				$bill_zip = $line2["bill_zip"];
				$bill_country = $line2["bill_country"];
				$total = $line2["total"];
				$cc_num = $line2["cc_num"];
				$cc_exp_y = $line2["cc_exp_y"];
				$cc_exp_m = $line2["cc_exp_m"];
				$bill_phone = $line2["bill_phone"];

				/*Try to actually charge the card in this order:
				1) transactionID (preapproved transaction)
				2) payment profile (card on file)
				After this we have nothing else in the receipt record we can use to make this charge
				*/
				if ( $cc_trans_id ) {//1
					$transResults = postAuthorizeTrans('PRIOR_AUTH_CAPTURE', $merchant_url, $merchant_username, $merchant_password, $total, null, null, null, null, null, null, null, null, null, null, null, null, $cc_trans_id);

					if (  $transResults[2]=="" ) {//no errors
						$transSuccess = true;
					}
					//PRIOR_AUTH_CAPTURE transactions, successul or not, don't change the below
					//$cc_auth_code = $transResults[0];
					//$cc_trans_id = $transResults[1];
				}
				
				if ( !$transSuccess ) {
					if ( $this_payment_profile_id ) {
						$profileTransResp = createCustomerProfileTransactionRequest($this_customer_profile_id, $this_payment_profile_id, $total, 'profileTransAuthCapture', $order_number, $cc_auth_code, $cc_trans_id);

						if ( strtolower($profileTransResp->messages->resultCode)!="ok" ) {
							$error_txt .= $transResults[2]!="" ? $transResults[2]."\n":'';
							$error_txt .= $profileTransResp->messages->message->text."\n";
						} else {
								$directResponse = explode(",", $profileTransResp->directResponse);
								$cc_auth_code = $directResponse[4];
								$cc_trans_id = $directResponse[6];

								$paymentProfileUsed = true;
								$transSuccess = true;
						}
					} else {
						$error_txt .= $transResults[2]."\n";
					}
				}

				if ( !$transSuccess ) {
					$promoteRecurringStr .= "<br />ERROR w/Order Number ".$order_number.": ".$error_txt."<br />";
				} else {
					$promoteRecurringStr .= successfulRecur($receipt_id, $order_number, $this_member_id, $bill_name);
				}

				if($error_txt == "") {//transaction posted successfully
					$query3 = "UPDATE receipts SET complete='1', ";
					
					if ( $this_payment_profile_id && $paymentProfileUsed ) {
						$query3 .= " payment_profile_id='$this_payment_profile_id', ";
					}

					$query3 .= " cc_auth_code='$cc_auth_code', cc_trans_id='$cc_trans_id' WHERE receipt_id='".$receipt_id."'";
					$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());

					if ( $result3 ) {//receipt successfully updated to complete
						checkInventory($receipt_id);

						sendConfirmationEmail($email, $site_email, $receipt_id, $order_number, $company_phone, $website_title, $company_name, $company_address, $company_city_state_zip, $product_name, $total);
					}
				}
			}
		}//END promote recurring orders with today's...
		
		mysql_free_result($result2);

		//EVEN if we didn't write anything just now, make sure the queue still have enough items 		
		//get the last order in this recurring orders series
		$getLastSQL = "SELECT * FROM receipts WHERE recurring_orders_id='".$this_recurring_orders_id."' ORDER BY ordered DESC LIMIT 1";
		$getLastResult = mysql_query($getLastSQL) or die("getLastSQL failed : " . mysql_error());

		if ( $getLastResult ) {
			while ($lineLastResult = mysql_fetch_array($getLastResult, MYSQL_ASSOC)) {
				$last_ordered = $lineLastResult["ordered"];
				$last_receipt_id = $lineLastResult["receipt_id"];

				cloneReceiptsAndItems($last_receipt_id, $this_final_order, $this_recurring_interval, $this_recurring_length, $this_recurring_orders_id, $this_rotating_products);
			}
		}

	}
	mysql_free_result($result1);

	if ( $promoteRecurringStr != "" ) {
		return $promoteRecurringStr;
		//mail($site_email, "Recurring Orders Processed", $promoteRecurringStr, "FROM: ".$site_email);
	}
}

function recurringFormOptions($recurring_interval, $recurring_length, $ordered, $rotating_products) {
	global $website_title;

	$str = '
	Repeat every
	<select name="recurring_interval" id="recurring_interval">
		<option value="2 weeks" '.($recurring_interval=='2 weeks' ? ' selected ':'').' >2 weeks</option>
		<option value="1 month" '.($recurring_interval=='1 month' ? ' selected ':'').' >1 month</option>
		<option value="6 weeks" '.($recurring_interval=='6 weeks' ? ' selected ':'').' >6 weeks</option>
		<option value="2 months" '.($recurring_interval=='2 months' ? ' selected ':'').' >2 months</option>
		<option value="3 months" '.($recurring_interval=='3 months' ? ' selected ':'').' >3 months</option>
	</select>
	&#160;for&#160;
	<select name="recurring_length" id="recurring_length">
		<option value="">infinity</option>
		<option value="1 year" '.($recurring_length=='1 year' ? ' selected ':'').' >1 year</option>
		<option value="6 months" '.($recurring_length=='6 months' ? ' selected ':'').' >6 months</option>
	</select>
	&#160;beginning&#160;
	<input type="text" name="ordered" id="ordered" size="10" maxlength="10" value="'.( $ordered ? $ordered : date('m/d/Y') ).'" /><span style="font-size:11px">(mm/dd/yyyy)</span>'.
	(strpos(strtolower($website_title),'sleepsquares')!==false ? '<br /><input type="checkbox" name="rotating_products" id="rotating_products" value="1" '.($rotating_products ? ' checked="checked"':'').' /> with rotating flavors' : '');

	return $str;
}

//cloneReceiptsAndItems creates future orders based on the $last_receipt_id passed
function cloneReceiptsAndItems($last_receipt_id, $final_order, $recurringInterval, $recurringLength, $recurring_orders_id, $rotating_products, $startDate=null, $maxReceiptsToCreate=12) {

	if ( $final_order==0 ) {//recurring interval without end		
		$query3 = "SELECT count(*) FROM receipts WHERE complete!='1' AND recurring_orders_id='".$recurring_orders_id."'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		$row3 = mysql_fetch_row($result3);
		if ( $row3[0] < $maxReceiptsToCreate) {//there are fewer orders than what we'd like in the queue
			$maxReceiptsToCreate = $maxReceiptsToCreate - $row3[0];//only add enough to get to the max
		} else if ( $row3[0] >= $maxReceiptsToCreate ) {//if there are exactly enough or too many already in the queue, add no more
			$maxReceiptsToCreate = 0;
		}
	}

	if ( $maxReceiptsToCreate > 0 ) {
		//look up items by sku from last receipt b/c customer may have paid a different price
		//and place in array for later use
		if ( $rotating_products==1) {
			$samePriceSQL  = "SELECT sku, name FROM product_skus ps1 WHERE cost IN (SELECT ps2.cost FROM product_skus ps2, receipt_items ri ";
			$samePriceSQL .= " WHERE ri.receipt_id='".$last_receipt_id."' and ri.sku=ps2.sku)";
			$samePriceSQL .= " ORDER BY ps1.prod_id";
			$resultSamePrice = mysql_query($samePriceSQL) or die("samePriceSQL failed : " . mysql_error() ."<br /><br />".$samePriceSQL);

			$samePrice = array();
			//now sort this into an array with cost as key to use later
			while ($priceLine = mysql_fetch_array($resultSamePrice, MYSQL_ASSOC)) {
				$samePrice[ $priceLine["cost"] ][ $priceLine["sku"] ] = $priceLine;
			}
		}

		$now = date("Y-m-d");


		for( $recCnt=1; $recCnt<200; $recCnt++ ) {

			//execute queries first but execute later
			$queryRec = "SELECT * FROM receipts WHERE receipt_id='$last_receipt_id' LIMIT 1";//LIMIT 1 should be completely redundant, but w/e
			$resultRec = mysql_query($queryRec) or die("queryRec failed : $queryRec" . mysql_error());
			
			//add an incomplete receipt with date in the future, cloning the original receipt for most of the data
			while ($lineRec = mysql_fetch_array($resultRec, MYSQL_ASSOC)) {

				$thisOrderDate = date("Y-m-d",strtotime( "+".$recurringInterval, strtotime($lineRec["ordered"]) ));
				
				if ( $recCnt==1 && $startDate ) {
					$thisOrderDate = date("Y-m-d",strtotime( "+".$recurringInterval, strtotime($startDate) ));
				}

				if  ( (!$recurringLength && $recCnt > $maxReceiptsToCreate) ||//more than the max number of receipts for an infinite recurring order
					  ($recurringLength && $thisOrderDate > $final_order)//final_order date passed
					) {
					break 2;
				}

		
				$order_number = getNextUserID();

				$newReceiptSQL = "INSERT INTO receipts SET ";
				foreach($lineRec as $col=>$val) {
					//the variables assigned to these columns don't share the column name or don't get set at all
					if ( $col!='ordered' && $col!='receipt_id' && $col!='created' && $col!='complete' && $col!='user_id' && $col!='cid' && $col!='payment_profile_id' && strpos($col, 'cc_')===false ) {
						$useThisVal = $val;
						if ( isset($$col) ) {//if this variable already exists on this page, use that instead (because it's more current)
							$useThisVal = $$col;
						}

						$newReceiptSQL .= " $col = '".addslashes($useThisVal)."', ";
					}
				}
				$newReceiptSQL .= " ordered='$thisOrderDate', created='$now', user_id='$order_number' ";
				$resultReceiptSQL = mysql_query($newReceiptSQL) or die("newReceiptSQL failed : " . mysql_error() ."<br /><br />".$newReceiptSQL);
				//echo '<br /><br />'.$newReceiptSQL;

				if ( $resultReceiptSQL ) {
					$insertedReceiptID = mysql_insert_id();
					$queryRecItems = "SELECT * FROM receipt_items WHERE receipt_id='$last_receipt_id'";
					$resultRecItems = mysql_query($queryRecItems) or die("queryRecItems failed : $queryRecItems" . mysql_error());				

					while ($lineRecItems = mysql_fetch_array($resultRecItems, MYSQL_ASSOC)) {
						
						if ( $rotating_products==1) {
							//figure out what the next product sent should be
							//tried to write this using next() and reset(), etc., but found it impossible
							foreach($samePrice as $anArrayByPrice) {
								if ( array_key_exists( $lineRecItems["sku"], $anArrayByPrice) ) {//this sku is in this array
									$grabNext = false;
									
									foreach($anArrayByPrice as $thisSku) {
										if ( $grabNext ) {
											$lineRecItems = array_merge($lineRecItems, $thisSku);
											$grabNext = false;
											break 2;
										}

										if ( $lineRecItems["sku"]==$thisSku["sku"] ) {//found this item
											$grabNext = true;
										}
									}

									if ( $grabNext==true ) {//this means we never found the next, so use the first
										foreach($anArrayByPrice as $thisSku) {
											$lineRecItems = array_merge($lineRecItems, $thisSku);
											break;
										}
									}
								}
							}
						}


						$newReceiptItemsSQL = "INSERT INTO receipt_items SET ";
						
						foreach($lineRecItems as $col=>$val) {//iterate over this one item's fields
							//the variables assigned to these columns don't share the column name or don't get set at all
							if ( $col!='ordered' && $col!='receipt_id' && $col!='receipt_item_id' && $col!='created' ) {
								$useThisVal = $val;
								if ( isset($$col) ) {//if this variable already exists on this page, use that instead (because it's more current)
									$useThisVal = $$col;
								}

								$newReceiptItemsSQL .= " $col = '$useThisVal', ";
							}
						}
						$newReceiptItemsSQL .= " created='$now', receipt_id='$insertedReceiptID'";

						$resultReceiptItemsSQL = mysql_query($newReceiptItemsSQL) or die("newReceiptItemsSQL failed : " . mysql_error() ."<br /><br />".$newReceiptItemsSQL);
						//echo '<div style="padding-left:50px"><br />'.$newReceiptItemsSQL.'</div>';
					}
					$last_receipt_id = $insertedReceiptID; 
				}
			}
		
		}//for loop < 200
	}//maxReceiptsToCreate
}

function sendConfirmationEmail($email, $site_email, $receipt_id, $order_number, $company_phone, $website_title, $company_name, $company_address, $company_city_state_zip, $product_name, $total) {
	$email_str = "";
	$query = "SELECT * FROM receipts WHERE receipt_id='$receipt_id'";
	$result = mysql_query($query) or die("sendConfirm Query failed : $query" . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$shipping = $line["shipping"];
		$discount = $line["discount"];

		$ship_state = $line["ship_state"];
		if($line["cc_first_name"] == "" || $line["cc_last_name"] == "") {
			$email_str .= "Dear " . stripslashes($line["bill_name"]) . ",\n\n";
		} else {
			$email_str .= "Dear " . $line["cc_first_name"] . " " . $line["cc_last_name"] . ",\n\n";

		}
		
		$email_str .= "Thank you for your ".$website_title." order. Your Order Confirmation Number and Order Number is " . $order_number . ". Please keep a copy of this email for your records. If you have any questions please call us toll free at ".$company_phone.".\n\n";

		if ( $line["recurring_orders_id"] ) {
			$queryRecur = "SELECT * FROM recurring_orders WHERE recurring_orders_id='".$line["recurring_orders_id"]."'";
			$resultRecur = mysql_query($queryRecur) or die("Query failed : $queryRecur" . mysql_error());
			while ($lineRecur = mysql_fetch_array($resultRecur, MYSQL_ASSOC)) {

				$email_str .= 'This order is set to repeat every '.$lineRecur["recurring_interval"];
				if ( $lineRecur["recurring_length"] ) {
					$email_str .= " for the next ".$lineRecur["recurring_length"];
				}
				$email_str .= ".\n\n";
			}
		}
		
		$email_str .= "Bill To:\n";
		$email_str .= stripslashes($line["bill_name"]) . "\n";
		$email_str .= $line["bill_address1"] . "\n";

		if ($line["bill_address2"]) {
			$email_str .= $line["bill_address2"] . "\n";
		}

		$email_str .= $line["bill_city"] . ", " . $line["bill_state"] . "\n";
		$email_str .= $line["bill_zip"] . ", " . $line["bill_country"] . "\n\n";

		$email_str .= "Ship To:\n";
		$email_str .= stripslashes($line["ship_name"]) . "\n";
		$email_str .= $line["ship_address1"] . "\n";

		if ($line["ship_address2"]) {
			$email_str .= $line["ship_address2"] . "\n";
		}

		$email_str .= $line["ship_city"] . ", " . $line["ship_state"] . "\n";
		$email_str .= $line["ship_zip"] . ", " . $line["ship_country"] . "\n\n";

		if($line["pay_type"] == "cc") {
			$email_str .= "Payment Information:\n";

			if($line["payment_profile_id"]) {
				$email_str .= "Saved Card on file: ID ".$line["payment_profile_id"]."\n\n";
			} else {
				$email_str .= $line["cc_first_name"] . " " . $line["cc_last_name"] . "\n";

				if ($line["cc_type"] == "mc") {
					$email_str .= "Mastercard";
				} elseif ($line["cc_type"] == "vi") {
					$email_str .= "Visa";
				} elseif ($line["cc_type"] == "am") {
					$email_str .= "American Express";
				} elseif ($line["cc_type"] == "di") {
					$email_str .= "Discover";
				}
				$email_str .= "\n";

				$email_str .= $line["cc_num"] . "\n\n";
			}
		} elseif ($line["pay_type"] == "chk") {
			$email_str .= "Payment Information:\n";
			$email_str .= "When paying by Check please print a second copy of your receipt, enclose with your Check and send to:\n";
			$email_str .= $company_name."\n";
			$email_str .= $company_address."\n";
			$email_str .= $company_city_state_zip."\n";
			$email_str .= "[Note: Your order will ship as soon as we receive your Check]\n\n";
		}

		if ($line["delivery"] !== "") {
			$email_str .= "Delivery Information\n";
			$email_str .= $line["delivery"] . "\n\n";
		}

		if ($line["discount_pct"]) {
			$email_str .= "Unit price(s) reflect a ";
			$email_str .= ( $line["discount_pct"] * 100 );
			$email_str .= "% discount\n";
		}
	}

	$subtotal = 0;

	$query = "SELECT receipt_items.sku as sku, receipt_items.quantity as quantity, receipt_items.price as price, receipt_items.name as name FROM receipts, receipt_items WHERE receipts.receipt_id=receipt_items.receipt_id AND receipts.receipt_id='$receipt_id'";
	$resultItems = mysql_query($query) or die("Query failed : $query" . mysql_error());
	while ($line2 = mysql_fetch_array($resultItems, MYSQL_ASSOC)) {
		$tmp_sku = $line2["sku"];

		//find product url
		$query2 = "SELECT url FROM product_skus WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line22 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		   foreach ($line22 as $col_value2) {
			   $url = "$col_value2";
			   $url = substr($url, 1);
			   $url = $base_url . "wc" . $url;
		   }
		}
		mysql_free_result($result2);

		$email_str .= $line2["name"] . " SKU: " . $tmp_sku . "                     ";

		$tmp_quantity = $line2["quantity"];
		if($buy_one_get_one_free == '1') {
			$tmp_quantity = $tmp_quantity * 2;
		}
		$email_str .= $tmp_quantity;

		$tmp_price = $line2["price"];
		$tmp_price = condDecimalFormat( $tmp_price);
		$email_str .= " x $" . $tmp_price . "                     ";

		$tmp_subtotal = $line2["quantity"] * $tmp_price;
		$tmp_subtotal = condDecimalFormat( $tmp_subtotal);
		$email_str .= "Subtotal: $" . $tmp_subtotal . "\n";
		$subtotal = $subtotal + $tmp_subtotal;
	}

	$email_str .= "Sub-Total: $" . condDecimalFormat( $subtotal) ."\n";
	if ( $discount != 0 )  {
		$email_str .= "Additional Discount: -$" . condDecimalFormat( $discount)."\n";
	}

	$email_str .= "Shipping: $".$shipping."\n";

	$total = condDecimalFormat( $total );
	$email_str .= "Total: $" . $total . "\n\n";

	$email_str .= "Note: All charges will appear as ".$company_name.", the company that produces $product_name.\n";


	$email_subj = "Your ".$website_title." Order";
	$email_from = "FROM: ".$site_email;

	mail($email, $email_subj, $email_str, $email_from);

	return $email_str;
}

function checkInventory($receipt_id) {
	$stock_email_str = "";
	$query = "SELECT sku, quantity FROM receipt_items WHERE receipt_id='$receipt_id'";

	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_sku = $line["sku"];
		$tmp_quantity = $line["quantity"];

		$query2 = "UPDATE product_skus SET stock = stock - $tmp_quantity WHERE sku='$tmp_sku'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	}
	mysql_free_result($result);

	$query = "SELECT name, stock, threshold FROM product_skus";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$temp_val1 = $line["stock"] - $line["threshold"];
		if($temp_val1 < 0) {
			//Send Email
			$query2 = "SELECT content, subject, email, emailto FROM inventory_emails WHERE inemails_id='1'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$content = $line2["content"];
				$subject = $line2["subject"];
				$email_tmp = $line2["email"];
				$emailto_tmp = $line2["emailto"];
			}
			mysql_free_result($result2);

			$stock_email_str .= $content;
			$stock_email_str .= "\n\n";
			$stock_email_str .= "Product: ";
			$stock_email_str .= $line["name"];
			$stock_email_str .= " has only ";
			$stock_email_str .= $line["stock"];
			$stock_email_str .= " items remaining in stock. It is time to replenish the inventory ";
			$stock_email_str .= "for this item.";
			$stock_email_str .= "\n\n";

			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($emailto_tmp, $email_subj, $stock_email_str, $email_from);
		}
	}
	mysql_free_result($result);
}

function successfulRecur($receipt_id, $order_number, $member_id, $bill_name) {
	global $base_url;

	$success_str = '<br /><a href="'.$base_url.'admin/orders.detail.php?edit=1&id='.$receipt_id.'&retail=1"  title="edit order">'.$order_number.'</a> for ';
	$success_str.= '<a href="'.$base_url.'admin/members_admin2_edit.php?member_id='.$member_id.'"  title="edit member">'.$bill_name.'</a>';

	return $success_str;
}
?>