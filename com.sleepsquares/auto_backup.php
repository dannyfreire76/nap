<?php
if ( $_REQUEST["passkey"]==substr($_SERVER["SERVER_NAME"],0,8) ) { //salviazo

$str = "";

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

	$emailaddress = "NAPbackups@gmail.com";
	$host="localhost"; // database host

	//$dbuser="salviazo_salviaz"; // database user name
	$dbuser=$lineAll["site_key_name"]."_".$lineAll["site_db_user"]; // database user name

	//$dbpswd="mazatec"; // database password
	$dbpswd=$lineAll["site_db_pw"];

	//$mysqldb="salviazo_store"; // name of database
	$mysqldb=$lineAll["site_key_name"]."_".$dbType;

	$path = $_SERVER["DOCUMENT_ROOT"]."/backups"; // full server path to the directory where you want the backup files (no trailing slash)

	$justfilename = $mysqldb.'_'. date("m_d_y") . ".sql.gz";
	$filename = $path . "/".$justfilename;

	if ( file_exists($filename) ) {
		unlink($filename);
	} else {
		fopen($filename, 'w') or die("can't open file");
	}
	//system("mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb > $filename",$result);
	//compress:
	system( "mysqldump --user=$dbuser --password=$dbpswd --host=$host $mysqldb | gzip > $filename",$result);


	$to = $emailaddress;

	$subject = "Database backup for ".$lineAll["site_title"]." attached: ".$justfilename;

	$random_hash = md5(date('r', time()));

//IMPORTANT NOTE: where ever you use $random_hash, make sure to add the "1" after it.  The reason is below where the line ends with random_hash (PHP-mixed), the code doesn't display the new line unless you put a literal after the closing php tags.  In effect, "1" can be any literal, so long as you're consistent.

	$fromAddy = "info@".$lineAll["site_url"];
	$headers = "From: ".$fromAddy."\r\nReply-To: ".$fromAddy."";
	$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."1\"";
	//$headers .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";

	$attachment = chunk_split(base64_encode(file_get_contents($filename)));

	ob_start(); //Turn on output buffering
	?>
--PHP-mixed-<?php echo $random_hash;?>1
Content-Type: application/x-gzip; name="<?php echo $justfilename; ?>"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

<?php echo $attachment; ?>

--PHP-mixed-<?php echo $random_hash; ?>1--
<?
//copy current buffer contents into $message variable and delete current output buffer
$output = ob_get_clean();

$mail_sent = @mail($to, $subject, $output, $headers);

	if ($mail_sent==1) {
		$str .= 'backup '.$justfilename.' sent succesfully at '.date("Y-m-d H:i:s");
	} else {
		$str .= 'backup '.$justfilename.' failed';
	}
	$str .= '<br />';

	//finally, delete the file off the server:
	unlink ($filename);
}

} else {
	$str .= 'Sorry, you need the right credentials.';
}

if ( $_REQUEST["show"] ) {//for cronless.com, echo kills the process
	echo $str;
}

?>
