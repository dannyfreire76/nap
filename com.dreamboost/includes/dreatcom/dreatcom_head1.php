<?php 
// BME WMS 
// Page: Footer Homepage Include
// Path/File: /includes/head1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

if(  $_SESSION["rep_id"] != "" ) {
	include_once($base_path.'includes/reps_head1.php');
}
else {

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

	if($member_id != ""){
		$logged_in = '';
		$not_logged_in = 'display:none;';
	} else {
		$logged_in = 'display:none;';
		$not_logged_in = '';
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
					<td width="200" align="left" valign="top" id="tagline"><img src="<?=$base_url?>images/logo/dreamboost_tagline-160.png" width="160" height="100" /></td>
					<td width="420" valign="top" id="logo"><img src="<?=$base_url?>images/logo/dreamboost_logotag-400.png" width="400" height="95" /></td>
					<td width="350" align="right" valign="top">
						<div id="navMenu">
							<ul>
								<li><a href="<?=$base_url?>index.php">Home</a></li>
								<li style="<?=$not_logged_in?>"><a href="<?=$base_url?>login.php">Login</a></li>
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
<?php /*?>				<li class="sidebarNav">Listen to a recent <a href="http://www.smodcast.com/" title="SMODCAST"><img src="<?=$base_url?>images/SMODCAST.jpg" alt="SMODCAST" style="height: 34px; width: 120px;"></a> about <?=$product_name?>  <object type="application/x-shockwave-flash" data="http://tinycomet.com/audio/player.swf" id="audioplayer1" width="290" height="24">
					<param name="movie" value="http://tinycomet.com/audio/player.swf">
					<param name="FlashVars" value="playerID=audioplayer1&amp;soundFile=<?=$base_url?>includes/SleepSquaresFull.mp3">
					<param name="quality" value="high">
					<param name="menu" value="false">
					<param name="wmode" value="transparent">
					</object></li><?php */?>
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
				<li class="sidebarNav"><a href="http://sleepsquares.com">Sleep Squares Products</a></li>
			</ul>
			<h6 style="<?=$logged_in?>" class="titleSidebar">MY ACCOUNT</h6>
			<ul style="<?=$logged_in?>">
				<li class="sidebarNav"><a href="<?=$base_url?>customer/customer_history.php">Order History</a></li>
				<li class="sidebarNav"><a href="<?=$base_url?>customer/update_customer.php">Your Profile</a></li>
			</ul>
			<img src="<?=$base_url?>images/img_sidebarbot2.png" width="190" height="90" style="margin:9px 0 -18px 0;" /></div>
		<!-- end #sidebar -->
		<div id="mainContent">
	<!-- ...head2 -->
<?php
}	

?>
