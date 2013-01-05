<?php
// BME WMS
// Page: Retailers Search Results page
// Path/File: /admin/retailers_admin2.php
// Version: 1.8
// Build: 1807
// Date: 06-10-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/pagination1.php';
include './includes/tabler1.php';
include_once ($base_path.'includes/wc1.php');

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$page_this = $_GET["page_this"];
$field = $_GET["field"];
$dir = $_GET["dir"];

$contact_user_id = $_REQUEST["contact_user_id"];

$retailers_id = $_POST['retailers_id'];
if($_POST["store_name"]) {
	$store_name = $_POST["store_name"];
} elseif($_GET["store_name"]) {
	$store_name = $_GET["store_name"];
}
if($_POST["contact_name"]) {
	$contact_name = $_POST["contact_name"];
} elseif($_GET["contact_name"]) {
	$contact_name = $_GET["contact_name"];
}
if($_POST["city"]) {
	$city = $_POST["city"];
} elseif($_GET["city"]) {
	$city = $_GET["city"];
}
if($_POST["state"]) {
	$state = $_POST["state"];
} elseif($_GET["state"]) {
	$state = $_GET["state"];
}
if($_POST["zip"]) {
	$zip = $_POST["zip"];
} elseif($_GET["zip"]) {
	$zip = $_GET["zip"];
}
if($_POST["country"]) {
	$country = $_POST["country"];
} elseif($_GET["country"]) {
	$country = $_GET["country"];
}
if($_POST["phone"]) {
	$phone = $_POST["phone"];
} elseif($_GET["phone"]) {
	$phone = $_GET["phone"];
}

$where_store_found = $_REQUEST["where_store_found"];
$retailer_status = $_REQUEST["retailer_status"];
$funds_owed = $_REQUEST["funds_owed"];


$submit = $_POST['submit'];

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
$page = "Retailers Manager > Create Retailers";
//$url = "retailers_admin2.php";
$url = $currentFile;
wms_manager_nav2($manager);
wms_page_nav2($manager);

$phone = str_replace("(", "", $phone);
$phone = str_replace(")", "", $phone);
$phone = str_replace("-", "", $phone);
$phone = str_replace(".", "", $phone);
$phone = str_replace("+", "", $phone);
$phone = str_replace(" ", "", $phone);
$phone = str_replace("\\", "", $phone);
$phone = str_replace("/", "", $phone);
$phone = str_replace(":", "", $phone);
$phone = str_replace(";", "", $phone);
$phone = str_replace("<", "", $phone);
$phone = str_replace(">", "", $phone);
$phone = str_replace("#", "", $phone);
$phone = str_replace("@", "", $phone);



$query = "";

