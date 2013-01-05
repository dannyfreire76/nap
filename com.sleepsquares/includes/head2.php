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

if($member_id){
	//logged in
	$logged_in="";
	$logged_out="display:none; !important";
}else{
	//logged out
	$logged_in="display:none; !important";
	$logged_out="";
}


$pages = array(
		$base_url => "Home",
		$base_url."store" => "Store",
		$base_url."faqs" => "FAQs",
		$base_url."supplement-facts" => "Supplement Facts",
		$base_url."directions" => "Suggestions",
		$base_url."articles" => "Articles",
		$base_url."testimonials" => "Testimonials",
		$base_url."news" => "In The News",
		$base_url."newsletters" => "Newsletter",
		$base_url."warnings" => "Warning&#160;&amp;&#160;Disclaimer",
		$base_url."about" => "About&#160;Us",
		$base_url."contact" => "Contact Us"
		);

?>
<!-- head2... -->
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
					<div id="navMenu">
						<ul>
							<li><a href="<?=$base_url?>index.php">Home</a></li>
							<li style="<?=$logged_out?>"><a href="<?=$base_url?>login.php">Login</a></li>
							<li style="<?=$logged_in?>"><a href="<?=$base_url?>login.php?action=logout">Logout</a></li>
							<li><a href="<?=$base_url?>store/cart.php">View Shopping Cart</a></li>
							<li><a href="<?=$base_url?>store/index.php">Order Sleep Squares</a></li>
							<li><a href="<?=$base_url?>contact/index.php">Contact Us</a></li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
		<div id="spcloffer"></div>
	</div>
	<div id="sidebar">
		<h6 class="titleSidebar">LASTEST NEWS</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$base_url?>news/index.php">In The News</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>articles/index.php">Recent Articles</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>testimonials/index.php">Testimonials</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>newsletters/index.php">Email Newsletter</a></li>
			<li class="sidebarNav">Listen to a recent <a href="http://www.smodcast.com/" title="SMODCAST"><img src="http://dreamboost.com/images/SMODCAST.jpg" alt="SMODCAST" style="height: 34px; width: 120px;"></a> about Dreamboost  <object type="application/x-shockwave-flash" data="http://tinycomet.com/audio/player.swf" id="audioplayer1" width="290" height="24">
				<param name="movie" value="http://tinycomet.com/audio/player.swf">
				<param name="FlashVars" value="playerID=audioplayer1&amp;soundFile=http://dreamboost.com/includes/SleepSquaresFull.mp3">
				<param name="quality" value="high">
				<param name="menu" value="false">
				<param name="wmode" value="transparent">
				</object></li>
		</ul>
		<h6 class="titleSidebar">RESEARCH</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$base_url?>faqs/index.php">FAQs</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>supplement-facts/index.php">Supplement Facts</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>directions/index.php">Usage &amp; Directions</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>warnings/index.php">Warning &amp; Disclaimer</a></li>
		</ul>
		<h6 class="titleSidebar">CORPORATE</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$base_url?>about/index.php">About UDI and<br />
				Slumberland Snacks
			</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>contact/index.php">Contact Us</a></li>
			<li class="sidebarNav"><a href="http://dreamboost.com">Dream Boost Products</a></li>
		</ul>
		<h6 style="<?=$logged_in?>" class="titleSidebar">MY ACCOUNT</h6>
		<ul style="<?=$logged_in?>">
			<li class="sidebarNav" style="<?=$logged_out?>"><a href="<?=$base_url?>login.php">Login</a></li>
			<li class="sidebarNav" style="<?=$logged_in?>"><a href="<?=$base_url?>login.php?action=logout">Logout</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>customer/update_customer.php">Your Profile</a></li>
			<li class="sidebarNav"><a href="<?=$base_url?>customer/customer_history.php">Order History</a></li>
		</ul>
		<img src="<?=$base_url?>images/img_sidebarbot2.png" width="190" height="90" style="margin:9px 0 -18px 0;" /></div>
	<!-- end #sidebar -->
	<div id="mainContent">
<!-- ...head2 -->
