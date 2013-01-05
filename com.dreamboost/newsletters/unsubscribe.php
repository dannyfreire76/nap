<?php
// BME WMS
// Page: Newsletters Unsubscribe page
// Path/File: /newsletters/unsubscribe.php
// Version: 1.8
// Build: 1803
// Date: 04-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
//$line_hgt = 700;

$submit = $_POST['submit'];
$email = $_POST['email'];

if($submit != "") {
	//Validate
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
		$error_txt .= "You did not enter your email address correctly. Please try again.<br>\n";
	}
	
	if($error_txt == "") {
		//Check DB
		$query = "SELECT email FROM news_member WHERE email='$email' AND status != '2'";
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
<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">
	<h2><?php echo $website_title; ?> Newsletter Unsubscribe</h2>

	<h3>Unsubscribe from the <?php echo $website_title; ?> E-Mail Newsletter.</h3>
		
	<?php
//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\" class=\"style2 error\">$error_txt</td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<table border="0"><tr><td align="left">
	<div class="window_top">
		  <div class="window_top_content">
			Unsubscribe 
		  </div>

		  <div class="window_content">
			<FORM name="newsletter" Method="POST" ACTION="./unsubscribe.php" class="wmsform">
			<input type="hidden" name="submit" value="1">
			<fieldset>
				<ol>
					<li>
						<label for="email">E-Mail</label>
						<INPUT type="text" id="email" name="email" size="30" maxlength="100" value="<?php echo $email; ?>" tabindex="2" />
					</li>
					<li class="fm-button-none">
						<input type="image" src="/images/button_unsubscribe.gif" id="button_unsubscribe" name="button_unsubscribe" alt="Unsubscribe">
					</li>
				</ol>
			</fieldset>
			</form>
		  </div>
		<div class="window_bottom"><div class="window_bottom_end"></div></div>
	</div>
</td></tr>
</table>

</div>
<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
