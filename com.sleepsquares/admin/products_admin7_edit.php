<?php
// BME WMS
// Page: Products Manager - Edit Product Category page
// Path/File: /admin/products_admin7_edit.php
// Version: 1.8
// Build: 1808
// Date: 03-25-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$edit = $_POST["edit"];
$name = $_POST["name"];
$description = $_POST["description"];
$image = $_POST["image"];
$image_alt_text = $_POST["image_alt_text"];
$image_width = $_POST["image_width"];
$image_height = $_POST["image_height"];
$is_parent = $_POST["is_parent"];
$parent_cat = $_POST["parent_cat"];
$parent_cat2 = $_POST["parent_cat2"];
$parent_cat3 = $_POST["parent_cat3"];
$position = $_POST["position"];
$display_name_description = $_POST["display_name_description"];
$display_on_website = $_POST["display_on_website"];
$display_in_wc = $_POST["display_in_wc"];
$active = $_POST["active"];
$prod_cat_id = $_POST["prod_cat_id"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Edit Product Categories";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($edit) {
	//Validate
	$error_txt = "";
	if($name == "") { $error_txt .= "Error, the Product Category Name is blank. You must enter a Name for this product category.<br>\n"; }
	
	if($error_txt == "") {
		$query = "UPDATE product_categories SET name='$name', description='$description', image='$image', image_alt_text='$image_alt_text', image_width='$image_width', image_height='$image_height', is_parent='$is_parent', parent_cat='$parent_cat', parent_cat2='$parent_cat2', parent_cat3='$parent_cat3', position='$position', display_name_description='$display_name_description', display_on_website='$display_on_website', display_in_wc='$display_in_wc', active='$active' WHERE prod_cat_id='$prod_cat_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Goto Manage Product Categories page
		header("Location: " . $base_url . "admin/products_admin7.php");
		exit;
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

<tr><td align="left"><font size="2">Make changes to the Product Category below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

$query = "SELECT prod_cat_id, created, modified, name, description, image, image_alt_text, image_width, image_height, is_parent, parent_cat, parent_cat2, parent_cat3, position, display_name_description, display_on_website, display_in_wc, active FROM product_categories WHERE prod_cat_id='$prod_cat_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prod_cat_id = $line["prod_cat_id"];
	$created = $line["created"];
	$modified = $line["modified"];
	$name = $line["name"];
	$description = $line["description"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
	$image_width = $line["image_width"];
	$image_height = $line["image_height"];
	$is_parent = $line["is_parent"];
	$parent_cat = $line["parent_cat"];
	$parent_cat2 = $line["parent_cat2"];
	$parent_cat3 = $line["parent_cat3"];
	$position = $line["position"];
	$display_name_description = $line["display_name_description"];
	$display_on_website = $line["display_on_website"];
	$display_in_wc = $line["display_in_wc"];
	$active = $line["active"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="products" Method="POST" ACTION="./products_admin7_edit.php" class="wmsform">
	<input type="hidden" name="prod_cat_id" value="<?php echo $prod_cat_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Product Category Information</legend>
		<ol>
			<li>
				<label for="created">Date Created</label>
				<?php echo $created; ?>
			</li>
			<li>
				<label for="modified">Date Modified</label>
				<?php echo $modified; ?>
			</li>
			<li>
				<label for="name">Product Category Name <em>*</em></label>
				<INPUT type="text" id="name" name="name" size="30" maxlength="255" value="<?php echo $name; ?>" tabindex="1" />
			</li>
			<li>
				<label for="description">Description <em>*</em></label>
				<TEXTAREA id="description" name="description" cols="35" rows="7" tabindex="2"><?php echo $description; ?></TEXTAREA>
			</li>
			<li>
				<label for="image">Image <em>*</em></label>
				<INPUT type="text" id="image" name="image" size="30" maxlength="255" value="<?php echo $image; ?>" tabindex="3" />
			</li>
			<li>
				<label for="image_alt_text">Image Alt Text <em>*</em></label>
				<INPUT type="text" id="image_alt_text" name="image_alt_text" size="30" maxlength="255" value="<?php echo $image_alt_text; ?>" tabindex="4" />
			</li>
			<li class="fm-optional">
				<label for="image_width">Image Width</label>
				<INPUT type="text" id="image_width" name="image_width" size="3" maxlength="3" value="<?php echo $image_width; ?>" tabindex="5" />
			</li>
			<li class="fm-optional">
				<label for="image_height">Image Height</label>
				<INPUT type="text" id="image_height" name="image_height" size="3" maxlength="3" value="<?php echo $image_height; ?>" tabindex="6" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Display Information</legend>
		<ol>
			<li>
				<label for="is_parent">Is Category Parent <em>*</em></label>
				<select id="is_parent" name="is_parent" tabindex="7">
				<option value="1"<?php if(isset($is_parent) && $is_parent == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($is_parent) && $is_parent == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="parent_cat">Parent Category <em>*</em></label>
				<select id="parent_cat" name="parent_cat" tabindex="8">
				<option value="0">None</option>
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE is_parent='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$prod_cat_id = $line["prod_cat_id"];
					$name = $line["name"];
					echo "<option value=\"$prod_cat_id\"";
					if($parent_cat == $prod_cat_id) { echo " SELECTED"; }
					echo ">$name</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li>
				<label for="parent_cat2">Parent Category 2 <em>*</em></label>
				<select id="parent_cat2" name="parent_cat2" tabindex="9">
				<option value="0">None</option>
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE is_parent='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$prod_cat_id = $line["prod_cat_id"];
					$name = $line["name"];
					echo "<option value=\"$prod_cat_id\"";
					if($parent_cat2 == $prod_cat_id) { echo " SELECTED"; }
					echo ">$name</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li>
				<label for="parent_cat3">Parent Category 3 <em>*</em></label>
				<select id="parent_cat3" name="parent_cat3" tabindex="10">
				<option value="0">None</option>
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE is_parent='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$prod_cat_id = $line["prod_cat_id"];
					$name = $line["name"];
					echo "<option value=\"$prod_cat_id\"";
					if($parent_cat3 == $prod_cat_id) { echo " SELECTED"; }
					echo ">$name</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li>
				<label for="position">Position in Category <em>*</em></label>
				<select id="position" name="position" tabindex="11">
				<?php
				for($i=1; $i < 100; $i++) {
					echo "<option value=\"$i\"";
					if(isset($position) && $position == $i) { echo " SELECTED"; }
					echo ">$i</option>\n";
				}
				?>
				</select>
			</li>
			<li>
				<label for="display_name_description">Display Category Name and Description <em>*</em></label>
				<select id="display_name_description" name="display_name_description" tabindex="12">
				<option value="1"<?php if(isset($display_name_description) && $display_name_description == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_name_description) && $display_name_description == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="display_on_website">Display on Website <em>*</em></label>
				<select id="display_on_website" name="display_on_website" tabindex="13">
				<option value="1"<?php if(isset($display_on_website) && $display_on_website == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_on_website) && $display_on_website == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="display_in_wc">Display in Wholesale Catalog <em>*</em></label>
				<select id="display_in_wc" name="display_in_wc" tabindex="14">
				<option value="1"<?php if(isset($display_in_wc) && $display_in_wc == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_in_wc) && $display_in_wc == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="active">Active <em>*</em></label>
				<select id="active" name="active" tabindex="15">
				<option value="1"<?php if(isset($active) && $active == '1') { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if(isset($active) && $active == '0') { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="edit" name="edit" value="Edit Product Category">
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