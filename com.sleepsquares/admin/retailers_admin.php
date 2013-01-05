<?php
// BME WMS
// Page: Retailers Homepage
// Path/File: /admin/retailers_admin.php
// Version: 1.8
// Build: 1805
// Date: 05-13-2007

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

$retailer_main_id = $_POST["retailer_main_id"];
$product_line = $_POST["product_line"];
$product = $_POST["product"];
$page_display = $_POST["page_display"];
$retailer_email = $_POST["retailer_email"];
$submit2 = $_POST['submit2'];


include './includes/wms_nav1.php';
$manager = "retailers";
$page = "Retailers Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ($submit2 != "") {
	$query = "UPDATE retailer_main SET product_line='$product_line', product='$product', page_display='$page_display', retailer_email='$retailer_email' WHERE retailer_main_id='$retailer_main_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

$query = "SELECT retailer_main_id, product_line, product, page_display, retailer_email FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$retailer_main_id = $line["retailer_main_id"];
	$product_line = $line["product_line"];
	$product = $line["product"];
	$page_display = $line["page_display"];
	$retailer_email = $line["retailer_email"];
}
mysql_free_result($result);

$query = "SELECT user_id, username FROM wms_users";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	list($user_id1, $user_id2, $user_id3, $user_id4, $user_id5, $user_id6, $user_id7, $user_id8, $user_id9, $user_id10) = $line["user_id"];
	list($username1, $username2, $username3, $username4, $username5, $username6, $username7, $username8, $username9, $username10) = $line["username"];
}
mysql_free_result($result);
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

<tr><td align="left"><font size="2">Welcome to the Retailers Manager, where you manage the retailers database and the retailers section of your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="retailers" Method="POST" ACTION="./retailers_admin2.php" class="wmsform">
	<input type="hidden" name="retailers_id" value="<?php echo $retailers_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Create New Retailer or Find Retailer</legend>
		<ol>
			<li class="fm-optional">
				<label for="store_name">Store Name</label>
				<INPUT type="text" id="store_name" name="store_name" size="30" maxlength="255" value="<?php echo $store_name; ?>" tabindex="1" />
			</li>
			<li class="fm-optional">
				<label for="contact_name">Contact Name</label>
				<INPUT type="text" id="contact_name" name="contact_name" size="30" maxlength="150" value="<?php echo $contact_name; ?>" tabindex="2" />
			</li>
			<li class="fm-optional">
				<label for="city">City</label>
				<INPUT type="text" id="city" name="city" size="30" maxlength="100" value="<?php echo $city; ?>" tabindex="3" />
			</li>
			<li class="fm-optional">
				<label for="state">State/Province</label>
				<select id="state" name="state" tabindex="4">
				<?php
				state_build_all($state);
				?>
				</select>
			</li>
			<li class="fm-optional">
				<label for="zip">Zip/Postal Code</label>
				<INPUT type="text" id="zip" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>" tabindex="5" />
			</li>
			<li class="fm-optional">
				<label for="country">Country</label>
				<select id="country" name="country" tabindex="6">
				<?php
				country_build_all($country);
				?>
				</select>
			</li>
			<li class="fm-optional">
				<label for="phone">Phone</label>
				<INPUT type="text" id="phone" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>" tabindex="7" />
			</li>
			<li class="fm-optional">
                <label for="where_store_found">Where did you find this Retailer?</label>
                <input type="text" value="<?php echo $where_store_found; ?>" maxlength="150" size="30" name="where_store_found" />
            </li>
			<li class="fm-optional">
                <label for="funds_owed">Funds Owed</label>
                <input type="checkbox" name="funds_owed" value="1" />
            </li>
			<li class="fm-optional">
                <label for="retailer_status">Retailer Status</label>
                <select name="retailer_status">
                    <option value="">Please Select</option>
                    <?php
                    $queryRS = "SELECT * FROM retailer_status ORDER BY rs_id";
                    $resultRS = mysql_query($queryRS) or die("Query failed : " . mysql_error());
                    while ($lineRS = mysql_fetch_array($resultRS, MYSQL_ASSOC)) {
                        echo '<option value="'.$lineRS["rs_id"].'"';
                        //if ( $lineRS["rs_id"] == $retailer_status ) { echo " SELECTED"; } 
                        echo '>'.$lineRS["rs_desc"].'</option>';

                    }
                    ?>
                </select>                
            </li>
			<li class="fm-optional">
                <label for="retailer_type[]">Retailer Type</label>
                <select name="retailer_type[]" multiple="true" size="4">
                    <?php
                    $queryRS = "SELECT retailer_type_id, name FROM retailer_type ORDER BY name";
                    $resultRS = mysql_query($queryRS) or die("Query failed : " . mysql_error());
                    while ($lineRS = mysql_fetch_array($resultRS, MYSQL_ASSOC)) {
                        echo '<option value="'.$lineRS["retailer_type_id"].'">'.$lineRS["name"].'</option>';

                    }
                    ?>
                </select>                
            </li>
            <li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Find Retailer">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<FORM name="retailers2" Method="POST" ACTION="./retailers_admin.php" class="wmsform">
	<input type="hidden" name="retailer_main_id" value="<? echo $retailer_main_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Main Retailers Manager Settings</legend>
		<ol>
			<li>
				<label for="product_line">Product Line <em>*</em></label>
				<INPUT type="text" id="product_line" name="product_line" size="30" maxlength="150" value="<?php echo $product_line; ?>" tabindex="1" />
			</li>
			<li>
				<label for="product">Product <em>*</em></label>
				<INPUT type="text" id="product" name="product" size="30" maxlength="150" value="<?php echo $product; ?>" tabindex="2" />
			</li>
			<li>
				<label for="page_display">Find a Retailer Page Display <em>*</em></label>
				<select id="page_display" name="page_display" tabindex="3">
				<option value="1"<?php if($page_display == 1) { echo " SELECTED"; } ?>>Alphabetically</option>
				<option value="2"<?php if($page_display == 2) { echo " SELECTED"; } ?>>By State and Country</option>
				</select>
			</li>
			<li>
				<label for="retailer_email">Retailer E-Mail <em>*</em></label>
				<INPUT type="text" id="retailer_email" name="retailer_email" size="30" maxlength="100" value="<?php echo $retailer_email; ?>" tabindex="4" />
			</li>
			<li class="fm-button">
				<input type="submit" id="submit2" name="submit2" value="Save">
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