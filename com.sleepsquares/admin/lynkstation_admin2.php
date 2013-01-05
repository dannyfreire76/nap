<?php
// BME WMS
// Page: LynkStation Add Link page
// Path/File: /admin/lynkstation_admin2.php
// Version: 1.8
// Build: 1804
// Date: 01-29-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$submit = $_POST["submit"];
$title = $_POST["title"];
$website_url = $_POST["website_url"];
$description = $_POST["description"];
$email = $_POST["email"];
$image_url = $_POST["image_url"];
$category = $_POST["category"];
$reciprical_link = $_POST["reciprical_link"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Add Links";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($submit != "") {
	//Validate
	$query = "SELECT website_dups, reciprocal_dups, title, url, description FROM lynkstation_main";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$website_dups = $line["website_dups"];
		$reciprocal_dups = $line["reciprocal_dups"];
		$my_title = $line["title"];
		$my_url = $line["url"];
		$my_description = $line["description"];
	}
	mysql_free_result($result);

	$error_txt = "";
	if($title == "") { $error_txt .= "The Title field is blank. Please complete this field.<br>\n"; }
	if($website_url == "" || $website_url == "http://") { $error_txt .= "The Website URL field is blank. Please complete this field.<br>\n"; }
	if($description == "") { $error_txt .= "The Description field is blank. Please complete this field.<br>\n"; }
	if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
 		$error_txt .= "The Email field is blank or entered incorrectly. Please complete this field.<br>\n";
 	}
	if($error_txt == "") {
		if($website_dups == '0' && $website_url != "") {
			$website_url3 = rtrim($website_url, "/");
			$website_url4 = rtrim($website_url, "/index.html");
			$website_url5 = rtrim($website_url, "/index.php");
			$website_url6 = rtrim($website_url, "/index.asp");
			$website_url7 = rtrim($website_url, "/index.jsp");
			$query2 = "SELECT approved, website_url, category FROM lynkstation_links WHERE website_url LIKE '$website_url%' OR website_url='$website_url3' OR website_url='$website_url4' OR website_url='$website_url5' OR website_url='$website_url6' OR website_url='$website_url7'";
			$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
			while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$approved = $line2["approved"];
				$website_url2 = $line2["website_url"];
				$category2 = $line2["category"];
			}
			mysql_free_result($result2);
			if(isset($website_url2) && $website_url2 != "") {
				$error_txt .= "Error, the Website URL you are submitting has already been submitted to this LynkStation and can not be submitted more than once. ";
				if(isset($approved) && $approved == '0') { $error_txt .= "The Website is in the Approval process and the LynkStation Owner will be processing it shortly.<br>\n"; }
				if(isset($approved) && $approved == '1') { $error_txt .= "The Website has been Approved and is visible on the {$category2} page.<br>\n"; }
				if(isset($approved) && $approved == '2') { $error_txt .= "The Website has been Rejected and will not be listed on this LynkStation.<br>\n"; }
				if(isset($approved) && $approved == '3') { $error_txt .= "The Website is not active or has some other form of a Bad Link.<br>\n"; }
				if(isset($approved) && $approved == '4') { $error_txt .= "The Website is not listed because no Reciprocal Link was provided.<br>\n"; }
				if(isset($approved) && $approved == '5') { $error_txt .= "The Website is not listed because the Reciprocal Link provided is Invalid.<br>\n"; }
			}
		}
		if($reciprocal_dups == '0' && $reciprical_link != "") {
			$reciprical_link3 = rtrim($reciprical_link, "/");
			$reciprical_link4 = rtrim($reciprical_link, "/index.html");
			$reciprical_link5 = rtrim($reciprical_link, "/index.php");
			$reciprical_link6 = rtrim($reciprical_link, "/index.asp");
			$reciprical_link7 = rtrim($reciprical_link, "/index.jsp");
			$query3 = "SELECT reciprical_link FROM lynkstation_links WHERE reciprical_link LIKE '$reciprical_link%' OR reciprical_link='$reciprical_link3' OR reciprical_link='$reciprical_link4' OR reciprical_link='$reciprical_link5' OR reciprical_link='$reciprical_link6' OR reciprical_link='$reciprical_link7'";
			$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
			while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
				$reciprical_link2 = $line3["reciprical_link"];
			}
			mysql_free_result($result3);
			if(isset($reciprical_link2) && $reciprical_link2 != "") {
				$error_txt .= "Error, the Reciprocal Link you are submitting has already been submitted to this LynkStation and can not be submitted more than once.<br>\n";
			}
		}
	}
	
	if($error_txt == "") {
		if($reciprical_link != "") {
			// Validate Reciprocal Link
			$reciprical_passed = "0";
			$my_url = rtrim($my_url, "/");
			$handle = @fopen($reciprical_link, "r");
			if($handle) {
				while (!feof($handle)) {
					$buffer = fgets($handle, 4096);
					$pos = strpos($buffer, $my_url);
					if ($pos === false) {
					} else {
						$reciprical_passed = "1";
					}
				}
				fclose($handle);
				$reciprical_last_checked = date("Y-m-d H:i:s");
				if($reciprical_passed == "0") {
					$error_txt .= "Error, the Reciprocal Link you are submitting does not contain a link to our website. Please check the link and try again.<br>\n";;
				}
			} else {
				$error_txt .= "Error, the Reciprocal Link you are submitting does not contain a link to our website. Please check the link and try again.<br>\n";;
			}
		}
		// Validate Link
		$link_passed = "0";
		$handle = @fopen($website_url, "r");
		if($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if($buffer != "") {
					$link_passed = "1";
				}
			}
			fclose($handle);
			$link_last_checked = date("Y-m-d H:i:s");
			if($link_passed == "0") {
				$error_txt .= "Error, the Website URL you are submitting can not be found. Please check the link and try again.<br>\n";
			}
		} else {
			$error_txt .= "Error, the Website URL you are submitting can not be found. Please check the link and try again.<br>\n";
		}
	}

	// Validate using Filters
	$query = "SELECT filter FROM lynkstation_filters WHERE status='1'";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$filters[] = $line["filter"];
	}
	mysql_free_result($result);
	
	$filters_count = count($filters);
	
	if($error_txt == "") {
		// Validate Title Filter		
		$title_match = strtolower($title);
		for($i=0;$i<$filters_count;$i++) {
			$pos = strpos($title_match, $filters[$i]);
			if ($pos === false) {
			} else {
			   $error_txt .= "Error, the Title field contains a banned word. Your submission is rejected.<br>\n";
			   break;
			}
		}
	}
	if($error_txt == "") {
		// Validate Description Filter
		$description_match = strtolower($description);
		for($i=0;$i<$filters_count;$i++) {
			$pos = strpos($description_match, $filters[$i]);
			if ($pos === false) {
			} else {
			   $error_txt .= "Error, the Description field contains a banned word. Your submission is rejected.<br>\n";
			   break;
			}
		}
	}
	if($error_txt == "") {
		// Validate Website URL Filter
		$website_url_match = strtolower($website_url);
		for($i=0;$i<$filters_count;$i++) {
			$pos = strpos($website_url_match, $filters[$i]);
			if ($pos === false) {
			} else {
			   $error_txt .= "Error, the Website URL field contains a banned word. Your submission is rejected.<br>\n";
			   break;
			}
		}
	}
	if($error_txt == "") {
		// Validate Reciprocal Link Filter
		$reciprical_link_match = strtolower($reciprical_link);
		for($i=0;$i<$filters_count;$i++) {
			$pos = strpos($reciprical_link_match, $filters[$i]);
			if ($pos === false) {
			} else {
			   $error_txt .= "Error, the Reciprocal Link field contains a banned word. Your submission is rejected.<br>\n";
			   break;
			}
		}
	}
	
	//If no Errors, Update DB
	if($error_txt == "" && $link_passed == "1") {
		$now = date("Y-m-d H:i:s");
		$query = "INSERT INTO lynkstation_links SET created='$now', approved='1', title='$title', website_url='$website_url', description='$description', email='$email', image_url='$image_url', category='$category', reciprical_link='$reciprical_link', reciprical_last_checked='$reciprical_last_checked', reciprical_passed='$reciprical_passed', link_last_checked='$link_last_checked', link_passed='$link_passed'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		$query = "SELECT content, subject, email FROM lynkstation_emails WHERE lsemails_id='2'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$content = $line["content"];
			$subject = $line["subject"];
			$email_tmp = $line["email"];
		}
		mysql_free_result($result);

		$query = "SELECT position FROM lynkstation_cats where name='$category'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$position = $line["position"];
		}
		mysql_free_result($result);

		$email_str = "Dear " . $title . ",\n\n";
		$email_str .= $content;
		$email_str .= "\n\n";
		
		$email_str .= "Your website is listed in the " . $category . " category at " . $base_url;
		$email_str .= "links/links" . $position . ".php\n";
		$email_str .= "Thank you for exchanging links with us.\n\n";

		$email_subj = $subject;
		$email_from = "FROM: " . $email_tmp;
		mail($email, $email_subj, $email_str, $email_from);

		$title = "";
		$website_url = "";
		$description = "";
		$email = "";
		$image_url = "";
		$category = "";
		$reciprical_link = "";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>MyBWMS @ <?php echo $website_title.": ".$page; ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/admin/includes/wmsform.css">
