<?php
// BME WMS
// Page: Shipping Manager Manage Shipping Containers page
// Path/File: /admin/shipping_admin10.php
// Version: 1.8
// Build: 1801
// Date: 02-19-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$sc_id = $_POST["sc_id"];
$name = $_POST["name"];
$width = $_POST["width"];
$length = $_POST["length"];
$height = $_POST["height"];
$weight = $_POST["weight"];
$interval = $_POST["interval"];
$sequence = $_POST["sequence"];

$submit = $_POST["submit"];
$create = $_POST["create"];

include './includes/wms_nav1.php';
$manager = "shipping";
$page = "Shipping Manager > Shipping Containers";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Check for Errors
	$error_txt = "";
	if($name == "") { $error_txt .= "Error, the Name is blank. There needs to be a name.<br>"; }
	if($width == "") { $error_txt .= "Error, the Width is blank. There needs to be a width.<br>"; }
	if($length == "") { $error_txt .= "Error, the Length is blank. There needs to be a length.<br>"; }
	if($height == "") { $error_txt .= "Error, the Height is blank. There needs to be a height.<br>"; }
	if($weight == "") { $error_txt .= "Error, the Weight is blank. There needs to be a weight.<br>"; }
	if($interval == "") { $error_txt .= "Error, the Interval is blank. There needs to be a interval.<br>"; }
	if($sequence == "") { $error_txt .= "Error, the Sequence is blank. There needs to be a sequence.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO ship_containers SET sc_desc='$name', sc_width='$width', sc_length='$length', sc_weight='$weight', sc_height='$height', sc_int='$interval', sc_seq='$sequence'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($status, $name, $cost, $qty_max, $create);
	}
} elseif($sc_id) {
	//Check for Errors
	$error_txt = "";
	if($name == "") { $error_txt .= "Error, the Name is blank. There needs to be a name.<br>"; }
	if($width == "") { $error_txt .= "Error, the Width is blank. There needs to be a width.<br>"; }
	if($length == "") { $error_txt .= "Error, the Length is blank. There needs to be a length.<br>"; }
	if($height == "") { $error_txt .= "Error, the Height is blank. There needs to be a height.<br>"; }
	if($weight == "") { $error_txt .= "Error, the Weight is blank. There needs to be a weight.<br>"; }
	if($interval == "") { $error_txt .= "Error, the Interval is blank. There needs to be a interval.<br>"; }
	if($sequence == "") { $error_txt .= "Error, the Sequence is blank. There needs to be a sequence.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE ship_containers SET sc_desc='$name', sc_width='$width', sc_length='$length', sc_weight='$weight', sc_height='$height', sc_int='$interval', sc_seq='$sequence' WHERE sc_id='$sc_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		unset($ship_con_id, $status, $name, $cost, $qty_max, $save);
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

<tr><td align="left"><font size="2">These are the Shipping Containers - you can create, edit, or Deactivate them below. Shipping containers are the types of packages in which you ship your products.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="content-category-create" Method="POST" ACTION="./shipping_admin10.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create a New Shipping Container</legend>
		<ol>
			<li>
				<label for="name">Container Name <em>*</em></label>
				<INPUT type="text" id="name" name="name" size="30" maxlength="150" value="<?php echo $name; ?>" tabindex="2" />
			</li>
			<li>
				<label for="cost">Width <em>*</em></label>
				<INPUT type="text" id="width" name="width" size="3" maxlength="3" value="<?php echo $width; ?>" tabindex="3" />
			</li>
			<li>
				<label for="qty_max">Length <em>*</em></label>
				<INPUT type="text" id="length" name="length" size="3" maxlength="3" value="<?php echo $length; ?>" tabindex="4" />
			</li>
			<li>
				<label for="qty_max">Height <em>*</em></label>
				<INPUT type="text" id="height" name="height" size="3" maxlength="3" value="<?php echo $height; ?>" tabindex="4" />
			</li>
			<li>
				<label for="qty_max">Weight <em>*</em></label>
				<INPUT type="text" id="weight" name="weight" size="3" maxlength="3" value="<?php echo $weight; ?>" tabindex="4" />
			</li>
			<li>
				<label for="qty_max">Interval <em>*</em></label>
				<INPUT type="text" id="interval" name="interval" size="3" maxlength="3" value="<?php echo $interval; ?>" tabindex="4" />
			</li>
			<li>
				<label for="qty_max">Sequence <em>*</em></label>
				<INPUT type="text" id="sequence" name="sequence" size="3" maxlength="3" value="<?php echo $sequence; ?>" tabindex="4" />
			</li>

            <li class="fm-button">
				<input type="submit" id="create" name="create" value="Create New Shipping Container">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr>
<th scope="col">Name</th>
<th scope="col">Width</th>
<th scope="col">Length</th>
<th scope="col">Height</th>
<th scope="col">Weight</th>
<th scope="col">Interval</th>
<th scope="col">Sequence</th>
<th scope="col">&nbsp;</th></tr>

<?php
$line_counter = 0;
$query = "SELECT * FROM ship_containers ORDER BY sc_seq";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"shipping-container\" Method=\"POST\" ACTION=\"./shipping_admin10.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo ">";

	echo "<td><input type=\"text\" name=\"name\" size=\"30\" maxlength=\"150\" value=\"";
	echo $line["sc_desc"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"width\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_width"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"length\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_length"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"height\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_height"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"weight\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_weight"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"interval\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_int"];
	echo "\"></td>";
	echo "<td><input type=\"text\" name=\"sequence\" size=\"3\" maxlength=\"3\" value=\"";
	echo $line["sc_seq"];
	echo "\"></td>";
    echo "<input type=\"hidden\" name=\"sc_id\" value=\"";
	echo $line["sc_id"];
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