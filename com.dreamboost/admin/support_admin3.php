<?php
// BME WMS
// Page: Support Manager Upgrades page
// Path/File: /admin/support_admin3.php
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

$submit = $_POST["submit"];
$how_often = $_POST["how_often"];
$day_of_week = $_POST["day_of_week"];
$hour = $_POST["hour"];
$minute = $_POST["minute"];

include './includes/wms_nav1.php';
$manager = "support";
$page = "Support Manager > Upgrades";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	$query = "UPDATE support_main SET how_often='$how_often', day_of_week='$day_of_week', hour='$hour' WHERE support_id='1'";
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

<tr><td align="left"><font size="2">Welcome to the Support Manager, where you can find answers on how to use the entire MyBWMS system or get help from our staff. Below you will find information about upgrading your MyBWMS.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
echo "<tr><td align=\"left\"><font size=\"2\"><b>MyBWMS System and Managers Upgrades</b></font></td></tr>\n";

echo "<tr><td align=\"left\"><font size=\"2\">If there are any upgrades available they will be listed below. Click on them to download and install.</font></td></tr>\n";

//GET file from main server and copy to users server and then install there

echo "<tr><td>&nbsp;</td></tr>";

echo "<tr><td align=\"left\"><font size=\"2\"><b>Automatic Upgrades</b></font></td></tr>\n";

$query = "SELECT how_often, day_of_week, hour, minute FROM support_main WHERE support_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$how_often = $line["how_often"];
	$day_of_week = $line["day_of_week"];
	$hour = $line["hour"];
	$minute = $line["minute"];
}
mysql_free_result($result);
?>

<tr><td align="left"><font size="2">Use the switch below to control how often your MyBWMS System Automatically checks for upgrades. If upgrades are found they will be downloaded and installed on the day and time you specify.</font></td></tr>

<tr><td align="left">
	<FORM name="support" Method="POST" ACTION="./support_admin3.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Select Your Automatic Upgrades Settings</legend>
		<ol>
			<li>
				<label for="how_often">How Often <em>*</em></label>
				<select id="how_often" name="how_often" tabindex="1">
				<?php
				echo "<option value=\"daily\"";
				if($how_often == "daily") { echo " SELECTED"; }
				echo ">Daily</option>\n";
				echo "<option value=\"weekly\"";
				if($how_often == "weekly") { echo " SELECTED"; }
				echo ">Weekly</option>\n";
				echo "<option value=\"biweekly\"";
				if($how_often == "biweekly") { echo " SELECTED"; }
				echo ">Bi-Weekly</option>\n";
				echo "<option value=\"monthly\"";
				if($how_often == "monthly") { echo " SELECTED"; }
				echo ">Monthly</option>\n";
				?>
				</select>
			</li>
			<li>
				<label for="day_of_week">Day of the Week <em>*</em></label>
				<select id="day_of_week" name="day_of_week" tabindex="2">
				<?php
				echo "<option value=\"sat\"";
				if($day_of_week == "sat") { echo " SELECTED"; }
				echo ">Saturday</option>\n";
				echo "<option value=\"sun\"";
				if($day_of_week == "sun") { echo " SELECTED"; }
				echo ">Sunday</option>\n";
				echo "<option value=\"mon\"";
				if($day_of_week == "mon") { echo " SELECTED"; }
				echo ">Monday</option>\n";
				echo "<option value=\"tue\"";
				if($day_of_week == "tue") { echo " SELECTED"; }
				echo ">Tuesday</option>\n";
				echo "<option value=\"wed\"";
				if($day_of_week == "wed") { echo " SELECTED"; }
				echo ">Wednesday</option>\n";
				echo "<option value=\"thr\"";
				if($day_of_week == "thr") { echo " SELECTED"; }
				echo ">Thursday</option>\n";
				echo "<option value=\"fri\"";
				if($day_of_week == "fri") { echo " SELECTED"; }
				echo ">Friday</option>\n";
				?>
				</select>
			</li>
			<li>
				<label for="hour">Time of Day <em>*</em></label>
				<select id="hour" name="hour" tabindex="3">
				<?php
				for($i = 0; $i <= 23; $i++){
					if($i < 10) { $j = 0 . $i; } else { $j = $i; }
					echo "<option value=\"$j\"";
					if($hour == "$i") { echo " SELECTED"; }
					echo ">$j:00</option>\n";
				}
				?>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save Info">
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