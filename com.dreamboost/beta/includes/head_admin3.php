<?php 
// BME WMS 
// Page: Header Admin Include
// Path/File: /includes/head_admin3.php
// Version: 1.1
// Build: 1104
// Date: 01-12-2007
?>
<table border="0" cellspacing="0" cellpadding="3" width="100%">
<tr><td bgcolor="#6699CC" valign="middle" align="left" NOWRAP><font face="Arial" size="5" color="#FFFFFF"><b>&nbsp;phpWMS</b></font> <font face="Arial" size="4" color="#003366">@ <?php echo $website_title.": "; ?></font></td>
<?php
echo "<td bgcolor=\"#6699CC\" align=\"left\" valign=\"middle\">";
wms_manager_nav1_new($manager, 1);
echo "</td>";
echo "<td bgcolor=\"#6699CC\" align=\"left\" valign=\"middle\">";
wms_page_nav1($manager, $page);
echo "</td>";
?>
<td bgcolor="#6699CC" align="right" width="100%">&nbsp;</td>
<td bgcolor="#6699CC" align="right" valign="middle">
<a href="./index2.php"><img src="../images/wms/home.gif" border="0" width="16" height="16" alt="Home"></a>&nbsp;
<img src="../images/wms/help.gif" border="0" width="16" height="16" alt="Help">&nbsp;
<?php
bug_report($page);
?>
&nbsp;<a href="./index.php"><img src="../images/wms/logout.gif" border="0" width="16" height="16" alt="Logout"></a>&nbsp;</td>
</tr>
</table>