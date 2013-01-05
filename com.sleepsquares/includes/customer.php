<?php

function checkCustomerLogin() {
	global $member_id, $base_url;
    if( !$member_id ) {
        header("Location: ".$base_url);
        exit;
    }
}

function doLogin($this_member_id) {
	global $dbh_master, $user_id, $free_prods_arr;

	if ( !$user_id ) {//when called remotely using an image, user_id is not set
		$user_id = $_COOKIE["nap_user"];
	}

	$query = "SELECT * FROM members WHERE member_id='".$this_member_id."'";
	$resultLogin = mysql_query($query) or die("Query failed : " . mysql_error());

	while ($line = mysql_fetch_array($resultLogin, MYSQL_ASSOC)) {
		$_SESSION["member_id"] = $this_member_id;
		$_SESSION["member_name"] = $line["first_name"].' '.$line["last_name"];
		$_SESSION["member_email"] = $line["email"];
		$_SESSION["profile_id"] = $line["customer_profile_id"];

		foreach($line as $col=>$val) {
			$_SESSION['address_info']["".$col.""] = $val;
		}

		$queryCode = "SELECT * FROM discount_codes WHERE status='1' AND discount_code='".$line["q_disc_code"]."'";
		$resultCode = mysql_query($queryCode) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultCode)>0 ) {
			while ($lineCode = mysql_fetch_array($resultCode, MYSQL_ASSOC)) {
				if ( $lineCode["expire_days"] && $lineCode["expire_days"]!=0 ) {
					$code_created = strtotime($lineCode["created"]);
					$todays_date = date("Y-m-d");
					$today = strtotime($todays_date);
					$exp_days = $lineCode["expire_days"];
					$exp_sec = $exp_days * 60 * 60 * 24;

					$days_left = floor( ($code_created + $exp_sec  - $today ) / (60 * 60 * 24) );
					if ( $days_left > 0 ) {
						$_SESSION["active_discount_code"] = $line["q_disc_code"];
					}
				}
			}
		}
		else {
			//remove code from user
			$quest_disc2 = "UPDATE members SET q_disc_code = NULL WHERE member_id = '".$this_member_id."';";
			$resultQuest2 = mysql_query($quest_disc2) or die("Query failed : " . mysql_error());
		}
	
		//now update the cart user_id (which comes from a cookie) for this member_id w/whatever the cookie is right now (for previously saved items when user was logged in but had a diff cookie)
		// AND
		//update the cart member_id for any items that have the current cookie (i.e.: might have added to cart before logging in)
		$upCookie = "UPDATE cart SET user_id='".$user_id."', member_id='".$_SESSION["member_id"]."' WHERE site='".$_SERVER['HTTP_HOST']."' AND ( (member_id='".$_SESSION["member_id"]."' AND member_id!=0) OR user_id='".$user_id."' )";
		mysql_query($upCookie, $dbh_master) or die("Query failed : " . mysql_error());

		//Track login
		$now = date("Y-m-d H:i:s");
		$logins = $logins + 1;
		$query = "UPDATE members SET last_login='$now', logins='$logins' WHERE member_id='$this_member_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		//now combine same SKUs in cart													//keep the ORDER BY so free_prods_arr delete below keeps most recent special
		$querySKU1 = "SELECT *, SUM(quantity) AS quantity FROM cart WHERE user_id='$user_id' GROUP BY member_id, user_id, sku, site, name ORDER BY created DESC;";
		$resultSKU1 = mysql_query($querySKU1, $dbh_master) or die("Query failed: " . mysql_error());
		
		while ($lineSKU1 = mysql_fetch_array($resultSKU1, MYSQL_ASSOC)) {
            $tmp_sku = $lineSKU1["sku"];
			$tmp_site = $lineSKU1["site"];
			$now = date("Y-m-d H:i:s");
			$querySKU2 = "INSERT INTO cart set created='$now'";

			foreach($lineSKU1 as $col=>$val) {
				if ( $col!="total_qty" && $col!="cart_id" && $col!="created" && $col!="modified" ) {
					$querySKU2 .= ", ".$col." = '".$val."'";
				}
			}
			
			$resultSKU2 = mysql_query($querySKU2, $dbh_master) or die("Query failed: " . mysql_error());

			$this_cart_id = mysql_insert_id($dbh_master);

			$queryDelSku = "DELETE FROM cart WHERE user_id='$user_id' AND sku='$tmp_sku' AND site='$tmp_site' AND cart_id!='".$this_cart_id."'";
			$resultDelSku = mysql_query($queryDelSku, $dbh_master) or die("Query failed : " . mysql_error());
		}

		//only allow 1 special deal in the cart
		$queryX = "SELECT * FROM cart WHERE user_id='$user_id' AND site='".$_SERVER['HTTP_HOST']."' ORDER BY created DESC";
		$resultX = mysql_query($queryX, $dbh_master) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultX) > 0 ) {
			$special_already_found=false;
			while ($lineX = mysql_fetch_array($resultX, MYSQL_ASSOC)) {

				$query2 = "SELECT prod_id FROM product_skus WHERE sku='".$lineX["sku"]."'";
				$result2 = mysql_query($query2) or die("query2 failed: " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$tmp_prod_id = $line2["prod_id"];
				}

				if ( in_array($tmp_prod_id, $free_prods_arr) ) {
					//only keep the first special deal item you find
					if ( $special_already_found ) {
						$queryDelSpc = "DELETE FROM cart WHERE cart_id='".$lineX["cart_id"]."';";
						$resultDelSpc = mysql_query($queryDelSpc, $dbh_master) or die("queryDelSpc failed: " . mysql_error());
					} else {//in case same sku was already combined above, make sure special deal is set to quantity of 1
						$queryUpSpc = "UPDATE cart SET quantity='1' WHERE cart_id='".$lineX["cart_id"]."';";
						$resultUpSpc = mysql_query($queryUpSpc, $dbh_master) or die("queryDelSpc failed: " . mysql_error());
					}

					$special_already_found = true;
				}
			}
		}
	}
}

