<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include '../includes/wc1.php';

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
<title>MyBWMS @ <?php echo $website_title.": Edit Sales Rep"; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>admin/includes/wmsform.css">
<script type="text/javascript" src="<?=$current_base?>includes/jquery.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/extend.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/interface.js"></script>

<script type="text/javascript">
        $(function() {//on doc ready
            $('#loading').html('All paid orders flagged as commissions paid.');
        });
</script>

</head>
<body>
<div align="center">
<?php
include './includes/head_admin3.php';

	if ( isset($_REQUEST['recalcNow']) ) {
?>
		<font size="2"><br /><div id="loading">Please wait... <img src="/images/small_loading.gif" /></div><br /></font>
	<?php
		$queryRep = "UPDATE wholesale_receipts SET comm_paid = NOW() WHERE comm_paid=0 and complete='1' AND funds_received!=0;";
		$resultRep = mysql_query($queryRep) or die("Query failed : " . mysql_error());
	}

echo '
	<br />
	<form method="post">
		<input type="submit" name="recalcNow" value="Update Unpaid Commissions Now" onClick="return confirm(\'Are you sure?\')" />
	</form>
	<br />';	

include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>

</div>

</body>
</html>