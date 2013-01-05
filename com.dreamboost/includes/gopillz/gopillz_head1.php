<?php 
// BME WMS 
// Page: Header Home Include
// Path/File: /includes/head_home1.php
// Version: 1.1
// Build: 1103
// Date: 09-26-2006

include_once('main1.php');
include_once($base_path.'includes/cart1.php');

function buildSubMenu() {
    global $retailer_id;
	global $member_id;
    global $base_path;
    global $base_url;
    global $website_title;
	global $current_base;
	global $user_id;

    if($retailer_id != "") {
        echo '<!--<a href="'.$base_url.'/wc/my/index2.php">My '.$website_title.'</a> | --><a href="'.$base_url.'wc/my/order_history.php">Order History</a> | <a href="'.$base_url.'wc/my/update_retailer.php">Store Info</a>';        
        echo '&#160;|&#160';
        $cart_totals = get_wc_cart_total($retailer_id);

		if ( $cart_totals[1]>0 ) {
            echo '<a href="'.$base_url.'wc/cart.php"><image id="cartImg" src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;'.$cart_totals[1].' item'.($cart_totals[1]>1 ? "s" : "").' In Your Cart: ';
            echo "$";
            echo $cart_totals[0];
            echo '</a>';
		}
        else {
			echo '<image id="cartImg" src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.';
		}
    }
	else {
		if($member_id != "") {//member logged in
			echo '<span class="error3">Welcome '.$_SESSION["member_name"].'!</span>&#160;&#160;&#160;';
			echo '|<a href="'.$base_url.'customer/update_customer.php">Your Profile</a>';
            echo '|<a href="'.$base_url.'customer/customer_history.php">Order History</a>';
            echo '|<a href="javascript:void(0);" id="logout">Logout</a>';
		}
		else
			{
			echo '<a href="javascript:void(0)" id="login" class="form_link">Returning customer sign-in</a>';

			echo '
				<div id="login_form" class="no_display absolute nav_form_wrapper header_form">
					<div class="window_content nav_form">';
						require_once( $base_path.'login.php' );		
			echo '	</div>
					<div class="window_bottom"><div class="window_bottom_end"></div></div>
				</div>';
		}
        $cart_totals = get_cart_total($user_id);

		echo '|&#160;&#160;&#160;';
        
		if ( $cart_totals[1]>0 ) {
			echo '<a href="'.$base_url.'store/cart.php"><image src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />'.$cart_totals[1].' item'.($cart_totals[1]>1 ? "s" : "").' In Your Cart: ';
			echo "$";
			echo number_format($cart_totals[0], 2);
			echo '</a>';
		}
		else {
			echo '<image src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.';
		}
		
	}
}

if ( $_REQUEST['action']=='submenu' ) {
    buildSubMenu();
    exit();
}
?>
<div id="header">
	<center>
	<table border="0" cellspacing="0" cellpadding="0">
	    <tr>
            <td><a href="<?=$base_url?>index.php"><img src="<?=$current_base?>images/logo.png" border="0" id="hdr_logo" alt="<?=$website_title?>"></a></td>
            <td>
            <?php
                $queryL = "SELECT * FROM page WHERE status='1' AND cat='L' ORDER BY seq";
                $resultL = mysql_query($queryL) or die("Query failed : " . mysql_error());
                while ($rowL = mysql_fetch_array($resultL, MYSQL_ASSOC)) {
                    echo '<a href="'.$base_url.$rowL["url"].'/index.php"><img src="'.$current_base.'images/'.$rowL["url"].'.jpg" class="logo_link" border="0" alt="'.$rowL["page_name"].'"></a>';
                    echo '<br />';
                }
            ?>
            </td>
        </tr>
    </table>
	</center>

<div class="left_hdr">&#160;</div>
<div class="right_hdr">&#160;</div>

<div class="header_nav">
<?php

$hcnt = 0;
$queryHMenu = "SELECT * FROM page WHERE status='1' AND cat='H' ORDER BY seq";
$resultHMenu = mysql_query($queryHMenu) or die("Query failed : " . mysql_error());
while ($lineHMenu = mysql_fetch_array($resultHMenu, MYSQL_ASSOC)) {
    $nav = $lineHMenu["url"];
    $desc = $lineHMenu["page_name"];

    $hcnt++;
    $hlink = 'javascript: void(0)';

    $nav_a_class = '';
    if ( $nav != '' ) {
        $hlink = $base_url.$nav.'/index.php';
        if ( strpos($_SERVER["SCRIPT_NAME"], $nav) ) {
            $nav_a_class = ' on';
        }
    }
	echo '<a href="'.$hlink.'" class="'.$nav_a_class.'">';
	echo strToUpper($desc).'</a>';
    if ( $hcnt!=mysql_num_rows($resultHMenu) ) {
        echo '&#160;&#160;&#160;|&#160;&#160;&#160;';
    }

}

echo '</div>

</div><!-- end header-->


<input type="hidden" id="current_base" name="current_base" value="'.$current_base.'" />
<input type="hidden" id="base_path" name="base_path" value="'.$base_path.'" />

<div id="sub_menu">';
if ( !strpos($_SERVER["SCRIPT_NAME"], "confirm.php") ) {
    buildSubMenu();
}
echo '</div>

<div id="container">
';

?>

