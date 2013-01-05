<?php
// BME WMS
// Page: Email Lists Send Newsletter page
// Path/File: /admin/email_lists_admin5.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/tabler1.php';


$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$field = ($_REQUEST["field"] ? $_REQUEST["field"] : "newsletter_id");
$dir = ($_REQUEST["dir"] ? $_REQUEST["dir"] : "ASC");

include './includes/wms_nav1.php';
$manager = "email_lists";
$page = "E-Mail Lists Manager > Send Newsletter";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ( $_REQUEST["action"] ) {
	//all actions result in a save

	$now = date("Y-m-d H:i:s");
	$msg_subject = $_REQUEST["msg_subject"];
	$msg_content = $_REQUEST["msg_content"];

	if ( $_REQUEST["nID"] ) {
		$query = "UPDATE newsletter set msg_subject='".$_REQUEST["msg_subject"]."', msg_content='".$_REQUEST["msg_content"]."' WHERE newsletter_ID='".$_REQUEST["nID"]."'";
		$result = mysql_query($query) or die("Query failed: ".mysql_error().'<br />'.$query);

		$newsletter_ID = $_REQUEST["nID"];
	} else {
		$query = "INSERT INTO newsletter (created, msg_subject, msg_content) VALUES ('".$now."', '".$_REQUEST["msg_subject"]."', '".$_REQUEST["msg_content"]."')";
		$result = mysql_query($query) or die("Query failed: ".mysql_error().'<br />'.$query);

		$newsletter_ID = mysql_insert_id();
	}

	if ( $_REQUEST["action"]=='saveAndSend' ) {
		$msg_content = nl2br($msg_content);
		
		//so we use the MD5 algorithm to generate a random hash
		$random_hash = md5(date('r', time()));
		
		//define the headers we want passed. Note that they are separated with \r\n
		$headers = "From: ".$website_title."<".$site_email.">\r\nReply-To: ".$site_email;
		//add boundary string and mime type specification
		$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";

		$queryNewsMembers = "SELECT * FROM news_member WHERE status='1'";
		$resultNewsMembers = mysql_query($queryNewsMembers) or die("Query failed : " . mysql_error());
		while ($lineNewsMembers = mysql_fetch_array($resultNewsMembers, MYSQL_ASSOC)) {
			$toEmail = $lineNewsMembers["email"];
			$toName = $lineNewsMembers["name"];


			//define the body of the message.
			ob_start(); //Turn on output buffering
//this is insane, but don't indent the block below or it won't work
			?>
--PHP-alt-<?php echo $random_hash; ?> 
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

Hello <?php echo $toName; ?>,<br /><br />
<?php echo $msg_content; ?>

--PHP-alt-<?php echo $random_hash; ?>--
<?
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();
			$mail_sent = @mail("$toName <$toEmail>", $msg_subject, $message, $headers);

			if ( $mail_sent ) {
				$msg_result = "Newsletter sent successfully.";

				$now2 = date("Y-m-d H:i:s");
				$querySent = "UPDATE newsletter SET last_sent='".$now2."' WHERE newsletter_id='".$newsletter_ID."'";
				$resultSent = mysql_query($querySent) or die("QuerySent failed: ".mysql_error().'<br />'.$querySent);

			} else {
				$msg_result = "There was an error sending the newsletter.";
			}
		}
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

<script type="text/javascript">
	function checkMsg(thisAction) {
		var $thisForm = $('#createMsg_form');

		if ( $('#msg_subject', $thisForm).val()=="" || $('#msg_content', $thisForm).val()=="" ) {
			alert('Please complete all fields.');
			return false;
		} else {

			$('#action', $thisForm).val(thisAction);

			if ( thisAction!='saveAndSend' || confirm('You are about to send this newsletter to all subscribed users?  Are you sure?') ) {
				$thisForm.submit();
			}
		}		
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

<?php

//Error Messages
if( $msg_result ) {
	echo '<tr><td align="left" class="'.($mail_sent ? "error3":"error").'">'.$msg_result.'</td></tr>';
}

$newsletter_ID = "";
$msg_content = "";
$msg_subject = "";

if ( $_REQUEST['nID'] && !$_REQUEST["action"] ) {
	$newsletter_ID = $_REQUEST['nID'];
	$querySel = "SELECT * FROM newsletter WHERE newsletter_id='".$newsletter_ID."'";
	$resultSel = mysql_query($querySel) or die("Query failed: ".mysql_error().'<br />'.$querySel);
	while ($lineSel = mysql_fetch_array($resultSel, MYSQL_ASSOC)) {
		foreach($lineSel as $col=>$val)	{
			$$col = $val;
		}
	}

}
?>

</table>

<form name="createMsg_form" id="createMsg_form" action="" method="post">
	<input type="hidden" name="action" id="action" value="" />
	<input type="hidden" name="nID" id="nID" value="<?=$newsletter_ID?>" />
	
	<?php
		if ( $newsletter_ID ) {
			echo 'Editing Newsletter #'.$newsletter_ID;
		} else {
			echo "Create new message: ";
		}
	?>
	<table>
		<tr>
			<td>Subject<br />
				<input type="text" name="msg_subject" id="msg_subject" value="<?=$msg_subject?>" style="width: 100%" />
			</td>
		</tr>
		<tr valign="top">
			<td>
				<br />
				Content<br />
				<textarea name="msg_content" id="msg_content" rows="10" style="width: 100%"><?=$msg_content?></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<button onClick="checkMsg('save'); return false;">Save Message</button>
				<button onClick="checkMsg('saveAndSend'); return false;">Save & Send Message</button>
			</td>
		</tr>
	</table>
</form>
<br />
<?php


$query = "SELECT * FROM newsletter ORDER BY $field $dir";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

if ( mysql_num_rows($result) > 0 ) {
	echo '<table class="maintable" width="100%" cellspacing="0">';
	
	$labels = array('ID', 'Modified', 'Subject', 'Body', 'Last Sent');
	$fields = array('newsletter_id', 'modified', 'msg_subject', 'msg_content', 'last_sent');
	getColumnHeaders($url, '1', $labels, $fields, '', '', '', '', '', '', '', '', $addtl_flds);

	echo '<th>Edit/Send</th>';

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<tr>';
		echo	'<td>';
		echo		$line["newsletter_id"];
		echo	'</td>';
		echo	'<td>';
		echo		$line["modified"];
		echo	'</td>';
		echo	'<td>';
		echo		$line["msg_subject"];
		echo	'</td>';
		echo	'<td>';
		echo		$line["msg_content"];
		echo	'</td>';
		echo	'<td>';
		echo		$line["last_sent"];
		echo	'</td>';		
		echo	'<td class="text_center" align="center">';
		echo		'<img class="hand" style="float:none" onClick="window.location.href=\'email_lists_admin5.php?nID='.$line["newsletter_id"].'\'" alt="Edit" name="edit" id="edit" src="/images/wms/edit.gif" />';
		echo	'</td>';
		echo '</tr>';
	}

	echo '</table>';
}
echo '<br />';
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>