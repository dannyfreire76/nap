<?php
// BME WMS
// Page: Products Manager - Manage Promotions page
// Path/File: /admin/products_admin9.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

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
$buy_one_get_one_free = $_POST["buy_one_get_one_free"];
$main_id = $_POST["main_id"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Manage Promotions";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Validate
	$error_txt = "";
	//if($name == "") { $error_txt .= "Error, the Product Category Name is blank. You must enter a Name for this product category.<br>\n"; }
	
	if($error_txt == "") {
		$modified = date("Y-m-d H:i:s");
		$query = "UPDATE promo_main SET modified='$modified', buy_one_get_one_free='$buy_one_get_one_free' WHERE main_id='$main_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
}

$query = "SELECT main_id, buy_one_get_one_free FROM promo_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$main_id = $line["main_id"];
	$buy_one_get_one_free = $line["buy_one_get_one_free"];
}
mysql_free_result($result);

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

<tr><td align="left"><font size="2">You can manage the Promotions for your website below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="promos" Method="POST" ACTION="./products_admin9.php" class="wmsform">
	<input type="hidden" name="main_id" value="<?php echo $main_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Select Promotion Option</legend>
		<ol>
			<li>
				<label for="buy_one_get_one_free">Buy One Get One Free <em>*</em></label>
				<select id="buy_one_get_one_free" name="buy_one_get_one_free" tabindex="1">
				<option value="1"<?php if(isset($buy_one_get_one_free) && $buy_one_get_one_free == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($buy_one_get_one_free) && $buy_one_get_one_free == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Save">
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