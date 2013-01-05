<?php
// BME WMS
// Page: Products Manager Homepage
// Path/File: /admin/products_admin2.php
// Version: 1.8
// Build: 1807
// Date: 05-20-2007

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

$page_this = $_REQUEST["page_this"];
$field = $_REQUEST['field'];
$dir = $_REQUEST['dir'];
$delete = $_REQUEST["delete"];
$prod_sku_id = $_REQUEST["prod_sku_id"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Manage Product SKUs";
$url = "products_admin2.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($delete == 1) {
	$query = "DELETE FROM product_skus WHERE prod_sku_id='$prod_sku_id'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
}

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
<script language="JavaScript">
function warn_on_submit ()
{
	var msg = confirm("\nAre you sure you want to delete this Product SKU?\n\n");
	
	if (msg) {
		return true;
	} else {
		return false;
	}
}
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Below is listed all the Product SKUs for your website. Click the Edit button to make changes to any Product SKU.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr>
<?php
$labels = array('Product', 'Status', 'SKU', 'Display on Website');
$fields = array('name', 'active', 'sku', 'display_on_website');
getColumnHeaders($url, $page_this, $labels, $fields);
?>
<th scope="col">&nbsp;</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM product_skus";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT prod_sku_id, name, active, sku, display_on_website ";
$query .= "FROM product_skus ";
$query .= "ORDER BY ";
if( $field && $dir ) {
	$query .= $field." ".$dir." ";
} else {
	$query .= "active DESC, name ASC ";
}
$query .= "LIMIT $record_start,$limit";

$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["name"];
	echo "</td><td>";
	if($line["active"] == '0') { echo "Inactive"; }
	elseif($line["active"] == '1') { echo "Active"; }
	echo "</td><td>";
	echo $line["sku"];
	echo "</td><td>";
	if($line['display_on_website'] == 1) {
		echo "Yes";
	} elseif($line['display_on_website'] == 0) {
		echo "No";
	}
	echo "</td>";
	
	echo "<FORM name=\"products-manage\" Method=\"POST\" ACTION=\"./products_admin2_edit.php\" class=\"wmsform\">";
	echo "<input type=\"hidden\" name=\"prod_sku_id\" value=\"";
	echo $line["prod_sku_id"];
	echo "\">";
	echo "<td align=\"center\">";
	echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\">";
	echo "</td>";
	echo "</form> ";
	echo "<FORM name=\"products-delete\" Method=\"POST\" ACTION=\"./products_admin2.php\" class=\"wmsform\">";
	echo "<input type=\"hidden\" id=\"delete\" name=\"delete\" value=\"1\">";
	echo "<input type=\"hidden\" name=\"prod_sku_id\" value=\"";
	echo $line["prod_sku_id"];
	echo "\">";
	echo "<td align=\"center\">";
	if($line["active"] == '0') {
		echo "<input type=\"image\" src=\"/images/wms/delete.gif\" id=\"delete_button\" name=\"delete_button\" width=\"16\" height=\"16\" alt=\"Delete\" onClick=\"return warn_on_submit()\">";
	} else {
		echo "&nbsp;";
	}
	echo "</td>";
	echo "</form>";
	echo "</tr>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

<?php
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