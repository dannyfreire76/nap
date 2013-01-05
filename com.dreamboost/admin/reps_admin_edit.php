<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/st_and_co1.php';
include '../includes/common.php';

include '../includes/db.class.php';
$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}
$rep_id = "";
$rep_zips = array();
$rep_states = array();
$rep_cities = array();
$rep_pcts = array();
$selected_rep_inds = array();

foreach($_REQUEST as $key => $value)
{
	if ( strpos($key, 'rep_pct_')!==false ) {
		$rep_pct_vals = split("\|", $value);
        $rep_pcts[ $rep_pct_vals[0] ]=$rep_pct_vals[1];
    }

	if ( strpos($key, 'selected_rep_industries')!==false ) {
		$selected_rep_inds = split(',',$value);
	}

    $$key = $value;
	//echo '<br />'.$key.' = '.$value;
} 

if( $_REQUEST["action"]=='genpass' ) {// Generate New Password
    include './includes/password/class.password.php';
    $pas = new password();
    $pas->specchar = false;
    $newpass = $pas->generate();

    echo $newpass;
    exit();
}

include './includes/wms_nav1.php';

function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please find the login details ";
		$email_str .= "for your " . $website_title . " Sales Representative account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "reps/\n\n";
						
		$subject = "New " . $website_title . " Sales Representative Login Info";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

if($_REQUEST["checkname"] != "") {
    $queryName = "SELECT * FROM reps WHERE username='".$_REQUEST["username"]."' AND rep_id !='".$rep_id."'";

	//echo $queryName;
    $resultName = mysql_query($queryName) or die("Query failed : " . mysql_error());
    if ( mysql_num_rows($resultName)>0 ) {
        echo 'That Username is already taken.  Please try again.';
    }
    else {
        echo 'ok';
    }

    exit();
}


