<?php

if ($_SESSION["rep_id"]) {
	include_once($base_path.'includes/reps_foot1.php');
} else {
?>
	</div><!--container-->

	<div class="footer_nav">

	<?php
	$fcnt = 0;
	$queryFMenu = "SELECT * FROM page WHERE status='1' AND cat='F' ORDER BY seq";
	$resultFMenu = mysql_query($queryFMenu) or die("Query failed : " . mysql_error());
	while ($lineFMenu = mysql_fetch_array($resultFMenu, MYSQL_ASSOC)) {
		$navf = $lineFMenu["url"];
		$descf = $lineFMenu["page_name"];

		$fcnt++;
		$flink = 'javascript: void(0)';

		$nav_form_link = '';
		$nav_a_class = '';
		if ( strpos($navf,'#')===false ) {
			$flink = $base_url.$navf.'/index.php';
			if ( strpos($_SERVER["SCRIPT_NAME"], $navf) ) {
				$nav_a_class = ' on';
			}
		}
		else {
			$nav_a_class = 'form_link';
			$nav_form_link = substr($navf, 1);
		}
		echo '<a href="'.$flink.'" class="'.$nav_a_class.'" id="'.$nav_form_link.'">';
		echo $descf.'</a>';
		if ( $fcnt!=mysql_num_rows($resultFMenu) ) {
			echo ' | ';
		}

		if ( $nav_form_link!='' ) {
			echo '
				<div id="'.$nav_form_link.'_form" class="no_display absolute window_top nav_form_wrapper">
					  <div class="window_top_content">&#160;</div>
					  <div class="window_content nav_form">';
			require_once( $base_path.$nav_form_link.'.php' );
			echo	'</div>
				</div>';
		}
	}
	?>
	</div>

	<div class="footer">
		<center>
		<div class="footer_links">
			<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td align="center" width="40%">
				<?php
					$z_cnt = 0;
					$queryZMenu = "SELECT * FROM page WHERE status='1' AND url NOT LIKE '%#%' ORDER BY seq";
					$resultZMenu = mysql_query($queryZMenu) or die("Query failed : " . mysql_error());

					while ($lineZMenu = mysql_fetch_array($resultZMenu, MYSQL_ASSOC)) {
						$z_cnt++;
						if ( $z_cnt <= (mysql_num_rows($resultZMenu)/2) ) {
							echo '<a href="'.$current_base.$lineZMenu["url"].'">'.str_replace(" ", "&#160;", $lineZMenu["page_name"]).'</a>';
							if ( $z_cnt < (floor(mysql_num_rows($resultZMenu)/2)) ) {
								echo ' | ';
							}
						}
					}
				?>
				</td>
				<td align="center" width="20%">
					<img src="<?=$current_base?>images/quickssl_anim.gif" class="geo" border="0" alt="Transactions Secured By GeoTrust" title="Transactions Secured By GeoTrust" />
				</td>
				<td align="center" width="40%">
				<?php
					$z_cnt2 = 0;
					$queryZMenu = "SELECT * FROM page WHERE status='1' AND url NOT LIKE '%#%' ORDER BY seq";
					$resultZMenu = mysql_query($queryZMenu) or die("Query failed : " . mysql_error());

					while ($lineZMenu = mysql_fetch_array($resultZMenu, MYSQL_ASSOC)) {
						$z_cnt2++;
						if ( $z_cnt2 > (mysql_num_rows($resultZMenu)/2) ) {
							echo '<a href="'.$current_base.$lineZMenu["url"].'">'.str_replace(" ", "&#160;", $lineZMenu["page_name"]).'</a>';
							if ( $z_cnt2 < (mysql_num_rows($resultZMenu)) ) {
								echo ' | ';
							}
						}
					}
				?>
				</td>
			</tr>
			</table>
			<br />
			<a href="<?=$current_base?>about/privacy_security.php">Privacy & Security</a> &nbsp; Copyright &copy; 2004-<?php echo date("Y") ?> <a href="<?=$current_base?>about/index.php">NAP & Associates, LLC.</a> All Rights Reserved.
		</div>
		</center>
	</div>

	<div id="special" class="no_display absolute" style="display:none;">
		<a href="javascript:void(0)">
			<div class="left hand" id="ad"><img class="right hand close" alt="click to close" title="click to close" src="/images/close.gif" border="0" /></div>
		</a>
	</div>
<?php
    }
?>
