<?php
// BME WMS
// Page: Import Leads From Site page
// Path/File: /admin/leads_admin8.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

ini_set('max_execution_time', '10000');

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$submit = $_POST["submit"];
$begin_num = $_POST["begin_num"];
$end_num = $_POST["end_num"];

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$this_user_id = $_COOKIE["wms_user"];

include './includes/wms_nav1.php';
$manager = "leads";
$page = "Leads Manager > Import Leads";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	$leads_created = 0;
	$leads_not_created = 0;
	require_once ("./includes/phpscraper/class_scraper.php");
	
	for($i=$begin_num;$i<=$end_num;$i++) {
		// Get html --------
		$o_sc = new scraper();
		$s_url = 'http://www.herbal-shops.com/modify.php?editid='.$i;
		//$s_url = 'http://www.dream-boost.com/scraper/'.$i.'.html';
		$s_user_agent = 'Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.0.1) Gecko/20020921 Netscape/7.0';
		$s_html = $o_sc->browse($s_url, $s_user_agent);
		
		if($s_html != "") {
			// Delimit start and end of patterns
			$s_start_pattern = "Edit Entry:";
			$s_end_pattern = "<input type='submit'";
			
			// Show info
			//echo $s_html;
			
			$a_result = $o_sc->extract2($s_html, $s_start_pattern, $s_end_pattern);
			//print_r ($a_result);
			$b_result = $o_sc->dup_check($a_result);
			if($b_result > 0) {
				$c_result = $o_sc->leads_create($a_result);
				
				if($c_result > 0) {
					//echo "Lead created\n";
					$leads_created = $leads_created + 1;
				} else {
					//echo "Lead not created\n";
					$leads_not_created = $leads_not_created + 1;
				}
			} else {
				$leads_not_created = $leads_not_created + 1;
			}
		} else {
			$leads_not_created = $leads_not_created + 1;
		}
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

<tr><td align="left"><font size="2">Use this page to process leads from the herbal-shops.com website.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form action="./leads_admin8.php" method="POST">
<tr><td align="left"><font size="2"><b>Beginning Number: </b></font><input type="text" id="begin_num" name="begin_num" size="7" maxlegth="7" value="<?php echo $begin_num; ?>"></td></tr>
<tr><td align="left"><font size="2"><b>Ending Number: </b></font><input type="text" id="end_num" name="end_num" size="7" maxlegth="7" value="<?php echo $end_num; ?>"></td></tr>
<tr><td align="left"><input type="submit" name="submit" value="Import Leads"></td></tr>
</form>

<tr><td>&nbsp;</td></tr>

<?php
if ($leads_created != "" || $leads_not_created != "") {
?>	
	<tr><td align="left"><font size="2">Leads Created: <b><?php echo $leads_created; ?></b></font></td></tr>
	<tr><td align="left"><font size="2">Leads Not Created: <b><?php echo $leads_not_created; ?></b></font></td></tr>
<?php
}
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