if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
	$query = " WHERE retailer_status='1' ";
} else if ( strpos($_SERVER['SCRIPT_NAME'], "admin14") ) {
	$query = " WHERE retailer_status='4' ";
} else if ( strpos($_SERVER['SCRIPT_NAME'], "admin5") ) {
	$query = " WHERE retailer_status!='1' AND retailer_status!='4' AND next_contact_by_person!='' ";

	if ( !$contact_user_id ) {
		$query .= " AND next_contact_by_person=$user_id ";
	}
} else if ( strpos($_SERVER['SCRIPT_NAME'], "admin9") ) {
	$query = " WHERE sent_promo_pack='2' ";
}
else {
	if($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "" || $country != "" || $where_store_found != "" || $retailer_status != "" || $funds_owed != "" || $retailer_type) {
		$query .= " WHERE";
	}
	if($store_name != "") {
		$query .= " store_name LIKE '%".addslashes($store_name)."%'";
	}
	if($store_name != "" && $contact_name != "") {
		$query .= " AND";
	}
	if($contact_name != "") {
		$query .= " contact_name LIKE '%".addslashes($contact_name)."%'";
	}
	if(($store_name != "" || $contact_name != "") && $city != "") {
		$query .= " AND";
	}
	if($city != "") {
		$query .= " city LIKE '%".addslashes($city)."%'";
	}
	if(($store_name != "" || $contact_name != "" || $city != "") && $phone != "") {
		$query .= " AND";
	}
	if($phone != "") {
		$query .= " (phone='$phone' OR fax_other_phone='$phone')";
	}
	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "") && $zip != "") {
		$query .= " AND";
	}
	if($zip != "") {
		$query .= " zip LIKE '$zip%'";
	}
	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "") && $state != "") {
		$query .= " AND";
	}
	if($state != "") {
		$query .= " state='$state'";
	}
	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "") && $country != "") {
		$query .= " AND";
	}
	if($country != "") {
		$query .= " country='$country'";
	}
	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "" || $country != "") && $where_store_found != "") {
		$query .= " AND";
	}
	if($where_store_found != "") {
		$query .= " where_store_found LIKE '%$where_store_found%'";
	}
	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "" || $country != "" || $where_store_found != "") && $retailer_status != "") {
		$query .= " AND";
	}
	if($retailer_status != "") {
		$query .= " retailer_status = '$retailer_status'";
	}

	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "" || $country != "" || $where_store_found != "" || $retailer_status != "") && $funds_owed != "") {
		$query .= " AND";
	}
	if($funds_owed != "") {
		$query .= " retailer.retailer_id IN (SELECT wholesale_receipts.retailer_id FROM wholesale_receipts WHERE funds_received=0 AND complete=1) ";
	}

	if(($store_name != "" || $contact_name != "" || $city != "" || $phone != "" || $zip != "" || $state != "" || $country != "" || $where_store_found != "" || $retailer_status != "") && $funds_owed != "" && $retailer_type ) {
		$query .= " AND";
	}

	if($retailer_type != "") {
		$query .= " retailer.retailer_id IN (SELECT retailer_id FROM retailer_type_link WHERE ";

		$queryRetType = "";
		foreach($retailer_type as $this_type) {
			$queryRetType .= ($queryRetType!=""? " OR ":"")." retailer_type_id = '".$this_type."'";
		}

		$query .= $queryRetType;
		$query .= " ) ";

	}
}

if($contact_user_id != "") {
	if ( $contact_user_id!='%' ) {
		if ( $query=="" ) {
			$query .= " WHERE ";
		}
		else {
			$query .= " AND ";
		}

		$query .= " next_contact_by_person='$contact_user_id' ";	
	}
} else if (strpos($_SERVER['SCRIPT_NAME'], "admin5")) {
	$contact_user_id = $user_id;
}

$queryCount = "SELECT count(*) as count FROM retailer".$query;
//echo $queryCount.'<br />';

$result = mysql_query($queryCount) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$queryRecords = "SELECT *, DATE_format(next_contact_on, '%m/%d/%Y') as next_contact_on_format, DATE_format(last_contact_on, '%m/%d/%Y') as last_contact_on_format FROM retailer ".$query;
$line_counter = 0;
	$queryRecords .= "ORDER BY ";
	if( $field && $dir ) {		
		$queryRecords .= "".$field." ".$dir." ";
	} else {
		if ( strpos($_SERVER['SCRIPT_NAME'], "admin9") ) {
			$queryRecords .= " last_contact_on DESC ";
		} else if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
			$queryRecords .= " next_contact_on DESC ";
		}
			else {
			$queryRecords .= "zip DESC ";
		}
	}
	$queryRecords .= "LIMIT $record_start,$limit";
	//echo $queryRecords.'<br />';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/date_input.css">

