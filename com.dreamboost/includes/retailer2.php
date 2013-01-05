<?php 
// BME WMS 
// Page: WC Retailer Include
// Path/File: /includes/retailer2.php
// Version: 1.1
// Build: 1107
// Date: 12-27-2006

include ('../includes/main1.php');
require_once("../admin/includes/usps_quote/usps.php");
require_once("../admin/includes/usps_quote/ups.php");
require_once("../admin/includes/usps_quote/fedex.php");

if ( $_GET["recalc_ship"] ) {
    loadShipping($_GET["tot_weight"], $_GET["shipping_method"], $_GET["box_count_num"], $_GET["subtotal"], 'USPS|UPS');
    exit();
}

if ( $_GET["calc_discount"] ) {
    echo calcDiscount($_GET["subtotal"], $_GET["dcode"]);
    exit();
}

class ShipBox {
    var $counter = 0;//the only val that needs to be initiated, rest comes from database
}

function calcDiscount ( $subtotal, $dcode ) {
    $new_subtotal = $subtotal;
    $queryDisc = "SELECT percent_off FROM discount_codes WHERE status='1' AND discount_code='$dcode'";
    $resultDisc = mysql_query($queryDisc) or die("Query failed : " . mysql_error());
    while ($lineDisc = mysql_fetch_array($resultDisc, MYSQL_ASSOC)) {
        $percent_off = $lineDisc["percent_off"];
        $percent_off = 1 - $percent_off;
		$new_subtotal = $subtotal * $percent_off;
    }
    mysql_free_result($resultDisc);
    return number_format($new_subtotal, 2);
}


function shipping_account_get($shipper) {
	$query = "SELECT * FROM shipping_accounts WHERE status='1' AND shipper='$shipper'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$shipping_account = $line;
	}
	mysql_free_result($result);
	return $shipping_account;
}

function find_fedex( $shipping_info, $tot_lbs, $tot_ozs, $box_count_num, $boxes, $service_type ) {
    $fedex = new Fedex;
    $shipper = "FedEx";
	$tmp_tot_ozs = $tot_ozs / 16;
	$tmp_tot_lbs = $tot_lbs + $tmp_tot_ozs;
    $shipping_account = shipping_account_get($shipper);

    $fedex->setServer($shipping_account['server']);
    $fedex->setAccountNumber($shipping_account['account_number']);
    $fedex->setMeterNumber($shipping_account['meter_number']);
    $fedex->setCarrierCode($service_type);
    $fedex->setDropoffType('BUSINESSSERVICECENTER');
    //$fedex->setService('FEDEX2DAY', 'FedEx Ground');//omitting gets all
    $fedex->setPackaging('YOURPACKAGING');
    $fedex->setWeightUnits('LBS');
    $fedex->setWeight($tmp_tot_lbs);
    $fedex->setOriginStateOrProvinceCode( $shipping_account['origin_state'] );
    $fedex->setOriginPostalCode( $shipping_account['origin_zip'] );
    $fedex->setOriginCountryCode( 'US' );
    $fedex->setDestStateOrProvinceCode( $shipping_info['ship_state'] );
    $fedex->setDestPostalCode( $shipping_info['ship_zip'] );
    $fedex->setDestCountryCode('US');
    $fedex->setPayorType('SENDER');
    $fedex->setPackageCount($box_count_num);
    $fedex->setPackages($boxes);
   
    $fedex_resp = $fedex->getPrice();
	$fedex_rates_count = count($fedex_resp->list);

    for($i=0; $i<$fedex_rates_count; $i++) {
        $fedex_rates[ $fedex_resp->list[$i]->mailservice ] = $fedex_resp->list[$i]->rate;//use mailservice as the key in this array of rates
	}
	
	return $fedex_rates;
}

