<?php
// BME WMS
// Page: My Update Retailer page
// Path/File: /wc/my/update_retailer.php
// Version: 1.1
// Build: 1102
// Date: 11-01-2006

header('Content-type: text/html; charset=utf-8');
include '../../includes/main1.php';
include '../../includes/wc1.php';
include $base_path.'includes/st_and_co1.php';
include $base_path.'admin/includes/retailer1.php';

check_wholesale_login();


foreach($_POST as $key => $value)
{
    $$key = $value;
}

$query = "SELECT product_line, product FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
	$product = $line["product"];
}
mysql_free_result($result);

if($submit != "") {
	$error_txt = "";
   	$error_fields = array();

	if($username != "") {
		$queryName = "SELECT * FROM retailer WHERE username='".$username."' AND retailer_id !='".$retailer_id."'";
		//echo $queryName;
		$resultName = mysql_query($queryName) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultName)>0 ) {
			$error_txt .= 'That Username is already taken.  Please try again.';
			array_push($error_fields, "username");
		}
	}

    $pw_set = false;
    if($_POST["new_pw"] != "") {
        $pw_set = true;
        
        if ( $_POST["new_pw"] != $_POST["confirm_new_pw"] ) {
            $error_txt .= "The values in the password fields do not match.<br>\n";
    		array_push($error_fields, "new_pw");
            array_push($error_fields, "confirm_new_pw");
        }
        else if ( strlen($_POST["new_pw"]) < 7 || strlen($_POST["new_pw"]) >10 ) {
            $error_txt .= "Your password must be 7-10 characters long.<br>\n";
    		array_push($error_fields, "new_pw");
            array_push($error_fields, "confirm_new_pw");
        }
    }

	if($username == "") { $error_txt .= "Please enter your Username.<br>\n"; array_push($error_fields, "username"); }
	if($store_name == "") { $error_txt .= "Please enter your Store Name.<br>\n"; array_push($error_fields, "store_name"); }
	if($contact_name == "") { $error_txt .= "Please enter your Contact Name.<br>\n"; array_push($error_fields, "contact_name"); }
	if($address1 == "") { $error_txt .= "Please enter your Address.<br>\n"; array_push($error_fields, "address1"); }
	if($city == "") { $error_txt .= "Please enter your City.<br>\n"; array_push($error_fields, "city"); }
	if($state == "") { $error_txt .= "Please enter your State.<br>\n"; array_push($error_fields, "state"); }
	if($zip == "") { $error_txt .= "Please enter your Zip/Postal Code.<br>\n"; array_push($error_fields, "zip"); }
	if($country == "") { $error_txt .= "Please enter your Country.<br>\n"; array_push($error_fields, "country"); }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){ $error_txt .= "Please enter a valid Email.<br>\n"; array_push($error_fields, "email"); }
	if($phone == "") { $error_txt .= "Error, you did not enter a Phone Number. Please enter your phone number.<br>\n"; array_push($error_fields, "phone"); }
	if($list_store_website == 1) {
		if($store_name_website == "") { $error_txt .= "Please enter your Store name.<br>\n"; array_push($error_fields, "store_name"); }
	}
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE retailer SET";
		$query .= " username='".$username."',";
		$query .= " store_name_checkbox='$store_name_checkbox',";
		$query .= " contact_name='".addslashes($contact_name)."',";
		$query .= " contact_name_checkbox='$contact_name_checkbox',";
		$query .= " password=md5('$password'),";
		$query .= " address1='".addslashes($address1)."',";
		$query .= " address2='".addslashes($address2)."',";
		$query .= " city='".addslashes($city)."',";
		$query .= " state='$state',";
		$query .= " zip='$zip',";
		$query .= " country='$country',";
		$query .= " email='$email',";
		$query .= " email_checkbox='$email_checkbox',";
		$query .= " phone='$phone',";
		$query .= " phone_checkbox='$phone_checkbox',";
		$query .= " fax_other_phone='$fax_other_phone',";
		$query .= " fax_other_phone_checkbox='$fax_other_phone_checkbox',";
		$query .= " website='$website',";
		$query .= " website_checkbox='$website_checkbox',";
		$query .= " received_promo_pack='$received_promo_pack',";
		$query .= " list_store_website='$list_store_website',";
		$query .= " store_name_website='".addslashes($store_name_website)."',";
		$query .= " contact_name_website='".addslashes($contact_name_website)."',";
		$query .= " contact_phone_website='$contact_phone_website',";
		$query .= " contact_fax_website='$contact_fax_website',";
		$query .= " contact_email_website='$contact_email_website',";
		$query .= " contact_website_website='$contact_website_website',";
		$query .= " items_sold='".addslashes($items_sold)."',";
        $query .= " hours_days_operation='".addslashes($hours_days_operation)."'";

        if($pw_set) {
            $query .= ", password= '".md5($new_pw)."' ";
			send_email_login($email, $contact_name, $username, $new_pw);
        }

		$query .= " WHERE retailer_id='$retailer_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

	}
}

