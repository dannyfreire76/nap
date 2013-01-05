<?php

include '../includes/main1.php';

if ( $_REQUEST["submittal"] ) {
	$queryA = "SELECT quid FROM questionnaire_users WHERE qlogin = '".$_REQUEST["q_login"]."'  AND qpw = '".md5($_REQUEST["q_pw"])."'";
	$resultA = mysql_query($queryA);

	$err_msg = 'We could not locate your information.  Please check your name and password and try again.';
	
	if ( !$resultA ) {
		echo $err_msg;
	}
	else {
		if ( mysql_num_rows($resultA) > 0) {
			while ($lineA = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
				$_SESSION['user_id'] = $lineA["quid"];
				echo 'ok|'.$_SESSION['user_id'];
			}
		}
		else {
			echo $err_msg;
		}
		mysql_free_result($resultA);
	}
	exit();
}


header('Content-type: text/html; charset=utf-8');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Dream Boost Questionnaires | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<script src="../includes/questionnaires.js" type="text/javascript"></script>
<script src="../includes/wmsform.js" type="text/javascript"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('/images/warning_over.gif','/images/aboutus_over.gif','/images/newsletter_over.gif','/images/links_over.gif','/images/find_over.gif','/images/become_over.gif','/images/store_over.gif','/images/faqs_over.gif','/images/lucid_over.gif','/images/suggestions_over.gif','/images/supplement_over.gif','/images/testimonial_over.gif','/images/contact_over.gif')">

<?php
include '../includes/head1.php';

echo '
		<table border="0" width="100%">
			<tr>
				<td align="left" colspan="3">
					<img alt="Questionnaires" src="'.$current_base.'images/Questionnaires.gif" />
				</td>
			</tr>
		</table>';


echo '
	<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'" id="q_login_form">
		<div class="left style2">
			<table border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td class="text_right">
						Login Name: 
					</td>
					<td class="text_left">
						<input type="text" name="login_name" id="login_name" />
					</td>
				</tr>
				<tr>
					<td class="text_right">
						Password: 
					</td>
					<td class="text_left">
						<input type="password" name="login_pw" id="login_pw" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td class="text_right">
						<input type="button" value="Log In" id="q_login" />
					</td>
				</tr>
			</table>
		</div>

		<div class="loading left style2 text_left">&#160;</div>
	</form>
	 ';
?>

<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>