if($_GET["submit"] != "") {

	$query = "";
	$queryA = "";
	$queryB = "";
    $pw_set = false;

    foreach($_POST as $fld_name=>$fld_val) {
        if ( $fld_name != "submit" && strpos($fld_name, "areaRow_")===false && strpos($fld_name, "confirm_new")===false && strpos($fld_name, "rep_pct")===false && strpos($fld_name, "selected_rep_industries")===false && strpos($fld_name, "sendMailChk")===false && strpos($fld_name, "rep_countries")===false ) {
            if ($rep_id) {
                if ( strpos($fld_name, "_date")!==false ) {                
                    $query .= $query == "" ? "" : " , ";
					if ( $fld_val ) {
	                    $query .= " ".$fld_name."=STR_TO_DATE('".$fld_val."','%m/%d/%Y') ";
					} else {
						$query .= " ".$fld_name."=NULL ";
					}
                } else if ( strpos($fld_name, "new_pw")!==false ) {
                    if ($fld_val!='') {
                        $query .= $query == "" ? "" : " , ";
                        $query .= " password= '".md5($fld_val)."' ";
                        $pw_set = true;
                    }
                } else {
                    $query .= $query == "" ? "" : " , ";
                    $query .= " ".$fld_name."='".$fld_val."' ";
                }
            }
            else {                
                if ( strpos($fld_name, "rep_id")===false ) {
                    if ( strpos($fld_name, "_date")!==false ) {                
                        $queryA .= $queryA == "" ? "" : " , ";
                        $queryB .= $queryB == "" ? "" : " , ";
                        
                        $queryA .= " ".$fld_name." ";
						if ( $fld_val ) {
	                        $queryB .= " STR_TO_DATE('".$fld_val."','%m/%d/%Y') ";
						} else {
							$queryB .= " NULL ";
						}

                    } else if ( strpos($fld_name, "new_pw")!==false ) {
                        if ($fld_val!='') {
                            $queryA .= $queryA == "" ? "" : " , ";
                            $queryB .= $queryB == "" ? "" : " , ";
                            
                            $queryA .= " password ";
                            $queryB .= " '".md5($fld_val)."' ";

                            $pw_set = true;
                        }
                    } else {
                        $queryA .= $queryA == "" ? "" : " , ";
                        $queryB .= $queryB == "" ? "" : " , ";
                        
                        $queryA .= " ".$fld_name." ";
                        $queryB .= " '".$fld_val."' ";
                    }
                }
            }
        }
    }

    if ($rep_id) {
        $query = "UPDATE reps SET ".$query." WHERE rep_id='".$rep_id."'";
    }
    else {
        $query = "INSERT INTO reps (".$queryA.") VALUES (".$queryB.")";
    }
	//echo $query;
    $resultRep = mysql_query($query) or die("Query failed : " . mysql_error() . '<br />'.$query);

    if ($rep_id) {
        $queryDelState="DELETE FROM reps_areas WHERE rep_id='".$rep_id."'";
        $resultDelState=mysql_query($queryDelState) or die("Query failed : " . mysql_error());

        $queryDelPct="DELETE FROM rep_comm_pct WHERE rep_id='".$rep_id."'";
        $resultDelPct=mysql_query($queryDelPct) or die("Query failed : " . mysql_error());

        $queryDelInd="DELETE FROM reps_industries WHERE rep_id='".$rep_id."'";
        $resultDelInd=mysql_query($queryDelInd) or die("Query failed : " . mysql_error(). '<br />'.$queryDelInd);

	}
    else {
        $rep_id = mysql_insert_id();
    }

	//add international rep areas
	foreach($_POST["rep_countries"] as $fld_name=>$fld_val) {
		if ( $fld_val!='US' ) {//US is below
			$queryRepAreas = "INSERT INTO reps_areas (rep_id, country, state, county, city, zip) VALUES ($rep_id, '".stripcslashes($fld_val)."', '%', '%', '%', '%')";
			//echo $queryRepAreas.'<br />';
			mysql_query($queryRepAreas) or die("queryRepAreas failed: ".$queryRepAreas.'<br /><br />'.mysql_error());
		}
	}

	//add US state rep areas
    foreach($_POST as $fld_name=>$fld_val) {
		if ( strpos($fld_name, "areaRow_")!==false ) {
			$queryRepAreas = "INSERT INTO reps_areas (rep_id, country, state, county, city, zip) VALUES ($rep_id, ".stripcslashes($fld_val).")";
			//echo $queryRepAreas.'<br />';
	        mysql_query($queryRepAreas) or die("queryRepAreas failed: ".$queryRepAreas.'<br /><br />'.mysql_error());
		}
	}

	//add rep percents
	if ( sizeof($rep_pcts) > 0 ) {
		foreach($rep_pcts as $pct_key=>$pct_val) {
			$queryInsPct="INSERT INTO rep_comm_pct (rep_id, price_level, rep_pct) VALUES ('".$rep_id."', '".$pct_key."', '".$pct_val."')";
			//echo "queryInsPct: ".$queryInsPct."<br />";
			$resultInsPct=mysql_query($queryInsPct) or die("queryInsPct failed: " . mysql_error());
        }		
	}

	//add rep industries
	foreach($selected_rep_inds as $selected_type) {
		$queryIndIns = "INSERT INTO reps_industries SET rep_id='$rep_id', retailer_type_id='$selected_type'";
		$resultIndIns = mysql_query($queryIndIns) or die("Query failed : " . mysql_error() . '<br />'.$queryIndIns);
	}

    if($pw_set && $sendMailChk==1) {
        send_email_login($_REQUEST["email"], $_REQUEST["first_name"], $_REQUEST["last_name"], $_REQUEST["username"], $_REQUEST["new_pw"]);
    }
    
    echo 'ok';
    exit();
}

$query = "SELECT * FROM reps WHERE rep_id='$rep_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    foreach($line as $key => $value)
    {
        $$key = $value;
    }
}

