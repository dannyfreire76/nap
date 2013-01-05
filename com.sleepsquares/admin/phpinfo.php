<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>Untitled</title>
</head>

<body>

<?php

include '../includes/main1.php';
include '../includes/common.php';


print_d($GLOBALS,false);
phpinfo();
$dbs = @mysql_list_dbs($dbh) or die("cannot list databases");

?>
<ul>
<?php
for($i=0;$i < mysql_num_rows($dbs);$i++){
	?><li><?=mysql_tablename($dbs,$i)?></li><?php
}
//print_d(mysql_query("DATABASE();"),false);
?>
</ul>

</body>
</html>
