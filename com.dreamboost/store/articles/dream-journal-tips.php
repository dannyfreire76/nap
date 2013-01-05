<?php
$newLocation = "http://www.dreamboost.com/articles/article_id/19";
header("HTTP/1.1 301 Moved Permanently", TRUE, 301); 
header("Location: $newLocation");
exit;
?>