<?php
	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
	error_reporting(E_ERROR);

	include_once("mysql_connect.php");
	include_once('customer.php');

	if ( $_REQUEST["setcookie"] ) {
		$queryCookie = "SELECT * FROM sites";
		$resultCookie = mysql_query($queryCookie, $dbh_master) or die("Query failed : " . mysql_error());
		while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {

			if ( strpos( $_REQUEST["referrer"], $lineCookie["site_url"] ) !== false ) { //only allow one of our sites to do anything
				$new_user_id = $_REQUEST["setcookie"];

				//set new or override existing cookie
				$thisCookie = setcookie("nap_user", $new_user_id, time()+60*60*24*30, "/", ".".$_SERVER["HTTP_HOST"], 0) or die ("Set Cookie failed : " . mysql_error());

			}
		}

		$im = file_get_contents('../images/trans.gif');
		header('content-type: image/gif');
		echo $im; 

	} else if ( $_REQUEST["removecookie"] ) {
		$queryCookie = "SELECT * FROM sites";
		$resultCookie = mysql_query($queryCookie, $dbh_master) or die("queryCookie failed : " . mysql_error());
		while ($lineCookie = mysql_fetch_array($resultCookie, MYSQL_ASSOC)) {

			if ( strpos( $_REQUEST["referrer"], $lineCookie["site_url"] ) !== false ) { //only allow one of our sites to do anything
				$cookie_name = $_REQUEST["removecookie"];

				$cookie_val = $_COOKIE[$cookie_name];

				if ( $cookie_val ) {
					//expire old cookie
					$thisCookie = setcookie($cookie_name, $cookie_val, time()-3600, "/", ".".$_SERVER["HTTP_HOST"], 1) or die ("Set Cookie failed");
				}
			}
		}

		$im = file_get_contents('../images/trans.gif');
		header('content-type: image/gif');
		echo $im; 

	} else if ( $_REQUEST["checkEmail"] ) {//called when user logs in
		session_start();

		if ( !$_SESSION["member_id"] ) {//user not logged in, so check if email from referring site also exists for this site
			$member_email_test = check_dup_email($_REQUEST["checkEmail"]);

			if($member_email_test > 0) {
				doLogin( $member_email_test );			
			} else {//no matching email in this site, so create new member record with same info as referring site
				$querySites = "SELECT * FROM partner_sites WHERE site_url = '".$_REQUEST["referrer"]."'";
				$resultSites = mysql_query($querySites) or die("Query failed: " . mysql_error());
				while ($lineSites = mysql_fetch_array($resultSites, MYSQL_ASSOC)) {
						
					$referrerDBHName = "dbh".$lineSites["site_key_name"];
					
					duplicateMember($$referrerDBHName, $_REQUEST["checkEmail"]);
				}
			}
		}
		
		$im = file_get_contents('../images/trans.gif');
		header('content-type: image/gif');
		echo $im; 

	} else if ( $_REQUEST["logout"] ) {//called when user logs into a partner site
		session_start();

		if ( $_SESSION["member_id"] ) {//user logged in
		    $_SESSION = array();//log user out

			if ( $_COOKIE["member_id"] ) {
				$thisCookie = setcookie("member_id", "", time()-3600, "/", ".".$_SERVER["HTTP_HOST"], 1) or die ("Set Cookie failed");
			}
		
		}
		$im = file_get_contents('../images/trans.gif');
		header('content-type: image/gif');
		echo $im; 

	}
	else {
		echo 'cookie: '.$_COOKIE["nap_user"];
	}
?>