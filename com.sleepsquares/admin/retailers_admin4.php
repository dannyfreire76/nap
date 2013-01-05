<?php
// BME WMS
// Page: Retailers Modify Retailer page
// Path/File: /admin/retailers_admin4.php
// Version: 1.8
// Build: 1807
// Date: 07-12-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/retailer1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$edit = $_GET["edit"];
$selected_retailer_types = array();
foreach($_REQUEST as $key => $value)
{
    $$key = $value;
} 


$this_user_id = $_COOKIE["wms_user"];

$query = "SELECT product_line, product FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
	$product = $line["product"];
}
mysql_free_result($result);

include './includes/wms_nav1.php';
$manager = "retailers";
$page = "Retailers Manager > Edit Retailers";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ($submit != "") {
	//prepare data
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	$phone = str_replace("-", "", $phone);
	$phone = str_replace(".", "", $phone);
	$phone = str_replace("+", "", $phone);
	$phone = str_replace(" ", "", $phone);
	$phone = str_replace("\\", "", $phone);
	$phone = str_replace("/", "", $phone);
	$phone = str_replace(":", "", $phone);
	$phone = str_replace(";", "", $phone);
	$phone = str_replace("<", "", $phone);
	$phone = str_replace(">", "", $phone);
	$phone = str_replace("#", "", $phone);
	$phone = str_replace("@", "", $phone);

	$fax_other_phone = str_replace("(", "", $fax_other_phone);
	$fax_other_phone = str_replace(")", "", $fax_other_phone);
	$fax_other_phone = str_replace("-", "", $fax_other_phone);
	$fax_other_phone = str_replace(".", "", $fax_other_phone);
	$fax_other_phone = str_replace("+", "", $fax_other_phone);
	$fax_other_phone = str_replace(" ", "", $fax_other_phone);
	$fax_other_phone = str_replace("\\", "", $fax_other_phone);
	$fax_other_phone = str_replace("/", "", $fax_other_phone);
	$fax_other_phone = str_replace(":", "", $fax_other_phone);
	$fax_other_phone = str_replace(";", "", $fax_other_phone);
	$fax_other_phone = str_replace("<", "", $fax_other_phone);
	$fax_other_phone = str_replace(">", "", $fax_other_phone);
	$fax_other_phone = str_replace("#", "", $fax_other_phone);
	$fax_other_phone = str_replace("@", "", $fax_other_phone);
	
	//write info to comments field
	
	// combine last_contact_on and next_contact_on DATETIME fields
	if($last_contact_on != ""){
		$last_contact_on = $last_contact_on . " " . $last_contact_on_h . ":" . $last_contact_on_n . ":00";
	} else {
		$last_contact_on = "";
	}
	if($next_contact_on != ""){
		$next_contact_on = $next_contact_on . " " . $next_contact_on_h . ":" . $next_contact_on_n . ":00";
	} else {
		$next_contact_on = "";
	}
	
	// combine sent_promo_pack_when DATETIME field
	if($sent_promo_pack_when != ""){
		$sent_promo_pack_when = $sent_promo_pack_when . " " . $sent_promo_pack_when_h . ":" . $sent_promo_pack_when_n . ":00";
	} else {
		$sent_promo_pack_when = "";
	}
	
	//entered_by and last_mod_by to this_user_id
	$last_mod_by = $this_user_id;
	
	//checks for duplication of entries - hard with no required fields
	$error_txt = "";
	if ( check_user($username, $retailer_id) < 1 ) { 
        $error_txt .= "Error, duplicate username. The username you entered is already in use. Please select a different username.<br>\n";
    }
    
    if ( $password ) {
        if ($password != $confirm_password) {
            $error_txt .= "The new password must match in both password fields.<br />";
        }
        else if ( strlen($password)<7 ) {
            $error_txt .= "The new password must be 7-10 characters in length.<br />";
        }
    }

	if($error_txt == "") {
		$queryFields = array( 
			"last_mod_by" => $last_mod_by,
			"last_contact_by" => $last_contact_by,
			"next_contact_by" => $next_contact_by,
			"last_contact_on" => $last_contact_on,
			"next_contact_on" => $next_contact_on,
			"last_contact_by_person" => $last_contact_by_person,
			"next_contact_by_person" => $next_contact_by_person,
			"store_name" => addslashes($store_name),
			"store_name_checkbox" => $store_name_checkbox,
			"contact_name" => addslashes($contact_name),
			"contact_name_checkbox" => $contact_name_checkbox,
			"username" => $username,
			"password" => md5($password),
			"address1" => addslashes($address1),
			"address2" => addslashes($address2),
			"city" => addslashes($city),
			"state" => $state,
			"zip" => $zip,
			"country" => $country,
			"email" => $email,
			"email_checkbox" => $email_checkbox,
			"phone" => $phone,
			"phone_checkbox" => $phone_checkbox,
			"fax_other_phone" => $fax_other_phone,
			"fax_other_phone_checkbox" => $fax_other_phone_checkbox,
			"website" => $website,
			"website_checkbox" => $website_checkbox,
			"where_store_found" => addslashes($where_store_found),
			"sent_promo_pack" => $sent_promo_pack,
			"sent_promo_pack_when" => $sent_promo_pack_when,
			"received_promo_pack" => $received_promo_pack,
			"contact_number" => $contact_number,
			"retailer_status" => $retailer_status,
			"comments" => addslashes($comments),
			"list_store_website" => $list_store_website,
			"store_name_website" => addslashes($store_name_website),
			"contact_name_website" => addslashes($contact_name_website),
			"contact_phone_website" => $contact_phone_website,
			"contact_fax_website" => $contact_fax_website,
			"contact_email_website" => $contact_email_website,
			"contact_website_website" => $contact_website_website,
			"items_sold" => addslashes($items_sold),
			"secure_funds_only" => $secure_funds_only,
			"discount_code" => $discount_code,
			"store_type" => $store_type,
			"hours_days_operation" => addslashes($hours_days_operation),
			"credit" => $credit
		);

		if ($retailer_id) {
			$query = "UPDATE retailer SET ";

			$queryB = "";			
			foreach($queryFields as $fld_name=>$fld_val) {
				if ( ($fld_name!='password' && $fld_name!='last_contact_on' && $fld_name!='next_contact_on' && $fld_name!='sent_promo_pack_when' && $fld_name!='sent_promo_pack_when') || $_REQUEST[$fld_name]!='') {
					$queryB .= $queryB == "" ? "" : " , ";
					$queryB .= $fld_name."='".$fld_val."'";

					if ($fld_name=='password') {
						send_email_login($email, $contact_name, $username, $password);
					}
				}
			}

			$query = $query.$queryB." WHERE retailer_id='$retailer_id'";
		}
		else {
			$query = "INSERT INTO retailer (";
			
			$queryA = "";
			foreach($queryFields as $fld_name=>$fld_val) {
				if ( ($fld_name!='password' && $fld_name!='last_contact_on' && $fld_name!='next_contact_on' && $fld_name!='sent_promo_pack_when' && $fld_name!='sent_promo_pack_when') || $_REQUEST[$fld_name]!='') {
					$queryA .= $queryA == "" ? "" : " , ";
					$queryA .= $fld_name;

					if ($fld_name=='password') {
						send_email_login($email, $contact_name, $username, $password);
					}
				}
			}

			$query = $query.$queryA.") VALUES ( ";

			$queryB = "";
			foreach($queryFields as $fld_name=>$fld_val) {
				if ( ($fld_name!='password' && $fld_name!='last_contact_on' && $fld_name!='next_contact_on' && $fld_name!='sent_promo_pack_when' && $fld_name!='sent_promo_pack_when') || $_REQUEST[$fld_name]!='') {
					$queryB .= $queryB == "" ? "" : " , ";
					$queryB .= "'".$fld_val."'";
				}
			}

			$query = $query.$queryB.") ";

		}

		//echo $query."<br /><br />"; exit();
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		if (!$retailer_id) {
			$retailer_id = mysql_insert_id();
		}
		

        $queryDelPct="DELETE FROM retailer_rep_types WHERE retailer_id='".$retailer_id."'";
        $resultDelPct=mysql_query($queryDelPct) or die("Query failed : " . mysql_error());

		if ( sizeof($valid_rep_types) > 0 ) {
			foreach($valid_rep_types as $rep_fld=>$val_rep_id) {
				$queryIns = "INSERT INTO retailer_rep_types (retailer_id, rep_type_id) VALUES (".$retailer_id.", ".$val_rep_id.")";
				$resultIns = mysql_query($queryIns) or die("Query failed : " . mysql_error());
			}
		}
		
		$query = "DELETE FROM retailer_type_link WHERE retailer_id='$retailer_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		foreach($selected_retailer_types as $selected_type) {
			$query = "INSERT INTO retailer_type_link SET created='$now', retailer_id='$retailer_id', retailer_type_id='$selected_type'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		}

		if($username != $old_username || $password != $old_password) {
			//send_email_login($email, $contact_name, $username, $password);
		}
	}
	$edit = $retailer_id;

}

