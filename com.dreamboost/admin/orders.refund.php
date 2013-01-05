<?php
header('Content-type: text/html; charset=utf-8');

include '../includes/main1.php';
include '../includes/common.php';
include '../includes/db.class.php';
$db = new DB();

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

//print_d($thisSite);
//exit;

if(!empty($_REQUEST['receipt_id'])){
	$table = 'receipts';
	$idFld = 'receipt_id';
	$idVal = $_REQUEST['receipt_id'];
}
elseif(!empty($_REQUEST['wholesale_receipt_id'])){
	$table = 'wholesale_receipts';
	$idFld = 'wholesale_receipt_id';
	$idVal = $_REQUEST['wholesale_receipt_id'];
}
else{
	exit("Cannot save order. No receipt_id or wholesale_receipt_id was passed.");
}

// get the payment processor for the active store
$cc_processor = $db->GetRecord("SELECT company, url, username as x_login, password as x_tran_key FROM merchant_acct WHERE status='1' LIMIT 1");

$flds = array(	'x_Version'		=>	'3.1',
				'x_Delim_Data'	=>	'True',
				'x_delim_char'	=>	'|',
				'x_Login'		=>	$cc_processor['x_login'],
				'x_Tran_Key'	=>	$cc_processor['x_tran_key'],
				'x_Trans_ID'	=>	$_REQUEST['x_Trans_ID'],
				'x_Amount'		=>	$_REQUEST['x_Amount'],
				'x_Card_Num'	=>	$_REQUEST['x_Card_Num'],
				'x_Exp_Date'	=>	$_REQUEST['x_Exp_Date'],
				'x_Type'		=>	'CREDIT');

//print_d($flds);
//exit("Needs a test before credit is actually run");

$fields = "";
foreach($flds as $key=>$value){
	$fields .= "$key=" . urlencode($value) . "&";
}
$fields = substr(trim($fields),0,strlen(trim($fields)) -1);

$error_txt = null;
$ch = curl_init($cc_processor['url']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields);
### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
$result = urldecode(curl_exec($ch));
if(curl_errno($ch)) { 
	$error_txt .= curl_error($ch); 
}
curl_close($ch);

$data = explode("|", $result);

if($data[0] == "3" || $data[0] == "2") {
	exit("There was a problem with the refund information you entered: ".$data[3]);
}
elseif($error_txt){
	exit($error_txt);	
}

$cc_auth_code = $data[4];
$cc_trans_id = $data[6];

/* Table for holding refund amounts in each site
CREATE TABLE IF NOT EXISTS `refunds` (
  `refund_id` int(11) NOT NULL auto_increment,
  `receipt_id` int(11) default NULL,
  `wholesale_receipt_id` int(11) default NULL,
  `site_id` int(11) default NULL,
  `refund_amount` float(10,2) NOT NULL default '0.00',
  `original_total` float(10,2) NOT NULL default '0.00',
  `trans_id` varchar(25) default NULL,
  `auth_code` varchar(25) default NULL,
  PRIMARY KEY  (`refund_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
*/

// If we get this far, save to database
$sql = "INSERT INTO refunds ($idFld,`site_id`,`refund_amount`,`original_total`,`trans_id`,`auth_code`) 
			VALUES ('$idVal','$thisSite[site_id]','$_REQUEST[x_Amount]','$_REQUEST[orig_total]','$cc_trans_id','$cc_auth_code')";

$db->Execute($sql);

$receipt = $db->GetRecord("SELECT subtotal,discount,tax,shipping FROM $table WHERE $idFld = '$idVal'");

$subtotal		= number_format($receipt['subtotal'],2,".","");
$discount		= number_format($receipt['discount'],2,".","");
$tax			= number_format($receipt['tax'],2,".","");
$shipping		= number_format($receipt['shipping'],2,".","");
$refundAmount	= number_format($_REQUEST['x_Amount'],2,".","");

$total = number_format((($subtotal - $discount) + $tax + $shipping) - $refundAmount,2,".","");

$sql = "UPDATE $table SET total = '$total' WHERE $idFld = '$idVal' LIMIT 1";
$db->Execute($sql);


?>