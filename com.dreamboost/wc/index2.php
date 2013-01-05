<?php
// BME WMS
// Page: Wholesale Catalog Homepage
// Path/File: /wc/index2.php
// Version: 1.1
// Build: 1104
// Date: 12-27-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/wc1.php';

check_wholesale_login();

$query = "SELECT contact_name FROM retailer WHERE retailer_id='".$_SESSION['wc_user']."'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$contact_name = stripslashes($line["contact_name"]);
}
mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog</title>
</head>
<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left">Welcome <?php echo $contact_name; ?>.</td></tr>

<?php
// Display Blank Email Message
$retval = check_email_address($_SESSION['wc_user']);

if($retval > 0) {
	echo "<tr><td align=\"left\"><font face=\"";
	echo $font;
	echo "\" color=\"#";
	echo $fontcolor;
	echo "\" size=\"4\">";
	echo "We do not have your current email address. Please <a href=\"./my/update_retailer.php\">update your information now</a>.";
	echo "</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

//Error Messages
if($error_txt) {
	echo "<tr><td><font face=\"Arial\" size=\"4\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>


</table>

<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>