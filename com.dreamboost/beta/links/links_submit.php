<?php
// BME WMS
// Page: LynkStation Link Submit page
// Path/File: /links/links_submit.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$query = "SELECT email, approval_reqd, notify_owner, website_dups, reciprocal_dups, title, url, description FROM lynkstation_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$lsowner_email = $line["email"];
	$approval_reqd = $line["approval_reqd"];
	$notify_owner = $line["notify_owner"];
	if($approval_reqd == "1") { $appv_tmp = "0"; }
	if($approval_reqd == "0") { $appv_tmp = "1"; }
	$website_dups = $line["website_dups"];
	$reciprocal_dups = $line["reciprocal_dups"];
	$my_title = $line["title"];
	$my_url = $line["url"];
	$my_description = $line["description"];
}
mysql_free_result($result);

if($submit != "") {
	$error_txt = "";
	//Validation
	if($title == "") {
		$error_txt .= "Error, you did not enter a title. Please enter a title for your website.<br>\n";
	}
	$title2 = strtolower($title);
	// bad title filter
	if($title2 == "viagra" || $title2 == "buy viagra" || $title2 == "cialis" || $title2 == "buy cialis" || $title2 == "phentermine" || $title2 == "buy phentermine" || $title2 == "levitra" || $title2 == "buy levitra" || $title2 == "ativan" || $title2 == "buy ativan" || $title2 == "bontril" || $title2 == "buy bontril") {
		$error_txt .= "Error, the content of your title has been deemed inappropriate by our filter. Please enter a title for your website.<br>\n";
	}
	if($website_url == "" || $website_url == "http://") {
		$error_txt .= "Error, you did not enter a Website URL. Please enter your Website's URL starting with http://.<br>\n";
	}
	if($website_dups == '0' && $website_url != "") {
		$website_url3 = rtrim($website_url, "/");
		$website_url4 = rtrim($website_url, "/index.html");
		$website_url5 = rtrim($website_url, "/index.php");
		$website_url6 = rtrim($website_url, "/index.asp");
		$website_url7 = rtrim($website_url, "/index.jsp");
		$query2 = "SELECT approved, website_url, category FROM lynkstation_links WHERE website_url LIKE '$website_url%' OR website_url='$website_url3' OR website_url='$website_url4' OR website_url='$website_url5' OR website_url='$website_url6' OR website_url='$website_url7'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$approved = $line2["approved"];
			$website_url2 = $line2["website_url"];
			$category2 = $line2["category"];
		}
		mysql_free_result($result2);
		if(isset($website_url2) && $website_url2 != "") {
			$error_txt .= "Error, the Website URL you are submitting has already been submitted to this LynkStation and can not be submitted more than once. ";
			if(isset($approved) && $approved == '0') { $error_txt .= "The Website is in the Approval process and the LynkStation Owner will be processing it shortly.<br>\n"; }
			if(isset($approved) && $approved == '1') { $error_txt .= "The Website has been Approved and is visible on the {$category2} page.<br>\n"; }
			if(isset($approved) && $approved == '2') { $error_txt .= "The Website has been Rejected and will not be listed on this LynkStation.<br>\n"; }
			if(isset($approved) && $approved == '3') { $error_txt .= "The Website is not active or has some other form of a Bad Link.<br>\n"; }
			if(isset($approved) && $approved == '4') { $error_txt .= "The Website is not listed because no Reciprocal Link was provided.<br>\n"; }
			if(isset($approved) && $approved == '5') { $error_txt .= "The Website is not listed because the Reciprocal Link provided is Invalid.<br>\n"; }
		}
	}
	if($description == "") {
		$error_txt .= "Error, you did not enter a description. Please enter a description of your website.<br>\n";
	}
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "Error, you did not enter your email address or it is incorrect. We need your email address to let you know when your website link is posted.<br>\n";
	}
	if($reciprical_link == "" || $reciprical_link == " " || $reciprical_link == "http://") {
		$error_txt .= "Error, you did not enter a Reciprocal Link. Please enter the location of where the link to our website is located on your website - it is required.<br>\n";
	}
	if($reciprocal_dups == '0' && $reciprical_link != "") {
		$reciprical_link3 = rtrim($reciprical_link, "/");
		$reciprical_link4 = rtrim($reciprical_link, "/index.html");
		$reciprical_link5 = rtrim($reciprical_link, "/index.php");
		$reciprical_link6 = rtrim($reciprical_link, "/index.asp");
		$reciprical_link7 = rtrim($reciprical_link, "/index.jsp");
		$query3 = "SELECT reciprical_link FROM lynkstation_links WHERE reciprical_link LIKE '$reciprical_link%' OR reciprical_link='$reciprical_link3' OR reciprical_link='$reciprical_link4' OR reciprical_link='$reciprical_link5' OR reciprical_link='$reciprical_link6' OR reciprical_link='$reciprical_link7'";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
			$reciprical_link2 = $line3["reciprical_link"];
		}
		mysql_free_result($result3);
		if(isset($reciprical_link2) && $reciprical_link2 != "") {
			$error_txt .= "Error, the Reciprocal Link you are submitting has already been submitted to this LynkStation and can not be submitted more than once.<br>\n";
		}
	}

	//Check for errors
	if($error_txt == "") {
		//Write to DB

		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO lynkstation_links SET created='$now', approved='$appv_tmp', title='$title', website_url='$website_url', description='$description', email='$email', category='$category', reciprical_link='$reciprical_link'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Send Email
		$query = "SELECT content, subject, email FROM lynkstation_emails WHERE lsemails_id='1'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$content = $line["content"];
			$subject = $line["subject"];
			$email_tmp = $line["email"];
		}
		mysql_free_result($result);

		$email_str = "Dear " . $title . ",\n\n";
		$email_str .= $content;
		$email_str .= "\n\n";
		
		$email_subj = $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);

		if($notify_owner == "1") {
			$query = "SELECT content, subject, email FROM lynkstation_emails WHERE lsemails_id='4'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$content = $line["content"];
				$subject = $line["subject"];
				$email_tmp = $line["email"];
			}
			mysql_free_result($result);

			$email_str = "Dear LynkStation Owner,\n\n";
			$email_str .= $content;
			$email_str .= "\n\n";
		
			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($lsowner_email, $email_subj, $email_str, $email_from);
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
		header("Location: " . $base_url . "links/thanks.php");
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Website Link Submit</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Send Us Your Website Link - Trade Links with Us</td></tr>

