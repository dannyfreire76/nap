<?php
// BME WMS
// Page: Browse All Retailers Search Results page
// Path/File: /admin/retailers_admin7.php
// Version: 1.8
// Build: 1807
// Date: 07-04-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/pagination1.php';
include './includes/tabler1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$page_this = $_GET["page_this"];
$field = $_GET['field'];
if (!$field) {
    $field = 'store_name';
}
$dir = $_GET['dir'];
if (!$dir) {
    $dir = 'asc';
}

$query = "SELECT product_line FROM retailer_main";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$product_line = $line["product_line"];
}
mysql_free_result($result);

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "retailers";
$page = "Retailers Manager > Duplicate Retailers";
$url = "retailers_dupes.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="../admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" onload="$('#loading_tmp').hide()">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Duplicate Retailers&#160;&#160;<img id="loading_tmp" src="/images/icons/iconLoadingAnimation.gif" align="absmiddle" /></font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr>
<?php
$labels = array('Retailer ID', 'Store Name', 'Contact Name', 'Address', 'City', 'State', 'Zip', 'Phone');
$fields = array('retailer_id', 'store_name', 'contact_name', 'address1', 'city', 'state', 'zip', 'phone');
getColumnHeaders($url, $page_this, $labels, $fields);
?>
<th scope="col">&nbsp;</th></tr>

<?php

$line_counter = 0;
$query = "SELECT retailer_id, store_name, contact_name, address1, city, state, zip, phone, retailer_status ";
$query .= "FROM retailer ret1 WHERE EXISTS (SELECT NULL FROM retailer ret2 WHERE ret2.store_name = ret1.store_name AND ret2.zip = ret1.zip AND ret2.phone = ret1.phone AND ret2.address1 = ret1.address1 AND ret2.retailer_id != ret1.retailer_id) ";
$query .= "ORDER BY ";
if($field == 'store_name') {
	if($dir == 'asc') {
		$query .= "store_name ASC ";
	} elseif($dir == 'dsc') {
		$query .= "store_name DESC ";
	}
} elseif($field == 'contact_name') {
	if($dir == 'asc') {
		$query .= "contact_name ASC ";
	} elseif($dir == 'dsc') {
		$query .= "contact_name DESC ";
	}
} elseif($field == 'address1') {
	if($dir == 'asc') {
		$query .= "address1 ASC ";
	} elseif($dir == 'dsc') {
		$query .= "address1 DESC ";
	}
} elseif($field == 'city') {
	if($dir == 'asc') {
		$query .= "city ASC ";
	} elseif($dir == 'dsc') {
		$query .= "city DESC ";
	}
} elseif($field == 'state') {
	if($dir == 'asc') {
		$query .= "state ASC ";
	} elseif($dir == 'dsc') {
		$query .= "state DESC ";
	}
} elseif($field == 'zip') {
	if($dir == 'asc') {
		$query .= "zip ASC ";
	} elseif($dir == 'dsc') {
		$query .= "zip DESC ";
	}
} elseif($field == 'phone') {
	if($dir == 'asc') {
		$query .= "phone ASC ";
	} elseif($dir == 'dsc') {
		$query .= "phone DESC ";
	}
} else {
	$query .= "state DESC ";
}
$query .= "LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"retailers-manage\" Method=\"POST\" ACTION=\"./retailers_admin4.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo ">";
    echo '<td>'.$line["retailer_id"].'</td>';
    echo "<td>";
	echo stripslashes($line["store_name"]);
	echo "</td><td>";
	echo stripslashes($line["contact_name"]);
	echo "</td><td>";
	echo stripslashes($line["address1"]);
	echo "</td><td>";
	echo stripslashes($line["city"]);
	echo "</td><td>";
	echo $line["state"];
	echo "</td><td>";
	echo $line["zip"];
	echo "</td><td NOWRAP>";
	echo $line["phone"];
	echo "</td><td align=\"center\">";
	echo "<input type=\"hidden\" name=\"retailer_id\" value=\"";
	echo $line["retailer_id"];
	echo "\">";
	echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\">";
	echo "</td></tr>\n";
	echo "</form>\n";
}
?>
</table></td></tr>

<?php
$record_count = mysql_num_rows($result);
pagination_display($url, $page_this, $limit, $record_count, $field, $dir);
?>
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