if ($delete != "") {
	$query = "DELETE FROM retailer WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());

	$query = "DELETE FROM retailer_type_link WHERE retailer_id='$retailer_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());

	// Send to main retailers admin page
	header("Location: " . $base_url . "admin/retailers_admin.php");
	exit;

}

if ($order != "") {
	// Send to retailers place orders page
	header('Location: '.$base_secure_url.'admin/retailers_admin10.php?retailer_id='.$retailer_id);
	exit;
}

$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	foreach($line as $col=>$val) {
		$$col = stripslashes($val);
		//echo $col.' :: '.$val.'<br />';
	}

}
mysql_free_result($result);

list($last_contact_on_date, $last_contact_on_time) = split(" ", $last_contact_on, 2);
list($next_contact_on_date, $next_contact_on_time) = split(" ", $next_contact_on, 2);
list($sent_promo_pack_when_date, $sent_promo_pack_when_time) = split(" ", $sent_promo_pack_when, 2);
list($last_contact_on_y, $last_contact_on_m, $last_contact_on_d) = split("-", $last_contact_on_date, 3);
list($last_contact_on_h, $last_contact_on_n, $last_contact_on_s) = split(":", $last_contact_on_time, 3);
list($next_contact_on_y, $next_contact_on_m, $next_contact_on_d) = split("-", $next_contact_on_date, 3);
list($next_contact_on_h, $next_contact_on_n, $next_contact_on_s) = split(":", $next_contact_on_time, 3);
list($sent_promo_pack_when_y, $sent_promo_pack_when_m, $sent_promo_pack_when_d) = split("-", $sent_promo_pack_when_date, 3);
list($sent_promo_pack_when_h, $sent_promo_pack_when_n, $sent_promo_pack_when_s) = split(":", $sent_promo_pack_when_time, 3);

	if($edit != "") { $retailer_id = $edit; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/wmsform.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>includes/jquery/ui.multiselect.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>includes/jquery/jquery.ui.css">

<script type="text/javascript" src="<?=$base_url?>/includes/wmsform.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery.ui.min.js"></script>
<script type="text/javascript" src="<?=$base_url?>/includes/jquery/ui.multiselect.js"></script>

<script type="text/javascript">
	$(function(){
		$("#retailer_types_select").multiselect({sortable: false, dividerLocation:.5});
		//$(".multiselect").multiselect({sortable: false, searchable: false});
	});
</script>


<script language="JavaScript">
function doEcho()
{
	if (document.form1.store_name_checkbox.checked) {
		if (document.form1.store_name_website.value=="") {
			document.form1.store_name_website.value=document.form1.store_name.value;
		}
	}		
	if (document.form1.contact_name_checkbox.checked) {
		if (document.form1.contact_name_website.value=="") {
			document.form1.contact_name_website.value=document.form1.contact_name.value;
		}
	}		
	if (document.form1.email_checkbox.checked) {
		if (document.form1.contact_email_website.value=="") {
			document.form1.contact_email_website.value=document.form1.email.value;
		}
	}		
	if (document.form1.phone_checkbox.checked) {
		if (document.form1.contact_phone_website.value=="") {
			document.form1.contact_phone_website.value=document.form1.phone.value;
		}
	}		
	if (document.form1.fax_other_phone_checkbox.checked) {
		if (document.form1.contact_fax_website.value=="") {
			document.form1.contact_fax_website.value=document.form1.fax_other_phone.value;
		}
	}		
	if (document.form1.website_checkbox.checked) {
		if (document.form1.contact_website_website.value=="") {
			document.form1.contact_website_website.value=document.form1.website.value;
		}
	}		
}

function warn_on_submit ()
{
	var msg = confirm("\nAre you sure you want to delete this Retailer?\n\n");
	
	if (msg) {
		return true;
	} else {
		return false;
	}
}

var win = null;
function newWindow(mypage,myname,w,h,features) {
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  if (winl < 0) winl = 0;
  if (wint < 0) wint = 0;
  var settings = 'height=' + h + ',';
  settings += 'width=' + w + ',';
  settings += 'top=' + wint + ',';
  settings += 'left=' + winl + ',';
  settings += features;
  win = window.open(mypage,myname,settings);
  win.window.focus();
}

function genUsername(newUser) {
	document.form1.username.value = newUser;
}
function genPassword(newPass) {
	document.form1.password.value = newPass;
}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onLoad="doEcho()">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2"><b>
<?php
	if ($retailer_id) {
		echo 'Edit the selected Retailer';
	}
	else {
		echo 'Add New Retailer';
	}
?>
</b></font></td></tr>

<tr><td><a href="retailers_admin.php">&lt; &lt; back to Retailer Home Page</a></td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form name="form1" action="./retailers_admin4.php" method="POST">
<input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">
<tr><td align="left"><table border="0">
<?php
$funds_owed = 0;
$queryFO = "SELECT * FROM wholesale_receipts WHERE retailer_id='".$retailer_id."' AND funds_received=0 AND complete=1";
$resultFO = mysql_query($queryFO) or die("Query failed : " . mysql_error());
if ( mysql_num_rows($resultFO) > 0 ) {
	$funds_owed = 1;
}

if($funds_owed==1) {
	echo '<tr><td class="funds_owed bold text_right" style="font-size: 14px">This retailer owes funds.</td></tr>';
}  

?>
<tr><td align="right"><font size="+1">Store Name:</font></td><td><input type="text"<?php
if($funds_owed==1) {echo ' class="funds_owed"';}  

?> id="store_name" name="store_name" size="30" maxlength="255" value="<?php echo $store_name; ?>" onChange="doEcho()"> <input type="checkbox" name="store_name_checkbox" value="1"<?php if($store_name_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Contact Name:</font></td><td><input type="text" name="contact_name" size="30" maxlength="150" value="<?php echo $contact_name; ?>" onChange="doEcho()"> <input type="checkbox" name="contact_name_checkbox" value="1"<?php if($contact_name_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Secure Funds Only:</font></td><td><input type="checkbox" name="secure_funds_only" value="1"<?php if($secure_funds_only == "1") { echo " CHECKED"; } ?>></td></tr>

<tr>
	<td align="right"><font face="Arial" size="+1">Store Type:</font></td>
	<td>
		<select name="store_type" id="store_type">
		<?php
		$query3 = "SELECT * FROM store_types";
		$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
		while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
			echo '<option value="'.$line3["store_types_id"].'"';
			if ( $store_type==$line3["store_types_id"] ) {
				echo ' selected ';
			}
			echo ' >';
			echo $line3["store_types_desc"];
			echo '</option>';
		}
		?>
		</select>
	</td>
</tr>
<tr><td align="right"><font face="Arial" size="+1">Credit:</font></td><td><input type="text" name="credit" size="10" maxlength="10" value="<?php echo $credit; ?>"></td></tr>


<tr><td colspan="2" align="center"><hr></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Username:</font></td><td><input type="text" name="username" size="10" maxlength="10" value="<?php echo $username; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">New Password:</font></td><td><input type="password" name="password" size="10" maxlength="10" autocomplete="off"> (7-10 characters)</td></tr>
<tr><td align="right"><font face="Arial" size="+1">Confirm Password:</font></td><td><input type="password" name="confirm_password" size="10" maxlength="10" autocomplete="off"></td></tr>
<tr><td colspan="2" align="center"><hr></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Address1:</font></td><td><input type="text" name="address1" size="30" maxlength="150" value="<?php echo $address1; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Address2:</font></td><td><input type="text" name="address2" size="30" maxlength="150" value="<?php echo $address2; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">City:</font></td><td><input type="text" name="city" size="30" maxlength="100" value="<?php echo $city; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">State/Province:</font></td><td><select name="state">
<option value="">Select state/province</option>
<?php
$query = "SELECT * FROM states WHERE status='1' ORDER BY name";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<option value="'.$line["code"].'"';
	if($state == $line["code"]) { echo " SELECTED"; }
	echo '>'.$line["name"].'</option>';
}

?>

</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Zip/Postal Code:</font></td><td><input type="text" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Country:</font></td><td><select name="country">
<option value="">Select a country</option>
<?php
$query = "SELECT * FROM countries WHERE status='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<option value="'.$line["code"].'"';
	if($country == $line["code"]) { echo " SELECTED"; }
	echo '>'.$line["name"].'</option>';
}

