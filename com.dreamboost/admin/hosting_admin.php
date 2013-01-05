<?php
// BME WMS
// Page: Hosting Admin Homepage
// Path/File: /admin/hosting_admin.php
// Version: 1.8
// Build: 1801
// Date: 05-20-2007

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
$domain = $_POST['domain'];
$username = $_POST['username'];
$password = $_POST['password'];

include './includes/wms_nav1.php';
$manager = "hosting";
$page = "Hosting Manager > Homepage";
$url = 'hosting_admin.php';
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$error_txt = "";
	if($domain == "") { $error_txt .= "Error, you did not enter a Domain for your Hosting Account. Please enter your Hosting Account Domain.<br>\n"; }
	if($username == "") { $error_txt .= "Error, you did not enter a Username for your Hosting Account. Please enter your Hosting Account Username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a Password for your Hosting Account. Please enter your Hosting Account Password.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE hosting_main SET domain='$domain', username='$username', password='$password' WHERE hosting_main_id='1'";
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

<tr><td align="left"><font size="2">Welcome to the Hosting Manager, where you manage your website hosting account.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td>&nbsp;</td></tr>
<?php
$query = "SELECT domain, username, password FROM hosting_main WHERE hosting_main_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$domain = $line['domain'];
	$username = $line['username'];
	$password = $line['password'];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="hosting" Method="POST" ACTION="./hosting_admin.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Hosting Manager Global Settings</legend>
		<ol>
			<li>
				<label for="domain">Domain Name <em>*</em></label>
				<INPUT type="text" id="domain" name="domain" size="30" maxlength="150" value="<?php echo $domain; ?>" tabindex="1" />
			</li>
			<li>
				<label for="username">Username <em>*</em></label>
				<INPUT type="text" id="username" name="username" size="30" maxlength="100" value="<?php echo $username; ?>" tabindex="2" />
			</li>
			<li>
				<label for="password">Password <em>*</em></label>
				<INPUT type="text" id="password" name="password" size="30" maxlength="100" value="<?php echo $password; ?>" tabindex="3" />
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