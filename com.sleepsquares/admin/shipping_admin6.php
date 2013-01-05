<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include_once("./includes/retailer1.php");

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}


include './includes/wms_nav1.php';

if ( $_REQUEST["wholesale_receipt_id"] ) {
	$main_table = "wholesale_receipt";
	$receipt_id = $_REQUEST["wholesale_receipt_id"];
	$receipt_item_id = $_REQUEST["wholesale_receipt_item_id"];
} else {
	$main_table = "receipt";
	$receipt_id = $_REQUEST["receipt_id"];
	$receipt_item_id = $_REQUEST["receipt_item_id"];
}


$tracking_num = $_REQUEST["tracking_num"];
$dont_ship = $_REQUEST["dont_ship"];
$loop_ship = $_REQUEST["loop_ship"];
$shipper = $_REQUEST["shipper"];
$prod_batch_id = $_REQUEST["prod_batch_id"];

$error_text="";

function checkAllShipped(){
	global $receipt_id, $main_table, $min_global_user_id;
	$all_items_shipped=true;

	$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
	$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
	$line_counter = 0;

	while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
		$thisDBHName = "dbh".$lineSites["site_key_name"];
		global $$thisDBHName;

		$orderUID = getOrderUserID($receipt_id);
		if ( $orderUID!="" ) {
			$queryFirst = "SELECT * FROM ".$main_table."s t1, ".$main_table."_items t2 WHERE t1.user_id='$orderUID' AND t1.receipt_id=t2.receipt_id";
			
			if ( $lineSites["site_url"] != $_SERVER["HTTP_HOST"] ) {
				$queryFirst .= " AND t1.user_id >= ".$min_global_user_id;
			}
		} else {
			$queryFirst = "SELECT * FROM ".$main_table."_items WHERE ".$main_table."_id='$receipt_id'";
		}

		//$queryFirst = "SELECT count(*) AS total_item_count FROM ".$main_table."_items WHERE ".$main_table."_id='$receipt_id'";

		$resultFirst = mysql_query($queryFirst, $$thisDBHName) or die("queryFirst failed: ".mysql_error().'<br />'.$queryFirst);
		while ($lineFirst = mysql_fetch_array($resultFirst, MYSQL_ASSOC)) {
			if ( $lineFirst["shipped_out"]!=1 ) {
				$all_items_shipped=false;
				break;
			}
		}		
	}
	return $all_items_shipped;
}

$all_shipped=false;

if ( !$_REQUEST["show_only"] ) {
    $now = date("Y-m-d H:i:s");

    if($loop_ship != ""){// go to next order chronologically
        $queryThisDate = "SELECT * FROM ".$main_table."s WHERE ".$main_table."_id='$receipt_id'";
        $resultDate = mysql_query($queryThisDate) or die("Query failed : " . mysql_error());
        while ($lineDate = mysql_fetch_array($resultDate, MYSQL_ASSOC)) {
			$thisDate = $lineDate["ordered"];
		}

        $query = "SELECT ".$main_table."_id FROM ".$main_table."s WHERE complete='1' AND shipped='0' AND ".$main_table."_id!='$receipt_id' AND ordered > '".$thisDate."' ORDER BY created LIMIT 1";
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($result)>0 ) {
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$receipt_id = $line["".$main_table."_id"];
			}
		} else { //we must be at the end of the queue so go back to the first transaction
			$queryFirst = "SELECT ".$main_table."_id FROM ".$main_table."s WHERE complete='1' AND shipped='0' AND ".$main_table."_id!='$receipt_id' ORDER BY created LIMIT 1";
			$resultFirst = mysql_query($queryFirst) or die("Query failed : " . mysql_error());
			while ($lineFirst = mysql_fetch_array($resultFirst, MYSQL_ASSOC)) {
				$receipt_id = $lineFirst["".$main_table."_id"];
			}
		}
    }

	if ( $_REQUEST["ship"] || $_REQUEST["ship_all"] ) {
		if (  $tracking_num != "" ) {

			if ( $_REQUEST["ship"] ) {
				$thisDBHName = "dbh".$_REQUEST["item_site"];

				$query = "UPDATE ".$main_table."_items SET shipped_out='1', tracking_num='$tracking_num', shipper='$shipper', prod_batch_id='$batch_selected' WHERE ".$main_table."_item_id='$receipt_item_id'";
				$result = mysql_query($query, $$thisDBHName) or die("Query failed : " . mysql_error());

				$query = "UPDATE ".$main_table."s SET back_ordered='1' WHERE ".$main_table."_id='$receipt_id'";
				$result = mysql_query($query, $$thisDBHName) or die("Query failed : " . mysql_error());


			} else if ( $_REQUEST["ship_all"] ) {
				$orderUID = getOrderUserID($receipt_id);
				$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
				$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
				$line_counter = 0;

				while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
					$thisDBHName = "dbh".$lineSites["site_key_name"];

					if ( $orderUID!="" ) {
						$query = "UPDATE ".$main_table."s t1, ".$main_table."_items t2  SET shipped_out='1', tracking_num='$tracking_num', shipper='$shipper', prod_batch_id='$batch_selected' WHERE t1.user_id='$orderUID' AND t1.receipt_id=t2.receipt_id";
					} else {
						$query = "UPDATE ".$main_table."_items SET shipped_out='1', tracking_num='$tracking_num', shipper='$shipper', prod_batch_id='$batch_selected' WHERE ".$main_table."_id='$receipt_id'";
					}
					$result = mysql_query($query, $$thisDBHName) or die("Query failed: " . mysql_error());
				}

			}

		} else {
			$error_text .= "Please enter a tracking number.";
		}
	}
}

