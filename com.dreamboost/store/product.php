<?php
// BME WMS
// Page: Store Product Page
// Path/File: product.php
// Version: 1.8
// Build: 1805
// Date: 05-06-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$prod_id = $_REQUEST["prod_id"];
$display_criteria = ($retailer_id ? "display_in_wc" : "display_on_website");

$query = "SELECT * FROM products WHERE prod_id='$prod_id' AND $display_criteria='1' and active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prod_cat_id = $line["prod_cat_id"];
	$prod_active = $line["active"];
	$name = $line["name"];
	$url = $line["url"];
	$sub_name = $line["sub_name"];
	$pricing = $line["pricing"];
	$description = $line["description"];
	$ordering_info = $line["ordering_info"];
	$image = $line["image"];
	$image_alt_text = $line["image_alt_text"];
	$image_width = $line["image_width"];
	$image_height = $line["image_height"];
}
mysql_free_result($result);

$query = "SELECT * FROM product_categories WHERE prod_cat_id='$prod_cat_id' AND $display_criteria='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
	$cat_parent_cat = $line['parent_cat'];
	$cat_is_parent = $line['is_parent'];
}
mysql_free_result($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $cat_name . ": " . $name; ?> | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
</head>
<body>
<div align="center">

<?php
include '../includes/head1.php';
?>


<table border="0" width="95%">

<tr><td align="left" class="style2"><IMG height="34" alt="Online Store" 
	  src="<?=$current_base?>images/OnlineStore.gif" width="136" /></td></tr>

<tr><td align="left">
<table border="0" cellpadding="0" cellspacing="0" width="90%">
<tr><td align="left" class="style2"><a href="index.php">Online Store</a> > 
<?php
if($cat_parent_cat != 0) {
	$query = "SELECT name, parent_cat, is_parent FROM product_categories WHERE prod_cat_id='$cat_parent_cat' AND $display_criteria='1' AND active='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		if($line['parent_cat'] != 0) {
			$query2 = "SELECT name, parent_cat, is_parent FROM product_categories WHERE prod_cat_id='".$line['parent_cat']."' AND $display_criteria='1' AND active='1'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				if($line2['parent_cat'] != 0) {
					$query3 = "SELECT name, parent_cat, is_parent FROM product_categories WHERE prod_cat_id='".$line2['parent_cat']."' AND $display_criteria='1' AND active='1'";
					$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
					while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
						echo "<a href=\"product_";
						if($line3['is_parent'] == 1) {
							echo "category";
						} elseif($line3['is_parent'] == 0) {
							echo "list";
						}
						echo ".php?prod_cat_id=";
						echo $line2['parent_cat'];
						echo "\">";
						echo $line3['name'];
						echo "</a> > ";
					}
					mysql_free_result($result3);
				}
				echo "<a href=\"product_";
				if($line2['is_parent'] == 1) {
					echo "category";
				} elseif($line2['is_parent'] == 0) {
					echo "list";
				}
				echo ".php?prod_cat_id=";
				echo $line['parent_cat'];
				echo "\">";
				echo $line2['name'];
				echo "</a> > ";
			}
			mysql_free_result($result2);
		}
		echo "<a href=\"product_";
		if($line['is_parent'] == 1) {
			echo "category";
		} elseif($line['is_parent'] == 0) {
			echo "list";
		}
		echo ".php?prod_cat_id=";
		echo $cat_parent_cat;
		echo "\">";
		echo $line['name'];
		echo "</a> > ";
	}
	mysql_free_result($result);
}
?>
<a href="product_
<?php
if($cat_is_parent == 1) {
	echo "category";
} elseif($cat_is_parent == 0) {
	echo "list";
}
?>
.php?prod_cat_id=<?php echo $prod_cat_id; ?>"><?php echo $cat_name; ?></a> > <a href="product.php?prod_id=<?php echo $prod_id; ?>"><?php echo $name; ?></a></td><td class="style2 text_right"><a href="shipping.php">Shipping Information</a></td></tr>
</table>
</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><table border="0" cellspacing="8" cellpadding="0">
<tr><td VALIGN="TOP" align="left">

<?php
if ( $image ) {
    echo '<img src="../images/'.$image.'" border="0" alt="'.$image_alt_text.'">';
}
?>

</td>
<td VALIGN="TOP" align="left" NOWRAP class="style2">
<?php
//if($sub_name != "") {
	echo "<b>$name</b><br>\n";
//}
?>
<?php

echo '<form id="orderForm" name="orderForm" action="'.($retailer_id ? $current_base."wc/cart.php" : "cart.php").'" method="POST" onSubmit="return checkOrderForm( $(this) );">';

