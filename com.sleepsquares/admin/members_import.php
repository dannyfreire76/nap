<?php
// BME WMS
// Page: Members Manager Import Members page
// Path/File: /admin/members_import.php
// Version: 1.1
// Build: 1103
// Date: 12-20-2006

include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

header('Content-type: text/html; charset=utf-8');
$import_members = $_POST["import_members"];
$receipt_id = $_POST["receipt_id"];

include './includes/wms_nav1.php';
$manager = "members";
wms_manager_nav2($manager);

function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your My " . $website_title . " account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "my/\n\n";
						
		$subject = "New " . $website_title . " My " . $website_title . " Password";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

function check_dup_email($email) {
	$email_test = "";
	$query = "SELECT member_id, email FROM members WHERE email='$email'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$member_id = $line["member_id"];
		$email_test = $line["email"];
	}
	mysql_free_result($result);
	if($email_test == "") { return -1; }
	else { return $member_id; }
}

function get_receipts($receipt_id) {
	// Generate New Password
	include_once '../admin/includes/password/class.password.php';
	$pas = new password();
	$pas->specchar = true;
	$newpass = $pas->generate();
	$receipt_info['password'] = $newpass;
	
	$query = "SELECT receipt_id, bill_email, bill_name, bill_address1, bill_address2, bill_city, bill_state, bill_zip, bill_country, bill_phone, ship_name, ship_address1, ship_address2, ship_city, ship_state, ship_zip, ship_country, ship_phone, cc_first_name, cc_last_name FROM receipts WHERE complete='1' AND member_id='0' AND receipt_id='$receipt_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$receipt_info['receipt_id'] = $line["receipt_id"];
		$receipt_info['bill_email'] = $line["bill_email"];
		$receipt_info['bill_name'] = $line["bill_name"];
		$receipt_info['bill_address1'] = $line["bill_address1"];
		$receipt_info['bill_address2'] = $line["bill_address2"];
		$receipt_info['bill_city'] = $line["bill_city"];
		$receipt_info['bill_state'] = $line["bill_state"];
		$receipt_info['bill_zip'] = $line["bill_zip"];
		$receipt_info['bill_country'] = $line["bill_country"];
		$receipt_info['bill_phone'] = $line["bill_phone"];
		$receipt_info['ship_name'] = $line["ship_name"];
		$receipt_info['ship_address1'] = $line["ship_address1"];
		$receipt_info['ship_address2'] = $line["ship_address2"];
		$receipt_info['ship_city'] = $line["ship_city"];
		$receipt_info['ship_state'] = $line["ship_state"];
		$receipt_info['ship_zip'] = $line["ship_zip"];
		$receipt_info['ship_country'] = $line["ship_country"];
		$receipt_info['ship_phone'] = $line["ship_phone"];
		$receipt_info['cc_first_name'] = $line["cc_first_name"];
		$receipt_info['cc_last_name'] = $line["cc_last_name"];
	}
	mysql_free_result($result);

	if($receipt_info['bill_name'] != "") {
		$receipt_info['nickname'] = $receipt_info['bill_name'];
	} else {
		if($receipt_info['ship_name'] != "") {
			$receipt_info['nickname'] = $receipt_info['ship_name'];
			$receipt_info['bill_name'] = $receipt_info['cc_first_name'] . " " . $receipt_info['cc_last_name'];
		}
	}
	$names = explode(" ", $receipt_info['nickname']);
	$names_count = count($names);
	$names_last = $names_count - 1;
	$receipt_info['first_name'] = $names[0];
	$receipt_info['last_name'] = $names[$names_last];

	return $receipt_info;
}

