<?php
// BME WMS
// Page: Members Manager Create Member page
// Path/File: /admin/members_admin3.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/st_and_co1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$submit = $_POST["submit"];
$email = $_POST["email"];
$password = $_POST["password"];
$nickname = $_POST["nickname"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$dob = $_POST["dob"];
$bill_name = $_POST["bill_name"];
$bill_address1 = $_POST["bill_address1"];
$bill_address2 = $_POST["bill_address2"];
$bill_city = $_POST["bill_city"];
$bill_state = $_POST["bill_state"];
$bill_zip = $_POST["bill_zip"];
$bill_country = $_POST["bill_country"];
$bill_phone = $_POST["bill_phone"];
$ship_name = $_POST["ship_name"];
$ship_address1 = $_POST["ship_address1"];
$ship_address2 = $_POST["ship_address2"];
$ship_city = $_POST["ship_city"];
$ship_state = $_POST["ship_state"];
$ship_zip = $_POST["ship_zip"];
$ship_country = $_POST["ship_country"];
$ship_phone = $_POST["ship_phone"];

include './includes/wms_nav1.php';
$manager = "members";
$page = "Members Manager > Create New Members";
wms_manager_nav2($manager);
wms_page_nav2($manager);

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

function check_dup_email_local($email) {
	$email_test = "";
	$query = "SELECT email FROM members WHERE email='$email'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$email_test = $line["email"];
	}
	mysql_free_result($result);
	if($email_test == "") { return 1; }
	else { return -1; }
}

