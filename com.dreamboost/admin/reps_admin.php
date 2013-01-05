<?php
// BME WMS
// Page: Members Manager Manage Members page
// Path/File: /admin/members_admin2.php
// Version: 1.8
// Build: 1804
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

foreach($_REQUEST as $key => $value)
{
    $$key = $value;

	if ( strpos($key, "PHP")===false && strpos($key, "cp") !== 0) {
		$hidden_flds .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';

		if ( $key!="dir" && $key!="field" && $key!="page_this" ) {
			$addtl_flds .= '&'.$key.'='.$value;
		}
	}
} 

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;



include './includes/wms_nav1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": Reps Manager"; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/extend.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/reps.js"></script>
<style type="text/css">
	label {display: none}
</style>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';

?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Manage Sales Representatives</font></td></tr>
</table>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
	<?=$hidden_flds?>
    <table cellpadding="0" cellspacing="0">
        <tr style="font-weight: bold" valign="top">
            <td>
                First Name: 
            </td>
            <td>
                Last Name: 
            </td>
            <td>
                State(s) Represented: 
            </td>
			<td class="no_display" id="city_label">
                Cities Represented: <input type="hidden" value="<?=$rep_cities ? join("\|",$rep_cities): "" ?>" name="rep_cities_search" id="rep_cities_search" />
            </td>

        </tr>
        <tr valign="top">
            <td>
                <input type="textbox" value="<?=$rep_fname?>" name="rep_fname" />&#160;
            </td>
            <td>
                <input type="textbox" value="<?=$rep_lname?>" name="rep_lname" />&#160;
            </td>
            <td>
                <select name="rep_states[]" id="rep_states" multiple="true" size="4">
                    <?php
                        $querySt = "SELECT * FROM states WHERE country_id='1'";//get US only
                        $resultSt = mysql_query($querySt) or die("Query failed : " . mysql_error());
                        while ($lineSt = mysql_fetch_array($resultSt, MYSQL_ASSOC)) {
                            echo '<option value="'.$lineSt["code"].'"';
							if ( $rep_states && is_array($rep_states) && in_array($lineSt["code"], $rep_states) ) {
								echo ' selected';
							}
							echo '>'.$lineSt["name"].'</option>';
                        }
                    ?>
                </select>
                &#160;
            </td>
            <td id="citiesWrapper">
            </td>
            <td>
                <input type="submit" name="search" value="Search" />
            </td>
        </tr>
    </table>
</form>

<table border="0" width="97%">
<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

echo '<tr><td align="left"><table class="maintable" width="100%" cellspacing="0">';
$labels = array('First Name', 'Last Name', 'Username', 'Status');
$fields = array('first_name', 'last_name', 'username', 'status');

getColumnHeaders($url, $page_this, $labels, $fields, '', '', '', '', '', '', '', '', $addtl_flds);
echo '<th></th>';

$line_counter = 0;
$query = "SELECT * FROM reps";

if  ( $_REQUEST["search"] ) {
	$query .= " WHERE ";
	$query .= " first_name LIKE '%".$rep_fname."%'";
	$query .= " AND last_name LIKE '%".$rep_lname."%'";

    $queryCities = "";
	$queryStates = "";
	if ( is_array($rep_cities) &&  sizeof($rep_cities)>0 ) {
		foreach($rep_cities as $a_city) {
			$cs = split("\,", $a_city);
			if ($queryCities!="") {
				$queryCities .= " OR";
			}
			$queryCities .= " rep_id IN (SELECT rep_id FROM reps_areas WHERE reps_areas.city = '".$cs[0]."' AND reps_areas.state = '".$cs[1]."')";
		}
	}else if ( is_array($rep_states) && sizeof($rep_states)>0 ) {
		foreach($rep_states as $a_state) {
			if ($queryStates!="") {
				$queryStates .= " OR";
			}
			$queryStates .= " rep_id IN (SELECT rep_id FROM reps_areas WHERE reps_areas.state = '".$a_state."')";
		}
	}

	if ( $queryCities!="" || $queryStates!="" ) {
		$query = $query." AND (".$queryCities.$queryStates.") ";
	}
}

if ( $field && $dir ) {
	$query .= " ORDER BY ".$field." ";
		if( $dir == 'asc' ) {
			$query .= " ASC";
		}
		else {
			$query .= " DESC";
		}
}
else {
	$query .= " ORDER BY last_name ASC";
}

$result = mysql_query($query) or die("Query failed : " . mysql_error());
$record_count = mysql_num_rows($result);

$query .=  " LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	echo "<FORM name=\"members-manage\" Method=\"POST\" ACTION=\"./reps_admin_edit.php\" class=\"wmsform\">\n";
	echo "<tr";
	if(is_int($line_this)) { echo " class=\"d\""; }
	echo ">";
	echo "<td>";
	echo $line["first_name"];
	echo "</td>";
	echo "<td>";
	echo $line["last_name"];
	echo "</td>";
	echo "<td>";
	echo $line["username"];
	echo "</td><td>";
	if($line["status"] == 1) {
		echo "Active";
	} elseif($line["status"] == 0) {
		echo "Inactive";
	}
	echo "</td>";
    echo "<td align=\"center\">";
	echo "<input type=\"hidden\" name=\"rep_id\" value=\"";
	echo $line["rep_id"];
	echo "\">";
	echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\"></td></tr>\n";
	echo "</form>\n";
}
mysql_free_result($result);
?>
</table></td></tr>

<?php
pagination_display($url, $page_this, $limit, $record_count, $field, $dir, '', '', '', '', '', '', '', '', '', $addtl_flds);
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