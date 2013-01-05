<?php

/* USPS Class - a PHP class to interact with the U.S. Postal Service's Web Tools APIs and retrieve real-time shipping quotes.
This class can handle domestic and international shipping requests. It requires DOMXML and cURL to interface with the USPS and
move through the returned XML document. Feel free to use this class in your programs as you see fit, it is freeware. */

class USPS
{
	var $user_id;
	var $password;
	var $api;
	var $request_xml;
	var $package_index = 0;
	var $current_result = array();

	var $country_list = array(
	"United States",
	"Afghanistan",
	"Albania",
	"Algeria",
	"Andorra",
	"Angola",
	"Anguilla",
	"Antigua and Barbadua",
	"Argentina",
	"Armenia",
	"Aruba",
	"Ascension",
	"Australia",
	"Austria",
	"Azerbaijan",
	"Bahamas",
	"Bahrain",
	"Bangladesh",
	"Barbados",
	"Belarus",
	"Belgium",
	"Belize",
	"Benin",
	"Bermuda",
	"Bhutan",
	"Bolivia",
	"Bosnia-Herzegovina",
	"Botswana",
	"Brazil",
	"British Virgin Islands",
	"Brunei Darussalam",
	"Bulgaria",
	"Burkina Faso",
	"Burma",
	"Burundi",
	"Cambodia",
	"Cameroon",
	"Canada",
	"Cape Verde",
	"Cayman Islands",
	"Central African Republic",
	"Chad",
	"Chile",
	"China",
	"Colombia",
	"Comoros",
	"Democratic Republic of the Congo",
	"Republic of the Congo",
	"Costa Rica",
	"Ivory Coast",
	"Croatia",
	"Cuba",
	"Cyprus",
	"Czech Republic",
	"Denmark",
	"Djibouti",
	"Dominica",
	"Dominican Republic",
	"Ecuador",
	"Egypt",
	"El Salvador",
	"Equitorial Guinea",
	"Eritrea",
	"Estonia",
	"Ethiopia",
	"Falkland Islands",
	"Faroe Islands",
	"Fiji",
	"Finland",
	"France",
	"French Guiana",
	"French Polynesia",
	"Gabon",
	"Gambia",
	"Republic of Georgia",
	"Germany",
	"Ghana",
	"Gibraltar",
	"Great Britain and Northern Ireland",
	"Greece",
	"Greenland",
	"Grenanda",
	"Guadeloupe",
	"Guatemala",
	"Guinea",
	"Guinea-Bissau",
	"Guyana",
	"Haiti",
	"Honduras",
	"Hong Kong",
	"Hungary",
	"Iceland",
	"India",
	"Indonesia",
	"Iran",
	"Iraq",
	"Ireland",
	"Israel",
	"Italy",
	"Jamaica",
	"Japan",
	"Jordan",
	"Kazakhstan",
	"Kenya",
	"Kiribati",
	"Democratic People's Republic of Korea",
	"Republic of Korea",
	"Kuwait",
	"Kyrgyzstan",
	"Laos",
	"Latvia",
	"Lebanon",
	"Lesotho",
	"Liberia",
	"Libya",
	"Liechtenstein",
	"Lithuania",
	"Luxembourg",
	"Macao",
	"Macedonia",
	"Madagascar",
	"Malawi",
	"Malaysia",
	"Maldives",
	"Mali",
	"Malta",
	"Martinique",
	"Mauritania",
	"Mauritius",
	"Mexico",
	"Moldova",
	"Mongolia",
	"Montserrat",
	"Morocco",
	"Mozambique",
	"Namibia",
	"Nauru",
	"Nepal",
	"Netherlands",
	"Netherlands Antilles",
	"New Caledonia",
	"New Zealand",
	"Nicaragua",
	"Niger",
	"Nigeria",
	"Norway",
	"Oman",
	"Pakistan",
	"Panama",
	"Papua New Guinea",
	"Paraguay",
	"Peru",
	"Philippines",
	"Pitcairn Island",
	"Poland",
	"Portugal",
	"Qatar",
	"Reunion",
	"Romania",
	"Russia",
	"Rwanda",
	"St. Christopher and Nevis",
	"St. Helena",
	"St. Lucia",
	"St. Pierre and Miquelon",
	"St. Vincent and the Grenadines",
	"San Marino",
	"Sao Tome and Principe",
	"Saudi Arabia",
	"Senegal",
	"Serbia-Montenegro",
	"Seychelles",
	"Sierra Leone",
	"Singapore",
	"Slovak Republic",
	"Slovenia",
	"Solomon Islands",
	"Somalia",
	"South Africa",
	"Spain",
	"Sri Lanka",
	"Sudan",
	"Suriname",
	"Swaziland",
	"Sweden",
	"Switzerland",
	"Syria",
	"Taiwan",
	"Tajikistan",
	"Tanzania",
	"Thailand",
	"Togo",
	"Tonga",
	"Trinidad and Tobago",
	"Tristan de Cunha",
	"Tunisia",
	"Turkey",
	"Turkmenistan",
	"Turks and Caicos Islands",
	"Tuvalu",
	"Uganda",
	"Ukraine",
	"United Arab Emirates",
	"Uruguay",
	"Uzbekistan",
	"Vanuatu",
	"Vatican City",
	"Venezuela",
	"Vietnam",
	"Wallis and Futuna Islands",
	"Western Samoa",
	"Yemen",
	"Zambia",
	"Zimbabwe");