<script type="text/javascript" src="/includes/jquery.js"></script>
<script type="text/javascript" src="/includes/wmsform.js"></script>
</head>
<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<div align="center">

<?php
include './includes/head_admin3.php';
?>

<table border="0" width="97%">

<tr><td>&nbsp;</td></tr>

<tr><td align="left"><font size="2">Welcome to LynkStation, where you manage the Links section of your website. On this page you add links to your LynkStation. Since you are adding the links they are automatically approved.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<form action="./lynkstation_admin2.php" method="POST">
<tr><td align="left"><font size="2"><table border="0">
<tr><td><font face="Arial" size="+1">Title:</font></td><td><input type="text" name="title" size="30" maxlength="150" value="<?php echo $title; ?>"></td></tr>
<?php if($website_url == "") { $website_url = "http://"; } ?>
<tr><td><font face="Arial" size="+1">Website URL:</font></td><td><input type="text" name="website_url" size="30" maxlength="150" value="<?php echo $website_url; ?>"> <font face="Arial" size="-1">(Must start with http://)</font></td></tr>
<tr><td><font face="Arial" size="+1">Description:</font></td><td><input type="text" name="description" size="30" maxlength="255" value="<?php echo $description; ?>"></td></tr>
<tr><td><font face="Arial" size="+1">Email:</font></td><td><input type="text" name="email" size="30" maxlength="100" value="<?php echo $email; ?>"> <font face="Arial" size="-1">(Not shown on website)</font></td></tr>
<!--<tr><td><font face="Arial" size="+1">Image URL:</font></td><td><input type="text" name="image_url" size="30" maxlength="150" value="<?php echo $image_url; ?>"></td></tr>-->
<tr><td><font face="Arial" size="+1">Category:</font></td><td><select name="category">
<?php
$query = "SELECT name FROM lynkstation_cats WHERE name!='' ORDER BY position";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<option value=\"";
	echo $line["name"];
	echo "\"";
	if($category == $line["name"]) { echo " SELECTED"; }
	echo ">";
	echo $line["name"];
	echo "</option>";
}
mysql_free_result($result);
?>
</select></td></tr>
<tr><td><font face="Arial" size="+1">Reciprocal Link:</font></td><td><input type="text" name="reciprical_link" size="30" maxlength="150" value="<?php echo $reciprical_link; ?>"> <font face="Arial" size="-1">(Not shown on website)</font></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td></tr>
</form>
</table></td></tr>

<tr><td>&nbsp;</td></tr>
</table>
<?php
include './includes/foot_admin1.php';
footer_admin($timer->getTTMS());
mysql_close($dbh);
?>
</div>
</body>
</html>