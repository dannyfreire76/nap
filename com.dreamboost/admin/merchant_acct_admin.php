<?php
// BME WMS
// Page: Merchant Account Admin Homepage
// Path/File: /admin/merchant_acct_admin.php
// Version: 1.8
// Build: 1804
// Date: 03-25-2007

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
$company = $_POST["company"];
$url = $_POST["url"];
$username = $_POST["username"];
$password = $_POST["password"];
$signature = $_POST["signature"];
$status = $_POST["status"];
$merchant_acct_id = $_POST['merchant_acct_id'];

include './includes/wms_nav1.php';
$manager = "merchant_acct";
$page = "Merchant Account Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Validate
	$error_txt = "";
	if($url == "") { $error_txt .= "Error, you did not enter a URL for your Merchant Account. Please enter your Merchant Account URL.<br>\n"; }
	if($username == "") { $error_txt .= "Error, you did not enter a Username for your Merchant Account. Please enter your Merchant Account Username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a Password for your Merchant Account. Please enter your Merchant Account Password.<br>\n"; }
	
	if($error_txt == "") {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO merchant_acct SET created='$now', status='$status', company='$company', url='$url', username='$username', password='$password', signature='$signature'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($status, $company, $url, $username, $password, $signature, $create);
	}
} else if($merchant_acct_id) {
	//Validate
	$error_txt = "";
	if($url == "") { $error_txt .= "Error, you did not enter a URL for your Merchant Account. Please enter your Merchant Account URL.<br>\n"; }
	if($username == "") { $error_txt .= "Error, you did not enter a Username for your Merchant Account. Please enter your Merchant Account Username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a Password for your Merchant Account. Please enter your Merchant Account Password.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE merchant_acct SET status='$status', company='$company', url='$url', username='$username', password='$password', signature='$signature' WHERE merchant_acct_id='$merchant_acct_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($merchant_acct_id, $status, $company, $url, $username, $password, $signature, $save);
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

<tr><td align="left"><font size="2">Welcome to the Merchant Account Manager, where you manage the Merchant Account to be used in your Online Store, Wholesale Catalog, and Retailers Manager. Below find general settings for your Merchant Account.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$line_counter = 0;
$query = "SELECT company FROM merchant_acct WHERE status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["company"] == 1) {
		echo "<tr><td align=\"left\"><font size=\"2\"><a href=\"https://secure.authorize.net/\" TARGET=\"_BLANK\">Login to your Authorize.net Merchant Account</a></font></td></tr>\n";
	} elseif($line["company"] == 2) {
		echo "<tr><td align=\"left\"><font size=\"2\"><a href=\"https://www.sagepayments.net/virtualterminal/\" TARGET=\"_BLANK\">Login to your Sage Payments Merchant Account</a></font></td></tr>\n";
	} elseif($line["company"] == 3) {
		echo "<tr><td align=\"left\"><font size=\"2\"><a href=\"https://www.paypal.com/\" TARGET=\"_BLANK\">Login to your PayPal Account</a></font></td></tr>\n";		
	}
}
mysql_free_result($result);
?>

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<FORM name="merchant_acct_create" Method="POST" ACTION="./merchant_acct_admin.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create a new Merchant Account</legend>
		<ol>
			<li>
				<label for="company">Company <em>*</em></label>
				<select id="company" name="company" tabindex="1">
				<option value="1"<?php if($company == 1) { echo " SELECTED"; } ?>>Authorize.net</option>
				<option value="2"<?php if($company == 2) { echo " SELECTED"; } ?>>Sage Payments</option>
				<option value="3"<?php if($company == 3) { echo " SELECTED"; } ?>>PayPal</option>
				</select>
			</li>
			<li>
				<label for="url">URL <em>*</em></label>
				<INPUT type="text" id="url" name="url" size="30" maxlength="150" value="<?php echo $url; ?>" tabindex="2" />
			</li>
			<li>
				<label for="username">Username <em>*</em></label>
				<INPUT type="text" id="username" name="username" size="30" maxlength="30" value="<?php echo $username; ?>" tabindex="3" />
			</li>
			<li>
				<label for="password">Password <em>*</em></label>
				<INPUT type="text" id="password" name="password" size="30" maxlength="30" value="<?php echo $password; ?>" tabindex="4" />
			</li>
			<li>
				<label for="signature">Signature <em>*</em></label>
				<INPUT type="text" id="signature" name="signature" size="30" maxlength="255" value="<?php echo $signature; ?>" tabindex="5" />
			</li>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="6">
				<option value="1"<?php if($status == 1) { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if($status == 0) { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create Merchant Account">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Status</th><th scope="col">Company</th><th scope="col">URL</th><th scope="col">Username</th><th scope="col">Password</th><th scope="col">Signature</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT merchant_acct_id, status, company, url, username, password, signature FROM merchant_acct ORDER BY status DESC";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"merchant_acct_manage\" Method=\"POST\" ACTION=\"./merchant_acct_admin.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td><SELECT name=\"status\">";
	echo "<option value=\"1\"";
	if($line["status"] == "1") { echo " SELECTED"; }
	echo ">Active</option>";
	echo "<option value=\"0\"";
	if($line["status"] == "0") { echo " SELECTED"; }
	echo ">Inactive</option>";
	echo "</select></td>";

	echo "<td><SELECT name=\"company\">";
	echo "<option value=\"1\"";
	if($line["company"] == "1") { echo " SELECTED"; }
	echo ">Authorize.net</option>";
	echo "<option value=\"2\"";
	if($line["company"] == "2") { echo " SELECTED"; }
	echo ">Sage Payments</option>";
	echo "<option value=\"3\"";
	if($line["company"] == "3") { echo " SELECTED"; }
	echo ">PayPal</option>";
	echo "</select></td>";

	echo "<td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"150\" value=\"";
	echo $line["url"];
	echo "\"></td>";

	echo "<td><input type=\"text\" name=\"username\" size=\"30\" maxlength=\"30\" value=\"";
	echo $line["username"];
	echo "\"></td>";

	echo "<td><input type=\"text\" name=\"password\" size=\"30\" maxlength=\"30\" value=\"";
	echo $line["password"];
	echo "\"></td>";

	echo "<td><input type=\"text\" name=\"signature\" size=\"30\" maxlength=\"255\" value=\"";
	echo $line["signature"];
	echo "\"></td>";

	echo "<input type=\"hidden\" name=\"merchant_acct_id\" value=\"";
	echo $line["merchant_acct_id"];
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