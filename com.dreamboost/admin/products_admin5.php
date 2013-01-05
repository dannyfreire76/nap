<?php
// BME WMS
// Page: Products Manager - Create Product SKU page
// Path/File: /admin/products_admin5.php
// Version: 1.8
// Build: 1805
// Date: 03-15-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$create = $_POST["create"];
if($_GET["prod_id"] != "") {
	$prod_id = $_GET["prod_id"];
} else {
	$prod_id = $_POST["prod_id"];
}
$sku = $_POST["sku"];
$name = $_POST["name"];
$drop_down = $_POST["drop_down"];
$ship_con_id = $_POST["ship_con_id"];
$cost = $_POST["cost"];
$wholesale_cost1 = $_POST["wholesale_cost1"];
$wholesale_cost2 = $_POST["wholesale_cost2"];
$wholesale_cost3 = $_POST["wholesale_cost3"];
$dist_cost1 = $_POST["dist_cost1"];
$dist_cost2 = $_POST["dist_cost2"];
$dist_cost3 = $_POST["dist_cost3"];
$weight_lbs = $_POST["weight_lbs"];
$weight_ozs = $_POST["weight_ozs"];
$stock_status = $_POST["stock_status"];
$stock = $_POST["stock"];
$display_on_website = $_POST["display_on_website"];
$display_in_wc = $_POST["display_in_wc"];
$active = $_POST["active"];
$threshold = $_POST["threshold"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Create Product SKUs";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Validate
	$error_txt = "";
	if($sku == "") { $error_txt .= "Error, the SKU is blank. You must enter a SKU for this product.<br>\n"; }
	if($name == "") { $error_txt .= "Error, the Product Name is blank. You must enter a Name for this product.<br>\n"; }
	if($cost == "") { $error_txt .= "Error, the Retail Price is blank. You must enter a Retail Price for this product.<br>\n"; }
	if($wholesale_cost1 == "") { $error_txt .= "Error, the Wholesale Price1 is blank. You must enter a Wholesale Price1 for this product.<br>\n"; }
	if($wholesale_cost2 == "") { $error_txt .= "Error, the Wholesale Price2 is blank. You must enter a Wholesale Price2 for this product.<br>\n"; }
	if($wholesale_cost3 == "") { $error_txt .= "Error, the Wholesale Price3 is blank. You must enter a Wholesale Price3 for this product.<br>\n"; }
	if($dist_cost1 == "") { $error_txt .= "Error, the Distributor Price1 is blank. You must enter a Distributor Price1 for this product.<br>\n"; }
	if($dist_cost2 == "") { $error_txt .= "Error, the Distributor Price2 is blank. You must enter a Distributor Price2 for this product.<br>\n"; }
	if($dist_cost3 == "") { $error_txt .= "Error, the Distributor Price3 is blank. You must enter a Distributor Price3 for this product.<br>\n"; }
	if($stock == "") { $error_txt .= "Error, the Stock Amount is blank. You must enter a Stock Amount for this product.<br>\n"; }
	if($threshold == "") { $error_txt .= "Error, the Stock Threshold is blank. You must enter a Stock Threshold for this product.<br>\n"; }
	
	if($error_txt == "") {
		$created = date("Y-m-d H:i:s");
		$query = "INSERT INTO product_skus SET created='$created', prod_id='$prod_id', sku='$sku', name='$name', drop_down='$drop_down', ship_con_id='$ship_con_id', cost='$cost', wholesale_cost1='$wholesale_cost1', wholesale_cost2='$wholesale_cost2', wholesale_cost3='$wholesale_cost3', dist_cost1='$dist_cost1', dist_cost2='$dist_cost2', dist_cost3='$dist_cost3', weight_lbs='$weight_lbs', weight_ozs='$weight_ozs', stock_status='$stock_status', stock='$stock', display_on_website='$display_on_website', display_in_wc='$display_in_wc', active='$active', threshold='$threshold'";
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

<tr><td align="left"><font size="2">Create a Product SKU below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="products" Method="POST" ACTION="./products_admin5.php" class="wmsform">
	<input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Product SKU Information</legend>
		<ol>
			<li>
				<label for="sku">SKU <em>*</em></label>
				<INPUT type="text" id="sku" name="sku" size="4" maxlength="4" value="<?php echo $sku; ?>" tabindex="1" />
			</li>
			<li>
				<label for="name">Product SKU Name <em>*</em></label>
				<INPUT type="text" id="name" name="name" size="30" maxlength="255" value="<?php echo $name; ?>" tabindex="2" />
			</li>
			<li>
				<label for="drop_down">Drop Down List Name <em>*</em></label>
				<INPUT type="text" id="drop_down" name="drop_down" size="30" maxlength="255" value="<?php echo $drop_down; ?>" tabindex="3" />
			</li>
			<li>
				<label for="ship_con_id">Shipping Container <em>*</em></label>
				<select id="ship_con_id" name="ship_con_id" tabindex="4">
				<?php
				$query = "SELECT sc_id, sc_desc FROM ship_containers";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$this_ship_con_id = $line["sc_id"];
					$name = $line["sc_desc"];
					echo "<option value=\"$this_ship_con_id\"";
					if($ship_con_id == $this_ship_con_id) { echo " SELECTED"; }
					echo ">$name</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Product SKU Pricing Information</legend>
		<ol>
			<li>
				<label for="cost">Retail Price <em>*</em></label>
				<INPUT type="text" id="cost" name="cost" size="9" maxlength="9" value="<?php echo $cost; ?>" tabindex="5" />
			</li>
			<li>
				<label for="wholesale_cost1">Wholesale Price1 <em>*</em></label>
				<INPUT type="text" id="wholesale_cost1" name="wholesale_cost1" size="9" maxlength="9" value="<?php echo $wholesale_cost1; ?>" tabindex="6" />
			</li>
			<li>
				<label for="wholesale_cost2">Wholesale Price2 <em>*</em></label>
				<INPUT type="text" id="wholesale_cost2" name="wholesale_cost2" size="9" maxlength="9" value="<?php echo $wholesale_cost2; ?>" tabindex="7" />
			</li>
			<li>
				<label for="wholesale_cost3">Wholesale Price3 <em>*</em></label>
				<INPUT type="text" id="wholesale_cost3" name="wholesale_cost3" size="9" maxlength="9" value="<?php echo $wholesale_cost3; ?>" tabindex="8" />
			</li>
			<li>
				<label for="dist_cost1">Distributor Price1 <em>*</em></label>
				<INPUT type="text" id="dist_cost1" name="dist_cost1" size="9" maxlength="9" value="<?php echo $dist_cost1; ?>" tabindex="9" />
			</li>
			<li>
				<label for="dist_cost2">Distributor Price2 <em>*</em></label>
				<INPUT type="text" id="dist_cost2" name="dist_cost2" size="9" maxlength="9" value="<?php echo $dist_cost2; ?>" tabindex="10" />
			</li>
			<li>
				<label for="dist_cost3">Distributor Price3 <em>*</em></label>
				<INPUT type="text" id="dist_cost3" name="dist_cost3" size="9" maxlength="9" value="<?php echo $dist_cost3; ?>" tabindex="11" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Product SKU Information</legend>
		<ol>
			<li>
				<label for="weight_lbs">Weight (Pounds) <em>*</em></label>
				<INPUT type="text" id="weight_lbs" name="weight_lbs" size="5" maxlength="5" value="<?php echo $weight_lbs; ?>" tabindex="13" />
			</li>
			<li>
				<label for="weight_ozs">Weight (Ounces) <em>*</em></label>
				<INPUT type="text" id="weight_ozs" name="weight_ozs" size="5" maxlength="5" value="<?php echo $weight_ozs; ?>" tabindex="14" />
			</li>
			<li>
				<label for="stock_status">Stock Status <em>*</em></label>
				<select id="stock_status" name="stock_status" tabindex="15">
				<option value="1"<?php if(isset($stock_status) && $stock_status == '1') { echo " SELECTED"; } ?>>In Stock</option>
				<option value="0"<?php if(isset($stock_status) && $stock_status == '0') { echo " SELECTED"; } ?>>Out of Stock</option>
				<option value="2"<?php if(isset($stock_status) && $stock_status == '2') { echo " SELECTED"; } ?>>Portfolio Only</option>
				</select>
			</li>
			<li>
				<label for="stock">Stock Amount <em>*</em></label>
				<INPUT type="text" id="stock" name="stock" size="5" maxlength="11" value="<?php echo $stock; ?>" tabindex="16" />
			</li>
			<li>
				<label for="threshold">Stock Threshold <em>*</em></label>
				<INPUT type="text" id="threshold" name="threshold" size="2" maxlength="2" value="<?php echo $threshold; ?>" tabindex="17" />
			</li>
			<li>
				<label for="display_on_website">Display on Website <em>*</em></label>
				<select id="display_on_website" name="display_on_website" tabindex="18">
				<option value="1"<?php if(isset($display_on_website) && $display_on_website == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_on_website) && $display_on_website == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="display_in_wc">Display in Wholesale Catalog <em>*</em></label>
				<select id="display_in_wc" name="display_in_wc" tabindex="19">
				<option value="1"<?php if(isset($display_in_wc) && $display_in_wc == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_in_wc) && $display_in_wc == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="active">Active <em>*</em></label>
				<select id="active" name="active" tabindex="20">
				<option value="1"<?php if(isset($active) && $active == '1') { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if(isset($active) && $active == '0') { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create Product SKU">
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