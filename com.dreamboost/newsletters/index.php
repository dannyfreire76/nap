<?php
// BME WMS
// Page: Newsletters Homepage
// Path/File: /newsletters/index.php
// Version: 1.1
// Build: 1115
// Date: 09-26-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$subscribe_news = $_POST["subscribe_news"];
$name = $_POST["name"];
$email = $_POST["email"];

if($subscribe_news != "") {
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
		
		if($tmp_email == $email) {
			$error_txt .= "You are already subscribed to this newsletter. You do not need to subscribe again<br>\n";
		}
	}

	if($error_txt == "") {
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
		
		//Send to Thanks Page
		header("Location: " . $base_url . "newsletters/thanks.php");
		exit;
		
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Newsletter</title>

<?php include '../includes/meta1.php'; ?>
</head>

<body>
<?php include '../includes/head1.php'; ?>

<div class="boxContent" style="width:90%; margin:auto;">
	<h2><?php echo $website_title; ?>: Newsletters</h2>

	<h3>Sign up today for our newsletter.</h3>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td align=\"left\"><font face=\"$font\" size=\"+1\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<FORM name="newsletter" Method="POST" ACTION="./index.php">
	<table border="0" cellpadding="3">
	<tr>
		<td align="right">Your Name: </td>
		<td align="left"><INPUT type="Text" name="name" size="30" maxlength="100" value=""></td>
	</tr>
	<tr>
		<td align="right">Your Email Address: </td>
		<td align="left"><input type="Text" name="email" size="30" maxlength="100" value=""></td>
		</tr>
	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><input type="Submit" name="subscribe_news" value="Subscribe"></td>
	</tr>	
	</table>
</form>
<p>Please note: Our Newsletter System will send a confirmation email to your email address to verify you want to subscribe to our newsletter and no one else is subscribing you to our newsletter. When reading the message, please simply click the reply button and then the send button in your email program. Also note, in some cases, SPAM filtering software will catch the confirmation email. You will need to check for it in your Junk mailbox. </p>
<h3><a href="./unsubscribe.php">Click here to unsubscribe</a> from our newsletter.</h3>
</div>
<br clear="all">
<br>
<?php include '../includes/foot1.php'; ?>
</body>
</html>
