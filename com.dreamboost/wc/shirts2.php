<?php
// BME WMS
// Page: Wholesale Catalog Product page
// Path/File: /wc/shirts2.php
// Version: 1.1
// Build: 1102
// Date: 11-21-2006

$retailer_id = $_COOKIE["wc_user"];
$retailer_status = $_COOKIE["wc_status"];
if(!$retailer_id) {
	header("Location: " . $base_url . "wc/index.php");
	exit;
}
header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$query = "SELECT name FROM products WHERE prod_id='8' AND display_on_website='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name = $line["name"];
}
mysql_free_result($result);

$query = "SELECT name FROM product_categories WHERE prod_cat_id='5' AND display_on_website='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog - <?php echo $cat_name; ?>: <?php echo $name; ?></title>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head_wc1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="5">Wholesale Catalog: <?php echo $cat_name; ?> - <?php echo $name; ?></font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="center" VALIGN="TOP"><a href="./shirts.php"><img src="../images/store_tshirt1big.jpg" border="0" width="676" height="771" alt="Salvia Zone Tee Shirts"></a><br>
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4"><a href="./shirts.php">Click to Go Back</a></font></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot_wc1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>