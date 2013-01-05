<?php 
// BME WMS 
// Page: Footer Homepage Include
// Path/File: /includes/head1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007

function display_line() {
	global $line_hgt;
	if($line_hgt == "") { $line_hgt = 1000; }
	echo "<IMG height=\"$line_hgt\" alt=\"side\" src=\"/images/lines/";
	echo $line_hgt;
	echo "line.gif\" width=\"18\">\n";
}

?>
<DIV align="center">
<TABLE width="677" align="center" border="0" cellspacing="0" cellpadding="0"><!--DWLayoutTable-->
  <TBODY>
  <TR>
    <TD vAlign="top" align="center" width="18">
    <?php
    display_line();
    ?>
    </TD>
    <TD vAlign="top" width="628" align="center">
      <TABLE width="797" border="0">
        <TBODY>
        <TR>
          <TD vAlign="top" align="center" colSpan="7"><a href="/index.php">
          <IMG height="76" alt="Dream Boost Sleep Aid and Dream Enhancer" src="/beta/images/headerDreamBoost2a.jpg" width="401" border="0"><IMG height="76" alt="Better Sleep. Better Dreams. Better Living." src="/beta/images/headerDreamBoost2b.jpg" width="390" border="0">
          <?php
          /*
          <IMG height="76" alt="Dream Boost Sleep Aid and Dream Enhancer" src="/images/headerDreamBoost.jpg" width="791" border="0">
          */
          ?>
          </a></TD></TR>
        <TR>
          <TD vAlign="top" align="center" colSpan="7" height="236"><IMG height="220" 
            alt="banner" 
            src="/images/banner.jpg" 
            width="791"><BR><IMG height="14" alt="blue line" 
            src="/images/BlueLine.jpg" 
            width="794"></TD></TR>
        <TR vAlign="top" align="center">
          <TD width="77"><A 
            onmouseover="MM_swapImage('Store','','/images/store_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/store/index.php"><IMG height="39" 
            alt="Store" 
            src="/images/store.gif" 
            width="67" border="0" name="Store"></A></TD>
          <TD width="150"><A 
            onmouseover="MM_swapImage('Lucid dreaming','','/images/lucid_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/lucid_dream/index.php"><IMG height="38" 
            alt="Lucid Dreaming" 
            src="/images/lucid.gif" 
            width="141" border="0" name="Lucid dreaming"></A></TD>
          <TD width="124"><A 
            onmouseover="MM_swapImage('Suggestions','','/images/suggestions_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/directions/index.php"><IMG height="38" 
            alt="Suggestions" 
            src="/images/suggestions.gif" 
            width="114" border="0" name="Suggestions"></A></TD>
          <TD width="158"><A 
            onmouseover="MM_swapImage('Supplement','','/images/supplement_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/sup_facts/index.php"><IMG height="38" 
            alt="Supplement Facts" 
            src="/images/supplement.gif" 
            width="153" border="0" name="Supplement"></A></TD>
          <TD width="124"><A 
            onmouseover="MM_swapImage('Testimonials','','/images/testimonial_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/testimonials/index.php"><IMG height="38" 
            alt="Testimonials" 
            src="/images/testimonial.gif" 
            width="114" border="0" name="Testimonials"></A></TD>
          <TD width="76"><A 
            onmouseover="MM_swapImage('FAQs','','/images/faqs_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/faqs/index.php"><IMG height="39" alt="FAQs" 
            src="/images/faqs.gif" 
            width="67" border="0" name="FAQs"></A></TD>
          <TD width="78"><A 
            onmouseover="MM_swapImage('Contact','','/images/contact_over.gif',1)" 
            onmouseout="MM_swapImgRestore()" 
            href="/contact/index.php"><IMG height="38" 
            alt="Contact" 
            src="/images/contact.gif" 
            width="77" border="0" name="Contact"></A></TD></TR>
        <TR vAlign="top" align="center">
          <TD colSpan="7">
