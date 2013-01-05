<?php
// BME WMS
// Page: Shipping Homepage
// Path/File: /admin/shipping_admin.php
// Version: 1.8
// Build: 1806
// Date: 05-12-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/st_and_co1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$submit = $_POST["submit"];
$free_shipping_offered = $_POST["free_shipping_offered"];
$free_shipping = $_POST["free_shipping"];
$tax = $_POST["tax"];
$instate_tax = $_POST["instate_tax"];
$state_tax = $_POST["state_tax"];
$handling = $_POST["handling"];
$ship_page = $_POST["ship_page"];
$ship_note = $_POST['ship_note'];

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Add HTML code
	$ship_page = str_replace("\n", "<br>", $ship_page);
	$ship_note = str_replace("\n", "<br>", $ship_note);

	//Validate
	$error_txt = "";
	if($tax == "") { $error_txt .= "You must enter the tax to be charged on the website.<br>\n"; }
	if($ship_page == "") { $error_txt .= "Error, the Shipping Page Content field is blank. There needs to be shipping page content.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE ship_main SET free_shipping_offered='$free_shipping_offered', free_shipping='$free_shipping', tax='$tax', instate_tax='$instate_tax', state_tax='$state_tax', handling='$handling', ship_page='$ship_page', ship_note='$ship_note' WHERE ship_main_id='1'";
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

<tr><td align="left"><font size="2">Welcome to the Shipping Manager, where you manage the entire Shipping operation of your website. On this page you will find general statistics and settings for the Shipping system. As well, please click through to the other pages of the Shipping Manager to manage other portions.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">There are <b>
<?php
$query = "SELECT shipped FROM receipts WHERE complete='1' AND shipped='0' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$shipped_counter = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$shipped_counter++;
}
mysql_free_result($result);
echo $shipped_counter;
?>
</b> Orders in the Retailer Shipping Queue waiting to be shipped.</font></td></tr>
<tr><td align="left"><font size="2">There are <b>
<?php
$query = "SELECT shipped FROM wholesale_receipts WHERE complete='1' AND shipped='0' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$shipped_counter = 0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$shipped_counter++;
}
mysql_free_result($result);
echo $shipped_counter;
?>
</b> Orders in the Wholesale Shipping Queue waiting to be shipped.</font></td></tr>

<tr><td>&nbsp;</td></tr>
<?php
$query = "SELECT * FROM ship_main WHERE ship_main_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$free_shipping_offered = $line["free_shipping_offered"];
	$free_shipping = $line["free_shipping"];
	$tax = $line["tax"];
	$instate_tax = $line["instate_tax"];
	$state_tax = $line["state_tax"];
	$handling = $line["handling"];
	$ship_page = $line["ship_page"];
	$ship_note = $line['ship_note'];
}
mysql_free_result($result);

$ship_page = str_replace("\n", "", $ship_page);
$ship_page = str_replace("<br>", "\n", $ship_page);

$ship_note = str_replace("\n", "", $ship_note);
$ship_note = str_replace("<br>", "\n", $ship_note);
?>

<tr><td align="left">
	<FORM name="shipping" Method="POST" ACTION="./shipping_admin.php" class="wmsform">
	<p>Please complete the form below. Required fields marked&#160;<em>*</em></p>
	<fieldset>
		<legend>Shipping Manager Global Settings</legend>
		<ol>
			<li>
				<label for="free_shipping_offered">Free Retail Shipping&#160;<em>*</em></label>
				<select id="free_shipping_offered" name="free_shipping_offered" tabindex="1">
				<option value="1"<?php if($free_shipping_offered == "1") { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if($free_shipping_offered == "0") { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="free_shipping">Free Retail Shipping Over&#160;<em>*</em></label>
				<INPUT type="text" id="free_shipping" name="free_shipping" size="10" maxlength="10" value="<?php echo $free_shipping; ?>" tabindex="2" />
			</li>
			<li>
				<label for="free_shipping_wholesale">Free Wholesale Shipping&#160;<em>*</em></label>
				<select id="free_shipping_wholesale" name="free_shipping_wholesale" tabindex="1">
				<option value="1"<?php if($free_shipping_wholesale == "1") { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if($free_shipping_wholesale == "0") { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="free_wholesale_ship_limit">Free Wholesale Shipping Over&#160;<em>*</em></label>
				<INPUT type="text" id="free_wholesale_ship_limit" name="free_wholesale_ship_limit" size="10" maxlength="10" value="<?php echo $free_wholesale_ship_limit; ?>" tabindex="2" />
			</li>
			<li>
				<label for="tax">Percent Tax Charged (decimal)&#160;<em>*</em></label>
				<INPUT type="text" id="tax" name="tax" size="6" maxlength="6" value="<?php echo $tax; ?>" tabindex="3" />
			</li>
			<li>
				<label for="instate_tax">Tax Charged On Only In-State Orders&#160;<em>*</em></label>
				<select id="instate_tax" name="instate_tax" tabindex="4">
				<option value="0"<?php if($instate_tax == "0") { echo " SELECTED"; } ?>>No</option>
				<option value="1"<?php if($instate_tax == "1") { echo " SELECTED"; } ?>>Yes</option>
				</select>
			</li>
			<li>
				<label for="state_tax">If Yes, which State&#160;<em>*</em></label>
				<select id="state_tax" name="state_tax" tabindex="5">
				<?php
				state_build_all($state_tax);
				?>
				</select>
			</li>
			<li>
				<label for="handling">Handling Fee per Retail Order&#160;<em>*</em></label>
				<INPUT type="text" id="handling" name="handling" size="10" maxlength="10" value="<?php echo $handling; ?>" tabindex="6" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Shipping Content</legend>
		<ol>
			<li>
				<label for="ship_page">Shipping Page Content&#160;<em>*</em></label>
				<TEXTAREA id="ship_page" name="ship_page" cols="40" rows="9" tabindex="7"><?php echo $ship_page; ?></TEXTAREA>
			</li>
			<li>
				<label for="ship_note">Shipping Note Content&#160;<em>*</em></label>
				<TEXTAREA id="ship_note" name="ship_note" cols="40" rows="6" tabindex="8"><?php echo $ship_note; ?></TEXTAREA>
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