<?php
// BME WMS
// Page: Pagination Included Functions
// Path/File: /admin/includes/pagination1.php
// Version: 1.8
// Build: 1803
// Date: 06-10-2007

function pagination_display($url, $page_this, $limit, $record_count, $field='', $dir='', $store_name='', $contact_name='', $city='', $state='', $zip='', $country='', $phone='', $user_to_contact='', $where_store_found='', $addtl_flds='') {
	
	$store_name = urlencode($store_name);
	$contact_name = urlencode($contact_name);
	$city = urlencode($city);
	$state = urlencode($state);
	$zip = urlencode($zip);
	$country = urlencode($country);
	$phone = urlencode($phone);
	$user_to_contact = urlencode($user_to_contact);
    $where_store_found = urlencode($_REQUEST["where_store_found"]);
	$funds_owed = urlencode($_REQUEST["funds_owed"]);
	$retailer_type = $_REQUEST["retailer_type"];
	$retailer_str = "";
	if ( is_array($retailer_type) ) {
		foreach($retailer_type as $this_type) {
			$retailer_str .= "&retailer_type[]=".$this_type;
		}
	}
	
	$page_count = ceil($record_count / $limit);
	$page_next = $page_this + 1;
	$page_prev = $page_this - 1;
	
	echo "<tr><td align=\"left\"><font size=\"2\">";
	if($page_prev > 0) {
		echo "<a href=\"./$url?page_this=$page_prev".$addtl_flds;
		if($field) {
			echo "&field=$field";
		}
		if($dir) {
			echo "&dir=$dir";
		}
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
		if($retailer_str) {
			echo $retailer_str;
		}

		echo "\">&lt;&lt;Previous</a> ";
	}

	for($i=1;$i<=$page_count;$i++) {
		if($page_this != $i) {
			echo "<a href=\"./$url?page_this=$i".$addtl_flds;
			if($field) {
				echo "&field=$field";
			}
			if($dir) {
				echo "&dir=$dir";
			}
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
			if($retailer_str) {
				echo $retailer_str;
			}
			echo "\">";
		}
		echo $i;
		if($page_this != $i) {
			echo "</a>";
		}
		echo " ";
	}

	if($page_next <= $page_count) {
		echo "<a href=\"./$url?page_this=$page_next".$addtl_flds;
		if($field) {
			echo "&field=$field";
		}
		if($dir) {
			echo "&dir=$dir";
		}
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
		if($retailer_str) {
			echo $retailer_str;
		}

		echo "\">Next&gt;&gt;</a>";
	}

	echo "</font></td></tr>\n";
}
?>