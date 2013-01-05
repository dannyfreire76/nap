<?php 
// BME WMS 
// Page: Footer Homepage Include
// Path/File: /includes/head1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

include_once('main1.php');
include $base_path.'includes/cart1.php';
include $base_path.'includes/retailer1.php';

if ( $_REQUEST['action']=='menu' ) {
    buildMenu();
    exit();
}

if ( $_REQUEST['action']=='subMenu' ) {
    buildSubMenu();
    exit();
}

function buildSubMenu() {
	global $retailer_id;

    if(  $_SESSION["rep_id"] != "" ) {
        echo '<span class="error3" id="welcome">Welcome '.$_SESSION["rep_info"]["first_name"].'!</span>&#160;&#160;&#160;';

        if(  $retailer_id != "" ) {
			echo '<span class="error3" id="welcome">Active Retailer: '.$_SESSION["retailer_name"].'</span>&#160;&#160;';
            buildRetailerLinks();
        }
    }
}

function buildMenu(){
    global $base_path;
    global $base_url;
    global $website_title;
	global $current_base;
	global $retailer_id;

	if ($_SESSION["rep_id"]) {
	$pages = array(
		"reps_retailers" => "Retailers",
		"reps_reports" => "Sales Reports",
		"store" => "Store",
        "#rep_logout" => "Logout"
		);

		foreach($pages as $nav=>$desc)
		{
	        if(  $retailer_id != "" || $nav!='store' ) {
				$nav_a_class = '';
				$flink = 'javascript: void(0)';
				$nav_form_link = '';

				if ( strpos($nav,'#')===false ) {
					$flink = $base_url.$nav.'/index.php';
					if ( strpos($_SERVER["SCRIPT_NAME"], $nav) ) {
						$nav_a_class = ' on';
					}
				}
				else {
					//$nav_a_class = 'form_link';
					$nav_form_link = substr($nav, 1);
				}
				echo '<div class="nav_div left hand">';	
				echo '<a id="'.$nav_form_link.'" class="'.$nav_a_class.'" href="'.$flink.'">'.$desc.'</a>';
				echo '<img class="cloud no_display" src="'.$current_base.'images/stars.gif" />';
				echo '</div>';
			}
		}
	}
    else {
        echo '&#160;';
    }
}
?>
<div id="container">
	<div id="header">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="200" align="left" valign="top" id="tagline"><img src="<?=$base_url?>images/logo/dreamboost_tagline-160.png" width="160" height="100" /></td>
				<td width="420" valign="top" id="logo"><img src="<?=$base_url?>images/logo/dreamboost_logotag-400.png" width="400" height="95" /></td>
				<td width="350" align="right" valign="top">
					<?php buildMenu(); ?>
				</td>
			</tr>
		</table>
	</div>

	<?php buildSubMenu(); ?>
	
	<div id="mainContent">
<!-- ...head2 -->

<?php
echo '
<input type="hidden" id="current_base" name="current_base" value="'.$current_base.'" />
<input type="hidden" id="base_path" name="base_path" value="'.$base_path.'" />
<input type="hidden" id="base_url" name="base_url" value="'.$base_url.'" />';

?>

