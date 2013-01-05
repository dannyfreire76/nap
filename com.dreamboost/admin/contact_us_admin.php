<?php
// BME WMS
// Page: Contact Us Manager Homepage
// Path/File: /admin/contact_us_admin.php
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

$submit = $_POST["submit"];
$email = $_POST["email"];
$notify_owner = $_POST["notify_owner"];
$contact_main_id = $_POST["contact_main_id"];

include './includes/wms_nav1.php';
$manager = "contact_us";
$page = "Contact Us Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$email) ){
		$error_txt .= "The Email Address field is blank or you entered the address incorrectly. ";
		$error_txt .= "Please complete this field.<br>\n";
	}

	if($error_txt == "") {
		$query = "UPDATE contact_us_main SET email='$email', notify_owner='$notify_owner' WHERE contact_main_id='$contact_main_id'";
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

<tr><td align="left"><font size="2">Welcome to the Contact Us Manager, where you manage all the incoming contact e-mail requiring answers. On this page you will find general statistics about the waiting e-mail and e-mail you've answered. There are some general Contact Us Manager wide variables to set and change when needed below. As well, please click through to the other pages of the Contact Us Manager to manage other portions.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
$query = "SELECT created, answered FROM contact_us";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$email_counter1 = 0;
$email_counter2 = 0;
$email_counter3 = 0;

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if($line["created"] != "") { $email_counter1++; }
	if($line["answered"] != "0000-00-00 00:00:00") { $email_counter2++; }
	if($line["answered"] == "0000-00-00 00:00:00") { $email_counter3++; }
}
mysql_free_result($result);

?>
<tr><td align="left"><font size="2">There are currently <b><?php echo $email_counter3; ?></b> Unanswered Contacts Waiting<br>
There are currently <b><?php echo $email_counter2; ?></b> Answered Contacts<br>
There are currently <b><?php echo $email_counter1; ?></b> Total Contacts<br></font></td></tr>

<tr><td>&nbsp;</td></tr>
<?php
	$query = "SELECT contact_main_id, email, notify_owner FROM contact_us_main";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$contact_main_id = $line["contact_main_id"];
		$email = $line["email"];
		$notify_owner = $line["notify_owner"];
	}
	mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="contact" Method="POST" ACTION="./contact_us_admin.php" class="wmsform">
	<input type="hidden" name="contact_main_id" value="<?php echo $contact_main_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Contact Us Manager Settings</legend>
		<ol>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="150" value="<?php echo $email; ?>" tabindex="1" />
			</li>
			<li>
				<label for="notify_owner">Notify When Contact Us Submitted <em>*</em></label>
				<select id="notify_owner" name="notify_owner" tabindex="2">
				<option value="0"<?php if($notify_owner == "0") { echo " SELECTED"; } ?>>No</option>
				<option value="1"<?php if($notify_owner == "1") { echo " SELECTED"; } ?>>Yes</option>
				</select>
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
