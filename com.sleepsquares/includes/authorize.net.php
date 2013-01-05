<?php

function createTransactionProfiles($customer_profile_id, $payment_profile_id, $email, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y, $cc_exp_m, $bill_phone) {

	if ( !$customer_profile_id ) {
		$parsedresponse = createCustomerProfile($email, $cc_first_name, $cc_last_name, $bill_address1.' '.$bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y.'-'.$cc_exp_m, $bill_phone);//returns false if already profile exists

		if ( strtolower($parsedresponse->messages->resultCode)=="ok" ) {//if we just added a new Customer, get paymentProfileID
			$customer_profile_id = htmlspecialchars($parsedresponse->customerProfileId);
			$payment_profile_id = htmlspecialchars($parsedresponse->customerPaymentProfileIdList->numericString);
		} elseif( strtolower($parsedresponse->messages->message->code)=='e00039' ) {//duplicate Profile error				
			//extract Customer Profile ID from the error message
			$customer_profile_id = preg_replace('/\D/', '', $parsedresponse->messages->message->text);
		}
	}

	//if cc and Authorize.net and customer profile id and member creation succeeded, try to add payment profiles in Authorize
	if( $customer_profile_id ) {
		//attempt to create a customerProfileRequest
		if ( !$payment_profile_id ) {
			$paymentProfileResp = createCustomerPaymentProfileRequest($customer_profile_id, $cc_first_name, $cc_last_name, $bill_address1.' '.$bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y.'-'.$cc_exp_m, $bill_phone);

			if ( strtolower($paymentProfileResp->messages->resultCode)=="ok" ) {
				$payment_profile_id = htmlspecialchars($paymentProfileResp->customerPaymentProfileId);
			} elseif ( strtolower($paymentProfileResp->messages->message->code)=='e00039' ) {//duplicate payment profile (doesn't actually give you the ID that was duplicated), so we should try to update

				$completeProfileRequest = getCustomerProfileRequest( $customer_profile_id );//get all payment profiles

				foreach ( $completeProfileRequest->profile->paymentProfiles as $aProfile ) {//find the duplicate profile so we can update it
					if ( $aProfile->billTo->firstName==$cc_first_name &&
						$aProfile->billTo->lastName==$cc_last_name &&
						$aProfile->billTo->address==trim($bill_address1.' '.$bill_address2) &&
						$aProfile->billTo->city==$bill_city &&
						$aProfile->billTo->state==$bill_state &&
						$aProfile->billTo->zip==$bill_zip &&
						$aProfile->billTo->country==$bill_country &&
						$aProfile->payment->creditCard->cardNumber=='XXXX'.substr($cc_num, -4)
					) {
						$payment_profile_id = $aProfile->customerPaymentProfileId;
						updateCustomerPaymentProfileRequest($customer_profile_id, $payment_profile_id, $cc_first_name, $cc_last_name, $bill_address1.' '.$bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp_y.'-'.$cc_exp_m, $bill_phone);
						break;
					}
				}
			}	
		}//if !$payment_profile_id
	}//if $customer_profile_id

	$profile_error = "";
	
	if ( !$customer_profile_id || !$payment_profile_id ) {
		$profile_error = "There was a problem creating profiles with this information";
	}

	return array($customer_profile_id, $payment_profile_id, $profile_error);
}//createTransactionProfiles

function postAuthorizeTrans($authOrCapture, $merchant_url, $merchant_username, $merchant_password, $total, $cc_num, $cc_exp_m, $cc_exp_y, $cid, $cc_first_name, $cc_last_name, $bill_address1, $bill_address2, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_trans_id) {
	$this_error_txt = "";

	$c = curl_init($merchant_url);
	curl_setopt($c, CURLOPT_HEADER, 0);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

	if ( $authOrCapture=='PRIOR_AUTH_CAPTURE' && $cc_trans_id ) {//preauthorized transaction just needs trans_id to be captured
		curl_setopt($c, CURLOPT_POSTFIELDS, 'x_version=3.1&x_delim_data=True&x_login='.$merchant_username.'&x_password='.$merchant_password.'&x_trans_id='.$cc_trans_id.'&x_type='.$authOrCapture);
	} else {
		curl_setopt($c, CURLOPT_POSTFIELDS, 'x_version=3.1&x_delim_data=True&x_login='.$merchant_username.'&x_password='.$merchant_password.'&x_amount=' . urlencode($total) . '&x_card_num=' . urlencode($cc_num) . '&x_exp_date=' . urlencode($cc_exp_m) . urlencode($cc_exp_y) . '&x_card_code=' . urlencode($cid) . '&x_first_name=' . urlencode($cc_first_name) . '&x_last_name=' . urlencode($cc_last_name) . '&x_address=' . urlencode($bill_address1) . urlencode(' ') . urlencode($bill_address2) . '&x_city=' . urlencode($bill_city) . '&x_state=' . urlencode($bill_state) . '&x_zip=' . urlencode($bill_zip) . '&x_country=' . urlencode($bill_country) . '&x_method=CC&x_type='.$authOrCapture);
	}

	$page = urldecode(curl_exec($c));
	
	//echo $cc_first_name.' '.$cc_last_name.'<br />'.$authOrCapture.'<pre>';
	//echo var_dump($page);
	//echo '</pre>';
	
	if(curl_errno($c)) { 
		$this_error_txt .= curl_error($c); 
	}

	curl_close($c);
	$page2 = explode(",", $page);
	
	if($page2[0] != "1" ) {
		$this_error_txt .= "Error, There was a problem with the credit card information you entered, because \"" . $page2[3] . "\"<br>\n";
	}
	
	$cc_auth_code = $page2[4];
	$cc_trans_id = $page2[6];

	return array($cc_auth_code, $cc_trans_id, $this_error_txt);
}

