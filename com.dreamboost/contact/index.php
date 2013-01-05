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
$heard_about = $_POST["heard_about"];

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
		$error_txt .= "Please enter your name.<br>\n";
	}
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "Please enter a valid e-mail address.<br>\n";
	}
	if($heard_about == "") {
		$error_txt .= "Please tell us how you heard about $product_name.<br>\n";
	}
	if($subject == "") {
		$error_txt .= "Please enter the subject of your message.<br>\n";
	}
	if($comments == "") {
		$error_txt .= "Please enter your comments/questions.<br>\n";
	}

	if($error_txt == "") {
		//Insert into DB
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO contact_us SET created='$now', name='$name', email='$email', address1='$address1', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', subject='$subject', comments='$comments', heard_about='$heard_about'";
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

</head>
<body>

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
	echo "<tr><td align=\"left\" class=\"style2 error\">$error_txt</td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left" class="style2">For more information please contact us at:<br>
1-888-725-9664<br>
Or by e-mail at <a href="mailto:info@dreamboost.com">info@dreamboost.com</a><br>
<br>
You can contact Jeff directly at <a href="mailto:jeff@dreamboost.com">jeff@dreamboost.com</a><br>
You can contact our Webmaster directly at <a href="mailto:webmaster@dreamboost.com">webmaster@dreamboost.com</a></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="center">
<table border="0"><tr><td align="left">
	<div class="window_top">
		  <div class="window_top_content">
			Required fields marked <em>*</em>
		  </div>

		  <div class="window_content">
			<FORM name="contact" Method="POST" ACTION="./index.php">
			<input type="hidden" name="submit" value="1">
			<fieldset>
				<ol>
					<li>
						<label for="name">Name <em>*</em></label><br />
						<INPUT type="text" id="name" name="name" size="30" maxlength="255" value="<?php echo $name; ?>" tabindex="1" />
					</li>
					<li>
						<label for="email">E-Mail <em>*</em></label><br />
						<INPUT type="text" id="email" name="email" size="30" maxlength="150" value="<?php echo $email; ?>" tabindex="2" />
					</li>
					<li class="fm-optional">
						<label for="address1">Address</label><br />
						<INPUT type="text" id="address1" name="address1" size="30" maxlength="100" value="<?php echo $address1; ?>" tabindex="3" />
					</li>
					<li class="fm-optional">
						<label for="address2">Address 2</label><br />
						<INPUT type="text" id="address2" name="address2" size="30" maxlength="100" value="<?php echo $address2; ?>" tabindex="4" />
					</li>
					<li class="fm-optional">
						<label for="city">Town/City</label><br />
						<INPUT type="text" id="city" name="city" size="30" maxlength="100" value="<?php echo $city; ?>" tabindex="5" />
					</li>
					<li class="fm-optional">
						<label for="state">State/Province</label><br />
						<select id="state" name="state" tabindex="6">
						<?php
						state_build_all($state);
						?>
						</select>
					</li>
					<li class="fm-optional">
						<label for="zip">Zip/Postal Code</label><br />
						<INPUT type="text" id="zip" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>" tabindex="7" />
					</li>
					<li class="fm-optional">
						<label for="country">Country</label><br />
						<select id="country" name="country" tabindex="8">
						<?php
						country_build_all($country);
						?>
						</select>
					</li>
					<li class="fm-optional">
						<label for="phone">Phone</label><br />
						<INPUT type="text" id="phone" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>" tabindex="9" />
					</li>
				</ol>
			</fieldset>
			<fieldset>
				<ol>		
					<li>
						<label for="subject">How did you hear about <?=$product_name?>? <em>*</em></label><br />
						<INPUT type="text" id="heard_about" name="heard_about" size="30" value="<?php echo $heard_about; ?>" tabindex="10" />
					</li>
                    <li>
						<label for="subject">Subject <em>*</em></label><br />
						<INPUT type="text" id="subject" name="subject" size="30" maxlength="150" value="<?php echo $subject; ?>" tabindex="10" />
					</li>
                    <li>
						<label for="comments">Comments/Questions <em>*</em></label><br />
						<TEXTAREA id="comments" name="comments" cols="35" rows="7" tabindex="11"><?php echo $comments; ?></TEXTAREA>
					</li>
					<li class="fm-none">
						<fieldset>
							<legend>Please subscribe me to the <?php echo $website_title; ?> Newsletter</legend>
							<label for="newsletter"><input type="checkbox" id="newsletter" name="newsletter" value="1" CHECKED tabindex="12" /> Yes</label><br />
						</fieldset>
					</li>
					<li class="fm-button-none">
						<input type="image" src="/images/button_contact_us.gif" id="button_contact_us" name="button_contact_us" alt="Contact Us" onmouseover="MM_swapImage('button_contact_us','','/images/button_contact_us_over.gif',1)" onmouseout="MM_swapImgRestore()">
					</li>
				</ol>
			</fieldset>
			</form>

		  </div>
		<div class="window_bottom"><div class="window_bottom_end"></div>
	</div>

	
</td></tr></table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>