<?php

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';
include './includes/tabler1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

if ( isset($_REQUEST["deleteID"]) ) {
	$query = "DELETE FROM retailer_type WHERE retailer_type_id='".$_REQUEST["deleteID"]."'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	
	if ( mysql_affected_rows()>0 ) {
		echo 'ok';
	}

	exit();
}

include './includes/wms_nav1.php';
$manager = "retailers";
$page = "Retailers Manager > Industries Maintenance";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ( isset($_REQUEST["saveChanges"]) ) {
	foreach($_REQUEST as $n=>$v) {
		if ( strpos($n,'retailerTypeID_')!==false ) {
			$retTypeID = substr(  $n, strlen('retailerTypeID_')  );

			$query = "UPDATE retailer_type SET name='".$v."' WHERE retailer_type_id='".$retTypeID."'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
		} elseif ( $n=='newInds' ) {
			if ( trim($v)!='' ) {
				$newIndsArr = split('\|', $v);
				foreach($newIndsArr as $aNewInd) {
					$query = "INSERT INTO retailer_type (name) VALUES ('".$aNewInd."')";
					$result = mysql_query($query) or die("Query failed : " . mysql_error());
				}
			}
		}
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?=$base_url?>/admin/includes/wmsform.css">
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<script type="text/javascript" src="<?=$base_url?>/includes/jquery/jquery.autogrow-textarea.js"></script>

<script type="text/javascript">
	$(function(){
		$('.deleteBtn').click(function(){
			var $delBtn = $(this);
			var $parentRow = $delBtn.parents('tr:first');
			var $theInput = $('input.retailerTypeID', $parentRow);
			
			if ( confirm('Are you sure you want to delete ' + $theInput.val() + '?') ) {
				var retailer_type_id = $delBtn.attr('retailer_type_id');
				$.post('industries_maintenance.php', {deleteID:retailer_type_id}, function(resp){
					if ( resp.toLowerCase().trim()=='ok' ) {
						$parentRow.remove();
					}
				});
			}
		});

		$('#newInds').autogrow();
	});

	function formatNewInd() {
		$('#newInds').val(  $('#newInds').val().replace(/\n/g, "|")   ); 
		return true;
	}
</script>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<b>Edit Retailer Industries</b>
</td></tr>


<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>

<form name="form1" action="./industries_maintenance.php" method="POST" onSubmit="return formatNewInd()">
<input type="hidden" name="retailer_id" value="<?php echo $retailer_id; ?>">
<tr><td align="left">
	<table class="maintable" width="100%" cellspacing="0">
		<tr>
			<th scope="col" align="right">ID</th>
			<th scope="col" style="padding-left: 10px;">Name</th>
			<th scope="col">&nbsp;</th>
		</tr>

		<?php
		$query = "SELECT retailer_type_id, name FROM retailer_type ORDER BY name;";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		$retTypeCnt=0;
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$retailer_type_id = $line["retailer_type_id"];
			$retailer_type_name = $line["name"];
			echo '<tr><td style="width:36px;" align="right">'.$retailer_type_id.'</td>';
			echo '<td style="padding-left:10px;"><input type="text" class="retailerTypeID" name="retailerTypeID_'.$retailer_type_id.'" value="'.$retailer_type_name.'" style="width:300px" /></td>';
			echo '<td style="text-align:center">';
			echo '<a href="javascript:void(0)"><img class="deleteBtn" retailer_type_id="'.$retailer_type_id.'" src="/images/wms/delete.gif" width="16" height="16" alt="Delete" style="float:none; border: 0px;" /></a>';
			echo "</td>";
			echo '</tr>';
		}
		mysql_free_result($result);
	?>
		<tr>
			<th>&#160;</th>
			<th style="padding-left:10px;" scope="col" colspan="2">Add New Industries Below (press Enter/Return between each)</th>
		</tr>

		<tr>
			<td></td>
			<td style="padding-left:10px;">
				<textarea id="newInds" name="newInds" style="width:300px; height:20px; overflow:hidden;"></textarea>
			</td>
			<td></td>
		</tr>
	</table>
	<center>
		<input type="submit" id="saveChanges" name="saveChanges" value="Save Changes" />&#160;<input type="reset" id="restChanges" value="Reset Form" />
	</center>
	</form>
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