Fedex Rates
<pre>
<?php
require("fedex.php");

$fedexService['PRIORITYOVERNIGHT']     = 'FedEx Priority Overnight';
$fedexService['STANDARDOVERNIGHT']     = 'FedEx Standard Overnight';
$fedexService['FIRSTOVERNIGHT']        = 'FedEx First Overnight';
$fedexService['FEDEX2DAY']             = 'FedEx 2 Day';
$fedexService['FEDEXEXPRESSSAVER']     = 'FedEx Express Saver';
$fedexService['INTERNATIONALPRIORITY'] = 'FedEx International Priority';
$fedexService['INTERNATIONALECONOMY']  = 'FedEx International Economy';
$fedexService['INTERNATIONALFIRST']    = 'FedEx International First';
$fedexService['FEDEX1DAYFREIGHT']      = 'FedEx Overnight Freight';
$fedexService['FEDEX2DAYFREIGHT']      = 'FedEx 2 day Freight';
$fedexService['FEDEX3DAYFREIGHT']      = 'FedEx 3 day Freight';
$fedexService['FEDEXGROUND']           = 'FedEx Ground';
$fedexService['GROUNDHOMEDELIVERY']    = 'FedEx Home Delivery';

foreach($fedexService as $service=>$serviceName)
{
    $fedex = new Fedex;
    $fedex->setServer("https://gatewaybeta.fedex.com/GatewayDC");
    $fedex->setAccountNumber(344995826); //Get your own - this will not work...
    $fedex->setMeterNumber(344995826);    //Get your own - this will not work...
    $fedex->setCarrierCode("FDXE");
    $fedex->setDropoffType("REGULARPICKUP");
    $fedex->setService($service, $serviceName);
    $fedex->setPackaging("YOURPACKAGING");
    $fedex->setWeightUnits("LBS");
    $fedex->setWeight(17);
    $fedex->setOriginStateOrProvinceCode("NY");
    $fedex->setOriginPostalCode(14852);
    $fedex->setOriginCountryCode("US");
    $fedex->setDestStateOrProvinceCode("NY");
    $fedex->setDestPostalCode(11801);
    $fedex->setDestCountryCode("US");
    $fedex->setPayorType("SENDER");
    
    $price = $fedex->getPrice();
    
    print_r($price);

}
?>
</pre>
