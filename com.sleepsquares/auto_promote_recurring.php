<?php
include_once('includes/main1.php');
include_once('../includes/wc1.php');
include_once($base_path.'includes/admin_orders_util.php');
include_once($base_path.'includes/authorize.net.php');


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

	$recurringFound = false;
	while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {

		$thisDBHName = "dbh".$lineAll["site_key_name"];
		$$thisDBHName=mysql_connect("localhost", $lineAll["site_key_name"]."_".$lineAll["site_db_user"], $lineAll["site_db_pw"]) or die ('Could not connect to the database: ' . $thisDBHName);

		if ($$thisDBHName) {
			$dbName = $lineAll["site_key_name"]."_".$dbType;
			mysql_select_db($dbName, $$thisDBHName) or die("Could not select database");

			//get wms_main vars fresh for this connection
			$queryA = "SELECT * FROM wms_main";
			$resultA = mysql_query($queryA) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($resultA, MYSQL_ASSOC)) {
				foreach($line as $col=>$val) {
					//echo '<br />*'.$col;
					$$col = $val;
				}
			}

			$sqlTableExists ='SHOW TABLES WHERE Tables_in_' . $dbName . ' = \'recurring_orders\'';
			$rsTableExists = mysql_query($sqlTableExists);
			 
			if(mysql_fetch_array($rsTableExists)) {
				//echo '<br /><br />'.$website_title."_".$dbType.'<br />'.$recurringMsg;

				$recurringMsg = promoteNextRecurringOrders();

				if ( $recurringMsg!="") {
					$recurringFound = true;
					$str .= "From ".$lineAll["site_title"].":<br />" . $recurringMsg."<br /><br /><br />";					
				}
			}
		}
	}

	if ( $recurringFound ) { 
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: '.$site_email.' <'.$site_email.'>' . "\r\n";
		$headers .= 'From: '.$site_email.' <'.$site_email.'>' . "\r\n";

		// Mail it
		mail($site_email, "Recurring Orders Processed", strip_tags($str, '<br /><br>'), $headers);
	}

} else {
	$str .= 'Sorry, you need the right credentials.';
}

if ( $_REQUEST["show"] ) {//for cronless.com, echo kills the process
	echo $str;
}

?>
