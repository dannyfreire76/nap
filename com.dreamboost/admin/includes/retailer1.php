<?php
// BME WMS
// Page: Retailer Manager Included Functions
// Path/File: /admin/includes/retailer1.php
// Version: 1.1
// Build: 1101
// Date: 12-07-2006

function send_email_login($email, $contact_name, $username, $password) {
	global $website_title;
	global $base_url;
	global $site_email;
	if($email != "") {
		// Send email with password
		$email_str = "Dear ";
		$email_str .= $contact_name . ",\n\n";
		$email_str .= "Your account's login information was recently changed, by you or a site administrator. Please find the login details ";
		$email_str .= "for your " . $website_title . " Retailer account listed below. We recommend ";
		$email_str .= "keeping a copy of this email in a safe place for ";
		$email_str .= "future use.\n\n";
		$email_str .= "Login Information:\n";
		$email_str .= "Username: " . $username . "\n";
		$email_str .= "Password: " . $password . "\n";
		$email_str .= $base_url . "\n\n";
						
		$subject = $website_title . " Retailer Login Info";

		$email_subj = $subject;
		$email_tmp = $site_email;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);
	}
}

function check_user($username, $retailer_id) {
	if($username != "") {
		$query = "SELECT username FROM retailer";
		$query .= " WHERE username='$username'";
		if($retailer_id != 0) {
			$query .= " AND retailer_id!='$retailer_id'";
		}
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$tmp_username = $line["username"];
		}
		mysql_free_result($result);
		if($tmp_username == "" && $tmp_username != $username) { return 1; }
		elseif($tmp_username != "" && $tmp_username == $username) { return -1; }
	} else {
		return 1;
	}
}

function getOrderUserID($receipt_id) {
	global $main_table;

	$orderUID = "";
	if ( $main_table=='receipt' ) {
		$queryUID = "SELECT user_id FROM ".$main_table."s WHERE ".$main_table."_id='$receipt_id'";
		$resultUID = mysql_query($queryUID) or die("queryUID failed: " . mysql_error());
		while ($lineUID = mysql_fetch_array($resultUID, MYSQL_ASSOC)) {
			$orderUID = $lineUID["user_id"];
		}
	}
	
	return $orderUID;
}


?>