<?php

if ($_SESSION["rep_id"]) {
	include_once($base_path.'includes/reps_foot1.php');
} else {
?>
	<!-- foot1... -->
	</div> <!-- close mainContent-->
		<br class="clearfloat" />
		<div id="footer">
		<table width="100%" border="0" cellspacing="0" cellpadding="9" style="height:110px;">
			<tr valign="bottom">
				<td class="copy"><img src="<?=$base_url?>images/quickssl_anim.gif" width="115" height="55" border="0" class="fltlft" /><strong>&copy; 2007-2011 UDI/Slumberland Snacks</strong><br />
					All Rights Reserved.<br />
					<a href="<?=$base_url?>about/privacy_security.php">Privacy &amp; Security</a></td>
				<td>&nbsp;</td>
				<td align="right" class="footerNav"><strong><a href="<?=$base_url?>faqs/index.php">FAQs</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=$base_url?>about/index.php">About Us</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?=$base_url?>contact/index.php">Contact Us</a></strong></td>
			</tr>
		</table>
		<!-- end #footer -->
		</div>
	</div> <!-- close container -->
	<!-- ...foot1 -->
<?php
}

if(isset($dbh)){
	if($dbh){
		mysql_close($dbh);
	}
}
if(isset($dbh_master)){
	if($dbh_master){
		mysql_close($dbh_master);
	}
}
if(isset($$thisDBHName)){
	if($$thisDBHName){
		mysql_close($$thisDBHName);
	}
}
?>