<tr><td align="left" class="style2">To place your link on our website we ask that you place our link on your website. Simply use the information below to add a link to our website on your website before filling out this form. Then place the URL of the page where the link to our website is, in the Reciprocal Link field in the form below when submiting your website. Our website will automatically check that our link is in place and keep checking to ensure the link does not get removed. If it is removed, our website will automatically remove your link from our website. Please note we do not want this to happen, we would much rather have all the links stay active for as along as possible.</td></tr>

<tr><td>&nbsp;</td></tr>

<?php
	echo "<tr><td align=\"left\"><table border=\"0\">";
	echo "<tr><td align=\"left\" class=\"style3\">Title:</td><td align=\"left\" class=\"style2\">";
	echo $my_title;
	echo "</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style3\">URL:</td><td align=\"left\" class=\"style2\">";
	echo $my_url;
	echo "</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style3\">Description:</td><td align=\"left\" class=\"style2\">";
	echo $my_description;
	echo "</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style3\">Category:</td><td align=\"left\" class=\"style2\">";
	echo "Health</td></tr>\n";
	echo "</table></td></tr>\n";
	
	echo "<tr><td>&nbsp;</td></tr>\n";
	
	echo "<tr><td align=\"left\" class=\"style2\">Or you can simply copy the HTML code from the box ";
	echo "below:</td></tr>\n";

	echo "<tr><form><td><TEXTAREA name=\"code\" rows=\"5\" cols=\"50\"><a href=\"";
	echo $my_url;
	echo "\" TARGET=\"_BLANK\">";
	echo $my_title;
	echo "</a> - ";
	echo $my_description;
	echo "<br></TEXTAREA></td></form></tr>\n";
?>

<tr><td>&nbsp;</td></tr>

<?php
if($error_txt) { 
	echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<FORM name="link" Method="POST" ACTION="./links_submit.php">
<tr><td align="left"><table border="0">
<tr><td align="right" class="style2">Title:</td><td><INPUT type="text" name="title" size="30" maxlength="150" value="<? echo $title; ?>"></td></tr>
<?php
if($website_url == "") { $website_url = "http://"; }
?>
<tr><td align="right" class="style2">Website URL:</td><td><INPUT type="text" name="website_url" size="30" maxlength="150" value="<? echo $website_url; ?>"></td></tr>
<tr><td align="right" class="style2">Description:</td><td><INPUT type="text" name="description" size="30" maxlength="255" value="<? echo $description; ?>"></td></tr>
<tr><td align="right" class="style2">E-Mail Address:</td><td class="style2"><INPUT type="text" name="email" size="30" maxlength="100" value="<?php echo $email; ?>"> Note: Not displayed on website</td></tr>
<tr><td align="right" class="style2">Category:</td><td><select name="category">
<?php
$query = "SELECT name FROM lynkstation_cats WHERE name!='' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<option value=\"";
	echo $line["name"];
	echo "\"";
	if($category == $line["name"]) { echo " SELECTED"; }
	echo ">";
	echo $line["name"];
	echo "</option>\n";
}
mysql_free_result($result);
?>
</select></td></tr>
<?php
if($reciprical_link == "") { $reciprical_link = "http://"; }
?>
<tr><td align="right" class="style2">Reciprocal Link:</td><td class="style2"><INPUT type="text" name="reciprical_link" size="30" maxlength="150" value="<?php echo $reciprical_link; ?>"> Note: Not displayed on website</td></tr>
<tr><td colspan="2" align="center" class="style2"><input type="checkbox" name="newsletter" value="1" CHECKED> Yes, please subscribe me to the <?php echo $website_title; ?> Newsletter.</td></tr>

<tr><td colspan="2" align="center"><INPUT type="Submit" name="submit" value="Submit Your Website"></td></tr>
</table></td></tr>
</form>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>