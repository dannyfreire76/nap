<?php
// BME WMS
// Page: Leads Statistics page
// Path/File: /admin/leads_admin6.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Leads Statistics";
wms_manager_nav2($manager);
wms_page_nav2($manager);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">This page lists statistics for the Leads in the Leads Manager. This data is based on what is in the database at this very second.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<?php
$total_counter = 0;

	$query = "SELECT leads_id FROM leads";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total_counter++;
	}
	mysql_free_result($result);

	
?>
<tr><td align="left"><font size="2">There are <b><?php echo $total_counter; ?></b> Total Leads in the Leads Manager</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$status_converted = 0;
$status_lead = 0;

	$query = "SELECT leads_status FROM leads";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($line["leads_status"] == "0") { $status_converted++; }
		if($line["leads_status"] == "1") { $status_lead++; }
	}
	mysql_free_result($result);
?>

<tr><td align="left"><font size="2">Leads Active <b><?php echo $status_lead; ?></b></font></td></tr>
<tr><td align="left"><font size="2">Leads Converted to Retailers <b><?php echo $status_converted; ?></b></font></td></tr>

<tr><td>&nbsp;</td></tr>
<tr><td align="left"><font size="2"><b>By Type of Retailer</b></font></td></tr>
<?php
	$query = "SELECT retailer_type_id, name FROM retailer_type";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$retailer_type[] = $line["retailer_type_id"] . "|" . $line["name"];
	}
	mysql_free_result($result);

	$retailer_type_count = count($retailer_type);	
	for($i=0;$i<$retailer_type_count;$i++) {
		list($retailer_type_id, $retailer_type_name) = explode('|', $retailer_type[$i]);
		
		$query2 = "SELECT count(*) as count FROM leads_type_link WHERE retailer_type_id='$retailer_type_id'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$type_count = $line2["count"];
		}
		mysql_free_result($result2);

		
		echo "<tr><td align=\"left\"><font size=\"2\">There are <b>";
		echo $type_count;
		echo "</b> ";
		echo $retailer_type_name;
		echo " Leads</font></td></tr>";
	}
?>

<tr><td>&nbsp;</td></tr>

<?php
$country_us = 0;
$country_ca = 0;
$country_uk = 0;
$country_gb = 0;
$country_nl = 0;
$country_de = 0;
$country_ch = 0;
$country_it = 0;
$country_at = 0;
$country_pl = 0;
$country_au = 0;
$country_jp = 0;
$country_es = 0;
$country_nz = 0;
$country_pr = 0;
$country_hk = 0;
$country_fr = 0;
$country_er = 0;
$country_mx = 0;
$country_se = 0;
$country_id = 0;
$country_cm = 0;
$country_be = 0;
$country_tp = 0;
$country_ga = 0;
$country_none = 0;
$country_other = 0;
$country_other_name = "";

	$query = "SELECT country FROM leads";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($line["country"] == "US") { $country_us++; }
		elseif($line["country"] == "CA") { $country_ca++; }
		elseif($line["country"] == "UK") { $country_uk++; }
		elseif($line["country"] == "GB") { $country_gb++; }
		elseif($line["country"] == "NL") { $country_nl++; }
		elseif($line["country"] == "DE") { $country_de++; }
		elseif($line["country"] == "CH") { $country_ch++; }
		elseif($line["country"] == "IT") { $country_it++; }
		elseif($line["country"] == "AT") { $country_at++; }
		elseif($line["country"] == "PL") { $country_pl++; }
		elseif($line["country"] == "AU") { $country_au++; }
		elseif($line["country"] == "JP") { $country_jp++; }
		elseif($line["country"] == "ES") { $country_es++; }
		elseif($line["country"] == "NZ") { $country_nz++; }
		elseif($line["country"] == "PR") { $country_pr++; }
		elseif($line["country"] == "HK") { $country_hk++; }
		elseif($line["country"] == "FR") { $country_fr++; }
		elseif($line["country"] == "ER") { $country_er++; }
		elseif($line["country"] == "MX") { $country_mx++; }
		elseif($line["country"] == "SE") { $country_se++; }
		elseif($line["country"] == "ID") { $country_id++; }
		elseif($line["country"] == "CM") { $country_cm++; }
		elseif($line["country"] == "BE") { $country_be++; }
		elseif($line["country"] == "TP") { $country_tp++; }
		elseif($line["country"] == "GA") { $country_ga++; }
		elseif($line["country"] == "") { $country_none++; }
		else { 
			$country_other++;
			$country_other_name .= $line["country"] . "|";
		}
	}
	mysql_free_result($result);

