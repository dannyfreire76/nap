<?php
include '../includes/main1.php';
include "../includes/common.php";

//function to send xml request via curl
function send_request_via_curl($content)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://api.authorize.net/xml/v1/request.api");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	return $response;
}

//helper function for parsing response
function substring_between($haystack,$start,$end)
{
	if (strpos($haystack,$start) === false || strpos($haystack,$end) === false)
	{
		return false;
	}
	else
	{
		$start_position = strpos($haystack,$start)+strlen($start);
		$end_position = strpos($haystack,$end);
		return substr($haystack,$start_position,$end_position-$start_position);
	}
}

//function to parse Authorize.net response
function parse_return($content)
{
	$refId = substring_between($content,'<refId>','</refId>');
	$resultCode = substring_between($content,'<resultCode>','</resultCode>');
	$code = substring_between($content,'<code>','</code>');
	$text = substring_between($content,'<text>','</text>');
	$subscriptionId = substring_between($content,'<subscriptionId>','</subscriptionId>');
	return array ($refId, $resultCode, $code, $text, $subscriptionId);
}

//$authorize_net="ARBCreateSubscriptionRequest";
//$authorize_net="ARBUpdateSubscriptionRequest";
$authorize_net="ARBCancelSubscriptionRequest";

$table="members";
$member_id=39;
$query="SELECT * FROM `$table` WHERE member_id='$member_id' LIMIT 1";
$result = mysql_query($query) or die("This query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$vars = explode(",","email,username,first_name,last_name,".
			"bill_name,bill_address1,bill_address2,".
			"bill_city,bill_state,bill_zip,bill_country,".
			"bill_phone,ship_name,ship_address1,".
			"ship_address2,ship_city,ship_state,ship_zip,".
			"ship_country,ship_phone");
	for($i=0;$i<count($vars);$i++){
		$param=$vars[$i];
		if($line[$param]){
			$GLOBALS[$table."_".$param]=$line[$param];
		}
	}
}

$table="merchant_acct";
$query="SELECT * FROM `$table` WHERE status='1' LIMIT 1";
$result = mysql_query($query) or die("This query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$vars = explode(",","username,password");
	for($i=0;$i<count($vars);$i++){
		$param=$vars[$i];
		if($line[$param]){
			$GLOBALS[$table."_".$param]=$line[$param];
		}
	}
}

$subscription_name="recurring order";
$interval_length=60;
$interval_unit="days";
$startDate=date("Y-m-d");
$totalOccurrences=6;
//$total=1.02;
//$cc_num=4495012533512520;
//$expirationDate="2013-09";
//$subscriptionId=10103797;

$merchant_acct_url="https://api.authorize.net/xml/v1/request.api";

$$authorize_net=preg_replace("/\{([^\{]{1,100}?)\}/e","$$1",file_get_contents($authorize_net.".xml"));

//post the xml at this authorize.net url...
$data=send_request_via_curl($$authorize_net);
$data=parse_return($data);
print_d($data,false);

?><textarea name="request" rows="10" cols="150"><?=$$authorize_net?></textarea><br />
<textarea name="response" rows="10" cols="150"><?=$data?></textarea><br />
