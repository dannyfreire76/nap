<?php
// BME WMS
// Page: CID page
// Path/File: /wc/cid.php
// Version: 1.1
// Build: 1101
// Date: 12-01-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: What is a Security Code?</title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">What is a Security Code?</font><br>
<br>
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">The Security Code is a three (3) or four (4) digit number listed on the back of your credit card immediately following your card number. (On American Express cards, the security code may be on the front.)<br>
<br>
This number prevents fraudulent charges to your credit card, such as someone stealing your credit card receipt and using that information to make a purchase.<br>
<br>
Note: Some older cards may not have a Security Code. In these cases, simply leave the Security Code field blank.<br>
<br>
<a href="javascript:window.close()">Close</a></font>
<?php
mysql_close($dbh);
?>
</body>
</html>