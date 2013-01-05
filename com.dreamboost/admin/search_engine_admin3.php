<?php
// BME WMS
// Page: Search Engine Manage Search Engine E-Mails page
// Path/File: /admin/search_engine_admin3.php
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
$seemails_id = $_POST["seemails_id"];

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "search_engine";
$page = "Search Engine Manager > Manage Search Engine E-Mails";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($seemails_id != "") {
	//Validate
	$error_txt = "";
	if($subject == "") { $error_txt .= "The Subject is blank. You must enter a subject.<br>\n"; }
	if($content == "") { $error_txt .= "The Email Message Content is blank. You must enter information for the message.<br>\n"; }
	
	if($error_txt == "") {
		//Update DB
		$query = "UPDATE search_engine_emails SET content='$content', subject='$subject' WHERE seemails_id='$seemails_id'";
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

<tr><td align="left"><font size="2">These are the E-Mail Messages sent to the users automatically at different stages in the process of search engine request update and update complete process.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT seemails_id, content, subject FROM search_engine_emails WHERE seemails_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$subject = $line["subject"];
	$content = $line["content"];
	$seemails_id = $line["seemails_id"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="search_engine" Method="POST" ACTION="./search_engine_admin3.php" class="wmsform">
	<input type="hidden" name="seemails_id" value="<?php echo $seemails_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Message sent when user requests a Search Engine Update</legend>
		<ol>
			<li>
				<label for="subject">Subject <em>*</em></label>
				<INPUT type="text" id="subject" name="subject" size="30" maxlength="255" value="<?php echo $subject; ?>" tabindex="1" />
			</li>
			<li>
				<label for="content">E-Mail Message Content <em>*</em></label>
				<TEXTAREA id="content" name="content" cols="35" rows="7" tabindex="2"><?php echo $content; ?></TEXTAREA>
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
$query = "SELECT seemails_id, content, subject FROM search_engine_emails WHERE seemails_id='2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$subject = $line["subject"];
	$content = $line["content"];
	$seemails_id = $line["seemails_id"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="search_engine2" Method="POST" ACTION="./search_engine_admin3.php" class="wmsform">
	<input type="hidden" name="seemails_id" value="<?php echo $seemails_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Message sent when Webmaster completes Search Engine update</legend>
		<ol>
			<li>
				<label for="subject">Subject <em>*</em></label>
				<INPUT type="text" id="subject" name="subject" size="30" maxlength="255" value="<?php echo $subject; ?>" tabindex="1" />
			</li>
			<li>
				<label for="content">E-Mail Message Content <em>*</em></label>
				<TEXTAREA id="content" name="content" cols="35" rows="7" tabindex="2"><?php echo $content; ?></TEXTAREA>
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