function duplicateMember($referrerDBH, $emailToDupe, $siteToWriteDBH) {
	global $dbh, $member_id;

	if ( !$siteToWriteDBH ) {
		$siteToWriteDBH = $dbh;
	}

	//grab the member record form the referring site
	$queryMem = "SELECT * FROM members WHERE email='".$emailToDupe."' LIMIT 1";//the LIMIT 1 should be redundant, but you never know
	$resultMem = mysql_query($queryMem, $referrerDBH) or die("Query failed : " . mysql_error());
	//insert the record found in the referring site into THIS site
	$now = date("Y-m-d H:i:s");
	$insertQuery = "INSERT INTO members SET created='$now' ";
	
	while ($line = mysql_fetch_array($resultMem, MYSQL_ASSOC)) {
		foreach($line as $col=>$val) {
			if ( $col != "member_id" && $col != "created" ) {

				//check that the username (which also has to be unique) doesn't already exist in this db
				if ( $col=='username' ) {				
					//make sure we have a unique name
					$val = findUniqueName($val, 0, $siteToWriteDBH);
				}

				$insertQuery .= ", ".$col."='".$val."'";

			}
		}

		//INSERT into this site's db now, suppressing errors so image still displays
		$resultOfInsert = mysql_query($insertQuery, $siteToWriteDBH);// or die("Insert query failed : " . mysql_error());

		if ( !$member_id ) {
			if ( mysql_affected_rows($siteToWriteDBH) > 0 ) {
				doLogin( mysql_insert_id() );//this wouldn't work for calls that pass in the siteToWriteDBH anyway, since it's from the host site and they're already logged in
			}
		}
	}
}

function findUniqueName($baseUname, $intToAdd, $siteToWriteDBH) {
	global $dbh;

	if ( !$siteToWriteDBH ) {
		$siteToWriteDBH = $dbh;
	}

	$unameToAdd = $baseUname;
	if ( $intToAdd > 0 )  {
		$unameToAdd = $baseUname.$intToAdd;
	}
	$checkNameQuery = "SELECT * FROM members WHERE username='".$unameToAdd."'";
	$resultCheckName = mysql_query($checkNameQuery, $siteToWriteDBH) or die("checkNameQuery failed : " . mysql_error());

	if ( mysql_affected_rows($siteToWriteDBH)>0 ) {
		$nextInt = $intToAdd * 1+1;
		return findUniqueName( $baseUname, $nextInt, $siteToWriteDBH );
	} else {
		return $unameToAdd;
	}

}

function check_dup_email($email, $thisHandle) {
	$email_test = "";
	$query = "SELECT member_id, email FROM members WHERE email='$email'";
	
	if ($thisHandle) {
		$result = mysql_query($query, $thisHandle) or die("Query failed : " . mysql_error());
	} else {
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$this_member_id = $line["member_id"];
		$email_test = $line["email"];
	}
	mysql_free_result($result);
	if($email_test == "") { return -1; }
	else { return $this_member_id; }
}

function checkPartnerSiteMembers($member_email) {
	global $URL, $user_id;

	$imgStr = "";
	$queryCookie = "SELECT * FROM partner_sites WHERE site_url != '".$_SERVER["HTTP_HOST"]."'";
	$resultCookie = mysql_query($queryCookie) or die("queryCookie failed: " . mysql_error());
	while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {
		//img src actually executes the code on the other server
		$imgStr .= '<img style="display:none" src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineCookie["site_url"].'/'.(strpos($URL, '/staging') ? "staging/" : "").'includes/cookie_control.php?checkEmail='.$member_email.'&referrer='.$_SERVER['HTTP_HOST'].'&timestamp='.time().'" />';
	}

	return $imgStr;
}


function logoutMemberPartnerSite() {
	global $URL;

	$imgStr = "";
	$queryCookie = "SELECT * FROM partner_sites";
	$resultCookie = mysql_query($queryCookie) or die("queryCookie failed: " . mysql_error());
	while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {
		//img src actually executes the code on the other server
		$imgStr .= '<img style="display:none" src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineCookie["site_url"].'/'.(strpos($URL, '/staging') ? "staging/" : "").'includes/cookie_control.php?logout=1&referrer='.$_SERVER['HTTP_HOST'].'&timestamp='.time().'" />';
	}

	return $imgStr;
}


function removeCookiePartnerSites($cookie_name) {
	global $URL;

	$imgStr = "";
	$queryCookie = "SELECT * FROM partner_sites";
	$resultCookie = mysql_query($queryCookie) or die("Query failed : " . mysql_error());
	while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {
		//img src actually executes the code on the other server
		$imgStr .= '<img style="display:none" src="'.(($_SERVER['HTTPS'] != '') ? "https://" : "http://").$lineCookie["site_url"].'/'.(strpos($URL, '/staging') ? "staging/" : "").'includes/cookie_control.php?removecookie='.$cookie_name.'&referrer='.$_SERVER['HTTP_HOST'].'&timestamp='.time().'" />';
		
	}

	return $imgStr;
}

?>