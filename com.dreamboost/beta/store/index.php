<?php
// BME WMS
// Page: Online Store Main Page
// Path/File: /store/index.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 950;

if($confirm){
	$result = setcookie("db_user", $user_id, time()-3600, "/store/", ".dreamboost.com", 0) or die ("Set Cookie failed : " . mysql_error());
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Store</title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/reset.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/core.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/site_styles.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/beta/includes/wmsform.css" />
<script type="text/javascript" src="/beta/includes/js_funcs1.js"></script>
</head>
<body bgColor="#ffffff" onload="MM_preloadImages('newsletter_Layer 64_f2.gif','button_subscribe_over.gif','/beta/images/warning_over.gif','/beta/images/aboutus_over.gif','images/newsletter_over.gif','images/links_over.gif','images/find_over.gif','images/become_over.gif','/beta/images/store_over.gif','/beta/images/faqs_over.gif','/beta/images/lucid_over.gif','/beta/images/suggestions_over.gif','/beta/images/supplement_over.gif','/beta/images/testimonial_over.gif','/beta/images/contact_over.gif')">

<?php
include '../includes/head1.php';
?>

            <TABLE width="797" border="0">
              <TBODY>
              <TR>
                <TD align="left" colSpan="3"><IMG height="34" alt="Online Store" 
                  src="/beta/images/OnlineStore.gif" width="136"></TD></TR>
              <TR>
                <TD vAlign="middle" align="center" width="200"><a href="/beta/store/40pillbottle.php"><IMG 
                  height="122" alt="One Month Supply of Dream Boost" src="/beta/images/1bottle1.jpg" 
                  width="74" border="0"></a></TD>
                <TD vAlign="middle" align="center" width="232"><a href="/beta/store/two40pillbottles.php"><IMG 
                  height="122" alt="Two Month Supply of Dream Boost" src="/beta/images/2bottles2.jpg" 
                  width="143"></a></TD>
                <TD vAlign="top" align="center" width="351">
                <table border="0" cellspacing="0" cellpadding="0">
                <tr><td vAlign="middle" align="center"><a href="/beta/store/three40pillbottles.php"><IMG 
                  height="122" alt="Three Month Supply of Dream Boost" src="/beta/images/3bottles3.jpg" 
                  width="143"></a></td><td vAlign="middle" align="center"><a href="/beta/store/three40pillbottles.php"><IMG height="35" alt="Plus" 
                  src="/beta/images/Plus.gif" width="33" border="0"></a></td><td vAlign="middle" align="center"><a href="/beta/store/three40pillbottles.php"><IMG height="122" 
                  alt="One Month Supply of Dream Boost" src="/beta/images/1bottle1.jpg" width="74" border="0"></a></td></tr></table></TD></TR>
              <TR>
                <TD vAlign="top" align="center"><SPAN 
                  class="style3">$28.95</SPAN><BR><SPAN class="style2">Free Booklet 
                  Included!</SPAN></TD>
                <TD vAlign="top" align="center"><SPAN 
                  class="style3">$57.90</SPAN><BR><SPAN class="style2">FREE SHIPPING + Free Booklet!</SPAN></TD>
                <TD vAlign="top" align="center"><SPAN 
                  class="style3">$86.85</SPAN><BR><SPAN class="style2">FREE SHIPPING + ONE FREE 
                  BOTTLE<BR>+ Free Booklet!</SPAN></TD></TR>
              <TR>
                <TD vAlign="top" align="center"><A 
                  onmouseover="MM_swapImage('button_order_now1','','/beta/images/button_order_now_over.gif',1)" 
                  onmouseout="MM_swapImgRestore()" href="/beta/store/40pillbottle.php"><IMG 
                  height="34" alt="Order Now" 
                  src="/beta/images/button_order_now.gif" 
                  width="104" border="0" name="button_order_now1"></A></TD>
                <TD vAlign="top" align="center"><A 
                  onmouseover="MM_swapImage('button_order_now2','','/beta/images/button_order_now_over.gif',1)" 
                  onmouseout="MM_swapImgRestore()" href="/beta/store/two40pillbottles.php"><IMG 
                  height="34" alt="Order Now" 
                  src="/beta/images/button_order_now.gif" 
                  width="104" border="0" name="button_order_now2"></A></TD>
                <TD vAlign="top" align="center"><A 
                  onmouseover="MM_swapImage('button_order_now3','','/beta/images/button_order_now_over.gif',1)" 
                  onmouseout="MM_swapImgRestore()" href="/beta/store/three40pillbottles.php"><IMG 
                  height="34" alt="Order Now" 
                  src="/beta/images/button_order_now.gif" 
                  width="104" border="0" name="button_order_now3"></A></TD></TR>
              <TR>
                <TD colSpan="3" height="24"><BR><BR></TD></TR>
              <TR>
                <TD vAlign="middle" align="center" colSpan="2">
                <table border="0">
                <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
                  <DIV align="justify">
                  <BLOCKQUOTE>
                    <P class="style2">Check out this great and informative book - An Initiation 
                    into the World of Lucid Dreaming by Darien Simon, M.S. - 
                    from Dream Boost and presented by The Upstate Dream 
                    Institute.</P></BLOCKQUOTE></DIV>
                  <P><A 
                  onmouseover="MM_swapImage('button_order_now4','','/beta/images/button_order_now_over.gif',1)" 
                  onmouseout="MM_swapImgRestore()" href="/beta/store/book1.php"><IMG 
                  height="34" alt="Order Now" 
                  src="/beta/images/button_order_now.gif" 
                  width="104" border="0" name="button_order_now4"></A></P></td></tr></table></TD>
                <TD vAlign="middle" align="center"><A 
                  href="/beta/store/book1.php"><IMG height="183" 
                  alt="An Initiation into the World of Lucid Dreaming" src="/beta/images/BookCoversmall.jpg" 
                  width="133"></A></TD></TR></TBODY></TABLE>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>