<script type="text/javascript" src="<?=$current_base?>includes/jquery.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/interface.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.dimensions.min.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.date_input.min.js"></script>
<script type="text/javascript">
    $(function() {//on doc ready
		$('[@wrID]').each(function(){
			$(this).date_input({ start_of_week: 0 });
		});


		$('[@retID]').each(function(){
			$('#'+$(this).attr('retID')+'_orders').slideToggle(200);
		})

		$('.saveChanges').click(function(){
			var post_url = 'retailers_admin12.php';//to avoid duplication just refer to existing file

			var post_data = {};
			post_data.action = 'update';
			
			$('input[@wrID]').each(function() {
				var wrID = $(this).attr('wrID');
				eval('post_data.funds_received_' + wrID +' = "' + $(this).val() + '"');//this is the only way to build up post params dynamically
			})

			var $thisLoading = $(this).siblings('.loading');
			$thisLoading.html('').removeClass('error3').removeClass('error');
			
			$.post(post_url, post_data, function(resp){
				if (resp=='ok') {
					$thisLoading.addClass('error3').html('Changes saved.');
				}
				else {
					$thisLoading.addClass('error').html(resp);
				}
				$thisLoading.parents('div:first').Pulsate(300,3);
			})
		})
    });
</script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Create a new Retailer.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left">
	<FORM name="retailers" Method="POST" ACTION="./retailers_admin3.php" class="wmsform">
	<input type="hidden" name="store_name" value="<?php echo $store_name; ?>">
	<input type="hidden" name="contact_name" value="<?php echo $contact_name; ?>">
	<input type="hidden" name="city" value="<?php echo $city; ?>">
	<input type="hidden" name="state" value="<?php echo $state; ?>">
	<input type="hidden" name="phone" value="<?php echo $phone; ?>">
	<input type="hidden" name="zip" value="<?php echo $zip; ?>">
	<input type="hidden" name="country" value="<?php echo $country; ?>">
	<input type="hidden" name="where_store_found" value="<?php echo $where_store_found; ?>">
	<fieldset>
		<legend></legend>
		<ol>
			<li class="fm-button">
				<input type="submit" id="create_retailer" name="create_retailer" value="Create New Retailer">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><form action="" method="POST"><font size="2">Retailers For <select name="contact_user_id" onChange="submit()">
<option value="%">All Users</option>
<?php
	$query = "SELECT user_id, first_name, last_name FROM wms_users";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<option value=\"";
		echo $line["user_id"];
		echo "\"";
		if($contact_user_id == $line["user_id"]) { echo " SELECTED"; }
		echo ">";
		echo $line["first_name"];
		echo " ";
		echo $line["last_name"];
		echo "</option>\n";
	}
	mysql_free_result($result);
?>
</select> To Contact</font></form>

<?php
	if ( $funds_owed ) {
		echo '<div class="right">';
		echo '<span class="loading"></span>';
		echo '<button class="saveChanges">save changes</button>';
		echo '</div>';
	}
?>


</td></tr>

<tr><td align="left">
<table class="maintable" width="100%" cellspacing="0">
<tr>
<?php
if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
	$labels = array('Store&#160;Name', 'Contact&#160;Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Next&#160;Contacted');
	$fields = array('store_name', 'contact_name', 'address1', 'city', 'state', 'zip', 'phone', 'next_contact_on');
} else {
	$labels = array('Store&#160;Name', 'Contact&#160;Name', 'Address', 'City', 'State', 'Zip', 'Phone', 'Last&#160;Contacted');
	$fields = array('store_name', 'contact_name', 'address1', 'city', 'state', 'zip', 'phone', 'last_contact_on');
}

getColumnHeaders($url, $page_this, $labels, $fields, $store_name, $contact_name, $city, $state, $zip, $country, $phone);
?>
<th colspan="2" scope="col" style="text-align:center;">Manage</th>

</tr>

<?php
	$result = mysql_query($queryRecords) or die("Query failed : " . mysql_error());
