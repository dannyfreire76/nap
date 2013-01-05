<?php
// BME WMS
// Page: LynkStation Manage Email page
// Path/File: /admin/lynkstation_admin6.php
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
$lsemails_id = $_POST["lsemails_id"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Manage LynkStation E-Mails";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($lsemails_id != "") {
	//Validate
	$error_txt = "";
	if($subject == "") { $error_txt .= "The Subject is blank. You must enter a subject.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "The Email Address is blank or incorrect. You must enter an email address.<br>\n";
	}
	if($content == "") { $error_txt .= "The Email Message Content is blank. You must enter information for the message.<br>\n"; }
	
	if($error_txt == "") {
		//Update DB
		$query = "UPDATE lynkstation_emails SET content='$content', subject='$subject', email='$email' WHERE lsemails_id='$lsemails_id'";
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

<tr><td align="left"><font size="2">These are the e-mail messages sent to the users automatically at different stages in the process of their website link being sent, approved, and/or rejected. You can edit the e-mail messages, subjects, and from addresses below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><font size="2"><table border="0">
<tr><td colspan="2"><font size="2"><b>E-Mail Message sent when user first submits their Website Link</b></font></td></tr>
<?php
$query = "SELECT lsemails_id, content, subject, email FROM lynkstation_emails WHERE lsemails_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><form action=\"./lynkstation_admin6.php\" method=\"POST\"><td><font size=\"2\">Subject:</font></td><td><input type=\"text\" name=\"subject\" size=\"40\" maxlength=\"255\" value=\"";
	echo $line["subject"];
	echo "\"></td></tr>\n";
	echo "<tr><td><font size=\"2\">E-Mail:</font></td><td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><font size=\"2\">E-Mail Message Content:<br>Before Message comes Dear and user's name</font><br><TEXTAREA name=\"content\" cols=\"50\" rows=\"4\">";
	echo $line["content"];
	echo "</TEXTAREA></td></tr>\n";
	echo "<tr><input type=\"hidden\" name=\"lsemails_id\" value=\"";
	echo $line["lsemails_id"];
	echo "\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Edit Email Message\"></td></tr></form>\n";
}
mysql_free_result($result);

?>
</td></tr></table>
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><table border="0">
<tr><td colspan="2"><font size="2"><b>E-Mail Message sent when user's Website Link is Approved</b></font></td></tr>
<?php
$query = "SELECT lsemails_id, content, subject, email FROM lynkstation_emails WHERE lsemails_id='2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><form action=\"./lynkstation_admin6.php\" method=\"POST\"><td><font face=\"Arial\" size=\"+1\">Subject:</font></td><td><input type=\"text\" name=\"subject\" size=\"40\" maxlength=\"255\" value=\"";
	echo $line["subject"];
	echo "\"></td></tr>\n";
	echo "<tr><td><font size=\"2\">E-Mail:</font></td><td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><font face=\"Arial\" size=\"+1\">Email Message Content:<br>Before Message comes Dear and user's name</font><br><TEXTAREA name=\"content\" cols=\"50\" rows=\"4\">";
	echo $line["content"];
	echo "</TEXTAREA></td></tr>\n";
	echo "<tr><input type=\"hidden\" name=\"lsemails_id\" value=\"";
	echo $line["lsemails_id"];
	echo "\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Edit Email Message\"></td></tr></form>\n";
}
mysql_free_result($result);

?>
</td></tr></table>
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><table border="0">
<tr><td colspan="2"><font size="2"><b>E-Mail Message sent when user's Website Link is Rejected</b></font></td></tr>
<?php
$query = "SELECT lsemails_id, content, subject, email FROM lynkstation_emails WHERE lsemails_id='3'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><form action=\"./lynkstation_admin6.php\" method=\"POST\"><td><font face=\"Arial\" size=\"+1\">Subject:</font></td><td><input type=\"text\" name=\"subject\" size=\"40\" maxlength=\"255\" value=\"";
	echo $line["subject"];
	echo "\"></td></tr>\n";
	echo "<tr><td><font face=\"Arial\" size=\"+1\">Email:</font></td><td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><font face=\"Arial\" size=\"+1\">Email Message Content:<br>Before Message comes Dear and user's name</font><br><TEXTAREA name=\"content\" cols=\"50\" rows=\"4\">";
	echo $line["content"];
	echo "</TEXTAREA></td></tr>\n";
	echo "<tr><input type=\"hidden\" name=\"lsemails_id\" value=\"";
	echo $line["lsemails_id"];
	echo "\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Edit Email Message\"></td></tr></form>\n";
}
mysql_free_result($result);

?>
</td></tr></table>
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><table border="0">
<tr><td colspan="2"><font size="2"><b>E-Mail Message sent to Owner when user first submits Website Link</b></font></td></tr>
<?php
$query = "SELECT lsemails_id, content, subject, email FROM lynkstation_emails WHERE lsemails_id='4'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><form action=\"./lynkstation_admin6.php\" method=\"POST\"><td><font face=\"Arial\" size=\"+1\">Subject:</font></td><td><input type=\"text\" name=\"subject\" size=\"40\" maxlength=\"255\" value=\"";
	echo $line["subject"];
	echo "\"></td></tr>\n";
	echo "<tr><td><font face=\"Arial\" size=\"+1\">Email:</font></td><td><input type=\"text\" name=\"email\" size=\"40\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td></tr>\n";
	echo "<tr><td colspan=\"2\"><font face=\"Arial\" size=\"+1\">Email Message Content:<br>Before Message comes Dear and user's name</font><br><TEXTAREA name=\"content\" cols=\"50\" rows=\"4\">";
	echo $line["content"];
	echo "</TEXTAREA></td></tr>\n";
	echo "<tr><input type=\"hidden\" name=\"lsemails_id\" value=\"";
	echo $line["lsemails_id"];
	echo "\"><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"Edit Email Message\"></td></tr></form>\n";
}
mysql_free_result($result);

?>
</td></tr></table>
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