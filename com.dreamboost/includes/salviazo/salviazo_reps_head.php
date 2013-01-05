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
				echo '<a id="'.$nav_form_link.'" href="'.$hlink.'" class="'.$nav_a_class.'">';
				echo $desc.'</a>';
				if ( $hcnt!=mysql_num_rows($resultHMenu) ) {
					echo '&#160;&#160;&#160;|&#160;&#160;&#160;';
				}
			}
		}
	}
    else {
        echo '&#160;';
    }
}
?>


<div class="header">
	<center>
	<table border="0" cellspacing="0" cellpadding="0">
	    <tr>
            <td><a href="<?=$base_url?>index.php"><img src="<?=$current_base?>images/home_head2.jpg" border="0" alt="<?=$website_title?>"></a></td>
        </tr>
    </table>
	</center>
</div>


<div class="header_nav">
<?php

buildMenu();

echo '</div>

<div id="sub_menu">
    <?php buildSubMenu(); ?>
</div>

<div id="container">

<input type="hidden" id="current_base" name="current_base" value="'.$current_base.'" />
<input type="hidden" id="base_path" name="base_path" value="'.$base_path.'" />
<input type="hidden" id="base_url" name="base_url" value="'.$base_url.'" />';

?>

