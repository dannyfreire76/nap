<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/wc1.php';


	$queryRep = "SELECT reps.* FROM reps WHERE status='1'";
	$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
	
	while ($lineRep = mysql_fetch_array($resultRep, MYSQL_ASSOC)) {
		//echo '<br /><br />Rep '.$lineRep["rep_id"].'<br />';
		$queryRepArea = "SELECT county, state FROM reps_areas WHERE rep_id='".$lineRep["rep_id"]."' AND county!='%' GROUP BY county, state";
		$resultRepArea = mysql_query($queryRepArea) or die("Query failed : " . mysql_error());
		
		$counties = array();
		while ($lineRepArea = mysql_fetch_array($resultRepArea, MYSQL_ASSOC)) {
			
			//get city count from rep_areas
			$queryRepAreaCnt = "SELECT COUNT(DISTINCT(city)) AS cityCnt FROM reps_areas ";
			$queryRepAreaCnt .= " WHERE rep_id='".$lineRep["rep_id"]."' AND state='".$lineRepArea["state"]."' AND county='".$lineRepArea["county"]."' AND city!='%' AND zip='%'";

			//echo "queryRepAreaCnt 1: ".$queryRepAreaCnt.'<br />';
			$resultRepAreaCnt = mysql_query($queryRepAreaCnt) or die("queryRepAreaCnt failed: " . mysql_error().'<br />'.$queryRepAreaCnt);
			while ($lineRepAreaCnt = mysql_fetch_array($resultRepAreaCnt, MYSQL_ASSOC)) {
				$repCitiesCnt = $lineRepAreaCnt["cityCnt"];
			}

			//get city count from zip_codes
			$queryAreaCnt = "SELECT COUNT(DISTINCT(city)) AS cityCnt FROM zip_codes ";
			$queryAreaCnt .= " WHERE state='".$lineRepArea["state"]."' AND county='".$lineRepArea["county"]."'";

			//echo "queryAreaCnt 2: ".$queryAreaCnt.'<br />';
			$resultAreaCnt = mysql_query($queryAreaCnt) or die("Query failed : " . mysql_error());
			while ($lineAreaCnt = mysql_fetch_array($resultAreaCnt, MYSQL_ASSOC)) {
				$areaCitiesCnt = $lineAreaCnt["cityCnt"];
			}
			//echo '<b>'.$repCitiesCnt.' :: '.$areaCitiesCnt.'</b><br />';

			//rep has all cities in a given county and state, so remove all entires and add 1 wildcard entry instead
			if ( $repCitiesCnt==$areaCitiesCnt ) {
				$queryDelArea = "DELETE FROM reps_areas WHERE rep_id='".$lineRep["rep_id"]."' AND state='".$lineRepArea["state"]."' AND county='".$lineRepArea["county"]."' AND city!='%' AND zip='%'";
				$delRes = mysql_query($queryDelArea) or die("Delete query failed: " . mysql_error());

				if ($delRes) {
					$queryInsertArea = "INSERT INTO reps_areas (rep_id, state, county, city, zip) VALUES ('".$lineRep["rep_id"]."', '".$lineRepArea["state"]."', '".$lineRepArea["county"]."', '%', '%')";
					mysql_query($queryInsertArea) or die("Insert query failed: " . mysql_error());
				}
			}
		}

	
	
	
	}

?>