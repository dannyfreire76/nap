<?php
// BME WMS
// Page: Testimonials Submit page
// Path/File: /testimonials/testimonial_submit.php
// Version: 1.8
// Build: 1802
// Date: 01-31-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/st_and_co1.php';

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
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent">
	<h2>Send Us Your Dream Boost Testimonial</h2>
	<p>Enter your Dream Boost Testimonial in the form below to send it to us. If we choose yours from the great ones we receive every day we'll post it on our website to share with the world.</p>

<?php
if($error_txt) { 
	echo "<p class=\"error\">$error_txt</p>\n";
}
?>

<div class="window_top" id="test_submit">
	  <div class="window_top_content">
		Required fields marked <em>*</em> 
	  </div>

	  <div class="window_content">

<FORM name="testimonial" Method="POST" ACTION="./testimonial_submit.php">
<input type="hidden" name="submit" value="1">
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr><td align="left"><font color="red">*</font> Please Enter Your Information</td></tr>
<?php if($testimonial_submit == "1") { ?>
<tr><td colspan="2" VALIGN="TOP"><?php if($testimonial != "") { echo "<textarea name=\"testimonial\" cols=\"55\" rows=\"6\">";
	echo $testimonial; } 
else {  echo "<textarea name=\"testimonial\" cols=\"55\" rows=\"6\" onClick=\"document.testimonial.testimonial.value=''\">";
	echo "Type Your $testimonial_name Here"; } ?>
	</textarea></td></tr>
<?php } ?>

<?php if($name_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($name_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $name_name; ?>:
<?php if($name_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="name" size="30" maxlength="255" value="<?php echo $name; ?>"></td></tr>
<?php } ?>

<?php if($email_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($email_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $email_name; ?>:
<?php if($email_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="email" size="30" maxlength="255" value="<?php echo $email; ?>"></td></tr>
<?php } ?>

<?php if($address1_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($address1_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $address1_name; ?>:
<?php if($address1_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="address1" size="30" maxlength="100" value="<?php echo $address1; ?>"></td></tr>
<?php } ?>

<?php if($address2_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($address2_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $address2_name; ?>:
<?php if($address2_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="address2" size="30" maxlength="100" value="<?php echo $address2; ?>"></td></tr>
<?php } ?>

<?php if($city_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($city_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $city_name; ?>:
<?php if($city_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="city" size="30" maxlength="100" value="<?php echo $city; ?>"></td></tr>
<?php } ?>

<?php if($state_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($state_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $state_name; ?>:
<?php if($state_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><select name="state">
				<?php
				state_build_all($state);
				?>
</select></td></tr>
<?php } ?>

<?php if($zip_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($zip_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $zip_name; ?>:
<?php if($zip_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<?php } ?>

<?php if($country_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($country_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $country_name; ?>:
<?php if($country_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><select name="country">
				<?php
				country_build_all($country);
				?>
</select></td></tr>
<?php } ?>

<?php if($phone_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($phone_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $phone_name; ?>:
<?php if($phone_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>"></td></tr>
<?php } ?>

<?php if($fax_submit == "1") { ?>
<tr><td align="right" NOWRAP>
<?php if($fax_reqd_submit == "1") { echo "<font color=\"red\">*</font> "; } ?>
<?php echo $fax_name; ?>:
<?php if($fax_displayed == "0") { echo "<br><font size=\"1\">Note: Not displayed</font>"; } ?>
</td><td><INPUT type="Text" name="fax" size="30" maxlength="30" value="<?php echo $fax; ?>"></td></tr>
<?php } ?>

<?php if($product_submit == "1") { ?>
<tr><td align="right" NOWRAP>
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

<tr><td colspan="2" align="left">Release Form:<br>
<textarea cols="55" rows="4">I hereby grant to The Upstate Dream Institute ("UDI"), its successors and assigns, for good and valuable consideration, the absolute and irrevocable right and license with respect to my name and the testimonial made by me (the "Submitted Information") and submitted to www.dreamboost.com: (a) To use, publish and distribute my name and the Submitted Information, or any part thereof, or as amended or modified by UDI, in any and all media now known or hereafter developed, published by or under UDI authority, in whole or in part, individually or in conjunction with other testimonials and for any purpose whatsoever, including without limitation illustration, promotion, advertising and publicity. I acknowledge that UDI may use the Submitted Information in accordance with the permission granted herein without any payment to the undersigned or any third party. I represent and warrant that I am the sole proprietor of all rights in and to the Submitted Information, that I have full power and authority to grant the rights granted to UDI herein; that, if applicable, I have obtained all rights, consents and permissions necessary to grant the rights granted herein; that the use of the Submitted Information by UDI as set forth above shall not violate or infringe upon the rights of any individual or entity; and that the Submitted Information is original.  I consent to any interview(s) requested by UDI and promise to speak truthfully to the representatives of The Upstate Dream Institute, consent to the utilization by The Upstate Dream Institute and its affiliates of all or any part of the information obtained through such interviews, as set forth above, agree that an interview featuring me, or any other work similar to that covered by this agreement, has not been previously published in any form or media and will not be published elsewhere in any form or media before my information is published by The Upstate Dream Institute I release, discharge and hold harmless The Upstate Dream Institute (and those acting under its permission or authority) from any and all claims, demands or liabilities arising out of or in connection with the use, production or reproduction of the testimonial and including without limitation any claims for invasion of privacy or publicity. I also acknowledge that The Upstate Dream Institute has not promised, and that I have not requested, any compensation, and that no compensation will be paid, for my participation.</textarea></td></tr>
<tr><td colspan="2" align="left"><font color="red">*</font> <input type="checkbox" name="agree" value="1"<?php if($agree != "") { echo " CHECKED"; } ?>> I Agree <font size="1">(must check to proceed)</font></td></tr>
<tr><td colspan="2" align="center"><input type="checkbox" name="newsletter" value="1" CHECKED> Yes, please subscribe me to the <?php echo $website_title; ?> Newsletter.</td></tr>
<tr><td colspan="2" align="center"><input type="image" src="/images/button_submit_testimonial.gif" id="button_submit_testimonial" name="button_submit_testimonial" alt="Submit Your Testimonial">
</td></tr>
</table>
</form>

<SCRIPT LANGUAGE="JavaScript">
function txtarea1() {
	document.testimonial.testimonial = "";
}
</SCRIPT>


	  </div>
	<div class="window_bottom"><div class="window_bottom_end">&#160;</div></div>
</div>

</div>
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