if($send_login_email != "") {
	send_email_login($email, $contact_name, $username, $password);
}

$query = "SELECT * FROM retailer WHERE retailer_id='$retailer_id'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$last_login = $line["last_login"];
	$store_name = stripslashes($line["store_name"]);
	$store_name_checkbox = $line["store_name_checkbox"];
	$contact_name = stripslashes($line["contact_name"]);
	$contact_name_checkbox = $line["contact_name_checkbox"];
	$username = $line["username"];
	$password = $line["password"];
	$address1 = stripslashes($line["address1"]);
	$address2 = stripslashes($line["address2"]);
	$city = stripslashes($line["city"]);
	$state = $line["state"];
	$zip = $line["zip"];
	$country = $line["country"];
	$email = $line["email"];
	$email_checkbox = $line["email_checkbox"];
	$phone = $line["phone"];
	$phone_checkbox = $line["phone_checkbox"];
	$fax_other_phone = $line["fax_other_phone"];
	$fax_other_phone_checkbox = $line["fax_other_phone_checkbox"];
	$website = $line["website"];
	$website_checkbox = $line["website_checkbox"];
	$received_promo_pack = $line["received_promo_pack"];
	$list_store_website = $line["list_store_website"];
	$store_name_website = stripslashes($line["store_name_website"]);
	$contact_name_website = stripslashes($line["contact_name_website"]);
	$contact_phone_website = $line["contact_phone_website"];
	$contact_fax_website = $line["contact_fax_website"];
	$contact_email_website = $line["contact_email_website"];
	$contact_website_website = $line["contact_website_website"];
	$items_sold = stripslashes($line["items_sold"]);
	$hours_days_operation = stripslashes($line["hours_days_operation"]);
	$logins = $line["logins"];
	$credit = $line["credit"];
}
mysql_free_result($result);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: My <?php echo $website_title; ?> Update Retailer</title>
<script language="JavaScript">
function doEcho()
{
	if (document.update_retailer.store_name_checkbox.checked) {
		if (document.update_retailer.store_name_website.value=="") {
			document.update_retailer.store_name_website.value=document.update_retailer.store_name.value;
		}
	}		
	if (document.update_retailer.contact_name_checkbox.checked) {
		if (document.update_retailer.contact_name_website.value=="") {
			document.update_retailer.contact_name_website.value=document.update_retailer.contact_name.value;
		}
	}		
	if (document.update_retailer.email_checkbox.checked) {
		if (document.update_retailer.contact_email_website.value=="") {
			document.update_retailer.contact_email_website.value=document.update_retailer.email.value;
		}
	}		
	if (document.update_retailer.phone_checkbox.checked) {
		if (document.update_retailer.contact_phone_website.value=="") {
			document.update_retailer.contact_phone_website.value=document.update_retailer.phone.value;
		}
	}		
	if (document.update_retailer.fax_other_phone_checkbox.checked) {
		if (document.update_retailer.contact_fax_website.value=="") {
			document.update_retailer.contact_fax_website.value=document.update_retailer.fax_other_phone.value;
		}
	}		
	if (document.update_retailer.website_checkbox.checked) {
		if (document.update_retailer.contact_website_website.value=="") {
			document.update_retailer.contact_website_website.value=document.update_retailer.website.value;
		}
	}		
}
function genPassword(newPass) {
	document.update_retailer.password.value = newPass;
}
</script>
<?php
include $base_path.'includes/meta1.php';
echo '<script language="JavaScript">';
    if ($error_fields) {
        echo "$(function() {//on doc ready\n";
        foreach ($error_fields as $bad_field) {
            echo "
                if ( $(':input[@name=".$bad_field."]').parents('td:first').siblings(':first').size()>0 ) {
                    $(':input[@name=".$bad_field."]').parents('td:first').siblings(':first').addClass('error');
                }
                else {
                    $(':input[@name=".$bad_field."]').parents('td:first').addClass('error');
                }
            ";
        }
        echo "})";
    }
