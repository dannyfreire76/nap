<?php
header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

include '../includes/common.php';

//print_d($siteHandles);
if(isset($siteHandles)){
	$handle = $siteHandles['salviazo_store'];
}
else{
	$handle = $dbh;	
}

$query = "SELECT r.store_name,r.address1,r.address1,r.city,r.state,r.zip,r.country,r.contact_name,r.phone,r.email,
		  COUNT(w.wholesale_receipt_id) as order_count
		  FROM retailer r, wholesale_receipts w
		  WHERE w.ordered >= '2010-01-01 00:00:00'
		  AND w.retailer_id = r.retailer_id
		  AND email != 'rhoelle@yahoo.com'
		  AND email != 'danny@freire-design.com'
		  GROUP BY r.retailer_id
		  ORDER BY store_name";

print "<pre>\n";
$headerout = false;
$result = mysql_query($query,$handle) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
	$line['store_name'] = stripslashes($line['store_name']);
	
	if(!$headerout){
		print '"' . join('","',array_keys($line)) . '"' . "\n";
		$headerout = true;	
	}
	
	print '"' . join('","',array_values($line)) . '"' . "\n";
}
mysql_free_result($result);
print "</pre>\n";

?>