mysql_free_result($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": Edit Sales Rep"; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>includes/jquery/ui.multiselect.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>includes/jquery/jquery.ui.css">

<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery.ui.min.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/ui.multiselect.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/extend.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/reps.js"></script>
<script type="text/javascript">
        $(function() {//on doc ready
            Rep.init();
        });

        var Rep= new function() {
			this.post_data = {};

            this.init = function() {
                Rep.form = $('#update_rep_form');

				$("#rep_industries_select").multiselect({sortable: false, dividerLocation:.5});
				$("#rep_countries").multiselect({sortable: false, dividerLocation:.5, change: 'Rep.toggleStates()'});

                $('#saved_fields > div').each(function(){
                    Rep.populateFields(this);
                })

                $(':input:visible', Rep.form).each(function(){
                    if ( !$(this).attr('optional') && $(this).attr('type') != "submit" ) {
                        var $therow = $(this).parents('li:first')
						if ( $('span.error', $therow).size()==0 ) {
	                       $therow.prepend('<span class="error bold top left">*&#160;</span>')
						}
                    }
                })

				Rep.toggleStates();

                $('#submit', Rep.form).click( function() { Rep.checkForm(); return false; } );
                Rep.form.submit( function() { Rep.checkForm(); return false; } );
            }

			this.toggleStates = function() {
				if ( $('option:selected[value=US]', '#rep_countries').size() > 0 ) {
					if ( !$('#repStatesWrapper').is(':visible') ) {
						$('#repStatesWrapper').slideDown(250);
					}

					if ( !$('#stateLabelTable').is(':visible') ) {
						$('#stateLabelTable').slideDown(250);
					}
				} else {
					if ( $('#repStatesWrapper').is(':visible') ) {
						$('#repStatesWrapper').slideUp(250);
					}

					if ( $('#stateLabelTable').is(':visible') ) {
						$('#stateLabelTable').slideUp(250);
					}
				}
			}

            this.populateFields = function(theDiv) {
                var $ref_fld = $( ':input[name='+$(theDiv).attr('realname')+']' );
                var saved_val = $(theDiv).html();

                if ( $ref_fld.attr('type')=='radio' ) {
                    var this_group = $ref_fld.attr('name');
                    $('input[name='+this_group+']').each(function(){
                        if ( $(this).val()==saved_val ){
                            $(this).attr('checked', 'true');
                        }
                    })
                }
                else if ( $ref_fld.attr('type')=='checkbox' ) {
                    $ref_fld.attr('checked', 'true');
                }
                else {
                    $ref_fld.val( saved_val );
                }
            }

            this.checkForm = function() {
                var err_msg = '';
                var err_fld = '';
                
                $(':input[type!=radio][type!=checkbox][id!=""]:visible', Rep.form).each(function() {
                    if ( !$(this).attr('optional') || $(this).val()!='' ) {
                        var $therow = $(this).parents('li:first')
                        var field_name = $('label', $therow).html()

                        if ( $(this).attr('id')=='email') {
                            if ( !$(this).val().trim().match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.info)|(\..{2,2}))$)\b/gi) ) {
                                err_msg = 'Please enter a valid ' + field_name + '.';
                                err_fld = $(this).attr('id');
                            }
                        }

                        if ( $(this).attr('minlength') && $(this).val().length < $(this).attr('minlength') ) {
                            err_msg = 'Please enter at least '+ $(this).attr('minlength') +' characters for ' + field_name + '.';
                            err_fld = $(this).attr('id');
                        }
                        
                        if ( $(this).attr('type')=='password') {
                            if ( $('#new_pw').val() != $('#confirm_new_pw').val() ) {
                                err_msg = 'The new password must match in both password fields.';
                                err_fld = $(this).attr('id');
                            }
                        }

                        if ( $(this).attr('id')=='start_date' ) {
                            if ( !ValidateDate( $(this).val() ) ) {
                                err_msg = 'Please enter a properly formatted Start Date<br />(mm/dd/yyyy).';
                                err_fld = $(this).attr('id');
                            }
                        }                        
                        else if ( $(this).val()=='' ) {
                            err_msg = 'Please complete the ' + field_name + ' field.';
                            err_fld = $(this).attr('id');
                        }

                    }    
                    if ( err_fld != '' ) {
                        return false; //in this context, breaks out of each loop
                    }
                })

                if ( err_fld != '' ) {
                    $( '#floating_msg' ).html(err_msg);
                    return Rep.showError( $('#'+err_fld) );
                }
                else {
                    if ( $('#old_username').val() !=  $('#username').val() ) {
                        var check_name_url = $(Rep.form).attr('action');
                        $.post(check_name_url, {checkname:"Y", username:$('#username').val(), rep_id:$('#rep_id').val()}, function(resp){
                            if ( resp.trim().toLowerCase() != 'ok' ) {
                                $( '#floating_msg' ).html(resp);
                                return Rep.showError( $('#username') );
                            }
                            else {
                                Rep.submitUpdates();
                            }
                        })                
                    }
                    else {
                        Rep.submitUpdates();
                    }
                }
            }

            this.submitUpdates = function() {
                $( '.loading', Rep.form ).small_spinner().slideDown(200);
                var post_url = $(Rep.form).attr('action') + "?submit=1";
				Rep.post_data.sendMailChk = 0;

				Rep.post_data.rep_id = $('#rep_id').val();

				Rep.post_data.rep_countries = $('#rep_countries').val();

				$(':input[type!=radio][type!=checkbox]:visible', Rep.form).each( function() {
					if ( $(this).attr("name") ) {
						eval('Rep.post_data.' + $(this).attr("name")+' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
					}
				});

				if ( $('#sendMailChk').is(':checked') ) {
					Rep.post_data.sendMailChk = 1;
				}

				$('#rep_industries_select', Rep.form).each( function() {
					eval('Rep.post_data.selected_rep_industries = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
				});

				row_count = 1;
				$('#repAreasTable > tbody > tr').each( function() {//iterate through each area row

					var thisRowData = "";
					var thisAreaType = "";
					var lastAreaType = "";

					if ( $(':checkbox:visible:checked', this).size() > 0 ) {
						
						thisRowData = "'US'";//all states must have US set as country
						
						$(':checkbox:visible:checked', this).each(function(){
							if ( $(this).parents(':hidden').size()==0 ) {
								thisAreaType = $(this).attr('areaType');

								if ( lastAreaType!="" && thisAreaType * 1 <= lastAreaType * 1 ) {
									//diff areaType found, but it's less than the last one, so step back the pipes the difference between the last and this one

									row_count = Rep.commitRowData(row_count, thisRowData);

									for ( z=0; z <= (lastAreaType*1 - thisAreaType*1); z++ ) {
										thisRowData = thisRowData.substring( 0, thisRowData.lastIndexOf(',') );
									}
								}

								if ( thisRowData!="" ) {
									thisRowData += ",";
								}
								thisRowData += "'" + $(this).val() + "'";

								lastAreaType = thisAreaType;
							}
						})
					}
						
					row_count = Rep.commitRowData(row_count, thisRowData);
				});

				/*
				for (var this_row in Rep.post_data) {
					$('body').append(this_row+' = '+Rep.post_data[this_row]+'<br />');
				}
				return false;
				*/

                $.post(post_url, Rep.post_data, function(resp){
					$.scrollTo( '.loading', 400 );
                    if ( resp == 'ok' ) {
						if ( Rep.post_data.rep_id != '' ) {
							$( '.loading', Rep.form ).removeClass('error').html('Thanks for updating this information.');
							$('#old_username').val( $('#username').val() );
						} else {
							window.location.href="reps_admin.php";
						}
                    }
                    else {
                        $( '.loading', Rep.form ).addClass('error').html(resp);
                    }
                })            
            }

			this.commitRowData = function(row_count, thisRowData) {
				if ( thisRowData != "" ) {

					//pad out the row data with wildcards for the sql query if the user didn't specify all the way to the most granular level
					var totalPossibleLevels = 5 //country, state, county, city, zip

					if ( thisRowData.split(/\,/g).length < totalPossibleLevels ) {
						for( pCnt=thisRowData.split(/\,/g).length; pCnt < totalPossibleLevels; pCnt++ ) {
							thisRowData += ",'%'";
						}
					}

					eval('Rep.post_data.areaRow_' + row_count + ' = "' + thisRowData + '"');//this is the only way to build up post params dynamically

					row_count = row_count*1 + 1;
				}

				return row_count;
			}

            this.showError = function(elem) {
				$.scrollTo( elem, 400 );

                var float_pos = $(elem).offset();

                var left_padding, right_padding, top_padding, bottom_padding = 0;
                if ( $(elem).css('padding-left') ) {
                    left_padding = parseInt( $(elem).css('padding-left') );
                }
                if ( $(elem).css('padding-right') ) {
                    right_padding = parseInt( $(elem).css('padding-right') );
                }

                var new_left = float_pos.left +  parseInt( $(elem).width() ) + right_padding + left_padding;

                var new_top = float_pos.top;
                $('#floating_msg')
                    .css('left', new_left+'px')
                    .css('top', new_top+'px')
                    .fadeIn(300, function(){
                        if ( $(elem).attr('type')=='textbox' ) {
                            $(elem).focus();
                        }                
                    });

                setTimeout("$('#floating_msg').fadeOut(300)", 3500);
                $('button').removeAttr('disabled');
                $('.loading', Rep.form).html('');
                return false;     
            }


        }
    </script>

	<style type="text/css">
		.areaWrapper {
			height: 600px;
			padding-right: 5px;
			overflow-x: hidden;
			overflow-y: auto;
		}

		form.wmsform .no_more_borders label {
			width: auto;
		}

		.no_more_borders tr {
			vertical-align: top;
		}

		.no_more_borders td {
			border: 0px;
		}

		.threesides {
			border: 1px solid #808080;
			border-top-width: 0px;
		}

		form.wmsform label.error3 {
			color: #A5087B;
		}
	</style>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">

<div id="saved_fields" class="no_display">
    <?php
    $query = "SELECT * FROM reps WHERE rep_id='$rep_id'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach($line as $col=>$val) {
            if ( $col=='start_date' || $col=='end_date' ) {
				if ( $val ) {
					$val = date('m/d/Y', strtotime($val));
				}
            }
            echo '<div realname="'.$col.'">'.$val.'</div>';
        }
        $username = $line["username"];
    }
    ?>