function getMerchantCreds() {
	global $merchant_url, $merchant_username, $merchant_password, $api_host, $api_path, $company;

	$query = "SELECT * FROM merchant_acct WHERE status='1' LIMIT 1";
	$result = mysql_query($query) or die("merchant_acct query failed: " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$api_host = $line["api_host"];
		$api_path = $line["api_path"];
		$merchant_url = $line["url"];
		$merchant_username = $line["username"];
		$merchant_password = $line["password"];
		$company = $line["company"];
	}
}

function MerchantAuthenticationBlock() {
	global $merchant_username, $merchant_password;

	if ( !isset($merchant_username) ) {
		getMerchantCreds();
	}

	return
        "<merchantAuthentication>".
        "<name>" . $merchant_username . "</name>".
        "<transactionKey>" . $merchant_password . "</transactionKey>".
        "</merchantAuthentication>";
}

//function to send xml request to Api.
//There is more than one way to send https requests in PHP.
function send_xml_request($content) {
	global $api_host, $api_path;

	if ( !isset($api_host) ) {
		getMerchantCreds();
	}

	return send_request_via_fsockopen($api_host,$api_path,$content);
}

//function to send xml request via fsockopen
//It is a good idea to check the http status code.
function send_request_via_fsockopen($host,$path,$content)
{
	$posturl = "ssl://" . $host;
	$header = "Host: $host\r\n";
	$header .= "User-Agent: PHP Script\r\n";
	$header .= "Content-Type: text/xml\r\n";
	$header .= "Content-Length: ".strlen($content)."\r\n";
	$header .= "Connection: close\r\n\r\n";
	$fp = fsockopen($posturl, 443, $errno, $errstr, 30);
	if (!$fp)
	{
		$body = false;
	}
	else
	{
		error_reporting(E_ERROR);
		fputs($fp, "POST $path  HTTP/1.1\r\n");
		fputs($fp, $header.$content);
		fwrite($fp, $out);
		$response = "";
		while (!feof($fp))
		{
			$response = $response . fgets($fp, 128);
		}
		fclose($fp);
		error_reporting(E_ALL ^ E_NOTICE);
		
		$len = strlen($response);
		$bodypos = strpos($response, "\r\n\r\n");
		if ($bodypos <= 0)
		{
			$bodypos = strpos($response, "\n\n");
		}
		while ($bodypos < $len && $response[$bodypos] != '<')
		{
			$bodypos++;
		}
		$body = substr($response, $bodypos);
	}
	return $body;
}
/*
//function to send xml request via curl
function send_request_via_curl($host,$path,$content)
{
	$posturl = "https://" . $host . $path;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $posturl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$response = curl_exec($ch);
	return $response;
}
*/

//function to parse the api response
//The code uses SimpleXML. http://us.php.net/manual/en/book.simplexml.php 
//There are also other ways to parse xml in PHP depending on the version and what is installed.
function parse_api_response($content)
{
	$parsedresponse = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOWARNING);
	/*
	if ("Ok" != $parsedresponse->messages->resultCode) {
		echo "The operation failed with the following errors:<br>";
		foreach ($parsedresponse->messages->message as $msg) {
			echo "[" . htmlspecialchars($msg->code) . "] " . htmlspecialchars($msg->text) . "<br>";
		}
		echo "<br>";
	}
	*/
	return $parsedresponse;
}