?>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Email:</font></td><td><input type="text" name="email" size="30" maxlength="255" value="<?php echo $email; ?>" onChange="doEcho()"> <input type="checkbox" name="email_checkbox" value="1"<?php if($email_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Phone:</font></td><td><input type="text" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>" onChange="doEcho()"> <input type="checkbox" name="phone_checkbox" value="1"<?php if($phone_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Fax/Other Phone:</font></td><td><input type="text" name="fax_other_phone" size="30" maxlength="30" value="<?php echo $fax_other_phone; ?>" onChange="doEcho()"> <input type="checkbox" name="fax_other_phone_checkbox" value="1"<?php if($fax_other_phone_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Website:</font></td><td><input type="text" name="website" size="30" maxlength="255" value="<?php echo $website; ?>" onChange="doEcho()"> <input type="checkbox" name="website_checkbox" value="1"<?php if($website_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">To use in website display area</font></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Industry(ies):</font></td><td>
<select name="selected_retailer_types[]" id="retailer_types_select" multiple="true" style="min-width: 415px" size="15">
<?php
$query = "SELECT retailer_type_id, name FROM retailer_type ORDER BY name;";
if ($retailer_id) {
	$query = "SELECT rt.retailer_type_id, rt.name, rtl.retailer_type_link_id FROM retailer_type rt left JOIN retailer_type_link rtl ON rtl.retailer_type_id=rt.retailer_type_id AND rtl.retailer_id='$retailer_id' ORDER BY rt.name";
}
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$retTypeCnt=0;
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$retailer_type_id = $line["retailer_type_id"];
	$retailer_type_name = $line["name"];
	echo '<option value="'.$retailer_type_id.'" ';
	$retTypeCnt++;

	if ( $line["retailer_type_link_id"] ) {
		echo ' selected="true"';
	}

	echo ">".$retailer_type_name."</option>";
}
echo '</select>';
mysql_free_result($result);

