<?php
require("/home/salviazo/public_html/staging/admin/includes/usps_quote/fedex.php");

$fedex = new Fedex;
    //$fedex->setServer('https://gatewaybeta.fedex.com/GatewayDC');
    $fedex->setAccountNumber(344995826);
    $fedex->setMeterNumber(12312312);//don't think the value really matters
    $fedex->setCarrierCode('FDXE');
    $fedex->setDropoffType('BUSINESSSERVICECENTER');
    //$fedex->setService('FEDEX2DAY', 'FedEx Ground');
    $fedex->setPackaging('YOURPACKAGING');
    $fedex->setWeightUnits('LBS');
    $fedex->setWeight(17);
    $fedex->setOriginStateOrProvinceCode('OH');
    $fedex->setOriginPostalCode(44333);
    $fedex->setOriginCountryCode('US');
    $fedex->setDestStateOrProvinceCode('CA');
    $fedex->setDestPostalCode(90210);
    $fedex->setDestCountryCode('US');
    $fedex->setPayorType('SENDER');
   
    $price = $fedex->getPrice();
    echo '<br /><br />error: '.$price->error->number.' , '.$price->error->description;
    echo '<br /><br />price: '.$price->price->rate;
?>