<?php
// BME WMS
// Page: Footer Homepage Include
// Path/File: /includes/foot1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007
/**
 *PAGE FOOTER, CLOSES DATABASES...
 */

?>
<!-- foot1... -->
	</div> <!-- close container-->
	<div id="footer-container">
		<table border="0" bgcolor="#fff" cellpadding="0" cellspacing="0" width="980">
			<tr>
				<td class="container">
					<table bgcolor="#ffffff" border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" class="hmenubar" style="text-align: left">
							<?php foreach($pages as $url=>$title):?>
								<?php
								if(substr($url,-1) == '/'){
									$url = substr($url,0,strlen($url) -1);
								}
								?>
								<a class="tan_text" href="<?php echo $url;?>/index.php"><?php echo $title;?></a>
								<img class="hsplitter" src="<?=$current_base?>images/ss/seperator.png" width="2" height="13">
							<?php endforeach;?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<table border="0" width="100%" style="background-color: #33120D;">
			<tbody>
			<tr valign="middle">
				<td style="text-align:left">
					<a class="tan_text" href="<?=$base_url?>about/privacy_security.php">Privacy & Security</a>
					<br /><span class="tan_text">&copy; 2007-2010 -</span>
						<a class="tan_text" href="<?=$base_url?>about/index.php">UDI/Slumberland Snacks</a>
					<br /><span class="tan_text">All Rights Reserved.</span>
				</td>
				<td style="text-align:right">
					<img src="<?=$current_base?>images/quickssl_anim.gif" alt="Transactions secured by Geotrust" title="Transactions secured by Geotrust" />
				</td>
			</tr>
			</tbody>
		</table>
	</div>

</div> <!-- close outer-container -->
<!-- ...foot1 -->
<?php
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
