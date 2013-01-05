<?php
include '../includes/main1.php';
include $base_path.'includes/st_and_co1.php';
include $base_path.'includes/reps.php';
include $base_path.'includes/wc1.php';

checkRepLogin();

$rep_id = $_SESSION["rep_id"];

$query="SELECT * FROM reps_areas WHERE reps_areas.rep_id='".$rep_id."' GROUP BY state, city, country;";
$result=mysql_query($query) or die("Query failed : " . mysql_error());
while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$countries[] = $line["country"];

	$states[] = $line["state"];

	if ( $line["county"] != "%" ) {
		$states[ $line["state"] ]["counties"] = $line["county"];
	}
}

if ( isset($_REQUEST["loadArea"]) ) {
	$level = $_REQUEST["nextLevel"];
	
	$showRet = false;
	$str = "";

	if ( isset($_REQUEST["prevLevels"]) && $_REQUEST["prevLevels"]!='undefined' && $_REQUEST["prevLevels"]!='' ) {
		$prevLevels = $_REQUEST["prevLevels"];
		$prevVals = $_REQUEST["prevVals"];

		if ( strpos($prevLevels, '|') !== false ) {
			$prevLevelsArr = preg_split('/\|/', $prevLevels);
			$prevValsArr = preg_split('/\|/', $prevVals);
		} else {
			$prevLevelsArr[] = $prevLevels;
			$prevValsArr[] = $prevVals;
		}
		$prev_level = $prevLevelsArr[0];
	}

	if ( $level != 'undefined' ) {//not the last level in the tree (zip)
		switch ($level) {
			case 'State':
				$next_level = 'County';
				$next_level_plural = 'Counties';
				break;
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

		$str = '<label>Select&#160;a&#160;'.$level.':&#160;</label>'.
				'<select class="areaDropdown" thisLevel="'.$level.'" nextLevel="'.$next_level.'" prevVals="'.$_REQUEST["areaVal"].'" prevLevels="'.$_REQUEST["thisLevel"].'">'.
					'<option value=""></option>';


		// START SELECT current reps areas:
		$queryRepAreas = "SELECT ".$level;
		
		if ( $next_level ) {
			$queryRepAreas .= ", ".$next_level;
		}
			
		$queryRepAreas .= " FROM reps_areas WHERE reps_areas.rep_id='".$rep_id."' AND ".$level."!='%'";
		$queryGroup = " GROUP BY $level";

		if ( count($prevLevelsArr) > 0 ) {
			$queryGroup .= ", ";

			for( $i=0; $i<count($prevLevelsArr); $i++ ) {
				$queryRepAreas .= " AND ".$prevLevelsArr[$i]."='".$prevValsArr[$i]."' ";
			
				$queryGroup .= $prevLevelsArr[$i];
				if ( $i < count($prevLevelsArr)-1 ) {
					$queryGroup .= ", ";
				}
			}
		}

		$queryRepAreas = $queryRepAreas.$queryGroup;
		$repAreas = array();

		//echo "<br />".$queryRepAreas.'<br />';exit();
		$resultRepAreas=mysql_query($queryRepAreas) or die("Query failed : " . mysql_error());

		if ( mysql_num_rows($resultRepAreas) > 0 ) {
			while ($lineArea = mysql_fetch_array($resultRepAreas, MYSQL_ASSOC)) {
				$scrubbedID = str_replace( ' ', '_', $lineArea[$level] );
				$mainID = $scrubbedID.'_'.str_replace( '|', '_', $prevVals);

				$str .= '<option>'.$lineArea[$level].'</option>';

			}
		} else {
			$showRet = true;
		}

		$str .= '</select>';

	} else {//we're down to the zip
		$showRet = true;
	}

	if ( $showRet ) {
		$str = buildAreaRetailers($prevLevelsArr, $prevValsArr);
	}

	echo $str;

	exit();
}

if ( isset($_REQUEST["foreignCountry"]) ) {
	echo buildForeignRetailers($_REQUEST["foreignCountry"]);

	exit();
}

function buildForeignRetailers($prevLevelsArr, $prevValsArr) {
	global $rep_id;

	$query = "SELECT * FROM retailer ";
	//only retailers that allow this kind of rep
	$query .= " WHERE retailer.retailer_id IN ";
	$query .= " (SELECT rrt.retailer_id FROM retailer_rep_types rrt, reps WHERE rrt.rep_type_id=reps.rep_type_id AND reps.rep_id='".$rep_id."')";

	//only retailers with rep industry in common
	$query .= " AND retailer.retailer_id IN ";
	$query .= " (SELECT rtl.retailer_id FROM retailer_type_link rtl, reps_industries ri WHERE rtl.retailer_type_id=ri.retailer_type_id AND ri.rep_id='".$rep_id."')";
	$query .= " AND retailer.country='".$_REQUEST["foreignCountry"]."' ";
	$retResult = mysql_query($query) or die("Query failed : ".$query.'<br />'. mysql_error());

	$str = "";

	if ( mysql_num_rows($retResult) > 0 ) {
		$str .= '<label>Select&#160;a&#160;Retailer:&#160;</label><select id="repSelectRetailers">';
		$str .= '<option value=""></option>';
		while ($lineRetailers = mysql_fetch_array($retResult, MYSQL_ASSOC)) {
			$str .= '<option value="'.$lineRetailers["retailer_id"].'">'.stripslashes($lineRetailers["store_name"]).'</option>';			
		}
		$str .= "</select>";
	} else {
		$str .= '<label class="error3">No&#160;retailers&#160;found.</label>';
	}

	return $str;
}

function buildAreaRetailers($prevLevelsArr, $prevValsArr) {
	$retResult = getAreaRetailers($prevLevelsArr, $prevValsArr);
	$str = "";

	if ( mysql_num_rows($retResult) > 0 ) {
		$str .= '<label>Select&#160;a&#160;Retailer:&#160;</label><select id="repSelectRetailers">';
		$str .= '<option value=""></option>';
		while ($lineRetailers = mysql_fetch_array($retResult, MYSQL_ASSOC)) {
			$str .= '<option value="'.$lineRetailers["retailer_id"].'">'.stripslashes($lineRetailers["store_name"]).'</option>';			
		}
		$str .= "</select>";
	} else {
		$str .= '<label class="error3">No&#160;retailers&#160;found.</label>';
	}

	return $str;
}

function getAreaRetailers($prevLevelsArr, $prevValsArr) {
	global $rep_id;

	$query = "SELECT * FROM retailer ";
	//only retailers that allow this kind of rep
	$query .= " WHERE retailer.retailer_id IN ";
	$query .= " (SELECT rrt.retailer_id FROM retailer_rep_types rrt, reps WHERE rrt.rep_type_id=reps.rep_type_id AND reps.rep_id='".$rep_id."')";

	//only retailers with rep industry in common
	$query .= " AND retailer.retailer_id IN ";
	$query .= " (SELECT rtl.retailer_id FROM retailer_type_link rtl, reps_industries ri WHERE rtl.retailer_type_id=ri.retailer_type_id AND ri.rep_id='".$rep_id."')";
	

	if ( count($prevLevelsArr) > 0 ) {
			$useCounty = "";
			$useState = "";

			for( $i=0; $i<count($prevLevelsArr); $i++ ) {
				if ( strtolower($prevLevelsArr[$i]) == 'county' ) {
					$useCounty = $prevValsArr[$i];
				} else {
					$query .= " AND retailer.".$prevLevelsArr[$i]."='".$prevValsArr[$i]."' ";

					if ( strtolower($prevLevelsArr[$i]) == 'state' ) {
						$useState = $prevValsArr[$i];
					}					
				}
			}

			if ($useCounty != "") {
				$query .= " AND retailer.city IN ";
				$query .= " (SELECT zip_codes.city FROM zip_codes WHERE zip_codes.county='".$useCounty."' AND zip_codes.state='".$useState."')";
			}
		}

	$query .= " ORDER BY store_name ASC ";
	//echo '<br /><br />'.$query;
	$result = mysql_query($query) or die("Query failed : ".$query.'<br />'. mysql_error());

	return $result;
}
#####################################
if ( $_REQUEST["setRetailer"] ) {
    $queryRet = "SELECT * FROM retailer WHERE retailer_id='".$_REQUEST["setRetailer"]."'";
    $resultRet = mysql_query($queryRet) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($resultRet, MYSQL_ASSOC)) {
        $retailer_id = $line["retailer_id"];
        $retailer_status = $line["retailer_status"];
		$retailer_name = stripslashes( $line["store_name"] );
		$retailer_store_type = $line["store_type"];
	}
    
    if($retailer_id != "") {
        $_SESSION["wc_user"] = $retailer_id;
        $_SESSION["wc_status"] = $retailer_status;
		$_SESSION["retailer_name"] = $retailer_name;
		$_SESSION["retailer_store_type"] = $retailer_store_type;
		find_price_lvl($retailer_id, $retailer_status);//so that the cart gets updated

        echo 'ok';
    }
    else {
        echo 'There was a problem setting this retailer.';
    }
    
    exit();
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Sales Representative Interface</title>
<?php
include $base_path.'includes/meta1.php';
?>
    <script language="JavaScript">
        $(function() {//on doc ready
            RepRetailers.init();
        });

        var RepRetailers = new function() {
            this.init = function() {
                RepRetailers.form = $('#rep_select_retailer');
                
				RepRetailers.bindDropdowns();

				RepRetailers.bindCountrySelect();

				if ( $('option[@value!=""]','#repSelectCountry').size()==1 ) {
					$('option[@value!=""]','#repSelectCountry').attr('selected', 'selected');
					$('#repSelectCountry').trigger('change');
				}
            }

			this.bindCountrySelect = function() {
				$('#repSelectCountry').each(function(){
					$('#repSelectCountry').change(function(){
						//remove everything except states
						$('select', '#repRetailersWrapper').remove();
						$('label', '#repRetailersWrapper').remove();

						if ( $('#repUseWrapper').is(":visible") ) {
							$('#repUseWrapper').fadeOut(300);
						}

						if ( $(this).val()=='US' ) {
							if ( $('#repSelectState').parent().is(':hidden') ) {
								$('#repSelectState').val('');
								$('#repSelectState').parent().fadeIn(300);
							}
						} else {

							if ( $('#repSelectState').parent().is(':visible') ) {
								$('#repSelectState').parent().fadeOut(300, function(){
									$('#repSelectState').val('');
								});
							}

							if ( $(this).val()!='' ) {

								$('.loading').small_spinner();

								$.post("index.php", 
									{
									foreignCountry: $('#repSelectCountry').val()
									}
									, function(resp){
										if ( resp != '' ) {
											$('#repRetailersWrapper').append( resp );
									
											RepRetailers.bindDropdowns();

											if ( $('#repRetailersWrapper').is(":hidden") ) {
												$('#repRetailersWrapper').fadeIn(300, function(){
												});
											}
											
											RepRetailers.selectRetailersInit();										
										}
										$('.loading').html('');
									}
								);							
							}
						}
					});
				});
			}

			this.bindDropdowns = function() {
				$('.areaDropdown').unbind().change(function(){
					var $thisDD = $(this);

					//remove options which appear after the one just changed
					var ddIdx = $('.areaDropdown', '#repRetailersWrapper').index(this);
					$('select:gt('+ddIdx+')', '#repRetailersWrapper').remove();
					$('label:gt('+ddIdx+')', '#repRetailersWrapper').remove();

					if ( $('#repUseWrapper').is(":visible") ) {
						$('#repUseWrapper').fadeOut(300);
					}

					if ( $thisDD.val() != '' ) {
                        $('.loading').small_spinner();
                        
						var usePrevVal = "";
						var usePrevLevel = "";
						
						if ( $thisDD.attr('prevVals') ) {//compile prevVals/Levels from 
							//usePrevVal = $thisDD.val() + "|" + $thisDD.attr('prevVals');
							//usePrevLevel = $thisDD.attr('thisLevel') + "|" + $thisDD.attr('prevLevels');

							//the plus 1 is added for the State dropdown
							$('.areaDropdown:lt('+ (ddIdx + 1) +')').each(function(){
								if ( usePrevVal=="" ) {
									usePrevVal = $(this).val();
								} else {
									usePrevVal = $(this).val() + '|' + usePrevVal;
								}

								if ( usePrevLevel=="" ) {
									usePrevLevel = $(this).attr('thisLevel');
								} else {
									usePrevLevel = $(this).attr('thisLevel') + '|' + usePrevLevel;
								}
							});
						}
												
						if ( usePrevVal=="" ) {
							usePrevVal = $thisDD.val();
						} else {
							usePrevVal = $thisDD.val() + "|" + usePrevVal;
						}

						if ( usePrevLevel=="" ) {
							usePrevLevel = $thisDD.attr('thisLevel');
						} else {
							usePrevLevel = $thisDD.attr('thisLevel') + "|" + usePrevLevel;
						}

						
                        $.post("index.php", {
							loadArea:1, 
							areaVal:$thisDD.val(), 
							thisLevel:$thisDD.attr('thisLevel'), 
							nextLevel:$thisDD.attr('nextLevel'),
							prevLevels:usePrevLevel,
							prevVals: usePrevVal
						}, function(resp){
							if ( resp != '' ) {
								
								$('#repRetailersWrapper').append( resp );
								
								RepRetailers.bindDropdowns();

								if ( $('#repRetailersWrapper').is(":hidden") ) {
									$('#repRetailersWrapper').fadeIn(300);
								}
								
								RepRetailers.selectRetailersInit();
							}
                            $('.loading').html('');
                        });
                    }
                });
			}

            this.selectRetailersInit = function() {
                $('#repSelectRetailers').unbind().change(function(){
					if ( $(this).val()!="" ) {
						if ( $('#repUseWrapper').is(":hidden") ) {
							$('#repUseWrapper').unbind().fadeIn(200, function(){
								$('#useThisRetailer').click(function(){
									$(this).attr('disabled', 'true');
									var post_url = RepRetailers.form.attr('action');
									$.post(post_url, { setRetailer: $('#repSelectRetailers').val() }, function(resp){
										if ( resp == 'ok' ) {
											window.location.href=window.location.href;
										}
										else {
											$('#sub_menu').html( resp );
											$(this).removeAttr('disabled');
										}
									})
										return false;
								});
							});
						}
					} else {
						if ( !$('#repUseWrapper').is(":hidden") ) {
							$('#repUseWrapper').fadeOut(200);
						}
					}
                })
            }
        }

    </script>

	<style type="text/css">
		.areaDropdown { margin-right: 20px; margin-bottom: 10px; }
	</style>
</head>
<body>

<div align="center">

<?php
include $base_path.'includes/reps_head1.php';
?>


<table border="0">
    <tr><td>&#160;</td></tr>

<?php

//Error Messages
if($error_txt) {
	echo '<tr><td><span class="error">$error_txt</span></td></tr>\n';
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>
</table>

<span class="style4 two"><?php echo $website_title; ?>: Your Retailers 
	<span id="loadingWrapper" style="width:30px; padding-left: 10px">
        &#160;<span class="loading"></span>
    </span>
</span>
<br /><br />


<form id="rep_select_retailer" action="index.php" method="POST">
	<div class="left" style="margin-right: 20px; margin-bottom: 10px;">
		<label>Select a Country: </label><select name="repSelectCountry" id="repSelectCountry"><option value=""></option>
		<?php
				$rowcnt = 0;
				
				$queryCt = "SELECT * FROM countries GROUP BY code, name ORDER BY country_id, name";
				$resultCt = mysql_query($queryCt) or die("Query failed : " . mysql_error());
				while ($lineCt = mysql_fetch_array($resultCt, MYSQL_ASSOC)) {
					if ( $states && in_array( $lineCt["code"], $countries ) ) {
						echo '<option value="'.$lineCt["code"].'">'.$lineCt["name"].'</option>';
					}
				}
		?></select>
	</div>
   

	<div class="left no_display">
        <label>Select a State: </label><select name="repSelectState" id="repSelectState" class="areaDropdown" thisLevel="State" nextLevel="County"><option value=""></option>
        <?php
        	$rowcnt = 0;
			
			$querySt = "SELECT * FROM states WHERE country_id='1'";//get US only
			$resultSt = mysql_query($querySt) or die("Query failed : " . mysql_error());
			while ($lineSt = mysql_fetch_array($resultSt, MYSQL_ASSOC)) {
				if ( $states && in_array( $lineSt["code"], $states ) ) {
					echo '<option value="'.$lineSt["code"].'">'.$lineSt["name"].'</option>';
				}
			}
        ?></select>
    </div>
   
    <div class="left" style="display:none" id="repRetailersWrapper"></div>

    <div class="left" id="repUseWrapper" style="padding-left: 10px; display:none">
        <button id="useThisRetailer">Use This Retailer</button>
    </div>
<br />
</form>
<br class="clear" />
<?php
include $base_path.'includes/reps_foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>