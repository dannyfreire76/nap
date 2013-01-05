<?php
// BME WMS
// Page: Wholesale Catalog Login page
// Path/File: /wc/index.php
// Version: 1.1
// Build: 1103
// Date: 12-13-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$submit = $_POST["submit"];
$username = $_POST["username"];
$password = $_POST["password"];

$root_url = substr($base_url, 10, -1);

$retailer_id = $_COOKIE["wc_user"];
if($retailer_id != "") {
	$result = setcookie("wc_user", $retailer_id, time()-3600, "/wc/", $root_url, 0) or die ("Set Cookie failed : " . mysql_error());
}

if($submit != "") {
	//Check for Errors
	$error_txt = "";
	if($username == "") { $error_txt .= "Error, you did not enter a username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a password.<br>\n"; }
	
	//If no Errors, Verify against DB
	if($error_txt == "") {
		$query = "SELECT retailer_id, retailer_status, logins FROM retailer WHERE username='$username' AND password='$password'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	   		$retailer_id = $line["retailer_id"];
	   		$retailer_status = $line["retailer_status"];
	   		$logins = $line["logins"];
		}
		mysql_free_result($result);
	}
	if($retailer_id != "") {
		$result = setcookie("wc_user", $retailer_id, time()+60*60*24*30, "/wc/", $root_url, 0) or die ("Set Cookie failed : " . mysql_error());
		$result = setcookie("wc_status", $retailer_status, time()+60*60*24*30, "/wc/", $root_url, 0) or die ("Set Cookie failed : " . mysql_error());
		
		//Track login
		$now = date("Y-m-d H:i:s");
		$logins = $logins + 1;
		$query = "UPDATE retailer SET last_login='$now', logins='$logins' WHERE retailer_id='$retailer_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		
		//Goto Index2
		header("Location: " . $base_url . "wc/index2.php");
		exit;
	} else {
		$error_txt .= "Error, the username and password you entered are incorrect. Please check your information and try logging in again.<br>\n";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Wholesale Catalog Login</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" href="/includes/site_styles.css">
</head>
<body bgcolor="#<?php echo $bgcolor; ?>" OnLoad="document.login.username.focus();">
<div align="center">

<?php
include '../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="5"><?php echo $website_title; ?> Wholesale Catalog Login</font></td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4">Today is 
<?php
$now = date("Y-m-d H:i:s");
list($now_date, $now_time) = split(' ', $now);
list($now_yr, $now_mn, $now_dy) = split('-', $now_date);
echo $now_mn . "/" . $now_dy . "/" . $now_yr . " " . $now_time . "<br><br></td></tr>\n";

//Error Messages
if($error_txt) {
	echo "<tr><td>&nbsp;</td></tr>\n";
	echo "<tr><td><font face=\"Arial\" size=\"4\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="center" valign="top"><table border="0">
<tr><td align="left" valign="top">
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4">Please login using the form below:</font><br>
<table border="0">
<form name="login" action="./index.php" method="POST">
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4">Username:</font></td><td><input type="text" name="username" size="20"></td></tr>
<tr><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4">Password:</font></td><td><input type="password" name="password" size="20"></td></tr>
<tr><td align="center" colspan="2"><input type="submit" name="submit" value="Login"></td></tr>
</form>
</table>
</td><td>&nbsp; &nbsp;</td><td align="left" valign="top"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="4">Forget your username or password?<br>
<a href="./forget_uandp.php">Get now!</a></font></td></tr>
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