<?php
// BME WMS
// Page: Content Edit Article page
// Path/File: /admin/content_admin7_edit.php
// Version: 1.8
// Build: 1806
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

$article_id = $_POST["article_id"];
$position = $_POST["position"];
$status = $_POST["status"];
$category = $_POST["category"];
$article_name = $_POST["article_name"];
$headline = $_POST["headline"];
$subheadline = $_POST["subheadline"];
$description = $_POST["description"];
$keywords = $_POST["keywords"];
$by_line = $_POST["by_line"];
$body = $_POST["body"];
$parent_id = $_POST["parent_id"];
$line_hgt = $_POST["line_hgt"];
$image = $_POST["image"];
$image_alt_text = $_POST["image_alt_text"];
$image_width = $_POST["image_width"];
$image_height = $_POST["image_height"];
$image_align = $_POST["image_align"];
$image_thumbnail = $_POST["image_thumbnail"];
$image_thumbnail_width = $_POST["image_thumbnail_width"];
$image_thumbnail_height = $_POST["image_thumbnail_height"];
$submit = $_POST["submit"];

include './includes/wms_nav1.php';
$manager = "content";
$page = "Content Manager > Edit Inactive Article";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Add HTML code
	$body = str_replace("\n", "<br>", $body);

	//Check for Errors
	$error_txt = "";
	if($article_name == "") { $error_txt .= "Error, the Article Name is blank. There needs to be an article_name.<br>"; }
	if($headline == "") { $error_txt .= "Error, the Headline is blank. There needs to be a headline.<br>"; }
	if($body == "") { $error_txt .= "Error, the Body is blank. There needs to be a body.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE article SET position='$position', status='$status', category='$category', article_name='$article_name', headline='$headline', subheadline='$subheadline', description='$description', keywords='$keywords', by_line='$by_line', body='$body', parent_id='$parent_id', line_hgt='$line_hgt', image='$image', image_alt_text='$image_alt_text', image_width='$image_width', image_height='$image_height', image_align='$image_align', image_thumbnail='$image_thumbnail', image_thumbnail_width='$image_thumbnail_width', image_thumbnail_height='$image_thumbnail_height' WHERE article_id='$article_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		if($status == 1) {
			//Goto Manage Live page
			header("Location: " . $base_url . "admin/content_admin2.php");
			exit;
		} elseif($status == 2) {
			//Goto Manage Review page
			header("Location: " . $base_url . "admin/content_admin4.php");
			exit;
		} elseif($status == 0) {
			//Goto Manage Inactive page
			header("Location: " . $base_url . "admin/content_admin7.php");
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

<tr><td align="left"><font size="2">On this page you can edit the selected Article.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT article_id, status, category, position, article_name, headline, subheadline, description, keywords, by_line, body, parent_id, line_hgt, image, image_alt_text, image_width, image_height, image_align, image_thumbnail, image_thumbnail_width, image_thumbnail_height FROM article WHERE article_id='$article_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$position = $line["position"];
	$status = $line["status"];
	$category = $line["category"];
	$article_name = $line["article_name"];
	$headline = $line["headline"];
	$subheadline = $line["subheadline"];
	$description = $line["description"];
	$keywords = $line["keywords"];
	$by_line = htmlentities($line["by_line"]);
	$body = $line["body"];
	$parent_id = $line["parent_id"];
	$line_hgt = $line["line_hgt"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
	$image_width = $line["image_width"];
	$image_height = $line["image_height"];
	$image_align = $line["image_align"];
	$image_thumbnail = $line["image_thumbnail"];
	$image_thumbnail_width = $line["image_thumbnail_width"];
	$image_thumbnail_height = $line["image_thumbnail_height"];
	$article_id = $line["article_id"];
}
mysql_free_result($result);

$body = str_replace("\n", "", $body);
$body = str_replace("<br>", "\n", $body);
?>

<tr><td align="left">
	<FORM name="content" Method="POST" ACTION="./content_admin7_edit.php" class="wmsform">
	<input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Article</legend>
		<ol>
			<li>
				<label for="position">Position <em>*</em></label>
				<select id="position" name="position" tabindex="1">
				<?php
				for($i=1;$i<100;$i++){
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
				<label for="category">Category <em>*</em></label>
				<select id="category" name="category" tabindex="3">
				<?php
				$query2 = "SELECT article_category_id, category_name FROM article_categories";
				$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					echo "<option value=\"".$line2["article_category_id"] . "\"";
					if($category == $line2["article_category_id"]) { echo " SELECTED"; }
					echo ">";
					echo $line2["category_name"];
					echo "</option>\n";
				}
				mysql_free_result($result2);
				?>
				</select>
			</li>
			<li>
				<label for="article_name">Article Name <em>*</em></label>
				<INPUT type="text" id="article_name" name="article_name" size="30" maxlength="150" value="<?php echo $article_name; ?>" tabindex="4" />
			</li>
			<li class="fm-optional">
				<label for="by_line">By Line</label>
				<INPUT type="text" id="by_line" name="by_line" size="30" maxlength="100" value="<?php echo $by_line; ?>" tabindex="5" />
			</li>
			<li class="fm-optional">
				<label for="parent_id">Parent ID</label>
				<INPUT type="text" id="parent_id" name="parent_id" size="30" maxlength="11" value="<?php echo $parent_id; ?>" tabindex="6" />
			</li>
			<li>
				<label for="line_hgt">Line Height <em>*</em></label>
				<select id="line_hgt" name="line_hgt" tabindex="7">
				<option value="500"<?php if($line_hgt == 500) { echo " SELECTED"; } ?>>500</option>
				<option value="600"<?php if($line_hgt == 600) { echo " SELECTED"; } ?>>600</option>
				<option value="700"<?php if($line_hgt == 700) { echo " SELECTED"; } ?>>700</option>
				<option value="800"<?php if($line_hgt == 800) { echo " SELECTED"; } ?>>800</option>
				<option value="850"<?php if($line_hgt == 850) { echo " SELECTED"; } ?>>850</option>
				<option value="900"<?php if($line_hgt == 900) { echo " SELECTED"; } ?>>900</option>
				<option value="950"<?php if($line_hgt == 950) { echo " SELECTED"; } ?>>950</option>
				<option value="1000"<?php if($line_hgt == 1000) { echo " SELECTED"; } ?>>1000</option>
				<option value="1050"<?php if($line_hgt == 1050) { echo " SELECTED"; } ?>>1050</option>
				<option value="1100"<?php if($line_hgt == 1100) { echo " SELECTED"; } ?>>1100</option>
				<option value="1150"<?php if($line_hgt == 1150) { echo " SELECTED"; } ?>>1150</option>
				<option value="1200"<?php if($line_hgt == 1200) { echo " SELECTED"; } ?>>1200</option>
				<option value="1250"<?php if($line_hgt == 1250) { echo " SELECTED"; } ?>>1250</option>
				<option value="1300"<?php if($line_hgt == 1300) { echo " SELECTED"; } ?>>1300</option>
				<option value="1400"<?php if($line_hgt == 1400) { echo " SELECTED"; } ?>>1400</option>
				<option value="1500"<?php if($line_hgt == 1500) { echo " SELECTED"; } ?>>1500</option>
				<option value="1600"<?php if($line_hgt == 1600) { echo " SELECTED"; } ?>>1600</option>
				<option value="1700"<?php if($line_hgt == 1700) { echo " SELECTED"; } ?>>1700</option>
				<option value="1800"<?php if($line_hgt == 1800) { echo " SELECTED"; } ?>>1800</option>
				<option value="1900"<?php if($line_hgt == 1900) { echo " SELECTED"; } ?>>1900</option>
				<option value="1950"<?php if($line_hgt == 1950) { echo " SELECTED"; } ?>>1950</option>
				<option value="2000"<?php if($line_hgt == 2000) { echo " SELECTED"; } ?>>2000</option>
				<option value="2050"<?php if($line_hgt == 2050) { echo " SELECTED"; } ?>>2050</option>
				<option value="2100"<?php if($line_hgt == 2100) { echo " SELECTED"; } ?>>2100</option>
				<option value="2600"<?php if($line_hgt == 2600) { echo " SELECTED"; } ?>>2600</option>
				<option value="2700"<?php if($line_hgt == 2700) { echo " SELECTED"; } ?>>2700</option>
				</select>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Article Content</legend>
		<ol>
			<li>
				<label for="headline">Headline <em>*</em></label>
				<INPUT type="text" id="headline" name="headline" size="50" maxlength="255" value="<?php echo $headline; ?>" tabindex="8" />
			</li>
			<li class="fm-optional">
				<label for="subheadline">Sub-Headline</label>
				<INPUT type="text" id="subheadline" name="subheadline" size="50" maxlength="255" value="<?php echo $subheadline; ?>" tabindex="9" />
			</li>
			<li>
				<label for="body">Body <em>*</em></label>
				<TEXTAREA id="body" name="body" cols="40" rows="9" tabindex="10"><?php echo $body; ?></TEXTAREA>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter META Tag Content</legend>
		<ol>
			<li>
				<label for="description">Description <em>*</em></label>
				<TEXTAREA id="description" name="description" cols="35" rows="7" tabindex="11"><?php echo $description; ?></TEXTAREA>
			</li>
			<li>
				<label for="keywords">Keywords <em>*</em></label>
				<TEXTAREA id="keywords" name="keywords" cols="35" rows="7" tabindex="12"><?php echo $keywords; ?></TEXTAREA>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Image Content</legend>
		<ol>
			<li class="fm-optional">
				<label for="image">Image</label>
				<INPUT type="text" id="image" name="image" size="30" maxlength="150" value="<?php echo $image; ?>" tabindex="13" />
			</li>
			<li class="fm-optional">
				<label for="image_alt_text">Image Alt Text</label>
				<INPUT type="text" id="image_alt_text" name="image_alt_text" size="30" maxlength="255" value="<?php echo $image_alt_text; ?>" tabindex="14" />
			</li>
			<li class="fm-optional">
				<label for="image_width">Image Width</label>
				<INPUT type="text" id="image_width" name="image_width" size="3" maxlength="3" value="<?php echo $image_width; ?>" tabindex="15" />
			</li>
			<li class="fm-optional">
				<label for="image_height">Image Height</label>
				<INPUT type="text" id="image_height" name="image_height" size="3" maxlength="3" value="<?php echo $image_height; ?>" tabindex="16" />
			</li>
			<li class="fm-optional">
				<label for="image_align">Image Alignment</label>
				<select id="image_align" name="image_align" tabindex="17">
				<option value="1"<?php if($image_align == "1") { echo " SELECTED"; } ?>>Left</option>
				<option value="2"<?php if($image_align == "2") { echo " SELECTED"; } ?>>Right</option>
				</select>
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
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save">
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