<?php
// BME WMS
// Page: Download Leads From Sites page
// Path/File: /admin/leads_admin5.php
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

$submit = $_POST["submit"];
$url = $_POST["url"];

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Download Leads From Sites";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	$entered_by = $this_user_id;
	$status = "1";
	
	$error_txt = "";
	if($url == "" || $url == "http://") {
		$error_txt .= "Error, you did not enter a URL. Please enter a URL.<br>\n";
	}

	if($error_txt == "") {
		//Check if already entered
		$query = "SELECT status, url FROM leads_sites WHERE url='".$url."'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($line["url"] != "" && $line["status"] == 1) {
				$error_txt .= "Error, this URL has already been added. It is waiting to be imported.<br>\n";
			} elseif($line["url"] != "" && $line["status"] == 2) {
				$error_txt .= "Error, this URL has already been added. It is in the process of being imported.<br>\n";
			} elseif($line["url"] != "" && $line["status"] == 3) {
				$error_txt .= "Error, this URL has already been added. It has been imported.<br>\n";
			}
		}
		mysql_free_result($result);
	}

	if($error_txt == "") {
		//Insert new site
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO leads_sites SET created='$now', entered_by='$entered_by', status='$status', url='$url'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
	}
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
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Enter a site from which to download leads.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<tr><td align="left"><font size="2"><b>Add New Site to Download Leads From</b></font></td></tr>
<form action="./leads_admin5.php" method="POST">
<tr><td align="left"><font size="2"><b>URL: </b></font><input type="text" id="url" name="url" size="30" maxlegth="255" value="http://"> <input type="submit" name="submit" value="Add Site"></td></tr>
</form>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0">
<tr><td><font face="Arial" size="+1"><b>Status</b></font></td><td><font face="Arial" size="+1"><b>URL</b></font></td><td><font face="Arial" size="+1"><b>Entered By</b></font></td></tr>

<?php
	$query = "SELECT leads_sites_id, status, url, entered_by FROM leads_sites ORDER BY created DESC";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo "<tr><td><font face=\"Arial\" size=\"+1\">";
		if($line["status"] == 1) {
			echo "Added";
		} elseif($line["status"] == 2) {
			echo "Processing";
		} elseif($line["status"] == 3) {
			echo "Imported";
		}
		echo "</font></td><td><font face=\"Arial\" size=\"+1\">";
		echo "<a href=\"";
		echo $line["url"] . "\" TARGET=\"_BLANK\">";
		echo $line["url"];
		echo "</a></font></td><td><font face=\"Arial\" size=\"+1\">";
		
		$query2 = "SELECT first_name, last_name FROM wms_users WHERE user_id='".$line["entered_by"]."'";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo $line2["first_name"];
			echo " ";
			echo $line2["last_name"];
		}
		mysql_free_result($result2);
		echo "</font></td>";
		if($line["leads_sites_id"] == 1 && $this_user_id == 1) {
			echo "<form action=\"./leads_admin8.php\" method=\"POST\">";
			echo "<td><input type=\"submit\" name=\"import\" value=\"Import Leads\"></td>";
			echo "</form";
		} else {
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>\n";
	}
	mysql_free_result($result);
?>
</table></td></tr>

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