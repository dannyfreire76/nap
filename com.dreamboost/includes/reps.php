<?php
include_once('main1.php');

if ( $_REQUEST["getAreaData"] ) {
	buildArea( $_REQUEST["previous_level"], $_REQUEST["previous_value"], $_REQUEST["this_level"], $_REQUEST["this_key"], $_REQUEST["rep_id"], $_REQUEST["area_type"] );
	exit();
}


function checkRepLogin() {
	global $base_url;
    if( !$_SESSION["rep_id"] ) {
        header("Location: ".$base_url."reps/");
        exit;
    }
}

function buildArea($prevLevels,$prevVals, $level, $key, $rep_id, $areaType) {
	if ( strpos($prevLevels, '|') !== false ) {
		$prevLevelsArr = preg_split('/\|/', $prevLevels);
		$prevValsArr = preg_split('/\|/', $prevVals);
	} else {
		$prevLevelsArr[] = $prevLevels;
		$prevValsArr[] = $prevVals;
	}
	$prev_level = $prevLevelsArr[0];


	switch ($level) {
		case 'County':
			$next_level = 'City';
			$next_level_plural = 'Cities';
			break;
		case 'City':
			$next_level = 'Zip';
			$next_level_plural = 'Zips';
			break;
		case 'Zip':
			$next_level = null;
			break;
	}

	$str = "<table>";



	// START SELECT current reps areas:
	$queryRepAreas = "SELECT ".$level;
	
	if ( $next_level ) {
		$queryRepAreas .= ", ".$next_level;
	}
		
	$queryRepAreas .= " FROM reps_areas WHERE reps_areas.rep_id='".$rep_id."'";
	$queryGroup = " GROUP BY $level, ";

	for( $i=0; $i<count($prevLevelsArr); $i++ ) {
		$queryRepAreas .= " AND ".$prevLevelsArr[$i]."='".$prevValsArr[$i]."' ";
	
		$queryGroup .= $prevLevelsArr[$i];
		if ( $i < count($prevLevelsArr)-1 ) {
			$queryGroup .= ", ";
		}
	}

	$queryRepAreas = $queryRepAreas.$queryGroup;
	$repAreas = array();
//echo "queryRepAreas: ".$queryRepAreas.'<br />';
	$resultRepAreas=mysql_query($queryRepAreas) or die("Query failed : " . mysql_error());
	while ($lineRepAreas=mysql_fetch_array($resultRepAreas, MYSQL_ASSOC)) {
		$repAreas[] = $lineRepAreas[$level];

		if ( $lineRepAreas[$next_level] != "%" ) {
			$repAreasInternal[ $lineRepAreas[$level] ][$next_level_plural] = $lineRepAreas[$next_level];
		}
	}
	// END SELECT current reps areas:


	// SELECT this area's sub areas:
	$queryArea = "SELECT DISTINCT(".$level.") FROM zip_codes WHERE ";
	$queryAreaWhere = "";

	for( $i=0; $i<count($prevLevelsArr); $i++ ) {
		$queryArea .= $prevLevelsArr[$i]."='".$prevValsArr[$i]."' ";
		if ( $i < count($prevLevelsArr)-1 ) {
			$queryArea .= " AND ";
		}
	}
	$queryArea .= " ORDER BY ".$level;

	$resultArea = mysql_query($queryArea) or die("Query failed : " . mysql_error() .'\n'.$queryArea);
//echo 'queryArea:<br />'.$queryArea;

//echo '<pre>';
//echo var_dump($repAreas);
//echo '</pre>';

	while ($lineArea = mysql_fetch_array($resultArea, MYSQL_ASSOC)) {
		$scrubbedID = str_replace( ' ', '_', $lineArea[$level] );
		$mainID = $scrubbedID.'_'.str_replace( '|', '_', $prevVals);

		$str .= '<tr style="vertical-align:bottom"><td align="right">';
		$str .= '<label class="text_right" for="'.$mainID.'_'.$level.'">'.$lineArea[$level].'</label>';
		$str .= '</td><td>';


		$str .=		'<input type="checkbox" areaType="'.($areaType *1 + 1).'" showThis="'.$mainID.'_decisionWrapper" name="'.$mainID.'_'.$level.'" id="'.$mainID.'_'.$level.'" value="'.$lineArea[$level].'" ';
						if ( $repAreas && in_array( $lineArea[$level], $repAreas ) ) {
							$str .= ' checked';
						}
		$str.=		' />';

		$str .= '</tr>';
		
		if ( $next_level ) {
			$str .= '<tr>';

			$str .= '<td></td><td></td><td>';

			$str .=			'<div class="no_display" id="'.$mainID.'_decisionWrapper">';

			$str .=				'<table>';
			$str .=					'<tr valign="top">';
			$str .=						'<td>';
			$str .=							'<input type="radio" hideThis="'.$mainID.'_'.$next_level.'Wrapper" name="'.$mainID.'_AllOr'.$next_level.'" id="'.$mainID.'_AllOr'.$next_level.'_A" value="all" checked';

			$str .=							'/><label for="'.$mainID.'_AllOr'.$next_level.'_A"> Entire '.$level.'</label>';
			$str .=						'</td>';
			$str .=						'<td>';

			$str .=							'<input type="radio" showThis="'.$mainID.'_'.$next_level.'Wrapper" name="'.$mainID.'_AllOr'.$next_level.'" id="'.$mainID.'_AllOr'.$next_level.'_S" value="specific"';
									if ( sizeof( $repAreasInternal[ $lineArea[$level] ][$next_level_plural] ) >0 ) {
										$str .= ' checked';
									}
			$str .=							'/><label for="'.$mainID.'_AllOr'.$next_level.'_S"> '.$next_level_plural.' In</label>';
			$str .=							'<div class="no_display threesides" id="'.$mainID.'_'.$next_level.'Wrapper" level="'.$next_level.'" prevVal="'.$lineArea[$level].'|'.$prevVals.'" prevLevel="'.$level.'|'.$prevLevels.'" key="'.$scrubbedID.'" areaType="'.($areaType *1 + 1).'">';
			$str .=							'</div>';
			$str .=						'</td>';
			$str .=					'</tr>';
			$str .=				'</table>';

			$str .=			'</div><!-- decisionWrapper -->';



			$str .= '</td></tr>';
		}
	}

	$str .= '</table>';

	echo $str;
}

?>