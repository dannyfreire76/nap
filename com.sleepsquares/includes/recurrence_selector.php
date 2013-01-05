<?php

include "../includes/common.php";
include "../includes/db.class.php";

function GetSchedule($receipt_id, $recurring, $after, $every){
    if($receipt_id && $recurring && $after && $every){
	$rsSchedule = $db->GetRecord("SELECT * FROM schedules WHERE receipt_id=$receipt_id LIMIT 1;");
	if(!$rsSchedule){
	    $start = date("Y-m-d H:i:s");
	    $after=explode(',',$after);
	    $day=$after[0];
	    $month=$after[1];
	    $year=$after[2];
	    $after=implode(",",$after);
	    $every=explode(',',$every);
	    $days=$every[0];
	    $months=$every[1];
	    $years=$every[2];
	    $every=implode(',',$every);
	    $querySchedule="INSERT INTO schedules SET
	    `created`='$start',
	    `receipt_id`=$receipt_id,
	    `active`=1,
	    `after`='$after',
	    `every`='$every',
	    `delivery0`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 0*$days+$day DAY),INTERVAL 0*$months+$month MONTH),INTERVAL 0*$years+$year YEAR),
	    `delivery1`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 1*$days+$day DAY),INTERVAL 1*$months+$month MONTH),INTERVAL 1*$years+$year YEAR),
	    `delivery2`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 2*$days+$day DAY),INTERVAL 2*$months+$month MONTH),INTERVAL 2*$years+$year YEAR),
	    `delivery3`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 3*$days+$day DAY),INTERVAL 3*$months+$month MONTH),INTERVAL 3*$years+$year YEAR),
	    `delivery4`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 4*$days+$day DAY),INTERVAL 4*$months+$month MONTH),INTERVAL 4*$years+$year YEAR),
	    `delivery5`=DATE_ADD(DATE_ADD(DATE_ADD(created,INTERVAL 5*$days+$day DAY),INTERVAL 5*$months+$month MONTH),INTERVAL 5*$years+$year YEAR);";
	    $db->Execute($querySchedule,true);
	    $rsSchedule = $db->GetRecord("SELECT * FROM schedules WHERE receipt_id=$receipt_id LIMIT 1;");
	};
	return $rsSchedule;
    }else{
	return false;
    }
}

$receipt_id=$_REQUEST['id'];
$recurring=$_REQUEST["recurring"];
$after = $_POST["after"];
$every = $_POST["every"];

$schedule=GetSchedule($receipt_id,$recurring,$after,$every);

if($schedule){
    ?><select name="schedule"><?php
    for($i=0;$i<6;$i++){
	?><option value="<?=$schedule["delivery".$i]?>"><?=$schedule["delivery".$i]?></option><?php
    };
    ?></select><?php
}
?>
<!--<script type="text/javascript" src="../includes/jquery.js"></script>-->
<script type="text/javascript" src="../includes/_.jquery.js"></script>
<script type="text/javascript" src="../includes/_.date.js"></script>
<script type="text/javascript">
    /* Convenience functions for date components */
    Date.prototype.w=Date.prototype.getDay;
    Date.prototype.d=Date.prototype.getDate;
    Date.prototype.m=Date.prototype.getMonth;
    Date.prototype.y=Date.prototype.getFullYear;
    Date.prototype.mdy=function(){return this._("m/d/yyyy")};
    Date.prototype.ymd=function(){return this._("yyyy-mm-dd hh:ii:ss")};
</script>
<script type="text/javascript">
(function($) {
    $.fn.recurring = function() {
	var X=_("input",{name:"after",type:"hidden",value:"14,0,0"});
	X+= _("select",{name:"every"},_("option",{value:["30,0,0","60,0,0"]},["30 days","60 days"]));
	$(this).after(X);
        return this;
    }
    return $;
})(jQuery);
$(document).ready(function(){
    $('.recurring').recurring();
})
</script>
    <label for="recurring" class="recurring">
	    <input type="checkbox" name="recurring" id="recurring" /> Repeat every
    </label>
