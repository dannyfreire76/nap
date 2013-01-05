<?php

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
include '../includes/common.php';
error_reporting(E_ALL);
ini_set('display_errors','on');

//print_d($siteHandles);

print "<pre>\n";

//exit;

if(isset($siteHandles)){
	
	$headers = array();
	$rows = array();
	
	foreach($siteHandles as $site=>$h){

		$i = mysql_query("ALTER TABLE `receipts` ADD `active` INT( 1 ) NULL DEFAULT '1' AFTER `complete`");
		$i = mysql_query("ALTER TABLE `wholesale_receipts` ADD `active` INT( 1 ) NULL DEFAULT '1' AFTER `complete`");

		//$i = mysql_query("ALTER TABLE `receipts` ADD `cc_trans_id` VARCHAR( 50 ) NULL AFTER `cc_auth_code`",$h);
		//$i = mysql_query("ALTER TABLE `wholesale_receipts` ADD `cc_trans_id` VARCHAR( 50 ) NULL AFTER `cc_auth_code`",$h);


//$sql = "ALTER TABLE `refunds` ADD `trans_id` VARCHAR( 25 ) NULL DEFAULT NULL,
//							  ADD `auth_code` VARCHAR( 25 ) NULL DEFAULT NULL";




//		$sql = "CREATE TABLE `$site`.`refunds` (
//				`refund_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
//				`receipt_id` INT( 11 ) NULL DEFAULT NULL ,
//				`wholesale_receipt_id` INT( 11 ) NULL DEFAULT NULL ,
//				`site_id` INT( 11 ) NULL DEFAULT NULL ,
//				`refund_amount` FLOAT( 10, 2 ) NOT NULL DEFAULT '0.00',
//				`original_total` FLOAT( 10, 2 ) NOT NULL DEFAULT '0.00'
//				) ENGINE = MYISAM ;";
//		print_d($sql);

//		$i = mysql_query($sql,$h) or print mysql_error($h);

		
		//$i = mysql_query("UPDATE ship_main SET tax = '.080'",$h);

//$i = mysql_query("UPDATE wholesale_receipts SET complete = '0' WHERE `active` = '0'");
//$i = mysql_query("UPDATE receipts SET complete = '0' WHERE `active` = '0'");

//		$sql = "SELECT 	user_id as order_num,
//			ordered,
//			ship_state,
//			bill_name,
//			bill_address1,
//			bill_address2,
//			bill_city,
//			bill_state,
//			bill_zip,
//			bill_country,
//			bill_phone,
//			bill_email,
//			pay_type,
//			subtotal,
//			shipping,
//			tax,
//			total,
//			(tax / subtotal) as tax_rate
//		FROM `receipts` 
//		WHERE ship_state = 'NY'
//		AND shipped = '0'
//		AND ordered > '2010-01-01'";

//		print_d($site);

//		$sql = "SELECT cc_trans_id FROM receipts where cc_trans_id != ''";
//
//		$res = mysql_query($sql,$h) or die(mysql_error($h));
//		
//		while($row = mysql_fetch_array($res, MYSQL_ASSOC)){
		
//			if(empty($headers)){
//				$headers = array_keys($row);
//				array_unshift($headers,"site");	
//			}
//			array_unshift($row,$site);
//			$rows[] = array_values($row);
			
//			print_d($row);
//			
//		}


		
		//print("$site updated\n");
		
		//$i = mysql_query("ALTER TABLE `receipts` ADD `cc_trans_id` VARCHAR( 50 ) NULL AFTER `cc_auth_code`",$h);
		//$i = mysql_query("ALTER TABLE `wholesale_receipts` ADD `cc_trans_id` VARCHAR( 50 ) NULL AFTER `cc_auth_code`",$h);
/*
		
		$row = array();
		$sql = "SELECT * FROM admin_pages WHERE admin_page_name = 'Orders' AND admin_page_url = 'orders.index.php'";
		//print_d($sql);
		$res = mysql_query($sql,$h) or die(mysql_error($h));
		print// "Result: $res\n";
		
		$row = mysql_fetch_array($res, MYSQL_ASSOC);
		print mysql_error($h);
		print_d($row);
		
		if(empty($row)){
			$query = "INSERT INTO admin_pages (admin_page_name,admin_page_url,sequence,parent_id,display_in_nav)
					  VALUES('Orders','orders.index.php','5',NULL,'1')";
			//print_d($query);
			$insert = mysql_query($query,$h);
			print mysql_error($h);
			print_d("$site Updated");
		}
*/


	}
	
//	if(count($rows > 0)){
//		foreach($rows as $i=>$line){
//			print '"' . join('",',$line) . '"' . "\n";
//		}
//	}
	
}
?>
</pre>
