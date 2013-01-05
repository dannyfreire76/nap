<?php
// BME WMS
// Page: Footer Homepage Include
// Path/File: /includes/foot1.php
// Version: 1.8
// Build: 1801
// Date: 01-23-2007
/**
 *PAGE FOOTER, CLOSES DATABASES...
 */

if(isset($new_design) && $new_design){
	include $base_path.'includes/foot2.php';
}else{
	include $base_path.'includes/foot.php';
}
?>
