<?php
// BME WMS
// Page: FAQs To Be Reviewed Edit FAQ page
// Path/File: /admin/faqs_admin4_edit.php
// Version: 1.8
// Build: 1804
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$faqs_id = $_POST["faqs_id"];
$position = $_POST["position"];
$category = $_POST["category"];
$question = $_POST["question"];
$answer = $_POST["answer"];
$submit = $_POST["submit"];

include './includes/wms_nav1.php';
$manager = "faqs";
$page = "FAQs Manager > Edit To Be Reviewed FAQs";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit) {
	//Check for Errors
	$error_txt = "";
	if($question == "") { $error_txt .= "Error, the question is blank. There needs to be a question.<br>"; }
	if($answer == "") { $error_txt .= "Error, the answer is blank. There needs to be an answer.<br>"; }
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE faqs SET position='$position', status='$status', category='$category', question='$question', answer='$answer' WHERE faqs_id='$faqs_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		if($status == 1) {
			//Goto Manage Live page
			header("Location: " . $base_url . "admin/faqs_admin2.php");
			exit;
		} elseif($status == 0) {
			//Goto Manage Review page
			header("Location: " . $base_url . "admin/faqs_admin4.php");
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

<tr><td align="left"><font size="2">These are the Frequently Asked Questions waiting to be reviewed - you can edit or remove them below.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$query = "SELECT faqs_id, status, category, position, question, answer FROM faqs WHERE faqs_id='$faqs_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$position = $line["position"];
	$status = $line["status"];
	$category = $line["category"];
	$question = $line["question"];
	$answer = $line["answer"];
	$faqs_id = $line["faqs_id"];
}
mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="faqs" Method="POST" ACTION="./faqs_admin4_edit.php" class="wmsform">
	<input type="hidden" name="faqs_id" value="<?php echo $faqs_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter FAQ Information</legend>
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
				<option value="0"<?php if($status == "0") { echo " SELECTED"; } ?>>Review</option>
				</select>
			</li>
			<li>
				<label for="category">Category <em>*</em></label>
				<select id="category" name="category" tabindex="3">
				<?php
				$query2 = "SELECT faq_category_id, category_name FROM faqs_categories";
				$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					echo "<option value=\"".$line2["faq_category_id"] . "\"";
					if($category == $line2["faq_category_id"]) { echo " SELECTED"; }
					echo ">";
					echo $line2["category_name"];
					echo "</option>\n";
				}
				mysql_free_result($result2);
				?>
				</select>
			</li>
			<li>
				<label for="question">Question <em>*</em></label>
				<TEXTAREA id="question" name="question" cols="35" rows="7" tabindex="4"><?php echo $question; ?></TEXTAREA>
			</li>
			<li>
				<label for="answer">Answer <em>*</em></label>
				<TEXTAREA id="answer" name="answer" cols="35" rows="7" tabindex="5"><?php echo $answer; ?></TEXTAREA>
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