$storeCnt=0;
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$storeCnt++;
		$storeClass="odd";
		if ( $storeCnt%2==0 ) {
			$storeClass="even";
		}
		
		$this_funds_owed = 0;
		$queryFO = "SELECT *, DATE_format(ordered, '%m/%d/%Y') as ordered_format FROM wholesale_receipts WHERE retailer_id='".$line["retailer_id"]."' AND funds_received=0 AND complete=1";
		$resultFO = mysql_query($queryFO) or die("Query failed : " . mysql_error());
		if ( mysql_num_rows($resultFO) > 0 ) {
			$this_funds_owed = 1;
		}

		$line_counter++;
		$line_this = $line_counter / 2;
		echo "<FORM name=\"retailers-manage\" Method=\"POST\" ACTION=\"./retailers_admin4.php\" class=\"wmsform\">\n";
		echo '<tr valign="top" class="'.$storeClass.'"';
		if(is_int($line_this)) { echo " class=\"d\""; }
		echo "><td>";
		if ( $funds_owed ) {
			echo '<span onClick="$(\'#'.$line["retailer_id"].'_orders\').slideToggle(200);" retID="'.$line["retailer_id"].'" class="ul hand funds_owed">';
		}
		echo stripslashes($line["store_name"]);
		if ( $funds_owed ) {
			echo '</span>';
			echo '<div class="no_display" id="'.$line["retailer_id"].'_orders" style="padding-left:10px">';
			echo '<table class="maintable" cellpadding="2" cellspacing="0" border="0">';
			echo '<tr class="bold"><th>Date</th><th>Order #</th><th align="right">Total</th><th align="center">Funds Received</th></tr>';
			$ordCnt = 0;
			while ($lineFO = mysql_fetch_array($resultFO, MYSQL_ASSOC)) {
				$ordCnt++;
				$class="odd";
				if ( $ordCnt%2==0 ) {
					$class="even2";
				}
				echo '<tr class="'.$class.'"><td>'.$lineFO["ordered_format"].'</td><td>#'.$lineFO["wholesale_order_number"].'</td><td align="right">$'.condDecimalFormat($lineFO["total"]).'</td>';
				echo '<td><input type="text" size="10" name="funds_received_'.$lineFO["wholesale_receipt_id"].'" id="funds_received_'.$lineFO["wholesale_receipt_id"].'" wrID="'.$lineFO["wholesale_receipt_id"].'"></td>';
				echo '</tr>';
			}
			echo '</table>';
			echo '</div>';
		}
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
		echo "</td>";
		echo "<td NOWRAP>";
		if ( strpos($_SERVER['SCRIPT_NAME'], "admin8") ) {
			echo $line["next_contact_on_format"];
		} else {
			echo $line["last_contact_on_format"];
		}
		echo "</td>";
        echo "<td align=\"center\" style=\"padding-top:2px;\">";
		echo "<input type=\"hidden\" name=\"retailer_id\" value=\"";
		echo $line["retailer_id"];
		echo "\">";
		echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\" title=\"Edit Retailer\">";
		echo "</td>\n";
		echo "<td style=\"vertical-align:top;padding-top:0px;\">\n";
		
		
		$ordersSQL = "SELECT COUNT(retailer_id) as order_count
					  FROM wholesale_receipts
					  WHERE complete='1'
					  AND retailer_id='$line[retailer_id]'";
					  
		$ordersRS = mysql_query($ordersSQL) or die("Query failed : " . mysql_error());
		$orders = mysql_fetch_array($ordersRS, MYSQL_ASSOC);
		
		if(!empty($orders['order_count']) && $orders['order_count'] > 0){
			echo "<a href=\"retailers_admin12.php?retailer_id=$line[retailer_id]\" target=\"order-history\">\n";
			echo "<img style=\"margin:1px;\" src=\"/images/wms/history.gif\" border=\"0\" width=\"16\" height=\"16\" alt=\"Order History\" title=\"Order History\">";
			echo "</a>\n";
		}
		else{
			echo "&nbsp;";	
		}
		
		echo "</td></tr>\n";
		echo "</form>\n";
	}
	mysql_free_result($result);
?>
</table></td></tr>

<?php
pagination_display($url, $page_this, $limit, $record_count, $field, $dir, $store_name, $contact_name, $city, $state, $zip, $country, $phone);
?>
<tr><td>
<?php
	if ( $funds_owed ) {
		echo '<div class="right">';
		echo '<span class="loading"></span>';
		echo '<button class="saveChanges">save changes</button>';
		echo '</div>';
	}
?>&nbsp;
</td></tr>
</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>
</body>
</html>