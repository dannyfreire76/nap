<?php
require_once("xmlparser.php");

class Fedex {
    
    // Variables
    //var $server = "https://gatewaybeta.fedex.com/GatewayDC";
    var $server = "https://gateway.fedex.com/GatewayDC";
    var $accountNumber;
    var $meterNumber;
    var $carrierCode = "FDXE";
    var $dropoffType = "REGULARPICKUP";
    var $service='';
    var $serviceName;
    var $packaging = "YOURPACKAGING";
    var $weightUnits = "LBS";
    var $weight;
    // Origin Address
    var $originStateOrProvinceCode;
    var $originPostalCode;
    var $originCountryCode;
    // Destination Address
    var $destStateOrProvinceCode;
    var $destPostalCode;
    var $destCountryCode;
    var $payorType = "SENDER";
    
    // Functions    
    function setServer($server) {
        $this->server = $server;
    }

    function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    function setMeterNumber($meterNumber) {
        $this->meterNumber = $meterNumber;
    }

    function setCarrierCode($carrierCode) {
        $this->carrierCode = $carrierCode;
    }
    
    function setDropoffType($dropoffType) {
        $this->dropoffType = $dropoffType;
    }

    function setService($service, $name) {
        $this->service = $service;
        $this->serviceName = $name;
    }

    function setPackaging($packaging) {
        $this->packaging = $packaging;
    }
    
    function setWeightUnits($units) {
        $this->weightUnits = $units;
    }
    
    function setWeight($weight) {
        $this->weight = $weight;
    }
    
    function setOriginStateOrProvinceCode($code) {
        $this->originStateOrProvinceCode = $code;
    }
    
    function setOriginPostalCode($code) {
        $this->originPostalCode = $code;
    }
    
    function setOriginCountryCode($code) {
        $this->originCountryCode = $code;
    }
    
    function setDestStateOrProvinceCode($code) {
        $this->destStateOrProvinceCode = $code;
    }
    
    function setDestPostalCode($code) {
        $this->destPostalCode = $code;
    }
    
    function setDestCountryCode($code) {
        $this->destCountryCode = $code;
    }
    
    function setPayorType($type) {
        $this->payorType = $type;
    }

    function setPackages($pkgs) {
        $this->packages = $pkgs;
    }
    
    function setPackageCount($pkgcnt) {
        $this->packageCount = $pkgcnt;
    }
    function getPrice() {
        
        $str = '<?xml version="1.0" encoding="UTF-8" ?>';
        $str .= '<FDXRateAvailableServicesRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance" xsi:noNamespaceSchemaLocation="FDXRateAvailableServicesRequest.xsd">';
        $str .= '        <RequestHeader>';
        $str .= '            <CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>';
        $str .= '            <AccountNumber>'.$this->accountNumber.'</AccountNumber>';
        $str .= '            <MeterNumber>'.$this->meterNumber.'</MeterNumber>';
        $str .= '            <CarrierCode>'.$this->carrierCode.'</CarrierCode>';
        $str .= '        </RequestHeader>';
        $str .= '        <DropoffType>'.$this->dropoffType.'</DropoffType>';
        $str .= '        <ShipDate>'.date("Y-m-d").'</ShipDate>';
        if ( $this->service != '' ) {//if service is not set, gets all available rates
            $str .= '        <Service>'.$this->service.'</Service>';
        }
        $str .= '        <Packaging>'.$this->packaging.'</Packaging>';
        $str .= '        <WeightUnits>'.$this->weightUnits.'</WeightUnits>';
        $str .= '        <Weight>'.$this->weight.'</Weight>';
        $str .= '        <OriginAddress>';
        $str .= '            <StateOrProvinceCode>'.$this->originStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            <PostalCode>'.$this->originPostalCode.'</PostalCode>';
        $str .= '            <CountryCode>'.$this->originCountryCode.'</CountryCode>';
        $str .= '        </OriginAddress>';
        $str .= '        <DestinationAddress>';
        $str .= '            <StateOrProvinceCode>'.$this->destStateOrProvinceCode.'</StateOrProvinceCode>';
        $str .= '            <PostalCode>'.$this->destPostalCode.'</PostalCode>';
        $str .= '            <CountryCode>'.$this->destCountryCode.'</CountryCode>';
        $str .= '        </DestinationAddress>';
        $str .= '        <Payment>';
        $str .= '            <PayorType>'.$this->payorType.'</PayorType>';
        $str .= '        </Payment>';



        $str .= '        <PackageCount>'.$this->packageCount.'</PackageCount>';
  
        foreach( $this->packages as $box ) {//iterate through each type of box
            if ( $box->counter > 0 ) {//if in this shipment, there are any of this type of box        
                for($i=0; $i<$box->counter; $i++) {//add a box for every one in this type
                    $str .= '<Dimensions>
                        <Units>IN</Units>
                        <Length>'.$box->sc_length.'</Length>
                        <Height>'.$box->sc_height.'</Height>
                        <Width>'.$box->sc_width.'</Width>
                    </Dimensions>';
                }
            }
        }

        $str .= '    </FDXRateAvailableServicesRequest>';
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
        curl_setopt($ch, CURLOPT_URL,$this->server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 4);//this breaks it badly
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
        $data = curl_exec($ch);        

        if (curl_errno($ch)) {
            //$this->getPrice();

            //echo '**ERROR FOUND: '.curl_errno($ch).': '.curl_error($ch).'**';
            return null;
        } else {
            // close curl resource, and free up system resources
            curl_close($ch);
            $xmlParser = new xmlparser();
            $array = $xmlParser->GetXMLTree($data);
            //$xmlParser->printa($array);
            if(count($array['FDXRATEAVAILABLESERVICESREPLY'][0]['ENTRY'][0]['ERROR'])) { // If it is error
                $error = new federror();
                $error->number = $array['FDXRATEAVAILABLESERVICESREPLY'][0]['ENTRY'][0]['ERROR'][0]['CODE'][0]['VALUE'];
                $error->description = $array['FDXRATEAVAILABLESERVICESREPLY'][0]['ENTRY'][0]['ERROR'][0]['MESSAGE'][0]['VALUE'];
                $error->response = $array;
                $this->error = $error;
            } else if (count($array['FDXRATEAVAILABLESERVICESREPLY'][0]['ENTRY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'])) {
    
                foreach ($array[FDXRATEAVAILABLESERVICESREPLY][0][ENTRY] as $value){
                    $price = new fedprice();
                    $price->rate = $value['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'][0]['VALUE'];
                    $price->mailservice = $value['SERVICE'][0]['VALUE'];
                    $this->list[] = $price;
                
                }
            }
            return $this;
        }

    }
}

class federror
{
    var $number;
    var $description;
    var $response;
}
class fedprice
{
    var $mailservice;
    var $rate;
    var $response;
}
?>