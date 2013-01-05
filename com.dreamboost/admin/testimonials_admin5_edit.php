<?php
// BME WMS
// Page: Testimonial Manager Manage Live Testimonials Edit page
// Path/File: /admin/testimonials_admin5_edit.php
// Version: 1.8
// Build: 1804
// Date: 01-31-2007

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

if($_GET['id'] != "") {
	$testimonial_id = $_GET['id'];
} else {
	$testimonial_id = $_POST["testimonial_id"];
}
$save = $_POST["save"];
$position = $_POST["position"];
$status = $_POST["status"];
$name = $_POST["name"];
$email = $_POST["email"];
$address1 = $_POST["address1"];
$address2 = $_POST["address2"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$fax = $_POST["fax"];
$testimonial = $_POST["testimonial"];
$product = $_POST["product"];

include './includes/wms_nav1.php';
$manager = "testimonials";
$page = "Testimonials Manager > Edit Live Testimonials";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='name'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$name_name = $line["name"];
	$name_submit = $line["submit"];
	$name_reqd_submit = $line["reqd_submit"];
	$name_submit_pos = $line["submit_pos"];
	$name_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='email'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$email_name = $line["name"];
	$email_submit = $line["submit"];
	$email_reqd_submit = $line["reqd_submit"];
	$email_submit_pos = $line["submit_pos"];
	$email_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='address1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$address1_name = $line["name"];
	$address1_submit = $line["submit"];
	$address1_reqd_submit = $line["reqd_submit"];
	$address1_submit_pos = $line["submit_pos"];
	$address1_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='address2'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$address2_name = $line["name"];
	$address2_submit = $line["submit"];
	$address2_reqd_submit = $line["reqd_submit"];
	$address2_submit_pos = $line["submit_pos"];
	$address2_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='city'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$city_name = $line["name"];
	$city_submit = $line["submit"];
	$city_reqd_submit = $line["reqd_submit"];
	$city_submit_pos = $line["submit_pos"];
	$city_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='state'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$state_name = $line["name"];
	$state_submit = $line["submit"];
	$state_reqd_submit = $line["reqd_submit"];
	$state_submit_pos = $line["submit_pos"];
	$state_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='zip'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$zip_name = $line["name"];
	$zip_submit = $line["submit"];
	$zip_reqd_submit = $line["reqd_submit"];
	$zip_submit_pos = $line["submit_pos"];
	$zip_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='country'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$country_name = $line["name"];
	$country_submit = $line["submit"];
	$country_reqd_submit = $line["reqd_submit"];
	$country_submit_pos = $line["submit_pos"];
	$country_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='phone'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$phone_name = $line["name"];
	$phone_submit = $line["submit"];
	$phone_reqd_submit = $line["reqd_submit"];
	$phone_submit_pos = $line["submit_pos"];
	$phone_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='fax'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$fax_name = $line["name"];
	$fax_submit = $line["submit"];
	$fax_reqd_submit = $line["reqd_submit"];
	$fax_submit_pos = $line["submit_pos"];
	$fax_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='testimonial'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$testimonial_name = $line["name"];
	$testimonial_submit = $line["submit"];
	$testimonial_reqd_submit = $line["reqd_submit"];
	$testimonial_submit_pos = $line["submit_pos"];
	$testimonial_displayed = $line["displayed"];
}
mysql_free_result($result);

$query = "SELECT name, submit, reqd_submit, submit_pos, displayed FROM testimonial_fields WHERE int_name='product'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_name = $line["name"];
	$product_submit = $line["submit"];
	$product_reqd_submit = $line["reqd_submit"];
	$product_submit_pos = $line["submit_pos"];
	$product_displayed = $line["displayed"];
}
mysql_free_result($result);

