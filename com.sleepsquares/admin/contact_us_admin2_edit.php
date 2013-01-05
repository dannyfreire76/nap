<?php
// BME WMS
// Page: Contact Us Manager Answer Contact page
// Path/File: /admin/contact_us_admin2_edit.php
// Version: 1.8
// Build: 1804
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$answer2 = $_POST["answer2"];
$answer3 = $_POST["answer3"];
$answer = $_POST["answer"];
$contact_id = $_POST["contact_id"];
$name = $_POST["name"];
$email = $_POST["email"];
$subject = $_POST["subject"];
$comments = $_POST["comments"];

include './includes/wms_nav1.php';
$manager = "contact_us";
$page = "Contact Us Manager > Edit Unanswered Contacts";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($answer2) {
	$query = "SELECT email FROM contact_us_main";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$email_tmp = $line["email"];
	}
	mysql_free_result($result);

	$error_txt = "";
	if($answer == "") { $error_txt = "Error, the Answer is blank. You must enter an answer.<br>\n"; }
	
	if($error_txt == "") {
		$answered = date("Y-m-d H:i:s");
		$query = "UPDATE contact_us SET answered='$answered', answer='$answer' WHERE contact_id='$contact_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		$email_str = "Dear " . $name . ",\n\n";
		$email_str .= $answer . "\n\n";
		$email_str .= "In regards to your question\comments:\n";
		$email_str .= $comments . "\n";
		
		$email_subj = "RE: " . $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
		
		//Goto Manage Unanswered page
		header("Location: " . $base_url . "admin/contact_us_admin2.php");
		exit;
	}
} elseif($answer3) {
		$answered = date("Y-m-d H:i:s");
		$query = "UPDATE contact_us SET answered='$answered' WHERE contact_id='$contact_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Goto Manage Unanswered page
		header("Location: " . $base_url . "admin/contact_us_admin2.php");
		exit;
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

<tr><td align="left"><font size="2">Welcome to the Contact Us Manager, where you manage all the incoming contact e-mail requiring answers. On this page you can answer a contact.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
	$query = "SELECT contact_id, created, name, email, address1, address2, city, state, zip, country, phone, subject, comments, answer FROM contact_us WHERE contact_id='$contact_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		list($created_date, $created_time) = split(' ', $line["created"]);
		list($created_yr, $created_mn, $created_dy) = split('-', $created_date);
		$name = $line["name"];
		$email =  $line["email"];
		$address1 = $line["address1"];
		$address2 = $line["address2"];
		$city = $line["city"];
		$state = $line["state"];
		$zip = $line["zip"];
		$country = $line["country"];
		$phone = $line["phone"];
		$subject = htmlentities($line["subject"]);
		$comments = $line["comments"];
		$answer = $line["answer"];
		$contact_id = $line["contact_id"];
	}
	mysql_free_result($result);
?>

<tr><td align="left">
	<FORM name="contact_us" Method="POST" ACTION="./contact_us_admin2_edit.php" class="wmsform">
	<input type="hidden" name="name" value="<?php echo $name; ?>">
	<input type="hidden" name="email" value="<?php echo $email; ?>">
	<input type="hidden" name="subject" value="<?php echo $subject; ?>">
	<input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>User's Information</legend>
		<ol>
			<li class="fm-optional">
				<label for="created">Created</label>
				<?php echo $created_mn . "/" . $created_dy . "/" . $created_yr . " " . $created_time; ?>
			</li>
			<li class="fm-optional">
				<label for="name">Name</label>
				<?php echo $name; ?>
			</li>
			<li class="fm-optional">
				<label for="name">E-Mail</label>
				<?php echo $email; ?>
			</li>
			<li class="fm-optional">
				<label for="address1">Address</label>
				<?php echo $address1; ?>
			</li>
			<li class="fm-optional">
				<label for="address2">Address 2</label>
				<?php echo $address2; ?>
			</li>
			<li class="fm-optional">
				<label for="city">City</label>
				<?php echo $city; ?>
			</li>
			<li class="fm-optional">
				<label for="state">State</label>
				<?php echo $state; ?>
			</li>
			<li class="fm-optional">
				<label for="zip">Zip/Postal Code</label>
				<?php echo $zip; ?>
			</li>
			<li class="fm-optional">
				<label for="country">Country</label>
				<?php echo $country; ?>
			</li>
			<li class="fm-optional">
				<label for="phone">Phone</label>
				<?php echo $phone; ?>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Answer</legend>
		<ol>
			<li class="fm-optional">
				<label for="subject">Subject</label>
				<?php echo $subject; ?>
			</li>
			<li>
				<label for="comments">Comments\Question <em>*</em></label>
				<TEXTAREA id="comments" name="comments" cols="35" rows="7" tabindex="1"><?php echo $comments; ?></TEXTAREA>
			</li>
			<li>
				<label for="answer">Answer <em>*</em></label>
				<TEXTAREA id="answer" name="answer" cols="35" rows="7" tabindex="2"><?php echo $answer; ?></TEXTAREA>
			</li>
			<li class="fm-button">
				<input type="submit" id="answer2" name="answer2" value="Answer">
			</li>
			<li class="fm-button">
				<input type="submit" id="answer3" name="answer3" value="Mark As Answered">
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