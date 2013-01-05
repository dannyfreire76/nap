<?php
// BME WMS
// Page: Content Manager Manage Content Categories page
// Path/File: /admin/content_admin5.php
// Version: 1.8
// Build: 1804
// Date: 01-26-2007

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
$manager = "content";
$page = "Content Manager > Manage Content Categories";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Check for Errors
	$error_txt = "";
	if($category_name == "") { $error_txt .= "Error, the Category Name is blank. There needs to be a category name.<br>"; }
	if($site_path == "") { $error_txt .= "Error, the Site Path is blank. There needs to be a site path.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO article_categories SET created='$now', position='$position', status='$status', category_name='$category_name', site_path='$site_path'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($position, $status, $category_name, $site_path, $create);
	}
} elseif($article_category_id) {
	//Check for Errors
	$error_txt = "";
	if($category_name == "") { $error_txt .= "Error, the Category Name is blank. There needs to be a category name.<br>"; }
	if($site_path == "") { $error_txt .= "Error, the Site Path is blank. There needs to be a site path.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE article_categories SET position='$position', status='$status', category_name='$category_name', site_path='$site_path' WHERE article_category_id='$article_category_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($article_category_id, $position, $status, $category_name, $site_path, $save);
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

<tr><td align="left"><font size="2">These are the Content categories - you can create, edit, or Deactivate them below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="content-category-create" Method="POST" ACTION="./content_admin5.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create a New Content Category</legend>
		<ol>
			<li>
				<label for="position">Position <em>*</em></label>
				<select id="position" name="position" tabindex="1">
				<?php
				for($i=1;$i<81;$i++){
					echo "<option value=\"$i\"";
					if($position == $i) { echo " SELECTED"; }
					echo ">$i</option>\n";
				}
				?>
				</select>
			</li>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="2">
				<option value="1"<?php if($status == "1") { echo " SELECTED"; } ?>>Active</option>
				<option value="2"<?php if($status == "2") { echo " SELECTED"; } ?>>Review</option>
				<option value="0"<?php if($status == "0") { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li>
				<label for="category_name">Category Name <em>*</em></label>
				<INPUT type="text" id="category_name" name="category_name" size="30" maxlength="255" value="<?php echo $category_name; ?>" tabindex="3" />
			</li>
			<li>
				<label for="site_path">Site Path <em>*</em></label>
				<INPUT type="text" id="site_path" name="site_path" size="30" maxlength="255" value="<?php echo $site_path; ?>" tabindex="4" />
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create New Content Category">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr><th scope="col">Position</th><th scope="col">Status</th><th scope="col">Category Name</th><th scope="col">Site Path</th><th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT article_category_id, position, status, category_name, site_path FROM article_categories ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"content-category\" Method=\"POST\" ACTION=\"./content_admin5.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo "<SELECT name=\"position\">\n";
	for($i=1;$i<31;$i++){
		echo "<option value=\"$i\"";
		if($line["position"] == "$i") { echo " SELECTED"; }
		echo ">$i</option>\n";
	}
	echo "</select></td>";
	echo "<td><SELECT name=\"status\">";
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

	echo "<td><input type=\"text\" name=\"category_name\" size=\"30\" maxlength=\"255\" value=\"";
	echo $line["category_name"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"site_path\" size=\"30\" maxlength=\"255\" value=\"";
	echo $line["site_path"];
	echo "\"></td>";
	echo "<input type=\"hidden\" name=\"article_category_id\" value=\"";
	echo $line["article_category_id"];
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