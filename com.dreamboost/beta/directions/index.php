<?php
// BME WMS
// Page: Directions and Suggestions page
// Path/File: /directions/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: Directions and Suggestions</title>
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

<table border="0" width="95%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style4">Directions and Suggestions</td></tr>

<tr><td align="left" class="style3">Directions for Proper Use</td></tr>

<tr><td align="left" class="style2">Adults take 1-2 tablets before bed.</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="left" class="style3">Helpful Suggestions</td></tr>

<tr><td align="left" class="style2">Maintain a healthy lifestyle, including proper diet, exercise, and good sleeping habits, such as keeping a regular bedtime and setting aside 8 to 9 hours for undisturbed sleep.<br>
<br>
Communicate with others about your dreams, and ask about theirs.  Open discussion can allow for a better understanding of the dreaming experience.<br>
<br>
Relax before bedtime by listening to soothing music, reading a good book, doing yoga, or meditating.  Watching television doesn't count.<br>
<br>
Avoid alcohol, tobacco, and other substances that can interfere with sleep, including caffeine, especially within 4 to 6 hours before bedtime.<br>
<br>
Start a journal where you can record your dreams. Keep this journal within reach for easy access immediately upon waking. Be fluid; Write what comes to mind and don't worry about spelling or grammar. Broken phrases or sketched images are usually enough to trigger your memory and allow full recall later.<br>
<br>
Just before retiring, take three deep breaths and clear your mind one final time. Speak out loud, "I will recognize my dreams. I will react in my dreams. I will remember my dreams." Verbalizing your intentions is a great way to focus your energies.</td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td align="center"><table border="0">

<tr><td align="left" class="style4">Happy Sleeping & Dreaming!</td>

<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

<td align="left" class="style3"><a href="/beta/store/articles/sleep-tips.php">Helpful Suggestions for Better Sleep and Dreaming</a><br>
<a href="/beta/store/articles/lucid-dreaming-dream-recall-tips.php">Helpful Suggestions for Lucid Dreaming and Better Dream Recall</a><br>
<a href="/beta/store/articles/dream-journal-tips.php">Dream Journal Tips</a></td></tr>

</table></td></tr>

<tr><td>&nbsp;</td></tr>

</table>
<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>