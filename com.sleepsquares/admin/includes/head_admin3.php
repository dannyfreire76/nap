<?php
include_once('../includes/main1.php');
?>

<table border="0" cellspacing="0" cellpadding="3" width="100%">
<tr>
<?php
echo '<td align="left" valign="middle" class="mybwmshead" nowrap="true" id="site_load" style="color: #fff">';
create_nav_dropdowns();
echo "</td>";
?>
<td align="right" width="100%" class="mybwmshead">&nbsp;</td>
<td align="right" valign="middle" class="mybwmshead"><a href="./index2.php"><img src="../images/wms/home.gif" border="0" width="16" height="16" alt="Home"></a></td>
<td align="right" valign="middle" class="mybwmshead"><img src="../images/wms/help.gif" border="0" width="16" height="16" alt="Help" onclick="location='view-source:'"></td>
<td align="right" valign="middle" class="mybwmshead"><a href="./index.php?logout=1"><img src="../images/wms/logout.gif" border="0" width="16" height="16" alt="Logout"></a></td>
</tr>
</table>
<?php
echo '
<input type="hidden" id="current_base" name="current_base" value="'.$current_base.'" />
<input type="hidden" id="base_path" name="base_path" value="'.$base_path.'" />
<input type="hidden" id="base_url" name="base_url" value="'.$base_url.'" />';
?>