?>
</td>
</tr>
</td></tr>
<tr valign="top"><td align="right"><font face="Arial" size="+1">Which kind of reps get commission?</font></td><td>
	<select multiple="true" name="valid_rep_types[]" id="valid_rep_types" size="3">
	<?php
		$queryRT = "SELECT rep_types.rep_type_id AS rtID, rep_types.rep_type_desc ";
		if ($retailer_id) {
			$queryRT .=", rrt.*";
		}
		$queryRT .=" FROM rep_types ";
		if ($retailer_id) {
			$queryRT .=" LEFT JOIN retailer_rep_types rrt ON rep_types.rep_type_id=rrt.rep_type_id AND rrt.retailer_id=".$retailer_id;
		}
		$queryRT .=" ORDER BY sequence ASC";
		$resultRT = mysql_query($queryRT) or die("Query failed : " . mysql_error());
		while ($lineRT = mysql_fetch_array($resultRT, MYSQL_ASSOC)) {
			echo '<option value="'.$lineRT["rtID"].'" ';
			if ( $lineRT["retailer_rep_types_id"]!=null ) {
				echo ' SELECTED ';
			}
			echo '>';
			echo $lineRT["rep_type_desc"];
			echo '</option>';

		}
	?>
	</select>
</td></tr>

<tr><td align="right"><font face="Arial" size="+1">Where did you find this Retailer:</font></td><td><input type="text" name="where_store_found" size="30" maxlength="150" value="<?php echo $where_store_found; ?>"></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Last contacted this Retailer by:</font></td><td><select name="last_contact_by">
<option value="">Please Select</option>
<option value="None Yet"<? if($last_contact_by == "None Yet") { echo " SELECTED"; } ?>>None Yet</option>
<option value="Phone"<? if($last_contact_by == "Phone") { echo " SELECTED"; } ?>>Phone</option>
<option value="Mail"<? if($last_contact_by == "Mail") { echo " SELECTED"; } ?>>Mail</option>
<option value="In Person"<? if($last_contact_by == "In Person") { echo " SELECTED"; } ?>>In Person</option>
<option value="Email"<? if($last_contact_by == "Email") { echo " SELECTED"; } ?>>Email</option>
<option value="Fax"<? if($last_contact_by == "Fax") { echo " SELECTED"; } ?>>Fax</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Next contact this Retailer by:</font></td><td><select name="next_contact_by">
<option value="">Please Select</option>
<option value="None"<? if($next_contact_by == "None") { echo " SELECTED"; } ?>>None</option>
<option value="None Yet"<? if($next_contact_by == "None Yet") { echo " SELECTED"; } ?>>None Yet</option>
<option value="Phone"<? if($next_contact_by == "Phone") { echo " SELECTED"; } ?>>Phone</option>
<option value="Mail"<? if($next_contact_by == "Mail") { echo " SELECTED"; } ?>>Mail</option>
<option value="In Person"<? if($next_contact_by == "In Person") { echo " SELECTED"; } ?>>In Person</option>
<option value="Email"<? if($next_contact_by == "Email") { echo " SELECTED"; } ?>>Email</option>
<option value="Fax"<? if($next_contact_by == "Fax") { echo " SELECTED"; } ?>>Fax</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Last Contacted this Retailer On:</font></td><td><font face="Arial" size="+1"><?php echo $last_contact_on_date; ?></font> <?php
    // requires the class
    require "./includes/datepicker/class.datepicker.php";
    
    // instantiate the object
    $db=new datepicker();

    // Preselect Date
	if ( isset($last_contact_on) ) {
		$last_contact_on = str_replace('-','/',$last_contact_on);
		$db->preselectedDate = $last_contact_on;
	}
	
    // set the format in which the date to be returned
    $db->dateFormat = "Y-m-d";
    
    // set start month
    $startMonth = "";
    if($last_contact_on_m != "" && $last_contact_on_m != "00") {
    	$startMonth = $last_contact_on_m;
    }
    
    // set start year
    $startYear = "";
    if($last_contact_on_y != "" && $last_contact_on_y != "0000") {
    	$startYear = $last_contact_on_y;
    }
?>

    <input type="hidden" id="last_contact_on" name="last_contact_on">
    <input type="button" value="Pick Date" onclick="<?=$db->show("last_contact_on", $startMonth, $startYear)?>">
 &nbsp; 
