<?php
// BME WMS
// Page: Newsletters Unsubscribe page
// Path/File: /newsletters/unsubscribe.php
// Version: 1.8
// Build: 1802
// Date: 01-29-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 700;

if($submit != "") {
	//Validate
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "You did not enter your email address correctly. Please try again.<br>\n";
	}
	
	if($error_txt == "") {
		//Check DB
		$query = "SELECT email FROM news_member WHERE email='$email'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$tmp_email = $line["email"];
		}
		mysql_free_result($result);
		
		if($tmp_email != $email) {
			$error_txt .= "You are not subscribed to this newsletter. You do not need to unsubscribe.<br>\n";
		}
	}

	if($error_txt == "") {
		//Write to DBs
		$query = "UPDATE news_member SET status='2' WHERE email='$email'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$query = "SELECT member_id FROM news_member WHERE email='$email'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$tmp_member_id = $line["member_id"];
		}
		mysql_free_result($result);

		$query = "DELETE FROM news_subscriptions WHERE member_id='$tmp_member_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Send Confirmation Email
		$query = "SELECT content, subject, email FROM news_email WHERE email_id='2'";
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

		$email_subj = $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
		
		//Send to Thanks Page
		header("Location: " . $base_url . "newsletters/unsub_thanks.php");
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Unsubscribe from the Dream Boost E-Mail Newsletter | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css" />
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/button_unsubscribe_over.gif','/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Newsletter Unsubscribe</td></tr>

<tr><td align="left" class="style2">Unsubscribe from the <?php echo $website_title; ?> E-Mail Newsletter.</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style2\"><font color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="center">
<table border="0"><tr><td align="left">
	<FORM name="newsletter" Method="POST" ACTION="./unsubscribe.php" class="wmsform">
	<input type="hidden" name="submit" value="1">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Your Information</legend>
		<ol>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="100" value="<?php echo $email; ?>" tabindex="2" />
			</li>
			<li class="fm-button">
				<input type="image" src="/images/button_unsubscribe.gif" id="button_unsubscribe" name="button_unsubscribe" alt="Unsubscribe" onmouseover="MM_swapImage('button_unsubscribe','','/images/button_unsubscribe_over.gif',1)" onmouseout="MM_swapImgRestore()">
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