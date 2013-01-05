<?php
// BME WMS
// Page: LynkStation Manage Live Links page
// Path/File: /admin/lynkstation_admin4.php
// Version: 1.8
// Build: 1804
// Date: 01-30-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

$approved = $_POST["approved"];
$title = $_POST["title"];
$website_url = $_POST["website_url"];
$description = $_POST["description"];
$email = $_POST["email"];
$image_url = $_POST["image_url"];
$category = $_POST["category"];
$reciprical_link = $_POST["reciprical_link"];
$reciprical_first_failed = $_POST["reciprical_first_failed"];
$lslinks_id = $_POST["lslinks_id"];

include './includes/wms_nav1.php';
$manager = "lynkstation";
$page = "LynkStation Manager > Manage Live Links";
wms_manager_nav2($manager);
wms_page_nav2($manager);

if($lslinks_id) {
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
	if($approved == "1") { //Approved
		if($title == "") {
			$error_txt .= "Error, you removed the title. There needs to be a title.<br>\n";
		}
		if($website_url == "" || $website_url == "http://") {
			$error_txt .= "Error, you removed the website URL. There needs to be a website URL.<br>\n";
		}
		if($description == "") {
			$error_txt .= "Error, you removed the description. There needs to be a description.<br>\n";
		}
		if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*". "@([a-z0-9]+([\.-][a-z0-9]{1,})+)*$",$email) ){
			$error_txt .= "Error, you removed the email address or the email address is incorrect. There needs to be an email address.<br>\n";
		}
		if($error_txt == "") {
			if($website_dups == '0' && $website_url != "") {
				$website_url3 = rtrim($website_url, "/");
				$website_url4 = rtrim($website_url, "/index.html");
				$website_url5 = rtrim($website_url, "/index.php");
				$website_url6 = rtrim($website_url, "/index.asp");
				$website_url7 = rtrim($website_url, "/index.jsp");
				$query2 = "SELECT lslinks_id, approved, website_url, category FROM lynkstation_links WHERE website_url LIKE '$website_url%' OR website_url='$website_url3' OR website_url='$website_url4' OR website_url='$website_url5' OR website_url='$website_url6' OR website_url='$website_url7'";
				$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
				while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
					$lslinks_id2 = $line2["lslinks_id"];
					$approved2 = $line2["approved"];
					$website_url2 = $line2["website_url"];
					$category2 = $line2["category"];
				}
				mysql_free_result($result2);
				if(isset($website_url2) && $website_url2 != "" && $lslinks_id != $lslinks_id2) {
					$error_txt .= "Error, the Website URL you are submitting has already been submitted to this LynkStation and can not be submitted more than once. ";
					if(isset($approved2) && $approved2 == '0') { $error_txt .= "The Website is in the Approval process and the LynkStation Owner will be processing it shortly.<br>\n"; }
					if(isset($approved2) && $approved2 == '1') { $error_txt .= "The Website has been Approved and is visible on the {$category2} page.<br>\n"; }
					if(isset($approved2) && $approved2 == '2') { $error_txt .= "The Website has been Rejected and will not be listed on this LynkStation.<br>\n"; }
					if(isset($approved2) && $approved2 == '3') { $error_txt .= "The Website is not active or has some other form of a Bad Link.<br>\n"; }
					if(isset($approved2) && $approved2 == '4') { $error_txt .= "The Website is not listed because no Reciprocal Link was provided.<br>\n"; }
					if(isset($approved2) && $approved2 == '5') { $error_txt .= "The Website is not listed because the Reciprocal Link provided is Invalid.<br>\n"; }
				}
			}
			if($reciprocal_dups == '0' && $reciprical_link != "") {
				$reciprical_link3 = rtrim($reciprical_link, "/");
				$reciprical_link4 = rtrim($reciprical_link, "/index.html");
				$reciprical_link5 = rtrim($reciprical_link, "/index.php");
				$reciprical_link6 = rtrim($reciprical_link, "/index.asp");
				$reciprical_link7 = rtrim($reciprical_link, "/index.jsp");
				$query3 = "SELECT lslinks_id, reciprical_link FROM lynkstation_links WHERE reciprical_link LIKE '$reciprical_link%' OR reciprical_link='$reciprical_link3' OR reciprical_link='$reciprical_link4' OR reciprical_link='$reciprical_link5' OR reciprical_link='$reciprical_link6' OR reciprical_link='$reciprical_link7'";
				$result3 = mysql_query($query3) or die("Query failed : " . mysql_error());
				while ($line3 = mysql_fetch_array($result3, MYSQL_ASSOC)) {
					$lslinks_id3 = $line3["lslinks_id"];
					$reciprical_link2 = $line3["reciprical_link"];
				}
				mysql_free_result($result3);
				if(isset($reciprical_link2) && $reciprical_link2 != "" && $lslinks_id != $lslinks_id3) {
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
					if($reciprical_passed == "0") {
						if($reciprical_first_failed == "0000-00-00 00:00:00" || $reciprical_first_failed == "" || $reciprical_first_failed == "0") {
							$reciprical_first_failed = date("Y-m-d H:i:s");
						}
						$error_txt .= "Error, the Reciprocal Link you are submitting does not contain a link to our website. Please check the link and try again.<br>\n";;
					}
				} else {
					if($reciprical_first_failed == "0000-00-00 00:00:00" || $reciprical_first_failed == "" || $reciprical_first_failed == "0") {
						$reciprical_first_failed = date("Y-m-d H:i:s");
					}
					$error_txt .= "Error, the Reciprocal Link you are submitting does not contain a link to our website. Please check the link and try again.<br>\n";;
				}
				$reciprical_last_checked = date("Y-m-d H:i:s");
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

				if($link_passed == "0") {
					$error_txt .= "Error, the Website URL you are submitting can not be found. Please check the link and try again.<br>\n";
				}
			} else {
				$error_txt .= "Error, the Website URL you are submitting can not be found. Please check the link and try again.<br>\n";
			}
			$link_last_checked = date("Y-m-d H:i:s");
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
	}
	
	//If no Errors, Update DB
	if($error_txt == "") {
		$query = "UPDATE lynkstation_links SET approved='$approved', title='$title', website_url='$website_url', description='$description', email='$email', category='$category', reciprical_link='$reciprical_link', reciprical_first_failed='$reciprical_first_failed', reciprical_last_checked='$reciprical_last_checked', reciprical_passed='$reciprical_passed', link_last_checked='$link_last_checked', link_passed='$link_passed' WHERE lslinks_id='$lslinks_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());

		//Send User Email Message
		if($approved == "1") { //Approved
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
		
		} elseif($approved == "2") { //Rejected
			$query = "SELECT content, subject, email FROM lynkstation_emails WHERE lsemails_id='3'";
			$result = mysql_query($query) or die("Query failed : " . mysql_error());
			while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$content = $line["content"];
				$subject = $line["subject"];
				$email_tmp = $line["email"];
			}
			mysql_free_result($result);

			$email_str = "Dear " . $title . ",\n\n";
			$email_str .= $content;
			$email_str .= "\n\n";
			
			$email_subj = $subject;
			$email_from = "FROM: " . $email_tmp;
			mail($email, $email_subj, $email_str, $email_from);
		}
	} elseif($error_txt != "" && $reciprical_passed == "0") {
		$query = "UPDATE lynkstation_links SET reciprical_first_failed='$reciprical_first_failed', reciprical_last_checked='$reciprical_last_checked', reciprical_passed='$reciprical_passed', link_last_checked='$link_last_checked', link_passed='$link_passed' WHERE lslinks_id='$lslinks_id'";
		$result = mysql_query($query) or die("Query failed : " . mysql_error());
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

<tr><td align="left"><font size="2">These are the website links live on the site. You can edit any of them at any time. To move a website from one category to another you would change the category on the website listing below and then click edit for it's entry. To remove a website from the page simply reject the website below and again click edit for it's entry.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font size=\"2\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}

echo "<tr><form action=\"./lynkstation_admin4.php\" method=\"POST\"><td align=\"left\"><font size=\"2\"><b>Category:</b> </font><select name=\"category\">";
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
		$categories[] = $line["name"];
	}
	mysql_free_result($result);
