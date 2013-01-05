<?php
// BME WMS
// Page: Reports Manager Combined Sales page
// Path/File: /admin/reports_admin_q.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

function buildReportHeaders($result3) {
	$qhid = $_GET["qhid"];
	echo '<table border="0" width="100%" cellpadding="4" cellspacing="1" class="questionnaire maintable"><tr valign="bottom"><th class="text_center">&#160;Member&#160;</th>';
	$q_overall = 0;

	while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
		$display_question = $line3["question"];
		$display_class='';
		if ( strlen( $display_question) > 60 ) {
			$display_question = substr( $display_question, 0, 60 ).'...';
			$display_class = 'theres_more';
		}
		echo '<th class="'.$display_class.' hand" details_align="top">'.$display_question.'<div class="details no_display absolute">'.$line3["question"].'</div></th>';
	}			
	echo '</tr>';
}

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
$manager = "reports";
$page = "Reports Manager > Questionnaires Reports";
wms_manager_nav2($manager);
wms_page_nav2($manager);

$query = "SELECT state_tax FROM ship_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$state_tax = $line["state_tax"];
}
mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="includes/wmsform.css">
<style type="text/css">
	.details {
		background-color: #D0D7E2;
		font-size: 11px;
		border: 1px solid #000;
		padding: 3px;
	}
</style>
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/extend.js"></script>
<script type="text/javascript" src="/includes/interface.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
<script type="text/javascript" src="includes/reports.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Welcome to the Reports Manager Questionnaires section where you will find a collection of reports and statistical information about the questionnaires completed on your website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">

<?php
	$qhid = $_GET["qhid"];

	echo '<ul class="qul text_left style2">';

	$query = "SELECT qhid, title, seq FROM questionnaire_header ORDER BY seq ASC";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$li_class = '';
		if ( $line["qhid"] == $qhid ) {
			$li_class = 'bold';
		}
		echo '<li class="'.$li_class.'"><a href="'.$_SERVER["SCRIPT_NAME"].'?qhid='.$line["qhid"].'">'.$line["title"].'</a>';
		echo '</li>';
	}
	mysql_free_result($result);
	echo '</ul></div>';


	if ( $qhid ) {//questionnaire selected
		$query = "SELECT qhid, intro, seq FROM questionnaire_header WHERE qhid='".$qhid."'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo '<span class="style3"><br />'.$line["intro"].'</span>';

			$query3 = "SELECT qid, question FROM questionnaire_questions quests, questionnaire_qsets qsets WHERE qsets.qhid='".$qhid."' AND quests.qsid = qsets.qsid ORDER BY qsets.seq ASC, quests.seq ASC";
			$resultH = mysql_query($query3) or die("Query failed : " . mysql_error());
			buildReportHeaders($resultH);

			$query4 = "SELECT members.first_name, members.last_name, qc.qcid FROM members members, questionnaires_complete qc WHERE members.member_id = qc.quid AND qc.qhid = '".$qhid."' ORDER BY qc.created";
			$result4 = mysql_query($query4) or die("Query failed : " . mysql_error());

			if ( mysql_num_rows($result4) > 0  ) {
				while ($line4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
					$row_ct++;
					$row_class = 'odd';
					if ( $row_ct%2 == 0 ) {
						$row_class = 'd';
					}
					else {
						$row_class = 'e';
					}

					if ( $row_ct%25==0 ) {
						$resultH = mysql_query($query3) or die("Query failed : " . mysql_error());
						buildReportHeaders($resultH);
					}

					echo '<tr class="'.$row_class.'">';
					echo '<td class="theres_more hand bold" align="center" details_align="right">';
					echo $line4["first_name"].' '.$line4["last_name"];
					echo '</td>';

					$query5 = "SELECT qr.answer, qr.aid, qr.qcid, qa.aid, qa.answer_val FROM questionnaires_results qr RIGHT JOIN questionnaire_questions qq ON qq.qid=qr.qid AND qr.qcid='".$line4["qcid"]."' LEFT JOIN questionnaire_answers qa ON qr.aid=qa.aid WHERE qq.qsid IN ( SELECT sets.qsid FROM questionnaire_qsets sets WHERE qhid='".$qhid."')";
					$result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
					$qcell_ct=0;

					if ( $row_ct == 1 ) {
						$rows_returned = mysql_num_rows($result5);
						for ( $x=0; $x<$rows_returned; $x++ ) {
							$answers_arr[$x] = array();
						}
					}
					while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
						if ( $line5["aid"]!=0 ) {
							echo '<td align="center">';
							array_push( $answers_arr[$qcell_ct],  $line5["answer_val"]);
							echo $line5["answer_val"];
						}
						else {
							$display_answer = $line5["answer"];
							$answer_class = '';
							if ( strlen( $display_answer) > 40 ) {
								$display_answer = substr( $display_answer, 0, 40 ).'...';
								$answer_class = "theres_more";
							}
							echo '<td align="center" class="'.$answer_class.'" details_align="left">';
							echo $display_answer;
							echo '<div class="details no_display absolute text_left">'.$line5["answer"].'</div></td>';

						}
						echo '</td>';
						$qcell_ct++;
					}

					echo '</tr>';
				}

				echo '<tr>';
				echo '<th align="right">Mode</th>';
				for ( $i=0; $i<$qcell_ct; $i++ ) {
					echo '<th align="center">';
					if ( count($answers_arr[$i])>0 ) {
						$this_arr_ct = array_count_values($answers_arr[$i]);
						$mode_found = false;
						foreach ($this_arr_ct as $key=>$val) {
							if ( $val==max($this_arr_ct) ) {
								if ( $mode_found ) {//there's another element tied for the mode
									echo ' | ';
								}
								echo $key;
								$mode_found = true;
							}
						}
					}
					echo '</th>';
				}
				echo '</tr>';
			}
			else {
				echo '<tr><td colspan="'.(mysql_num_rows($resultH)+1).'" align="center" class="error">Sorry, there are no results for this questionnaire.</td></tr>';
			}

			echo '</table>';
		}
		mysql_free_result($result);
	}

?>

</font></td></tr>

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