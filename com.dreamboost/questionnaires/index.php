<?php
// BME WMS
// Page: Supplement Facts page
// Path/File: /sup_facts/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include $base_path.'includes/customer.php';

//checkCustomerLogin();

if ( $_REQUEST["submittal"] ) {
	$errors = false;
	$query = "INSERT INTO questionnaires_complete (quid, qhid) VALUES('".$member_id."', '".$_REQUEST["qhid"]."')";
	$result = mysql_query($query);

	if ( $result ) {
		$qcid = mysql_insert_id();
		foreach ($_GET as $elem_name => $aid) {
			if ( strpos($elem_name, 'q_')===0 ) {
				$qid = substr( $elem_name, (strpos($elem_name, 'q_')+2) );
				$answer = '';
				if ( !is_numeric($aid) ) {//if it's a string, it's not an answer in the db, but a typed answer
					$answer = $aid;
					$aid = '';
				}
				$query2 = "INSERT INTO questionnaires_results (qcid, qid, aid, answer) VALUES('".$qcid."', '".$qid."', '".$aid."', '".$answer."')";
				$result2 = mysql_query($query2);

				if ( !$result2 ) {
					$errors = true;
				}
			}
		}
	}
	else {
		$errors = true;
	}

	if ( $errors ) {
		echo 'Sorry, there was an unspecified error.';

	}
	else {
		echo 'ok';
	}

	exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Dream Boost Questionnaires | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<script src="../includes/questionnaires.js" type="text/javascript"></script>
<script src="../includes/wmsform.js" type="text/javascript"></script>
</head>
<body>

<?php
include '../includes/head1.php';



$qhid = $_GET["qhid"];

echo '	<div id="q_loading"></div>
		<table border="0" width="100%">
			<tr>
				<td align="left">
					<img alt="Questionnaires" src="'.$current_base.'images/Questionnaires.gif" id="title_image" />
				</td>';
echo '
			</tr>
		</table><br />';

if ( !$member_id ) {
	echo "<div class=\"style2\">Please <a onclick=\"$('#login').trigger('click'); return false;\" href=\"javascript:void(0)\">log in</a> to access questionnaires.</div>";
}
else {
	if ( $_GET["thanks"] ) {
		echo '<div class="thanks style2">Thanks for completing our questionnaire!</div><br />';
	}


    ///////////////////////////////////////////////////////
    if ( !$qhid ) {
        $queryQ1 = "SELECT q_disc_code FROM members WHERE member_id = '".$member_id."' AND q_disc_code IS NOT NULL AND q_disc_code != '';";
        $resultQ1 = mysql_query($queryQ1) or die("Query failed : " . mysql_error());
        $enough_q_complete = true;

        if ( mysql_num_rows($resultQ1)>0 ) {//user already has a code
            while ($lineQ1 = mysql_fetch_array($resultQ1, MYSQL_ASSOC)) {

                $queryCode = "SELECT * FROM discount_codes WHERE status='1' AND discount_code='".$lineQ1["q_disc_code"]."'";
                $resultCode = mysql_query($queryCode) or die("Query failed : " . mysql_error());
                if ( mysql_num_rows($resultCode)>0 ) {
                    while ($lineCode = mysql_fetch_array($resultCode, MYSQL_ASSOC)) {
                        if ( $lineCode["expire_days"] && $lineCode["expire_days"]!=0 ) {
                            $code_created = strtotime($lineCode["created"]);
                            $todays_date = date("Y-m-d");
                            $today = strtotime($todays_date);
                            $exp_days = $lineCode["expire_days"];
                            $exp_sec = $exp_days * 60 * 60 * 24;

                            $days_left = floor( ($code_created + $exp_sec  - $today ) / (60 * 60 * 24) );
                            if ( $days_left > 0 ) {
                                echo 'You currently have discount code <span class="bold">'.$lineQ1["q_disc_code"].'</span> active, good for <span class="bold">'.($lineCode["percent_off"]*100).'% off</span>, for the next '.$days_left.' days.';
                            }
                        }
                    }

                }
                else {
                    //remove code from user
                    $quest_disc2 = "UPDATE members SET q_disc_code = NULL WHERE member_id = '".$member_id."';";
                    $resultQuest2 = mysql_query($quest_disc2) or die("Query failed : " . mysql_error());
                }
            }
        } else {//no code for this questionnaire user already, insert or tell user how many more they need
            
            echo "<div>";
            $more_q_needed = array();

            $queryQ = "SELECT count( questionnaires_complete.qhid ) AS qcnt, questionnaire_header.qhid, disc_comp_num, questionnaire_header.title FROM questionnaires_complete RIGHT JOIN questionnaire_header ON questionnaires_complete.qhid = questionnaire_header.qhid AND questionnaires_complete.quid = '".$member_id."' GROUP BY qhid ORDER BY qhid";
            $resultQ = mysql_query($queryQ) or die("Query failed : " . mysql_error());
            if ( mysql_num_rows($resultQ)>0 ) {
                while ($lineQ = mysql_fetch_array($resultQ, MYSQL_ASSOC)) {
                    if ( $lineQ["qcnt"] < $lineQ["disc_comp_num"] ) {
                        $enough_q_complete = false;
                        $more_q_needed[ $lineQ["qhid"] ]["completed"] = false;
                        $more_q_needed[ $lineQ["qhid"] ]["num"] = ($lineQ["disc_comp_num"] - $lineQ["qcnt"]);
                    }
                    else {
                        $more_q_needed[ $lineQ["qhid"] ]["completed"] = true;
                    }
                }
            }
            else {
                $enough_q_complete = false;
            }
            
            if ( $enough_q_complete ) {
                $this_discount = 50;
                $discount_code = 'qst'.rand(1000, 9999).$this_discount;
                $location_target = 'QUEST'.$this_discount;
                $now = date("Y-m-d H:i:s");
                $exp_days = 90;

                $quest_disc = "INSERT INTO discount_codes SET created='$now', status='1', discount_code='$discount_code', percent_off='".($this_discount/100)."', location_target='$location_target', expire_days=$exp_days";
                $resultQuest = mysql_query($quest_disc) or die("Query failed : " . mysql_error());

                if ( $resultQuest ) {
                    $quest_disc2 = "UPDATE members SET q_disc_code = '".$discount_code."' WHERE member_id = '".$member_id."';";
                    $resultQuest2 = mysql_query($quest_disc2) or die("Query failed : " . mysql_error());

                    if ( $resultQuest2 ) {
                        echo 'Congratulations! You\'ve earned discount code <span class="bold">'.$discount_code.'</span>, good for <span class="bold">'.$this_discount.'% off</span>, for the next '.$exp_days.' days.';

                        $_SESSION["active_discount_code"] = $discount_code;
                    }
                }
            }
            else {
                echo 'You will receive a discount code when <span class="ul">all</span> outstanding questionnaires as indicated below have been completed.';
            }


            echo "</div><br />";
        }
    }
    //////////////////////////////////////////////////////////////


	if ( !$qhid ) {//no questionnaire selected
		echo '<ul class="qul text_left style2">';

		$query = "SELECT * FROM questionnaire_header ORDER BY seq ASC";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo '<li><a href="'.$_SERVER["SCRIPT_NAME"].'?qhid='.$line["qhid"].'">'.$line["title"].'</a>';

			$queryC = "SELECT qcid FROM questionnaires_complete WHERE qhid = '".$line["qhid"]."' AND quid = '".$member_id."' ORDER BY qcid ASC";
			$resultC = mysql_query($queryC) or die("Query failed : " . mysql_error());
			if ( mysql_num_rows($resultC)>0 ) {
				echo " (completed:";
				$q_completed  = 0;
				while ($lineC = mysql_fetch_array($resultC, MYSQL_ASSOC)) {
					$q_completed++;
					echo '&#160;&#160;<a href="index.php?qhid='.$line["qhid"].'&qcid='.$lineC["qcid"].'">'.$q_completed.'</a>';
				}

				echo ")";
            }

			if ( !$enough_q_complete ) {
				if ( !$more_q_needed[ $line["qhid"] ]["completed"] ) {
					if ( $more_q_needed[ $line["qhid"] ]["num"] ) {
						$num_needed = $more_q_needed[ $line["qhid"] ]["num"];
					}
					else {
						$num_needed = $line["disc_comp_num"];
					}
					echo "&#160;&#160;&#160;*".$num_needed." more needed for discount";
				}
			}
			echo '</li>';
		}
		mysql_free_result($result);
		echo '</ul>';
	}
	else {//questionnaire selected
		echo '<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'" name="main_q" id="main_q" class="text_center">
			  <input type="hidden" name="submit_qhid" id="submit_qhid" value="'.$qhid.'" />';
		$query = "SELECT qhid, intro, seq FROM questionnaire_header WHERE qhid='".$qhid."'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo '<span class="style4"><br />'.$line["intro"].'</span>';

			if ( $_GET["qcid"] )  {
				$queryCom = "SELECT created FROM questionnaires_complete WHERE qcid='".$_GET["qcid"]."'";
				$resultCom = mysql_query($queryCom) or die("Query failed : " . mysql_error());
				while ($lineCom = mysql_fetch_array($resultCom, MYSQL_ASSOC)) {
					$datetime = strtotime($lineCom["created"]);
					echo '<div class="style2">(Completed: '.date("m/d/y", $datetime).')</div>';
				}
			}
			
			echo '<table border="0" width="80%" cellpadding="4" cellspacing="0" align="center" class="questionnaire"><tr><td>&nbsp;</td></tr>';
			$q_overall = 0;

			$query2 = "SELECT qsid, qhid, label, seq FROM questionnaire_qsets WHERE qhid='".$line["qhid"]."' ORDER BY seq ASC";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				echo '<tr><td><!-- question cell --></td><td class="style2 qlabel">'.$line2["label"].'</td></tr>';

				$query3 = "SELECT qid, qsid, question, asid, qtype, dep_qid, dep_aid, seq FROM questionnaire_questions WHERE qsid='".$line2["qsid"]."' ORDER BY seq ASC";
				$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
				$q_in_this_section = 0;

				while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					$q_in_this_section++;
					$this_colspan = 1;
					$q_overall++;
					$row_class = 'odd';

					if ( $q_overall%2 == 0 ) {
						$row_class = 'even';
					}

					if ( $line3["dep_qid"] != '' && $line3["dep_aid"] != '' ) {
						$this_row = 'qrow_'.$line3["qid"];
						$dep_q = 'q_'.$line3["dep_qid"];
						$dep_id = 'qid'.$line3["dep_qid"].'_aid'.$line3["dep_aid"];
						echo '
							<script language="JavaScript">
								$("input[name='.$dep_q.']").click( function(){
									Quest.checkDependent("'.$dep_q.'", "'.$dep_id.'", "'.$this_row.'");
								} )

								$(function() {//on doc ready
									Quest.checkDependent("'.$dep_q.'", "'.$dep_id.'", "'.$this_row.'");
								});
							</script>
							';
					}

					if ( $line2["label"] == '' && $q_in_this_section > 1 ) {//if there's no label for this section, they're distinct questions, so space them out
						echo '<tr><td class="qlabel">&#160;</td></tr>';
					}

					if ( $line3["qtype"]=='text' ) {
						echo '<tr class="'.$row_class.'"><td class="style2" colspan="2" align="left">'.$line3["question"].'</td></tr><tr class="'.$row_class.'">';
						$this_colspan=2;
					}
					else {
						echo '<tr id="qrow_'.$line3["qid"].'" class="'.$row_class.'"><td class="style2 question" align="right">'.$line3["question"].'</td>';
					}
					//begin answer cell
					echo '<td class="style2" colspan="'.$this_colspan.'">';

					$c_answer = '';
					$c_answer_val = '';
					if ( $_GET["qcid"] )  {
						$query5 = "SELECT aid, answer FROM questionnaires_results WHERE qcid='".$_GET["qcid"]."' AND qid='".$line3["qid"]."'";
						$result5 = mysql_query($query5) or die("Query failed : " . mysql_error());
						while ($line5 = mysql_fetch_array($result5, MYSQL_ASSOC)) {
							$c_answer = $line5["aid"];
							$c_answer_val = $line5["answer"];
						}
					}
					
					$query4 = "SELECT aid, asid, answer_val, seq FROM questionnaire_answers WHERE asid='".$line3["asid"]."' ORDER BY seq ASC";
					$result4 = mysql_query($query4) or die("Query failed : " . mysql_error());
					$result4b = mysql_query($query4) or die("Query failed : " . mysql_error());
					if ($result4) {
						//we could o a separate query to get the total number of answers, but meh
						$total_answers = 0;
						while ($line4 = mysql_fetch_array($result4, MYSQL_ASSOC)) {
							$total_answers++;
						}
						mysql_free_result($result4);
						
						$col_width = 100/$total_answers;

						echo '<table class="answer_set"><tr>';
						$answ_count = 0;
						while ($line4 = mysql_fetch_array($result4b, MYSQL_ASSOC)) {
							$answ_count++;
							$fieldname = 'q_'.$line3["qid"];
							$fieldid = 'qid'.$line3["qid"].'_aid'.$line4["aid"];
							$disabled_attr = '';
							$selected_attr = '';
							$answer_class = '';
							$text_answer_class = '';
							if ( $_GET["qcid"] )  {
								$disabled_attr = ' disabled="1"';
								$text_answer_class = 'right_answer_text';
								if ( $c_answer == $line4["aid"] ) {
									$selected_attr = ' checked="1"';
									$answer_class = 'right_answer';
								}
							}
							echo '<td align="center" class="'.$answer_class.'" colspan="'.$this_colspan.'" width="'.$col_width.'%">';
								if ( $line3["qtype"]=='radio' ) {
									echo '<input type="radio" name="'.$fieldname.'" id="'.$fieldid.'" value="'.$line4["aid"].'"';
									echo $disabled_attr;
									echo $selected_attr;
									echo ' />&#160;';
								}
								else if ( $line3["qtype"]=='text' ) {
									echo '<textarea name="'.$fieldname.'" class="qtextbox '.$text_answer_class.'"';
									echo $disabled_attr;
									echo '>';
									echo $c_answer_val;
									echo '</textarea>';
								}
							echo '<label for="'.$fieldid.'" class="hand">'.$line4["answer_val"].'</label></td>';
						}
						echo '</tr></table>';
					}
					mysql_free_result($result4b);

					//end answer cell
					echo '</td></tr>';
				}
				mysql_free_result($result3);
			
			}
			mysql_free_result($result2);
		}
		mysql_free_result($result);

		echo '</table>';

		if ( !$_GET["qcid"] )  {
			echo '<input type="button" class="style2" name="q_submit" id="q_submit" value="Submit Questionnaire" />';
		}
		echo '</form>';
	}
}
?>

<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>