	function USPS($user_id, $password, $api = 'RateV2')
	{
		if(empty($user_id) || empty($password)) return false;
		else {
			$this->user_id = $user_id;
			$this->password = $password;
			$this->api = $api;
			$this->request_xml = '<' . $api . 'Request USERID="' . $user_id . '" PASSWORD="' . $password . '">';
		}
	}

	function reset()
	{
		$this->api = '';
		$this->current_result = '';
		$this->request_xml = '';
		$this->package_index = 0;
	}

	function add_package($attribs = '')
	{
		if(!is_array($attribs)) return false;

		//Check to make sure array has required values for API
		if($this->api == 'RateV2') {
			if(!$attribs['service'] || !$attribs['zip_origin'] || !$attribs['zip_dest'] || !$attribs['pounds'] || !$attribs['ounces'] || !$attribs['size']) return false;
		}

		if($this->api == 'RateV2')
		{
			//Check service type
			if(empty($attribs['service'])) return false;
			else {
				switch(strtolower($attribs['service']))
				{
					case 'express':
					case 'first class':
					case 'priority':
					case 'parcel':
					case 'bpm':
					case 'library':
					case 'media':
					case 'all':
						break;
					default:
						return false;
				}
			}

			//Check ZIP codes
			if(!isset($attribs['zip_origin'])) return false;
			if(!isset($attribs['zip_dest'])) return false;

			//Check weight
			if(!isset($attribs['pounds'])) return false;
			if(!isset($attribs['ounces'])) return false;

			//Check container for Express and Priority
			if(strtolower($attribs['service']) == 'express' || strtolower($attribs['service']) == 'priority')
			{
				if(!isset($attribs['container'])) return false;
				else {
					switch(strtolower($attribs['container']))
					{
						case 'flat rate envelope':
						case 'flat rate box':
							break;
						default:
							return false;
					}
				}
			}

			//Check size
			if(!$attribs['size']) return false;
			else {
				switch(strtolower($attribs['size']))
				{
					case 'regular':
					case 'large':
					case 'oversize':
						break;
					default:
						return false;
				}
			}

			//Check machinable for parcel post
			if(strtolower($attribs['service']) == 'parcel') {
				if(empty($attribs['machinable'])) return false;
			}

			//Add the package to the XML request
			$this->request_xml .= '<Package ID="' . $this->package_index . '">';
			$this->package_index++;
			$this->request_xml .= '<Service>' . strtoupper($attribs['service']) . '</Service>';
			$this->request_xml .= '<ZipOrigination>' . $attribs['zip_origin'] . '</ZipOrigination>';
			$this->request_xml .= '<ZipDestination>' . $attribs['zip_dest'] . '</ZipDestination>';
			$this->request_xml .= '<Pounds>' . $attribs['pounds'] . '</Pounds><Ounces>' . $attribs['ounces'] . '</Ounces>';
			if(strtolower($attribs['service']) == 'express' || strtolower($attribs['service']) == 'priority')
				$this->request_xml .= '<Container>' . $attribs['container'] . '</Container>';
			$this->request_xml .= '<Size>';
			//$this->request_xml .= mb_convert_case($attribs['size'], MB_CASE_TITLE);
			$this->request_xml .= $attribs['size'];
			$this->request_xml .= '</Size>';
			if(strtolower($attribs['service']) == 'parcel' || strtolower($attribs['service']) == 'all')
				$this->request_xml .= '<Machinable>' . $attribs['machinable'] . '</Machinable>';
			$this->request_xml .= '</Package>';
		}

		else if($this->api == 'IntlRate')
		{
			if(!$attribs['pounds']) return false;
			if(!$attribs['ounces']) return false;

			if(!$attribs['mail_type']) return false;
			else {
				switch(strtolower($attribs['mail_type']))
				{
					case 'package':
					case 'postcards or aerogrammes':
					case 'matter for the blind':
					case 'envelope':
						break;
					default:
						return false;
				}
			}

			if(!isset($attribs['country'])) return false;
			if(!in_array($attribs['country'], $this->country_list)) return false;

			//Add the package to the XML request
			$this->request_xml .= '<Package ID="' . $this->package_index . '">';
			$this->package_index++;	
			$this->request_xml .= '<Pounds>' . $attribs['pounds'] . '</Pounds><Ounces>' . $attribs['ounces'] . '</Ounces>';
			$this->request_xml .= '<MailType>' . $attribs['mail_type'] . '</MailType><Country>' . $attribs['country'] . '</Country>';
			$this->request_xml .= '</Package>';
		}

		return true;
	}

