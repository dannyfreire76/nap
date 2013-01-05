<?php
// BME WMS
// Page: Header Home Include
// Path/File: /includes/head_home1.php
// Version: 1.1
// Build: 1103
// Date: 09-26-2006
/**
 *CONTAINS VISUAL ELEMENTS FROM CART...
 */

include_once('main1.php');
include_once($base_path.'includes/cart1.php');

$font = "Verdana";

if($retailer_id != "") {
	$cart_totals = get_wc_cart_total($retailer_id);
}
else{
//if($member_id != "") {
	$cart_totals = get_cart_total($user_id);
}

$pages = array(
		$base_url => "Home",
		$base_url."store" => "Store",
		$base_url."faqs" => "FAQs",
		$base_url."supplement-facts" => "Supplement Facts",
		$base_url."directions" => "Suggestions",
		$base_url."articles" => "Articles",
		$base_url."news" => "In The News",
		$base_url."newsletters" => "Newsletter",
		$base_url."warnings" => "Warning&#160;&amp;&#160;Disclaimer",
		$base_url."about" => "About&#160;Us",
		$base_url."contact" => "Contact Us"
		);

?>
<!-- head1... -->
<div id="outer_container">


	<div id="sub_menu">
		<?php if(!strpos($_SERVER["SCRIPT_NAME"], "confirm.php") && !strpos($_SERVER['SCRIPT_NAME'],'login.php')):?>

			<?php if($retailer_id != ""):?>
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
					<tr>
						<td>
							<!--<a href="<?=$base_url?>wc/my/index2.php">My <?=$website_title?></a> | -->
							<a href="<?=$base_url?>wc/my/order_history.php">Order History</a> |
							<a href="<?=$base_url?>wc/my/update_retailer.php">Store Info</a>
						</td>
						<td align="right">
							<?php if($cart_totals[1] > 0):?>
								<a href="<?=$base_url?>wc/cart.php">
								<image id="cartImg" src="<?=$current_base?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />
								&#160;<?=$cart_totals[1];?>  item<?=($cart_totals[1]>1 ? "s" : "");?> In Your Cart: $<?=$cart_totals[0];?></a>
							<?php else:?>
								<image id="cartImg" src="<?=$current_base;?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.';
							<?php endif;?>
						</td>
					</tr>
				</table>
			<?php else:?>
				<?php if($member_id != ""):?>
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td align="left">
								<span class="error3">Welcome <?=$_SESSION["member_name"];?>!</span>&#160;&#160;&#160;
							</td>
							<td align="center">
								<a href="<?=$base_url;?>customer/update_customer.php">Your Profile</a> |
								<a href="<?=$base_url;?>customer/customer_history.php">Order History</a> |
								<a href="<?=$base_url;?>login.php?action=logout" id="logout">Logout</a>
							</td>
							<td align="right">
								<?php if($cart_totals[1] > 0):?>
									<a href="<?=$base_url?>store/cart.php">
									<image id="cartImg" src="<?=$current_base?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />
									&#160;<?=$cart_totals[1];?>  item<?=($cart_totals[1]>1 ? "s" : "");?> In Your Cart: $<?=number_format($cart_totals[0],2);?></a>
								<?php else:?>
									<image id="cartImg" src="<?=$current_base;?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.
								<?php endif;?>
							</td>
						</tr>
					</table>
				<?php else:?>
					<table border="0" cellpadding="3" cellspacing="0" width="100%">
						<tr>
							<td align="left">
								<a href="<?=$base_url?>login.php" id="login" class="form_link">Returning customer sign-in</a>
							</td>
							<td align="right">
								<?php if($cart_totals[1] > 0):?>
									<a href="<?=$base_url?>store/cart.php">
									<image id="cartImg" src="<?=$current_base?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />
									&#160;<?=$cart_totals[1];?>  item<?=($cart_totals[1]>1 ? "s" : "");?> In Your Cart: $<?=number_format($cart_totals[0],2);?></a>
								<?php else:?>
									<image id="cartImg" src="<?=$current_base;?>images/shop_cart1.gif" border="0" alt="Shopping Cart" />&#160;Your cart is currently empty.
								<?php endif;?>
							</td>
						</tr>
					</table>
				<?php endif;?>

			<?php endif;?>

		<?php endif;?>
	</div>



	<div id="header">
		<table border="0" bgcolor="#fff" cellpadding="0" cellspacing="0" width="980" height="165">
			<tr>
				<td class="container">
					<table bgcolor="#ffffff" border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" class="header">
								<span id="logo_click" onclick="window.location='<?=$current_base?>'">&nbsp;</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="hmenubar">
							<?php foreach($pages as $url=>$title):?>
								<?php
									if(substr($url,-1) == '/'){
										$url = substr($url,0,strlen($url) -1);
									}
								?>
								<a class="tan_text" href="<?php echo $url;?>/index.php"><?php echo $title;?></a>
								<img class="hsplitter" src="<?=$current_base?>images/ss/seperator.png" width="2" height="13">
							<?php endforeach;?>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="bw_dropshadow"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

	<div id="container">
<!-- ...head1 -->