function createCustomerProfile($email, $cc_first_name, $cc_last_name, $bill_address, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp, $bill_phone) {
	$content =
	'<?xml version="1.0" encoding="utf-8"?>' .
	'<createCustomerProfileRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">' .
	MerchantAuthenticationBlock().
		'<profile>'.
		'<merchantCustomerId></merchantCustomerId>'. // Your own identifier for the customer.
		'<description>'.$cc_first_name.' '.$cc_last_name.'</description>'.
		'<email>' .$email. '</email>'.
			"<paymentProfiles>".
				"<customerType>individual</customerType>".
				"<billTo>".
				 "<firstName>".$cc_first_name."</firstName>".
				 "<lastName>".$cc_last_name."</lastName>".
				 "<address>".$bill_address."</address>".
				 "<city>".$bill_city."</city>".
				 "<state>".$bill_state."</state>".
				 "<zip>".$bill_zip."</zip>".
				 "<country>".$bill_country."</country>".
				 "<phoneNumber>".$bill_phone."</phoneNumber>".
				"</billTo>".
				"<payment>".
					"<creditCard>".
					"<cardNumber>".$cc_num."</cardNumber>".
					"<expirationDate>".$cc_exp."</expirationDate>". // required format for API is YYYY-MM
					"</creditCard>".
				"</payment>".
			"</paymentProfiles>".
		'</profile>'.
		"<validationMode>liveMode</validationMode>". // or testMode
	'</createCustomerProfileRequest>';

	$response = send_xml_request($content);
	//echo 'Raw: '.htmlspecialchars($response); exit();
	$parsedresponse = parse_api_response($response);
	
	return $parsedresponse;
}

function createCustomerPaymentProfileRequest($customerProfileId, $cc_first_name, $cc_last_name, $bill_address, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp, $bill_phone) {
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
		"<paymentProfile>".
			"<billTo>".
			 "<firstName>".$cc_first_name."</firstName>".
			 "<lastName>".$cc_last_name."</lastName>".
			 "<address>".$bill_address."</address>".
			 "<city>".$bill_city."</city>".
			 "<state>".$bill_state."</state>".
			 "<zip>".$bill_zip."</zip>".
			 "<country>".$bill_country."</country>".
			 "<phoneNumber>".$bill_phone."</phoneNumber>".
			"</billTo>".
			"<payment>".
				"<creditCard>".
				"<cardNumber>".$cc_num."</cardNumber>".
				"<expirationDate>".$cc_exp."</expirationDate>". // required format for API is YYYY-MM
				"</creditCard>".
			"</payment>".
		"</paymentProfile>".
		"<validationMode>liveMode</validationMode>". // or testMode
	"</createCustomerPaymentProfileRequest>";

	$response = send_xml_request($content);
	//echo 'Raw: '.htmlspecialchars($response); exit();
	$parsedresponse = parse_api_response($response);
	
	return $parsedresponse;
}

function updateCustomerPaymentProfileRequest($customerProfileId, $customerPaymentProfileId, $cc_first_name, $cc_last_name, $bill_address, $bill_city, $bill_state, $bill_zip, $bill_country, $cc_num, $cc_exp, $bill_phone) {
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<updateCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
		"<paymentProfile>".
			"<billTo>".
			 "<firstName>".$cc_first_name."</firstName>".
			 "<lastName>".$cc_last_name."</lastName>".
			 "<address>".$bill_address."</address>".
			 "<city>".$bill_city."</city>".
			 "<state>".$bill_state."</state>".
			 "<zip>".$bill_zip."</zip>".
			 "<country>".$bill_country."</country>".
			 "<phoneNumber>".$bill_phone."</phoneNumber>".
			"</billTo>".
			"<payment>".
				"<creditCard>".
				"<cardNumber>".$cc_num."</cardNumber>".
				"<expirationDate>".$cc_exp."</expirationDate>". // required format for API is YYYY-MM
				"</creditCard>".
			"</payment>".
			"<customerPaymentProfileId>".$customerPaymentProfileId."</customerPaymentProfileId>".
		"</paymentProfile>".
		"<validationMode>liveMode</validationMode>". // or testMode
	"</updateCustomerPaymentProfileRequest>";

	$response = send_xml_request($content);
	//echo 'Raw: '.htmlspecialchars($response); exit();
	$parsedresponse = parse_api_response($response);
	
	return $parsedresponse;
}

//returns all customer info, including payment profiles
function getCustomerProfileRequest($customerProfileId) {
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<getCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"</getCustomerProfileRequest>";

	$response = send_xml_request($content);
	//echo 'Raw: '.htmlspecialchars($response); exit();
	$parsedresponse = parse_api_response($response);
	
	return $parsedresponse;
}

//submits an actual transaction using a saved payment profile
function createCustomerProfileTransactionRequest($customerProfileId, $customerPaymentProfileId, $total, $transType, $order_number, $cc_auth_code, $cc_trans_id) {
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		MerchantAuthenticationBlock().
		"<transaction>".
			"<".$transType.">".
				"<amount>" . $total . "</amount>". // should include tax, shipping, and everything
				"<customerProfileId>" .$customerProfileId. "</customerProfileId>".
				"<customerPaymentProfileId>" .$customerPaymentProfileId . "</customerPaymentProfileId>".
			"</".$transType.">".
		"</transaction>".
	"</createCustomerProfileTransactionRequest>";

	$response = send_xml_request($content);
	$parsedresponse = parse_api_response($response);

	//echo htmlspecialchars($content);
	//echo '<br /><br />';
	//echo 'Raw: '.htmlspecialchars($response); exit();
	
	return $parsedresponse;
}
?>