echo '</script>
</head>
<body>
<div align="center">';

include '../../includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font class="style4" face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">My <?php echo $website_title; ?> Store Information</font></td></tr>
<tr><td>&#160;</td></tr>

<?php

//Error Messages
if($error_txt) {
	echo "<tr><td class=\"error\">$error_txt</td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>

<tr><td align="left" valign="top">
<font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+1">Please enter your store information into the fields below to update your My <?php echo $website_title; ?> Store Information.</font><br>
<table border="0">
<form name="update_retailer" action="./update_retailer.php" method="POST">
<input type="hidden" name="retailer_id" value="<?=$retailer_id?>">
<tr><td align="center"><table border="0">
<tr><td align="right">Username:</td><td align="left"><input type="text" name="username" id="username" value="<?php echo $username; ?>" size="11" maxlength="50" /></td></tr>
<tr><td align="right">Store Name:</td><td align="left"><input type="text" name="store_name" size="30" maxlength="255" value="<?php echo $store_name; ?>" onChange="doEcho()"> <input type="checkbox" name="store_name_checkbox" value="1"<?php if($store_name_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site</td></tr>
<tr><td align="right">Contact Name:</td><td align="left"><input type="text" name="contact_name" size="30" maxlength="150" value="<?php echo $contact_name; ?>" onChange="doEcho()"> <input type="checkbox" name="contact_name_checkbox" value="1"<?php if($contact_name_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site</td></tr>

<?php
if ($credit > 0) {
?>
	<tr><td align="right">Credit:</td><td align="left">$<?=condDecimalFormat($credit)?></td></tr>
<?php
}
?>

<tr><td colspan="2" align="center"><hr></td></tr>
<tr><td align="right">New Password:</td><td align="left"><input type="password" autocomplete="off" name="new_pw" id="new_pw" size="11" minlength="7" maxlength="10" optional="true" /></td></tr>
<tr><td align="right">Confirm New Password:</td><td align="left"><input type="password" name="confirm_new_pw" autocomplete="off" id="confirm_new_pw" size="11" minlength="7" maxlength="10" optional="true" /></td></tr>
<tr><td colspan="2" align="center"><hr></td></tr>
<tr><td align="right">Address1:</td><td align="left"><input type="text" name="address1" size="30" maxlength="150" value="<?php echo $address1; ?>"></td></tr>
<tr><td align="right">Address2:</td><td align="left"><input type="text" name="address2" size="30" maxlength="150" value="<?php echo $address2; ?>"></td></tr>
<tr><td align="right">City:</td><td align="left"><input type="text" name="city" size="30" maxlength="100" value="<?php echo $city; ?>"></td></tr>
<tr><td align="right">State/Province:</td><td align="left"><select name="state">
<?php
    state_build_all($state)
?>
</select></td></tr>
<tr><td align="right">Zip/Postal Code:</td><td align="left"><input type="text" name="zip" size="10" maxlength="10" value="<?php echo $zip; ?>"></td></tr>
<tr><td align="right">Country:</td><td align="left"><select name="country">
<?php
    country_build_all($country)
?>

</select></td></tr>
<tr><td align="right">Email:</td><td align="left"><input type="text" name="email" size="30" maxlength="255" value="<?php echo $email; ?>" onChange="doEcho()"> <input type="checkbox" name="email_checkbox" value="1"<?php if($email_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site<input type="hidden" name="old_email" value="<?php if($email != "") { echo $email; } ?>"></td></tr>
<tr><td align="right">Phone:</td><td align="left"><input type="text" name="phone" size="30" maxlength="30" value="<?php echo $phone; ?>" onChange="doEcho()"> <input type="checkbox" name="phone_checkbox" value="1"<?php if($phone_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site</td></tr>
<tr><td align="right">Fax/Other Phone:</td><td align="left"><input type="text" name="fax_other_phone" size="30" maxlength="30" value="<?php echo $fax_other_phone; ?>" onChange="doEcho()"> <input type="checkbox" name="fax_other_phone_checkbox" value="1"<?php if($fax_other_phone_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site</td></tr>
<tr><td align="right">Website:</td><td align="left"><input type="text" name="website" size="30" maxlength="255" value="<?php echo $website; ?>" onChange="doEcho()"> <input type="checkbox" name="website_checkbox" value="1"<?php if($website_checkbox == "1") { echo " CHECKED"; } ?> onClick="doEcho()"> <font face="Arial" size="0">Display on Site</td></tr>

<tr><td align="right">Did you received the Promo Pack?</td><td align="left"><select name="received_promo_pack">
<option>Please Select</option>
<option value="1"<? if($received_promo_pack == "1") { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if($received_promo_pack == "0") { echo " SELECTED"; } ?>>No</option>
<option value="2"<? if($received_promo_pack == "2") { echo " SELECTED"; } ?>>Maybe</option>
</select></td></tr>

<tr><td colspan="2" align="center"><hr></td></tr>

<tr><td colspan="2" align="center"><b>Information to display in Find a Retailer section of website</b></td></tr>

<tr><td align="right">List Store on Website:</td><td align="left"><select name="list_store_website">
<option value="1"<? if($list_store_website == "1") { echo " SELECTED"; } ?>>Yes</option>
<option value="0"<? if($list_store_website == "0") { echo " SELECTED"; } ?>>No</option>
</select></td></tr>

<tr><td align="right">Store Name:</td><td align="left"><input type="text" name="store_name_website" size="30" maxlength="150" value="<?php echo $store_name_website; ?>"></td></tr>

<tr><td align="right">Contact Name:</td><td align="left"><input type="text" name="contact_name_website" size="30" maxlength="150" value="<?php echo $contact_name_website; ?>"></td></tr>

<tr><td align="right">Contact Phone:</td><td align="left"><input type="text" name="contact_phone_website" size="30" maxlength="30" value="<?php echo $contact_phone_website; ?>"></td></tr>

<tr><td align="right">Contact Fax:</td><td align="left"><input type="text" name="contact_fax_website" size="30" maxlength="30" value="<?php echo $contact_fax_website; ?>"></td></tr>

<tr><td align="right">Contact Email:</td><td align="left"><input type="text" name="contact_email_website" size="30" maxlength="30" value="<?php echo $contact_email_website; ?>"></td></tr>

<tr><td align="right">Contact Website:</td><td align="left"><input type="text" name="contact_website_website" size="30" maxlength="30" value="<?php echo $contact_website_website; ?>"></td></tr>

<tr><td align="right">Items Sold:</td><td align="left"><input type="text" name="items_sold" size="30" maxlength="255" value="<?php echo $items_sold; ?>"></td></tr>

<tr><td align="right">Hours and Days of Operation:</td><td align="left"><input type="text" name="hours_days_operation" size="30" maxlength="255" value="<?php echo $hours_days_operation; ?>"></td></tr>


<tr><td colspan="2" align="center"><input type="submit" name="submit" value=" Save "></td></tr>
</form>
</table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>
</td></table>

<?php
include '../../includes/foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>