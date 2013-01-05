<?php
// BME WMS
// Page: Products Manager Homepage
// Path/File: /admin/products_admin.php
// Version: 1.8
// Build: 1805
// Date: 03-18-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$submit = $_POST["submit"];
$items_per_row = $_POST["items_per_row"];
$display_warning = $_POST["display_warning"];
$display_disclaimer = $_POST["display_disclaimer"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$error_txt = "";
	
	if($error_txt == "") {
		$query = "UPDATE prod_main SET items_per_row='$items_per_row', display_warning='$display_warning', display_disclaimer='$display_disclaimer' WHERE prod_main_id='1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
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

<tr><td align="left"><font size="2">Welcome to the Products Manager, where you manage the Products, Product Categories, and Product SKUs on your website. Below please find general settings and statistics for your products.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$prod_counter1 = 0;
$prod_counter2 = 0;
$prod_counter3 = 0;
$query = "SELECT active FROM products";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$active = $line["active"];
	if($active == "0") { $prod_counter2 = $prod_counter2 + 1; }
	if($active == "1") { $prod_counter3 = $prod_counter3 + 1; }
}
$prod_counter1 = $prod_counter2 + $prod_counter3;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $prod_counter1; ?></b> Products<br>
There are <b><?php echo $prod_counter3; ?></b> Products active<br>
There are <b><?php echo $prod_counter2; ?></b> Products inactive</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$prodcat_counter1 = 0;
$prodcat_counter2 = 0;
$prodcat_counter3 = 0;
$query = "SELECT active FROM product_categories";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$active = $line["active"];
	if($active == "0") { $prodcat_counter2 = $prodcat_counter2 + 1; }
	if($active == "1") { $prodcat_counter3 = $prodcat_counter3 + 1; }
}
$prodcat_counter1 = $prodcat_counter2 + $prodcat_counter3;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $prodcat_counter1; ?></b> Product Categories<br>
There are <b><?php echo $prodcat_counter3; ?></b> Product Categories active<br>
There are <b><?php echo $prodcat_counter2; ?></b> Product Categories inactive</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$prodsku_counter1 = 0;
$prodsku_counter2 = 0;
$prodsku_counter3 = 0;
$query = "SELECT active FROM product_skus";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$active = $line["active"];
	if($active == "0") { $prodsku_counter2 = $prodsku_counter2 + 1; }
	if($active == "1") { $prodsku_counter3 = $prodsku_counter3 + 1; }
}
$prodsku_counter1 = $prodsku_counter2 + $prodsku_counter3;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $prodsku_counter1; ?></b> Product SKUs<br>
There are <b><?php echo $prodsku_counter3; ?></b> Product SKUs active<br>
There are <b><?php echo $prodsku_counter2; ?></b> Product SKUs inactive</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$disc_code_counter0 = 0;
$disc_code_counter1 = 0;
$disc_code_counter2 = 0;
$disc_code_total = 0;
$query = "SELECT status FROM discount_codes";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["status"] == "0") {
		$disc_code_counter0++;
	} elseif($line["status"] == "1") {
		$disc_code_counter1++;
	} elseif($line["status"] == "2") {
		$disc_code_counter2++;
	}
	$disc_code_total++;
}
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There are <b><?php echo $disc_code_total; ?></b> Total Discount Codes<br>
There are <b><?php echo $disc_code_counter1; ?></b> Active Discount Codes<br>
There are <b><?php echo $disc_code_counter2; ?></b> To Review Discount Codes<br>
There are <b><?php echo $disc_code_counter0; ?></b> Inactive Discount Codes<br>
</font></td></tr>

<tr><td>&nbsp;</td></tr>
<?php
$query = "SELECT items_per_row, display_warning, display_disclaimer FROM prod_main WHERE prod_main_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$items_per_row = $line["items_per_row"];
	$display_warning = $line["display_warning"];
	$display_disclaimer = $line["display_disclaimer"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="products" Method="POST" ACTION="./products_admin.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Products Manager Global Settings</legend>
		<ol>
			<li>
				<label for="items_per_row">Items Displayed Per Row <em>*</em></label>
				<select id="items_per_row" name="items_per_row" tabindex="1">
				<?php
				for($i=1;$i<11;$i++) {
					echo "<option value=\"$i\"";
					if($items_per_row == $i) {
						echo " SELECTED";
					}
					echo ">$i</option>\n";
				}
				?>
				</select>
			</li>
			<li>
				<label for="display_warning">Display Warning in Checkout <em>*</em></label>
				<select id="display_warning" name="display_warning" tabindex="2">
				<option value="1"<?php if(isset($display_warning) && $display_warning == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_warning) && $display_warning == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="display_disclaimer">Display Disclaimer in Checkout <em>*</em></label>
				<select id="display_disclaimer" name="display_disclaimer" tabindex="3">
				<option value="1"<?php if(isset($display_disclaimer) && $display_disclaimer == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_disclaimer) && $display_disclaimer == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

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