//update if everything has been shipped (might have already been shipped when the user first got to the page, so do this outside of any other condition)
if ( checkAllShipped()  ) {

	$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
	$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
	$line_counter = 0;

	while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
		$thisDBHName = "dbh".$lineSites["site_key_name"];

		$orderUID = getOrderUserID($receipt_id);
		if ( $orderUID!="" ) {
			$query = "UPDATE ".$main_table."s t1, ".$main_table."_items t2 SET back_ordered='0', shipped_date='$now', shipped='1' WHERE t1.user_id='$orderUID' AND t1.receipt_id=t2.receipt_id";
		} else {
			$query = "UPDATE ".$main_table."s SET back_ordered='0', shipped_date='$now', shipped='1' WHERE ".$main_table."_id='$receipt_id'";
		}

		$result = mysql_query($query, $$thisDBHName) or die("Query failed : " . mysql_error());

		$all_shipped = true;
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr valign="top"><td align="left"><font size="2">Order #<b>
<?php

$queryWR = "SELECT * FROM ".$main_table."s WHERE ".$main_table."_id='$receipt_id'";
$resultWR = mysql_query($queryWR) or die("Query failed : " . mysql_error());
while ($lineWR = mysql_fetch_array($resultWR, MYSQL_ASSOC)) {
	if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
		echo $lineWR["user_id"];
	} else {
		echo $lineWR["wholesale_order_number"];
	}

	?>
	</b> is ready to be shipped.</font></td></tr>

	<?php

	if ( $error_text != "" ) {
		echo '<tr><td class="error">'.$error_text.'</td></tr>';
	}

	echo "<tr valign=\"top\"><td align=\"left\" width=\"26%\">";
	echo "<br />Bill To:<br>\n";
	echo stripslashes($lineWR['bill_name']);
	echo "<br>\n";
	echo $lineWR['bill_address1'];
	echo "<br>\n";
	if($lineWR['bill_address2'] != '') {
		echo $lineWR['bill_address2'];
		echo "<br>\n";
	}
	echo $lineWR['bill_city'];
	echo ", ";
	echo $lineWR['bill_state'];
	echo " ";
	echo $lineWR['bill_zip'];
	echo " ";
	echo $lineWR['bill_country'];

	if($lineWR['delivery'] != '') {
		echo "Delivery Instructions: ";
		echo $lineWR['delivery'];
		echo "<br>\n";
	}
	echo "<br /><br />Shipping Cost: $";
	echo $lineWR['shipping'];
	echo "<br>\n";
	echo "Shipping Insurance: ";
	if($lineWR['ship_insurance'] == 1) {
		echo "Yes";
	} else {
		echo "No";
	}
	
	$payType = null;
	switch($lineWR['pay_type']){
		
		case "cc":
		$payType = "Credit Card";
		break;
		
		case "chk":
		$payType = "Check";
		break;
		
		case "cod":
		$payType = "COD";
		break;
		
		case "ona":
		$payType = "On Account";
		break;		
	}
	
	
	
	echo "<br>\n";
	echo "Pay Type: ".$payType;
	echo "<br>\n";
	echo "Order Total: ".$lineWR['total'];
	
	
	echo "</td>";
	echo "<td>";
        echo "<br />";
	echo "Ship To:<br>\n";
	echo stripslashes($lineWR['ship_name']);
	echo "<br>\n";
	echo $lineWR['ship_address1'];
	echo "<br>\n";
	if($lineWR['ship_address2'] != '') {
		echo $lineWR['ship_address2'];
		echo "<br>\n";
	}
	echo $lineWR['ship_city'];
	echo ", ";
	echo $lineWR['ship_state'];
	echo " ";
	echo $lineWR['ship_zip'];
	echo " ";
	echo $lineWR['ship_country'];
	echo "<br>\n";
	if($lineWR['ship_phone'] != '') {
		echo $lineWR['ship_phone'];
		echo "<br>\n";
	}

	echo "</td>";
    echo "</tr>\n";
}
?>

