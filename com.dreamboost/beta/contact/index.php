<?php
// BME WMS
// Page: Contact Us Homepage
// Path/File: /contact/index.php
// Version: 1.8
// Build: 1802
// Date: 01-29-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/st_and_co1.php';
$line_hgt = 1300;

$submit = $_POST["submit"];
$name = $_POST["name"];
$email = $_POST["email"];
$address1 = $_POST["address1"];
$address2 = $_POST["address2"];
$city = $_POST["city"];
$state = $_POST["state"];
$zip = $_POST["zip"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$subject = $_POST["subject"];
$comments = $_POST["comments"];
$newsletter = $_POST["newsletter"];

	$query = "SELECT email, notify_owner FROM contact_us_main";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$contact_us_email = $line["email"];
		$notify_owner = $line["notify_owner"];
	}
	mysql_free_result($result);

if($submit) {
	//Validate
	$error_txt = "";
	if($name == "") {
		$error_txt .= "Error, you did not enter a name. Please enter your name.<br>\n";
	}
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "Error, you did not enter your E-Mail or it is incorrect. Please enter your e-mail address.<br>\n";
	}
	if($subject == "") {
		$error_txt .= "Error, you did not enter a subject. Please enter your comments/questions subject.<br>\n";
	}
	if($comments == "") {
		$error_txt .= "Error, you did not enter comments/questions. Please enter your comments/questions.<br>\n";
	}

	if($error_txt == "") {
		//Insert into DB
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO contact_us SET created='$now', name='$name', email='$email', address1='$address1', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', subject='$subject', comments='$comments'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		//Send Email to User
		$query = "SELECT content, subject, email FROM contact_us_emails WHERE cuemails_id='1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$content = $line["content"];
			$subject = $line["subject"];
			$email_tmp = $line["email"];
		}
		mysql_free_result($result);

		$email_str = "Dear " . $name . ",\n\n";
		$email_str .= $content;
		
		$email_subj = $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);

		//Notify Owner
		if($notify_owner == "1") {
			$query = "SELECT content, subject, email FROM contact_us_emails WHERE cuemails_id='2'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$content = $line["content"];
				$subject = $line["subject"];
				$email_tmp = $line["email"];
			}
			mysql_free_result($result);
			
			$email_str = "";
			$email_str .= $content;
		
			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($contact_us_email, $email_subj, $email_str, $email_from);

		}

		if($newsletter == "1") {
			//Subscribe
			$subscribe = "0";
			//Check DB
			$query = "SELECT email FROM news_member WHERE email='$email'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$tmp_email = $line["email"];
			}
			mysql_free_result($result);
		
			if($tmp_email == $email) {
				$subscribe = "1";
			}
			if($subscribe == "0") {
				//Write to DBs
				$now = date("Y-m-d H:i:s");
				$query = "INSERT INTO news_member SET created='$now', status='0', name='$name', email='$email'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());

				$query = "SELECT member_id FROM news_member WHERE email='$email'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$tmp_member_id = $line["member_id"];
				}
				mysql_free_result($result);

				$now = date("Y-m-d H:i:s");
				$query = "INSERT INTO news_subscriptions SET created='$now', member_id='$tmp_member_id', newsletter_id='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
				//Send Confirmation Email
				$query = "SELECT content, subject, email FROM news_email WHERE email_id='1'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$content = $line["content"];
					$subject = $line["subject"];
					$email_tmp = $line["email"];
				}
				mysql_free_result($result);

				$email_str = "";
				$email_str .= $content;
				$email_str .= "\n\n";
				$email_str .= $base_url . "newsletters/index2.php?confirm=1&member_id=";
				$email_str .= $tmp_member_id;
				$email_str .= "\n\n\n";

				$email_subj = $subject;
				$email_from = "FROM: " . $email_tmp;
				mail($email, $email_subj, $email_str, $email_from);				
			}
		}

		//Redirect to Thank You Page
		header("Location: " . $base_url . "contact/thanks.php");
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Contact Us | <?php echo $website_title; ?></title>