if($submit != "") {
	//Check for Errors
	$error_txt = "";
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){ $error_txt .= "Error, the email address you entered is incorrect. Please enter your email address.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a password. Please enter your password.<br>\n"; }
	if($nickname == "") { $error_txt .= "Error, you did not enter a nickname. Please enter your nickname.<br>\n"; }
	if($first_name == "") { $error_txt .= "Error, you did not enter a first name. Please enter your first name.<br>\n"; }
	if($last_name == "") { $error_txt .= "Error, you did not enter a last name. Please enter your last name.<br>\n"; }
	if($dob == "") { $error_txt .= "Error, you did not pick a date of birth. Please pick your date of birth.<br>\n"; }
	
	// If no errors test for unique email
	if($error_txt == "") {
		$email_test = check_dup_email_local($email);
		if($email_test < 0) {
			$error_txt .= "Error, this email address has already been used to create a Member Account. Please use a different email address or Edit the current member.<br>\n";
		}
	}
	
	//If no Errors, Insert into DB
	if($error_txt == "") {
		$now = date("Y-m-d H:i:s");

		$queryBase = " members SET created='$now', status='1', email='$email', username='$email', password='".md5($password)."', nickname='$nickname', first_name='$first_name', last_name='$last_name', date_of_birth='".date("Y-m-d H:i:s", strtotime($dob))."', bill_name='$bill_name', bill_address1='$bill_address1', bill_address2='$bill_address2', bill_city='$bill_city', bill_state='$bill_state', bill_zip='$bill_zip', bill_country='$bill_country', bill_phone='$bill_phone', ship_name='$ship_name', ship_address1='$ship_address1', ship_address2='$ship_address2', ship_city='$ship_city', ship_state='$ship_state', ship_zip='$ship_zip', ship_country='$ship_country', ship_phone='$ship_phone'";

		$query = "INSERT INTO ".$queryBase;
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		send_email_login($email, $first_name, $last_name, $email, $password);

		include_once($base_path.'includes/customer.php');

		//force member duplication on partner sites
		$querySites = "SELECT * FROM partner_sites WHERE site_url!='".$_SERVER["HTTP_HOST"]."'";
		$resultSites = mysql_query($querySites) or die("Query 2 failed: " . mysql_error());
		while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
			$thisDBHName = "dbh".$lineSites["site_key_name"];
			$thisHandle = $$thisDBHName;

			$member_email_test = check_dup_email($email, $thisHandle);

			if($member_email_test > 0) {//already exists so update with same info as referring site
				$updateQ = "UPDATE ".$queryBase." WHERE email='$email'";
				$resultQ = mysql_query($updateQ, $thisHandle) or die("Update Query failed : " . mysql_error().'<br /><br />'.$updateQ);

			} else {//no matching email in this site, so create new member record with same info as referring site
				duplicateMember($dbh, $email, $thisHandle);
			}
		}

		//Goto Index
		header("Location: " . $base_url . "admin/members_admin2.php");
		exit;
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" OnLoad="document.create_member.email.focus();">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Welcome to the Members Manager Create Member page, where you create new members of your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left">
	<FORM name="create_member" Method="POST" ACTION="./members_admin3.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Member Information</legend>
		<ol>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="255" value="<?php echo $email; ?>" tabindex="1" />
			</li>
			<li>
				<label for="password">Password <em>*</em></label>
				<INPUT type="text" id="password" name="password" size="10" maxlength="10" value="<?php echo $password; ?>" tabindex="2" />
			</li>
			<li>
				<label for="nickname">Nickname <em>*</em></label>
				<INPUT type="text" id="nickname" name="nickname" size="30" maxlength="50" value="<?php echo $nickname; ?>" tabindex="3" />
			</li>
			<li>
				<label for="first_name">First Name <em>*</em></label>
				<INPUT type="text" id="first_name" name="first_name" size="30" maxlength="50" value="<?php echo $first_name; ?>" tabindex="4" />
			</li>
			<li>
				<label for="last_name">Last Name <em>*</em></label>
				<INPUT type="text" id="last_name" name="last_name" size="30" maxlength="50" value="<?php echo $last_name; ?>" tabindex="5" />
			</li>
			<li>
				<label for="pick_date">Date of Birth <em>*</em></label>
				<input type="text" id="dob" name="dob" size="10" maxlength="10" value="" tabindex="6" />(mm/dd/yyy)
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Billing Address Information</legend>
		<ol>		
			<li>
				<label for="bill_name">Name <em>*</em></label>
				<INPUT type="text" id="bill_name" name="bill_name" size="30" maxlength="100" value="<?php echo $bill_name; ?>" tabindex="7" />
			</li>
			<li>
				<label for="bill_address1">Address <em>*</em></label>
				<INPUT type="text" id="bill_address1" name="bill_address1" size="30" maxlength="100" value="<?php echo $bill_address1; ?>" tabindex="8" />
			</li>
			<li class="fm-optional">
				<label for="bill_address2">Address 2</label>
				<INPUT type="text" id="bill_address2" name="bill_address2" size="30" maxlength="100" value="<?php echo $bill_address2; ?>" tabindex="9" />
			</li>
			<li>
				<label for="bill_city">City <em>*</em></label>
				<INPUT type="text" id="bill_city" name="bill_city" size="30" maxlength="50" value="<?php echo $bill_city; ?>" tabindex="10" />
			</li>
			<li>
				<label for="bill_state">State/Province <em>*</em></label>
				<select id="bill_state" name="bill_state" tabindex="11">
				<?php
				state_build_all($bill_state);
				?>
				</select>
			</li>
			<li>
				<label for="bill_zip">Zip/Postal Code <em>*</em></label>
				<INPUT type="text" id="bill_zip" name="bill_zip" size="10" maxlength="10" value="<?php echo $bill_zip; ?>" tabindex="12" />
			</li>
			<li>
				<label for="bill_country">Country <em>*</em></label>
				<select id="bill_country" name="bill_country" tabindex="13">
				<?php
				country_build_all($bill_country);
				?>
				</select>
			</li>
			<li>
				<label for="bill_phone">Phone <em>*</em></label>
				<INPUT type="text" id="bill_phone" name="bill_phone" size="30" maxlength="30" value="<?php echo $bill_phone; ?>" tabindex="14" />
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Shipping Address Information</legend>
		<ol>		
			<li>
				<label for="ship_name">Name <em>*</em></label>
				<INPUT type="text" id="ship_name" name="ship_name" size="30" maxlength="100" value="<?php echo $ship_name; ?>" tabindex="15" />
			</li>
			<li>
				<label for="ship_address1">Address <em>*</em></label>
				<INPUT type="text" id="ship_address1" name="ship_address1" size="30" maxlength="100" value="<?php echo $ship_address1; ?>" tabindex="16" />
			</li>
			<li class="fm-optional">
				<label for="ship_address2">Address 2</label>
				<INPUT type="text" id="ship_address2" name="ship_address2" size="30" maxlength="100" value="<?php echo $ship_address2; ?>" tabindex="17" />
			</li>
			<li>
				<label for="ship_city">City <em>*</em></label>
				<INPUT type="text" id="ship_city" name="ship_city" size="30" maxlength="50" value="<?php echo $ship_city; ?>" tabindex="18" />
			</li>
			<li>
				<label for="ship_state">State/Province <em>*</em></label>
				<select id="ship_state" name="ship_state" tabindex="19">
				<?php
				state_build_all($ship_state);
				?>
				</select>
			</li>
			<li>
				<label for="ship_zip">Zip/Postal Code <em>*</em></label>
				<INPUT type="text" id="ship_zip" name="ship_zip" size="10" maxlength="10" value="<?php echo $ship_zip; ?>" tabindex="20" />
			</li>
			<li>
				<label for="ship_country">Country <em>*</em></label>
				<select id="ship_country" name="ship_country" tabindex="21">
				<?php
				country_build_all($ship_country);
				?>
				</select>
			</li>
			<li>
				<label for="ship_phone">Phone <em>*</em></label>
				<INPUT type="text" id="ship_phone" name="ship_phone" size="30" maxlength="30" value="<?php echo $ship_phone; ?>" tabindex="22" />
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Create Member">
			</li>
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>