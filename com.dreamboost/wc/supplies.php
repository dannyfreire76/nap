<?php
// BME WMS
// Page: Supplies
// Path/File: /wc/supplies.php
// Version: 1.1
// Build: 1102
// Date: 12-27-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

check_wholesale_login();

function get_item_count($retailer_id) {
	$item_count = 0;
	$query = "SELECT quantity FROM wc_cart WHERE retailer_id='$retailer_id'";
	// Add 2nd where clause to filter out supplies from item count sku!=
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_quantity = $line["quantity"];
		$item_count = $item_count + $tmp_quantity;
	}
	mysql_free_result($result);
	return $item_count;
}

function get_item_count_g2p($retailer_id) {//get count of items green through purple
	$item_count = 0;
	$query = "SELECT wc.quantity FROM wc_cart wc, product_skus skus WHERE wc.retailer_id='$retailer_id' AND wc.sku=skus.sku AND (skus.prod_id='1' OR skus.prod_id='2' OR skus.prod_id='3' OR skus.prod_id='6')";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_quantity = $line["quantity"];
		$item_count = $item_count + $tmp_quantity;
	}
	mysql_free_result($result);
	return $item_count;
}

function get_item_count_infinity($retailer_id) {//get count of infinity items
	$item_count = 0;
	$query = "SELECT wc.quantity FROM wc_cart wc, product_skus skus WHERE wc.retailer_id='$retailer_id' AND wc.sku=skus.sku AND skus.prod_id='25'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$tmp_quantity = $line["quantity"];
		$item_count = $item_count + $tmp_quantity;
	}
	mysql_free_result($result);
	return $item_count;
}


if( $_POST["supplies"] == 1 ) {
	$price_lvl = find_price_lvl_simple($retailer_id, $retailer_status);
	$item_count = get_item_count($retailer_id);
    //$item_count_gp = get_item_count_g2p($retailer_id);
    //$item_count_inf = get_item_count_infinity($retailer_id);

    foreach ($_REQUEST as $elem_name => $sku) {
        if ( strpos($elem_name, 'prod_fld_')===0 ) {
			$skuname = $_REQUEST['skuname_'.$sku];
			$prodid = $_REQUEST['prodid_'.$sku];

			$now = date("Y-m-d H:i:s");
    		
            $item_count2 = 1;
            if ($prodid=='5') {
				$item_count2 = floor($item_count * 1.25);
            }

            $querySup = "SELECT * FROM wc_cart WHERE retailer_id='$retailer_id' AND sku='$sku'";
            $resultSup = mysql_query($querySup) or die("Query failed : " . mysql_error());
            if ( mysql_num_rows($resultSup)==0 ) {
                $query = "INSERT INTO wc_cart SET created='$now', retailer_id='$retailer_id', quantity='$item_count2', sku='$sku', name='$skuname', price_lvl='$price_lvl'";
                $result = mysql_query($query) or die("Query failed : " . mysql_error());
            }
        }
    }
	header("Location: " . $base_secure_url . "wc/step1.php?retailer_id=".$retailer_id."&supplies=1");
	exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Online Wholesale Catalog - Supplies</title>

<?php
include '../includes/meta1.php';
?>

</head>
<body>
<div align="center">

<?php
include '../includes/head1.php';

?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><span class="two">Online Wholesale Catalog: Supplies</span></td></tr>

<?php
if($error_txt) { 
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\"><span class=\"error\">$error_txt</span></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left">&#160;</td></tr>
<tr><td align="left">Please select which of the following supplies, provided to you free with your <?php echo $website_title; ?> purchase, you need:<br /><br /></td></tr>
<tr><td align="left">
<table border="0" cellpadding="6" cellspacing="0">
<form action="<?php echo $base_secure_url; ?>wc/supplies.php" method="POST">
<!--
<tr><td>Do you need brochures?</td><td><select name="brochures">
<option value="1"<?php if($brochures == 1) { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<?php if($brochures == 0) { echo " SELECTED"; } ?>>No</option>
</select></td></tr>
<tr><td>Do you need samples?</td><td><select name="samples">
<option value="1"<?php if($samples == 1) { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<?php if($samples == 0) { echo " SELECTED"; } ?>>No</option>
</select></td></tr>
<tr><td>Do you need a catalog?</td><td><select name="catalog">
<option value="1"<?php if($catalog == 1) { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<?php if($catalog == 0) { echo " SELECTED"; } ?>>No</option>
</select></td></tr>
<tr><td>Do you need color posters?</td><td><select name="posters">
<option value="1"<?php if($posters == 1) { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<?php if($posters == 0) { echo " SELECTED"; } ?>>No</option>
</select></td></tr>
-->
<?php

$querySupplies = "SELECT * FROM products WHERE prod_cat_id='6' AND display_in_wc='1'";
$resultSupplies = mysql_query($querySupplies) or die("Query failed : " . mysql_error());
$rowcnt = 0;
while ($lineSupplies = mysql_fetch_array($resultSupplies, MYSQL_ASSOC)) {
    $rowcnt++;
    $row_class = 'odd';
    if ( $rowcnt%2==0 ) {
        $row_class = 'even';
    }
    $tmp_prod_id = $lineSupplies["prod_id"];
    $tmp_only1sku = $lineSupplies["only1sku"];
    $url = ($tmp_prod_id ? $base_url.'store/product.php?prod_id='.$tmp_prod_id : '' );
    echo '<tr valign="bottom" class="'.$row_class.'"><td><a href="'.$url.'" target="_blank">'.$lineSupplies["name"].'</a>:</td><td>';
    
    $querySKU = "SELECT * FROM product_skus WHERE prod_id='$tmp_prod_id'";
    $resultSKU = mysql_query($querySKU) or die("Query failed : " . mysql_error());
    while ($lineSKU = mysql_fetch_array($resultSKU, MYSQL_ASSOC)) {
        $ok = true;
        if ( $lineSKU["sku"]=='1020' ) {
            $inf_count = get_item_count_infinity($retailer_id);
            if ( $inf_count==0 ) {
                $ok=false;
            }
        }
        if ( $ok ) {
			if ( $tmp_only1sku=='1' ) {//user can only order 1 sku from this prod
	            echo '<input type="radio" name="prod_fld_'.$tmp_prod_id.'" id="rad_'.$tmp_prod_id.'_'.$lineSKU["sku"].'" value="'.$lineSKU["sku"].'" /><br />';
			}
			else {
				echo '<input type="checkbox" name="prod_fld_'.$lineSKU["sku"].'" id="prod_fld_'.$lineSKU["sku"].'" value="'.$lineSKU["sku"].'" /><br />';
			}
            echo '<input type="hidden" name="skuname_'.$lineSKU["sku"].'" value="'.$lineSKU["name"].'" />';
			echo '<input type="hidden" name="prodid_'.$lineSKU["sku"].'" value="'.$tmp_prod_id.'" />';
		}
    }    
    
    echo '</td></tr>';
}

?>

<input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">
<input type="hidden" name="supplies" value="1">
<tr><td colspan="2" align="right"><input type="submit" value="Checkout"></td></tr>
</form>
</table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>