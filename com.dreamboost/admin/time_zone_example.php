<?
	//*******************************************************************************
	// To see the action of this script, 
	// http://localhost/<virtual directory>/example.php?tzone=5.30&cdate=12/31/2003
	//*******************************************************************************
	include("./includes/time_zone/time_zone.class.php");
	$GetTime = new Time();
	echo "Current System Time is <b>".date("d - F - Y H:i:s")."</b> and its Time Zone is <b>".date("T")."</b>";
	echo "<BR>".str_repeat("-",100)."<BR>";
	echo "Current GMT Time is <b>".$GetTime->GMTTime($HTTP_GET_VARS['tzone'])."</b>";
	echo "<BR>".str_repeat("-",100)."<BR>";
	echo "The following table lists time at various time zones:<br><br>";
	echo "<table width=50% align=left border=1>";
	echo "<tr><td valign=top align=center>Sl.No.</td><td valign=top align=center>Time Zone</td><td valign=top align=center>Current Date & Time</td></tr>";
	$nTZones = array("-12","-11","-10","-9","-8","-7","-6","-5","-4","-3","-2","-1","0","1","2","3","3.30","4","4.30","5","5.30","5.45","6","6.30","7","8","9","9.30","10","11","12","13");
	if ($HTTP_GET_VARS['cdate'] != "")
	{
		$sCurDate = strtotime($HTTP_GET_VARS['cdate']);
	}
	else
	{
		$sCurDate = time();
	}
	for($i=0;$i<sizeof($nTZones);$i++)
	{
		echo "<tr><td valign=top align=center>".($i+1)."</td><td valign=top align=center>".$nTZones[$i]."</td><td valign=top align=center>".$GetTime->ConvertTime($sCurDate,$HTTP_GET_VARS['tzone'],$nTZones[$i])."</td></tr>";
	}
	echo "</table>";
?>