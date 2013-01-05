<?php
// BME WMS
// Page: Testimonials Submit page
// Path/File: /testimonials/testimonial_submit.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

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
$fax = $_POST["fax"];
$testimonial = $_POST["testimonial"];
$product = $_POST["product"];
$agree = $_POST["agree"];
$newsletter = $_POST["newsletter"];

$query = "SELECT email, notify_owner FROM testimonials_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$test_owner = $line["email"];
	$notify_owner = $line["notify_owner"];

}
mysql_free_result($result);

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

if($submit != "") {
	//Validation
	$error_txt = "";
	if($name_reqd_submit == "1" && $name == "") {
		$error_txt .= "Error, you did not enter a name. Please enter your name.<br>\n";
	}
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "Error, you did not enter your E-Mail or it is incorrect. We need your e-mail address to let you know when Your Thoughts are posted.<br>\n";
	}
	if($address1_reqd_submit == "1" && $address1 == "") {
		$error_txt .= "Error, you did not enter your address. Please enter your address.<br>\n";
	}
	if($address2_reqd_submit == "1" && $address2 == "") {
		$error_txt .= "Error, you did not enter your address. Please enter your address.<br>\n";
	}
	if($city_reqd_submit == "1" && $city == "") {
		$error_txt .= "Error, you did not enter your city. Please enter your city.<br>\n";
	}
	if($state_reqd_submit == "1" && $state == "") {
		$error_txt .= "Error, you did not enter your state/province. Please enter your state/province.<br>\n";
	}
	if($zip_reqd_submit == "1" && $zip == "") {
		$error_txt .= "Error, you did not enter your zip/postal code. Please enter your zip/postal code.<br>\n";
	}
	if($country_reqd_submit == "1" && $country == "") {
		$error_txt .= "Error, you did not enter your country. Please enter your country.<br>\n";
	}
	if($phone_reqd_submit == "1" && $phone == "") {
		$error_txt .= "Error, you did not enter your phone number. Please enter your phone number.<br>\n";
	}
	if($fax_reqd_submit == "1" && $fax == "") {
		$error_txt .= "Error, you did not enter your fax number. Please enter your fax number.<br>\n";
	}
	if($testimonial_reqd_submit == "1" && $testimonial == "") {
		$error_txt .= "Error, you did not enter Your Testimonial. Please enter Your Testimonial before proceeding.<br>\n";
	}
	if($product_reqd_submit == "1" && $product == "") {
		$error_txt .= "Error, you did not enter your product. Please enter your product.<br>";
	}
	if($agree == "") {
		$error_txt .= "Error, you did not check the I Agree box at the bottom of the page. We can not post Your Thoughts unless you check the box. Please make sure to read the release form before checking the box.<br>\n";
	}

	//Check for errors
	if($error_txt == "") {
		//Write to DB
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO testimonials SET created='$now', status='0', position='1', name='$name', email='$email', address1='$address1', address2='$address2', city='$city', state='$state', zip='$zip', country='$country', phone='$phone', fax='$fax', testimonial='$testimonial', product='$product', agree='$agree'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Send Email
		$query = "SELECT content, subject, email FROM testimonial_emails WHERE email_id='1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$content = $line["content"];
			$subject = $line["subject"];
			$email_tmp = $line["email"];
		}
		mysql_free_result($result);

		$email_str = "Dear " . $name . ",\n\n";
		$email_str .= $content;
		$email_str .= "\n\n";
		$email_str .= "Your Thoughts: " . $testimonial . "\n\n";
		
		$email_subj = $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
		
		if($notify_owner == "1") {
			$query = "SELECT content, subject, email FROM testimonial_emails WHERE email_id='4'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$content = $line["content"];
				$subject = $line["subject"];
				$email_tmp = $line["email"];
			}
			mysql_free_result($result);

			$email_str = "Dear Testimonial Manager Owner,\n\n";
			$email_str .= $content;
			$email_str .= "\n\n";
		
			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($test_owner, $email_subj, $email_str, $email_from);
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
		header("Location: " . $base_url . "testimonials/thanks.php");
		exit;
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Send Dream Boost Customer Testimonials | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css" />
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/button_submit_testimonial_over.gif','/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Send Us Your Dream Boost Testimonial</td></tr>

