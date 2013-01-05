<?php
// BME WMS
// Page: Search Engine Manager Update Data page
// Path/File: /admin/search_engine_admin2.php
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

$this_user_id = $_COOKIE["wms_user"];

$submit = $_POST["submit"];
$urls = $_POST["urls"];
$keywords = $_POST["keywords"];
$webmaster_email = $_POST["webmaster_email"];

include './includes/wms_nav1.php';
$manager = "search_engine";
$page = "Search Engine Manager > Update Info";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "")  {
	//Validate
	$error_txt = "";
	if($urls == "") { $error_txt .= "Error, the URLs field is blank. This field is required.<br>\n"; }
	if($keywords == "") { $error_txt .= "Error, the Keywords field is blank. This field is required.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]+))*$",$webmaster_email) ){
		$error_txt .= "Error, you did not enter your email address or it is incorrect. We need your email address to let you know when a search engine update is requested.<br>\n";
	}

	if($error_txt == "") {
	$now = date("Y-m-d H:i:s");
	$query = "UPDATE search_engine SET last_updated='$now', urls='$urls', keywords='$keywords', webmaster_email='$webmaster_email' WHERE search_engine_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}

	$query = "SELECT requested_update, webadmin_email FROM search_engine WHERE search_engine_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$requested_update = $line["requested_update"];
		$webadmin_email = $line["webadmin_email"];
	}
	mysql_free_result($result);

	$query = "SELECT content, subject FROM search_engine_emails WHERE seemails_id='2'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$content = $line["content"];
		$subject = $line["subject"];
	}
	mysql_free_result($result);

	$email_str = "";
	$email_str .= $content;
	$email_str .= "\n\n";
	$email_str .= "Link: " . $base_url . "admin/search_engine_admin.php";
	$email_str .= "\n\n";
	$email_str .= "Request made at: " . $requested_update . "\n";
	$email_str .= "Update completed at: " . $now;
	$email_str .= "\n\n";
			
	$email_subj = $subject . " - " . $base_url;
	$email_from = "FROM: " . $webmaster_email;
	mail($webadmin_email, $email_subj, $email_str, $email_from);

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

<tr><td align="left"><font size="2">Welcome to the Search Engine Manager, on this page you can update the data for this website's search engine.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT urls, keywords, webmaster_email FROM search_engine WHERE search_engine_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$urls = $line["urls"];
	$keywords = $line["keywords"];
	$webmaster_email = $line["webmaster_email"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="search_engine" Method="POST" ACTION="./search_engine_admin2.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Update Search Engine Data</legend>
		<ol>
			<li>
				<label for="urls">URLs <em>*</em></label>
				<INPUT type="text" id="urls" name="urls" size="30" maxlength="11" value="<?php echo $urls; ?>" tabindex="1" />
			</li>
			<li>
				<label for="keywords">Keywords <em>*</em></label>
				<INPUT type="text" id="keywords" name="keywords" size="30" maxlength="11" value="<?php echo $keywords; ?>" tabindex="2" />
			</li>
			<li>
				<label for="webmaster_email">E-Mail Address to send Update Requests <em>*</em></label>
				<INPUT type="text" id="webmaster_email" name="webmaster_email" size="30" maxlength="150" value="<?php echo $webmaster_email; ?>" tabindex="3" />
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save Values">
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