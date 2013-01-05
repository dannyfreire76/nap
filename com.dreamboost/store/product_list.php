<?php
// BME WMS
// Page: Store Product List Page
// Path/File: /store/product_list.php
// Version: 1.8
// Build: 1805
// Date: 05-06-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

$prod_cat_id = $_GET["prod_cat_id"];
$display_criteria = ($retailer_id ? "display_in_wc" : "display_on_website");

$query = "SELECT name, description, parent_cat, display_name_description, is_parent FROM product_categories WHERE prod_cat_id='$prod_cat_id' AND $display_criteria='1' AND active='1'";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
	$cat_description = $line["description"];
	$cat_parent_cat = $line['parent_cat'];
	$cat_display_name_description = $line["display_name_description"];
	$cat_is_parent = $line['is_parent'];
}

mysql_free_result($result);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Dream Boost Store | <?php echo ($cat_name ? $cat_name:'Online Store') ?> | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
</head>
<body>
<div align="center">

<?php
include '../includes/head1.php';
?>

<h1>Dream Boost Store Order Dream Boost today and try the supplement that optimizes sleep patterns for restful and productive sleep while increasing dreaming ability, vividness, and recall.</h1>
<h2>Special Offers from Dream Boost Try Dream Boost today for just $2 shipping and handling!</h2>
<h2>Dream Boost 10 & 20 tablet boxes</h2>
<h2>Dream Boost 30 tablet bottles</h2>
<h2>Dream Boost 60 tablet bottles</h2>
<h2>An Initiation into the World of Lucid Dreaming Written as a companion to Dream Boost, this book provides readers with insights into the history of dreaming and the lucid dreaming process</h2>
<TABLE border="0" width="100%">
  <TBODY>
  <TR>
	<TD align="left"><IMG height="34" alt="Online Store"
	  src="<?=$current_base?>images/OnlineStore.gif" width="136" /></TD></TR>
  <TR>

<tr><td align="left" class="style2">
<table border="0" cellpadding="0" cellspacing="0" width="90%"><tr>

<?php
if ( $prod_cat_id ) {
	echo '<td align="left" class="style4"><font class="bodytext"><a href="index.php">Online Store</a> >';
}

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

if ( $prod_cat_id ) {
	echo ' <a href="product_';

	if($cat_is_parent == 1) {
		echo "category";
	} elseif($cat_is_parent == 0) {
		echo "list";
	}

	echo '.php?prod_cat_id='.$prod_cat_id.'">'.$cat_name.'</a></font></td>';
}
?>
<td align="right" class="style2"><font class="bodytext"><a href="<?=$base_url?>store/shipping.php">Shipping Information</a></font></td></tr>
</table>

<table border="0" cellpadding="4" width="100%">
<tr><td>
<?php
$where_clause = "";
if ( $prod_cat_id ) {
	$where_clause = " AND prod_cat_id='".$prod_cat_id."'";
}

$query = "SELECT prod_cat_id, name, description, parent_cat, display_name_description, is_parent FROM product_categories WHERE $display_criteria='1' AND active='1'".$where_clause." ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$cat_name = $line["name"];
	$cat_description = $line["description"];
	$cat_parent_cat = $line['parent_cat'];
	$cat_display_name_description = $line["display_name_description"];
	$cat_is_parent = $line['is_parent'];
	$prod_cat_id = $line['prod_cat_id'];

	echo '<div class="style3 clear prod_name"><a href="?prod_cat_id='.$prod_cat_id.'">'.$cat_name.'</a>';
	echo ' - <span class="style2">'.$cat_description.'</span></div>';
	echo '<div class="prods_in_cat">';
	$item_counter = 0;
	//$query2 = "SELECT  p.prod_cat_id, p.prod_id, p.name, p.url, p.sub_name, ps.cost, p.description, p.ordering_info, p.image, p.image_thumbnail, p.image_alt_text, p.image_width, p. image_height FROM products p, product_skus ps WHERE (prod_cat_id='$prod_cat_id' OR prod_cat_id2='$prod_cat_id' OR prod_cat_id3='$prod_cat_id') AND p.prod_id = ps.prod_id AND p.display_on_website='1' AND p.active='1' ORDER BY prod_cat_id ASC, p.position ASC";
	$query2 = "SELECT  p.prod_cat_id, p.prod_id, p.name, p.url, p.sub_name, p.pricing, p.description, p.ordering_info, p.image, p.image_thumbnail, p.image_alt_text, p.image_width, p. image_height FROM products p WHERE (prod_cat_id='$prod_cat_id' OR prod_cat_id2='$prod_cat_id' OR prod_cat_id3='$prod_cat_id') AND p.$display_criteria='1' AND p.active='1' ORDER BY prod_cat_id ASC, p.position ASC";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		$related_prod_id = $line2["prod_id"];
		$related_name = $line2["name"];
		$related_image_alt_text = $line2["image_alt_text"];
		$related_image_thumbnail = $line2["image_thumbnail"];
		//$cost = $line2["cost"];
		$cost = $line2["pricing"];
		$ordering_info = $line2["ordering_info"];
		$item_counter++;
		echo '<table class="left text_left prod_list"><tr><td><a href="product.php?prod_id='.$related_prod_id.'"><img src="../images/'.$related_image_thumbnail.'" border="0" alt="'.$related_image_alt_text.'"></a></td>';
        echo '<td><br /><a href="product.php?prod_id='.$related_prod_id.'">'.$related_name.'</a><br /><b>'.$cost.'</b><br />'.$ordering_info.'<br />';
		echo '<a href="product.php?prod_id='.$related_prod_id.'">';
		if ( $prod_cat_id=='6') {
			echo '<img class="view_now" src="/images/button_view_now.gif" />';
		}
		else {
			echo '<img class="order_now" src="/images/button_order_now.gif" />';
		}
		echo '</a>';
		echo '</td></tr></table>';
	}
	echo '</div>';
}
?>
</td><td valign="top">
<?php
if ($cat_display_name_description == 1) {
?>
<font class="bodytext"><?php echo $cat_name; ?></font><br>
<br>
<font class="bodytext"><?php echo $cat_description; ?></font>
<?php
} else {
	echo "&nbsp;";
}
?>
</td></tr>
</table>
</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
</div>
