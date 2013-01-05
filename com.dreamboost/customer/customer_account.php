<?php

include '../includes/main1.php';
include $base_path.'includes/customer.php';

checkCustomerLogin();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $website_title; ?>: My <?php echo $website_title; ?></title>

<?php
include $base_path.'includes/meta1.php';
?>
</head>

<body>
<div align="center">

<?php
include $base_path.'includes/head1.php';
?>

<table border="0" width="677">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font face="<?php echo $font; ?>" color="#<?php echo $fontcolor; ?>" size="+2">My <?php echo $website_title; ?> Account</font></td></tr>

</table>
<ul>
    <li class="disc"><a href="<?=$base_url?>customer/customer_history.php">Order History</a></li>
    <li class="disc"><a href="<?=$base_url?>questionnaires/">Questionnaires</a></li>
</ul>
<?php
include $base_path.'includes/foot1.php';
?>

</div>
</body>
</html>