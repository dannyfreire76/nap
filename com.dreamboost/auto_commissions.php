<?php
$str = "";

if ( $_REQUEST["passkey"]==substr($_SERVER["SERVER_NAME"],0,8) ) { //salviazo

	$dbh_master = mysql_connect("localhost","nap_nap","mazatec");
	mysql_select_db("nap_store",$dbh_master) or die("Could not select master database");

	$dbType = "store";
	if ( strpos($_SERVER["REQUEST_URI"], '/staging/')!==false ) {
		$dbType = "staging";
	}

	$thisSite = array();
	//ALL SITES:
	$queryAll = "SELECT * FROM sites";
	$resultAll = mysql_query($queryAll, $dbh_master) or die("Query failed : " . mysql_error());
	while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {

		$thisDBHName = "dbh".$lineAll["site_key_name"];
		$$thisDBHName=mysql_connect("localhost", $lineAll["site_key_name"]."_".$lineAll["site_db_user"], $lineAll["site_db_pw"]) or die ('Could not connect to the database: ' . $thisDBHName);
		mysql_select_db($lineAll["site_key_name"]."_".$dbType, $$thisDBHName) or die("Could not select database");

		$queryRep = "UPDATE wholesale_receipts SET comm_paid = NOW() WHERE comm_paid=0 and complete='1' AND funds_received!=0;";
		$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());

		if ( $resultRep ) {
			$str .= 'All '.$lineAll["site_title"].' paid orders flagged as commissions paid.<br />';
		}
	}

} else {
	$str .= 'Sorry, you need the right credentials.';
}

if ( $_REQUEST["show"] ) {//for cronless.com, echo kills the process
	echo $str;
}

?>
