<?php
include '../includes/main1.php';
include $base_path.'includes/reps.php';

checkRepLogin();

include $base_path.'includes/invoice.php'; //doing it like this so pages outside of admin can use it
?>