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
		echo '<div id="sidebar">';

        echo '<span class="error3" id="welcome">Welcome '.$_SESSION["rep_info"]["first_name"].'!</span><br />';

        if(  $retailer_id != "" ) {
			echo '<br /><span class="error3" id="welcome">Active Retailer: '.$_SESSION["retailer_name"].'</span><br /><br />';
            buildRetailerLinks();
        }

		echo '</div>';
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

		echo '<div id="navMenu">
				<ul>';

		foreach($pages as $nav=>$desc)
		{
	        if(  $retailer_id != "" || $nav!='store' ) {
				$hcnt++;
				$hlink = 'javascript: void(0)';

				$nav_a_class = '';
				if ( strpos($nav,'#')===false ) {
					$hlink = $base_url.$nav.'/index.php';
					if ( strpos($_SERVER["SCRIPT_NAME"], $nav) ) {
						$nav_a_class = ' on';
					}
				} else {
					//$nav_a_class = 'form_link';
					$nav_form_link = substr($nav, 1);
				}

				echo '<li><a id="'.$nav_form_link.'" href="'.$hlink.'" class="'.$nav_a_class.'">'.$desc.'</a></li>';

			}
		}

		echo '</div></ul>';
	}
    else {
        echo '&#160;';
    }
}
?>

<!-- head2... -->
<script type="text/javascript" src="<?=$current_base?>includes/extend.js"></script>

<script type="text/javascript">
	$(function() {//on doc ready
		$('#rep_logout').unbind().bind('click', function() {
				var post_url = $('#current_base').val()+'reps/index.php';
				$.post(post_url, { action:'logout' }, function(resp){
					//$('#header').ScrollTo(400);
					var submenu_url = $('#current_base').val()+'includes/reps_head1.php?action=menu';
					$('#navMenu').load( submenu_url, function() {
						window.location.href=$('#current_base').val()+'reps/';
					})
				})
			}
		);
	});
</script>


<script type="text/javascript">
	$('body').css('background-color','white');
</script>
<div id="container">
	<div id="header">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="300" id="logo"><img src="<?=$base_url?>images/sleepsquares_logo-270.png" width="270" height="123" /></td>
				<td width="320" id="tagline"><img src="<?=$base_url?>images/sleepsquares_tagline-300.png" width="300" height="75" /></td>
				<td width="350" align="right" valign="top">
					<?php buildMenu(); ?>
				</td>
			</tr>
		</table>
		<div id="spcloffer"></div>
	</div>
	<!-- InstanceBeginEditable name="pageBody" -->

	<?php buildSubMenu(); ?>
	
	<div id="mainContent">
<!-- ...head2 -->

<?php


echo '
<input type="hidden" id="current_base" name="current_base" value="'.$current_base.'" />
<input type="hidden" id="base_path" name="base_path" value="'.$base_path.'" />
<input type="hidden" id="base_url" name="base_url" value="'.$base_url.'" />
';

?>

