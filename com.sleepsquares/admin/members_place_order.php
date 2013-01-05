<?php
// 2/25/2009: Using ship_method_wholesale instead of hardcoded options
// 7/7/2009: Removed active='1' from ship methods query
// 6/8/2011: Originally copied from admin/retailers_admin10.php and store/step1.php.

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include_once '../includes/main1.php';
include_once $base_path.'includes/retailer2.php'; //TODO: line #197... Use consumer version of function loadShipping($tot_weight, $shipping_method, $box_count_num, $subtotal, $shippers)
include_once $base_path.'includes/wc1.php'; //TODO: create a "call center rep" retailer. Use the member's information for the rep's shipping address, etc.
include "../includes/common.php";
include_once('../includes/customer.php');//TODO: Verify that doLogin actually works in this context.
//print_d($GLOBALS,true);
//print_d($_REQUEST,true);

//die('...development in progress!');

$vars = explode(",","active,back_ordered,bill_address1,bill_address2,bill_city,".
		"bill_country,bill_email,bill_name,bill_phone,bill_state,".
		"bill_zip,cc_auth_code,cc_exp_m,cc_exp_y,cc_first_name,".
		"cc_last_name,cc_num,cc_trans_id,cc_type,cid,complete,created,".
		"delivery,discount_code,item_count,member_id,modified,notes,ordered,".
		"pay_type,ship_address1,ship_address2,ship_city,ship_country,".
		"ship_name,ship_phone,ship_state,ship_zip,shipped,shipped_date,".
		"shipping,subtotal,tax,total,user_id");

for($i=0;$i<count($vars);$i++){
	$param=$vars[$i];
	if($_GET[$param]){
		$GLOBALS[$param]=$_GET[$param];
	}
	if($_POST[$param]){
		$GLOBALS[$param]=$_POST[$param];
	}
}

