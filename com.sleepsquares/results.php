<?php
// BME WMS
// Page:Clinical Study Results
// Path/File: /c_study.php
// Version: 1.8
// Build: 1801
// Date: 02-02-2012

header('Content-type: text/html; charset=utf-8');
include './includes/main1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Clinical Study Results</title>
<?php include './includes/meta1.php'; ?>
</head>
<body>

<?php
include './includes/head1.php';
?>	

	<div class="boxContent">
			<h1 style="text-align:center;">Clinical Study Results</h1>
			<div style="text-align: center;">
<table border="0" cellpadding="5">
<tr valign="top">

	
		<ul style="list-style: none;">
			<div class="boxContent3"><li><h2><b>82% felt more rested in the morning</h2></li></div>
			<div class="boxContent3"><li><h2>82% had a reduction in number of times they awoke</h2></li></div>
			<div class="boxContent3"><li><h2>79% had improved quality of sleep</h2></li></div>
			<div class="boxContent3"><li><h2>76% fell asleep faster and easier</h2></li></div>
			<div class="boxContent3"><li><h2>68% slept longer and more soundly</b></h2></li></div>
		
		<div class="boxContent" style="text-align:center"><li><h2>NO adverse events were reported during the study.</h2></li></div>
		<div class="boxContent2" style="text-align:center"><li><h2><font color="#FFFFFF"><a href="../study_results.php">Click here for the complete study.</a></font></h2></li></div>	
		</ul>
<!--<h3 style="text-align:center;">Conclusion</h3>
	<p><b>Sleep Squares is a <u>safe and effective supplement</u> to improve the overall quality of sleep.</b>  Subjects taking the suggested amount (1 piece) of Sleep Squares before bedtime reported less difficulty in falling asleep, were more satisfied with their sleep patterns, slept longer, and woke up fewer times during the night, compared with baseline.  Subjects also reported that they felt more rested and energetic in the morning when they took Sleep Squares at night.</p> -->
</tr>
</table>
			</div>
		</div>
	</div>
<div align=center>
<?php
include './includes/foot1.php';
//mysql_close($dbh);
?>

</body>
</html>