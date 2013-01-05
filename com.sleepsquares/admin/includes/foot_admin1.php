<?php 
// BME WMS 
// Page: Admin Footer Links Include
// Path/File: /includes/foot_admin1.php
// Version: 1.8
// Build: 1802
// Date: 01-21-2007


function footer_admin($ltime) {
	global $font;
	global $fontcolor;
	?>
<table border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td align="left" bgcolor="#6699CC"><font color="#FFFFFF" size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td><td align="center" bgcolor="#6699CC"><font color="#FFFFFF" size="2">Copyright &copy; 2004-2007 MyBWMS All Rights Reserved.</font></td><td align="right" bgcolor="#6699CC"><font color="#FFFFFF" size="2">Load Time: <?php echo $ltime; ?></font></td></tr>
</table>
<?php
}
?>