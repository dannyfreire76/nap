<?php
// BME WMS
// Page: Wholesale Catalog Forget Username and Password page
// Path/File: /wc/forget_uandp.php
// Version: 1.1
// Build: 1101
// Date: 10-31-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$submit = $_POST["submit"];
$email = $_POST["email"];

function send_email_login($email, $contact_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with password
		$email_str = "Dear ";
		$email_str .= $contact_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your " . $website_title . " Wholesale Catalog account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "wc/\n\n";
						
		$subject = $website_title . " Wholesale Catalog Password";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

if($submit != "") {
	//Check for Errors
	$error_txt = "";
	if($email == "") { $error_txt .= "Error, you did not enter an email address.<br>\n"; }
	
	//If no Errors, Verify against DB
	if($error_txt == "") {
		$query = "SELECT email, username, password, contact_name FROM retailer WHERE email='$email'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$email = $line["email"];
			$username = $line["username"];
			$password = $line["password"];
			$contact_name = $line["contact_name"];
		}
		mysql_free_result($result);
	}
	if($username != "") {
		send_email_login($email, $contact_name, $username, $password);
		
		//Goto Index
		header("Location: " . $base_url . "wc/index.php");
		exit;
	} else {
		$error_txt .= "Error, the email address you entered is incorrect. Please check your information and try retrieving your information again.<br>\n";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog Forget Pasword</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">
</head>
<body bgcolor="#<?php echo $bgcolor; ?>" OnLoad="document.forget_uandp.email.focus();">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Wholesale Catalog Forget Password</font></td></tr>
<?php
//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td><font face=\"Arial\" size=\"+1\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="center" valign="top"><table border="0">
<tr><td align="left" valign="top">
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Please enter your email address to have your username and password emailed to you:</font><br>
<table border="0">
<form name="forget_uandp" action="./forget_uandp.php" method="POST">
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Email Address:</font></td><td><input type="text" name="email" size="20" maxlength="255"></td></tr>
<tr><td align="center" colspan="2"><input type="submit" name="submit" value="Retrieve My Information"></td></tr>
</form>
</table>
</td><td>&nbsp; &nbsp;</td><td align="left" valign="top" nowrap><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</font></td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>

</table>

<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>