if($country_us != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_us; ?></b> Leads in the United States</font></td></tr>
<?php }

if($country_ca != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_ca; ?></b> Leads in Canada</font></td></tr>
<?php }

if($country_uk != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_uk; ?></b> Leads in the United Kingdom</font></td></tr>
<?php }

if($country_nl != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_nl; ?></b> Leads in the Netherlands</font></td></tr>
<?php }

if($country_de != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_de; ?></b> Leads in Germany</font></td></tr>
<?php }

if($country_ch != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_ch; ?></b> Leads in Switzerland</font></td></tr>
<?php }

if($country_it != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_it; ?></b> Leads in Itlay</font></td></tr>
<?php }

if($country_at != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_at; ?></b> Leads in Austria</font></td></tr>
<?php }

if($country_pl != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_pl; ?></b> Leads in Poland</font></td></tr>
<?php }

if($country_au != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_au; ?></b> Leads in Australia</font></td></tr>
<?php }

if($country_jp != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_jp; ?></b> Leads in Japan</font></td></tr>
<?php }

if($country_es != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_es; ?></b> Leads in Spain</font></td></tr>
<?php }

if($country_nz != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_nz; ?></b> Leads in New Zealand</font></td></tr>
<?php }

if($country_pr != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_pr; ?></b> Leads in Puerto Rico</font></td></tr>
<?php }

if($country_hk != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_hk; ?></b> Leads in Hong Kong</font></td></tr>
<?php }

if($country_fr != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_fr; ?></b> Leads in France</font></td></tr>
<?php }

if($country_er != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_er; ?></b> Leads in Eritrea</font></td></tr>
<?php }

if($country_mx != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_mx; ?></b> Leads in Mexico</font></td></tr>
<?php }

if($country_se != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_se; ?></b> Leads in Sweden</font></td></tr>
<?php }

if($country_id != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_id; ?></b> Leads in Indonesia</font></td></tr>
<?php }

if($country_cm != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_cm; ?></b> Retailers in Cameroon</font></td></tr>
<?php }

if($country_be != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_be; ?></b> Retailers in Belgium</font></td></tr>
<?php }

if($country_tp != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_tp; ?></b> Retailers in East Timor</font></td></tr>
<?php }

if($country_ga != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_ga; ?></b> Retailers in Gabon</font></td></tr>
<?php }

if($country_none != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_none; ?></b> Leads with no country set</font></td></tr>
<?php }

if($country_other != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $country_other; ?></b> Leads from other countries</font></td></tr>
<?php } ?>

