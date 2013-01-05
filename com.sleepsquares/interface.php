<?php

include('includes/main1.php');

$action = $_REQUEST["action"];

if ( $action=='set_country' ) {
    $_SESSION["country"]=$_REQUEST["country"];
    echo 'ok';
    exit();
}

?>