$line_counter = 0;
$for_sale = 0;
$query = "SELECT * FROM product_skus WHERE prod_id='$prod_id' AND $display_criteria='1' AND active='1' ORDER BY cost";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

if ( $retailer_id ) {
    $line_counter = 0;
    $for_sale = 0;
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $prods[$line_counter]["sku"] = $line["sku"];
        $prods[$line_counter]["name"] = $line["name"];
        $prods[$line_counter]["drop_down"] = $line["drop_down"];
        $prods[$line_counter]["cost"] = $line["cost"];
        $prods[$line_counter]["wholesale_cost1"] = $line["wholesale_cost1"];
        $prods[$line_counter]["wholesale_cost2"] = $line["wholesale_cost2"];
        $prods[$line_counter]["wholesale_cost3"] = $line["wholesale_cost3"];
        $prods[$line_counter]["dist_cost1"] = $line["dist_cost1"];
        $prods[$line_counter]["dist_cost2"] = $line["dist_cost2"];
        $prods[$line_counter]["dist_cost3"] = $line["dist_cost3"];
        $prods[$line_counter]["stock_status"] = $line["stock_status"];
        $line_counter++;
    }

    $showPriceTable = false;
    for( $x=0; $x<count($prods); $x++ ) {
        foreach( $prods[$x] as $key => $val ) {
            if ( strpos($key, 'cost') ) {
                if ( $val != 0 ) {
                    $showPriceTable = true;
                    break;
                }
            }
        }
    }

    if ( $showPriceTable ) {
        echo '<table class="price_chart" cellpadding="3" cellspacing="0" border="1" bordercolor="#000"><tr><td></td>';
        for( $x=0; $x<count($prods); $x++ ) {
            echo '<th class="text_right">'.$prods[$x]["drop_down"].'</th>';
        }
        echo'</tr><tr><td>Suggested Retail</td>';
        for( $x=0; $x<count($prods); $x++ ) {
            echo '<td class="text_right">$'.$prods[$x]["cost"].'</td>';
        }
        echo '</tr>';

        $querySLW = "SELECT slwid, slw_min, slw_max, slw_measure, cost_field, price_level FROM wholesale_price_levels ORDER BY price_level ASC";
        $resultSLW = mysql_query($querySLW) or die("Query failed : " . mysql_error());
        while ($lineSLW = mysql_fetch_array($resultSLW, MYSQL_ASSOC)) {
			if ( $price_level_type=='1' ) {
				echo '<tr>';
				echo '<td>';
				$min_shown = $lineSLW["slw_min"];
				if ( $lineSLW["slw_measure"]=='dollars' ) {
					echo '$';
					$min_shown = number_format($min_shown);
				}

				echo $min_shown;
				
				if ( $lineSLW["slw_max"] ) {
					echo ' - ';
					$max_shown = $lineSLW["slw_max"];
					if ( $lineSLW["slw_measure"]=='dollars' ) {
						echo '$';
						$max_shown = number_format($max_shown);
					}
					echo $max_shown;
				}
				else {
					echo '+';
				}
				
				if ( $lineSLW["slw_measure"]=='units' ) {
					echo ' units';
				}
				echo '</td>';
				for( $x=0; $x<count($prods); $x++ ) {
					if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {
						echo '<td class="text_right">$'.$prods[$x][ $lineSLW["cost_field"] ].'</td>';
					}
					else {
						echo '<td class="text_right"><a href="'.$base_url.'contact/index.php">contact us</a></td>';
					}
				}
				echo '</tr>';
			}
			else if ( $price_level_type=='2' && $_SESSION["retailer_store_type"]==$lineSLW["price_level"] ) {
				echo '<tr>';
				echo '<td>Your Price</td>';
				for( $x=0; $x<count($prods); $x++ ) {
					if ( $retailer_status==1 || strpos($lineSLW["cost_field"], 'dist')===false ) {
						echo '<td class="text_right">$'.$prods[$x][ $lineSLW["cost_field"] ].'</td>';
					}
					else {
						echo '<td class="text_right"><a href="'.$base_url.'contact/index.php">contact us</a></td>';
					}
				}
				echo '</tr>';
			}
        }
        echo '</table>';
    }
}
else {//regular customer
    echo '<table border="0" cellspacing="0" cellpadding="4">';
    $result = mysql_query($query) or die("Query failed : " . mysql_error());
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $line_counter++;
        $prod_sku_sku = $line["sku"];
        $prod_sku_name = $line["name"];
        $prod_sku_cost = $line["cost"];
        $stock_status = $line["stock_status"];
        if($stock_status == 1) {
            $for_sale = 1;
            echo "<tr valign=\"top\"><td><input type=\"radio\" name=\"sku\" value=\"$prod_sku_sku\"";
            if($line_counter == 1) { echo " CHECKED"; }
            echo "></td>";
            echo "<td class=\"style2\">$prod_sku_name</td>";
            echo "<td class=\"style2 text_right\">$";
            echo $prod_sku_cost;
            echo "</td></tr>\n";
        } elseif($stock_status == 0) {
            echo "<tr><td colspan=\"3\" class=\"bodytext\">Out of Stock</td></tr>\n";
        } elseif($stock_status == 2) {
            echo "<tr><td colspan=\"3\" class=\"bodytext\">Portfolio Only</td></tr>\n";
        }
    }
    echo "</table>\n";
    if($for_sale == 1) {
        echo '
        <br>
        Quantity 
        <select id="quantity" name="quantity"';
        if ( in_array($prod_id, $free_prods_arr) ) {
            echo ' disabled="true" ';
        }
        echo '>';


        for($i=1;$i<100;$i++){
            echo "<option value=\"$i\">$i</option>\n";
        }
        echo '
        </select><br>
        <input type="submit" id="button_add_to_cart" name="button_add_to_cart" value="Add to Cart">
        <div class="no_display error msg"></div>';
    }
}

