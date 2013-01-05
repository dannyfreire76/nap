<?php
// BME MyBWMS
// Page: WMS Homepage
// Path/File: /admin/index2.php
// Version: 1.8
// Build: 1806
// Date: 08-14-2007
header ( 'Content-type: text/html; charset=utf-8' );
include_once ("./includes/ltimer/ltimer.class.php");
$timer = new LTimer ();
include_once ('../includes/main1.php');
include '../includes/main2.php';
$user_id = $_COOKIE ["wms_user"];
$submit = $_POST ["submit"];
$website_title = $_POST ["website_title"];
$base_url = $_POST ["base_url"];
$base_secure_url = $_POST ["base_secure_url"];
$site_email = $_POST ["site_email"];
$company_name = $_POST ["company_name"];
$product_name = $_POST ["product_name"];
$wms_id = $_POST ['wms_id'];
if ($submit != "") {
	//Validate
	$error_txt = "";
	if ($website_title == "") { $error_txt .= "The Website Title field is blank. Please complete this field.<br>\n"; }
	if ($base_url == "") { $error_txt .= "The Base URL field is blank. Please complete this field.<br>\n"; }
	if ($base_secure_url == "") { $error_txt .= "The Base Secure URL field is blank. Please complete this field.<br>\n"; }
	if ($site_email == "") { $error_txt .= "The Site E-Mail Address field is blank. Please complete this field.<br>\n"; }
	if ($company_name == "") { $error_txt .= "The Company Name field is blank. Please complete this field.<br>\n"; }
	if ($product_name == "") { $error_txt .= "The Product Name field is blank. Please complete this field.<br>\n"; }
	//If no Errors, Update DB
	if ($error_txt == "") {
		$query = "UPDATE wms_main SET website_title='$website_title', base_url='$base_url', base_secure_url='$base_secure_url', site_email='$site_email', company_name='$company_name', product_name='$product_name' WHERE wms_id='$wms_id'";
		$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	}
}
include '../includes/main3.php';
include './includes/wms_nav1.php';
$manager = "homepage";
$page = "MyBWMS Homepage > Homepage";
wms_manager_nav2 ( $manager );
wms_page_nav2 ( $manager );
if (! $user_id) {
	header ( "Location: " . $base_url . "admin/index.php" );
	exit ();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title . ": " . $page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">
<?php include './includes/head_admin3.php'; ?>
<table border="0" width="97%">
	<tr>
		<td colspan="3" align="left">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" align="left"><font size="3"><?php
		echo $website_title;
		?> My Business and Website Management System</font></td>
	</tr>
	<tr>
		<td colspan="3" align="left"><font size="2">Hello <?php
		echo $_SESSION ['first_name'];
		?>
<br>
		<br></td>
	</tr>
<?php
//Error Messages
if ($error_txt) {
	echo "<tr><td colspan=\"3\" align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td colspan=\"3\">&nbsp;</td></tr>\n";
}
if ($_SESSION ['dashboard'] == "1") {
	$now_sml = date ( "Ymd000000" );
	$now_sml2 = date ( "Ymd235959" );
	$mn_sml = date ( "Ym01000000" );
	$mn_sml2 = date ( "Ymt235959" );
	$yr_sml = date ( "Y0101000000" );
	$yr_sml2 = date ( "Y1231235959" );
	$order_total = 0;
	$order_count = 0;
	$mn_order_total = 0;
	$mn_order_count = 0;
	$yr_order_total = 0;
	$yr_order_count = 0;
	$query = "SELECT total FROM receipts WHERE complete='1' AND ordered >= '$now_sml' AND ordered <= '$now_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$order_total = $order_total + $col_value;
			$order_count = $order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$query = "SELECT total FROM receipts WHERE complete='1' AND ordered >= '$mn_sml' AND ordered <= '$mn_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$mn_order_total = $mn_order_total + $col_value;
			$mn_order_count = $mn_order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$query = "SELECT total FROM receipts WHERE complete='1' AND ordered >= '$yr_sml' AND ordered <= '$yr_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$yr_order_total = $yr_order_total + $col_value;
			$yr_order_count = $yr_order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$order_total = sprintf ( "%01.2f", $order_total );
	$mn_order_total = sprintf ( "%01.2f", $mn_order_total );
	$yr_order_total = sprintf ( "%01.2f", $yr_order_total );
	$avg_order = 0;
	$mn_avg_order = 0;
	$yr_avg_order = 0;
	if ($order_count != 0) {
		$avg_order = $order_total / $order_count;
	}
	if ($mn_order_count != 0) {
		$mn_avg_order = $mn_order_total / $mn_order_count;
	}
	if ($yr_order_count != 0) {
		$yr_avg_order = $yr_order_total / $yr_order_count;
	}
	$avg_order = sprintf ( "%01.2f", $avg_order );
	$mn_avg_order = sprintf ( "%01.2f", $mn_avg_order );
	$yr_avg_order = sprintf ( "%01.2f", $yr_avg_order );
	?>
<tr>
		<td colspan="3" align="left">
		<table border="0">
			<tr>
				<td><font size="2"><b>Retail Daily Totals</b><br>
				Orders Today: <b><?php
	echo number_format($order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($avg_order,2);
	?></b></td>
				<td>&nbsp; &nbsp;</td>
				<td><font size="2"><b>Retail Monthly Totals</b><br>
				Orders This Month: <b><?php
	echo number_format($mn_order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($mn_order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($mn_avg_order,2);
	?></b></td>
				<td>&nbsp; &nbsp;</td>
				<td><font size="2"><b>Retail Yearly Totals</b><br>
				Orders This Year: <b><?php
	echo number_format($yr_order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($yr_order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($yr_avg_order,2);
	?></b></td>
			</tr>
<?php
	$order_total = 0;
	$order_count = 0;
	$mn_order_total = 0;
	$mn_order_count = 0;
	$yr_order_total = 0;
	$yr_order_count = 0;
	$query = "SELECT total FROM wholesale_receipts WHERE complete='1' AND ordered >= '$now_sml' AND ordered <= '$now_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$order_total = $order_total + $col_value;
			$order_count = $order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$query = "SELECT total FROM wholesale_receipts WHERE complete='1' AND ordered >= '$mn_sml' AND ordered <= '$mn_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$mn_order_total = $mn_order_total + $col_value;
			$mn_order_count = $mn_order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$query = "SELECT total FROM wholesale_receipts WHERE complete='1' AND ordered >= '$yr_sml' AND ordered <= '$yr_sml2'";
	$result = mysql_query ( $query ) or die ( "Query failed : " . mysql_error () );
	while ( $line = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
		foreach ( $line as $col_value ) {
			$yr_order_total = $yr_order_total + $col_value;
			$yr_order_count = $yr_order_count + 1;
		}
	}
	mysql_free_result ( $result );
	$order_total = sprintf ( "%01.2f", $order_total );
	$mn_order_total = sprintf ( "%01.2f", $mn_order_total );
	$yr_order_total = sprintf ( "%01.2f", $yr_order_total );
	$avg_order = 0;
	$mn_avg_order = 0;
	$yr_avg_order = 0;
	if ($order_count != 0) {
		$avg_order = $order_total / $order_count;
	}
	if ($mn_order_count != 0) {
		$mn_avg_order = $mn_order_total / $mn_order_count;
	}
	if ($yr_order_count != 0) {
		$yr_avg_order = $yr_order_total / $yr_order_count;
	}
	$avg_order = sprintf ( "%01.2f", $avg_order );
	$mn_avg_order = sprintf ( "%01.2f", $mn_avg_order );
	$yr_avg_order = sprintf ( "%01.2f", $yr_avg_order );
	?>
<tr>
				<td><font size="2"><b>Wholesale Daily Totals</b><br>
				Orders Today: <b><?php
	echo number_format($order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($avg_order,2);
	?></b></td>
				<td>&nbsp; &nbsp;</td>
				<td><font size="2"><b>Wholesale Monthly Totals</b><br>
				Orders This Month: <b><?php
	echo number_format($mn_order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($mn_order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($mn_avg_order,2);
	?></b></td>
				<td>&nbsp; &nbsp;</td>
				<td><font size="2"><b>Wholesale Yearly Totals</b><br>
				Orders This Year: <b><?php
	echo number_format($yr_order_count);
	?></b><br>
				Orders Total: <b>$<?php
	echo number_format($yr_order_total,2);
	?></b><br>
				Average Order: <b>$<?php
	echo number_format($yr_avg_order,2);
	?></b></td>
			</tr>
		</table>
		</td>
	</tr>
<?php
}
?>
<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td align="left"></td>
<?php
if ($_SESSION ['main_settings'] == "1") {
	?>
<td align="left">
		<FORM name="homepage" Method="POST" ACTION="./index2.php"
			class="wmsform"><input type="hidden" name="wms_id"
			value="<?php
	echo $wms_id;
	?>">
		<p>Please complete the form below. Required fields marked <em>*</em></p>
		<fieldset><legend>Please Enter Your Information</legend>
		<ol>
			<li><label for="website_title">Website Title <em>*</em></label> <INPUT
				type="text" id="website_title" name="website_title" size="30"
				maxlength="150" value="<?php
	echo $website_title;
	?>"
				tabindex="1" /></li>
			<li><label for="base_url">Base URL <em>*</em></label> <INPUT
				type="text" id="base_url" name="base_url" size="30" maxlength="150"
				value="<?php
	echo $base_url;
	?>" tabindex="3" /></li>
			<li><label for="base_secure_url">Base Secure URL <em>*</em></label> <INPUT
				type="text" id="base_secure_url" name="base_secure_url" size="30"
				maxlength="150" value="<?php
	echo $base_secure_url;
	?>"
				tabindex="4" /></li>
			<li><label for="site_email">Site E-Mail Address <em>*</em></label> <INPUT
				type="text" id="site_email" name="site_email" size="30"
				maxlength="100" value="<?php
	echo $site_email;
	?>" tabindex="5" /></li>
			<li><label for="company_name">Company Name <em>*</em></label> <INPUT
				type="text" id="company_name" name="company_name" size="30"
				maxlength="150" value="<?php
	echo $company_name;
	?>"
				tabindex="6" /></li>
			<li><label for="product_name">Product Name <em>*</em></label> <INPUT
				type="text" id="product_name" name="product_name" size="30"
				maxlength="150" value="<?php
	echo $product_name;
	?>"
				tabindex="7" /></li>
			<li>
			<li class="fm-button"><input type="submit" id="submit" name="submit"
				value="Save"></li>
		</ol>
		</fieldset>
		</form>
		</td>
<?php
} else {
	?>
<td align="left"><font size="2"><b>MyBWMS Version: <?php
	echo $version;
	?></b></font></td>
<?php
}
?>
<td width="20">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
</table>
<?php
include './includes/foot_admin1.php';
footer_admin ( $timer->getTTMS () );
mysql_close ( $dbh );
?>
</div>
</body>
</html>
