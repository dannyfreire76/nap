<?php
// 2/25/2009: Using ship_method_wholesale instead of hardcoded options
// 7/7/2009: Removed active='1' from ship methods query
// 6/8/2011: Originally copied from admin/retailers_admin10.php and store/step1.php.

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include_once '../includes/main1.php';
include_once $base_path.'includes/retailer2.php';
include_once $base_path.'includes/wc1.php';
include "../includes/common.php";

if ( $_REQUEST["calcShipping"]==1 ) {
	include_once('../includes/store_includes.php');
	echo calcShipping($_REQUEST["subtotalForShipping"]);
	exit();
}

//set submitted variables to simple var names with global scope
foreach( $_POST as $n=>$v ){
	//echo '<br />'.$n.' = '.$v;
	$$n = $v;
}

$query = "SELECT * FROM members WHERE member_id='".$member_id."'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$_SESSION["member_name"] = $line["first_name"].' '.$line["last_name"];

	foreach($line as $col=>$val) {
		$_SESSION['shipping_info']["".$col.""] = $val;//required for calcShipping
	}
}

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$query = "SELECT product_line FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';

$retailer_id = $_REQUEST["retailer_id"];
$submit = $_POST["submit"];

foreach($_POST as $fld_name=>$fld_val) {
	if ( strpos($fld_name, "quantity")!==false || strpos($fld_name, "name")!==false || strpos($fld_name, "price")!==false || strpos($fld_name, "total")!==false )  {
		$$fld_name = $fld_val;

		if ( strpos($fld_name, "quantity")!==false ) {
			$thisID = substr( $fld_name, 8 );
			$quantityP[$thisID] = $fld_val;
		} else if ( strpos($fld_name, "name")!==false ) {
			$thisID = substr( $fld_name, 4 );
			$nameP[$thisID] = $fld_val;
		} else if ( strpos($fld_name, "disc_price")!==false ) {
			$thisID = substr( $fld_name, 10 );
			$priceP[$thisID] = $fld_val;
		} else if ( strpos($fld_name, "price")!==false ) {
			$thisID = substr( $fld_name, 5 );
			$priceOrig[$thisID] = $fld_val;
		} else if ( strpos($fld_name, "total")!==false ) {
			$thisID = substr( $fld_name, 5 );
			$totalP[$thisID] = $fld_val;
		}
	}
}

if ( $_POST["submit"] ) {
    $discount_code_vars = split( "\|", $_POST["discount_code"] );
    $discount_code  = $discount_code_vars[0];
    $discount_code_insert = $discount_code;
}