if($save != "") {
	//Check for Errors
	$error_txt = "";
	if($name_reqd_submit == "1" && $name == "") {
		$error_txt .= "Error, you removed the name. There needs to be an name.<br>";
	}
	if($email_reqd_submit == "1" && $email == "") {
		$error_txt .= "Error, you removed the email address. There needs to be an email address.<br>";
	}
	if($address1_reqd_submit == "1" && $address1 == "") {
		$error_txt .= "Error, you removed the address. There needs to be an address.<br>\n";
	}
	if($address2_reqd_submit == "1" && $address2 == "") {
		$error_txt .= "Error, you removed the address. There needs to be an address.<br>\n";
	}
	if($city_reqd_submit == "1" && $city == "") {
		$error_txt .= "Error, you removed the city. There needs to be a city.<br>\n";
	}
	if($state_reqd_submit == "1" && $state == "") {
		$error_txt .= "Error, you removed the state/province. There needs to be a state/province.<br>\n";
	}
	if($zip_reqd_submit == "1" && $zip == "") {
		$error_txt .= "Error, you removed the zip/postal code. There needs to be a zip/postal code.<br>\n";
	}
	if($country_reqd_submit == "1" && $country == "") {
		$error_txt .= "Error, you removed the country. There needs to be a country.<br>\n";
	}
	if($phone_reqd_submit == "1" && $phone == "") {
		$error_txt .= "Error, you removed the phone number. There needs to be a phone number.<br>\n";
	}
	if($fax_reqd_submit == "1" && $fax == "") {
		$error_txt .= "Error, you removed the fax number. There needs to be a fax number.<br>\n";
	}
	if($testimonial_reqd_submit == "1" && $testimonial == "") {
		$error_txt .= "Error, you removed the testimonial. There needs to be a testimonial.<br>";
	}
	if($product_reqd_submit == "1" && $product == "") {
		$error_txt .= "Error, you removed the product. There needs to be a product.<br>";
	}
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE testimonials SET position='$position', status='$status', name='$name', email='$email', address1='$address1', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', fax='$fax', testimonial='$testimonial', product='$product' WHERE testimonial_id='$testimonial_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		if($status == 1) {
			//Goto Manage Live
			header("Location: " . $base_url . "admin/testimonials_admin5.php");
			exit;
		} elseif($status == 2) {
			//Goto Index
			header("Location: " . $base_url . "admin/testimonials_admin3.php");
			exit;
		} elseif($status == 0) {
			//Goto Index
			header("Location: " . $base_url . "admin/testimonials_admin2.php");
			exit;
		}
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

<tr><td align="left"><font size="2">Welcome to the Testimonials Manager, where you manage the testimonials section of your website. Please edit the testimonal below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT testimonial_id, status, position, name, email, address1, address2, city, state, zip, country, phone, fax, testimonial, product FROM testimonials WHERE testimonial_id='$testimonial_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$position = $line["position"];
	$status = $line["status"];
	$testimonial = $line["testimonial"];
	$name = $line["name"];
	$email = $line["email"];
	$address1 = $line["address1"];
	$address2 = $line["address2"];
	$city = $line["city"];
	$state = $line["state"];
	$zip = $line["zip"];
	$country = $line["country"];
	$phone = $line["phone"];
	$fax = $line["fax"];
	$product = $line["product"];
	$testimonial_id = $line["testimonial_id"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="testimonial-edit" Method="POST" ACTION="./testimonials_admin5_edit.php" class="wmsform">
	<input type="hidden" name="testimonial_id" value="<?php echo $testimonial_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>User's Testimonial Information</legend>
		<ol>
			<li>
				<label for="position">Position <em>*</em></label>
				<select id="position" name="position" tabindex="1">
				<?php
				for($i=1; $i<=50; $i++) {
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
				<option value="0"<?php if($status == "0") { echo " SELECTED"; } ?>>To review</option>
				<option value="1"<?php if($status == "1") { echo " SELECTED"; } ?>>Approved</option>
				<option value="2"<?php if($status == "2") { echo " SELECTED"; } ?>>Rejected</option>
				</select>
			</li>
			<?php
			if($testimonial_submit == "1") {
				echo "<li";
				if($testimonial_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"testimonial\">$testimonial_name";
				if($testimonial_reqd_submit == "1") { echo " <em>*</em>"; }
				if($testimonial_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n"; 
				echo "<TEXTAREA id=\"testimonial\" name=\"testimonial\" cols=\"35\" rows=\"7\" tabindex=\"3\">$testimonial</TEXTAREA>\n";
				echo "</li>\n";
			}
			if($name_submit == "1") {
				echo "<li";
				if($name_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"name\">$name_name";
				if($name_reqd_submit == "1") { echo " <em>*</em>"; }
				if($name_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"name\" name=\"name\" size=\"30\" maxlength=\"255\" value=\"$name\" tabindex=\"4\" />\n";
				echo "</li>\n";
			}
			if($email_submit == "1") {
				echo "<li";
				if($email_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"email\">$email_name";
				if($email_reqd_submit == "1") { echo " <em>*</em>"; }
				if($email_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"email\" name=\"email\" size=\"30\" maxlength=\"255\" value=\"$email\" tabindex=\"5\" />\n";
				echo "</li>\n";
			}
			if($address1_submit == "1") {
				echo "<li";
				if($address1_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"address1\">$address1_name";
				if($address1_reqd_submit == "1") { echo " <em>*</em>"; }
				if($address1_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"address1\" name=\"address1\" size=\"30\" maxlength=\"100\" value=\"$address1\" tabindex=\"6\" />\n";
				echo "</li>\n";
			}
			if($address2_submit == "1") {
				echo "<li";
				if($address2_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"address2\">$address2_name";
				if($address2_reqd_submit == "1") { echo " <em>*</em>"; }
				if($address2_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"address2\" name=\"address2\" size=\"30\" maxlength=\"100\" value=\"$address2\" tabindex=\"7\" />\n";
				echo "</li>\n";
			}
			if($city_submit == "1") {
				echo "<li";
				if($city_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"city\">$city_name";
				if($city_reqd_submit == "1") { echo " <em>*</em>"; }
				if($city_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"city\" name=\"city\" size=\"30\" maxlength=\"100\" value=\"$city\" tabindex=\"8\" />\n";
				echo "</li>\n";
			}
			if($state_submit == "1") {
				echo "<li";
				if($state_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"state\">$state_name";
				if($state_reqd_submit == "1") { echo " <em>*</em>"; }
				if($state_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<select id=\"state\" name=\"state\" tabindex=\"9\">\n";
				state_build_all($state);
				echo "</select>\n";
				echo "</li>\n";
			}
			if($zip_submit == "1") {
				echo "<li";
				if($zip_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"zip\">$zip_name";
				if($zip_reqd_submit == "1") { echo " <em>*</em>"; }
				if($zip_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"zip\" name=\"zip\" size=\"10\" maxlength=\"10\" value=\"$zip\" tabindex=\"10\" />\n";
				echo "</li>\n";
			}
			if($country_submit == "1") {
				echo "<li";
				if($country_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"country\">$country_name";
				if($country_reqd_submit == "1") { echo " <em>*</em>"; }
				if($country_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<select id=\"country\" name=\"country\" tabindex=\"11\">\n";
				country_build_all($country);
				echo "</select>\n";
				echo "</li>\n";
			}
			if($phone_submit == "1") {
				echo "<li";
				if($phone_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"phone\">$phone_name";
				if($phone_reqd_submit == "1") { echo " <em>*</em>"; }
				if($phone_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"phone\" name=\"phone\" size=\"30\" maxlength=\"30\" value=\"$phone\" tabindex=\"12\" />\n";
				echo "</li>\n";
			}
			if($fax_submit == "1") {
				echo "<li";
				if($fax_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"fax\">$fax_name";
				if($fax_reqd_submit == "1") { echo " <em>*</em>"; }
				if($fax_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<INPUT type=\"text\" id=\"fax\" name=\"fax\" size=\"30\" maxlength=\"30\" value=\"$fax\" tabindex=\"13\" />\n";
				echo "</li>\n";
			}
			if($product_submit == "1") {
				echo "<li";
				if($product_reqd_submit == "0") { echo " class=\"fm-optional\""; }
				echo ">\n";
				echo "<label for=\"product\">$product_name";
				if($product_reqd_submit == "1") { echo " <em>*</em>"; }
				if($product_displayed == "0") { echo "<br>Note: Not displayed"; }
				echo "</label>\n";
				echo "<select id=\"product\" name=\"product\" tabindex=\"14\">\n";
				echo "<option value=\"1\"";
				if($product == "1") { echo " SELECTED"; }
				echo ">SalviaZone Green</option>\n";
				echo "<option value=\"2\"";
				if($product == "2") { echo " SELECTED"; }
				echo ">SalviaZone Yellow</option>\n";
				echo "<option value=\"3\"";
				if($product == "3") { echo " SELECTED"; }
				echo ">SalviaZone Red</option>\n";
				echo "<option value=\"4\"";
				if($product == "4") { echo " SELECTED"; }
				echo ">SalviaZone Purple</option>\n";
				echo "<option value=\"6\"";
				if($product == "6") { echo " SELECTED"; }
				echo ">SalviaZone Blue/Infinity</option>\n";
				echo "<option value=\"5\"";
				if($product == "5") { echo " SELECTED"; }
				echo ">All</option>\n";
				echo "</select>\n";
				echo "</li>\n";
			}
			?>
			<li class="fm-button">
				<input type="submit" id="save" name="save" value="Save Changes">
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