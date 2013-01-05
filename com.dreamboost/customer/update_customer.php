<?php

include '../includes/main1.php';
include $base_path.'includes/st_and_co1.php';
include $base_path.'includes/customer.php';

checkCustomerLogin();

function send_email_login($email, $first_name, $last_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with new password
		$email_str = "Dear ";
		$email_str .= $first_name . " " . $last_name . ",\n\n";
		$email_str .= "Please review the login details ";
		$email_str .= "for your " . $website_title . " account listed below.\n\n";

		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url;
						
		$subject = "New  " . $website_title . " Login Info";
		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

if($_REQUEST["checkDupes"] != "") {
    $err_msg = "";
    $queryName = "SELECT * FROM members WHERE username='".$_REQUEST["username"]."' AND member_id !='".$member_id."'";
    $resultName = mysql_query($queryName) or die("Query failed : " . mysql_error());
    if ( mysql_num_rows($resultName)>0 ) {
        $err_msg = 'That Username is already taken.  Please try again.|username';
    }
    else {
        $queryName2 = "SELECT * FROM members WHERE email='".$_REQUEST["email"]."' AND member_id !='".$member_id."'";
        $resultName2 = mysql_query($queryName2) or die("Query failed : " . mysql_error());
        if ( mysql_num_rows($resultName2)>0 ) {
            $err_msg = 'That email is already taken by another account.  Please try again.|email';
        }
    }   
    
    if ( $err_msg == "" ) {
        echo 'ok';
    }
    else {
        echo $err_msg;
    }

    exit();
}



if($_GET["submit"] != "") {

	$query = "";
    $pw_set = false;

    foreach($_GET as $fld_name=>$fld_val) {
        if ( $fld_name != "submit" && strpos($fld_name, "new_pw")===false && strpos($fld_name, "sameAsBill")===false ) {
			$query .= $query == "" ? "" : " , ";
            $query .= " ".$fld_name."='".$fld_val."' ";
        }
    }


    if($_GET["new_pw"] != "") {
        $query .= $query == "" ? "" : " , ";
        $query .= " password= '".md5($_GET["new_pw"])."' ";

        $pw_set = true;
    }

    $query = "UPDATE members SET ".$query." WHERE member_id='".$member_id."'";

	//echo $query;
    $result = mysql_query($query) or die("Query failed : " . mysql_error());

    $query = "SELECT * FROM members  WHERE member_id='".$member_id."'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $_SESSION["member_name"] = $line["first_name"].' '.$line["last_name"];;

        foreach($line as $col=>$val) {
            $_SESSION['address_info']["".$col.""] = $val;
        }
    }
	/*
    if($pw_set) {
        send_email_login($_GET["email"], $_GET["first_name"], $_GET["last_name"], $_GET["username"], $_GET["new_pw"]);
    }
	*/
    echo 'ok';
    exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: My <?php echo $website_title; ?> Profile</title>
<?php
include $base_path.'includes/meta1.php';
?>

    <script language="JavaScript">
        $(function() {//on doc ready
            UC.init();
        });

        var UC= new function() {
    
            this.init = function() {
                UC.form = $('#update_customer_form');

                $('#saved_fields > div').each(function(){
                    UC.populateFields(this);
                })

                $(':input:visible', UC.form).each(function(){
                    if ( !$(this).attr('optional') && $(this).attr('type') != "submit" ) {
                        var $therow = $(this).parents('tr:first')
                        $('td:first', $therow).prepend('<span class="error">* </span>')
                    }
                })

                $('#sameAsBill').each(function(){
                    $(this).click(function(){
						if ( $(this).is(":checked") ) {
							$(':input[@id*=bill_]').each(function(){
								var fld_suffix = $(this).attr('id').substring( 5 );
								$('#ship_' + fld_suffix).val( $(this).val() );
							})
						}
                    })
                })

                $('#submit', UC.form).click( function() { UC.checkForm(); return false; } );
                UC.form.submit( function() { UC.checkForm(); return false; } );
            }

            this.populateFields = function(theDiv) {
                var $ref_fld = $( ':input[@name='+$(theDiv).attr('realname')+']' );
                var saved_val = $(theDiv).html();

                if ( $ref_fld.attr('type')=='radio' ) {
                    var this_group = $ref_fld.attr('name');
                    $('input[@name='+this_group+']').each(function(){
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
                
                $(':input:visible', UC.form).each(function() {
                    if ( !$(this).attr('optional') || $(this).val()!='' ) {
                        var $therow = $(this).parents('tr:first')
                        var field_name = $('td:first', $therow).html()
                        field_name = field_name.substring(field_name.indexOf('*')+1, field_name.indexOf(':'));

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
                                err_msg = 'Your new password must match in both password fields.';
                                err_fld = $(this).attr('id');
                            }
                        }

                        if ( $(this).val()=='' ) {
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
                    return UC.showError( $('#'+err_fld) );
                }
                else {
                    if (( $('#old_username').val() !=  $('#username').val() ) || ( $('#old_email').val() !=  $('#email').val() )) {
                        var check_name_url = $(UC.form).attr('action');
                        $.post(check_name_url, {checkDupes:"Y", username:$('#username').val(), email:$('#email').val()}, function(resp){
                            if ( resp.trim().toLowerCase() != 'ok' ) {
                                resp = resp.split("|")
                                $( '#floating_msg' ).html(resp[0]);
                                return UC.showError( $('#'+resp[1]) );
                            }
                            else {
                                UC.submitUpdates();
                            }
                        })                
                    }
                    else {
                        UC.submitUpdates();
                    }
                }
            }

            this.submitUpdates = function() {
                $( '.loading', UC.form ).small_spinner().slideDown(200);
                var post_url = "update_customer.php?submit=1"

                $(':input:visible', UC.form).each( function() {
                    post_url += "&" + $(this).attr("name") + "=" + $(this).val();
                })

                $.post(post_url, function(resp){
                    $( '.loading', UC.form ).ScrollTo(400);
                    if ( resp == 'ok' ) {
                        $( '.loading', UC.form ).addClass('error3').html('Thanks for updating your information.');
                        $('#old_username').val( $('#username').val() );
                    }
                    else {
                        $( '.loading', UC.form ).addClass('error').html(resp);
                    }
                })            
            }

            this.showError = function(elem) {
                $(elem).ScrollTo(400);

                var float_pos = findPos( $(elem).get(0) )

                var left_padding, right_padding, top_padding, bottom_padding = 0;
                if ( $('#floating_msg').css('padding-left') ) {
                  left_padding = 1* $('#floating_msg').css('padding-left').substring( 0, $('#floating_msg').css('padding-left').indexOf('px') );
                }
                if ( $('#floating_msg').css('padding-right') ) {
                  right_padding = 1 * $('#floating_msg').css('padding-right').substring( 0, $('#floating_msg').css('padding-right').indexOf('px') );
                }

                var new_left = float_pos[0];
                if ( $('#floating_msg').width() + float_pos[0] > UC.form.width() ) {                            
                    new_left = float_pos[0] - $('#floating_msg').width() + $(elem).width();
                    if ( !jQuery.browser.msie  ) {
                        new_left =  new_left - left_padding - right_padding;
                    }
                }

                if ( $(elem).css('padding-top') ) {
                    top_padding = 1* $(elem).css('padding-top').substring( 0, $(elem).css('padding-top').indexOf('px') );
                }
                if ( $(elem).css('padding-bottom') ) {  
                    bottom_padding = 1 * $(elem).css('padding-bottom').substring( 0, $(elem).css('padding-bottom').indexOf('px') );
                }
                var new_top = float_pos[1] +  $(elem).height() + top_padding + bottom_padding + 2;
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
                return false;     
            }


        }

    </script>
</head>
<body>

<div id="saved_fields" class="no_display">
    <?php
    $query = "SELECT * FROM members WHERE member_id='$member_id'";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        foreach($line as $col=>$val) {
            echo '<div realname="'.$col.'">'.$val.'</div>';
        }
        $username = $line["username"];
        $email = $line["email"];
    }
    ?>
</div>

<div id="floating_msg" class="no_display absolute"></div>

<div align="center">

<?php
include $base_path.'includes/head1.php';
?>
<form name="update_customer_form" id="update_customer_form" action="./update_customer.php" method="POST">
<input type="hidden" name="old_username" id="old_username" value="<?=$username?>">
<input type="hidden" name="old_email" id="old_email" value="<?=$email;?>">

<table border="0" width="677">
<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">My <?php echo $website_title; ?> Profile</font></td></tr>


<?php

//Error Messages
if($error_txt) {
	echo '<tr><td><span class="error">$error_txt</span></td></tr>\n';
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>

<tr><td align="left" valign="top">
Please review and update your information below.&#160;&#160;<span class="error">* </span>Required fields
<br />
<br />
<table border="0" align="center">
<tr><td align="right">Username:</td><td align="left"><input type="text" name="username" id="username" size="30" /></td></tr>

<tr><td align="right">First Name:</td><td align="left"><input type="text" name="first_name" id="first_name" size="30" /></td></tr>

<tr><td align="right">Last Name:</td><td align="left"><input type="text" name="last_name" id="last_name" size="30" /></td></tr>

<tr><td align="right">Email:</td><td align="left"><input type="text" name="email" id="email" size="30" /></td></tr>

<tr><td align="left" colspan="2">&#160;</td></tr>

<tr><td align="right">New Password:</td><td align="left"><input type="password" name="new_pw" id="new_pw" size="11" minlength="7" maxlength="10" optional="true" autocomplete="off" /></td></tr>

<tr><td align="right">Confirm New Password:</td><td align="left"><input type="password" name="confirm_new_pw" id="confirm_new_pw" size="11" minlength="7" maxlength="10" optional="true" autocomplete="off" /></td></tr>

<tr><td align="left" colspan="2">&#160;</td></tr>

<!-- Billing Info -->
<tr><td align="left" colspan="2"><b>Billing Information</b></td></tr>

<tr><td align="right">Name:</td><td align="left"><input type="text" name="bill_name" id="bill_name" size="30" /></td></tr>

<tr><td align="right">Address:</td><td align="left"><input type="text" name="bill_address1" id="bill_address1" size="30" /></td></tr>

<tr><td align="right">Address 2:</td><td align="left"><input type="text" name="bill_address2" id="bill_address2" size="30" maxlength="255" optional="true" /></td></tr>

<tr><td align="right">City:</td><td align="left"><input type="text" name="bill_city" id="bill_city" size="30" /></td></tr>

<tr><td align="right">State (if applicable):</td><td align="left"><select name="bill_state" id="bill_state" optional="true">
<?php
    state_build_all($state)
?>
</select></td></tr>
<tr><td align="right">Zip/Postal Code (if applicable):</td><td align="left"><input type="text" name="bill_zip" id="bill_zip" size="10" maxlength="10" optional="true" /></td></tr>
<tr><td align="right">Country:</td><td align="left"><select name="bill_country" id="bill_country">
<?php
    country_build_all($country)
?>
</select></td></tr>

<tr><td align="left" colspan="2">&#160;</td></tr>

<!-- Shipping Info -->
<tr><td align="left" colspan="2"><b>Shipping Information</b></td></tr>

<tr><td align="left" colspan="2"><input type="checkbox" name="sameAsBill" id="sameAsBill" optional="true" /><label for="sameAsBill"> Use same as Billing Information</label></td></tr>

<tr><td align="right">Name:</td><td align="left"><input type="text" name="ship_name" id="ship_name" size="30" /></td></tr>

<tr><td align="right">Address:</td><td align="left"><input type="text" name="ship_address1" id="ship_address1" size="30" /></td></tr>

<tr><td align="right">Address 2:</td><td align="left"><input type="text" name="ship_address2" id="ship_address2" size="30" optional="true" /></td></tr>

<tr><td align="right">City:</td><td align="left"><input type="text" name="ship_city" id="ship_city" size="30" /></td></tr>

<tr><td align="right">State (if applicable):</td><td align="left"><select name="ship_state" id="ship_state" optional="true">
<?php
    state_build_all($state)
?>
</select></td></tr>
<tr><td align="right">Zip/Postal Code (if applicable):</td><td align="left"><input type="text" name="ship_zip" id="ship_zip" size="10" maxlength="10" optional="true" /></td></tr>
<tr><td align="right">Country:</td><td align="left"><select name="ship_country" id="ship_country">
<?php
    country_build_all($country)
?>
</select></td></tr>

<tr><td colspan="2" align="center">
<br /><input type="submit" name="submit" id="submit" value=" Save Changes ">
    <br /><br />
    <div class="loading no_display"></div>

</td></tr>
</table>

</td></table>
</form>
<br />

<?php
include $base_path.'includes/foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>