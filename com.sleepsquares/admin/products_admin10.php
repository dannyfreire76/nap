<?php
// BME WMS
// Page: Products Manager - Manage Discount Codes page
// Path/File: /admin/products_admin10.php
// Version: 1.8
// Build: 1801
// Date: 01-30-2007

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
$status = $_POST["status"];
$discount_code = $_POST["discount_code"];
$percent_off = $_POST["percent_off"];
$location_target = $_POST["location_target"];
$disc_code_id = $_POST["disc_code_id"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Manage Discount Codes";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Validate
	$error_txt = "";
	if($discount_code == "") { $error_txt .= "Error, the Discount Code is blank. You must enter a Discount Code.<br>\n"; }
	
	if($error_txt == "") {
		$discount_code = strtolower($discount_code);
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO discount_codes SET created='$now', status='$status', discount_code='$discount_code', percent_off='$percent_off', location_target='$location_target'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($status, $discount_code, $percent_off, $location_target, $create);
	}
} elseif($disc_code_id) {
	//Check for Errors
	$error_txt = "";
	if($discount_code == "") { $error_txt .= "Error, the Discount Code is blank. There needs to be a Discount Code.<br>"; }

	//If no Errors, Update DB
	if($error_txt == "") {
		$discount_code = strtolower($discount_code);
		$query = "UPDATE discount_codes SET status='$status', discount_code='$discount_code', percent_off='$percent_off', location_target='$location_target' WHERE disc_code_id='$disc_code_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($disc_code_id, $status, $discount_code, $percent_off, $location_target, $save);
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

<tr><td align="left"><font size="2">You can manage the Discount Codes for the Online Store of your website below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="products-discounts-create" Method="POST" ACTION="./products_admin10.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create a New Discount Code</legend>
		<ol>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="1">
				<option value="1"<?php if($status == "1") { echo " SELECTED"; } ?>>Active</option>
				<option value="2"<?php if($status == "2") { echo " SELECTED"; } ?>>Review</option>
				<option value="0"<?php if($status == "0") { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li>
				<label for="discount_code">Discount Code <em>*</em></label>
				<INPUT type="text" id="discount_code" name="discount_code" size="10" maxlength="10" value="<?php echo $discount_code; ?>" tabindex="2" />
			</li>
			<li>
				<label for="percent_off">Percent Off <em>*</em></label>
				<select id="percent_off" name="percent_off" tabindex="3">
				<option value="0.05"<?php if($percent_off == "0.05") { echo " SELECTED"; } ?>>5%</option>
				<option value="0.10"<?php if($percent_off == "0.10") { echo " SELECTED"; } ?>>10%</option>
				<option value="0.15"<?php if($percent_off == "0.15") { echo " SELECTED"; } ?>>15%</option>
				<option value="0.20"<?php if($percent_off == "0.20") { echo " SELECTED"; } ?>>20%</option>
				<option value="0.25"<?php if($percent_off == "0.25") { echo " SELECTED"; } ?>>25%</option>
				<option value="0.30"<?php if($percent_off == "0.30") { echo " SELECTED"; } ?>>30%</option>
				<option value="0.35"<?php if($percent_off == "0.35") { echo " SELECTED"; } ?>>35%</option>				<option title="37% ?!??" value="0.37"<?php if($percent_off == "0.37") { echo " SELECTED"; } ?>>37%</option>
				<option value="0.40"<?php if($percent_off == "0.40") { echo " SELECTED"; } ?>>40%</option>
				<option value="0.45"<?php if($percent_off == "0.45") { echo " SELECTED"; } ?>>45%</option>
				<option value="0.50"<?php if($percent_off == "0.50") { echo " SELECTED"; } ?>>50%</option>
				<option value="0.55"<?php if($percent_off == "0.55") { echo " SELECTED"; } ?>>55%</option>
				<option value="0.60"<?php if($percent_off == "0.60") { echo " SELECTED"; } ?>>60%</option>
				<option value="0.65"<?php if($percent_off == "0.65") { echo " SELECTED"; } ?>>65%</option>
				<option value="0.70"<?php if($percent_off == "0.70") { echo " SELECTED"; } ?>>70%</option>
				<option value="0.75"<?php if($percent_off == "0.75") { echo " SELECTED"; } ?>>75%</option>
				</select>
			</li>
			<li class="fm-optional">
				<label for="location_target">Targeted Location</label>
				<INPUT type="text" id="location_target" name="location_target" size="30" maxlength="255" value="<?php echo $location_target; ?>" tabindex="4" />
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create New Discount Code">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Status</th><th scope="col">Discount Code</th><th scope="col">Percent Off</th><th scope="col">Targeted Location</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT disc_code_id, status, discount_code, percent_off, location_target FROM discount_codes ORDER BY status, discount_code";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"products-discounts-manage\" Method=\"POST\" ACTION=\"./products_admin10.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo "<SELECT name=\"status\">";
	echo "<option value=\"1\"";
	if($line["status"] == "1") { echo " SELECTED"; }
	echo ">Active</option>\n";
	echo "<option value=\"2\"";
	if($line["status"] == "2") { echo " SELECTED"; }
	echo ">Review</option>\n";
	echo "<option value=\"0\"";
	if($line["status"] == "0") { echo " SELECTED"; }
	echo ">Inactive</option>\n";
	echo "</select></td>";

	echo "<td><input type=\"text\" name=\"discount_code\" size=\"10\" maxlength=\"10\" value=\"";
	echo $line["discount_code"];
	echo "\"></td>";
	
	echo "<td><SELECT name=\"percent_off\">";
	echo "<option value=\"0.05\"";
	if($line["percent_off"] == "0.05") { echo " SELECTED"; }
	echo ">5%</option>\n";
	echo "<option value=\"0.10\"";
	if($line["percent_off"] == "0.10") { echo " SELECTED"; }
	echo ">10%</option>\n";
	echo "<option value=\"0.15\"";
	if($line["percent_off"] == "0.15") { echo " SELECTED"; }
	echo ">15%</option>\n";
	echo "<option value=\"0.20\"";
	if($line["percent_off"] == "0.20") { echo " SELECTED"; }
	echo ">20%</option>\n";
	echo "<option value=\"0.25\"";
	if($line["percent_off"] == "0.25") { echo " SELECTED"; }
	echo ">25%</option>\n";
	echo "<option value=\"0.30\"";
	if($line["percent_off"] == "0.30") { echo " SELECTED"; }
	echo ">30%</option>\n";
	echo "<option value=\"0.35\"";
	if($line["percent_off"] == "0.35") { echo " SELECTED"; }
	echo ">35%</option>\n";	echo "<option title=\"37% ?!??\" value=\"0.37\"";
	if($line["percent_off"] == "0.37") { echo " SELECTED"; }	echo ">37%</option>\n";	echo "<option value=\"0.40\"";
	if($line["percent_off"] == "0.40") { echo " SELECTED"; }
	echo ">40%</option>\n";
	echo "<option value=\"0.45\"";
	if($line["percent_off"] == "0.45") { echo " SELECTED"; }
	echo ">45%</option>\n";
	echo "<option value=\"0.50\"";
	if($line["percent_off"] == "0.50") { echo " SELECTED"; }
	echo ">50%</option>\n";
	echo "<option value=\"0.55\"";
	if($line["percent_off"] == "0.55") { echo " SELECTED"; }
	echo ">55%</option>\n";
	echo "<option value=\"0.60\"";
	if($line["percent_off"] == "0.60") { echo " SELECTED"; }
	echo ">60%</option>\n";
	echo "<option value=\"0.65\"";
	if($line["percent_off"] == "0.65") { echo " SELECTED"; }
	echo ">65%</option>\n";
	echo "<option value=\"0.70\"";
	if($line["percent_off"] == "0.70") { echo " SELECTED"; }
	echo ">70%</option>\n";
	echo "<option value=\"0.75\"";
	if($line["percent_off"] == "0.75") { echo " SELECTED"; }
	echo ">75%</option>\n";
	echo "</select></td>";

	echo "<td><input type=\"text\" name=\"location_target\" size=\"30\" maxlength=\"255\" value=\"";
	echo $line["location_target"];
	echo "\"></td>";
	echo "<input type=\"hidden\" name=\"disc_code_id\" value=\"";
	echo $line["disc_code_id"];
	echo "\"><td align=\"center\"><input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr></form>\n";
}
mysql_free_result($result);
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