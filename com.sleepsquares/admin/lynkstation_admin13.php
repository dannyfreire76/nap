<?php
// BME WMS
// Page: LynkStation Manager Manage Filters page
// Path/File: /admin/lynkstation_admin13.php
// Version: 1.8
// Build: 1801
// Date: 01-29-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$article_category_id = $_POST["article_category_id"];
$position = $_POST["position"];
$status = $_POST["status"];
$category_name = $_POST["category_name"];
$site_path = $_POST["site_path"];
$submit = $_POST["submit"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Manage Filters";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Check for Errors
	$error_txt = "";
	if($filter == "") { $error_txt .= "Error, the Filter is blank. There needs to be a filter.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$filter = strtolower($filter);
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO lynkstation_filters SET created='$now', status='$status', filter='$filter'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($status, $filter, $create);
	}
} elseif($ls_filter_id) {
	//Check for Errors
	$error_txt = "";
	if($filter == "") { $error_txt .= "Error, the Filter is blank. There needs to be a filter.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$filter = strtolower($filter);
		$query = "UPDATE lynkstation_filters SET status='$status', filter='$filter' WHERE ls_filter_id='$ls_filter_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($ls_filter_id, $status, $filter, $save);
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

<tr><td align="left"><font size="2">These are the LynkStation Filters - you can create, edit, or Deactivate them below. The filters will block submitted links to your website when any part of the Title, Description, or URL matches a filter.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="lynkstation-filter-create" Method="POST" ACTION="./lynkstation_admin13.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create a New LynkStation Filter</legend>
		<ol>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="1">
				<option value="1"<?php if($status == "1") { echo " SELECTED"; } ?>>Active</option>
				<option value="2"<?php if($status == "2") { echo " SELECTED"; } ?>>Review</option>
				<option value="0"<?php if($status == "0") { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li>
				<label for="filter">Filter <em>*</em></label>
				<INPUT type="text" id="filter" name="filter" size="30" maxlength="255" value="<?php echo $filter; ?>" tabindex="2" />
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create New LynkStation Filter">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Status</th><th scope="col">Filter</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT ls_filter_id, status, filter FROM lynkstation_filters ORDER BY status, filter";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"lynkstation-filter-manage\" Method=\"POST\" ACTION=\"./lynkstation_admin13.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo "<SELECT name=\"status\">";
	echo "<option value=\"1\"";
	if($line["status"] == "1") { echo " SELECTED"; }
	echo ">Active</option>";
	echo "<option value=\"2\"";
	if($line["status"] == "2") { echo " SELECTED"; }
	echo ">Review</option>";
	echo "<option value=\"0\"";
	if($line["status"] == "0") { echo " SELECTED"; }
	echo ">Inactive</option>";
	echo "</select></td>";

	echo "<td><input type=\"text\" name=\"filter\" size=\"30\" maxlength=\"255\" value=\"";
	echo $line["filter"];
	echo "\"></td>";
	echo "<input type=\"hidden\" name=\"ls_filter_id\" value=\"";
	echo $line["ls_filter_id"];
	echo "\"><td align=\"center\"><input type=\"image\" src=\"/images/wms/save.gif\" id=\"save\" name=\"save\" width=\"16\" height=\"16\" alt=\"Save\"></td></tr></form>\n";
}
mysql_free_result($result);
?>
</table>
</font></td></tr>

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