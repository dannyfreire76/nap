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
			"last_contact_on" => $last_contact_on_d>0 ? date("Y-m-d H:i:s",strtotime($last_contact_on_d.' '.$last_contact_on_h) ):'',
			"next_contact_on" => $next_contact_on_d>0 ? date("Y-m-d H:i:s",strtotime($next_contact_on_d.' '.$next_contact_on_h) ):'',
			"last_contact_by_person" => $last_contact_by_person,
			"next_contact_by_person" => $next_contact_by_person,
			"store_name" => addslashes($store_name),
			"store_name_checkbox" => $store_name_checkbox,
			"contact_name" => addslashes($contact_name),
			"contact_name_checkbox" => $contact_name_checkbox,
			"username" => $username,
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
			"sent_promo_pack_when" => $sent_promo_pack_when_d>0 ? date("Y-m-d H:i:s",strtotime($sent_promo_pack_when_d.' '.$sent_promo_pack_when_h) ):'',
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
		} else {
			$query = "INSERT INTO retailer SET ";
		}


		$queryB = "";			
		foreach($queryFields as $fld_name=>$fld_val) {
			$queryB .= $queryB == "" ? "" : " , ";
			$queryB .= $fld_name."='".$fld_val."'";
		}

		if ($password != "") {
			$queryB .= ", password='".md5($password)."'";
			send_email_login($email, $contact_name, $username, $password);
		}

		if ($retailer_id) {
			$queryB .= " WHERE retailer_id='$retailer_id'";
		} else {
			$queryB .= ", created='".date("Y-m-d")."'";
		}

		$query = $query.$queryB;

		//echo $query."<br /><br />"; //exit();
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
<tr><td align="right"><font face="Arial" size="+1">Last Contacted this Retailer On:</font></td><td>
<input type="text" name="last_contact_on_d" maxlength="10" size="10" placeholder="mm/dd/yyy" value="<?=$last_contact_on>0 ? date("m/d/Y",strtotime($last_contact_on)): ''?>" />
<select name="last_contact_on_h">
<?php
	$last_contact_hour = date("H",strtotime($last_contact_on) );

	for($x=0; $x<12; $x++) {
		$showThis = $x;
		if ($x==0){ $showThis=12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($last_contact_hour==$x ? ' selected ':'').'>'.$showThis.':00 AM</option>';
	}

	for($x=12; $x<24; $x++) {
		$showThis = $x;
		if ($x > 12){ $showThis = $x - 12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($last_contact_hour==$x ? ' selected ':'').'>'.$showThis.':00 PM</option>';
	}

?>
</select></td></tr>
<tr><td align="right"><font face="Arial" size="+1">Next Contact this Retailer On:</font></td><td><font face="Arial" size="+1"></font>
<input type="text" name="next_contact_on_d" maxlength="10" size="10" placeholder="mm/dd/yyy" value="<?=$next_contact_on>0 ? date("m/d/Y",strtotime($next_contact_on)): ''?>" />
<select name="next_contact_on_h">
<?php
	$next_contact_hour = date("H",strtotime($next_contact_on) );

	for($x=0; $x<12; $x++) {
		$showThis = $x;
		if ($x==0){ $showThis=12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($next_contact_hour==$x ? ' selected ':'').'>'.$showThis.':00 AM</option>';
	}

	for($x=12; $x<24; $x++) {
		$showThis = $x;
		if ($x > 12){ $showThis = $x - 12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($next_contact_hour==$x ? ' selected ':'').'>'.$showThis.':00 PM</option>';
	}

?>
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

<tr><td align="right"><font face="Arial" size="+1">When did Promo Pack get Sent:</font></td><td><font face="Arial" size="+1"></font>
<input type="text" name="sent_promo_pack_when_d" maxlength="10" size="10" placeholder="mm/dd/yyy" value="<?=$sent_promo_pack_when>0 ? date("m/d/Y",strtotime($sent_promo_pack_when)): ''?>" />
<select name="sent_promo_pack_when_h">
<?php
	$sent_promo_pack_when_hour = date("H",strtotime($sent_promo_pack_when) );

	for($x=0; $x<12; $x++) {
		$showThis = $x;
		if ($x==0){ $showThis=12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($sent_promo_pack_when_hour==$x ? ' selected ':'').'>'.$showThis.':00 AM</option>';
	}

	for($x=12; $x<24; $x++) {
		$showThis = $x;
		if ($x > 12){ $showThis = $x - 12; }
		echo '<option value="'.$x.':00" style="text-align:right" '.($sent_promo_pack_when_hour==$x ? ' selected ':'').'>'.$showThis.':00 PM</option>';
	}

?>
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