if ( $retailer_id ) {
    $prods_available = false;
    for( $x=0; $x<count($prods); $x++ ) {
        $stock_status = $prods[$x]["stock_status"];
        if($stock_status == 1) {
            $prods_available = true;//at least one sku is available
            echo '<br /><input type="radio" name="sku" value="'.$prods[$x]["sku"].'"';
            if($x == 0) { echo " CHECKED"; }
            echo '> '.$prods[$x]["drop_down"];
        } elseif($stock_status == 0) {
            echo '<div class="error3">'.$prods[$x]["drop_down"].' - Out of Stock<br /><br /><br/><br/></div>';
        } elseif($stock_status == 2) {
            echo '<div class="error3">'.$prods[$x]["drop_down"].' - Portfolio Only<br /><br /><br/><br/></div>';
        }
    }
    if ( $prods_available ) {
        echo '<br /><br />Quantity <input type="text" id="quantity" name="quantity" size="5" class="text_right" />
        <br /><input type="submit" id="button_add_to_cart" name="button_add_to_cart" value="Add to Cart"';
        if ( $prod_active!='1' ) {
            echo ' disabled="true"';
        }
        echo '>
        <div class="error msg">&#160;</div>';
    }
}

?>
</form></td><td>&nbsp;</td><td VALIGN="TOP" align="left" class="style2"><?php echo $ordering_info; if($ordering_info != "") { echo "<br><br>"; } ?>
<?php
//$user_id = $_COOKIE["db_user"];//9/27/2009
if($user_id && !$retailer_id) {
	echo "<a href=\"./cart.php\">View Your Shopping Cart</a>";
}
?>
</td></tr>
</table></td></tr>

<?php
    if ($retailer_id) {    
        if ( $prod_id=='15' || $prod_id=='13' ) {
            echo '<tr><td class="error3">Note: There are 12 bottles per carton, 12 cartons per case.</td></tr>';
        }
    }
?>

<tr><td align="left" class="style2"><?php echo $description; ?></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
$query = "SELECT prod_id, name, image_alt_text, image_thumbnail, image_thumbnail_width, image_thumbnail_height FROM products WHERE prod_id!='$prod_id' AND prod_cat_id='$prod_cat_id' AND $display_criteria='1' AND active='1' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());

if ( mysql_num_rows($result)>0 ) {

	echo '<tr><td align="left" class="style3">Other '.$cat_name.' Options:</td></tr><tr><td align="left" class="style2 prod_thumbs">';

	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$related_prod_id = $line["prod_id"];
		$related_name = $line["name"];
		$related_image_alt_text = $line["image_alt_text"];
		$related_image_thumbnail = '../images/'.$line["image_thumbnail"];
		//$related_image_thumbnail_width = $line["image_thumbnail_width"];
		//$related_image_thumbnail_height = $line["image_thumbnail_height"];
		echo '<a href="product.php?prod_id='.$related_prod_id.'"><img src="'.$related_image_thumbnail.'" class="other_thumbs" border="0" alt="'.$related_image_alt_text.'" title="'.$related_image_alt_text.'" /></a>';
		//echo "<a href=\"product.php?prod_id=$related_prod_id\">$related_name</a>";
	}
	echo '</td></tr>';
}
mysql_free_result($result);
?>


<tr><td>&nbsp;</td></tr>
</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>