<tr><td>&nbsp;</td></tr>
<?php
$state_AA = 0;
$state_AE = 0;
$state_AP = 0;
$state_AL = 0;
$state_AK = 0;
$state_AB = 0;
$state_AZ = 0;
$state_AR = 0;
$state_BC = 0;
$state_CA = 0;
$state_CO = 0;
$state_CT = 0;
$state_DE = 0;
$state_DC = 0;
$state_FL = 0;
$state_GA = 0;
$state_HI = 0;
$state_ID = 0;
$state_IL = 0;
$state_IN = 0;
$state_IA = 0;
$state_KS = 0;
$state_KY = 0;
$state_LA = 0;
$state_ME = 0;
$state_MB = 0;
$state_MD = 0;
$state_MA = 0;
$state_MI = 0;
$state_MN = 0;
$state_MS = 0;
$state_MO = 0;
$state_MT = 0;
$state_NE = 0;
$state_NV = 0;
$state_NB = 0;
$state_NH = 0;
$state_NJ = 0;
$state_NM = 0;
$state_NY = 0;
$state_NF = 0;
$state_NC = 0;
$state_ND = 0;
$state_NT = 0;
$state_NS = 0;
$state_OH = 0;
$state_OK = 0;
$state_ON = 0;
$state_OR = 0;
$state_PA = 0;
$state_PE = 0;
$state_QC = 0;
$state_RI = 0;
$state_SK = 0;
$state_SC = 0;
$state_SD = 0;
$state_TN = 0;
$state_TX = 0;
$state_UT = 0;
$state_VT = 0;
$state_VA = 0;
$state_WA = 0;
$state_WV = 0;
$state_WI = 0;
$state_WY = 0;
$state_YT = 0;
$state_none = 0;
$state_other = 0;

	$query = "SELECT state FROM leads";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($line["state"] == "AA") { $state_AA++; }
		elseif($line["state"] == "AE") { $state_AE++; }
		elseif($line["state"] == "AP") { $state_AP++; }
		elseif($line["state"] == "AL") { $state_AL++; }
		elseif($line["state"] == "AK") { $state_AK++; }
		elseif($line["state"] == "AB") { $state_AB++; }
		elseif($line["state"] == "AZ") { $state_AZ++; }
		elseif($line["state"] == "AR") { $state_AR++; }
		elseif($line["state"] == "BC") { $state_BC++; }
		elseif($line["state"] == "CA") { $state_CA++; }
		elseif($line["state"] == "CO") { $state_CO++; }
		elseif($line["state"] == "CT") { $state_CT++; }
		elseif($line["state"] == "DE") { $state_DE++; }
		elseif($line["state"] == "DC") { $state_DC++; }
		elseif($line["state"] == "FL") { $state_FL++; }
		elseif($line["state"] == "GA") { $state_GA++; }
		elseif($line["state"] == "HI") { $state_HI++; }
		elseif($line["state"] == "ID") { $state_ID++; }
		elseif($line["state"] == "IL") { $state_IL++; }
		elseif($line["state"] == "IN") { $state_IN++; }
		elseif($line["state"] == "IA") { $state_IA++; }
		elseif($line["state"] == "KS") { $state_KS++; }
		elseif($line["state"] == "KY") { $state_KY++; }
		elseif($line["state"] == "LA") { $state_LA++; }
		elseif($line["state"] == "ME") { $state_ME++; }
		elseif($line["state"] == "MB") { $state_MB++; }
		elseif($line["state"] == "MD") { $state_MD++; }
		elseif($line["state"] == "MA") { $state_MA++; }
		elseif($line["state"] == "MI") { $state_MI++; }
		elseif($line["state"] == "MN") { $state_MN++; }
		elseif($line["state"] == "MS") { $state_MS++; }
		elseif($line["state"] == "MO") { $state_MO++; }
		elseif($line["state"] == "MT") { $state_MT++; }
		elseif($line["state"] == "NE") { $state_NE++; }
		elseif($line["state"] == "NV") { $state_NV++; }
		elseif($line["state"] == "NB") { $state_NB++; }
		elseif($line["state"] == "NH") { $state_NH++; }
		elseif($line["state"] == "NJ") { $state_NJ++; }
		elseif($line["state"] == "NM") { $state_NM++; }
		elseif($line["state"] == "NY") { $state_NY++; }
		elseif($line["state"] == "NF") { $state_NF++; }
		elseif($line["state"] == "NC") { $state_NC++; }
		elseif($line["state"] == "ND") { $state_ND++; }
		elseif($line["state"] == "NT") { $state_NT++; }
		elseif($line["state"] == "NS") { $state_NS++; }
		elseif($line["state"] == "OH") { $state_OH++; }
		elseif($line["state"] == "OK") { $state_OK++; }
		elseif($line["state"] == "ON") { $state_ON++; }
		elseif($line["state"] == "OR") { $state_OR++; }
		elseif($line["state"] == "PA") { $state_PA++; }
		elseif($line["state"] == "PE") { $state_PE++; }
		elseif($line["state"] == "QC") { $state_QC++; }
		elseif($line["state"] == "RI") { $state_RI++; }
		elseif($line["state"] == "SK") { $state_SK++; }
		elseif($line["state"] == "SC") { $state_SC++; }
		elseif($line["state"] == "SD") { $state_SD++; }
		elseif($line["state"] == "TN") { $state_TN++; }
		elseif($line["state"] == "TX") { $state_TX++; }
		elseif($line["state"] == "UT") { $state_UT++; }
		elseif($line["state"] == "VT") { $state_VT++; }
		elseif($line["state"] == "VA") { $state_VA++; }
		elseif($line["state"] == "WA") { $state_WA++; }
		elseif($line["state"] == "WV") { $state_WV++; }
		elseif($line["state"] == "WI") { $state_WI++; }
		elseif($line["state"] == "WY") { $state_WY++; }
		elseif($line["state"] == "YT") { $state_YT++; }
		elseif($line["state"] == "") { $state_none++; }
		else {
			$state_other++;
			$state_other_name .= $line["state"] . "|";
		}
	}
	mysql_free_result($result);



