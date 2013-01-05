USPS Rates
<pre>
<?php
require("usps.php");


$usps = new USPS;
$usps->setServer("http://testing.shippingapis.com/ShippingAPITest.dll");
$usps->setUserName("746NAPAS5695");
$usps->setService("All");
$usps->setDestZip("20008");
$usps->setOrigZip("10022");
$usps->setWeight(10, 5);
$usps->setContainer("Flat Rate Box");
$usps->setCountry("USA");
$usps->setMachinable("true");
$usps->setSize("LARGE");
$price = $usps->getPrice(); 

/*    $usps = new USPS;
    $usps->setServer("http://testing.shippingapis.com/ShippingAPITest.dll");
    $usps->setUserName("746NAPAS5695");
    $usps->setService("PRIORITY");
    $usps->setDestZip("20008");
    $usps->setOrigZip("10022");
    $usps->setWeight(10, 5);
    $usps->setContainer("Flat Rate Box");
    $usps->setCountry("USA");
    $price = $usps->getPrice();
*/
/*    $usps = new USPS;
    $usps->setServer("http://testing.shippingapis.com/ShippingAPITest.dll");
    $usps->setUserName("746NAPAS5695");
    $usps->setService("All");
    $usps->setDestZip("20008");
    $usps->setOrigZip("10022");
    $usps->setWeight(10, 5);
    $usps->setContainer("Flat Rate Box");
    $usps->setCountry("USA");
    $usps->setMachinable("true");
    $usps->setSize("LARGE");
    $price = $usps->getPrice();
*/
/*    $usps = new USPS;
    $usps->setServer("http://testing.shippingapis.com/ShippingAPITest.dll");
    $usps->setUserName("746NAPAS5695");
    $usps->setWeight(2, 0);
    $usps->setCountry("Albania");
    $price = $usps->getPrice(); 
*/
print_r($price);
echo "Show val: ";
print_r($price->list[0]->rate);
echo "\n";

$userlist[0]['username']="Frank"; 
$userlist[0]['qty']=100; 
$userlist[0]['used']=15; 
$userlist[0]['left']=85; 
$userlist[1]['username']="William"; 
$userlist[1]['qty']=100; 
$userlist[1]['used']=35; 
$userlist[1]['left']=65; 
$userlist[2]['username']="Jonh"; 
$userlist[2]['qty']=110; 
$userlist[2]['used']=102; 
$userlist[2]['left']=8;
print_r($userlist);

$monkey_array = array('title'=>'Spider Monkey', 'src'=>'monkey.jpg');
$monkey_object = (object) $monkey_array;
print $monkey_object->title . ' ' . $monkey_object->src;
echo "\n";
print_r($monkey_object);
?>
</pre>