if ($submit != "") {
	//Validate
	$error_txt = "";
	if($quantity1 == "") { $error_txt .= "You must enter a Quantity on the first line.<br>\n"; }
	if($name1 == "") { $error_txt .= "You must select a Product on the first line.<br>\n"; }
	if($price1 == "") { $error_txt .= "You must enter a Price on the first line.<br>\n"; }
	if($total1 == "" || $item_count == "" || $subtotal == "" || $total == "") { $error_txt .= "You must hit the Calculate button before hitting the Save button.<br>\n"; }

	if($error_txt == "") {
	$subtotal = condDecimalFormat( $subtotal);
	$shippingFld = condDecimalFormat( $shippingFld);
	$discount = condDecimalFormat( $discount);
	$total = condDecimalFormat( $total);

	$now = date("Y-m-d H:i:s");

	$query = "INSERT INTO receipts SET created='$now', ordered='$now', complete='0', member_id='$member_id', user_id='".$user_id."', item_count='$item_count', subtotal='$subtotal', shipping='$shippingFld', tax='$tax', discount_code='$discount_code_insert', total='$total', shipping_method='$shipping_method'";
	//echo $query; exit();
	$result = mysql_query($query) or die("Query failed : " . mysql_error() .'<br />'.$query);

	$receipt_id = mysql_insert_id();

	foreach ($quantityP as $num => $val) {
		if ($val != "") {
			$name = $nameP[$num];
			$price = $priceP[$num];
			$orig_price = $priceOrig[$num];
			list($name, $sku) = split('\|', $name);
			$price = condDecimalFormat( $price);

			$now = date("Y-m-d H:i:s");
			$query = "INSERT INTO receipt_items SET receipt_id='$receipt_id', created='$now', sku='$sku', quantity='$val', price='$price', /*orig_price='$orig_price', */name='$name'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
//print_d($result);
		}
	}


	// Send to retailers place order page 2
	header("Location: ".$base_url."admin/members_place_order2.php?receipt_id=".$receipt_id."&member_id=".$member_id);
	exit;
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/wmsform.css">
<script type="text/javascript" src="<?=$current_base?>includes/jquery.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/extend.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/wmsform.js"></script>
<script language="JavaScript">
function updateRow() {
	var index = this.name.match(/\d+/i)[0];//strips number off of name

	var F = $('#form1');

	var thisQuantity = $('#quantity'+index, F).val();
	if ( thisQuantity!=0 || isNaN(thisQuantity) ) {
		var theseVals = $('#name'+index, F).val().split('\|');
		var thisCostPer = theseVals[3];
		
		thisCostPer = (thisCostPer * 1).toFixed(2);

		$('#price'+index, F).val( thisCostPer );
		$('#total'+index, F).val( (thisQuantity * thisCostPer).toFixed(2) );
		

	} else {//clear what was in there for cost
		$('#total'+index, F).val('');
	}
	
	reCalc();
}
	
	$(function() { //on doc ready
		$('#apply_credit').each(function() {
		if ($(this).is(':checked')) {
			$('#credit').css('color', 'navy');
		}

		$(this).click(function() {
			if ($(this).is(':checked')) {
			$('#credit').css('color', 'navy');
			} else {
			$('#credit').css('color', '#000');
			}
			reCalc();
		});
		});
		$('.product').blur(updateRow).change(updateRow);
		$('.quantity').blur(updateRow).change(updateRow);

		$('#shipest').click(function(){
			if ( parseInt( $('#subtotal').val() ) > 0) {
				var ship_url = "members_place_order.php?calcShipping=1&subtotalForShipping="+$('#subtotal').val();
				$.get(ship_url, function(resp) {
					$('#shippingFld').val( resp );
					reCalc();
				});
			}
		});

	});

function r2(n) {

    ans = n * 1000
    ans = Math.round(ans / 10) + ""
    while (ans.length < 3) {
	ans = "0" + ans
    }
    len = ans.length
    ans = ans.substring(0, len - 2) + "." + ans.substring(len - 2, len)
    return ans
}

function r3(n) {
    ans = n * 10000
    ans = Math.round(ans / 10) + "";
    while (ans.length < 4) {
	ans = "0" + ans;
    }

    len = ans.length
    ans = ans.substring(0, len - 3) + "." + ans.substring(len - 3, len)
    return ans
}

function reCalc() {
    var disc_pct = 1
    if ($('#discount_code').val() != '') {
	var code_vals = $('#discount_code').val().split("|");
	var dcode = code_vals[0];
	var disc_pct = code_vals[1];
	disc_pct = 1 - disc_pct;
    }


    for (i = 1; i <= 24; i++) {
	if (eval('document.form1.quantity' + i + '.value.valueOf() != "" || document.form1.price' + i + '.value.valueOf() != ""')) {
	    eval('document.form1.disc_price' + i + '.value = r3( document.form1.price' + i + '.value * disc_pct )');
	    eval('document.form1.price' + i + '.value = r2( document.form1.price' + i + '.value )');

	    eval('var total' + i + ' = r3(document.form1.quantity' + i + '.value * document.form1.disc_price' + i + '.value)');
	    eval('document.form1.total' + i + '.value = r3( total' + i + ' - 0 )');
	} else {
	    eval('var total' + i + ' = ""');
	}
    }

    var subtotal = total1.valueOf() - 0;

    for (x = 2; x <= 24; x++) {
		if (eval('total' + x) != "") {
			subtotal += eval('total' + x).valueOf() - 0;
		}
    }

    subtotal = r3(subtotal);

    $('#subtotal').val(subtotal);

    finishCalc();
}

function finishCalc() {
    var total = document.form1.subtotal.value.valueOf() - 0;
    total += document.form1.shippingFld.value.valueOf() - 0;
    total -= document.form1.discount.value.valueOf() - 0;

    if ($('#apply_credit').is(':checked')) {
	total -= document.form1.credit.value.valueOf() - 0;
    }

    total = r2(total);
    document.form1.total.value = total;

    var quantity_total = 0;
    parseFloat(quantity_total);

    for (y = 1; y <= 24; y++) {
	if (eval('document.form1.quantity' + y + '.value.valueOf() != ""')) {
	    eval('quantity_total += document.form1.quantity' + y + '.value.valueOf() - 0');
	}
    }

    document.form1.shippingFld.value = r2(document.form1.shippingFld.value);
    document.form1.discount.value = r2(document.form1.discount.value);

    parseFloat(quantity_total);
    document.form1.item_count.value = quantity_total - 0;
}

var win = null;

function newWindow(mypage, myname, w, h, features) {
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    if (winl < 0) winl = 0;
    if (wint < 0) wint = 0;
    var settings = 'height=' + h + ',';
    settings += 'width=' + w + ',';
    settings += 'top=' + wint + ',';
    settings += 'left=' + winl + ',';
    settings += features;
    win = window.open(mypage, myname, settings);
    win.window.focus();
}

$(function() { //on doc ready
    $('#recalc').click(function() {
	reCalc()
    });

    reCalc();
});

</script>

<style type="text/css">
 .pretty_disabled {
	 text-align:right;
	 background-color: #eeeeee;
	 border: 1px solid #e0e0e0;
	 color: navy
}
</style>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<form name="form1" id="form1" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left">Place an order for <b><?=$_SESSION["member_name"] ?></b>.</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<input type="hidden" name="member_id" value="<?=$member_id?>" />
<tr><td align="left"><table border="0">
<tr style="font-weight:bold; font-size: 12px"><th><font face="Arial">Quantity</font></th><th><font face="Arial">Product</font></th><th><font face="Arial">Cost Per</font></th>
<th><font face="Arial">After Discount</font></th>
<th align="right"><font face="Arial">Total</font></th><th>&nbsp;</th></tr>

<?php
$query = "SELECT * FROM product_skus WHERE active='1' AND display_on_website='1'";

for($cnt=1; $cnt<=24; $cnt++) {
	echo '<tr><td align="center"><input type="text" style="text-align:right" name="quantity'.$cnt.'" id="quantity'.$cnt.'" size="4" maxlength="4" value="'.$_POST["quantity".$cnt].'" class="quantity"></td><td align="center"><select name="name'.$cnt.'" id="name'.$cnt.'" class="product">';
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$product_id=$line["prod_id"];
		$tmp_name_val = $line["name"] . "|" . $line["sku"] ."|". $line["weight"]."|". $line["cost"];
		echo "<option value=\"";
		echo $tmp_name_val;
		echo "\"";
		if( $_POST["name".$cnt] == $tmp_name_val ) { echo " SELECTED"; }
		echo ">";
		echo $line["name"];
		echo "</option>\n";
		$sku1 = $line["sku"];
	}
	mysql_free_result($result);

	echo '</select></td><td align="center"><input type="text" style="text-align:right" id="price'.$cnt.'" name="price'.$cnt.'" size="9" maxlength="9" value="'.$_POST["price".$cnt].'" class="cost_per"></td>

	<td align="right"><input type="text" class="pretty_disabled" onfocus="$(\':input[@name=quantity'.($cnt+1).']\').focus()" name="disc_price'.$cnt.'" size="9" maxlength="9" value="" class="after_discount"></td>

	<td align="right"><input type="text" class="pretty_disabled" onfocus="$(\':input[@name=quantity'.($cnt+1).']\').focus()" id="total'.$cnt.'" name="total'.$cnt.'" size="9" maxlength="9" value="'.$_POST["total".$cnt].'" class="total"></td><td>&nbsp;</td></tr>';
}

?>
<tr><td align="center"><input type="text" name="item_count" size="4" maxlength="4" value="<?php echo $item_count; ?>"></td><td class="style4">Total Items</td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
</table>

<table cellpadding="3" cellspacing="0" border="0">
<tr><td align="right"><font face="Arial" size="+1"><b>Discount Code:</b></font></td><td colspan="2">
<select name="discount_code" id="discount_code">
<option value="">none</option>
<?php

$queryD = "SELECT * FROM discount_codes WHERE status='1' ORDER BY percent_off";
$resultD = mysql_query($queryD) or die("Query failed : " . mysql_error());
while ($lineD = mysql_fetch_array($resultD, MYSQL_ASSOC)) {
	echo '<option value="'.$lineD["discount_code"].'|'.$lineD["percent_off"].'"';
	if ( $discount_code==$lineD["discount_code"] ) { echo " SELECTED"; }
	echo '>'.($lineD["percent_off"]*100).'% off&#160;&#160;&#160;&#160;('.$lineD["location_target"].' - '.$lineD["discount_code"].')</option>';
}
?>
"></select></td></tr>

<tr>
    <td align="right">
        <font face="Arial" size="+1"><b>Subtotal:</b></font>
    </td>
    <td>
        <input type="text" class="pretty_disabled" onfocus="this.blur()" name="subtotal" id="subtotal" size="9" maxlength="9" value="<?php echo $subtotal; ?>">
     </td>
     <td>&nbsp;
    </td>
</tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Additional Discount: -</b></font></td><td><input type="text" style="text-align:right" name="discount" id="discount" size="9" maxlength="9" value="<?php echo $discount; ?>"></td><td>&nbsp;</td></tr>


<tr><td align="right"><b>Shipping Method:</b></td>
<td>
	<?php
		include_once('../includes/db.class.php');
		$db = new DB();

		$shipQry = "SELECT ship_method_id, name FROM ship_method WHERE active='1'";
		$shipMethods = $db->GetRecords($shipQry);

		print makeSelectBox("shipping_method",$shipMethods,$shipping_method);

	?>
</td></tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Shipping and Handling / COD:</b></font></td>
<td><input type="text" style="text-align:right" name="shippingFld" id="shippingFld" size="9" maxlength="9" value="<?php echo $shippingFld; ?>">
<a href="javascript:void(0)" id="shipest">Estimate Shipping</a></td><td>&#160;</td></tr>

<tr><td colspan="3"><hr /></td></tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Grand Total:</b></font></td><td colspan="2"><input type="text" style="text-align:right" name="total" size="9" maxlength="9" value="<?php echo $total; ?>"> <span id="recalc_wrapper"><input type="button" name="recalc" id="recalc" value="Calculate"></span></td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td colspan="4" align="center"><input type="submit" name="submit" value=" Save and Continue Order "></td></tr>
</table></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
</form>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>
