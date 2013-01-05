<?php
// BME WMS
// Page: Support Manager FAQs and Documentation page
// Path/File: /admin/support_admin2.php
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

include './includes/wms_nav1.php';
$manager = "support";
$page = "Support Manager > FAQs and Documentation";
wms_manager_nav2($manager);
wms_page_nav2($manager);
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

<tr><td align="left"><font size="2">Welcome to the Support Manager, where you can find answers on how to use the entire MyBWMS system or get help from our staff. Below you will find links to FAQs and Documentation on each Manager you own where you can find the most up-to-date information on your product.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font face=\"Verdana\" size=\"3\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<?php
$site_managers = check_managers_for_site($wms_id);

echo "<tr><td align=\"left\"><font size=\"2\">View MyBWMS System FAQs and Documentation</font></td></tr>\n";

if($site_managers['shipping'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Shipping Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['members'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Members Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['inventory'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Inventory Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['products'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Products Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['content'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Content Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['lynkstation'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View LynkStation Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['faqs'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View FAQs Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['contact_us'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Contact Us Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['testimonials'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Testimonials Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['leads'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Leads Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['retailers'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Retailers Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['search_engine'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Search Engine Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['email_lists'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View E-Mail Lists Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['meta_tag'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Meta Tag Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['photos'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Photos Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['surveys'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Surveys Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['polls'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Polls Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['reports'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Reports Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['users'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View MyBWMS Users Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['hosting'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Hosting Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['support'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Support Manager FAQs and Documentation</font></td></tr>\n";
}
if($site_managers['merchant_acct'] == "1") {
	echo "<tr><td align=\"left\"><font size=\"2\">View Merchant Account Manager FAQs and Documentation</font></td></tr>\n";
}
?>

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