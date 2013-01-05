<?php
// BME WMS
// Page: Shipping Information page
// Path/File: /store/shipping.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 600;

$query = "SELECT ship_page FROM ship_main WHERE ship_main_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$ship_page = $line["ship_page"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Shipping and Handling Information | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

  <TR>
	<TD align="left"><IMG height="34" alt="Online Store" 
	  src="/images/OnlineStore.gif" width="136" /></TD></TR>

<tr><td align="left" class="style4">Shipping Information</td></tr>

<tr><td align="left">
<br />
<?php
if ( $retailer_id ) {
    echo 'Shipping is based on weight, volume, and method.  During checkout, you will be given the opportunity to select the shipping method and carrier that works best for you.';
}
else {
    echo $ship_page;
	echo '<br />';
	echo '<table cellspacing="0" cellpadding="3" bordercolor="#000000" border="1" class="bordered_chart">';
	$queryShip = "SELECT * FROM ship_cost";
	$resultShip = mysql_query($queryShip) or die("Query failed : " . mysql_error());
	while ($lineShip = mysql_fetch_array($resultShip, MYSQL_ASSOC)) {
		echo '<tr><td>'.$lineShip["name"].'</td><td>$'.$lineShip["method_1"].'</td></tr>';
	}
	echo '</table>';
}
?></td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>