<select name="last_contact_on_h">
<option value="">HR</option>
<option value="01"<? if($last_contact_on_h == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($last_contact_on_h == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($last_contact_on_h == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($last_contact_on_h == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($last_contact_on_h == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($last_contact_on_h == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($last_contact_on_h == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($last_contact_on_h == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($last_contact_on_h == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($last_contact_on_h == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($last_contact_on_h == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($last_contact_on_h == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($last_contact_on_h == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($last_contact_on_h == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($last_contact_on_h == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($last_contact_on_h == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($last_contact_on_h == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($last_contact_on_h == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($last_contact_on_h == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($last_contact_on_h == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($last_contact_on_h == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($last_contact_on_h == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($last_contact_on_h == "23") { echo " SELECTED"; } ?>>23</option>
</select> <select name="last_contact_on_n">
<option value="">Min</option>
<option value="00"<? if($last_contact_on_n == "00") { echo " SELECTED"; } ?>>00</option>
<option value="01"<? if($last_contact_on_n == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($last_contact_on_n == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($last_contact_on_n == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($last_contact_on_n == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($last_contact_on_n == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($last_contact_on_n == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($last_contact_on_n == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($last_contact_on_n == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($last_contact_on_n == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($last_contact_on_n == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($last_contact_on_n == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($last_contact_on_n == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($last_contact_on_n == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($last_contact_on_n == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($last_contact_on_n == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($last_contact_on_n == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($last_contact_on_n == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($last_contact_on_n == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($last_contact_on_n == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($last_contact_on_n == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($last_contact_on_n == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($last_contact_on_n == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($last_contact_on_n == "23") { echo " SELECTED"; } ?>>23</option>
<option value="24"<? if($last_contact_on_n == "24") { echo " SELECTED"; } ?>>24</option>
<option value="25"<? if($last_contact_on_n == "25") { echo " SELECTED"; } ?>>25</option>
<option value="26"<? if($last_contact_on_n == "26") { echo " SELECTED"; } ?>>26</option>
<option value="27"<? if($last_contact_on_n == "27") { echo " SELECTED"; } ?>>27</option>
<option value="28"<? if($last_contact_on_n == "28") { echo " SELECTED"; } ?>>28</option>
<option value="29"<? if($last_contact_on_n == "29") { echo " SELECTED"; } ?>>29</option>
<option value="30"<? if($last_contact_on_n == "30") { echo " SELECTED"; } ?>>30</option>
<option value="31"<? if($last_contact_on_n == "31") { echo " SELECTED"; } ?>>31</option>
<option value="32"<? if($last_contact_on_n == "32") { echo " SELECTED"; } ?>>32</option>
<option value="33"<? if($last_contact_on_n == "33") { echo " SELECTED"; } ?>>33</option>
<option value="34"<? if($last_contact_on_n == "34") { echo " SELECTED"; } ?>>34</option>
<option value="35"<? if($last_contact_on_n == "35") { echo " SELECTED"; } ?>>35</option>
<option value="36"<? if($last_contact_on_n == "36") { echo " SELECTED"; } ?>>36</option>
<option value="37"<? if($last_contact_on_n == "37") { echo " SELECTED"; } ?>>37</option>
<option value="38"<? if($last_contact_on_n == "38") { echo " SELECTED"; } ?>>38</option>
<option value="39"<? if($last_contact_on_n == "39") { echo " SELECTED"; } ?>>39</option>
<option value="40"<? if($last_contact_on_n == "40") { echo " SELECTED"; } ?>>40</option>
<option value="41"<? if($last_contact_on_n == "41") { echo " SELECTED"; } ?>>41</option>
<option value="42"<? if($last_contact_on_n == "42") { echo " SELECTED"; } ?>>42</option>
<option value="43"<? if($last_contact_on_n == "43") { echo " SELECTED"; } ?>>43</option>
<option value="44"<? if($last_contact_on_n == "44") { echo " SELECTED"; } ?>>44</option>
<option value="45"<? if($last_contact_on_n == "45") { echo " SELECTED"; } ?>>45</option>
<option value="46"<? if($last_contact_on_n == "46") { echo " SELECTED"; } ?>>46</option>
<option value="47"<? if($last_contact_on_n == "47") { echo " SELECTED"; } ?>>47</option>
<option value="48"<? if($last_contact_on_n == "48") { echo " SELECTED"; } ?>>48</option>
<option value="49"<? if($last_contact_on_n == "49") { echo " SELECTED"; } ?>>49</option>
<option value="50"<? if($last_contact_on_n == "50") { echo " SELECTED"; } ?>>50</option>
<option value="51"<? if($last_contact_on_n == "51") { echo " SELECTED"; } ?>>51</option>
<option value="52"<? if($last_contact_on_n == "52") { echo " SELECTED"; } ?>>52</option>
<option value="53"<? if($last_contact_on_n == "53") { echo " SELECTED"; } ?>>53</option>
<option value="54"<? if($last_contact_on_n == "54") { echo " SELECTED"; } ?>>54</option>
<option value="55"<? if($last_contact_on_n == "55") { echo " SELECTED"; } ?>>55</option>
<option value="56"<? if($last_contact_on_n == "56") { echo " SELECTED"; } ?>>56</option>
<option value="57"<? if($last_contact_on_n == "57") { echo " SELECTED"; } ?>>57</option>
<option value="58"<? if($last_contact_on_n == "58") { echo " SELECTED"; } ?>>58</option>
<option value="59"<? if($last_contact_on_n == "59") { echo " SELECTED"; } ?>>59</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Next Contact this Retailer On:</font></td><td><font face="Arial" size="+1"><?php echo $next_contact_on_date; ?></font> <?php
    // instantiate the object
    $db=new datepicker();

    // Preselect Date
	if ( isset($next_contact_on) ) {
		$next_contact_on = str_replace('-','/',$next_contact_on);
		$db->preselectedDate = $next_contact_on;
	}
    
    // set the format in which the date to be returned
    $db->dateFormat = "Y-m-d";
    
    // set start month
    $startMonth = "";
    if($next_contact_on_m != "" && $next_contact_on_m != "00") {
    	$startMonth = $next_contact_on_m;
    }
    
    // set start year
    $startYear = "";
    if($next_contact_on_y != "" && $next_contact_on_y != "0000") {
    	$startYear = $next_contact_on_y;
    }
?>

    <input type="hidden" id="next_contact_on" name="next_contact_on">
    <input type="button" value="Pick Date" onclick="<?=$db->show("next_contact_on", $startMonth, $startYear)?>">
 &nbsp; 
<select name="next_contact_on_h">
<option value="">HR</option>
<option value="01"<? if($next_contact_on_h == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($next_contact_on_h == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($next_contact_on_h == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($next_contact_on_h == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($next_contact_on_h == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($next_contact_on_h == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($next_contact_on_h == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($next_contact_on_h == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($next_contact_on_h == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($next_contact_on_h == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($next_contact_on_h == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($next_contact_on_h == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($next_contact_on_h == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($next_contact_on_h == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($next_contact_on_h == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($next_contact_on_h == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($next_contact_on_h == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($next_contact_on_h == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($next_contact_on_h == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($next_contact_on_h == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($next_contact_on_h == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($next_contact_on_h == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($next_contact_on_h == "23") { echo " SELECTED"; } ?>>23</option>
</select> <select name="next_contact_on_n">
<option value="">Min</option>
<option value="00"<? if($next_contact_on_n == "00") { echo " SELECTED"; } ?>>00</option>
<option value="01"<? if($next_contact_on_n == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($next_contact_on_n == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($next_contact_on_n == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($next_contact_on_n == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($next_contact_on_n == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($next_contact_on_n == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($next_contact_on_n == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($next_contact_on_n == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($next_contact_on_n == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($next_contact_on_n == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($next_contact_on_n == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($next_contact_on_n == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($next_contact_on_n == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($next_contact_on_n == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($next_contact_on_n == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($next_contact_on_n == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($next_contact_on_n == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($next_contact_on_n == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($next_contact_on_n == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($next_contact_on_n == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($next_contact_on_n == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($next_contact_on_n == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($next_contact_on_n == "23") { echo " SELECTED"; } ?>>23</option>
<option value="24"<? if($next_contact_on_n == "24") { echo " SELECTED"; } ?>>24</option>
<option value="25"<? if($next_contact_on_n == "25") { echo " SELECTED"; } ?>>25</option>
<option value="26"<? if($next_contact_on_n == "26") { echo " SELECTED"; } ?>>26</option>
<option value="27"<? if($next_contact_on_n == "27") { echo " SELECTED"; } ?>>27</option>
<option value="28"<? if($next_contact_on_n == "28") { echo " SELECTED"; } ?>>28</option>
<option value="29"<? if($next_contact_on_n == "29") { echo " SELECTED"; } ?>>29</option>
<option value="30"<? if($next_contact_on_n == "30") { echo " SELECTED"; } ?>>30</option>
<option value="31"<? if($next_contact_on_n == "31") { echo " SELECTED"; } ?>>31</option>
<option value="32"<? if($next_contact_on_n == "32") { echo " SELECTED"; } ?>>32</option>
<option value="33"<? if($next_contact_on_n == "33") { echo " SELECTED"; } ?>>33</option>
<option value="34"<? if($next_contact_on_n == "34") { echo " SELECTED"; } ?>>34</option>
<option value="35"<? if($next_contact_on_n == "35") { echo " SELECTED"; } ?>>35</option>
<option value="36"<? if($next_contact_on_n == "36") { echo " SELECTED"; } ?>>36</option>
<option value="37"<? if($next_contact_on_n == "37") { echo " SELECTED"; } ?>>37</option>
<option value="38"<? if($next_contact_on_n == "38") { echo " SELECTED"; } ?>>38</option>
<option value="39"<? if($next_contact_on_n == "39") { echo " SELECTED"; } ?>>39</option>
<option value="40"<? if($next_contact_on_n == "40") { echo " SELECTED"; } ?>>40</option>
<option value="41"<? if($next_contact_on_n == "41") { echo " SELECTED"; } ?>>41</option>
<option value="42"<? if($next_contact_on_n == "42") { echo " SELECTED"; } ?>>42</option>
<option value="43"<? if($next_contact_on_n == "43") { echo " SELECTED"; } ?>>43</option>
<option value="44"<? if($next_contact_on_n == "44") { echo " SELECTED"; } ?>>44</option>
<option value="45"<? if($next_contact_on_n == "45") { echo " SELECTED"; } ?>>45</option>
<option value="46"<? if($next_contact_on_n == "46") { echo " SELECTED"; } ?>>46</option>
<option value="47"<? if($next_contact_on_n == "47") { echo " SELECTED"; } ?>>47</option>
<option value="48"<? if($next_contact_on_n == "48") { echo " SELECTED"; } ?>>48</option>
<option value="49"<? if($next_contact_on_n == "49") { echo " SELECTED"; } ?>>49</option>
<option value="50"<? if($next_contact_on_n == "50") { echo " SELECTED"; } ?>>50</option>
<option value="51"<? if($next_contact_on_n == "51") { echo " SELECTED"; } ?>>51</option>
<option value="52"<? if($next_contact_on_n == "52") { echo " SELECTED"; } ?>>52</option>
<option value="53"<? if($next_contact_on_n == "53") { echo " SELECTED"; } ?>>53</option>
<option value="54"<? if($next_contact_on_n == "54") { echo " SELECTED"; } ?>>54</option>
<option value="55"<? if($next_contact_on_n == "55") { echo " SELECTED"; } ?>>55</option>
<option value="56"<? if($next_contact_on_n == "56") { echo " SELECTED"; } ?>>56</option>
<option value="57"<? if($next_contact_on_n == "57") { echo " SELECTED"; } ?>>57</option>
<option value="58"<? if($next_contact_on_n == "58") { echo " SELECTED"; } ?>>58</option>
<option value="59"<? if($next_contact_on_n == "59") { echo " SELECTED"; } ?>>59</option>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">User Who Last Contacted Retailer:</font></td><td>
<?php

$query = "SELECT user_id, first_name, last_name FROM wms_users";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
$userct=0;
$users = array();
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
   foreach ($line as $col => $val) {
        $users[$userct]->$col = $val;//add properties to box
   }
    $userct++;
}

?>

<select name="last_contact_by_person">
<option value="0"<?php if(isset($last_contact_by_person) && $last_contact_by_person == "0") { echo " SELECTED"; } ?>>Nobody</option>

<?php
for($x=0; $x < count($users); $x++) {
	$user_id = $users[$x]->user_id;
	$first_name = $users[$x]->first_name;
	$last_name = $users[$x]->last_name;
	echo "<option value=\"" . $user_id . "\"";
	if($last_contact_by_person == $user_id) { echo " SELECTED"; }
	echo ">" . $first_name . " " . $last_name . "</option>\n";
}

?>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">User Who Next Contacts Retailer:</font></td><td><select name="next_contact_by_person">
<option value="0"<?php if(isset($next_contact_by_person) && $next_contact_by_person == "0") { echo " SELECTED"; } ?>>Nobody</option>

<?php
foreach($users as $user) {
	$user_id = $user->user_id;
	$first_name = $user->first_name;
	$last_name = $user->last_name;
	echo "<option value=\"" . $user_id . "\"";
	if($next_contact_by_person == $user_id) { echo " SELECTED"; }
	echo ">" . $first_name . " " . $last_name . "</option>\n";
}
?>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Sent Promo Pack:</font></td><td><select name="sent_promo_pack">
<option value="">Please Select</option>
<option value="1"<? if(isset($sent_promo_pack) && $sent_promo_pack == "1") { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if(isset($sent_promo_pack) && $sent_promo_pack == "0") { echo " SELECTED"; } ?>>No</option>
<option value="2"<? if(isset($sent_promo_pack) && $sent_promo_pack == "2") { echo " SELECTED"; } ?>>Need To Send</option>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">When did Promo Pack get Sent:</font></td><td><font face="Arial" size="+1"><?php echo $sent_promo_pack_when_date; ?></font> <?php
    // instantiate the object
    $db=new datepicker();

    // Preselect Date
	if ( isset($sent_promo_pack_when) ) {
		$sent_promo_pack_when = str_replace('-','/',$sent_promo_pack_when);
		$db->preselectedDate = $sent_promo_pack_when;
	}
	
    // set the format in which the date to be returned
    $db->dateFormat = "Y-m-d";
    
    // set start month
    $startMonth = "";
    if($sent_promo_pack_when_m != "" && $sent_promo_pack_when_m != "00") {
    	$startMonth = $sent_promo_pack_when_m;
    }
    
    // set start year
    $startYear = "";
    if($sent_promo_pack_when_y != "" && $sent_promo_pack_when_y != "0000") {
    	$startYear = $sent_promo_pack_when_y;
    }
?>

    <input type="hidden" id="sent_promo_pack_when" name="sent_promo_pack_when">
    <input type="button" value="Pick Date" onclick="<?=$db->show("sent_promo_pack_when", $startMonth, $startYear)?>">
 &nbsp; 
<select name="sent_promo_pack_when_h">
<option value="">HR</option>
<option value="01"<? if($sent_promo_pack_when_h == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($sent_promo_pack_when_h == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($sent_promo_pack_when_h == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($sent_promo_pack_when_h == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($sent_promo_pack_when_h == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($sent_promo_pack_when_h == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($sent_promo_pack_when_h == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($sent_promo_pack_when_h == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($sent_promo_pack_when_h == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($sent_promo_pack_when_h == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($sent_promo_pack_when_h == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($sent_promo_pack_when_h == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($sent_promo_pack_when_h == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($sent_promo_pack_when_h == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($sent_promo_pack_when_h == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($sent_promo_pack_when_h == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($sent_promo_pack_when_h == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($sent_promo_pack_when_h == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($sent_promo_pack_when_h == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($sent_promo_pack_when_h == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($sent_promo_pack_when_h == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($sent_promo_pack_when_h == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($sent_promo_pack_when_h == "23") { echo " SELECTED"; } ?>>23</option>
</select> <select name="sent_promo_pack_when_n">
<option value="">Min</option>
<option value="00"<? if($sent_promo_pack_when_n == "00") { echo " SELECTED"; } ?>>00</option>
<option value="01"<? if($sent_promo_pack_when_n == "01") { echo " SELECTED"; } ?>>01</option>
<option value="02"<? if($sent_promo_pack_when_n == "02") { echo " SELECTED"; } ?>>02</option>
<option value="03"<? if($sent_promo_pack_when_n == "03") { echo " SELECTED"; } ?>>03</option>
<option value="04"<? if($sent_promo_pack_when_n == "04") { echo " SELECTED"; } ?>>04</option>
<option value="05"<? if($sent_promo_pack_when_n == "05") { echo " SELECTED"; } ?>>05</option>
<option value="06"<? if($sent_promo_pack_when_n == "06") { echo " SELECTED"; } ?>>06</option>
<option value="07"<? if($sent_promo_pack_when_n == "07") { echo " SELECTED"; } ?>>07</option>
<option value="08"<? if($sent_promo_pack_when_n == "08") { echo " SELECTED"; } ?>>08</option>
<option value="09"<? if($sent_promo_pack_when_n == "09") { echo " SELECTED"; } ?>>09</option>
<option value="10"<? if($sent_promo_pack_when_n == "10") { echo " SELECTED"; } ?>>10</option>
<option value="11"<? if($sent_promo_pack_when_n == "11") { echo " SELECTED"; } ?>>11</option>
<option value="12"<? if($sent_promo_pack_when_n == "12") { echo " SELECTED"; } ?>>12</option>
<option value="13"<? if($sent_promo_pack_when_n == "13") { echo " SELECTED"; } ?>>13</option>
<option value="14"<? if($sent_promo_pack_when_n == "14") { echo " SELECTED"; } ?>>14</option>
<option value="15"<? if($sent_promo_pack_when_n == "15") { echo " SELECTED"; } ?>>15</option>
<option value="16"<? if($sent_promo_pack_when_n == "16") { echo " SELECTED"; } ?>>16</option>
<option value="17"<? if($sent_promo_pack_when_n == "17") { echo " SELECTED"; } ?>>17</option>
<option value="18"<? if($sent_promo_pack_when_n == "18") { echo " SELECTED"; } ?>>18</option>
<option value="19"<? if($sent_promo_pack_when_n == "19") { echo " SELECTED"; } ?>>19</option>
<option value="20"<? if($sent_promo_pack_when_n == "20") { echo " SELECTED"; } ?>>20</option>
<option value="21"<? if($sent_promo_pack_when_n == "21") { echo " SELECTED"; } ?>>21</option>
<option value="22"<? if($sent_promo_pack_when_n == "22") { echo " SELECTED"; } ?>>22</option>
<option value="23"<? if($sent_promo_pack_when_n == "23") { echo " SELECTED"; } ?>>23</option>
<option value="24"<? if($sent_promo_pack_when_n == "24") { echo " SELECTED"; } ?>>24</option>
<option value="25"<? if($sent_promo_pack_when_n == "25") { echo " SELECTED"; } ?>>25</option>
<option value="26"<? if($sent_promo_pack_when_n == "26") { echo " SELECTED"; } ?>>26</option>
<option value="27"<? if($sent_promo_pack_when_n == "27") { echo " SELECTED"; } ?>>27</option>
<option value="28"<? if($sent_promo_pack_when_n == "28") { echo " SELECTED"; } ?>>28</option>
<option value="29"<? if($sent_promo_pack_when_n == "29") { echo " SELECTED"; } ?>>29</option>
<option value="30"<? if($sent_promo_pack_when_n == "30") { echo " SELECTED"; } ?>>30</option>
<option value="31"<? if($sent_promo_pack_when_n == "31") { echo " SELECTED"; } ?>>31</option>
<option value="32"<? if($sent_promo_pack_when_n == "32") { echo " SELECTED"; } ?>>32</option>
<option value="33"<? if($sent_promo_pack_when_n == "33") { echo " SELECTED"; } ?>>33</option>
<option value="34"<? if($sent_promo_pack_when_n == "34") { echo " SELECTED"; } ?>>34</option>
<option value="35"<? if($sent_promo_pack_when_n == "35") { echo " SELECTED"; } ?>>35</option>
<option value="36"<? if($sent_promo_pack_when_n == "36") { echo " SELECTED"; } ?>>36</option>
<option value="37"<? if($sent_promo_pack_when_n == "37") { echo " SELECTED"; } ?>>37</option>
<option value="38"<? if($sent_promo_pack_when_n == "38") { echo " SELECTED"; } ?>>38</option>
<option value="39"<? if($sent_promo_pack_when_n == "39") { echo " SELECTED"; } ?>>39</option>
<option value="40"<? if($sent_promo_pack_when_n == "40") { echo " SELECTED"; } ?>>40</option>
<option value="41"<? if($sent_promo_pack_when_n == "41") { echo " SELECTED"; } ?>>41</option>
<option value="42"<? if($sent_promo_pack_when_n == "42") { echo " SELECTED"; } ?>>42</option>
<option value="43"<? if($sent_promo_pack_when_n == "43") { echo " SELECTED"; } ?>>43</option>
<option value="44"<? if($sent_promo_pack_when_n == "44") { echo " SELECTED"; } ?>>44</option>
<option value="45"<? if($sent_promo_pack_when_n == "45") { echo " SELECTED"; } ?>>45</option>
<option value="46"<? if($sent_promo_pack_when_n == "46") { echo " SELECTED"; } ?>>46</option>
<option value="47"<? if($sent_promo_pack_when_n == "47") { echo " SELECTED"; } ?>>47</option>
<option value="48"<? if($sent_promo_pack_when_n == "48") { echo " SELECTED"; } ?>>48</option>
<option value="49"<? if($sent_promo_pack_when_n == "49") { echo " SELECTED"; } ?>>49</option>
<option value="50"<? if($sent_promo_pack_when_n == "50") { echo " SELECTED"; } ?>>50</option>
<option value="51"<? if($sent_promo_pack_when_n == "51") { echo " SELECTED"; } ?>>51</option>
<option value="52"<? if($sent_promo_pack_when_n == "52") { echo " SELECTED"; } ?>>52</option>
<option value="53"<? if($sent_promo_pack_when_n == "53") { echo " SELECTED"; } ?>>53</option>
<option value="54"<? if($sent_promo_pack_when_n == "54") { echo " SELECTED"; } ?>>54</option>
<option value="55"<? if($sent_promo_pack_when_n == "55") { echo " SELECTED"; } ?>>55</option>
<option value="56"<? if($sent_promo_pack_when_n == "56") { echo " SELECTED"; } ?>>56</option>
<option value="57"<? if($sent_promo_pack_when_n == "57") { echo " SELECTED"; } ?>>57</option>
<option value="58"<? if($sent_promo_pack_when_n == "58") { echo " SELECTED"; } ?>>58</option>
<option value="59"<? if($sent_promo_pack_when_n == "59") { echo " SELECTED"; } ?>>59</option>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Retailer Received Promo Pack:</font></td><td><select name="received_promo_pack">
<option value="">Please Select</option>
<option value="1"<? if(isset($received_promo_pack) && $received_promo_pack == "1") { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if(isset($received_promo_pack) && $received_promo_pack == "0") { echo " SELECTED"; } ?>>No</option>
<option value="2"<? if(isset($received_promo_pack) && $received_promo_pack == "2") { echo " SELECTED"; } ?>>Maybe</option>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Number of Times:</font></td><td><select name="contact_number">
<option value="">Please Select</option>
<?php
for($i = 1; $i < 101; $i++){
	if($i < 10){
		$i = "0".$i;
	}
	echo "<option value=\"$i\"";
	if(isset($contact_number) && $contact_number == $i) {
		echo " SELECTED";
	}
	echo ">$i</option>\n";
}
?>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Retailer For <?php echo $product_line; ?> Status:</font></td><td><select name="retailer_status">
<option value="">Please Select</option>
<?php
$queryRS = "SELECT * FROM retailer_status ORDER BY rs_id";
$resultRS = mysql_query($queryRS) or die("Query failed : " . mysql_error());
while ($lineRS = mysql_fetch_array($resultRS, MYSQL_ASSOC)) {
    echo '<option value="'.$lineRS["rs_id"].'"';
    if ( $lineRS["rs_id"] == $retailer_status ) { echo " SELECTED"; } 
    echo '>'.$lineRS["rs_desc"].'</option>';

}
?>

</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Discount Code:</font></td><td><select name="discount_code">
<option value="">none</option>
<?php

$queryD = "SELECT * FROM discount_codes WHERE status='1' ORDER BY percent_off";
$resultD = mysql_query($queryD) or die("Query failed : " . mysql_error());
while ($lineD = mysql_fetch_array($resultD, MYSQL_ASSOC)) {
	echo '<option value="'.$lineD["discount_code"].'"';
	if ( isset($discount_code) && $discount_code==$lineD["discount_code"] ) { echo " SELECTED"; }
	echo '>'.($lineD["percent_off"]*100).'% off&#160;&#160;&#160;&#160;('.$lineD["location_target"].' - '.$lineD["discount_code"].')</option>';
}

?>
"></select></td></tr>


<tr><td align="right"><font face="Arial" size="+1">Comment and Notes Section:</font></td><td><TEXTAREA name="comments" cols="40" rows="7">
<?php
	if ( isset($comments) ) {
		echo $comments; 
	}
?></TEXTAREA></td></tr>

<tr><td colspan="2" align="center"><font face="Arial" size="+1"><b>Information for Find a Retailer section of website</b></font></td></tr>

<tr><td align="right"><font face="Arial" size="+1">List Store on Website:</font></td><td><select name="list_store_website">
<option value="1"<? if(isset($list_store_website) && $list_store_website == "1") { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if(isset($list_store_website) && $list_store_website == "0") { echo " SELECTED"; } ?>>No</option>
</select></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Store Name for Website:</font></td><td><input type="text" name="store_name_website" size="30" maxlength="150" value="<?php if ( isset($store_name_website) ) echo $store_name_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Name for Website:</font></td><td><input type="text" name="contact_name_website" size="30" maxlength="150" value="<?php if ( isset($contact_name_website) ) echo $contact_name_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Phone for Website:</font></td><td><input type="text" name="contact_phone_website" size="30" maxlength="30" value="<?php if ( isset($contact_phone_website) ) echo $contact_phone_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Fax for Website:</font></td><td><input type="text" name="contact_fax_website" size="30" maxlength="30" value="<?php if ( isset($contact_fax_website) ) echo $contact_fax_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Email for Website:</font></td><td><input type="text" name="contact_email_website" size="30" maxlength="30" value="<?php if ( isset($contact_email_website) ) echo $contact_email_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Contact Website for Website:</font></td><td><input type="text" name="contact_website_website" size="30" maxlength="30" value="<?php if ( isset($contact_website_website) )echo $contact_website_website; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Items Sold for Website:</font></td><td><input type="text" name="items_sold" size="30" maxlength="255" value="<?php if ( isset($items_sold) ) echo $items_sold; ?>"></td></tr>

<tr><td align="right"><font face="Arial" size="+1">Hours and Days of Operation for Website:</font></td><td><input type="text" name="hours_days_operation" size="30" maxlength="255" value="<?php if ( isset($hours_days_operation) ) echo $hours_days_operation; ?>"></td></tr>


<tr><td colspan="2" align="center">
<?php
	if ( $retailer_id ) {
?>
	<input type="submit" name="submit" value=" Modify Retailer ">
	&nbsp; <input type="submit" name="delete" value=" Delete Retailer " onClick="return warn_on_submit()">
	&nbsp; <input type="submit" name="order" value=" Place Order ">
	&nbsp; <input type="button" name="order_history" value=" View Order History " onClick="newWindow('./retailers_admin12.php?retailer_id=<?php echo $retailer_id; ?>','','650','250','resizable,scrollbars,status,toolbar,menubar')">
<?php
	} else {
		echo '<input type="submit" name="submit" value=" Create New Retailer ">';
	}
?>
</td></tr>
</form>
</table></td></tr>

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