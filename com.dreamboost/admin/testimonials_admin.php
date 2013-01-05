<?php
// BME WMS
// Page: Testimonials Manager Homepage
// Path/File: /admin/testimonials_admin.php
// Version: 1.8
// Build: 1806
// Date: 05-13-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$main_id = $_POST['main_id'];
$email = $_POST['email'];
$notify_owner = $_POST['notify_owner'];
$entries_per_page = $_POST['entries_per_page'];
$test_page = $_POST['test_page'];
$submit = $_POST['submit'];

include './includes/wms_nav1.php';
$manager = "testimonials";
$page = "Testimonials Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($main_id) {
	//Add HTML code
	$test_page = str_replace("\n", "<br>", $test_page);

	//Check for Errors
	$error_txt = "";
	if($email == "") { $error_txt .= "Error, the email address is blank. There needs to be an email address.<br>\n"; }
	if($entries_per_page == "") { $error_txt .= "Error, the Testimonials per Page entry is blank. Please complete this.<br>\n"; }
	if($test_page == "") { $error_txt .= "Error, the Testimonials Page Content field is blank. There needs to be testimonials page content.<br>\n"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE testimonials_main SET email='$email', notify_owner='$notify_owner', entries_per_page='$entries_per_page', test_page='$test_page' WHERE main_id='$main_id'";
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

<tr><td align="left"><font size="2">Welcome to the Testimonials Manager, where you manage the testimonials section of your website. Below are the general settings and statistics for your Testimonials Manager. On the following pages is where you control the heart of the system.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$test_counter1 = 0;
$test_counter2 = 0;
$test_counter3 = 0;
$test_counter4 = 0;
$query = "SELECT status FROM testimonials";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$status = $line["status"];
	if($status == "0") { $test_counter2 = $test_counter2 + 1; }
	if($status == "1") { $test_counter3 = $test_counter3 + 1; }
	if($status == "2") { $test_counter4 = $test_counter4 + 1; }
}
$test_counter1 = $test_counter2 + $test_counter3 + $test_counter4;
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">There have been <b><?php echo $test_counter1; ?></b> Testimonials Submitted<br>
There are <b><?php echo $test_counter2; ?></b> Testimonials waiting To Be Reviewed<br>
There have been <b><?php echo $test_counter3; ?></b> Testimonials Approved<br>
There have been <b><?php echo $test_counter4; ?></b> Testimonials Rejected</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT main_id, email, notify_owner, entries_per_page, test_page FROM testimonials_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$main_id = $line["main_id"];
	$email = $line["email"];
	$notify_owner = $line["notify_owner"];
	$entries_per_page = $line["entries_per_page"];
	$test_page = $line["test_page"];
}
mysql_free_result($result);

$test_page = str_replace("\n", "", $test_page);
$test_page = str_replace("<br>", "\n", $test_page);
?>

<tr><td align="left">
	<FORM name="testimonials" Method="POST" ACTION="./testimonials_admin.php" class="wmsform">
	<input type="hidden" name="main_id" value="<?php echo $main_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Testimonials Manager Settings</legend>
		<ol>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="100" value="<?php echo $email; ?>" tabindex="1" />
			</li>
			<li>
				<label for="notify_owner">Notify when Testimonials Submitted <em>*</em></label>
				<select id="notify_owner" name="notify_owner" tabindex="2">
				<option value="0"<?php if($notify_owner == "0") { echo " SELECTED"; } ?>>No</option>
				<option value="1"<?php if($notify_owner == "1") { echo " SELECTED"; } ?>>Yes</option>
				</select>
			</li>
			<li>
				<label for="entries_per_page">Testimonials per Page <em>*</em></label>
				<INPUT type="text" id="entries_per_page" name="entries_per_page" size="3" maxlength="3" value="<?php echo $entries_per_page; ?>" tabindex="3" />
			</li>
			<li>
				<label for="test_page">Testimonials Page Content <em>*</em></label>
				<TEXTAREA id="test_page" name="test_page" cols="40" rows="9" tabindex="4"><?php echo $test_page; ?></TEXTAREA>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save Info">
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