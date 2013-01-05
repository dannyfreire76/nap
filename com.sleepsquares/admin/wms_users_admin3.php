<?php
// BME MyBWMS
// Page: MyBWMS Users Create User page
// Path/File: /admin/wms_users_admin3.php
// Version: 1.8
// Build: 1804
// Date: 02-13-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

//no idea why these get picked up from session, so clear them:
$dashboard = 0;
$main_settings = 0;

$active_pages_str = "";
foreach($_POST as $fld_name=>$fld_val) {
	$$fld_name = $fld_val;
    if ( strpos($fld_name,'txtAdminPages_')!==false ) {
        $active_pages_str .= $fld_val.'|';
    }
}

$merchant_acct = $_POST["merchant_acct"];

include './includes/wms_nav1.php';
$manager = "users";
$page = "MyBWMS Users Manager > Create New Users";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Check for Errors
	$error_txt = "";
	if($username == "") { $error_txt .= "Error, you did not enter a username. There needs to be an username.<br>"; }
	if($password == "") { $error_txt .= "Error, you did not enter a password. There needs to be a password.<br>"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){ $error_txt .= "Error, you did not enter the user's email address. There needs to be an email address.<br>"; }
	if($first_name == "") { $error_txt .= "Error, you did not enter a first name. There needs to be a first name.<br>"; }
	if($last_name == "") { $error_txt .= "Error, you did not enter a last name. There needs to be a last name.<br>"; }

	//If no Errors, Insert to DB
	if($error_txt == "") {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO wms_users SET created='$now', status='$status', username='$username', password='$password', email='$email', first_name='$first_name', last_name='$last_name', timezone='$timezone', dashboard='$dashboard', main_settings='$main_settings', active_pages='$active_pages_str'";
		$result = mysql_query($query, $dbh_master) or die("Query failed : " . mysql_error());
		$wms_user_id = mysql_insert_id($dbh_master);
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "The password for your " . $website_title;
		$email_str .= " MyBWMS account has been set - by you or your ";
		$email_str .= "MyBWMS Administrator. Please find the login details ";
		$email_str .= "for your MyBWMS account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "admin/\n\n";
						
		$subject = "New " . $website_title . " MyBWMS Password";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);

		//build wms_users_sites records
		foreach($_POST as $fld_name=>$fld_val) {
			if ( strpos($fld_name,'site_')!==false ) {
				$queryIns = "INSERT INTO wms_users_sites (user_id, site_id) VALUES ($wms_user_id, ".$fld_val.")";
				mysql_query($queryIns, $dbh_master) or die("Query failed: " . mysql_error());		
			}
		}

		//Goto Thanks
		header("Location: " . $base_url . "admin/wms_users_admin2.php");
		exit;
	}
}

