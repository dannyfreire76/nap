<?php
// BME WMS
// Page: Wholesale Catalog Forget Username and Password page
// Path/File: /wc/forget_uandp.php
// Version: 1.1
// Build: 1101
// Date: 10-31-2006

include 'includes/main1.php';
include './admin/includes/password/class.password.php';

$full_view = ($_REQUEST['view']=='full') ? true : false;
$submit = $_POST["submit"];
$email = $_POST["email"];

function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your " . $website_title . " account listed below. We recommend ";
		$email_str .= "logging in and changing your password at your earliest convenience.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url;
						
		$subject = "New  " . $website_title . " Login Info";
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
		$queryPW = "SELECT username, first_name, last_name FROM members WHERE email='".$email."'";
		$resultPW = mysql_query($queryPW) or die("Query failed : " . mysql_error());
        if (mysql_num_rows($resultPW)>0) {
            while ($linePW = mysql_fetch_array($resultPW, MYSQL_ASSOC)) {
                $username = $linePW["username"];
                $first_name = $linePW["first_name"];
                $last_name = $linePW["last_name"];

                // Generate New Password
                $pas = new password();
                $newpass = $pas->generate();
                $query = "UPDATE members SET password='".md5($newpass)."'";
                $query .= "WHERE email='$email'";
                $result = mysql_query($query) or die("Query failed : " . mysql_error());

                send_email_login($email, $first_name, $last_name, $username, $newpass);
            }
        }
        else {
    		$error_txt .= "Login information not found.";
        }

        if ( !$full_view ) {
            $msg = ( $error_txt ? $error_txt : 'ok' );
            echo $msg;
            exit;
        }   
    
    }
}
if ( $full_view ) {
header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Forgot Password</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" href="/includes/site_styles.css" />
</head>
<body bgcolor="#<?php echo $bgcolor; ?>" OnLoad="document.forget_uandp.email.focus();">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">Forgot Password</font></td></tr>
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
<?php
}//END if ( $full_view )	
?>
<form id="c_forget_uandp" action="<?php echo $current_base.'c_forget_uandp.php'; ?>" method="POST">
    <table border="0" width="100%">
		<tr>
			<td align="right">Email:</td>
			<td><input type="text" id="email" size="20" maxlength="255" /></td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<input type="submit" id="submit" name="submit" value="Retrieve Info" />
			</td>
		</tr>
    </table>
    <div class="loading text_center">&#160;</div>
    <ul class="text_left">
        <li class="disc">
            <a href="javascript:void(0)" link="<?=$base_url?>wc/retailer_login.php" id="login_link">Login Now</a>
        </li>
    </ul>
</form>
<?php
if ( $full_view ) {
?>
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
<?php
}
?>