<tr><td align="left" class="style2">Enter your Dream Boost Testimonial in the form below to send it to us. If we choose yours from the great ones we receive every day we'll post it on our website to share with the world.</td></tr>

<tr><td>&nbsp;</td></tr>

<?php
if($error_txt) { 
	echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<FORM name="testimonial" Method="POST" ACTION="./testimonial_submit.php">
<input type="hidden" name="submit" value="1">
<tr><td align="left"><table border="0">
<tr><td align="left" class="style2"><font color="red">*</font> Please Enter Your Information</td></tr>
<?php if($testimonial_submit == "1") { ?>
<tr><td colspan="2" VALIGN="TOP"><?php if($testimonial != "") { echo "<textarea name=\"testimonial\" cols=\"70\" rows=\"6\">";
	echo $testimonial; } 
else {  echo "<textarea name=\"testimonial\" cols=\"70\" rows=\"6\" onClick=\"document.testimonial.testimonial.value=''\">";
	echo "Type Your $testimonial_name Here"; } ?>
	</textarea></td></tr>
<?php } ?>

<?php if($name_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($name_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $name_name; ?>:
<?php if($name_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="name" size="30" maxlength="255" value="<?php echo $name; ?>"></td></tr>
<?php } ?>

<?php if($email_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($email_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $email_name; ?>:
<?php if($email_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="email" size="30" maxlength="255" value="<?php echo $email; ?>"></td></tr>
<?php } ?>

<?php if($address1_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($address1_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $address1_name; ?>:
<?php if($address1_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="address1" size="30" maxlength="100" value="<?php echo $address1; ?>"></td></tr>
<?php } ?>

<?php if($address2_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($address2_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $address2_name; ?>:
<?php if($address2_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="address2" size="30" maxlength="100" value="<?php echo $address2; ?>"></td></tr>
<?php } ?>

<?php if($city_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($city_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $city_name; ?>:
<?php if($city_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="city" size="30" maxlength="100" value="<?php echo $city; ?>"></td></tr>
<?php } ?>

<?php if($state_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($state_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $state_name; ?>:
<?php if($state_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><select name="state">
<option value="">Select a State</option>
<option value="AA"<?php if($state == "AA") { echo " SELECTED"; } ?>>AF Asia (AA)</option>
<option value="AE"<?php if($state == "AE") { echo " SELECTED"; } ?>>AF Europe (AE)</option>
<option value="AP"<?php if($state == "AP") { echo " SELECTED"; } ?>>AF Pacific (AP)</option>
<option value="AL"<?php if($state == "AL") { echo " SELECTED"; } ?>>Alabama</option>
<option value="AK"<?php if($state == "AK") { echo " SELECTED"; } ?>>Alaska</option>
<option value="AB"<?php if($state == "AB") { echo " SELECTED"; } ?>>Alberta</option>
<option value="AZ"<?php if($state == "AZ") { echo " SELECTED"; } ?>>Arizona</option>
<option value="AR"<?php if($state == "AR") { echo " SELECTED"; } ?>>Arkansas</option>
<option value="BC"<?php if($state == "BC") { echo " SELECTED"; } ?>>British Columbia</option>
<option value="CA"<?php if($state == "CA") { echo " SELECTED"; } ?>>California</option>
<option value="CO"<?php if($state == "CO") { echo " SELECTED"; } ?>>Colorado</option>
<option value="CT"<?php if($state == "CT") { echo " SELECTED"; } ?>>Connecticut</option>
<option value="DE"<?php if($state == "DE") { echo " SELECTED"; } ?>>Delaware</option>
<option value="DC"<?php if($state == "DC") { echo " SELECTED"; } ?>>District of Columbia</option>
<option value="FL"<?php if($state == "FL") { echo " SELECTED"; } ?>>Florida</option>
<option value="GA"<?php if($state == "GA") { echo " SELECTED"; } ?>>Georgia</option>
<option value="HI"<?php if($state == "HI") { echo " SELECTED"; } ?>>Hawaii</option>
<option value="ID"<?php if($state == "ID") { echo " SELECTED"; } ?>>Idaho</option>
<option value="IL"<?php if($state == "IL") { echo " SELECTED"; } ?>>Illinois</option>
<option value="IN"<?php if($state == "IN") { echo " SELECTED"; } ?>>Indiana</option>
<option value="IA"<?php if($state == "IA") { echo " SELECTED"; } ?>>Iowa</option>
<option value="KS"<?php if($state == "KS") { echo " SELECTED"; } ?>>Kansas</option>
<option value="KY"<?php if($state == "KY") { echo " SELECTED"; } ?>>Kentucky</option>
<option value="LA"<?php if($state == "LA") { echo " SELECTED"; } ?>>Louisiana</option>
<option value="ME"<?php if($state == "ME") { echo " SELECTED"; } ?>>Maine</option>
<option value="MB"<?php if($state == "MB") { echo " SELECTED"; } ?>>Manitoba</option>
<option value="MD"<?php if($state == "MD") { echo " SELECTED"; } ?>>Maryland</option>
<option value="MA"<?php if($state == "MA") { echo " SELECTED"; } ?>>Massachusetts</option>
<option value="MI"<?php if($state == "MI") { echo " SELECTED"; } ?>>Michigan</option>
<option value="MN"<?php if($state == "MN") { echo " SELECTED"; } ?>>Minnesota</option>
<option value="MS"<?php if($state == "MS") { echo " SELECTED"; } ?>>Mississippi</option>
<option value="MO"<?php if($state == "MO") { echo " SELECTED"; } ?>>Missouri</option>
<option value="MT"<?php if($state == "MT") { echo " SELECTED"; } ?>>Montana</option>
<option value="NE"<?php if($state == "NE") { echo " SELECTED"; } ?>>Nebraska</option>
<option value="NV"<?php if($state == "NV") { echo " SELECTED"; } ?>>Nevada</option>
<option value="NB"<?php if($state == "NB") { echo " SELECTED"; } ?>>New Brunswick</option>
<option value="NH"<?php if($state == "NH") { echo " SELECTED"; } ?>>New Hampshire</option>
<option value="NJ"<?php if($state == "NJ") { echo " SELECTED"; } ?>>New Jersey</option>
<option value="NM"<?php if($state == "NM") { echo " SELECTED"; } ?>>New Mexico</option>
<option value="NY"<?php if($state == "NY") { echo " SELECTED"; } ?>>New York</option>
<option value="NF"<?php if($state == "NF") { echo " SELECTED"; } ?>>Newfoundland</option>
<option value="NC"<?php if($state == "NC") { echo " SELECTED"; } ?>>North Carolina</option>
<option value="ND"<?php if($state == "ND") { echo " SELECTED"; } ?>>North Dakota</option>
<option value="NT"<?php if($state == "NT") { echo " SELECTED"; } ?>>Northwest Territories</option>
<option value="NS"<?php if($state == "NS") { echo " SELECTED"; } ?>>Nova Scotia</option>
<option value="OH"<?php if($state == "OH") { echo " SELECTED"; } ?>>Ohio</option>
<option value="OK"<?php if($state == "OK") { echo " SELECTED"; } ?>>Oklahoma</option>
<option value="ON"<?php if($state == "ON") { echo " SELECTED"; } ?>>Ontario</option>
<option value="OR"<?php if($state == "OR") { echo " SELECTED"; } ?>>Oregon</option>
<option value="PA"<?php if($state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</option>
<option value="PE"<?php if($state == "PE") { echo " SELECTED"; } ?>>Prince Edward Island</option>
<option value="QC"<?php if($state == "QC") { echo " SELECTED"; } ?>>Quebec</option>
<option value="RI"<?php if($state == "RI") { echo " SELECTED"; } ?>>Rhode Island</option>
<option value="SK"<?php if($state == "SK") { echo " SELECTED"; } ?>>Saskatchewan</option>
<option value="SC"<?php if($state == "SC") { echo " SELECTED"; } ?>>South Carolina</option>
<option value="SD"<?php if($state == "SD") { echo " SELECTED"; } ?>>South Dakota</option>
<option value="TN"<?php if($state == "TN") { echo " SELECTED"; } ?>>Tennessee</option>
<option value="TX"<?php if($state == "TX") { echo " SELECTED"; } ?>>Texas</option>
<option value="UT"<?php if($state == "UT") { echo " SELECTED"; } ?>>Utah</option>
<option value="VT"<?php if($state == "VT") { echo " SELECTED"; } ?>>Vermont</option>
<option value="VA"<?php if($state == "VA") { echo " SELECTED"; } ?>>Virginia</option>
<option value="WA"<?php if($state == "WA") { echo " SELECTED"; } ?>>Washington</option>
<option value="DC"<?php if($state == "DC") { echo " SELECTED"; } ?>>Washington DC</option>
<option value="WV"<?php if($state == "WV") { echo " SELECTED"; } ?>>West Virginia</option>
<option value="WI"<?php if($state == "WI") { echo " SELECTED"; } ?>>Wisconsin</option>
<option value="WY"<?php if($state == "WY") { echo " SELECTED"; } ?>>Wyoming</option>
<option value="YT"<?php if($state == "YT") { echo " SELECTED"; } ?>>Yukon</option>
</select></td></tr>
<?php } ?>

<?php if($zip_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($zip_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $zip_name; ?>:
<?php if($zip_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<?php } ?>

<?php if($country_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($country_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $country_name; ?>:
<?php if($country_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><select name="country">
<option value="">Select a country</option>
<option value="US"<?php if($country == "US") { echo " SELECTED"; } ?>>United States</option>
<option value="AF"<?php if($country == "AF") { echo " SELECTED"; } ?>>Afghanistan</option>
<option value="AL"<?php if($country == "AL") { echo " SELECTED"; } ?>>Albania</option>
<option value="DZ"<?php if($country == "DZ") { echo " SELECTED"; } ?>>Algeria</option>
<option value="AS"<?php if($country == "AS") { echo " SELECTED"; } ?>>American Samoa</option>
<option value="AD"<?php if($country == "AD") { echo " SELECTED"; } ?>>Andorra</option>
<option value="AO"<?php if($country == "AO") { echo " SELECTED"; } ?>>Angola</option>
<option value="AI"<?php if($country == "AI") { echo " SELECTED"; } ?>>Anguilla</option>
<option value="AQ"<?php if($country == "AQ") { echo " SELECTED"; } ?>>Antarctica</option>
<option value="AG"<?php if($country == "AG") { echo " SELECTED"; } ?>>Antigua and Barbuda</option>
<option value="AR"<?php if($country == "AR") { echo " SELECTED"; } ?>>Argentina</option>
<option value="AM"<?php if($country == "AM") { echo " SELECTED"; } ?>>Armenia</option>
<option value="AW"<?php if($country == "AW") { echo " SELECTED"; } ?>>Aruba</option>
<option value="AU"<?php if($country == "AU") { echo " SELECTED"; } ?>>Australia</option>
<option value="AT"<?php if($country == "AT") { echo " SELECTED"; } ?>>Austria</option>
<option value="AZ"<?php if($country == "AZ") { echo " SELECTED"; } ?>>Azerbaijan</option>
<option value="BS"<?php if($country == "BS") { echo " SELECTED"; } ?>>Bahamas</option>
<option value="BH"<?php if($country == "BH") { echo " SELECTED"; } ?>>Bahrain</option>
<option value="BD"<?php if($country == "BD") { echo " SELECTED"; } ?>>Bangladesh</option>
<option value="BB"<?php if($country == "BB") { echo " SELECTED"; } ?>>Barbados</option>
<option value="BY"<?php if($country == "BY") { echo " SELECTED"; } ?>>Belarus</option>
<option value="BE"<?php if($country == "BE") { echo " SELECTED"; } ?>>Belgium</option>
<option value="BZ"<?php if($country == "BZ") { echo " SELECTED"; } ?>>Belize</option>
<option value="BJ"<?php if($country == "BJ") { echo " SELECTED"; } ?>>Benin</option>
<option value="BM"<?php if($country == "BM") { echo " SELECTED"; } ?>>Bermuda</option>
<option value="BT"<?php if($country == "BT") { echo " SELECTED"; } ?>>Bhutan</option>
<option value="BO"<?php if($country == "BO") { echo " SELECTED"; } ?>>Bolivia</option>
<option value="BA"<?php if($country == "BA") { echo " SELECTED"; } ?>>Bosnia and Herzegovina</option>
<option value="BW"<?php if($country == "BW") { echo " SELECTED"; } ?>>Botswana</option>
<option value="BV"<?php if($country == "BV") { echo " SELECTED"; } ?>>Bouvet Island</option>
<option value="BR"<?php if($country == "BR") { echo " SELECTED"; } ?>>Brazil</option>
<option value="IO"<?php if($country == "IO") { echo " SELECTED"; } ?>>British Indian Ocean Territory</option>
<option value="BN"<?php if($country == "BN") { echo " SELECTED"; } ?>>Brunei Darussalam</option>
<option value="BG"<?php if($country == "BG") { echo " SELECTED"; } ?>>Bulgaria</option>
<option value="BF"<?php if($country == "BF") { echo " SELECTED"; } ?>>Burkina Faso</option>
<option value="BI"<?php if($country == "BI") { echo " SELECTED"; } ?>>Burundi</option>
<option value="KH"<?php if($country == "KH") { echo " SELECTED"; } ?>>Cambodia</option>
<option value="CM"<?php if($country == "CM") { echo " SELECTED"; } ?>>Cameroon</option>
<option value="CA"<?php if($country == "CA") { echo " SELECTED"; } ?>>Canada</option>
<option value="CB"<?php if($country == "CB") { echo " SELECTED"; } ?>>Canary Islands</option>
<option value="CV"<?php if($country == "CV") { echo " SELECTED"; } ?>>Cape Verde</option>
<option value="KY"<?php if($country == "KY") { echo " SELECTED"; } ?>>Cayman Islands</option>
<option value="CF"<?php if($country == "CF") { echo " SELECTED"; } ?>>Central African Republic</option>
<option value="TD"<?php if($country == "TD") { echo " SELECTED"; } ?>>Chad</option>
<option value="CL"<?php if($country == "CL") { echo " SELECTED"; } ?>>Chile</option>
<option value="CN"<?php if($country == "CN") { echo " SELECTED"; } ?>>China</option>
<option value="CX"<?php if($country == "CX") { echo " SELECTED"; } ?>>Christmas Island</option>
<option value="CC"<?php if($country == "CC") { echo " SELECTED"; } ?>>Cocos (Keeling) Islands</option>
<option value="CO"<?php if($country == "CO") { echo " SELECTED"; } ?>>Colombia</option>
<option value="KM"<?php if($country == "KM") { echo " SELECTED"; } ?>>Comoros</option>
<option value="CG"<?php if($country == "CG") { echo " SELECTED"; } ?>>Congo</option>
<option value="CD"<?php if($country == "CD") { echo " SELECTED"; } ?>>Congo, The Democratic Republic of The</option>
<option value="CK"<?php if($country == "CK") { echo " SELECTED"; } ?>>Cook Islands</option>
<option value="CE"<?php if($country == "CE") { echo " SELECTED"; } ?>>Corsica</option>
<option value="CR"<?php if($country == "CR") { echo " SELECTED"; } ?>>Costa Rica</option>
<option value="CI"<?php if($country == "CI") { echo " SELECTED"; } ?>>Cote D'Ivoire</option>
<option value="HR"<?php if($country == "HR") { echo " SELECTED"; } ?>>Croatia</option>
<option value="CU"<?php if($country == "CU") { echo " SELECTED"; } ?>>Cuba</option>
<option value="CY"<?php if($country == "CY") { echo " SELECTED"; } ?>>Cyprus</option>
<option value="CZ"<?php if($country == "CZ") { echo " SELECTED"; } ?>>Czech Republic</option>
<option value="DK"<?php if($country == "DK") { echo " SELECTED"; } ?>>Denmark</option>
<option value="DJ"<?php if($country == "DJ") { echo " SELECTED"; } ?>>Djibouti</option>
<option value="DM"<?php if($country == "DM") { echo " SELECTED"; } ?>>Dominica</option>
<option value="DO"<?php if($country == "DO") { echo " SELECTED"; } ?>>Dominican Republic</option>
<option value="TP"<?php if($country == "TP") { echo " SELECTED"; } ?>>East Timor</option>
<option value="EC"<?php if($country == "EC") { echo " SELECTED"; } ?>>Ecuador</option>
<option value="EG"<?php if($country == "EG") { echo " SELECTED"; } ?>>Egypt</option>
<option value="SV"<?php if($country == "SV") { echo " SELECTED"; } ?>>El Salvador</option>
<option value="GQ"<?php if($country == "GQ") { echo " SELECTED"; } ?>>Equatorial Guinea</option>
<option value="ER"<?php if($country == "ER") { echo " SELECTED"; } ?>>Eritrea</option>
<option value="EE"<?php if($country == "EE") { echo " SELECTED"; } ?>>Estonia</option>
<option value="ET"<?php if($country == "ET") { echo " SELECTED"; } ?>>Ethiopia</option>
<option value="FK"<?php if($country == "FK") { echo " SELECTED"; } ?>>Falkland Islands (Malvinas)</option>
<option value="FO"<?php if($country == "FO") { echo " SELECTED"; } ?>>Faroe Islands</option>
<option value="FJ"<?php if($country == "FJ") { echo " SELECTED"; } ?>>Fiji</option>
<option value="FI"<?php if($country == "FI") { echo " SELECTED"; } ?>>Finland</option>
<option value="CS"<?php if($country == "CS") { echo " SELECTED"; } ?>>Former Czechoslovakia</option>
<option value="SU"<?php if($country == "SU") { echo " SELECTED"; } ?>>Former Ussr</option>
<option value="FR"<?php if($country == "FR") { echo " SELECTED"; } ?>>France</option>
<option value="FX"<?php if($country == "FX") { echo " SELECTED"; } ?>>France (European Territories)</option>
<option value="GF"<?php if($country == "GF") { echo " SELECTED"; } ?>>French Guiana</option>
<option value="PF"<?php if($country == "PF") { echo " SELECTED"; } ?>>French Polynesia</option>
<option value="TF"<?php if($country == "TF") { echo " SELECTED"; } ?>>French Southern Territories</option>
<option value="GA"<?php if($country == "GA") { echo " SELECTED"; } ?>>Gabon</option>
<option value="GM"<?php if($country == "GM") { echo " SELECTED"; } ?>>Gambia</option>
<option value="GE"<?php if($country == "GE") { echo " SELECTED"; } ?>>Georgia</option>
<option value="DE"<?php if($country == "DE") { echo " SELECTED"; } ?>>Germany</option>
<option value="GH"<?php if($country == "GH") { echo " SELECTED"; } ?>>Ghana</option>
<option value="GI"<?php if($country == "GI") { echo " SELECTED"; } ?>>Gibraltar</option>
<option value="GB"<?php if($country == "GB") { echo " SELECTED"; } ?>>Great Britain</option>
<option value="GR"<?php if($country == "GR") { echo " SELECTED"; } ?>>Greece</option>
<option value="GL"<?php if($country == "GL") { echo " SELECTED"; } ?>>Greenland</option>
<option value="GD"<?php if($country == "GD") { echo " SELECTED"; } ?>>Grenada</option>
<option value="GP"<?php if($country == "GP") { echo " SELECTED"; } ?>>Guadeloupe</option>
<option value="GU"<?php if($country == "GU") { echo " SELECTED"; } ?>>Guam</option>
<option value="GT"<?php if($country == "GT") { echo " SELECTED"; } ?>>Guatemala</option>
<option value="GN"<?php if($country == "GN") { echo " SELECTED"; } ?>>Guinea</option>
<option value="GW"<?php if($country == "GW") { echo " SELECTED"; } ?>>Guinea-Bissau</option>
<option value="GY"<?php if($country == "GY") { echo " SELECTED"; } ?>>Guyana</option>
<option value="HT"<?php if($country == "HT") { echo " SELECTED"; } ?>>Haiti</option>
<option value="HM"<?php if($country == "HM") { echo " SELECTED"; } ?>>Heard Island and Mcdonald Islands</option>
<option value="VA"<?php if($country == "VA") { echo " SELECTED"; } ?>>Holy See (Vatican City State)</option>
<option value="HN"<?php if($country == "HN") { echo " SELECTED"; } ?>>Honduras</option>
<option value="HK"<?php if($country == "HK") { echo " SELECTED"; } ?>>Hong Kong</option>
<option value="HU"<?php if($country == "HU") { echo " SELECTED"; } ?>>Hungary</option>
<option value="IS"<?php if($country == "IS") { echo " SELECTED"; } ?>>Iceland</option>
<option value="IN"<?php if($country == "IN") { echo " SELECTED"; } ?>>India</option>
<option value="ID"<?php if($country == "ID") { echo " SELECTED"; } ?>>Indonesia</option>
<option value="IR"<?php if($country == "IR") { echo " SELECTED"; } ?>>Iran, Islamic Republic of</option>
<option value="IQ"<?php if($country == "IQ") { echo " SELECTED"; } ?>>Iraq</option>
<option value="IE"<?php if($country == "IE") { echo " SELECTED"; } ?>>Ireland</option>
<option value="IL"<?php if($country == "IL") { echo " SELECTED"; } ?>>Israel</option>
<option value="IT">Italy</option>
<option value="JM">Jamaica</option>
<option value="JP">Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KO">Korea</option>
<option value="KW">Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Lao People's Democratic Republic</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libyan Arab Jamahiriya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MO">Macau</option>
<option value="MK">Macedonia, The Former Yugoslav Republic of</option>
<option value="MG">Madagascar</option>
<option value="MI">Madeira Islands</option>
<option value="MW">Malawi</option>
<option value="MY">Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ">Martinique</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="YT">Mayotte</option>
<option value="MX">Mexico</option>
<option value="FM">Micronesia, Federated States of</option>
<option value="MD">Moldova, Republic of</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP">Nepal</option>
<option value="NL">Netherlands</option>
<option value="AN">Netherlands Antilles</option>
<option value="NT">Neutral Zone</option>
<option value="NC">New Caledonia</option>
<option value="NZ">New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NU">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="KP">North Korea</option>
<option value="MP">Northern Mariana Islands</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PN">Pitcairn</option>
<option value="PL">Poland</option>
<option value="PT">Portugal</option>
<option value="PR">Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="RE">Reunion (French)</option>
<option value="RO">Romania</option>
<option value="RU">Russian Federation</option>
<option value="RW">Rwanda</option>
<option value="SH">Saint Helena</option>
<option value="KN">Saint Kitts and Nevis</option>
<option value="LC">Saint Lucia</option>
<option value="PM">Saint Pierre and Miquelon</option>
<option value="VC">Saint Vincent and The Grenadines</option>
<option value="SQ">Saipan</option>
<option value="WS">Samoa</option>
<option value="SM">San Marino</option>
<option value="ST">Sao Tome and Principe</option>
<option value="SA">Saudi Arabia</option>
<option value="SF">Scotland</option>
<option value="SN">Senegal</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovakia</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="SO">Somalia</option>
<option value="ZA">South Africa</option>
<option value="GS">South Georgia and The South Sandwich Islands</option>
<option value="KR">South Korea</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SJ">Svalbard and Jan Mayen</option>
<option value="SZ">Swaziland</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syrian Arab Republic</option>
<option value="TW">Taiwan, Province of China</option>
<option value="TJ">Tajikistan</option>
<option value="TZ">Tanzania, United Republic of</option>
<option value="TH">Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad and Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TC">Turks and Caicos Islands</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="UK">United Kingdom</option>
<option value="US">United States</option>
<option value="UM">United States Minor Outlying Islands</option>
<option value="UY">Uruguay</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VE">Venezuela</option>
<option value="VN">Viet Nam</option>
<option value="VG">Virgin Islands, British</option>
<option value="VI">Virgin Islands, U.S.</option>
<option value="WF">Wallis and Futuna</option>
<option value="EH">Western Sahara</option>
<option value="YE">Yemen</option>
<option value="YU">Yugoslavia</option>
<option value="ZR">Zaire</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
</select></td></tr>
<?php } ?>

<?php if($phone_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($phone_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $phone_name; ?>:
<?php if($phone_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>"></td></tr>
<?php } ?>

<?php if($fax_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($fax_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $fax_name; ?>:
<?php if($fax_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="fax" size="30" maxlength="30" value="<?php echo $fax; ?>"></td></tr>
<?php } ?>

<?php if($product_submit == "1") { ?>
<tr><td align="right" NOWRAP class="style2">
<?php if($product_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $product_name; ?>:
<?php if($product_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><select name="product" value="<?php echo $product; ?>">
<option value=""<? if($product == "") { echo " SELECTED"; } ?>>Select a Product</option>
<option value="1"<? if($product == "1") { echo " SELECTED"; } ?>>SalviaZone Green</option>
<option value="2"<? if($product == "2") { echo " SELECTED"; } ?>>SalviaZone Yellow</option>
<option value="3"<? if($product == "3") { echo " SELECTED"; } ?>>SalviaZone Red</option>
<option value="4"<? if($product == "4") { echo " SELECTED"; } ?>>SalviaZone Purple</option>
<option value="5"<? if($product == "5") { echo " SELECTED"; } ?>>All</option>
</select></td></tr>
<?php } ?>

<tr><td colspan="2" align="left" class="style2">Release Form:<br>
<textarea cols="55" rows="4">I hereby grant to The Upstate Dream Institute ("UDI"), its successors and assigns, for good and valuable consideration, the absolute and irrevocable right and license with respect to my name and the testimonial made by me (the "Submitted Information") and submitted to www.dreamboost.com: (a) To use, publish and distribute my name and the Submitted Information, or any part thereof, or as amended or modified by UDI, in any and all media now known or hereafter developed, published by or under UDI authority, in whole or in part, individually or in conjunction with other testimonials and for any purpose whatsoever, including without limitation illustration, promotion, advertising and publicity. I acknowledge that UDI may use the Submitted Information in accordance with the permission granted herein without any payment to the undersigned or any third party. I represent and warrant that I am the sole proprietor of all rights in and to the Submitted Information, that I have full power and authority to grant the rights granted to UDI herein; that, if applicable, I have obtained all rights, consents and permissions necessary to grant the rights granted herein; that the use of the Submitted Information by UDI as set forth above shall not violate or infringe upon the rights of any individual or entity; and that the Submitted Information is original.  I consent to any interview(s) requested by UDI and promise to speak truthfully to the representatives of The Upstate Dream Institute, consent to the utilization by The Upstate Dream Institute and its affiliates of all or any part of the information obtained through such interviews, as set forth above, agree that an interview featuring me, or any other work similar to that covered by this agreement, has not been previously published in any form or media and will not be published elsewhere in any form or media before my information is published by The Upstate Dream Institute I release, discharge and hold harmless The Upstate Dream Institute (and those acting under its permission or authority) from any and all claims, demands or liabilities arising out of or in connection with the use, production or reproduction of the testimonial and including without limitation any claims for invasion of privacy or publicity. I also acknowledge that The Upstate Dream Institute has not promised, and that I have not requested, any compensation, and that no compensation will be paid, for my participation.</textarea></td></tr>
<tr><td colspan="2" align="left" class="style2"><font color="red">*</font> <input type="checkbox" name="agree" value="1"<?php if($agree != "") { echo " CHECKED"; } ?>> I Agree <font size="1">(must check to proceed)</font></td></tr>
<tr><td colspan="2" align="center" class="style2"><input type="checkbox" name="newsletter" value="1" CHECKED> Yes, please subscribe me to the <?php echo $website_title; ?> Newsletter.</td></tr>
<tr><td colspan="2" align="center"><input type="image" src="/images/button_submit_testimonial.gif" id="button_submit_testimonial" name="button_submit_testimonial" alt="Submit Your Testimonial" onmouseover="MM_swapImage('button_submit_testimonial','','/images/button_submit_testimonial_over.gif',1)" onmouseout="MM_swapImgRestore()"></td></tr>
</form>
</table>
</font></td></tr>

<SCRIPT LANGUAGE="JavaScript">
function txtarea1() {
	document.testimonial.testimonial = "";
}
</SCRIPT>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>