<?php
// BME WMS
// Page: Shipping Accounts Edit page
// Path/File: /admin/shipping_accounts_edit.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/st_and_co1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$shipping_account_id = $_POST["shipping_account_id"];
$status = $_POST["status"];
$account_number = $_POST["account_number"];
$meter_number = $_POST["meter_number"];
$username = $_POST["username"];
$password = $_POST["password"];
$server = $_POST["server"];
$origin_city = $_POST["origin_city"];
$origin_state = $_POST["origin_state"];
$origin_zip = $_POST["origin_zip"];
$shipper_city = $_POST["shipper_city"];
$shipper_state = $_POST["shipper_state"];
$shipper_zip = $_POST["shipper_zip"];
$account_submit = $_POST["account_submit"];

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Edit Shipping Accounts";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($account_submit != "") {
	$query = "UPDATE shipping_accounts SET";
	$query .= " status='$status'";
	$query .= ", account_number='$account_number'";
	if($meter_number != "") {
		$query .= ", meter_number='$meter_number'";
	}
	if($username != "") {
		$query .= ", username='$username'";
	}
	if($password != "") {
		$query .= ", password='$password'";
	}
	if($server != "") {
		$query .= ", server='$server'";
	}
	if($origin_city != "") {
		$query .= ", origin_city='$origin_city'";
	}
	if($origin_state != "") {
		$query .= ", origin_state='$origin_state'";
	}
	if($origin_zip != "") {
		$query .= ", origin_zip='$origin_zip'";
	}
	if($shipper_city != "") {
		$query .= ", shipper_city='$shipper_city'";
	}
	if($shipper_state != "") {
		$query .= ", shipper_state='$shipper_state'";
	}
	if($shipper_zip != "") {
		$query .= ", shipper_zip='$shipper_zip'";
	}
	$query .= " WHERE shipping_account_id='$shipping_account_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	
	header("Location: " . $base_url . "admin/shipping_accounts.php");
	exit;
}

if($wholesale_submit != "") {
	$query = "UPDATE ship_method_wholesale SET name='$name', active='$active' WHERE ship_method_id='$ship_method_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

if($default_method != "") {
	$query = "UPDATE ship_main SET default_wholesale_ship_method='$default_method' WHERE ship_main_id='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	
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

<tr><td align="left"><font size="2">Edit the settings for this Shipping Account.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT shipping_account_id, status, shipper, account_number, meter_number, username, password, server, origin_city, origin_state, origin_zip, shipper_city, shipper_state, shipper_zip FROM shipping_accounts WHERE shipping_account_id='$shipping_account_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$shipping_account_id =  $line["shipping_account_id"];
	$shipper = $line["shipper"];
	$status = $line["status"];
	$account_number = $line["account_number"];
	$meter_number =  $line["meter_number"];
	$username = $line["username"];
	$password = $line["password"];
	$server = $line["server"];
	$origin_city = $line["origin_city"];
	$origin_state = $line["origin_state"];
	$origin_zip = $line["origin_zip"];
	$shipper_city = $line["shipper_city"];
	$shipper_state = $line["shipper_state"];
	$shipper_zip = $line["shipper_zip"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="shipping" Method="POST" ACTION="./shipping_accounts_edit.php" class="wmsform">
	<input type="hidden" name="shipping_account_id" value="<?php echo $shipping_account_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Your Shipping Account Information</legend>
		<ol>
			<li>
				<label for="shipper">Shipper <em>*</em></label>
				<?php
				if($shipper == "FEDX") {
					echo "FedEx";
				} elseif($shipper == "USPS") {
					echo "USPS";
				} elseif($shipper == "UPS") {
					echo "UPS";
				} elseif($shipper == "DHL") {
					echo "DHL";
				}
				?>
			</li>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="1">
				<option value="1"<?php if($status == 1) { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if($status == 0) { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li>
				<label for="account_number">Account Number <em>*</em></label>
				<INPUT type="text" id="account_number" name="account_number" size="30" maxlength="16" value="<?php echo $account_number; ?>" tabindex="2" />
			</li>
			<?php	
			if($shipper == "FEDX" || $shipper == "DHL") {
			?>
			<li>
				<label for="meter_number">Meter Number <em>*</em></label>
				<INPUT type="text" id="meter_number" name="meter_number" size="30" maxlength="16" value="<?php echo $meter_number; ?>" tabindex="3" />
			</li>
			<?php
			}
			?>
			<?php	
			if($shipper == "FEDX" || $shipper == "DHL") {
			?>
			<li>
				<label for="username">Username <em>*</em></label>
				<INPUT type="text" id="username" name="username" size="10" maxlength="10" value="<?php echo $username; ?>" tabindex="4" />
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "DHL") {
			?>
			<li>
				<label for="password">Password <em>*</em></label>
				<INPUT type="text" id="password" name="password" size="10" maxlength="10" value="<?php echo $password; ?>" tabindex="5" />
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "USPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="server">Server <em>*</em></label>
				<INPUT type="text" id="server" name="server" size="30" maxlength="100" value="<?php echo $server; ?>" tabindex="6" />
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "UPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="origin_city">Origin City <em>*</em></label>
				<INPUT type="text" id="origin_city" name="origin_city" size="30" maxlength="100" value="<?php echo $origin_city; ?>" tabindex="7" />
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "UPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="origin_state">Origin State <em>*</em></label>
				<select id="origin_state" name="origin_state" tabindex="8">
				<option value="">Select a state</option>
				<?php
				state_build_all($origin_state);
				?>
				</select>
			</li>
			<?php
			}
			?>
			<li>
				<label for="origin_zip">Origin Zip <em>*</em></label>
				<INPUT type="text" id="origin_zip" name="origin_zip" size="10" maxlength="10" value="<?php echo $origin_zip; ?>" tabindex="9" />
			</li>
			<?php
			if($shipper == "FEDX" || $shipper == "UPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="shipper_city">Shipper City <em>*</em></label>
				<INPUT type="text" id="shipper_city" name="shipper_city" size="30" maxlength="100" value="<?php echo $shipper_city; ?>" tabindex="10" />
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "UPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="shipper_state">Shipper State <em>*</em></label>
				<select id="shipper_state" name="shipper_state" tabindex="11">
				<option value="">Select a state</option>
				<?php
				state_build_all($shipper_state);
				?>
				</select>
			</li>
			<?php
			}
			?>
			<?php
			if($shipper == "FEDX" || $shipper == "UPS" || $shipper == "DHL") {
			?>
			<li>
				<label for="shipper_zip">Shipper Zip <em>*</em></label>
				<INPUT type="text" id="shipper_zip" name="shipper_zip" size="10" maxlength="10" value="<?php echo $shipper_zip; ?>" tabindex="12" />
			</li>
			<?php
			}
			?>
			<li class="fm-button">
				<input type="submit" id="account_submit" name="account_submit" value="Save">
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