<tr><td>&nbsp;</td></tr>

<tr><td align="left" colspan="2"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Product Name</th><th scope="col">SKU</th><th scope="col">Quantity</th><th scope="col">Shipped</th>
<th scope="col">Tracking Number</th>
<th scope="col">Shipper</th>
<th scope="col">Batch</th>
<th scope="col">&nbsp;</th></tr>

<?php
$orderUID = getOrderUserID($receipt_id);
$querySites = "SELECT * FROM partner_sites ORDER BY CASE WHEN site_url='".$_SERVER["HTTP_HOST"]."' THEN 0 else 1 END";
$resultSites = mysql_query($querySites) or die("Query failed : " . mysql_error());
$line_counter = 0;

while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
	$thisDBHName = "dbh".$lineSites["site_key_name"];

	$query = "";
	if ( $orderUID!="" ) {
		$query = "SELECT * FROM ".$main_table."s t1, ".$main_table."_items t2 WHERE t1.user_id='$orderUID' AND t1.receipt_id=t2.receipt_id";
		if ( $lineSites["site_url"] != $_SERVER["HTTP_HOST"] ) {
			$query .= " AND t1.user_id >= ".$min_global_user_id;
		}
	} else {
		if ( $lineSites["site_url"] == $_SERVER["HTTP_HOST"] ) {
			$query = "SELECT * FROM ".$main_table."_items WHERE ".$main_table."_id='$receipt_id'";
		}
	}

	if ( $query!="") {
		$result = mysql_query($query, $$thisDBHName) or die("Query failed: " . mysql_error());

		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$line_counter++;
			$line_this = $line_counter / 2;
			echo '<FORM name="shipping" Method="POST" ACTION="'.$_SERVER['SCRIPT_NAME'].'" class="wmsform">';
			echo "<tr";
			if(is_int($line_this)) { echo " class=\"d\""; }
			echo "><td>";
			echo $line["name"];
			echo '<img src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineSites['site_url'].'/favicon.ico" align="absmiddle" style="float:right; padding-bottom: 4px;" />';
			echo "</td><td>";
			echo $line["sku"];
			echo "</td><td align=\"center\">";
			echo $line["quantity"];
			echo "</td><td align=\"center\">";
			if($line["shipped_out"] == 0) {
				echo "No";
			} elseif($line["shipped_out"] == 1) {
				echo "Yes";
			}
			echo "</td><input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
			echo $line["".$main_table."_id"];

			if ( strToLower($lineSites["site_url"])==strtolower( $website_title ) ) {
				if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
					$tmp_receipt_id = $line["".$main_table."_id"];
				} else {
					$tmp_wholesale_receipt_id = $line["".$main_table."_id"];
				}
			}

			echo "\"><input type=\"hidden\" name=\"".$main_table."_item_id\" value=\"";
			echo $line["".$main_table."_item_id"];
			echo "\">";
			echo '<input type="hidden" name="item_site" value="'.$lineSites["site_key_name"].'" />';
			echo "<td>";
			if($line["shipped_out"] == 0) {
				echo "<input type=\"text\" name=\"tracking_num\" size=\"40\"></td>";
				echo "<td NOWRAP><select name=\"shipper\">\n";
				echo "<option value=\"USPS\"";
				if($line["shipper"] == "USPS") { echo " SELECTED"; }
				echo ">USPS</option>";
				echo "<option value=\"FEDX\"";
				if($line["shipper"] == "FEDX") { echo " SELECTED"; }
				echo ">FedEx</option>";
				echo "<option value=\"UPS\"";
				if($line["shipper"] == "UPS") { echo " SELECTED"; }
				echo ">UPS</option>";
				echo "<option value=\"DHL\"";
				if($line["shipper"] == "DHL") { echo " SELECTED"; }
				echo ">DHL</option>";
				echo "<option value=\"DLVD\"";
				if($line["shipper"] == "DLVD") { echo " SELECTED"; }
				echo ">Delivered</option>";
				echo "</select>";
				echo "</td>";

				echo '<td>';
					$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE batch_active='1' ORDER BY batch_active DESC, batch_created ASC";
					$resultBatch = mysql_query($queryBatch, $$thisDBHName) or die("Query failed : " . mysql_error());
					if ( mysql_num_rows($resultBatch) > 0 ) {
						echo '<select name="batch_selected" id="batch_selected">';
						echo '<option value=""></option>';
						$batchCnt=0;
						while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
							$batchCnt++;
							$selectedB="";
							if ( $batchCnt==1 ) {
								$selectedB= " selected";
							}
							echo '<option value="'.$lineBatch["prod_batch_id"].'" '.$selectedB.'>'.$lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"].'</option>';
						}
						echo '</select>';
					} else {
						echo 'no batches available';
					}
				echo '</td>';

				echo "<td>";
				echo "<input type=\"submit\" name=\"ship\" value=\"Ship\"></td>";
				echo "</tr>\n";
				echo "</form>";
			} else {
				echo $line["tracking_num"];
				echo "</td><td>";
				if($line["shipper"] == "USPS") { echo "USPS"; }
				if($line["shipper"] == "FEDX") { echo "FedEx"; }
				if($line["shipper"] == "UPS") { echo "UPS"; }
				if($line["shipper"] == "DHL") { echo "DHL"; }
				if($line["shipper"] == "DLVD") { echo "Delivered"; }
				echo "</td>";

				echo '<td>';
					$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE prod_batch_id='".$line["prod_batch_id"]."'";
					$resultBatch = mysql_query($queryBatch, $$thisDBHName) or die("Query failed : " . mysql_error());
					if ( mysql_num_rows($resultBatch) > 0 ) {
						while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
							echo $lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"];
						}
					} else {
						echo 'none selected';
					}
				echo '</td>';
				
				echo "<td>&nbsp;</td>";
				echo "</tr>\n";
				echo "</form>\n";
			}
		}
		mysql_free_result($result);
	}
}//END $querySites

