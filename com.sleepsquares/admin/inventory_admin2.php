<?php
// BME WMS
// Page: Inventory Manager Manage Email page
// Path/File: /admin/inventory_admin2.php
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
$emailto = $_POST["emailto"];
$inemails_id = $_POST["inemails_id"];

include './includes/wms_nav1.php';
$manager = "inventory";
$page = "Inventory Manager > Manage Inventory E-Mails";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($inemails_id != "") {
	//Validate
	$error_txt = "";
	if($subject == "") { $error_txt .= "The Subject is blank. You must enter a subject.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$email) ){
		$error_txt .= "The Email Address is blank or incorrect. You must enter an email address.<br>\n";
	}
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$emailto) ){
		$error_txt .= "The Email To Address is blank or incorrect. You must enter an email address.<br>\n";
	}
	if($content == "") { $error_txt .= "The Email Message Content is blank. You must enter information for the message.<br>\n"; }
	
	if($error_txt == "") {
		//Update DB
		$query = "UPDATE inventory_emails SET content='$content', subject='$subject', email='$email', emailto='$emailto' WHERE inemails_id='$inemails_id'";
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

<tr><td align="left"><font size="2">This is the E-Mail Message sent automatically when the stock level of a product drops below the threshold level. You can edit the E-Mail Messages, subjects, and from addresses below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT inemails_id, content, subject, email, emailto FROM inventory_emails WHERE inemails_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$subject = $line["subject"];
	$email = $line["email"];
	$emailto = $line["emailto"];
	$content = $line["content"];
	$inemails_id = $line["inemails_id"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="inventory-emails" Method="POST" ACTION="./inventory_admin2.php" class="wmsform">
	<input type="hidden" name="inemails_id" value="<?php echo $line["inemails_id"]; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Message sent when the stock level of a product drops below threshold</legend>
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
				<label for="emailto">E-Mail To <em>*</em></label>
				<INPUT type="text" id="emailto" name="emailto" size="30" maxlength="100" value="<?php echo $emailto; ?>" tabindex="3" />
			</li>
			<li>
				<label for="content">E-Mail Message Content <em>*</em></label>
				<TEXTAREA id="content" name="content" cols="35" rows="7" tabindex="4"><?php echo $content; ?></TEXTAREA>
			</li>
			<li class="fm-button">
				<input type="submit" id="emails" name="emails" value="Edit E-Mail Message">
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