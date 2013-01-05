<?php

include '../includes/main1.php';
include $base_path.'includes/reps.php';

checkRepLogin();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Sales Representative Interface</title>
<?php
include $base_path.'includes/meta1.php';
?>
	<style type="text/css">
		table.report_table, table.report_table td, table.report_table th {
			border: 1px solid #C0C0C0;
			font-size: 12px;
		}

		table.report_table td.override {
			border-bottom: 0px !important;
		}

		table.report_table th {
			background: #000080;
			color: #fff;
		}

		table.report_table td, table.report_table th {
			padding: 3px 6px;
		}

		table.no_borders td {
			border: none !important;
			padding: 0px !important;
		}

		/*
		.report_table tr:hover, .report_table tr.hovered {
			background-color: #EAEAEA;
		}
		*/

		tr.report_totals {
			font-weight: bold;
			color: #000080;
			background-color: #EAEAEA;
		}
	</style>
</head>
<body>

<div align="center">

<?php
include $base_path.'includes/reps_head1.php';
?>


<table border="0">
    <tr><td>&#160;</td></tr>

<?php

//Error Messages
if($error_txt) {
	echo '<tr><td><span class="error">$error_txt</span></td></tr>\n';
	echo "<tr><td>&nbsp;</td></tr>\n";
}

?>
</table>
<span class="style4 two"><?php echo $website_title; ?>: Sales Reports</span>
<br /><br />

<?php include $base_path.'includes/reps_reports_inc.php'; ?>

<br class="clear" />
<?php
include $base_path.'includes/reps_foot1.php';
mysql_close($dbh);
?>

</div>
</body>
</html>