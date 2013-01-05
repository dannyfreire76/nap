<?php
// BME WMS
// Page: Wholesale Catalog Header Include
// Path/File: /includes/head_wc1.php
// Version: 1.1
// Build: 1102
// Date: 12-07-2006
?>
<table border="0" cellspacing="0" cellpadding="0" width="677"><!-- <?=__FILE__?> -->
<tr><td rowspan="4"><a href="http://www.salviazone.com/index.php"><img src="../images/home_head2.jpg" border="0" width="508" height="142" alt="SalviaZone.com"></a></td>
<td><a href="http://www.salviazone.com/medsups/index.php"><img src="../images/nav_med_sup1.jpg" border="0" width="169" height="38" alt="Meditation Supplements"></a></td></tr>

<tr><td><a href="http://www.salviazone.com/about/index.php"><img src="../images/nav_aboutus1.jpg" border="0" width="169" height="36" alt="About Us"></a></td></tr>

<tr><td><a href="http://www.salviazone.com/findretailer/index.php"><img src="../images/nav_find_retail1.jpg" border="0" width="169" height="37" alt="Find a Retailer"></a></td></tr>

<tr><td class="hd2" width="169" height="31">
<form name="SearchForm" Method="POST" action="http://www.salviazone.com/search/sessearch.php" STYLE="DISPLAY:INLINE;"><!-- <?=__FILE__?> -->
<input type="hidden" name="cnt" value="25">
<input type="text" name="q" size="11"> <input type="submit" value="Search">
</form>
</td></tr>

<tr><td colspan="2"><a href="http://www.salviazone.com/wc/index2.php"><img src="../images/nav1_store1.jpg" border="0" alt="Store"></a><a href="http://www.salviazone.com/salvia/index.php"><img src="../images/nav1_salvia1.jpg" border="0" alt="Salvia"></a><a href="http://www.salviazone.com/directions/index.php"><img src="../images/nav1_directions1.jpg" border="0" alt="Directions"></a><a href="http://www.salviazone.com/testimonials/index.php"><img src="../images/nav1_testimonials1.jpg" border="0" alt="Testimonials"></a><a href="http://www.salviazone.com/faqs/index.php"><img src="../images/nav1_faqs1.jpg" border="0" alt="FAQs"></a><a href="http://www.salviazone.com/contact/index.php"><img src="../images/nav1_contact_us1.jpg" border="0" alt="Contact Us"></a></td></tr>
<tr><td colspan="2"><table border="0" width="100%">
<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="3"><?php
$retailer_id = $_COOKIE["wc_user"];
if($retailer_id != "") {
?>
<a href="http://www.salviazone.com/wc/my/index2.php">My <?php echo $website_title; ?></a> | <a href="http://www.salviazone.com/wc/my/order_history.php">Order History</a> | <a href="http://www.salviazone.com/wc/my/update_retailer.php">Store Info</a> | <a href="http://www.salviazone.com/wc/index.php">Logout</a>
<?php
} else {
?>
<a href="http://www.salviazone.com/wc/index.php">Login</a> | <a href="http://www.salviazone.com/beretailer/index.php">Register</a>
<?php
}
?>
</font></td><td align="right"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="2">
<?php
if($retailer_id != "") {
	include '../includes/cart1.php';
	$cart_total = get_wc_cart_total($retailer_id);
	echo "<a href=\"http://www.salviazone.com/wc/cart.php\"><image src=\"../images/shop_cart1.gif\" border=\"0\" height=\"16\" width=\"16\" alt=\"Shopping Cart\"></a> <a href=\"http://www.salviazone.com/wc/cart.php\">";
	echo "$";
	echo $cart_total;
	echo "</a>";
} else {
	echo "&nbsp;";
}
?>
</font></td></tr></table>
</td></tr>

</table>