function find_usps($ship_zip, $tot_lbs, $tot_ozs) {
	$shipper = "USPS";
	$shipping_account = shipping_account_get($shipper);
	$usps = new USPS;
	$usps->setServer($shipping_account['server']);
	$usps->setUserName($shipping_account['account_number']);
	$usps->setService("All");
	$usps->setDestZip("$ship_zip");
	$usps->setOrigZip($shipping_account['origin_zip']);
	$usps->setWeight($tot_lbs, $tot_ozs);
	$usps->setContainer("Flat Rate Box");
	$usps->setCountry("USA");
	$usps->setMachinable("true");
	$usps->setSize("LARGE");
	$usps_rate2 = $usps->getPrice();
	$usps_rate2_count = count($usps_rate2->list);

    for($i=0;$i<$usps_rate2_count;$i++) {
        $usps_rates[ $usps_rate2->list[$i]->mailservice ] = $usps_rate2->list[$i]->rate;//use mailservice as the key in this array of rates
	}
	
	return $usps_rates;
}

function find_ups($shipping_info, $subtotal, $tot_lbs, $tot_ozs, $box_count_num, $boxes, $method_code ) {
	$shipper = "UPS";
	$shipping_account = shipping_account_get($shipper);
	$tmp_tot_ozs = $tot_ozs / 16;
	$tmp_tot_lbs = $tot_lbs + $tmp_tot_ozs;
	
	$tmp_tot_lbs_per_box = $tmp_tot_lbs / $box_count_num;
	$subtotal_per_box = $subtotal / $box_count_num;
	
	// Create an opject of type ups
	$MyUPS=new ups();

	// set shipper info
	$MyUPS->SetShipper($shipping_account['shipper_city'],$shipping_account['shipper_state'],$shipping_account['shipper_zip'],'US');
	
	// uncomment this if you ship from somewhere other than the address you 
	// registered your key to.
	$MyUPS->SetShipFrom($shipping_account['origin_city'],$shipping_account['origin_state'],$shipping_account['origin_zip'],'US');

	// Set the Ship To address.
	$MyUPS->SetShipTo($shipping_info['ship_city'],$shipping_info['ship_state'],$shipping_info['ship_zip'],$shipping_info['ship_country'],1);

    foreach( $boxes as $box ) {//iterate through each type of box
        if ( $box->counter > 0 ) {//if in this shipment, there are any of this type of box        
            for($i=0; $i<$box->counter; $i++) {//add a box for every one in this type
    			$pkg=$MyUPS->AddPackage('02','Package '.$i, $tmp_tot_lbs_per_box, $subtotal_per_box);
                $MyUPS->SetPackageSize( $pkg, $box->sc_width, $box->sc_height, $box->sc_length );	// Adding a size to the box
            }
        }
    }

	// Request the rates this shipping setup
	//$UPSError=$MyUPS->ModeRateShop();
	
	// get the list of rates I specified, adding 0.00 to each one for handling
	$selopt=$MyUPS->GetRateListShort(0.00);

	// set the services list back to all of them
	$MyUPS->SetRateListLimit( $method_code );
	
	$ups_rate_transfer = $MyUPS->ModeGetRate($method_code);
	
	return $ups_rate_transfer;
}

function calc_box_weight($item_count, $boxes) {
	$box_weight = 0;
    $item_min=0;
    $item_max=0;

    $max_num_boxes = 100;//TODO, move to DB? - doesn't really matter because there will never be 100 boxes in a shipment
    for ($x=1; $x<=$max_num_boxes; $x++) {
        if ( $item_max < $item_count ) {
            $boxcntr = 0;


            reset($boxes);
            while (list($key, $value) = each($boxes)) {
                $box =& $boxes[$key];
                $item_max = $item_min + $box->sc_int;
                
                if( $item_count > $item_min && $item_count <= $item_max ) {
                    $box_weight += $box->sc_weight;
                    $box->counter = 1;

                    if ( $x>1 ) {//if we're past the first box, you have to add the biggest box times (number of loops minus 1)
                        $last_in_array = count($boxes)-1;
                        $boxes[ $last_in_array ]->counter = $boxes[ $last_in_array ]->counter + ($x-1);
                        $box_weight += $boxes[ $last_in_array ]->sc_weight * ($x-1);
                    }
                }

                $item_min = $item_max;//on the next loop, the max becomes the min
            }
        }
        else {
            break;
        }
    }

	$box_weight_count[0] = $box_weight;
	$box_weight_count[1] = $boxes;
	return $box_weight_count;
}