if($state_AA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AA; ?></b> Leads in AF Asia</font></td></tr>
<?php }

if($state_AE != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AE; ?></b> Leads in AF Europe</font></td></tr>
<?php }

if($state_AP != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AP; ?></b> Leads in AF Pacific</font></td></tr>
<?php }

if($state_AL != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AL; ?></b> Leads in Alabama</font></td></tr>
<?php }

if($state_AK != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AK; ?></b> Leads in Alaska</font></td></tr>
<?php }

if($state_AB != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AB; ?></b> Leads in Alberta</font></td></tr>
<?php }

if($state_AZ != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AZ; ?></b> Leads in Arizona</font></td></tr>
<?php }

if($state_AR != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_AR; ?></b> Leads in Arkansas</font></td></tr>
<?php }

if($state_BC != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_BC; ?></b> Leads in British Columbia</font></td></tr>
<?php }

if($state_CA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_CA; ?></b> Leads in California</font></td></tr>
<?php }

if($state_CO != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_CO; ?></b> Leads in Colorado</font></td></tr>
<?php }

if($state_CT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_CT; ?></b> Leads in Connecticut</font></td></tr>
<?php }

if($state_DE != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_DE; ?></b> Leads in Delaware</font></td></tr>
<?php }

if($state_DC != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_DC; ?></b> Leads in District of Columbia</font></td></tr>
<?php }

if($state_FL != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_FL; ?></b> Leads in Florida</font></td></tr>
<?php }

if($state_GA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_GA; ?></b> Leads in Georgia</font></td></tr>
<?php }

if($state_HI != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_HI; ?></b> Leads in Hawaii</font></td></tr>
<?php }

if($state_ID != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_ID; ?></b> Leads in Idaho</font></td></tr>
<?php }

if($state_IL != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_IL; ?></b> Leads in Illinois</font></td></tr>
<?php }

if($state_IN != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_IN; ?></b> Leads in Indiana</font></td></tr>
<?php }

if($state_IA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_IA; ?></b> Leads in Iowa</font></td></tr>
<?php }

if($state_KS != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_KS; ?></b> Leads in Kansas</font></td></tr>
<?php }

if($state_KY != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_KY; ?></b> Leads in Kentucky</font></td></tr>
<?php }

if($state_LA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_LA; ?></b> Leads in Louisiana</font></td></tr>
<?php }

if($state_ME != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_ME; ?></b> Leads in Maine</font></td></tr>
<?php }

if($state_MB != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MB; ?></b> Leads in Manitoba</font></td></tr>
<?php }

if($state_MD != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MD; ?></b> Leads in Maryland</font></td></tr>
<?php }

if($state_MA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MA; ?></b> Leads in Massachusetts</font></td></tr>
<?php }

if($state_MI != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MI; ?></b> Leads in Michigan</font></td></tr>
<?php }

if($state_MN != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MN; ?></b> Leads in Minnesota</font></td></tr>
<?php }

if($state_MS != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MS; ?></b> Leads in Mississippi</font></td></tr>
<?php }

if($state_MO != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MO; ?></b> Leads in Missouri</font></td></tr>
<?php }

if($state_MT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_MT; ?></b> Leads in Montana</font></td></tr>
<?php }

if($state_NE != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NE; ?></b> Leads in Nebraska</font></td></tr>
<?php }

if($state_NV != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NV; ?></b> Leads in Nevada</font></td></tr>
<?php }

if($state_NB != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NB; ?></b> Leads in New Brunswick</font></td></tr>
<?php }

if($state_NH != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NH; ?></b> Leads in New Hampshire</font></td></tr>
<?php }

if($state_NJ != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NJ; ?></b> Leads in New Jersey</font></td></tr>
<?php }

