<?php
// BME WMS
// Page: Contact Us Manager Manage Email page
// Path/File: /admin/contact_us_admin4.php
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

$content = $_POST["content"];
$subject = $_POST["subject"];
$email = $_POST["email"];
$cuemails_id = $_POST["cuemails_id"];

include './includes/wms_nav1.php';
$manager = "contact_us";
$page = "Contact Us Manager > Manage Contact Us E-Mails";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($cuemails_id != "") {
	//Validate
	$error_txt = "";
	if($subject == "") { $error_txt .= "The Subject is blank. You must enter a subject.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$email) ){
		$error_txt .= "The Email Address is blank or incorrect. You must enter an email address.<br>\n";
	}
	if($content == "") { $error_txt .= "The Email Message Content is blank. You must enter information for the message.<br>\n"; }
	
	if($error_txt == "") {
		//Update DB
		$query = "UPDATE contact_us_emails SET content='$content', subject='$subject', email='$email' WHERE cuemails_id='$cuemails_id'";
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

<tr><td align="left"><font size="2">These are the e-mail messages sent to the users automatically at different stages in the Contact Us process. You can edit the e-mail messages, subjects, and from addresses below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT cuemails_id, content, subject, email FROM contact_us_emails WHERE cuemails_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$subject = $line["subject"];
	$email = $line["email"];
	$content = $line["content"];
	$cuemails_id = $line["cuemails_id"];
}
mysql_free_result($result);

?>

<tr><td align="left">
	<FORM name="contact_us" Method="POST" ACTION="./contact_us_admin4.php" class="wmsform">
	<input type="hidden" name="cuemails_id" value="<?php echo $cuemails_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Message sent when user first submits their Contact Us Entry</legend>
		<ol>
			<li>
				<label for="subject">Subject <em>*</em></label>
				<INPUT type="text" id="subject" name="subject" size="30" maxlength="255" value="<?php echo $subject; ?>" tabindex="1" />
			</li>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="100" value="<?php echo $email; ?>" tabindex="2" />
			</li>
			<li>
				<label for="content">E-Mail Message Content <em>*</em></label>
				<TEXTAREA id="content" name="content" cols="35" rows="7" tabindex="3"><?php echo $content; ?></TEXTAREA>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Edit E-Mail Message">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT cuemails_id, content, subject, email FROM contact_us_emails WHERE cuemails_id='2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$subject = $line["subject"];
	$email = $line["email"];
	$content = $line["content"];
	$cuemails_id = $line["cuemails_id"];
}
mysql_free_result($result);

?>

<tr><td align="left">
	<FORM name="contact_us2" Method="POST" ACTION="./contact_us_admin4.php" class="wmsform">
	<input type="hidden" name="cuemails_id" value="<?php echo $cuemails_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Message sent when Contact Us Submission received</legend>
		<ol>
			<li>
				<label for="subject">Subject <em>*</em></label>
				<INPUT type="text" id="subject" name="subject" size="30" maxlength="255" value="<?php echo $subject; ?>" tabindex="1" />
			</li>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="100" value="<?php echo $email; ?>" tabindex="2" />
			</li>
			<li>
				<label for="content">E-Mail Message Content <em>*</em></label>
				<TEXTAREA id="content" name="content" cols="35" rows="7" tabindex="3"><?php echo $content; ?></TEXTAREA>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Edit E-Mail Message">
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