	function submit_request()
	{
		$this->request_xml .= '</' . $this->api . 'Request>';

		//Create a cURL instance and retrieve XML response
		if(!is_callable("curl_exec")) die("USPS::submit_request: curl_exec is uncallable");
		$ch = curl_init("https://secure.shippingapis.com/ShippingAPITest.dll");
		//$ch = curl_init("http://production.shippingapis.com/ShippingAPI.dll");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "API=" . $this->api . "&XML=" . $this->request_xml);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$return_xml = curl_exec($ch);

		//The return XML will be parsed with DOMXML into the current_result array; other accessor functions
		//will be able to return specific information about specific packages

		if(!is_callable("domxml_open_mem")) die("USPS::submit_request: domxml_open_mem is uncallable");

		//All variables prefixed with x_ are DOMXML objects
		$x_doc = domxml_open_mem($return_xml);

		$x_root = $x_doc->document_element();
		
		/*
		I commented this stuff out because I don't believe that errors are ever returned at the root level.
		
		//Is the document root an error message? If so, parse it into current_result and return false
		if($x_root->tagname() == 'Error')
		{
			foreach($x_root->child_nodes() as $x_err_component)
			{
				$key = $x_err_component->tagname();
				$value = $x_err_component->get_content();
				$this->current_result[0][$key] = $value;
			}
			return false;
		}
		*/

		if($x_root->tagname() == 'RateV2Response')
		{
			//Domestic return data needs to be parsed differently from international data

			foreach($x_root->child_nodes() as $x_package)
			{
				$package_id = $x_package->get_attribute("ID");
				$x_zip_origin = $x_package->first_child();	//Could be Error or ZipOrigination

				if($x_zip_origin->tagname() == 'Error')
				{
					foreach($x_zip_origin->child_nodes() as $x_err_component)
					{
						$key = $x_err_component->tagname();
						$value = $x_err_component->get_content();
						$this->current_result[$package_id]['Error'][$key] = $value;
					}
				}

				else if($x_zip_origin->tagname() == 'ZipOrigination')
				{
					foreach($x_package->child_nodes() as $x_pkg_info)
					{
						if($x_pkg_info->tagname() == 'Postage')
						{
							$x_mailservice = $x_pkg_info->first_child();
							$x_rate = $x_mailservice->next_sibling();
							
							$key = $x_mailservice->get_content();
							$value = $x_rate->get_content();
							
							$this->current_result[$package_id]['Postage'][$key] = $value;
						}
						
						else
						{
							$key = $x_pkg_info->tagname();
							$value = $x_pkg_info->get_content();
							$this->current_result[$package_id][$key] = $value;
						}
					}
				}
			}
		}

		else if($x_root->tagname() == 'IntlRateResponse')
		{
			//This is international data, so parse it accordingly

			foreach($x_root->child_nodes() as $x_package)
			{
				$package_id = $x_package->get_attribute('ID');
				$x_prohibitions = $x_package->first_child();

				if($x_prohibitions->tagname() == 'Error')
				{
					foreach($x_prohibitions->child_nodes() as $x_err_component)
					{
						$key = $x_err_component->tagname();
						$value = $x_err_component->get_content();
						$this->current_result[$package_id]['Error'][$key] = $value;
					}
				}

				else
				{
					$x_restrictions = $x_prohibitions->next_sibling();
					$x_observations = $x_restrictions->next_sibling();
					$x_customsforms = $x_observations->next_sibling();
					$x_expressmail = $x_customsforms->next_sibling();
					$x_areasserved = $x_expressmail->next_sibling();

					$this->current_result[$package_id]['Prohibitions'] = $x_prohibitions->get_content();
					$this->current_result[$package_id]['Restrictions'] = $x_restrictions->get_content();
					$this->current_result[$package_id]['Observations'] = $x_observations->get_content();
					$this->current_result[$package_id]['CustomsForms'] = $x_customsforms->get_content();
					$this->current_result[$package_id]['ExpressMail'] = $x_expressmail->get_content();
					$this->current_result[$package_id]['AreasServed'] = $x_areasserved->get_content();

					foreach($x_package->child_nodes() as $x_pkg_info)
					{
						if($x_pkg_info->tagname() == 'Service')
						{
							$service_id = $x_pkg_info->get_attribute('ID');
							foreach($x_pkg_info->child_nodes() as $x_svc_info)
							{
								$key = $x_svc_info->tagname();
								$value = $x_svc_info->get_content();
								$this->current_result[$package_id]['Service'][$service_id][$key] = $value;
							}
						}
					}
				}
			}
		}

		return true;
	}

	function get_rates($package_id = 0)
	{
		if($this->current_result[$package_id]['Error']) return $this->current_result[$package_id]['Error']['Description'];

		if($this->api == 'RateV2')
			return $this->current_result[$package_id]['Postage'];
		else if($this->api == 'IntlRate')
		{
			//SvcDescription and Postage
			$result = array();

			foreach($this->current_result[$package_id]['Service'] as $service)
			{
				$key = $service['SvcDescription'];
				$result[$key] = $service['Postage'];
			}

			return $result;
		}
		else return false;
	}

	function get_prohibitions($package_id)
	{
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Prohibitions'];
		else return false;
	}

	function get_restrictions($package_id)
	{
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Restrictions'];
		else return false;
	}

	function get_observations($package_id)
	{
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Observations'];
		else return false;
	}

	function get_areas_served($package_id)
	{
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['AreasServed'];
		else return false;
	}

	function get_package_error($package_id)
	{
		if($this->current_result[$package_id]['Error']) return $this->current_result[$package_id]['Error'];
	}
}

?>