function loadShipping($tot_weight, $shipping_method, $box_count_num, $subtotal, $shippers) {
    $shipping_info = $_SESSION['shipping_info'];
    $boxes = $_SESSION["boxes"];

    $fetch_shippers = split( "\|", $shippers );
    $query = "SELECT default_wholesale_ship_method FROM ship_main";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $default_method = $line["default_wholesale_ship_method"];
    }
    mysql_free_result($result);

    if($shipping_method == "") { $shipping_method = $default_method; }
    $tot_ozs2 = $tot_weight / 28.35;//convert grams to ounces
    $tot_lbs = floor( $tot_ozs2 / 16 );//convert ounces to pounds and round down
    $tot_ozs3 = $tot_lbs * 16;//convert rounded pounds to ounces
    $tot_ozs = $tot_ozs2 - $tot_ozs3;//number of ounces left over from the round


    $udi_fee = 4.00;
    $shipping_noins = $udi_fee;
    $addl_shipping = $udi_fee;

    $del_conf = 0.50 * $box_count_num;
    $addl_shipping = $addl_shipping + $del_conf;
    $tmp_insurance = floor($subtotal/100);
    $insurance = 1.50 + (1 * $tmp_insurance);
    $addl_shipping = $addl_shipping + $insurance;

    // Find wholesale shipping methods
    $usps_found=false;
    $fedex_found=false;
    $query = "SELECT * FROM ship_method_wholesale WHERE active='1' ORDER BY ship_method_id";
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ( in_array($line["shipper"], $fetch_shippers) ) {//only get shippers asked for
            $this_shipping = '';
            switch ( $line["shipper"] ) {
                case 'USPS':
                    if ( !$usps_found ) {
                        $usps_rates = find_usps( $shipping_info['ship_zip'], $tot_lbs, $tot_ozs );//gets all in advance and then we choose the right code
                        $usps_found = true;
                    }
                    $this_shipping = $usps_rates[ $line["method_code"] ] + $addl_shipping * 1;
                    break;
                case 'FedEx':
                    if ( !$fedex_found ) {
                        $fedexe_rates = find_fedex( $shipping_info, $tot_lbs, $tot_ozs, $box_count_num, $boxes, 'FDXE' );//gets all Express in advance and then we choose the right code
                        $fedexg_rates = find_fedex( $shipping_info, $tot_lbs, $tot_ozs, $box_count_num, $boxes,  'FDXG' );//gets all Ground in advance and then we choose the right code
                        $fedex_found = true;
                    }
                    if ( strpos($line["method_code"], 'GROUND') ) {
                        $this_shipping = $fedexg_rates[ $line["method_code"] ] + $addl_shipping * 1;
                    } else {
                        $this_shipping = $fedexe_rates[ $line["method_code"] ] + $addl_shipping * 1;
                    }
                    break;
                case 'UPS':
                    $ups_rate = find_ups($shipping_info, $subtotal, $tot_lbs, $tot_ozs, $box_count_num, $boxes, $line["method_code"] );//gets rate for just this code
                    $this_shipping = $ups_rate + $shipping_noins * 1;
                    break;
            }
            if ( $this_shipping!=$addl_shipping ) {//if true, nothing came back from shipping server
                echo '<option class="text_right" value="'.$line["ship_method_id"].'|';
                $this_shipping = sprintf( "%01.2f", $this_shipping );
                echo $this_shipping;
                echo '"';

                if( $shipping_method == $line["ship_method_id"] ) {
                    $shipping_active = $this_shipping;
                    echo ' selected="true"';
                }

                echo '>';
                echo $line["name"];
                echo ' - $';
                echo $this_shipping;
                echo "&#160;</option>";
            }
        }
    }
    mysql_free_result($result);

    return $shipping_active;
}
?>