echo "</select> <input type=\"submit\" name=\"change\" value=\"Change\"></td></form></tr>\n";
echo "<tr><td>&nbsp;</td></tr>\n";
?>

<tr><td align="left"><font size="2"><table border="0">
<tr><td><font face="Arial" size="+1"><b>Status</b></font></td><td><font face="Arial" size="+1"><b>Title</b></font></td><td><font face="Arial" size="+1"><b>Website URL</b></font></td><td><font face="Arial" size="+1"><b>Description</b></font></td><td><font face="Arial" size="+1"><b>Email</b></font></td><td><font face="Arial" size="+1">&nbsp;</font></td></tr>
<?php
if($category == "") { $category = $categories[0]; }
$query = "SELECT lslinks_id, approved, title, website_url, description, email, reciprical_link, reciprical_first_failed FROM lynkstation_links WHERE approved='1' AND category='$category' ORDER BY modified DESC";
$result = mysql_query($query) or die("Query failed : " . mysql_error());
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo "<form action=\"./lynkstation_admin4.php\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"reciprical_first_failed\" value=\"";
	echo $line["reciprical_first_failed"];
	echo "\">\n";
	echo "<tr><td><SELECT name=\"approved\">";
	echo "<option value=\"0\"";
	if($line["approved"] == "0") { echo " SELECTED"; }
	echo ">To review</option>";
	echo "<option value=\"1\"";
	if($line["approved"] == "1") { echo " SELECTED"; }
	echo ">Approved</option>";
	echo "<option value=\"2\"";
	if($line["approved"] == "2") { echo " SELECTED"; }
	echo ">Rejected</option>";
	echo "<option value=\"3\"";
	if($line["approved"] == "3") { echo " SELECTED"; }
	echo ">Bad Link</option>";
	echo "<option value=\"4\"";
	if($line["approved"] == "4") { echo " SELECTED"; }
	echo ">No Reciprocal</option>";
	echo "<option value=\"5\"";
	if($line["approved"] == "5") { echo " SELECTED"; }
	echo ">Bad Reciprocal</option>";
	echo "</select></td><td><input type=\"text\" name=\"title\" size=\"18\" maxlength=\"150\" value=\"";
	echo $line["title"];
	echo "\"></td><td><input type=\"text\" name=\"website_url\" size=\"18\" maxlength=\"150\" value=\"";
	echo $line["website_url"];
	echo "\"></td><td><input type=\"text\" name=\"description\" size=\"22\" maxlength=\"255\" value=\"";
	echo $line["description"];
	echo "\"></td><td><input type=\"text\" name=\"email\" size=\"14\" maxlength=\"100\" value=\"";
	echo $line["email"];
	echo "\"></td><input type=\"hidden\" name=\"lslinks_id\" value=\"";
	echo $line["lslinks_id"];
	echo "\"><td rowspan=\"2\"><input type=\"submit\" value=\"Edit\"></td></tr>\n";
	echo "<tr><td colspan=\"3\"><font face=\"Arial\" size=\"+1\">Category: </font><select name=\"category\">";
	$query2 = "SELECT name FROM lynkstation_cats WHERE name!='' ORDER BY position";
	$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
	while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
		echo "<option value=\"";
		echo $line2["name"];
		echo "\"";
		if($category == $line2["name"]) { echo " SELECTED"; }
		echo ">";
		echo $line2["name"];
		echo "</option>";
	}
	mysql_free_result($result2);
	echo "</select></td><td colspan=\"2\"><font face=\"Arial\" size=\"+1\">Reciprocal Link: </font><input type=\"text\" name=\"reciprical_link\" size=\"21\" maxlength=\"150\" value=\"";
	echo $line["reciprical_link"];
	echo "\"></td></tr></form>\n";
	echo "<tr><td colspan=\"7\">Displayed on Site as: <li><a href=\"";
	echo $line["website_url"];
	echo "\" TARGET=\"_BLANK\">";
	echo $line["title"];
	echo "</a> - ";
	echo $line["description"];
	echo "</li></td></tr>\n";
}
mysql_free_result($result);
?>
</td></tr></table>
</font></td></tr>

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