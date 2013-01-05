<?php 
include_once $base_path.'includes/cart1.php';

function buildRetailerLinks() {
	global $retailer_id;
	global $base_url;
	global $current_base;

    echo '<a href="'.str_replace('http:', 'https:', $base_url).'wc/my/order_history.php">Order History</a> | <a href="'.$base_url.'wc/my/update_retailer.php">Store Info</a>';        
    echo '|';
    $cart_totals = get_wc_cart_total($retailer_id);

    if ( $cart_totals[1]>0 ) {
        echo '<a href="'.$base_url.'wc/cart.php"><image src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;'.$cart_totals[1].' item'.($cart_totals[1]>1 ? "s" : "").' In Your Cart: ';
        echo "$";
        echo $cart_totals[0];
        echo '</a>';
    }
    else {
        echo '<image id="cartImg" src="'.$current_base.'images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.';
    }
}

function buildStore($line, $row_class) {
	echo '<tr class="'.$row_class.'"><td>';
	$store_name = stripslashes($line["store_name_website"]);
	if ( $store_name == '' ) {
		$store_name = stripslashes($line["store_name"]);
	}
	if ( $store_name == '' ) {
		$store_name = 'Untitled Retailer';
	}
	echo '<span class="store_name">'.$store_name.'</span>';
	if($line["address1"] != "") { 
		echo "<br />";
		echo stripslashes($line["address1"]);
	}
	if($line["address2"] != "") { 
		echo "<br />";
		echo stripslashes($line["address2"]);
	}
	echo "<br />";
	echo stripslashes($line["city"]) . ", " . $line["state"] . " " . $line["zip"];
	if($line["country"] != "US") { echo " " . $line["country"]; }
	if($line["contact_phone_website"] != "") {
		echo "<br />Phone: ";
		echo $line["contact_phone_website"];
	}
	if($line["contact_fax_website"] != "") {
		echo "<br />Fax: ";
		echo $line["contact_fax_website"];
	}
	if($line["contact_name_website"] != "") {
		echo "<br /><u>Contact</u>: ";
		echo stripslashes($line["contact_name_website"]);
	}
	if($line["contact_website_website"] != "") {
		echo "<br />Website: <a href=\"http://";
		echo $line["contact_website_website"];
		echo "\">";
		echo $line["contact_website_website"];
		echo "</a>";
	}
	if($line["contact_email_website"] != "") {
		echo "<br />Email: <a href=\"mailto:";
		echo $line["contact_email_website"];
		echo "\">";
		echo $line["contact_email_website"];
		echo "</a>";
	}
	if($line["hours_days_operation"] != "") {
		echo "<br /><u>Hours/Days Open</u>: ";
		echo stripslashes($line["hours_days_operation"]);
	}
	if($line["items_sold"] != "") {
		echo "<br /><u>Items Sold</u>: ";
		echo stripslashes($line["items_sold"]);
	}
	echo '<br /><span class="distance">Distance: '.$line["distance"].' miles</span>';
	echo "</td></tr>";
}
?>