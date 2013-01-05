<?php
// BME WMS
// Page: Shipping Information page
// Path/File: /wc/shipping.php
// Version: 1.1
// Build: 1101
// Date: 10-30-2006

$retailer_id = $_COOKIE["wc_user"];
if(!$retailer_id) {
	header("Location: " . $base_url . "wc/index.php");
	exit;
}
header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store - Shipping Information</title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head_wc1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Wholesale Catalog: Shipping Information</font></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">We ship domestically and internationally. Shipping and handling costs vary depending on where the package is being shipped to. For orders shipping within the United States, shipping and handling is $4.30. Free shipping on all orders over $75 and shipping to the U.S.<br>
<br>
Salvia / SALVIA ZONE is completely legal in the United States, with the exception of Delaware, Louisiana, and Missouri. Salvia / SALVIA ZONE is also completely legal in most countries in the world. Only Australia, Denmark, Finland, and Italy have certain restrictions to date thus we can not sell to or ship to persons in the specified states and countries.</font></td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot_wc1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>