$line_counter++;
$line_this = $line_counter / 2;
echo '<form name="shipping2" Method="POST" ACTION="'.$_SERVER['SCRIPT_NAME'].'" class="wmsform">';

echo "<tr";
if(is_int($line_this)) { echo " class=\"d\""; }
echo ">";

if ( !$all_shipped ) {
	echo "<td colspan=\"4\" align=\"right\"><b>Ship All</b></td>";
	echo "<input type=\"hidden\" name=\"".$main_table."_id\" value=\"";

	if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
		echo $tmp_receipt_id;
	} else {
		echo $tmp_wholesale_receipt_id;
	}

	echo "\"><td><input type=\"text\" name=\"tracking_num\" size=\"40\"></td>";
			echo "<td NOWRAP><select name=\"shipper\">\n";
			echo "<option value=\"USPS\"";
			echo ">USPS</option>";
			echo "<option value=\"FEDX\"";
			echo ">FedEx</option>";
			echo "<option value=\"UPS\"";
			echo ">UPS</option>";
			echo "<option value=\"DHL\"";
			echo ">DHL</option>";
			echo "<option value=\"DLVD\"";
			echo ">Delivered</option>";
			echo "</select> ";

	echo "</td>";

	echo '<td>';
		$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches WHERE batch_active='1' ORDER BY batch_active DESC, batch_created ASC";
		$resultBatch = mysql_query($queryBatch, $$thisDBHName) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultBatch) > 0 ) {
			echo '<select name="batch_selected" id="batch_selected">';
			echo '<option value=""></option>';
			$batchCnt=0;
			while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
				$batchCnt++;
				$selectedB="";
				if ( $batchCnt==1 ) {
					$selectedB= " selected";
				}
				echo '<option value="'.$lineBatch["prod_batch_id"].'" '.$selectedB.'>'.$lineBatch["batch_created_format"].' - '.$lineBatch["batch_desc"].'</option>';
			}
			echo '</select>';
		} else {
			echo 'no batches available';
		}
	echo '</td>';

	echo "<td><input type=\"submit\" name=\"ship_all\" value=\"Ship All\"></td></tr>\n";
} else {
	echo '<td colspan="8" class="text_center error">All items have been shipped</td>';
}

