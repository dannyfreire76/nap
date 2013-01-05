<?php
// BME WMS
// Page: State and Country Select Include file
// Version: 1.1
// Build: 1101
// Date: 12-28-2006

function state_build_all($state) {
	$query="SELECT code, name FROM states ORDER BY name";
	$result=mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) { 
		$codes[] = $line["code"];
		$states[] = $line["name"];
	}
	mysql_free_result($result);

	echo "<option value=\"\">Select a State</option>\n";
	
	for($i=0;$i<count($states);$i++) {
		echo "<option value=\"";
		echo $codes[$i];
		echo "\"";
		if($state == $codes[$i]) { echo " SELECTED"; }
		echo ">";
		echo $states[$i];
		echo "</option>\n";
	}
}

function state_build_active($state) {
	
}

function country_build_all($country) {
	$query="SELECT code, name FROM countries ORDER BY name";
	$result=mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line=mysql_fetch_array($result, MYSQL_ASSOC)) { 
		$codes[] = $line["code"];
		$countries[] = $line["name"];
	}
	mysql_free_result($result);

	echo "<option value=\"\">Select a Country</option>\n";
	
	for($i=0;$i<count($countries);$i++) {
		echo "<option value=\"";
		echo $codes[$i];
		echo "\"";
		if($country == $codes[$i]) { echo " SELECTED"; }
		echo ">";
		echo $countries[$i];
		echo "</option>\n";
	}
}

function country_build_active($country) {
	
}
?>