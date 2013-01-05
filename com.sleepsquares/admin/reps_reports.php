<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/st_and_co1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>MyBWMS @ <?php echo $website_title.": Sales Rep Reports"; ?></title>
	<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
	<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
	<script type="text/javascript" src="<?=$current_base?>includes/jquery.js"></script>
	<script type="text/javascript" src="<?=$current_base?>includes/extend.js"></script>
	<script type="text/javascript" src="<?=$current_base?>includes/interface.js"></script>

	<style type="text/css">
		table.report_table, table.report_table td, table.report_table th {
			border: 1px solid #C0C0C0;
			font-size: 12px;
		}
		
		table.no_borders td {
			border: none !important;
			padding: 0px !important;
		}


	</style>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">

<div id="floating_msg" class="no_display absolute"></div>

<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Sales Reps Reports</font></td></tr>

<tr><td align="left">
<br /><br />

<?php include $base_path.'includes/reps_reports_inc.php'; ?>

</td></tr>

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