echo '</tr>';

echo "</form>\n";

echo "</table></td></tr>\n";

echo '<FORM name="shipping3" Method="POST" ACTION="./'; 
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo 'shipping_admin3.php';
} else {
	echo 'shipping_admin7.php';
}

echo '" class="wmsform">';
echo "<tr><input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo $tmp_receipt_id;
} else {
	echo $tmp_wholesale_receipt_id;
}
echo "\">";
echo '<input type="hidden" name="show_only" value="1" />';
echo "<td align=\"right\" colspan=\"2\"><input type=\"submit\" name=\"print_receipt\" value=\" View & Print Invoice \"></td></tr>\n";
echo "</form>\n";

if ( !strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo "<FORM name=\"shipping3\" Method=\"POST\" ACTION=\"./shipping_ws_packing.php\" class=\"wmsform\">\n";
	echo "<tr><input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
	if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
		echo $tmp_receipt_id;
	} else {
		echo $tmp_wholesale_receipt_id;
	}
	echo "\"><td align=\"right\" colspan=\"2\"><input type=\"submit\" name=\"packing_slip\" value=\" View & Print Packing Slip \"></td></tr>\n";
	echo "</form>\n";
}

echo '<FORM name="shipping3" Method="POST" ACTION="./';
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo 'shipping_admin3.php';
} else {
	echo 'shipping_admin7.php';
}
echo '" class="wmsform">';

echo "<tr><input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo $tmp_receipt_id;
} else {
	echo $tmp_wholesale_receipt_id;
}
echo "\"><td align=\"right\" colspan=\"2\"><input type=\"submit\" name=\"print_receipt\" value=\" Notify Recepient of Shipment \"></td></tr>\n";
echo "</form>\n";


$query = "SELECT notes FROM ".$main_table."s WHERE ".$main_table."_id='$receipt_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["notes"] != "") {
		echo "<tr><td align=\"left\">";
		echo "<b>Notes:</b> ";
		echo stripslashes($line["notes"]);
		echo "</td></tr>\n";
	}
}
mysql_free_result($result);

echo '<FORM name="shipping4" Method="POST" ACTION="'.$_SERVER['SCRIPT_NAME'].'" class="wmsform">';
echo "<input type=\"hidden\" name=\"".$main_table."_id\" value=\"";
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo $tmp_receipt_id;
} else {
	echo $tmp_wholesale_receipt_id;
}
echo "\">";
echo '<td align="left" colspan="2"><input type="button" name="back_to_q" value="Back To Queue" onClick="window.location=\'';
if ( strpos($_SERVER['SCRIPT_NAME'], "admin2") ) {
	echo 'shipping_admin4.php';
} else {
	echo 'shipping_admin5.php';
}

echo '\'"></td></tr>';

echo "<td align=\"left\" colspan=\"2\"><input type=\"submit\" name=\"loop_ship\" value=\"View Next Order to Ship\"></td></tr>\n";
echo "</form>\n"
?>
</table>
</font></td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>