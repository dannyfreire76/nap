<?
/**
 * Author:   Ernest Wojciuk
 * Web Site: www.imap.pl
 * Email:    ernest@moldo.pl
 * Comments: EMAIL TO DB:: EXAMPLE 1
 */

include_once("./includes/emailtodb/class.emailtodb.php");

$cfg["db_host"] = 'localhost';
$cfg["db_user"] = 'dreamboo_dreambo';
$cfg["db_pass"] = 'mazatec';
$cfg["db_name"] = 'dreamboo_store';

$mysql_pconnect = mysql_pconnect($cfg["db_host"], $cfg["db_user"], $cfg["db_pass"]);
if(!$mysql_pconnect){echo "Connection Failed"; exit; }
$db = mysql_select_db($cfg["db_name"], $mysql_pconnect);
if(!$db){echo"DB Select Failed"; exit;}


$edb = new EMAIL_TO_DB();
$edb->connect('mail.dreamboost.com', '/pop3:110/notls', 'gnv-orders+dream-boost.com', 'dregnv@7');
$edb->do_action();

?>