//$member_id = $_POST["member_id"];
$submit = $_POST["submit"];
$order = $_POST["order"];
//$user_id = $_POST["user_id"];
$email = $_POST["email"];
$password = $_POST["password"];
$old_password = $_POST["old_password"];
$nickname = $_POST["nickname"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$date_of_birth = $_POST["date_of_birth"];
$bill_name = $_POST["bill_name"];
$bill_address1 = $_POST["bill_address1"];
$bill_address2 = $_POST["bill_address2"];
$bill_city = $_POST["bill_city"];
$bill_state = $_POST["bill_state"];
$bill_zip = $_POST["bill_zip"];
$bill_country = $_POST["bill_country"];
$bill_phone = $_POST["bill_phone"];
$ship_name = $_POST["ship_name"];
$ship_address1 = $_POST["ship_address1"];
$ship_address2 = $_POST["ship_address2"];
$ship_city = $_POST["ship_city"];
$ship_state = $_POST["ship_state"];
$ship_zip = $_POST["ship_zip"];
$ship_country = $_POST["ship_country"];
$ship_phone = $_POST["ship_phone"];
$forward = $_POST["forward"];

//if ( $_REQUEST["retailer_id"] ) {
//	$queryRet = "SELECT * FROM retailer WHERE retailer_id=".$_REQUEST["retailer_id"];
//	$resultRet = mysql_query($queryRet) or die("Query failed : " . mysql_error());
//	while ($lineRet = mysql_fetch_array($resultRet, MYSQL_ASSOC)) {
//		//print_d($lineRet,true);
//		$shipping_info['ship_name'] = $lineRet["store_name"];
//		$shipping_info['ship_address1'] = $lineRet["address1"];
//		$shipping_info['ship_address2'] = $lineRet["address2"];
//		$shipping_info['ship_city'] = $lineRet["city"];
//		$shipping_info['ship_state'] = $lineRet["state"];
//		$shipping_info['ship_zip'] = $lineRet["zip"];
//		$shipping_info['ship_country'] = $lineRet["country"];
//		$shipping_info['ship_phone'] = $lineRet["phone"];
//		$_SESSION['ship_state'] = $lineRet["state"];
//		$_SESSION['shipping_info'] = $shipping_info;
//
//		$discount_code = $lineRet["discount_code"];
//		$credit_in_db = $lineRet["credit"];
//
//    }
//}
//
if ( $_GET["recalc_ship_admin"] ) {
	$queryBoxes = "SELECT * FROM ship_containers";
	//print_d(array(__FILE__,__LINE__,$queryBoxes));
	$resultBoxes = mysql_query($queryBoxes) or die("Query failed : " . mysql_error());
	$boxrowct=0;
	while ($lineBoxes = mysql_fetch_array($resultBoxes, MYSQL_ASSOC)) {
	   //print_d($lineBoxes,true);
	   $boxes[$boxrowct] = new ShipBox;
	   foreach ($lineBoxes as $col => $val) {
			$boxes[$boxrowct]->$col = $val;//add properties to box
	   }
	   $boxrowct++;
	}

	$box_weight_and_count = calc_box_weight($_GET["tot_quant"], $boxes);
	$box_weight = $box_weight_and_count[0];
	$boxes = $box_weight_and_count[1];//boxes array has been updated with counts of each box
	$box_count_num = 0;
	foreach($boxes as $box) {
		$box_count_num += $box->counter;
	}
	$tot_weight = $_GET["tot_weight"] + $box_weight;

	$_SESSION["boxes"] = $boxes;//store in session so we don't have to pass it around

    //TODO: Revise this for CONSUMER
    loadShipping($tot_weight, $_GET["shipping_method"], $box_count_num, $_GET["subtotal"], 'FedEx|USPS|UPS');
    exit();
}

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$query = "SELECT product_line FROM retailer_main";
//print_d(array(__FILE__,__LINE__,$query));
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	//print_d($line,true);
	$product_line = $line["product_line"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';
$manager = "callcenter";
$page = "Call Center Manager > Place Order Page 1";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$retailer_id = $_REQUEST["retailer_id"];
$user_id_ordering = $_POST["user_id_ordering"];
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

$shipping_method = $_POST["shipping_method"];
$item_count = $_POST["item_count"];
$subtotal = $_POST["subtotal"];
$shipping = $_POST["shipping"];
$discount = $_POST["discount"];

if ( $_POST["submit"] ) {
    $discount_code_vars = split( "\|", $_POST["discount_code"] );
    $discount_code  = $discount_code_vars[0];
    $discount_code_insert = $discount_code;//only use the posted one (not the one coming from the retailer's record) for the INSERT
	$discount_pct_insert = $discount_code_vars[1];
}
$total = $_POST["total"];

$this_user_id = $_COOKIE["wms_user"];


if ($submit != "") {


	//Validate
	$error_txt = "";
	if($quantity1 == "") { $error_txt .= "You must enter a Quantity on the first line.<br>\n"; }
	if($name1 == "") { $error_txt .= "You must select a Product on the first line.<br>\n"; }
	if($price1 == "") { $error_txt .= "You must enter a Price on the first line.<br>\n"; }
	if($shipping_method == "") { $error_txt .= "You must enter a Shipping Method.<br>\n"; }
	if($total1 == "" || $item_count == "" || $subtotal == "" || $total == "") { $error_txt .= "You must hit the Calculate button before hitting the Save button.<br>\n"; }

	if($error_txt == "") {
	$subtotal = condDecimalFormat( $subtotal);
	$shipping = condDecimalFormat( $shipping);
	$discount = condDecimalFormat( $discount);
	$total = condDecimalFormat( $total);

	$now = date("Y-m-d H:i:s");


	//NOTE that the subtotal for orders placed here is diff than orders placed via retailer login.  Here it's the total before discounts instead of (total minus discounts - shipping) as it is in retailer site (b/c there discount is factored into each item price, here it is not)
	//TODO: Correct this for CONSUMER receipts
	//$query = "INSERT INTO receipts SET created='$now', ordered='$now', complete='0', member_id='$member_id', item_count='$item_count', subtotal='$subtotal', shipping='$shipping', tax='$tax', discount_code='$discount_code_insert', total='$total'";
	$query = "INSERT INTO receipts SET created='$now', ordered='$now', complete='0', member_id='$member_id', user_id='$this_user_id', item_count='$item_count', subtotal='$subtotal', shipping='$shipping', tax='$tax', discount_code='$discount_code_insert', total='$total'";


	if ( $apply_credit ) {
		$query .= ", credit_used=$credit";
	}
	//print_d(array(__FILE__,__LINE__,$query));
	$result = mysql_query($query) or die("Query failed : " . mysql_error() .'<br />'.$query);
//print_d($result);

	$receipt_id = mysql_insert_id();
//print_d($receipt_id);

	$disc_pct = 1;
	if ( $discount && $subtotal!=0 ) {
		$disc_pct = $discount / $subtotal;
	}
//print_d($disc_pct);

	foreach ($quantityP as $num => $val) {
		if ($val != "") {
			$name = $nameP[$num];
//print_d($name);
			$price = $priceP[$num];
//print_d($price);
			$orig_price = $priceOrig[$num];
//print_d($orig_price);
			list($name, $sku) = split('\|', $name);
//print_d($sku);
			$price = condDecimalFormat( $price);
//print_d($price);

			$now = date("Y-m-d H:i:s");
			//TODO: Correct this for CONSUMER receipts
			$query = "INSERT INTO receipt_items SET receipt_id='$receipt_id', created='$now', sku='$sku', quantity='$val', price='$price', /*orig_price='$orig_price', */name='$name'";
			//print_d(array(__FILE__,__LINE__,$query));
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
//print_d($result);
		}
	}


	// Send to retailers place order page 2
	header("Location: ".$base_url."admin/members_place_order2.php?receipt_id=".$receipt_id."&member_id=".$member_id."&user_id=".$user_id);
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
	var F = this.form;
	//var X = [F['quantity' + index], F['name' + index], F['price' + index], F['disc_price' + index], F['total' + index]];
	if (isNaN(F['quantity' + index].value)) F['quantity' + index].value = 0;
	var retail=F['name' + index].value.match(/\d+\.\d\d/g).sort(function(a,b){return a-b}).pop();
	F['total' + index].value = F['quantity' + index].value * (F['price' + index].value = retail);
	F['total' + index].value = (1*F['total' + index].value).toFixed(2);
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
    total += document.form1.shipping.value.valueOf() - 0;
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

    document.form1.shipping.value = r2(document.form1.shipping.value);
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
    $('#shipest').click(function() {
	ShipLoad()
    });
    $('#recalc').click(function() {
	reCalc()
    });

    reCalc();
});

function ShipLoad() {
    reCalc();
    var tot_weight = 0;
    var tot_quant = 0;
    $('input[@name*=quantity]').each(function() {
	if (!isNaN($(this).val()) && $(this).val() > 0) {
	    tot_quant += $(this).val() * 1;
	    var this_name = $(this).attr('name');
	    var this_id = this_name.substring(8)

	    $sku_name_field = $('select[@name=name' + this_id + ']');
	    var tmp_var = $sku_name_field.val().split('|');
	    var this_weight = tmp_var[2];
	    tot_weight += this_weight * 1;
	}
    })

    var orig_html = $('#shipest_wrapper').html();
    var ship_url = 'members_place_order.php?recalc_ship_admin=1&tot_weight=' + tot_weight + '&shipping_method=&tot_quant=' + tot_quant + '&subtotal=' + $('#subtotal').val();
    $('#shipest_wrapper').each(function() {
	$(this).small_spinner();
	$.get(ship_url, function(data) {
	    var selected_option = $("select[@id=shipping_method] option[@selected]");
	    var selected_val = $(selected_option).val();
	    $('#shipping_method').html(data)
	    $('#shipest_wrapper').html(orig_html);
	    $('#shipest').click(function() {
		ShipLoad()
	    });
	    $('select[@id=shipping_method]').css('color', '#C000C0')
	    setTimeout("$('select[@id=shipping_method]').css('color', '#000')", 3000)
	});
    })
}</script>

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

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Welcome to the <?=$page?> page, where you place an order for a member of our website.
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><input type="button" name="order_history" value=" View Order History " onClick="newWindow('./retailers_admin12.php?retailer_id=<?php echo $retailer_id; ?>','','650','250','resizable,scrollbars,status,toolbar')"></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><b>Place CONSUMER Order Form</b></font></td></tr>

<form name="form1" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST"  class="wmsform">
<input type="hidden" name="member_id" value="<?=$member_id?>" />
<tr><td align="left"><table border="0">
<tr style="font-weight:bold; font-size: 12px"><th><font face="Arial">Quantity</font></th><th><font face="Arial">Product</font></th><th><font face="Arial">Cost Per</font></th>
<th><font face="Arial">After Discount</font></th>
<th align="right"><font face="Arial">Total</font></th><th>&nbsp;</th></tr>

<?php
$query = "SELECT name, sku, weight, prod_id FROM product_skus WHERE active='1' AND display_on_website='1'";
//print_d(array(__FILE__,__LINE__,$query));
for($cnt=1; $cnt<=24; $cnt++) {
	echo '<tr><td align="center"><input type="text" style="text-align:right" name="quantity'.$cnt.'" size="4" maxlength="4" value="'.$_POST["quantity".$cnt].'" class="quantity"></td><td align="center"><select name="name'.$cnt.'" class="product">';
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	$tmp_pricing = "";
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		//print_d($line,true);
		$product_id=$line["prod_id"];
		$queryProduct = "SELECT pricing, prod_id FROM products WHERE prod_id='$product_id' AND active='1' AND display_on_website='1' LIMIT 1";
		//print_d(array(__FILE__,__LINE__,$queryProduct));
		$resultProduct = mysql_query($queryProduct) or die("Query failed : " . mysql_error());
		while ($lineProduct = mysql_fetch_array($resultProduct, MYSQL_ASSOC)) {
			$pricing = $lineProduct["pricing"];
		}
		$tmp_name_val = $line["name"] . "|" . $line["sku"] ."|". $line["weight"]."|". $pricing;
		echo "<option value=\"";
		echo $tmp_name_val;
		echo "\"";
		if( $_POST["name".$cnt] == $tmp_name_val ) { echo " SELECTED"; $tmp_pricing = $pricing; }
		echo ">";
		echo $line["name"];
		echo "</option>\n";
		$sku1 = $line["sku"];
	}
	mysql_free_result($result);

	echo '</select></td><td align="center"><input type="text" style="text-align:right" name="price'.$cnt.'" size="9" maxlength="9" value="'.$tmp_pricing.'" class="cost_per"></td>

	<td align="right"><input type="text" class="pretty_disabled" onfocus="this.blur()" name="disc_price'.$cnt.'" size="9" maxlength="9" value="" class="after_discount"></td>

	<td align="right"><input type="text" class="pretty_disabled" onfocus="this.blur()" name="total'.$cnt.'" size="9" maxlength="9" value="'.$_POST["total".$cnt].'" class="total"></td><td>&nbsp;</td></tr>';
}

?>
<tr><td align="center"><input type="text" name="item_count" size="4" maxlength="4" value="<?php echo $item_count; ?>"></td><td class="style4">Total Items</td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
</table>

<table cellpadding="3" cellspacing="0" border="0">
<tr><td align="right" class="style4">Shipping Estimates: </td><td colspan="2">
<select id="shipping_method" class="text_right" name="shipping_method">
<?php

echo '</select>';
?>
    <span id="shipest_wrapper"><input type="button" name="shipest" id="shipest" value="Get Estimates"></span>
</td>
</tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Shipping Method:</b> </td><td><select name="shipping_method">
<option value="">Please Select</option>
<?php
//TODO: Correct this for CONSUMER ...
$querySMW = "SELECT * FROM ship_method_wholesale";
//print_d(array(__FILE__,__LINE__,$querySMW));
$resultSMW = mysql_query($querySMW) or die("Query failed : " . mysql_error());
while ($lineSMW = mysql_fetch_array($resultSMW, MYSQL_ASSOC)) {
    //print_d($lineSMW,true);
    echo '<option value="'.$lineSMW["name"].'" ';
    if ( $shipping_method == $lineSMW["name"] ) {
        echo ' selected="true" ';
    }
    echo '>'.$lineSMW["name"].'</option>';
}
?>
</select></font></td><td>&nbsp;</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Discount Code:</b></font></td><td colspan="2">
<select name="discount_code" id="discount_code">
<option value="">none</option>
<?php

$queryD = "SELECT * FROM discount_codes WHERE status='1' ORDER BY percent_off";
//print_d(array(__FILE__,__LINE__,$queryD));
$resultD = mysql_query($queryD) or die("Query failed : " . mysql_error());
while ($lineD = mysql_fetch_array($resultD, MYSQL_ASSOC)) {
	//print_d($lineD,true);
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

<?php
	if ( $credit_in_db > 0 ) {
		echo '<tr><td VALIGN="TOP" align="right"><input type="checkbox" name="apply_credit" id="apply_credit"';
		if ($apply_credit) {
			echo ' checked';
		}
		echo '/><label for="apply_credit" class="bold" face="Arial" size="+1">Apply Credit </label><font face="Arial" size="+1"><b>-</b></font></td>';
		echo '<td VALIGN="TOP" align="left">';
		echo '<input type="text" style="text-align:right; color: #000" class="pretty_disabled" onfocus="this.blur()" name="credit" id="credit" value="'.condDecimalFormat($credit_in_db).'" maxlength="9" size="9" />';
		echo '</td></tr>';
	}
?>

<tr><td align="right"><font face="Arial" size="+1"><b>Shipping and Handling / COD:</b></font></td>
<td><input type="text" style="text-align:right" name="shipping" size="9" maxlength="9" value="<?php echo $shipping; ?>"></td><td>&nbsp;</td></tr>

<tr><td colspan="3"><hr /></td></tr>

<tr><td align="right"><font face="Arial" size="+1"><b>Grand Total:</b></font></td><td colspan="2"><input type="text" style="text-align:right" name="total" size="9" maxlength="9" value="<?php echo $total; ?>"> <span id="recalc_wrapper"><input type="button" name="recalc" id="recalc" value="Calculate"></span></td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td colspan="4" align="center"><input type="submit" name="submit" value=" Save and Continue Order "></td></tr>
</form>
</table></td></tr>

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
