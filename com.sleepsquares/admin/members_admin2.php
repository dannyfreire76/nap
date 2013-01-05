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

$page_this = $_REQUEST["page_this"];

$limit = 30;
if($page_this == "") { $page_this = 1; }
$page_next = $page_this + 1;
$page_prev = $page_this - 1;
$record_start = $page_prev * $limit;

$dir = $_REQUEST["dir"];
$field = $_REQUEST['field'];
$member_fname = $_REQUEST['member_fname'];
$member_lname = $_REQUEST['member_lname'];
$member_city = $_REQUEST['member_city'];
$member_state = $_REQUEST['member_state'];
$member_zip = $_REQUEST['member_zip'];
$member_country = $_REQUEST['member_country'];
$member_username = $_REQUEST['member_username'];
$member_email = $_REQUEST['member_email'];
$member_phone = $_REQUEST['member_phone'];


include './includes/wms_nav1.php';

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
<script type="text/javascript" src="/includes/_.jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.order').click(function(){
			this.form.action="members_place_order.php";
			$(this.form).append(_("input",{type:"hidden",name:"order",value:"place order"}));
			this.form.submit();
		});
	});
</script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';

foreach($_REQUEST as $key => $value)
{
	if ( strpos($key, "PHP")===false && strpos($key, "cp") !== 0) {
		$hidden_flds .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';

		if ( $key!="dir" && $key!="field" && $key!="page_this" ) {
			$addtl_flds .= '&'.$key.'='.$value;
		}
	}
}
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Welcome to the <?=$page?> page, where you manage the members of our website.</font></td></tr>
</table>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
	<?=$hidden_flds?>
    <table cellpadding="0" cellspacing="0">
        <tr style="font-weight: bold">
            <td>
                First Name:
            </td>
            <td>
                Last Name:
            </td>
            <td>
                City:
            </td>
            <td>
                State:
            </td>
            <td>
                Zip Code:
            </td>
            <td>
                Country:
            </td>
            <td>
                Username:
            </td>
            <td>
                Email:
            </td>
            <td>
                Phone:
            </td>
        </tr>
        <tr>
            <td>
                <input type="textbox" value="<?=$member_fname?>" name="member_fname"  class="search"/>&#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_lname?>" name="member_lname"  class="search"/>&#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_city?>" name="member_city"  class="search"/>&#160;
            </td>
            <td>
                <select value="<?=$member_state?>" name="member_state" class="search">
                    <option value="">--all--</option>
                    <?php
                        $querySt = "SELECT * FROM states";
                        $resultSt = mysql_query($querySt) or die("Query failed : " . mysql_error());
                        while ($lineSt = mysql_fetch_array($resultSt, MYSQL_ASSOC)) {
                            echo '<option value="'.$lineSt["code"].'"';
							if ( $lineSt["code"]==$member_state ) {
								echo ' selected';
							}
							echo '>'.$lineSt["name"].'</option>';
                        }
                    ?>
                </select>
                &#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_zip?>" name="member_zip" size="7"  class="search"/>&#160;
            </td>
            <td>
                <select value="<?=$member_country?>" name="member_country" class="search">
                    <option value="">--all--</option>
                    <?php
                        $queryCt = "SELECT * FROM countries";
                        $resultCt = mysql_query($queryCt) or die("Query failed : " . mysql_error());
                        while ($lineCt = mysql_fetch_array($resultCt, MYSQL_ASSOC)) {
                            echo '<option value="'.$lineCt["code"].'"';
							if ( $lineCt["code"]==$member_country ) {
								echo ' selected';
							}
							echo '>'.$lineCt["name"].'</option>';
                        }
                    ?>
                </select>
                &#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_username?>" name="member_username" size="7" class="search" />&#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_email?>" name="member_email" size="7" class="search" />&#160;
            </td>
            <td>
                <input type="textbox" value="<?=$member_phone?>" name="member_phone" size="7" class="search" />&#160;
            </td>
		</tr>
		<tr>
            <td colspan="9" align="center">
				<br />
                <input type="submit" name="search" value="Search" />
				&#160;&#160;
                <input type="button" name="clear" value="Clear" onclick="$('.search').each(function(){this.value=''});void(0)" />
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
$labels = array('First Name', 'Last Name', 'Username', 'Email', 'Status', 'Last Login');
$fields = array('first_name', 'last_name', 'username', 'Email', 'status', 'last_login');

getColumnHeaders($url, $page_this, $labels, $fields, '', '', '', '', '', '', '', '', $addtl_flds);
echo '<th></th>';

$line_counter = 0;
$query = "SELECT * FROM members";

if  ( $_REQUEST["search"] ) {
	$query .= " WHERE ";
	$query .= " first_name LIKE '%".$member_fname."%'";
	$query .= " AND last_name LIKE '%".$member_lname."%'";
	$query .= " AND bill_city LIKE '%".$member_city."%'";
	$query .= " AND username LIKE '%".$member_username."%'";
	$query .= " AND email LIKE '%".$member_email."%'";
	if ($member_state != '') {
		$query .= " AND bill_state = '".$member_state."'";
	}
	$query .= " AND bill_zip LIKE '%".$member_zip."%'";
	$query .= " AND bill_phone LIKE '%".$member_phone."%'";
	if ($member_country != '') {
		$query .= " AND bill_country ='".$member_country."'";
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
	$query .= " ORDER BY nickname ASC";
}

$result = mysql_query($query) or die("Query failed : " . mysql_error());
$record_count = mysql_num_rows($result);

$query .=  " LIMIT $record_start,$limit";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$line_counter++;
	$line_this = $line_counter / 2;
	$destination = "members_admin2_edit.php";
	echo "<FORM name=\"members-manage\" Method=\"POST\" ACTION=\"./".$destination."\" class=\"wmsform\">\n";
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
	echo "</td>";
	echo "<td>";
	echo $line["email"];
	echo "</td><td>";
	if($line["status"] == 1) {
		echo "Active";
	} elseif($line["status"] == 0) {
		echo "Inactive";
	}
	echo "</td><td>";
	echo $line["last_login"];
	echo "</td><td align=\"center\">";
	echo "<input type=\"hidden\" name=\"member_id\" value=\"".$line["member_id"]."\">";
	echo "<input type=\"image\" src=\"/images/wms/edit.gif\" id=\"edit\" name=\"edit\" width=\"16\" height=\"16\" alt=\"Edit\">";
	echo "&#160;&#160;<input type=\"button\" value=\"order\" class=\"order\" />";
	echo "</td></tr>\n";
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
