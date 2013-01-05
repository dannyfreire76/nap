<?php
// BME WMS
// Page: Search Engine Admin Homepage
// Path/File: /admin/search_engine_admin.php
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
$webadmin_email = $_POST["webadmin_email"];
$update = $_POST["update"];

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "search_engine";
$page = "Search Engine Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$webadmin_email) ){
		$error_txt .= "Error, you did not enter your email address or it is incorrect. We need your email address to let you know when the search engine update is complete.<br>\n";
	}
	
	
	if($error_txt == "") {
	$query = "UPDATE search_engine SET webadmin_email='$webadmin_email' WHERE search_engine_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
	
}

if($update != "") {
	$now = date("Y-m-d H:i:s");
	$query = "UPDATE search_engine SET requested_update='$now' WHERE search_engine_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());

	$query = "SELECT webmaster_email FROM search_engine WHERE search_engine_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$webmaster_email = $line["webmaster_email"];
	}
	mysql_free_result($result);

	$query = "SELECT content, subject FROM search_engine_emails WHERE seemails_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$content = $line["content"];
		$subject = $line["subject"];
	}
	mysql_free_result($result);

	$email_str = "";
	$email_str .= $content;
	$email_str .= "\n\n";
	$email_str .= "My Website is: " . $base_url . "\n";
	$email_str .= "\n";
	$email_str .= "Link: " . $base_url . "admin/search_engine_admin2.php";
	$email_str .= "\n\n";
	$email_str .= "Request made at: " . $now;
	$email_str .= "\n\n";
			
	$email_subj = $subject . " - " . $base_url;
	$email_from = "FROM: " . $webadmin_email;
	mail($webmaster_email, $email_subj, $email_str, $email_from);
	
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

<tr><td align="left"><font size="2">Welcome to the Search Engine Manager, where you manage the Search Engine section of your website. On this page you will find general statistics about your Search Engine. As well, please click through to the other pages of the Search Engine Manager to manage other portions.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><font size="2"><b>Search Engine Statistics</b></font></td></tr>

<?php
$query = "SELECT last_updated, urls, keywords, webadmin_email FROM search_engine WHERE search_engine_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<tr><td align=\"left\"><font size=\"2\">URLs: ";
	echo $line["urls"];
	echo "</font></td></tr>\n";
	echo "<tr><td align=\"left\"><font size=\"2\">Keywords: ";
	echo $line["keywords"];
	echo "</font></td></tr>\n";
	echo "<tr><td align=\"left\"><font size=\"2\">Last Updated: ";
	list($updated_date, $updated_time) = split(' ', $line["last_updated"]);
	list($updated_yr, $updated_mn, $updated_dy) = split('-', $updated_date);
	echo $updated_mn . "/" . $updated_dy . "/" . $updated_yr . " " . $updated_time;
	echo "</font></td></tr>\n";
	
	echo "<tr><td>&nbsp;</td></tr>\n";

	$webadmin_email = $line["webadmin_email"];
	
}
mysql_free_result($result);
?>
<tr><td align="left">
	<FORM name="search_engine" Method="POST" ACTION="./search_engine_admin.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>E-Mail Address to send Update Notices to</legend>
		<ol>
			<li>
				<label for="webadmin_email">E-Mail <em>*</em></label>
				<INPUT type="text" id="webadmin_email" name="webadmin_email" size="30" maxlength="150" value="<?php echo $webadmin_email; ?>" tabindex="1" />
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save Info">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<FORM name="search_engine2" Method="POST" ACTION="./search_engine_admin.php" class="wmsform">
	<input type="hidden" name="webadmin_email" value="<?php echo $webadmin_email; ?>">
	<fieldset>
		<legend>Request a Search Engine Update</legend>
		<ol>
			<li class="fm-button">
				<input type="submit" id="update" name="update" value="Request Update">
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