if($import_members != "") {
	$receipt_id_last = $receipt_id + 100;
	for($i=$receipt_id;$i<$receipt_id_last;$i++) {
		$receipt_info = get_receipts($i);
		if($receipt_info['bill_email'] != "") {
			$member_email_test = check_dup_email($receipt_info['bill_email']);
		
			if($member_email_test < 0) {
				$now = date("Y-m-d H:i:s");
				$query = "INSERT INTO members SET created='$now', status='1', email='";
				$query .= $receipt_info['bill_email'];
				$query .= "', username='";
				$query .= $receipt_info['bill_email'];
				$query .= "', password='";
				$query .= $receipt_info['password'];
				$query .= "', nickname='";
				$query .= addslashes($receipt_info['nickname']);
				$query .= "', first_name='";
				$query .= addslashes($receipt_info['first_name']);
				$query .= "', last_name='";
				$query .= addslashes($receipt_info['last_name']);
				$query .= "', bill_name='";
				$query .= addslashes($receipt_info['bill_name']);
				$query .= "', bill_address1='";
				$query .= addslashes($receipt_info['bill_address1']);
				$query .= "', bill_address2='";
				$query .= addslashes($receipt_info['bill_address2']);
				$query .= "', bill_city='";
				$query .= addslashes($receipt_info['bill_city']);
				$query .= "', bill_state='";
				$query .= $receipt_info['bill_state'];
				$query .= "', bill_zip='";
				$query .= $receipt_info['bill_zip'];
				$query .= "', bill_country='";
				$query .= $receipt_info['bill_country'];
				$query .= "', bill_phone='";
				$query .= $receipt_info['bill_phone'];
				$query .= "', ship_name='";
				$query .= addslashes($receipt_info['ship_name']);
				$query .= "', ship_address1='";
				$query .= addslashes($receipt_info['ship_address1']);
				$query .= "', ship_address2='";
				$query .= addslashes($receipt_info['ship_address2']);
				$query .= "', ship_city='";
				$query .= addslashes($receipt_info['ship_city']);
				$query .= "', ship_state='";
				$query .= $receipt_info['ship_state'];
				$query .= "', ship_zip='";
				$query .= $receipt_info['ship_zip'];
				$query .= "', ship_country='";
				$query .= $receipt_info['ship_country'];
				$query .= "', ship_phone='";
				$query .= $receipt_info['ship_phone'];
				$query .= "'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
	
				$query = "SELECT member_id FROM members WHERE email='";
				$query .= $receipt_info['bill_email'];
				$query .= "'";
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$member_id = $line["member_id"];
				}
				mysql_free_result($result);
	
				//send_email_login($bill_email, $first_name, $last_name, $bill_email, $newpass);
	
			} else {
				$member_id = $member_email_test;
			}
			
			//Write to receipts DB
			$query = "UPDATE receipts SET member_id='$member_id' WHERE receipt_id='";
			$query .= $receipt_info['receipt_id'];
			$query .= "'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Admin - Members</title>

<link rel="stylesheet" type="text/css" href="<?php echo $base_secure_url; ?>includes/site_styles.css">

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php
include '../includes/head_admin1.php';
?>

<table border="0" width="677">

<?php
wms_manager_nav1($manager, 1);
?>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="Arial" size="+2"><?php echo $website_title; ?> Administration Area - Members</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="Arial" size="+1">Welcome to the Members Manager Import Members page, where you create new members from the receipts.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="Arial" size="+1"><a href="./members_admin2.php">Manage Members</a><br>
<a href="./members_admin3.php">Create New Members</a><br>
<a href="./members_admin4.php">Members Statistics</a><br>
<a href="./members_admin5.php">Manage Members Emails</a></font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td><font face=\"Arial\" size=\"+1\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form action="./members_import.php" method="POST">
<tr><td align="left"><font face="Arial" size="+1">Receipt ID: <input type="text" name="receipt_id"></font></td></tr>
<tr><td align="left"><input type="submit" name="import_members" value="Import Members"></td></tr>
</form>

<tr><td>&nbsp;</td></tr>
<tr><td align="left"><font face="Arial" size="+1">Back to <a href="./members_admin.php">Main Members Admin Page</a></font></td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
</body>
</html>