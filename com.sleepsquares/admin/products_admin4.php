<?php
// BME WMS
// Page: Products Manager - Create Product page
// Path/File: /admin/products_admin4.php
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

$create = $_POST["create"];
$name = $_POST["name"];
$sub_name = $_POST["sub_name"];
$pricing = $_POST["pricing"];
$description = $_POST["description"];
$ordering_info = $_POST["ordering_info"];
$image = $_POST["image"];
$image_alt_text = $_POST["image_alt_text"];
$image_width = $_POST["image_width"];
$image_height = $_POST["image_height"];
$image_thumbnail = $_POST["image_thumbnail"];
$image_thumbnail_width = $_POST["image_thumbnail_width"];
$image_thumbnail_height = $_POST["image_thumbnail_height"];
$position = $_POST["position"];
$display_on_website = $_POST["display_on_website"];
$display_in_wc = $_POST["display_in_wc"];
$active = $_POST["active"];
$prod_cat_id = $_POST["prod_cat_id"];
$prod_cat_id2 = $_POST["prod_cat_id2"];
$prod_cat_id3 = $_POST["prod_cat_id3"];

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Create Products";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($create) {
	//Validate
	$error_txt = "";
	if($name == "") { $error_txt .= "Error, the Product Name is blank. You must enter a Name for this product.<br>\n"; }
	
	if($error_txt == "") {
		$created = date("Y-m-d H:i:s");
		$query = "INSERT INTO products SET prod_cat_id='$prod_cat_id', prod_cat_id2='$prod_cat_id2', prod_cat_id3='$prod_cat_id3', created='$created', name='$name', sub_name='$sub_name', description='$description', ordering_info='$ordering_info', image='$image', image_alt_text='$image_alt_text', image_width='$image_width', image_height='$image_height', image_thumbnail='$image_thumbnail', image_thumbnail_width='$image_thumbnail_width', image_thumbnail_height='$image_thumbnail_height', position='$position', display_on_website='$display_on_website', display_in_wc='$display_in_wc', active='$active', pricing='$pricing'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		//Goto Manage Products page
		header("Location: " . $base_url . "admin/products_admin3.php");
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

<tr><td align="left"><font size="2">On this page you can create a Product.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="products" Method="POST" ACTION="./products_admin4.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Product Information</legend>
		<ol>
			<li>
				<label for="name">Product Name <em>*</em></label>
				<INPUT type="text" id="name" name="name" size="30" maxlength="255" value="<?php echo $name; ?>" />
			</li>
			<li>
				<label for="prod_cat_id">Product Category <em>*</em></label>
				<select id="prod_cat_id" name="prod_cat_id">
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE active='1' ORDER BY active DESC, parent_cat, position";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					echo "<option value=\"";
					echo $line["prod_cat_id"];
					echo "\"";
					if($prod_cat_id == $line["prod_cat_id"]) { echo " SELECTED"; }
					echo ">";
					echo $line["name"];
					echo "</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li>
				<label for="prod_cat_id2">Product Category 2 <em>*</em></label>
				<select id="prod_cat_id2" name="prod_cat_id2">
				<option value="0">None</option>
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE active='1' ORDER BY active DESC, parent_cat, position";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					echo "<option value=\"";
					echo $line["prod_cat_id"];
					echo "\"";
					if($prod_cat_id2 == $line["prod_cat_id"]) { echo " SELECTED"; }
					echo ">";
					echo $line["name"];
					echo "</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li>
				<label for="prod_cat_id3">Product Category 3 <em>*</em></label>
				<select id="prod_cat_id3" name="prod_cat_id3">
				<option value="0">None</option>
				<?php
				$query = "SELECT prod_cat_id, name FROM product_categories WHERE active='1' ORDER BY active DESC, parent_cat, position";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					echo "<option value=\"";
					echo $line["prod_cat_id"];
					echo "\"";
					if($prod_cat_id3 == $line["prod_cat_id"]) { echo " SELECTED"; }
					echo ">";
					echo $line["name"];
					echo "</option>\n";
				}
				mysql_free_result($result);
				?>
				</select>
			</li>
			<li class="fm-optional">
				<label for="sub_name">Secondary Product Name</label>
				<INPUT type="text" id="sub_name" name="sub_name" size="30" maxlength="255" value="<?php echo $sub_name; ?>" />
			</li>
			<li class="fm-optional">
				<label for="pricing">Pricing</label>
				<INPUT type="text" id="pricing" name="pricing" size="30" maxlength="255" value="<?php echo $pricing; ?>" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Product Information</legend>
		<ol>
			<li>
				<label for="description">Description <em>*</em></label>
				<TEXTAREA id="description" name="description" cols="35" rows="7" tabindex="12"><?php echo $description; ?></TEXTAREA>
			</li>
			<li>
				<label for="ordering_info">Ordering Information <em>*</em></label>
				<TEXTAREA id="ordering_info" name="ordering_info" cols="35" rows="7" tabindex="13"><?php echo $ordering_info; ?></TEXTAREA>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Product Information</legend>
		<ol>
			<li>
				<label for="image">Image <em>*</em></label>
				<INPUT type="text" id="image" name="image" size="30" maxlength="255" value="<?php echo $image; ?>" tabindex="14" />
			</li>
			<li>
				<label for="image_alt_text">Image Alt Text <em>*</em></label>
				<INPUT type="text" id="image_alt_text" name="image_alt_text" size="30" maxlength="255" value="<?php echo $image_alt_text; ?>" tabindex="15" />
			</li>
			<li class="fm-optional">
				<label for="image_width">Image Width</label>
				<INPUT type="text" id="image_width" name="image_width" size="4" maxlength="4" value="<?php echo $image_width; ?>" tabindex="16" />
			</li>
			<li class="fm-optional">
				<label for="image_height">Image Height</label>
				<INPUT type="text" id="image_height" name="image_height" size="4" maxlength="4" value="<?php echo $image_height; ?>" tabindex="17" />
			</li>
			<li class="fm-optional">
				<label for="image_thumbnail">Image Thumbnail</label>
				<INPUT type="text" id="image_thumbnail" name="image_thumbnail" size="30" maxlength="150" value="<?php echo $image_thumbnail; ?>" tabindex="18" />
			</li>
			<li class="fm-optional">
				<label for="image_thumbnail_width">Image Thumbnail Width</label>
				<INPUT type="text" id="image_thumbnail_width" name="image_thumbnail_width" size="3" maxlength="3" value="<?php echo $image_thumbnail_width; ?>" tabindex="19" />
			</li>
			<li class="fm-optional">
				<label for="image_thumbnail_height">Image Thumbnail Height</label>
				<INPUT type="text" id="image_thumbnail_height" name="image_thumbnail_height" size="3" maxlength="3" value="<?php echo $image_thumbnail_height; ?>" tabindex="20" />
			</li>
			<li>
				<label for="position">Position in Category <em>*</em></label>
				<select id="position" name="position" tabindex="21">
				<?php
				for($i=1; $i<100; $i++) {
					echo "<option value=\"$i\"";
					if(isset($position) && $position == $i) { echo " SELECTED"; }
					echo ">$i</option>\n";
				}
				?>
				</select>
			</li>
			<li>
				<label for="display_on_website">Display on Website <em>*</em></label>
				<select id="display_on_website" name="display_on_website" tabindex="22">
				<option value="1"<?php if(isset($display_on_website) && $display_on_website == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_on_website) && $display_on_website == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="display_in_wc">Display in Wholesale Catalog <em>*</em></label>
				<select id="display_in_wc" name="display_in_wc" tabindex="23">
				<option value="1"<?php if(isset($display_in_wc) && $display_in_wc == '1') { echo " SELECTED"; } ?>>Yes</option>
				<option value="0"<?php if(isset($display_in_wc) && $display_in_wc == '0') { echo " SELECTED"; } ?>>No</option>
				</select>
			</li>
			<li>
				<label for="active">Active <em>*</em></label>
				<select id="active" name="active" tabindex="24">
				<option value="1"<?php if(isset($active) && $active == '1') { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if(isset($active) && $active == '0') { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="create" name="create" value="Create Product">
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