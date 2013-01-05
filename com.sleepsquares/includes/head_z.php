<?php
// new SleepSquares design

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
<div id="container"><!-- <?=__FILE__?> -->
	<div id="header">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="300" id="logo"><img src="<?=$current_base?>images/logo/sleepsquares_logo-270.png" width="270" height="123" /></td>
				<td width="320" id="tagline"><img src="<?=$current_base?>images/logo/sleepsquares_tagline-300.png" width="300" height="75" /></td>
				<td width="350" align="right" valign="top">
					<div id="navMenu">
						<ul>
							<li><a href="<?=$current_base?>index_z.php">Home</a></li>
							<li><a href="<?=$current_base?>login_z.php">Login / Account</a></li>
							<li><a href="<?=$current_base?>store/cart_z.php">View Shopping Cart</a></li>
							<li><a href="<?=$current_base?>store/index_z.php">Order Sleep Squares</a></li>
							<li><a href="<?=$current_base?>contact/index_z.php">Contact Us</a></li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
		<div id="spcloffer"></div>
		<!-- end #header -->
	</div>
	<div id="sidebar">
		<h6 class="titleSidebar">LASTEST NEWS</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$current_base?>news/index_z.php">In The News</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>articles/index_z.php">Recent Articles</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>testimonials/index_z.php">Testimonials</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>newsletters/index_z.php">Email Newsletter</a></li>
		</ul>
		<h6 class="titleSidebar">RESEARCH</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$current_base?>faqs/index_z.php">FAQs</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>supplement-facts/index_z.php">Supplement Facts</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>directions/index_z.php">Usage &amp; Directions</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>warnings/index_z.php">Warning &amp; Disclaimer</a></li>
		</ul>
		<h6 class="titleSidebar">CORPORATE</h6>
		<ul>
			<li class="sidebarNav"><a href="<?=$current_base?>about/index_z.php">About UDI and<br />
				Slumberland Snacks
			</a></li>
			<li class="sidebarNav"><a href="<?=$current_base?>contact/index_z.php">Contact Us</a></li>
			<li class="sidebarNav"><a href="http://dreamboost.com/">Dream Boost Products</a></li>
		</ul>
		<img src="<?=$current_base?>images/bg/img_sidebarbot2.png" width="190" height="90" style="margin:9px 0 -18px 0;" />
	</div>
	<!-- end #sidebar -->
	<div id="mainContent">
		<div class="boxContent">
