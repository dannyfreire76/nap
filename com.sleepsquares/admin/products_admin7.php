<?php
// BME WMS
// Page: Manage Product Categories page
// Path/File: /admin/products_admin7.php
// Version: 1.8
// Build: 1808
// Date: 05-14-2007

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
$dir = $_GET['dir'];
$delete = $_POST["delete"];
$prod_cat_id = $_POST["prod_cat_id"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Manage Product Categories";
$url = "products_admin7.php";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($delete == 1) {
	$query = "DELETE FROM product_categories WHERE prod_cat_id='$prod_cat_id'";
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
	var msg = confirm("\nAre you sure you want to delete this Product Category?\n\n");
	
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

<tr><td align="left"><font size="2">Below is listed all the Product Categories for your website. Click the Edit button to make changes to any Product Category.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">
<tr>
<?php
$labels = array('Position', 'Category', 'Status', 'Is Parent', 'Parent');
$fields = array('position', 'name', 'active', 'is_parent', 'parent');
getColumnHeaders($url, $page_this, $labels, $fields);
?>
<th scope="col">&nbsp;</th><th scope="col">&nbsp;</th></tr>

<?php
$query = "SELECT count(*) as count FROM product_categories";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$record_count = $line["count"];
}
mysql_free_result($result);

$line_counter = 0;
$query = "SELECT prod_cat_id, name, is_parent, parent_cat, position, active ";
$query .= "FROM product_categories ";
$query .= "ORDER BY ";
if($field == 'active') {
	if($dir == 'asc') {
		$query .= "active ASC ";
	} elseif($dir == 'dsc') {
		$query .= "active DESC ";
	}
} elseif($field == 'position') {
	if($dir == 'asc') {
		$query .= "position ASC ";
	} elseif($dir == 'dsc') {
		$query .= "position DESC ";
	}
} elseif($field == 'name') {
	if($dir == 'asc') {
		$query .= "name ASC ";
	} elseif($dir == 'dsc') {
		$query .= "name DESC ";
	}
} elseif($field == 'is_parent') {
	if($dir == 'asc') {
		$query .= "is_parent ASC ";
	} elseif($dir == 'dsc') {
		$query .= "is_parent DESC ";
	}
} elseif($field == 'parent') {
	if($dir == 'asc') {
		$query .= "parent_cat ASC ";
	} elseif($dir == 'dsc') {
		$query .= "parent_cat DESC ";
	}
} else {
	$query .= "active DESC ";
}
$query .= "LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo "><td>";
	echo $line["position"];
	echo "</td><td>";
	echo $line["name"];
	echo "</td><td>";
	if($line["active"] == '0') { echo "Inactive"; }
	elseif($line["active"] == '1') { echo "Active"; }
	echo "</td><td>";
	if($line["is_parent"] == 1) { echo "Yes"; } else { echo "No"; }
	echo "</td><td>";
	if($line["parent_cat"] == 0) {
		echo "None";
	} else {
		//echo $line["parent_cat"];
		$query2 = "SELECT name FROM product_categories WHERE prod_cat_id='".$line["parent_cat"]."'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo $line2['name'];
		}
		mysql_free_result($result2);
	}
	echo "</td>";
	echo "<FORM name=\"products-manage\" Method=\"POST\" ACTION=\"./products_admin7_edit.php\" class=\"wmsform\">";
	echo "<input type=\"hidden\" name=\"prod_cat_id\" value=\"";
	echo $line["prod_cat_id"];
	echo "\">";
	echo "<td align=\"center\">";
	echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\"> ";
	echo "</td>";
	echo "</form> ";
	echo "<FORM name=\"products-delete\" Method=\"POST\" ACTION=\"./products_admin7.php\" class=\"wmsform\">";
	echo "<input type=\"hidden\" id=\"delete\" name=\"delete\" value=\"1\">";
	echo "<input type=\"hidden\" name=\"prod_cat_id\" value=\"";
	echo $line["prod_cat_id"];
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