if($state_NM != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NM; ?></b> Leads in New Mexico</font></td></tr>
<?php }

if($state_NY != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NY; ?></b> Leads in New York</font></td></tr>
<?php }

if($state_NF != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NF; ?></b> Leads in Newfoundland</font></td></tr>
<?php }

if($state_NC != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NC; ?></b> Leads in North Carolina</font></td></tr>
<?php }

if($state_ND != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_ND; ?></b> Leads in North Dakota</font></td></tr>
<?php }

if($state_NT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NT; ?></b> Leads in Northwest Territories</font></td></tr>
<?php }

if($state_NS != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_NS; ?></b> Leads in Nova Scotia</font></td></tr>
<?php }

if($state_OH != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_OH; ?></b> Leads in Ohio</font></td></tr>
<?php }

if($state_OK != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_OK; ?></b> Leads in Oklahoma</font></td></tr>
<?php }

if($state_ON != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_ON; ?></b> Leads in Ontario</font></td></tr>
<?php }

if($state_OR != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_OR; ?></b> Leads in Oregon</font></td></tr>
<?php }

if($state_PA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_PA; ?></b> Leads in Pennsylvania</font></td></tr>
<?php }

if($state_PE != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_PE; ?></b> Leads in Prince Edward Island</font></td></tr>
<?php }

if($state_QC != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_QC; ?></b> Leads in Quebec</font></td></tr>
<?php }

if($state_RI != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_RI; ?></b> Leads in Rhode Island</font></td></tr>
<?php }

if($state_SK != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_SK; ?></b> Leads in Saskatchewan</font></td></tr>
<?php }

if($state_SC != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_SC; ?></b> Leads in South Carolina</font></td></tr>
<?php }

if($state_SD != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_SD; ?></b> Leads in South Dakota</font></td></tr>
<?php }

if($state_TN != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_TN; ?></b> Leads in Tennessee</font></td></tr>
<?php }

if($state_TX != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_TX; ?></b> Leads in Texas</font></td></tr>
<?php }

if($state_UT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_UT; ?></b> Leads in Utah</font></td></tr>
<?php }

if($state_VT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_VT; ?></b> Leads in Vermont</font></td></tr>
<?php }

if($state_VA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_VA; ?></b> Leads in Virginia</font></td></tr>
<?php }

if($state_WA != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_WA; ?></b> Leads in Washington</font></td></tr>
<?php }

if($state_WV != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_WV; ?></b> Leads in West Virginia</font></td></tr>
<?php }

if($state_WI != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_WI; ?></b> Leads in Wisconsin</font></td></tr>
<?php }

if($state_WY != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_WY; ?></b> Leads in Wyoming</font></td></tr>
<?php }

if($state_YT != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_YT; ?></b> Leads in Yukon</font></td></tr>
<?php }

if($state_none != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_none; ?></b> Leads with no state set</font></td></tr>
<?php } ?>

<?php
if($state_other != "0") { ?>
<tr><td align="left"><font size="2">There are <b><?php echo $state_other; ?></b> Leads from other states</font></td></tr>
<?php } ?>

<tr><td>&nbsp;</td></tr>
<?php
	$query = "SELECT count(*) as count FROM leads WHERE email !=''";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$inc_count = $line["count"];
	}
	mysql_free_result($result);
	$query = "SELECT count(*) as count FROM leads WHERE email=''";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ex_count = $line["count"];
	}
	mysql_free_result($result);
	$tot_count = $inc_count + $ex_count;
	if($tot_count != 0) {
		$per_count = $inc_count / $tot_count;
		$per_count2 = $ex_count / $tot_count;
	} else {
		$per_count = 0;
		$per_count2 = 0;
	}
	$per_count = sprintf("%01.4f", $per_count);
	$per_count = $per_count * 100;
	$per_count2 = sprintf("%01.4f", $per_count2);
	$per_count2 = $per_count2 * 100;
?>
<tr><td align="left"><font size="2">Leads with Email Addresses <b><?php echo $inc_count . " - " . $per_count . "%"; ?></b></font></td></tr>
<tr><td align="left"><font size="2">Leads without Email Addresses <b><?php echo $ex_count . " - " . $per_count2 . "%"; ?></b></font></td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>