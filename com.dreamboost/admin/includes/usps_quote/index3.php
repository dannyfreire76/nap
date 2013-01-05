<?
	// include the UPS Script.
	require_once('ups.php');


	// Create an opject of type ups
	$MyUPS=new ups();

	// set shipper info
	$MyUPS->SetShipper('Ithaca','NY','13068','US');
	
	// uncomment this if you ship from somewhere other than the address you 
	// registered your key to.
	//$MyUPS->SetShipFrom('Springfield','MO','65807','US');  

	// Set the Ship To address.  This will probably be gleaned from the user
	$MyUPS->SetShipTo('Hicksville','NY','11801','US',1);
	
	// Add a package.  Note that I saved the package number for use in the following functions
	$pkg=$MyUPS->AddPackage('02','First Package',33);
	$MyUPS->SetPackageValue($pkg,87.53);	// Adding an insured value
	$MyUPS->SetPackageSize($pkg,108,2,2);	// Adding a size to the box

	// adding another package (with an insured value)
	$pkg=$MyUPS->AddPackage('02','Second Package',113,25.50);
	
	// Request the rates this shipping setup
	$UPSError=$MyUPS->ModeRateShop();
	
	// limit the shipping services I want displayed 
	//    (these are the service codes from ups. NOTE: you can use either integers (12) or strings ('12') )
	$MyUPS->SetRateListLimit('03',12,'02');

	// get the list of rates I specified, adding 1.50 to each one for handling
	$selopt=$MyUPS->GetRateListShort(1.50);

	// set the services list back to all of them
	$MyUPS->SetRateListLimit();

	// I debuged here to see that everything was happy =)
//	$MyUPS->Debug();


	// here I'm getting the cost of service '03' (ground).  usually this would be on the next page
	// after the user selected something from the options of ModeRateShop
	$MyRate=$MyUPS->ModeGetRate('03');


// used my values I got back.
echo <<<__FOO__

<select name="foo">
	$selopt
</select>


<br><br>
Cost is $MyRate.
__FOO__;

?>