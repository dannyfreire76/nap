<?php

include('includes/main1.php');

$min_correct = 7;
$max_discount = 40;
$total_q = 10;

if ( $_REQUEST["submittal"] ) {
    $q_num = $_REQUEST["q_num"];
    $msg = "";

	foreach ($_REQUEST as $elem_name => $aid) {
		if ( strpos($elem_name, 'rad_')===0 ) {
			$qid = substr( $elem_name, (strpos($elem_name, 'rad_')+4) );
			$query2 = "SELECT aid, answer_explained from quiz_questions WHERE qid = '".$qid."'";
			$result2 = mysql_query($query2);
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                $msg .=  '<div class="bold" id="q_response">';
				if ( $aid==$line2["aid"] ) {//user got the answer right
					$_SESSION["num_correct_answers"] = $_SESSION["num_correct_answers"] + 1;
                    $msg .= '<b>That\'s right!</b>';

				}
                else {
                    $msg .= '<b>Sorry, that\'s incorrect.</b>';
                }
                $msg .=  '</div>';
                $msg .= $line2["answer_explained"].'<br />';
                if ( $q_num != $total_q ) {
                    $msg .= '<div class="right next_question" id="next_question'.$q_num.'"><a href="javascript:void(0)" class="bold" last_q="'.$q_num.'" next_q="'.($q_num+1).'">Next question >></a></div>';
                }
			}
		}
	}
    
    echo $msg;

    if ( $q_num == $total_q ) {
        echo '<br /><div id="results">';
        if ( $_SESSION["num_correct_answers"] >= $min_correct ) {
            $this_discount = $max_discount - ( ($total_q - $_SESSION["num_correct_answers"]) * 10 );

            //echo $max_discount.', '.$total_q.', '.$_SESSION["num_correct_answers"].'<br />';
            $discount_code = 'quiz'.rand(1000, 9999).$this_discount;
            $location_target = 'QUIZ'.$this_discount;
            $now = date("Y-m-d H:i:s");
            $exp_days = 30;

            $quiz_disc = "INSERT INTO discount_codes SET created='$now', status='1', discount_code='$discount_code', percent_off='".($this_discount/100)."', location_target='$location_target', expire_days=$exp_days";
            $resultQuiz = mysql_query($quiz_disc) or die("Query failed : " . mysql_error());

            if ( $resultQuiz ) {
                echo 'You answered '.$_SESSION["num_correct_answers"].' of '.$total_q.' questions correctly.';
                echo '<br /><br />';
                echo 'Congratulations! You\'ve earned discount code <span class="bold discount_code">'.$discount_code.'</span>, good for <span class="bold">'.$this_discount.'% off</span>, for the next '.$exp_days.' days.';
                if ( $_SESSION["num_correct_answers"] < $total_q ) {
                    echo '<p><div class="ul bold text_center hand" onClick="javascript:Main.openGame()">Try for an even deeper discount now!</div></p>';
                }
                $_SESSION["discount_code"] = $discount_code;
            }
        }
        else {
            echo 'Sorry, you only answered '.$_SESSION["num_correct_answers"].' question'.($_SESSION["num_correct_answers"]>1?'s':'').' correctly.  Discounts start at '.$min_correct.' correct answers.<p>';
            echo '<div class="try_again ul bold text_center hand" onClick="javascript:Main.openGame()">Try again now!</div>';
            echo '</p>';
            $_SESSION["discount_code"] = null;
        }
        echo '</div>';
    }
	exit();
}
else {
	$_SESSION["num_correct_answers"] = 0; 

	echo '<div><img class="right hand close" alt="click to close" title="click to close" src="/images/close.gif" /></div>';
	echo '<div id="inner_questions" class="clear">';
	echo '<form name="quiz_form">';
	$master_cnt = 0;
    $total_questions = 10;
	$queryQ = "SELECT * FROM quiz_questions ORDER BY RAND() LIMIT $total_questions";
	$resultQ = mysql_query($queryQ) or die("Query failed : " . mysql_error());
	while ($lineQ = mysql_fetch_array($resultQ, MYSQL_ASSOC)) {
		$qid = $lineQ["qid"];
		$question = $lineQ["question"];
		$asid = $lineQ["asid"];
		$aid = $lineQ["aid"];
		$master_cnt++;

        echo '<div class="q_a no_display" id ="qa_'.$master_cnt.'">';
            echo '<div id="just_qa'.$master_cnt.'">';
                //echo '<input type="text" class="anchor" id="hiddenq_'.$master_cnt.'" tabindex="-1" />';
                echo '<div id="q_'.$master_cnt.'">Question '.$master_cnt.' of '.$total_questions.'<br /><br />';
                echo $question.'</div><br /><table>';

                $queryA = "SELECT * FROM quiz_answers WHERE asid = '".$asid."' ORDER BY aid";// ORDER BY RAND()";
                $resultA = mysql_query($queryA) or die("Query failed : " . mysql_error());
                while ($lineA = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
                    $aid = $lineA["aid"];
                    $asid = $lineA["asid"];
                    $answer = $lineA["answer"];

                    echo '<tr valign="top"><td><input type="radio" name="rad_'.$qid.'" question="q_'.$master_cnt.'" value="'.$aid.'" id="id_'.$aid.'" /></td><td><label class="hand" for="id_'.$aid.'"> '.$answer.'</label></td></tr>';
                }
                echo '</table><br />';
            echo '</div>';
            echo '<div class="no_display absolute text_center load" id="load_layer'.$master_cnt.'"></div>';
            echo '<div class="q_feedback" id="q_feedback'.$master_cnt.'">';
            echo    '<center><button id="submit_quiz" question_num="'.$master_cnt.'">submit answer</button></center>';
            echo '</div>';
        echo '</div>';
    }

	echo '</form>';
	echo '</div>';
}
?>