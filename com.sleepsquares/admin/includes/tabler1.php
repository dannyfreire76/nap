<?php
// BME WMS
// Page: Table Maker Included Functions
// Path/File: /admin/includes/tabler1.php
// Version: 1.8
// Build: 1802
// Date: 06-10-2007

function getColumnHeaders($url, $page_this, $labels, $fields, $store_name='', $contact_name='', $city='', $state='', $zip='', $country='', $phone='', $user_to_contact='', $addtl_flds='') {
	
	$field = $_REQUEST["field"];
	$dir = $_REQUEST["dir"];
	$store_name = urlencode($store_name);
	$contact_name = urlencode($contact_name);
	$city = urlencode($city);
	$state = urlencode($state);
	$zip = urlencode($zip);
	$country = urlencode($country);
	$phone = urlencode($phone);
	$user_to_contact = urlencode($user_to_contact);
    $where_store_found = $_REQUEST["where_store_found"];
	$funds_owed = $_REQUEST["funds_owed"];
	$next_contact_on = $_REQUEST["next_contact_on"];
	$retailer_type = $_REQUEST["retailer_type"];
	$retailer_str = "";
	if ( is_array($retailer_type) ) {
		foreach($retailer_type as $this_type) {
			$retailer_str .= "&retailer_type[]=".$this_type;
		}
	}


	for($i=0;$i<count($labels);$i++) {
		echo "<th><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"maintablenone\">";
		echo "<tr><td>";
		echo $labels[$i];
		echo "</td><td>&nbsp;</td>";
		echo "<td><a href=\"$url?page_this=$page_this&field=".$fields[$i]."&dir=ASC".$addtl_flds;
		if($store_name) {
			echo "&store_name=$store_name";
		}
		if($contact_name) {
			echo "&contact_name=$contact_name";
		}
		if($city) {
			echo "&city=$city";
		}
		if($state) {
			echo "&state=$state";
		}
		if($zip) {
			echo "&zip=$zip";
		}
		if($country) {
			echo "&country=$country";
		}
		if($phone) {
			echo "&phone=$phone";
		}
		if($user_to_contact) {
			echo "&user_to_contact=$user_to_contact";
		}
		if($where_store_found) {
			echo "&where_store_found=$where_store_found";
		}
		if($funds_owed) {
			echo "&funds_owed=$funds_owed";
		}		
		if($next_contact_on) {
			echo "&next_contact_on=$next_contact_on";
		}
		if($retailer_str) {
			echo $retailer_str;
		}

		echo "\">";
		echo "<img src=\"/images/wms/up.gif\" width=\"16\" height=\"16\" alt=\"Up\" border=\"";
		if ( $dir=='ASC' && $fields[$i]==$field ) {
			echo '1';
		}
		else {
			echo '0';
		}
		echo '"';
		echo "></a></td>";
		echo "<td><a href=\"$url?page_this=$page_this&field=".$fields[$i]."&dir=DESC".$addtl_flds;
		if($store_name) {
			echo "&store_name=$store_name";
		}
		if($contact_name) {
			echo "&contact_name=$contact_name";
		}
		if($city) {
			echo "&city=$city";
		}
		if($state) {
			echo "&state=$state";
		}
		if($zip) {
			echo "&zip=$zip";
		}
		if($country) {
			echo "&country=$country";
		}
		if($phone) {
			echo "&phone=$phone";
		}
		if($user_to_contact) {
			echo "&user_to_contact=$user_to_contact";
		}
		if($where_store_found) {
			echo "&where_store_found=$where_store_found";
		}
		if($funds_owed) {
			echo "&funds_owed=$funds_owed";
		}
		if($next_contact_on) {
			echo "&next_contact_on=$next_contact_on";
		}
		if($retailer_str) {
			echo $retailer_str;
		}

		echo "\">";
		echo "<img src=\"/images/wms/down.gif\" width=\"16\" height=\"16\" alt=\"Down\" border=\"";
		if ( $dir=='DESC' && $fields[$i]==$field ) {
			echo '1';
		}
		else {
			echo '0';
		}
		echo '"';
		echo "></a>";
		echo "</td></tr></table></th>";
	}
}
?>