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
<FDXRateAvailableServicesRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="FDXRateAvailableServicesRequest.xsd">        
	<RequestHeader>
		<CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>
		<AccountNumber>344995826</AccountNumber>
		<MeterNumber>5846302</MeterNumber>
		<CarrierCode>FDXE</CarrierCode>
	</RequestHeader>
	<DropoffType>BUSINESSSERVICECENTER</DropoffType>
	<ShipDate>2009-02-25</ShipDate>        
	<Packaging>YOURPACKAGING</Packaging>        
	<WeightUnits>LBS</WeightUnits>        
	<Weight>0.467372134039</Weight>        
	<OriginAddress>
		<StateOrProvinceCode>NY</StateOrProvinceCode>
		<PostalCode>14882</PostalCode>
		<CountryCode>US</CountryCode>
	</OriginAddress>        
	<DestinationAddress>
		<StateOrProvinceCode>NY</StateOrProvinceCode>
		<PostalCode>13053</PostalCode>
		<CountryCode>US</CountryCode>        
	</DestinationAddress>        
	<Payment>
		<PayorType>SENDER</PayorType>        
	</Payment>        
	<PackageCount>1</PackageCount>
	<Dimensions>

		<Units>IN</Units>


		<Length>10</Length>


		<Height>3</Height>


		<Width>7</Width>

        </Dimensions>    
</FDXRateAvailableServicesRequest>
';

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

$ch2 = curl_init();
// set URL and other appropriate options
curl_setopt($ch2, CURLOPT_URL, 'http://production.shippingapis.com/ShippingAPI.dll?API=RateV2&XML=<RateV2Request%20USERID="746NAPAS5695"%20PASSWORD=""><Package%20ID="0"><Service>All</Service><ZipOrigination>14852</ZipOrigination><ZipDestination>13053</ZipDestination><Pounds>0</Pounds><Ounces>7.47795414462</Ounces><Container>Flat+Rate+Box</Container><Size>LARGE</Size><Machinable>true</Machinable></Package></RateV2Request>');
curl_setopt($ch2, CURLOPT_HEADER, 0);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

echo '<br /><br />Attempting to reach usps...<br /><br />';

if (!$result=curl_exec ($ch2)) {print "cURL Failed to connect!";} else {print "$result";}

$data = curl_exec($ch2); //execute post and get results
if(curl_errno($ch2)) { 
    $error_txt .= curl_error($ch2); 
    echo 'Communication error: '.$error_txt;
} else {
	// close curl resource, and free up system resources
	curl_close($ch2);
	$xmlParser = new xmlparser();
	$array = $xmlParser->GetXMLTree($data);
	echo '<br /><br />data returned: ';
	$xmlParser->printa($array);
}


?>