<?php
// BME WMS
// Page: Products Manager - Edit Product page
// Path/File: /admin/products_admin3_edit.php
// Version: 1.8
// Build: 1808
// Date: 03-25-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}


include './includes/wms_nav1.php';
$manager = "products";
$page = "Products Manager > Edit Products";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if ( $_REQUEST["submit_batch"] ) {
	$queryBatchDel = "DELETE FROM product_batches";
	$resultBatchDel = mysql_query($queryBatchDel) or die("Query failed : " . mysql_error());

	foreach($_POST as $bkey => $bvalue) {
		 if ( strpos($bkey, 'batch_date_')!==false && $bvalue!="" ) {
			$thisBatchID = substr($bkey, 11);
			$thisBatchCreated = $bvalue;
			$thisBatchDesc = $_POST["batch_desc_".$thisBatchID];
			$thisBatchActive = $_POST["batch_active_".$thisBatchID];

			$queryBatchIns = "INSERT INTO product_batches (batch_created, batch_desc, batch_active) VALUES (str_to_date('".$thisBatchCreated."', '%m/%d/%Y'), '".$thisBatchDesc."', '".$thisBatchActive."')";
			$resultBatchIns = mysql_query($queryBatchIns) or die("Query failed : " . mysql_error());
		}
	}

	//Goto Manage Products page
	//header("Location: " . $base_url . "admin/products_admin3.php");
	//exit;
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
<link rel="stylesheet" type="text/css" media="screen" href="<?=$current_base?>includes/date_input.css">

<style type="text/css">
	#batchTable td {
		white-space: nowrap;
	}
</style>

<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.dimensions.min.js"></script>
<script type="text/javascript" src="<?=$current_base?>includes/jquery.date_input.min.js"></script>

<script type="text/javascript">
    $(function() {//on doc ready
        BatchAdmin.init();
    });

    var BatchAdmin = new function() {
        this.init = function() {

            $('input[@name*=batch_date]').each(function(){
				$(this).date_input({ start_of_week: 0 });
            });

			$('#addBatch').click(BatchAdmin.addBatch)
			
			$('#showDeadBatches').click(BatchAdmin.toggleBatchInactive);
        }

		this.addBatch = function() {
			var currentRows = $('.batchRow').size();
			var newID = currentRows * 1 +1;

			var newBatch = '<tr class="batchRow"><td><input type="text" id="batch_date_'+newID+'" name="batch_date_'+newID+'" value="" size="10" />';
			newBatch += '&#160;<span class="smaller">(mm/dd/yyyy)</span></td>';
			newBatch += '<td><input type="text" id="batch_desc_'+newID+'" name="batch_desc_'+newID+'" value="" /></td>';
			newBatch += '<td align="left" nowrap="true"><input type="checkbox" name="batch_active_'+newID+'" id="batch_active_'+newID+'" value="1" checked="true" />&#160;<a style="color:#fff;" href="javascript:void(0)" onClick="$(this).parents(\'tr:first\').remove();">remove&#160;this&#160;row</a></td></tr>';
			$('#batchTable').append( newBatch );

			$('input[@name=batch_date_'+newID+']').each(function(){
				$(this).date_input({ start_of_week: 0 });
            });
		}

		this.toggleBatchInactive = function() {
			if ( $(this).is(':checked') ) {
				$('#inactiveBatchTable').show();
			} else {
				$('#inactiveBatchTable').hide();
			}			
		}
    }
</script>

</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Manage product batches below.
</font></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left">
	<FORM name="batchAdminForm" Method="POST" ACTION="./batch_admin.php" class="wmsform">
	<input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>">
	<fieldset>
		<legend>Please Enter Batch Information</legend>
		<ol>
			<li>
				<table id="batchTable">
					<tr style="color:#fff">
						<th>
							Date
						</th>
						<th>
							Description
						</th>
						<th align="left">
							Active
						</th>

					</tr>
					<?php
						$batch_cnt=0;
						$queryBatch = "SELECT *, DATE_format(batch_created, '%m/%d/%Y') AS batch_created_format FROM product_batches ORDER BY batch_active DESC, batch_created ASC";
						$resultBatch = mysql_query($queryBatch) or die("Query failed : " . mysql_error());
						$showInactive = false;
						$addBatchLink = '<a href="javascript:void(0)" id="addBatch" style="color: #fff">Click to add another batch</a>';
						
						if ( mysql_num_rows($resultBatch) > 0 ) {
							while ($lineBatch = mysql_fetch_array($resultBatch, MYSQL_ASSOC)) {
								$batch_cnt++;

								if ( $lineBatch["batch_active"]==0 && !$showInactive ) {
									echo '</table>';
									echo $addBatchLink;
									echo '<br /><br /><input type="checkbox" name="showDeadBatches" id="showDeadBatches" />&#160;<label for="showDeadBatches" style="width:auto">Show Inactive Batches</label>';
									echo '<table id="inactiveBatchTable" class="no_display">';
									$showInactive = true;
								}

								echo '<tr class="batchRow"><td><input type="text" id="batch_date_'.$batch_cnt.'" name="batch_date_'.$batch_cnt.'" value="'.$lineBatch["batch_created_format"].'" size="10" />';
								echo '&#160;<span class="smaller">(mm/dd/yyyy)</span></td>';
								echo '<td><input type="text" id="batch_desc_'.$batch_cnt.'" name="batch_desc_'.$batch_cnt.'" value="'.$lineBatch["batch_desc"].'" /></td>';
								echo '<td align="left"><input type="checkbox" name="batch_active_'.$batch_cnt.'" id="batch_active_'.$batch_cnt.'" value="1"'; 
								if ( $lineBatch["batch_active"]==1 ) {
									echo ' checked="true" ';
								}
								echo ' /></td></tr>';
							}
						} else {
							echo '<tr class="batchRow"><td><input type="text" id="batch_date_1" name="batch_date_1" value="" size="10" />';
							echo '&#160;<span class="smaller">(mm/dd/yyyy)</span></td>';
							echo '<td><input type="text" id="batch_desc_1" name="batch_desc_1" value="" /></td>';
							echo '<td align="left"><input type="checkbox" name="batch_active_1" id="batch_active_1" checked="true" value="1" /></td>';
							echo '</tr>';
						}
						
						echo '</table>';
						if ( !$showInactive ) {//did we have to show Inactive batches?  If we haven't, we still need to show the add batch link
							echo '<br />'.$addBatchLink;
						}
					?>
			</li>
		</ol>
	</fieldset>
	<input type="submit" name="submit_batch" value="Save Changes" />
	</form>
</td></tr>

</table>

<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>