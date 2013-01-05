<?php
// BME WMS
// Page: Customer Login page
// Path/File: /login.php
// Version: 1.1
// Build: 1103
// Date: 12-13-2006

include_once('includes/main1.php');
include_once($base_path.'includes/customer.php');

$full_view = ($_REQUEST['view']=='full') ? true : false;
$submit = $_REQUEST["submit"];
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];
$action = $_REQUEST["action"];
$persist = $_REQUEST["persist"];


if ( $action=='logout' ) {
	
	$imgStr = logoutMemberPartnerSite();
	echo $imgStr;
    exit();
}

if ( !$member_id ) {
    if ( $full_view ) {
        header('Content-type: text/html; charset=utf-8');
    }

    if($submit != "") {
		$msg = "";
		$imgStr = "";

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
            }
        }

        if($member_id != "") {
			doLogin($member_id);

			if ( $persist ) {//user opted to stay logged in
				$thisCookie = setcookie("member_id", $member_id, time()+60*60*24*30, "/", ".".$_SERVER["HTTP_HOST"], 0) or die ("Set Cookie failed : " . mysql_error());
			}

			$imgStr = checkPartnerSiteMembers($member_email);

			if ( $full_view ) {
                //Goto Index2
                header("Location: index2.php");
                exit;
            }
        } else {
            $error_txt .= "Login not found.<br>\n";
        }

        if ( !$full_view ) {
            if ($error_txt) {
                $msg = $error_txt;
            } else {
				$msg='ok|'.$imgStr;
			}
            echo $msg;
            exit;
        }

    }

    if ( $full_view ) {
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
    <title><?php echo $website_title; ?>: Member Login</title>
    <?php
    include $base_path.'includes/meta1.php';
    ?>
    <link rel="stylesheet" type="text/css" href="/includes/site_styles.css" />
    </head>
    <body bgcolor="#<?php echo $bgcolor; ?>" OnLoad="document.login.username.focus();">
    <div align="center">

    <?php
    include '../includes/head1.php';
    ?>

    <table border="0" width="677">

    <tr><td>&nbsp;</td></tr>

    <tr><td align="left"><?php echo $website_title; ?> Member Login</td></tr>

    <tr><td align="left">Today is 
    <?php
    $now = date("Y-m-d H:i:s");
    list($now_date, $now_time) = split(' ', $now);
    list($now_yr, $now_mn, $now_dy) = split('-', $now_date);
    echo $now_mn . "/" . $now_dy . "/" . $now_yr . " " . $now_time . "<br><br></td></tr>\n";

    //Error Messages
    if($error_txt) {
        echo "<tr><td>&nbsp;</td></tr>\n";
        echo "<tr><td class=\"error\">$error_txt</td></tr>\n";
        echo "<tr><td>&nbsp;</td></tr>\n";
    }

    ?>
    <tr><td align="center" valign="top"><table border="0">
    <tr><td align="left" valign="top">
    Please login using the form below:<br>
    <?php
    }//END if ( $full_view )	
    ?>
    <form id="login_actual" action="<?php echo $current_base.'login.php'; ?>" method="POST">
        <table border="0" width="100%">
			<tr>
                <td align="right" id="username_label" class="text_right">Username:</td>
                <td class="text_left"><input type="text" name="username" id="username" size="20" /></td>
            </tr>
            <tr id="pw_wrapper">
                <td class="text_right"><span>Password:</span>&#160;</td>
                <td class="text_left"><span><input type="password" name="password" id="password" size="20" /></span>&#160;</td>
            </tr>
            <tr>
                <td align="center" colspan="2"><input type="checkbox" id="persist" name="persist" value="1" style="width:auto" /><label for="persist"> Remember me</label></td>
			</tr>
			<tr>
                <td align="center" colspan="2"><input type="submit" id="submit" name="submit" value="Login" /></td>
			</tr>
        </table>
        <div class="loading text_center">&#160;</div>
        <ul class="text_left">
            <li class="disc">
                <a href="javascript:void(0)" link="<?=$current_base?>c_forget_uandp.php" id="forgotpw">Forgot Your Password?</a>
            </li>
            <li class="disc">
                <a href="javascript:void(0)" link="<?=$current_base?>c_forget_uandp.php?unOnly=1" id="nevergotpw">Retrieve Username</a>
            </li>

	</ul>
    </form>

    <?php
    if ( $full_view ) {
    ?>
        </td><td></td></tr>
        </table></td></tr>

        <tr><td>&nbsp;</td></tr>

        </table>

    <?php
        include '../includes/foot1.php';
    ?>

        </div>
    </body>
    </html>
    <?php
    }//END if ( $full_view )
}
?>