$query = "SELECT shipping, members, inventory, products, lynkstation, faqs, contact_us, testimonials, leads, retailers, search_engine, email_lists, photos, surveys, polls, reports, users, hosting, support, merchant_acct FROM wms_main WHERE wms_id='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$shipping2 = $line["shipping"];
	$members2 = $line["members"];
	$inventory2 = $line["inventory"];
	$products2 = $line["products"];
	$lynkstation2 = $line["lynkstation"];
	$faqs2 = $line["faqs"];
	$contact_us2 = $line["contact_us"];
	$testimonials2 = $line["testimonials"];
	$leads2 = $line["leads"];
	$retailers2 = $line["retailers"];
	$search_engine2 = $line["search_engine"];
	$email_lists2 = $line["email_lists"];
	$photos2 = $line["photos"];
	$surveys2 = $line["surveys"];
	$polls2 = $line["polls"];
	$reports2 = $line["reports"];
	$users2 = $line["users"];
	$hosting2 = $line["hosting"];
	$support2 = $line["support"];
	$merchant_acct2 = $line["merchant_acct"];
}
mysql_free_result($result);
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
<script language="JavaScript">
function genPassword(newPass) {
	document.form1.password.value = newPass;
}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Please use the form below to create a new MyBWMS user for the website administration area.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<tr><td align="left">
	<FORM name="users-create" Method="POST" ACTION="./wms_users_admin3.php" class="wmsform">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter User Information</legend>
		<ol>
			<li>
				<label for="username">Username <em>*</em></label>
				<INPUT type="text" id="username" name="username" size="30" maxlength="100" value="" tabindex="1" />
			</li>
			<li>
				<label for="password">Password <em>*</em></label>
				<INPUT type="text" id="password" name="password" size="20" maxlength="20" value="" tabindex="2" />
			</li>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="20" maxlength="100" value="" tabindex="3" />
			</li>
			<li>
				<label for="first_name">First Name <em>*</em></label>
				<INPUT type="text" id="first_name" name="first_name" size="30" maxlength="50" value="" tabindex="4" />
			</li>
			<li>
				<label for="last_name">Last Name <em>*</em></label>
				<INPUT type="text" id="last_name" name="last_name" size="30" maxlength="50" value="" tabindex="5" />
			</li>
			<li>
				<label for="status">Status <em>*</em></label>
				<select id="status" name="status" tabindex="6">
				<option value="1"<?php if($status == 1) { echo " SELECTED"; } ?>>Active</option>
				<option value="0"<?php if($status == 0) { echo " SELECTED"; } ?>>Inactive</option>
				</select>
			</li>
			<li>
				<label for="timezone">Time Zone <em>*</em></label>
				<select id="timezone" name="timezone" tabindex="7">
				<option value="UTC-12"<?php if($timezone == "UTC-12") { echo " SELECTED"; } ?>>UTC-12</option>
				<option value="UTC-11"<?php if($timezone == "UTC-11") { echo " SELECTED"; } ?>>UTC-11</option>
				<option value="UTC-10"<?php if($timezone == "UTC-10") { echo " SELECTED"; } ?>>UTC-10</option>
				<option value="UTC-09"<?php if($timezone == "UTC-09") { echo " SELECTED"; } ?>>UTC-9</option>
				<option value="UTC-08"<?php if($timezone == "UTC-08") { echo " SELECTED"; } ?>>UTC-8 Pacific Time</option>
				<option value="UTC-07"<?php if($timezone == "UTC-07") { echo " SELECTED"; } ?>>UTC-7 Mountain Time</option>
				<option value="UTC-06"<?php if($timezone == "UTC-06") { echo " SELECTED"; } ?>>UTC-6 Central Time</option>
				<option value="UTC-05"<?php if($timezone == "UTC-05") { echo " SELECTED"; } ?>>UTC-5 Eastern Time</option>
				<option value="UTC-04"<?php if($timezone == "UTC-04") { echo " SELECTED"; } ?>>UTC-4 Atlantic Time</option>
				<option value="UTC-03"<?php if($timezone == "UTC-03") { echo " SELECTED"; } ?>>UTC-3</option>
				<option value="UTC-02"<?php if($timezone == "UTC-02") { echo " SELECTED"; } ?>>UTC-2</option>
				<option value="UTC-01"<?php if($timezone == "UTC-01") { echo " SELECTED"; } ?>>UTC-1</option>
				<option value="UTC+00"<?php if($timezone == "UTC+00") { echo " SELECTED"; } ?>>UTC</option>
				<option value="UTC+01"<?php if($timezone == "UTC+01") { echo " SELECTED"; } ?>>UTC+1</option>
				<option value="UTC+02"<?php if($timezone == "UTC+02") { echo " SELECTED"; } ?>>UTC+2</option>
				<option value="UTC+03"<?php if($timezone == "UTC+03") { echo " SELECTED"; } ?>>UTC+3</option>
				<option value="UTC+04"<?php if($timezone == "UTC+04") { echo " SELECTED"; } ?>>UTC+4</option>
				<option value="UTC+05"<?php if($timezone == "UTC+05") { echo " SELECTED"; } ?>>UTC+5</option>
				<option value="UTC+06"<?php if($timezone == "UTC+06") { echo " SELECTED"; } ?>>UTC+6</option>
				<option value="UTC+07"<?php if($timezone == "UTC+07") { echo " SELECTED"; } ?>>UTC+7</option>
				<option value="UTC+08"<?php if($timezone == "UTC+08") { echo " SELECTED"; } ?>>UTC+8</option>
				<option value="UTC+09"<?php if($timezone == "UTC+09") { echo " SELECTED"; } ?>>UTC+9</option>
				<option value="UTC+10"<?php if($timezone == "UTC+10") { echo " SELECTED"; } ?>>UTC+10</option>
				<option value="UTC+11"<?php if($timezone == "UTC+11") { echo " SELECTED"; } ?>>UTC+11</option>
				<option value="UTC+12"<?php if($timezone == "UTC+12") { echo " SELECTED"; } ?>>UTC+12</option>
				<option value="UTC+13"<?php if($timezone == "UTC+13") { echo " SELECTED"; } ?>>UTC+13</option>
				</select>
			</li>
		</ol>
	</fieldset>
	<fieldset>
		<legend>Which site(s) should this user have access to?</legend>
		<ol>
			<li class="fm-none">
				<fieldset>
					<?php
						$queryUserSites = "SELECT sites.site_id AS the_site_id, sites.* FROM sites";
						$resultUserSites = mysql_query($queryUserSites, $dbh_master) or die("Query failed : " . mysql_error());
						while ($lineUserSites = mysql_fetch_array($resultUserSites, MYSQL_ASSOC)) {
							echo '<label for="site_'.$lineUserSites["the_site_id"].'">';
							echo '<input type="checkbox" id="site_'.$lineUserSites["the_site_id"].'" name="site_'.$lineUserSites["the_site_id"].'" value="'.$lineUserSites["the_site_id"].'" ';
							if ( $lineUserSites["wms_users_sites_id"] != "" ) {
								echo ' checked ';
							}
							echo '/>&#160;';
							echo $lineUserSites["site_title"].'</label>';
						}
					?>
				<fieldset>
			</li>

		</ol>
	</fieldset>
	<fieldset>
		<legend>Which section(s) of the MyBWMS should this user have access to?</legend>
		<ol>
			<li class="fm-none">
				<fieldset>
					<label for="dashboard"><input type="checkbox" checked="true" id="dashboard" name="dashboard" value="1" CHECKED tabindex="8" /> Executive Sales Summary Dashboard</label>
					<label for="main_settings"><input type="checkbox" checked="true" id="main_settings" name="main_settings" value="1"<?php if($main_settings == "1") { echo " CHECKED"; } ?> tabindex="9" /> Access to Main Settings</label>
                    <?php
                    $query = "SELECT * FROM admin_pages WHERE parent_id IS NULL ORDER BY sequence";
                    $result = mysql_query($query) or die("Query failed : " . mysql_error());
                    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                        echo '<label for="txtAdminPages_'.$line["admin_page_id"].'"><input type="checkbox" id="txtAdminPages_'.$line["admin_page_id"].'" name="txtAdminPages_'.$line["admin_page_id"].'" value="'.$line["admin_page_id"].'" CHECKED /> '.$line["admin_page_name"].'</label>';
                    }

                    ?>
				</fieldset>
			</li>
			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Create User">
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