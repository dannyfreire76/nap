<?php
// BME WMS
// Page:Clinical Study Results
// Path/File: /study_results.php
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
			<h3 style="text-align:center;">Sleep Squares Clinical Study Results</h3>
			<div>
<table border="0" cellpadding="5">
<tr valign="top">
<h4 style="text-align:center;"><i>Study Parameters</i></h4>
	<p>
		A randomized, single-blind, crossover clinical study was conducted by BioScreen Clinical Services of Phoenix, Arizona, and sponsored by Slumberland Snacks of Ithaca, New York, to evaluate the efficacy of Sleep Squares as measured by an improvement in overall quality of sleep.
		<br /><br />
		<b>Subject requirements:</b>
			<ul>
				<li>be between the ages of 18 and 65 and in good general health</li>
				<li>score between 8 and 21 on the Insomnia Severity Index</li>
				<li>regularly sleep in a temperature controlled environment (ie, air conditioned bedroom)</li>
				<li>read, understand, and sign an informed consent form</li>
				<li>complete a preliminary medical history form</li>
			</ul>
		<p><b>Conditions for exclusion from study:</b></p>
			<ul>
				<li>had a history of any acute or chronic disease that would interfere with or result in increased risk with study participation</li>
				<li>were on medications or other treatments for any sleep disorder that could not be suspended or discontinued</li>
				<li>had any form of diabetes</li>
				<li>were allergic to soy, peanuts, tree nuts, diary and/or wheat, or had a history of allergic reactions to vitamins or other health supplements</li>
				<li>were pregnant, planning a pregnancy, or were nursing</li>			
			</ul>
	</p>
<h4 style="text-align:center;"><i>Results</i></h4>
	<p>NO adverse events were reported during the study.
		<ul>
			<li><b>Ability to fall asleep -</b> 76% reported less difficulty in falling asleep</li>
			<li><b>Quality of sleep -</b> 79% reported an improvement in satisfaction with sleep pattern</li>
			<li><b>Morning after -</b> 82% reported they felt more rested in the morning</li>
			<li><b>Sleep duration -</b> 68% reported an increase in sleep duration</li>
			<li><b>Waking moments -</b> 82% reported a decrease in the number of times they woke during the night</li>
		</ul>
	</p>	
<h3 style="text-align:center;">Conclusion</h3>
	<p><b>Sleep Squares is a <u>safe and effective supplement</u> to improve the overall quality of sleep.</b>  Subjects taking the suggested amount (1 piece) of Sleep Squares before bedtime reported less difficulty in falling asleep, were more satisfied with their sleep patterns, slept longer, and woke up fewer times during the night, compared with baseline.  Subjects also reported that they felt more rested and energetic in the morning when they took Sleep Squares at night.</p>
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
