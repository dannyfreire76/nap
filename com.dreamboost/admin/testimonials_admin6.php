<?php
// BME WMS
// Page: Testimonial Manager Manage Testimonial Fields page
// Path/File: /admin/testimonials_admin6.php
// Version: 1.8
// Build: 1804
// Date: 01-31-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$name = $_POST["name"];
$submit = $_POST["submit"];
$reqd_submit = $_POST["reqd_submit"];
$submit_pos = $_POST["submit_pos"];
$displayed = $_POST["displayed"];
$display_pos = $_POST["display_pos"];
$fields_id = $_POST["fields_id"];

include './includes/wms_nav1.php';
$manager = "testimonials";
$page = "Testimonials Manager > Manage Testimonial Fields";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($fields_id) {
	//Validate
	$error_txt = "";
	if($name == "") { $error_txt .= "You did not enter a Name for the field. Please enter a Name.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE testimonial_fields SET name='$name', submit='$submit', reqd_submit='$reqd_submit', submit_pos='$submit_pos', displayed='$displayed', display_pos='$display_pos' WHERE fields_id='$fields_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
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

<tr><td align="left"><font size="2">These are all the fields available in the Testimonial Manager and this screen allows you to control their behavior. You can control which fields users are asked to submit, which are required, which are displayed in the results, and in which order they are displayed in on the submission form and in the results. Please use the form below to make all these choices.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Name</th><th scope="col">Internal Name</th><th scope="col">Submit</th><th scope="col">Required Submit</th><th scope="col">Submit Position</th><th scope="col">Displayed</th><th scope="col">Display Position</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT fields_id, name, int_name, submit, reqd_submit, submit_pos, displayed, display_pos FROM testimonial_fields ORDER BY submit_pos";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"testimonials-manage\" Method=\"POST\" ACTION=\"./testimonials_admin6.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo ">";
	echo "<td NOWRAP><input type=\"text\" name=\"name\" value=\"";
	echo $line["name"];
	echo "\"></td><td>";
	echo $line["int_name"];
	echo "</td><td><select name=\"submit\">";
	echo "<option value=\"1\"";
	if($line["submit"] == "1") { echo " SELECTED"; }
	echo ">Yes</option>";
	echo "<option value=\"0\"";
	if($line["submit"] == "0") { echo " SELECTED"; }
	echo ">No</option>";
	echo "</select></td><td><select name=\"reqd_submit\">";
	echo "<option value=\"1\"";
	if($line["reqd_submit"] == "1") { echo " SELECTED"; }
	echo ">Yes</option>";
	echo "<option value=\"0\"";
	if($line["reqd_submit"] == "0") { echo " SELECTED"; }
	echo ">No</option>";
	echo "</select></td><td><select name=\"submit_pos\">";
	for($i = 1; $i <= 12; $i++) {
		echo "<option value=\"$i\"";
		if($line["submit_pos"] == "$i") { echo " SELECTED"; }
		echo ">$i</option>";
	}
	echo "</select></td><td><select name=\"displayed\">";
	echo "<option value=\"1\"";
	if($line["displayed"] == "1") { echo " SELECTED"; }
	echo ">Yes</option>";
	echo "<option value=\"0\"";
	if($line["displayed"] == "0") { echo " SELECTED"; }
	echo ">No</option>";
	echo "</select></td><td><select name=\"display_pos\">";
	for($i = 1; $i <= 12; $i++) {
		echo "<option value=\"$i\"";
		if($line["display_pos"] == "$i") { echo " SELECTED"; }
		echo ">$i</option>";
	}
	echo "</select></td><input type=\"hidden\" name=\"fields_id\" value=\"";
	echo $line["fields_id"];
	echo "\"><td align=\"center\"><input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr>\n";
	echo "</form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

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