</div>

<div id="floating_msg" class="no_display absolute"></div>

<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"> <?php echo $rep_id?'Edit Selected':'Add New' ?> Sales Rep</font></td></tr>
<?php
if ($rep_id) {
    echo '<tr><td><a href="reps_admin.php" style="font-size: 12px;">&lt;&lt; back to Sales Reps homepage</a></td></tr>';
}

//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<form name="update_rep_form" id="update_rep_form" method="post" action="./reps_admin_edit.php" class="wmsform" style="width: 100%;">
	<input type="hidden" name="rep_id" id="rep_id" value="<?php echo $rep_id; ?>">
    <input type="hidden" name="old_username" id="old_username" value="<?=$username?>">
	<p>Please complete the form below. Required fields marked <span class="error bold">* </span></p>
	<fieldset>
		<legend>Please Enter Rep Info</legend>
		<ol>
            <li>
				<label for="email">Username</label>
				<INPUT type="text" id="username" name="username" size="30" maxlength="255" />
			</li>
            <li>
				<label for="first_name">First Name</label>
				<INPUT type="text" id="first_name" name="first_name" size="30" maxlength="50" />
			</li>
			<li>
				<label for="last_name">Last Name</label>
				<INPUT type="text" id="last_name" name="last_name" size="30" maxlength="50" />
			</li>
            <li>
				<label for="email">E-Mail</label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="255" />
			</li>
            <li>
				<label for="start_date">Start Date</label>
				<INPUT type="text" id="start_date" name="start_date" size="11" maxlength="10" /> <span class="smaller">(mm/dd/yyyy)</span>
			</li>
            <li>
				<label for="end_date">End Date</label>
				<INPUT type="text" id="end_date" name="end_date" size="11" maxlength="10" optional="true" /> <span class="smaller">(mm/dd/yyyy)</span>
			</li>
            <br />           
            <li class="<?php echo $rep_id?"fm-optional":"" ?>">
				<label for="password"><?php echo $rep_id?'New':'' ?> Password</label>
				<input type="password" name="new_pw" id="new_pw" size="11" minlength="7" maxlength="10" <?php echo $rep_id?'optional="true"':'' ?> autocomplete="off" />
				&#160;&#160;<input type="checkbox" name="sendMailChk" id="sendMailChk" value="1" optional="true" /><label for="sendMailChk">Send new password email</label>
			</li>
            <li class="<?php echo $rep_id?"fm-optional":"" ?>">
				<label for="password">Confirm <?php echo $rep_id?'New':'' ?> Password</label>
				<input type="password" name="confirm_new_pw" id="confirm_new_pw" size="11" minlength="7" maxlength="10" <?php echo $rep_id?'optional="true"':'' ?> autocomplete="off" />
			</li>
            <br />           
			<li>
				<label for="rep_type">Rep Type</label>
				<select name="rep_type_id" id="rep_type_id">
                    <?php
                        $query="SELECT * FROM rep_types ORDER BY sequence DESC;";
                        $result=mysql_query($query) or die("Query failed : " . mysql_error());
                        while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) {
                            echo '<option value="'.$line["rep_type_id"].'">'.$line["rep_type_desc"].'</option>';
                        }
                    ?>
				</select>
			</li>
			<li>
				<label for="status">Status</label>
				<select name="status" id="status">
                   <option value="0">Inactive</option>
				   <option value="1">Active</option>
                </select>
			</li>
			<li>
				<label for="" class="left">Commission %:</label>
				<?php
					echo '<div class="left">';
					if ( $rep_id ) {
						$queryC="SELECT wpl.*, dcp.*, rcp.*, wpl.price_level AS the_price_level FROM def_comm_pct dcp, wholesale_price_levels wpl LEFT JOIN rep_comm_pct rcp ON rcp.price_level = wpl.price_level AND rcp.rep_id=".$rep_id." WHERE wpl.price_level=dcp.price_level AND dcp.rep_type_id=".$rep_type_id." ORDER BY wpl.price_level ASC;";
					} else {
						$queryC="SELECT wpl.*, wpl.price_level AS the_price_level FROM wholesale_price_levels wpl ORDER BY wpl.price_level ASC;";
					}
					//echo $queryC."<br /><br />";
					$resultC=mysql_query($queryC) or die("Query failed: ".$queryC."<br />" . mysql_error());
					while ($lineC=mysql_fetch_array($resultC, MYSQL_ASSOC)) {
						echo 'Price Level: '.$lineC["the_price_level"].' ('.$lineC["slw_min"].($lineC["slw_max"]!=0 ? ' - '.$lineC["slw_max"]:'+').' '.$lineC["slw_measure"].')';
						echo '<br />';
						echo '<select style="text-align:right;" name="rep_pct_'.$lineC["the_price_level"].'" id="rep_pct_'.$lineC["the_price_level"].'">';
						for ($z=0; $z<=100; $z=$z+.5) {
							echo '<option class="'.( strpos($z, '.5')!==false ? 'even' : 'odd').'" value="'.$lineC["the_price_level"].'|'.$z.'"';
							if ( $lineC["rep_pct"] != null && $lineC["rep_pct"]==$z ) {
								echo ' selected ';
							} else if ($lineC["rep_pct"]==null && $lineC["def_pct"]==$z) {
								echo ' selected ';
							}
							echo '>';
							echo number_format($z, 1)."%";
							echo '</option>';
						}
						echo '</select>';
						echo '<br /><br />';
					}
					echo '</div>';
					echo '<br class="clear" />';
				?>
			</li>
			<li>
				<label for="rep_industries_select">Industry(ies) Represented</label>
				<select name="selected_rep_industries[]" id="rep_industries_select" multiple="true" style="min-width: 415px" size="15">
				<?php
				$query = "SELECT retailer_type_id, name FROM retailer_type ORDER BY name;";
				if ($rep_id) {
					$query = "SELECT rt.retailer_type_id, rt.name, ri.reps_industries_id FROM retailer_type rt left JOIN reps_industries ri ON ri.retailer_type_id=rt.retailer_type_id AND ri.rep_id='$rep_id' ORDER BY rt.name";
				}
				$result = mysql_query($query) or die("Query failed : " . mysql_error());
				$retTypeCnt=0;
				while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$retailer_type_id = $line["retailer_type_id"];
					$retailer_type_name = $line["name"];
					echo '<option value="'.$retailer_type_id.'" ';
					$retTypeCnt++;

					if ( $line["reps_industries_id"] ) {
						echo ' selected="true"';
					}

					echo ">".$retailer_type_name."</option>";
				}
				echo '</select>';
				mysql_free_result($result);
				?>
			</li>
			<li class="fm-optional">
				<label for="repStatesWrapper">Areas Represented</label>
				
				<table cellpadding="2" cellspacing="0" class="maintable" width="100%">
					<tr>
						<th align="right" style="width:170px">
							Country&#160;&#160;
						</th>
						<th style="padding-left: 0px;">
							<?php
								$queryCountries="SELECT country FROM reps_areas WHERE reps_areas.rep_id='".$rep_id."' GROUP BY country";
								$resultCountries = mysql_query($queryCountries) or die("Query failed : " . mysql_error());
								
								while ($lineRepCountries=mysql_fetch_array($resultCountries, MYSQL_ASSOC)) {
									$repCountries[] = $lineRepCountries["country"];
								}
							?>
							<select style="min-width: 415px" name="rep_countries" id="rep_countries" multiple="true" size="15">
								<?php
									$queryCountries2="SELECT code, name FROM countries GROUP BY code, name ORDER BY country_id";
									$resultCountries2 = mysql_query($queryCountries2) or die("Query failed : " . mysql_error());
									while ($lineCountries2=mysql_fetch_array($resultCountries2, MYSQL_ASSOC)) {
										echo '<option value="'.$lineCountries2["code"].'"';
										
										if ( in_array($lineCountries2["code"], $repCountries) ) {
											echo ' selected ';
										}

										echo '>'.$lineCountries2["name"].'</option>';
									}
								?>
							</select>
						</th>
					</tr>
				</table>
				<br />
				<table id="stateLabelTable" cellpadding="2" cellspacing="0" class="maintable" width="100%">
					<tr>
						<th align="right" style="width:170px">
							State&#160;&#160;
						</th>
						<th align="center">
						</th>
					</tr>
				</table>
				<div id="repStatesWrapper" class="areaWrapper">
					<table cellpadding="2" cellspacing="0" class="maintable" width="100%" id="repAreasTable">
						<?php
							$rowcnt = 0;
							$query="SELECT * FROM reps_areas WHERE reps_areas.rep_id='".$rep_id."' GROUP BY state, city;";
							$result=mysql_query($query) or die("Query failed : " . mysql_error());
							while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) {
								$states[] = $line["state"];

								if ( $line["county"] != "%" ) {
									$states[ $line["state"] ]["counties"] = $line["county"];
								}
							}
							
							$querySt = "SELECT * FROM states WHERE country_id='1'";//get US only
							$resultSt = mysql_query($querySt) or die("Query failed : " . mysql_error());
							while ($lineSt = mysql_fetch_array($resultSt, MYSQL_ASSOC)) {
								$rowcnt++;
								echo '
									<tr class="';
								$rclass="odd";
								if ( $rowcnt % 2==0 ) {
									$rclass='even';
								}

								echo $rclass.'">';

								$nameClass = "";
								if ( ($states && in_array( $lineSt["code"], $states )) || sizeof( $states[ $lineSt["code"] ]["cities"] ) >0 ) {
									$nameClass = ' error3 ';
								}

								echo '<td align="right" valign="top" style="width:170px"><label for="'.$lineSt["code"].'_State"  class="'.$nameClass.'">'.$lineSt["name"].'</label>&#160;&#160;</td>';
								
								echo '<td align="left">';
								
									echo '<table class="no_more_borders">';
									echo	'<tr valign="top">';
									echo		'<td>';
									echo			'<input type="checkbox" showThis="'.$lineSt["code"].'_decisionWrapper" areaType="1" name="'.$lineSt["code"].'_State" id="'.$lineSt["code"].'_State" value="'.$lineSt["code"].'" ';
									if ( $states && in_array( $lineSt["code"], $states ) ) {
										echo ' checked';
									}
									echo ' />';
									echo		'</td>';
									echo	'</tr>';

									echo	'<tr valign="top">';
									echo		'<td style="border:none"></td>';
									echo		'<td style="border:none">';
									echo			'<div class="no_display" id="'.$lineSt["code"].'_decisionWrapper">';

									echo				'<table>';
									echo					'<tr valign="top">';
									echo						'<td>';
									echo							'<input type="radio" hideThis="'.$lineSt["code"].'_CountyWrapper" name="'.$lineSt["code"].'_AllOrCounty" id="'.$lineSt["code"].'_AllOrCounty_A" value="all" checked />';
									
									echo							'<label for="'.$lineSt["code"].'_AllOrCounty_A"> Entire State</label>';
									echo						'</td>';
									echo						'<td>';

									echo							'<input type="radio" showThis="'.$lineSt["code"].'_CountyWrapper" name="'.$lineSt["code"].'_AllOrCounty" id="'.$lineSt["code"].'_AllOrCounty_S" value="specific"';
															if ( sizeof( $states[ $lineSt["code"] ]["counties"] ) >0 ) {
																echo ' checked';
															}
									echo							'/><label for="'.$lineSt["code"].'_AllOrCounty_S"> Counties In</label>';
									echo							'<div class="no_display threesides" id="'.$lineSt["code"].'_CountyWrapper" level="County" areaType="1" prevVal="'.$lineSt["code"].'" prevLevel="State" key="'.$lineSt["code"].'">';
									echo							'</div>';
									echo						'</td>';
									echo					'</tr>';
									echo				'</table>';

									echo			'</div><!-- decisionWrapper -->';
									echo		'</td>';
									echo	'</tr>';
									echo '</table>';

								echo '</td>';
								
								echo '</tr>';
							}
						?>
					</table>
				</div>
				<!--
				<table width="100%">
					<tr>
						<td align="right" style="padding-right:38px"><a href="javascript: void(0)" id="linkLoadCounties">load counties</a></td>
						<td align="right" style="padding-right:38px"><a href="javascript: void(0)" id="linkLoadCities">load cities</a></td>
					</tr>
				</table>
				-->
			</li>
            <br />
            <li>
				<label for="address1">Address</label>
				<INPUT type="text" id="address1" name="address1" size="30" maxlength="100" />
			</li>
			<li class="fm-optional">
				<label for="address2">Address 2</label>
				<INPUT type="text" id="address2" name="address2" size="30" maxlength="100" optional="true" />
			</li>
			<li>
				<label for="city">City</label>
				<INPUT type="text" id="city" name="city" size="30" maxlength="50" />
			</li>
			<li>
				<label for="state">State/Province</label>
				<select id="state" name="state">
				<?php
				state_build_all($state);
				?>
				</select>
			</li>
			<li>
				<label for="zip">Zip/Postal Code</label>
				<INPUT type="text" id="zip" name="zip" size="10" maxlength="10" />
			</li>
			<li>
				<label for="country">Country</label>
				<select id="country" name="country">
				<?php
                    if ( $rep_id ) {
                        country_build_all($country);
                    } else {
                        country_build_all('US');
                    }
				?>
				</select>
			</li>
			<li>
				<label for="phone">Phone</label>
				<INPUT type="text" id="phone" name="phone" size="30" maxlength="30" />
			</li>

			<li class="fm-button">
				<input type="submit" id="submit" name="submit" value="Save">
			</li>
		</ol>
	</fieldset>
        <div class="loading no_display text_center bold"></div>
    </form>
</td></tr>

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