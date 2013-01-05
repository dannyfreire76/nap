<?php 
// BME WMS 
// Page: Find a Retailer page
// Path/File: /findretailer/index.php
// Version: 1.1
// Build: 1115
// Date: 09-26-2006

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$zone = $_GET["zone"];

include_once('../includes/retailer1.php');


//not used right now
function getDistance($lat1, $lon1, $lat2, $lon2) {   
	$pi = 3.14159265358979323846;   
	$x = sin( $lat1 * $pi/180 ) * sin( $lat2 * $pi/180  ) + cos( $lat1 *$pi/180 ) * cos( $lat2 * $pi/180 ) * cos( abs( ($lon2 * $pi/180) - ($lon1 *$pi/180) ) );   
	$x = atan( ( sqrt( 1- pow( $x, 2 ) ) ) / $x );   
	return ( 1.852 * 60.0 * (($x/$pi)*180) ) / 1.609344;
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) { 

  $theta = $lon1 - $lon2; 
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
  $dist = acos($dist); 
  $dist = rad2deg($dist); 
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344); 
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

function msort($array, $id="id", $sort_ascending=true) {
	$temp_array = array();
	while(count($array)>0) {
		$lowest_id = 0;
		$index=0;
		foreach ($array as $item) {
			if (isset($item[$id])) {
								if ($array[$lowest_id][$id]) {
				if ($item[$id]<$array[$lowest_id][$id]) {
					$lowest_id = $index;
				}
				}
							}
			$index++;
		}
		$temp_array[] = $array[$lowest_id];
		$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
	}
			if ($sort_ascending) {
		return $temp_array;
			} else {
				return array_reverse($temp_array);
			}
}

if ( $_GET["submittal"]==1 ) {
	$query2 = "SELECT latitude, longitude from zip_codes WHERE zip = '".$_GET['zip']."'";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	if ( mysql_num_rows($result2) > 0 ) {
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$lat1 = $line2["latitude"];
			$lon1 = $line2["longitude"];

			$query3 = "SELECT retailer_id, store_name, store_name_website, address1, address2, r.city, r.state, r.zip, country, contact_phone_website, contact_fax_website, contact_name_website, contact_website_website, contact_email_website, hours_days_operation, items_sold, z.county, z.latitude, z.longitude FROM retailer AS r, zip_codes as z WHERE retailer_status='1' AND list_store_website='1' AND z.zip = r.zip";
			$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());

			if ( $result3 ) {
				$all_stores = array();
				$store_ct = 0;
				while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					//echo $line3["city"].', '.$line3["state"].' '.$line3["zip"].': '.round(distance($lat1, $lon1, $line3["latitude"], $line3["longitude"], 'm'), 1).'<br />';
					$all_stores[$store_ct] = 
						array( 'retailer_id' => $line3["retailer_id"], 
							   'distance' => round(distance($lat1, $lon1, $line3["latitude"], $line3["longitude"], 'm'), 1),
							   'store_name' => $line3["store_name"],
								'store_name_website' => $line3["store_name_website"],
							   'address1' => $line3["address1"],
							   'address2' => $line3["address2"],
							   'city' => $line3["city"],
							   'state' => $line3["state"],
							   'zip' => $line3["zip"],
							   'country' => $line3["country"],
							   'contact_phone_website' => $line3["contact_phone_website"],
							   'contact_fax_website' => $line3["contact_fax_website"],
							   'contact_name_website' => $line3["contact_name_website"],
							   'contact_website_website' => $line3["contact_website_website"],
							   'contact_email_website' => $line3["contact_email_website"],
							   'hours_days_operation' => $line3["hours_days_operation"],
							   'items_sold' => $line3["items_sold"],
							   'county' => $line3["county"]
							);
					$store_ct++;
				}
				//sort( $all_stores );
				$sorted_stores = msort($all_stores, "distance");

				$sorted_ct = 0;
				echo '<table class="retailer text_left">';
				foreach ($sorted_stores as $key=>$val) {
					$sorted_ct++;
					$row_class = 'odd';
					if ( $sorted_ct <= 3 ) {
						if ( $sorted_ct%2==0 ) {
							$row_class = 'even';
						}
						//echo $val['city'].': '.$val['distance'].'<br />';
						buildStore($val, $row_class);
					}
					else {
						break;
					}
				}
				echo '</table>';
			}

		}
	}
	else {
		echo '<span class="error">Sorry, we were unable to locate that zip code.  Please try a different location.</span>';
	}
	mysql_free_result($result2);
	exit();
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Find a Retailer</title>

<?php 
include '../includes/meta1.php';
?>

<script src="../includes/retailers.js" type="text/javascript"></script>

</head>
<body bgcolor="#<?php echo $bgcolor; ?>">
<div align="center">

<?php 
include '../includes/head1.php';
?>

<table border="0" id="f_retailer" cellpadding="4">

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<div class="window_top">
		  <div class="window_top_content">
			Find a Retailer
		  </div>

		  <div class="window_content">
			<form action="" name="fr_form" id="fr_form">
				<div id="zip_form_div">
					Please enter your zip code:
					<br /><br />
					<input type="text" name="zip" id="zip" size="5" maxlength="5" /> <input type="button" id="retailer_go" value="Go" />
				</div>
				<div class="loading  text_center no_display"></div>
			</form>
		</div>
		<div class="window_bottom"><div class="window_bottom_end"></div></div>
	</div>
</td>
</tr>
</table>
<div class="no_display text_left" id="retail_results"></div>
<br />
<?php 
include '../includes/foot1.php'; 
mysql_close($dbh); 
?>
</div>
</body>
</html>