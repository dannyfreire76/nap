<?php
// BME WMS
// Page: Customer Login page
// Path/File: /login.php
// Version: 1.1
// Build: 1103
// Date: 12-13-2006

// Modifed for single page use: 10/21/2010
// Added forgot to single page login form: 10/21/2010

include_once('includes/main1.php');
include_once($base_path.'includes/customer.php');

$submit = $_REQUEST["submit"];

// do some filtering on these inputs for sql attacks
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

$action = $_REQUEST["action"];
$persist = $_REQUEST["persist"];

$imgStr = null;
$error_txt = null;
$loginOk = false;
$welcomeName = null;

$last_url = $current_base;
if(!empty($_SESSION['last_url'])){
	$last_url = $_SESSION['last_url'];
}
if(empty($_SESSION['last_url']) && !empty($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'],'login.php')){
	$_SESSION['last_url'] = $_SERVER['HTTP_REFERER'];
	$last_url = $_SERVER['HTTP_REFERER'];
}

header('Content-type: text/html; charset=utf-8');

if($action == 'logout'){
	$imgStr = logoutMemberPartnerSite();
	$loginOk = false;
}

if($submit != ""){

	$msg = "";
	$member_id = "";

	//Check for Errors
	$error_txt = "";
	if($username == "") { $error_txt .= "Error, you did not enter a username.<br>\n"; }
	if($password == "") { $error_txt .= "Error, you did not enter a password.<br>\n"; }

	//If no Errors, Verify against DB
	if($error_txt == "") {
		$query = "SELECT * FROM members WHERE username='$username' AND password=md5('$password')";
		$result = mysql_query($query) or die("Query in members failed: " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$member_id = $line["member_id"];
			$member_email = $line["email"];
			$welcomeName = $line["nickname"];
		}
	}

	if($member_id != "") {

		doLogin($member_id);

		if($persist){  //user opted to stay logged in
			$thisCookie = setcookie("member_id", $member_id, time()+60*60*24*30, "/", ".".$_SERVER["HTTP_HOST"], 0) or die ("Set Cookie failed : <!-- 7 -->" . mysql_error());
		}

		$imgStr = checkPartnerSiteMembers($member_email);

		$loginOk = true;
	}
	else{
		$error_txt .= "Invalid Login. Please try again.<br>\n";
	}
}


function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your " . $website_title . " account listed below.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n\n";
		$email_str .= $base_url;

		$subject = "New  " . $website_title . " Login Info";
		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

if($_REQUEST['forgot'] != "") {

	include './admin/includes/password/class.password.php';

	// filter for valid email or return false if filter fails
	$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

	// Check for Errors
	$error_txt = "";
	if($email == ""){
		$error_txt .= "Error, you did not enter a valid email address.<br>\n";
	}

	// If no Errors, Verify against DB
	if($error_txt == "") {
		$queryPW = "SELECT username, first_name, last_name FROM members WHERE email='".$email."'";
		$resultPW = mysql_query($queryPW) or die("Query failed : " . mysql_error());
        if(mysql_num_rows($resultPW) > 0) {

            while($linePW = mysql_fetch_array($resultPW, MYSQL_ASSOC)){

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

				$error_txt .= "Your login information has been sent to the email<br />
							   address listed in your $website_title account.<br />\n";
            }
        }
        else {
    		$error_txt .= "Login information not found.";
        }
    }
}


$now = date("Y-m-d H:i:s");
list($now_date, $now_time) = split(' ', $now);
list($now_yr, $now_mn, $now_dy) = split('-', $now_date);
$rightNow = $now_mn . "/" . $now_dy . "/" . $now_yr . " " . $now_time;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><!-- <?=__FILE__?> -->
<title><?php echo $website_title; ?>: Member Login</title>
<?php include $base_path.'includes/meta1.php'; ?>

<script type="text/javascript">
<!--
function delayedRedirect(url){
    window.location = "<?php echo $last_url;?>";
}
function toggle_forgot_form(id){
	var e = document.getElementById(id);
    if(e.style.display == 'block'){
        e.style.display = 'none';
	}
    else{
        e.style.display = 'block';
	}
}
//-->
</script>

</head>

<?php if(!$loginOk):?>
	<body bgcolor="#<?php echo $bgcolor; ?>" onLoad="document.forms['login_actual'].elements['username'].focus();"><!-- <?=__FILE__?> -->
<?php else:?>
	<body bgcolor="#<?php echo $bgcolor; ?>"><!-- <?=__FILE__?> -->
<?php endif;?>

<div align="center">

<?php include $base_path.'includes/head1.php'; ?>

<?php if($loginOk):?>

	<div align="center" style="padding-top:50px;">
		<h3>Welcome <?php echo $welcomeName;?></h3>
		<p>One Moment Please...</p>
	</div>

	<script type="text/javascript">
		setTimeout('delayedRedirect()', 3000);
	</script>

<?php else:?>

	<table border="0" width="500">
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="center"><h2><?php echo $website_title; ?> Member Login</h2></td>
		</tr>
		<tr>
			<td align="center">Today is <?php echo $rightNow;?></td>
		</tr>

		<?php if($error_txt):?>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td class="error"><?php echo $error_txt;?></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		<?php endif; ?>
		<tr>
			<td align="center" valign="top">
				<table border="0">
					<tr>
						<td align="center" valign="top">Please login using the form below:<br />&nbsp;

							<form id="login_actual" action="<?php echo $current_base.'login.php'; ?>" method="POST"><!-- <?=__FILE__?> -->
								<input type="hidden" name="view" value="full">
								<table border="0" width="100%"><!-- <?=__FILE__?> -->
									<tr>
										<td align="right" id="username_label" class="text_right">Username:</td>
										<td class="text_left">
											<input type="text" name="username" id="username" size="20" />
										</td>
									</tr>
									<tr id="pw_wrapper">
										<td class="text_right">Password:</td>
										<td class="text_left">
											<span><input type="password" name="password" id="password" size="21" /></span>&#160;
										</td>
									</tr>
									<tr><td colspan="2">&nbsp;</td></tr>
									<tr>
										<td align="center" colspan="2" style="padding-left:5px;padding-bottom:10px;">
											<input type="checkbox" id="persist" name="persist" value="1" style="width:auto" />
											<label for="persist"> Remember me</label>
										</td>
									</tr>
									<tr>
										<td align="center" colspan="2">
											<input type="submit" id="submit" name="submit" value="Login" />
										</td>
									</tr>
								</table>
								<ul class="text_left">
									<li class="disc">
										<a href="javascript:void(0)" onclick="toggle_forgot_form('frm_forgot_login');">Forgot or Never Got Your Password?</a>
									</li>
								</ul>
							</form>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div id="frm_forgot_login" style="display:none;">
		<p>Please enter your email address to have your username and a temporary password emailed to you:</p>
		<form id="forget_form" action="<?php echo $current_base.'login.php'; ?>" method="POST"><!-- <?=__FILE__?> -->
			<table border="0">
				<tr>
					<td align="right">Email:</td>
					<td><input type="text" name="email" id="email" size="20" maxlength="255" /></td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input type="submit" id="submit" name="forgot" value="Retrieve Info" />
					</td>
				</tr>
			</table>
		</form>
		<p><b>NOTE:</b> Clicking on the Retrieve Info button above will reset your password. Once <br />
			 you login, you can click on the Your Profile link at the top of the page to change it.</p>
	</div>

<?php endif;?>

<?php include $base_path.'includes/foot1.php'; ?>

</div>

<?php echo $imgStr;?>

</body>
</html>