<?php
include '../includes/meta1.php';
?>

<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css" />
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<script type="text/javascript" src="/includes/jquery-1.5.2.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/button_contact_us_over.gif','/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Contact Us</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left" class="style2">For more information please contact us at:<br>
1-888-725-9663<br>
Or by e-mail at <a href="mailto:info@dreamboost.com">info@dreamboost.com</a><br>
<br>
You can contact Jeff directly at <a href="mailto:jeff@dreamboost.com">jeff@dreamboost.com</a><br>
You can contact Brian directly at <a href="mailto:brian@dreamboost.com">brian@dreamboost.com</a><br>
You can contact our Webmaster directly at <a href="mailto:webmaster@dreamboost.com">webmaster@dreamboost.com</a></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="center">
<table border="0"><tr><td align="left">
	<FORM name="contact" Method="POST" ACTION="./index.php" class="wmsform">
	<input type="hidden" name="submit" value="1">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Your Information</legend>
		<ol>
			<li>
				<label for="name">Name <em>*</em></label>
				<INPUT type="text" id="name" name="name" size="30" maxlength="255" value="<?php echo $name; ?>" tabindex="1" />
			</li>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="150" value="<?php echo $email; ?>" tabindex="2" />
			</li>
			<li class="fm-optional">
				<label for="address1">Address</label>
				<INPUT type="text" id="address1" name="address1" size="30" maxlength="100" value="<?php echo $address1; ?>" tabindex="3" />
			</li>
			<li class="fm-optional">
				<label for="address2">Address 2</label>
				<INPUT type="text" id="address2" name="address2" size="30" maxlength="100" value="<?php echo $address2; ?>" tabindex="4" />
			</li>
			<li class="fm-optional">
				<label for="city">Town/City</label>
				<INPUT type="text" id="city" name="city" size="30" maxlength="100" value="<?php echo $city; ?>" tabindex="5" />
			</li>
			<li class="fm-optional">
				<label for="state">State/Province</label>
				<select id="state" name="state" tabindex="6">
				<?php
				state_build_all($state);
				?>
				</select>
			</li>
			<li class="fm-optional">
				<label for="zip">Zip/Postal Code</label>
				<INPUT type="text" id="zip" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>" tabindex="7" />
			</li>
			<li class="fm-optional">
				<label for="country">Country</label>
				<select id="country" name="country" tabindex="8">
				<?php
				country_build_all($country);
				?>
				</select>
			</li>
			<li class="fm-optional">
				<label for="phone">Phone</label>
				<INPUT type="text" id="phone" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>" tabindex="9" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Please Enter Your Comments/Questions</legend>
		<ol>		
			<li>
				<label for="subject">Subject <em>*</em></label>
				<INPUT type="text" id="subject" name="subject" size="30" maxlength="150" value="<?php echo $subject; ?>" tabindex="10" />
			</li>
			<li>
				<label for="comments">Comments/Questions <em>*</em></label>
				<TEXTAREA id="comments" name="comments" cols="35" rows="7" tabindex="11"><?php echo $comments; ?></TEXTAREA>
			</li>
			<li class="fm-none">
				<fieldset>
					<legend>Please subscribe me to the <?php echo $website_title; ?> Newsletter</legend>
					<label for="newsletter"><input type="checkbox" id="newsletter" name="newsletter" value="1" CHECKED tabindex="12" /> Yes</label>
				</fieldset>
			</li>
			<li class="fm-button">
				<input type="image" src="/images/button_contact_us.gif" id="button_contact_us" name="button_contact_us" alt="Contact Us" onmouseover="MM_swapImage('button_contact_us','','/images/button_contact_us_over.gif',1)" onmouseout="MM_swapImgRestore()">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr></table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>