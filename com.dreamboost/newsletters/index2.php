<?php
// BME WMS
// Page: Newsletter Confirmation Thank You page
// Path/File: /newsletters/index2.php
// Version: 1.1
// Build: 1105
// Date: 09-26-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$confirm = $_GET["confirm"];
$member_id = $_GET["member_id"];

if($confirm == "1") {
	$query = "UPDATE news_member SET status='1' WHERE member_id='$member_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Newsletter Confirmation Thank You</title>

<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/meta1.php';
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2"><?php echo $website_title; ?> Newsletter Confirmation Thank You</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Thank you. You successfully confirmed your subscription to the <?php echo $website_title; ?> Website Newsletter. We'll be emailing you information regularly.</font></td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>