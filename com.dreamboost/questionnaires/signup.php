<?php

include '../includes/main1.php';

if ( $_REQUEST["submittal"] ) {
	$query = "INSERT INTO questionnaire_users (qage, qsex, qlogin, qpw) VALUES('".$_REQUEST["q_age"]."', '".$_REQUEST["q_sex"]."', '".$_REQUEST["q_login"]."', '".md5($_REQUEST["q_pw"])."')";
	$result = mysql_query($query);

	if ( !$result ) {
		$pos = strpos(' '.mysql_error(), 'Duplicate');
		if ( $pos ) {
			echo 'There was an error signing you up.  Please try a different Login Name.';
		}
		else {
			echo 'There was an unspecified error.  Please try again.';
		}
	}
	else {
		//get the userid just created
		$queryA = "SELECT quid FROM questionnaire_users WHERE qlogin = '".$_REQUEST["q_login"]."'";
		$resultA = mysql_query($queryA);
		while ($lineA = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
			$_SESSION['user_id'] = $lineA["quid"];
		}
		mysql_free_result($resultA);

		echo 'ok|'.$_SESSION['user_id'];
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
	<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'" id="q_signup_form">
		<div class="left style2">
			<table border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td class="text_right">
						Age: 
					</td>
					<td>
						<input type="text" name="age" id="age" />
					</td>
				</tr>
				<tr>
					<td class="text_right">
						Sex: 
					</td>
					<td>
						<input type="radio" class="rad" name="sex" id="sexM" value="M" /> Male&#160;&#160;&#160;&#160;<input type="radio" class="rad" name="sex" id="sexF" value="F" /> Female
					</td>
				</tr>
				<tr>
					<td class="text_right">
						Login Name: 
					</td>
					<td>
						<input type="text" name="login_name" id="login_name" class="has_form_tip" />
						<div class="form_tip">(at least 6 characters)</div>
					</td>
				</tr>
				<tr>
					<td class="text_right">
						Password: 
					</td>
					<td>
						<input type="password" name="login_pw" id="login_pw" class="has_form_tip" />
						<div class="form_tip">(6 - 9 characters)</div>

					</td>
				</tr>
				<tr>
					<td class="text_right">
						Confirm Password: 
					</td>
					<td>
						<input type="password" name="login_pw_confirm" id="login_pw_confirm" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td class="text_right">
						<input type="button" value="Sign Up Now!" id="q_signup" />
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