<?php
require_once("admin/includes/usps_quote/xmlparser.php");

/*
$ch = curl_init("https://gateway.fedex.com/GatewayDC"); 
curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
*/
//*****************************

        
	$str = '<?xml version="1.0" encoding="UTF-8" ?>
<FDXSubscriptionRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:noNamespaceSchemaLocation="FDXSubscriptionRequest.xsd">
<RequestHeader>
<CustomerTransactionIdentifier>String</CustomerTransactionIdentifier>
<AccountNumber>344995826</AccountNumber>
</RequestHeader>
<Contact>
<PersonName>Danny Freire</PersonName>
<CompanyName>Upstate Dream Company</CompanyName>
<Department>IT</Department>
<PhoneNumber>6072803743</PhoneNumber>
<E-MailAddress>danny@freire-design.com</E-MailAddress>
</Contact>
<Address>
<Line1>1939 East Shore Drive</Line1>
<Line2>Suite 1</Line2>
<City>Lansing</City>
<StateOrProvinceCode>NY</StateOrProvinceCode>
<PostalCode>14882</PostalCode>
<CountryCode>US</CountryCode>
</Address>
</FDXSubscriptionRequest>';

$header[] = "Host: dreamboost.com";
$header[] = "MIME-Version: 1.0";
$header[] = "Content-type: multipart/mixed; boundary=----doc";
$header[] = "Accept: text/xml";
$header[] = "Content-length: ".strlen($str);
$header[] = "Cache-Control: no-cache";
$header[] = "Connection: close \r\n";
$header[] = $str;

$ch = curl_init();

//Disable certificate check.
// uncomment the next line if you get curl error 60: error setting certificate verify locations
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// uncommenting the next line is most likely not necessary in case of error 60
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//-------------------------
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//curl_setopt($ch, CURLOPT_CAINFO, "c:/ca-bundle.crt");
//-------------------------
$theURL = "https://gateway.fedex.com/GatewayDC";
curl_setopt($ch, CURLOPT_URL, $theURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_TIMEOUT, 4);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


//*************************
echo 'Attempting to reach '.$theURL.'...<br /><br />';

if (!$result=curl_exec ($ch)) {print "cURL Failed to connect!";} else {print "$result";}

$data = curl_exec($ch); //execute post and get results

if(curl_errno($ch)) { 
    $error_txt .= curl_error($ch); 
    echo 'Communication error: '.$error_txt;
} else {
	// close curl resource, and free up system resources
	curl_close($ch);
	$xmlParser = new xmlparser();
	$array = $xmlParser->GetXMLTree($data);
	echo '<br />data returned: ';
	$xmlParser->printa($array);
}
?>


