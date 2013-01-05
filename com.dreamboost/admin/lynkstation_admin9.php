<?php
// BME WMS
// Page: LynkStation Manager Send Email page
// Path/File: /admin/lynkstation_admin9.php
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
$email_tmp = $_POST["email_tmp"];
$subject = $_POST["subject"];
$content = $_POST["content"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Send E-Mails To All Users";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "SELECT name FROM lynkstation_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$lsname = $line["name"];
}
mysql_free_result($result);

if($submit != "") {
	//Check for Errors
	$error_txt = "";
	if($email_tmp == "") { $error_txt .= "Error, you did not include the Email From Address. There needs to be an Email From Address.<br>"; }
	if($subject == "") { $error_txt .= "Error, you did not include the Subject. There needs to be a Subject.<br>"; }
	if($content == "") { $error_txt .= "Error, you did not include the Contents of your email message. There needs to be Contents for your email message.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "SELECT DISTINCT email, website_url, category FROM lynkstation_links WHERE approved='1' ORDER BY email";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {

			//Send User Email Message
			$email = $line["email"];
			$website_url = $line["website_url"];
			$category = $line["category"];
			
			$query2 = "SELECT position FROM lynkstation_cats where name='$category'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$position = $line2["position"];
			}
			mysql_free_result($result2);
			
			$email_str = "";
			$content = str_replace("[singlequote]", "'", $content);
			$content = str_replace("[doublequote]", "\"", $content);
			$email_str .= $content;
			$email_str .= "\n\n";
			$email_str .= "Your website: " . $website_url . " is listed in our " . $lsname;
			$email_str .= " in the " . $category . " category at " . $base_url . "links/links";
			$email_str .= $position . ".php\n";
			$email_str .= "Thank you for exchanging links with us.\n\n";
			
			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($email, $email_subj, $email_str, $email_from);
		}
		mysql_free_result($result);	
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

<tr><td align="left"><font size="2">Use this page to send an e-mail message to each e-mail address in your LynkStation database. All duplicate e-mail addresses will be sent only one e-mail. The system will also insert where their link is posted on your LynkStation.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><table border="0">
<form action="./lynkstation_admin9.php" method="POST">
<tr><td><font size="2">E-Mail From Address: </font></td><td><input type="text" name="email_tmp" size="30" maxlength="100"></td></tr>
<tr><td><font size="2">Subject: </font></td><td><input type="text" name="subject" size="30" maxlength="200"></td></tr>
<tr><td colspan="2"><font size="2">E-Mail Contents: </font><br>
<TEXTAREA name="content" cols="45" rows="7"></TEXTAREA></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Send Emails"></td></tr>
</table></td></tr>

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