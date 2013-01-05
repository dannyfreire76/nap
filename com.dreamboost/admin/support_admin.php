<?php
// BME WMS
// Page: Support Manager Homepage
// Path/File: /admin/support_admin.php
// Version: 1.8
// Build: 1803
// Date: 01-22-2007

header('Content-type: text/html; charset=utf-8');
include_once("./includes/ltimer/ltimer.class.php");
$timer=new LTimer();
include '../includes/main1.php';

$user_id = $_COOKIE["wms_user"];
if(!$user_id) {
	header("Location: " . $base_url . "admin/index.php");
	exit;
}

include './includes/wms_nav1.php';
$manager = "support";
$page = "Support Manager > Homepage";
wms_manager_nav2($manager);
wms_page_nav2($manager);
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

<tr><td align="left"><font size="2">Welcome to the Support Manager, where you can find answers on how to use the entire MyBWMS system or get help from our staff. Below you will find a form you can ask for help from our professional and knowledgeable staff. If you have any questions or problems you can contact us at <a href="mailto:bengler9@optonline.net">bengler9@optonline.net</a> This is our direct email link. It will probably be easier and quicker if you fill-out the form below though.</font></td></tr>

<tr><td>&nbsp;</td></tr>

<?php
//Error Messages
if($error_txt) {
	echo "<tr><td align=\"left\"><font face=\"Verdana\" size=\"3\" color=\"red\">$error_txt</font></td></tr>\n";
	echo "<tr><td>&nbsp;</td></tr>\n";
}
?>
<?php
$site_managers = check_managers_for_site($wms_id);
?>
<tr><td align="left">
	<FORM name="support" Method="POST" ACTION="./support_admin.php" class="wmsform">
	<input type="hidden" name="base_url" value="<?php echo $base_url; ?>">
	<input type="hidden" name="license" value="<?php echo $license; ?>">
	<p>Please complete the form below. Required fields marked <em>*</em></p>
	<fieldset>
		<legend>Please Enter Your Information</legend>
		<ol>
			<li>
				<label for="first_name">Your First Name <em>*</em></label>
				<INPUT type="text" id="first_name" name="first_name" size="30" maxlength="150" value="<?php echo $first_name; ?>" tabindex="1" />
			</li>
			<li>
				<label for="first_name">Your Last Name <em>*</em></label>
				<INPUT type="text" id="last_name" name="last_name" size="30" maxlength="150" value="<?php echo $last_name; ?>" tabindex="2" />
			</li>
			<li>
				<label for="email">E-Mail <em>*</em></label>
				<INPUT type="text" id="email" name="email" size="30" maxlength="150" value="<?php echo $email; ?>" tabindex="3" />
			</li>
			<li>
				<label for="problem_type">Type of Problem <em>*</em></label>
				<select id="problem_type" name="problem_type" tabindex="4">
				<option value="">Please Select</option>
				<option value="presales"<?php if($problem_type == "presales") { echo " SELECTED"; } ?>>Question about a product before purchasing</option>
				<option value="contact"<?php if($problem_type == "contact") { echo " SELECTED"; } ?>>General Contact for Information</option>
				<option value="media"<?php if($problem_type == "media") { echo " SELECTED"; } ?>>Contact and Questions from the Media</option>
				<option value="support"<?php if($problem_type == "support") { echo " SELECTED"; } ?>>Questions or problem with an installed product</option>
				<option value="upgrade"<?php if($problem_type == "upgrade") { echo " SELECTED"; } ?>>Suggestions for feature upgrades to existing products</option>
				<option value="addition"<?php if($problem_type == "addition") { echo " SELECTED"; } ?>>Suggestions for new products to be added to phpWMS</option>
				</select>
			</li>
			<li>
				<label for="product">Regarding Product <em>*</em></label>
				<select id="product" name="product" tabindex="5">
				<option value="">Please Select</option>
				<option value="wms"<?php if($product == "wms") { echo " SELECTED"; } ?>>Main MyBWMS System</option>
				<?php
				if($site_managers['shipping'] == "1") {
					echo "<option value=\"shipping\"";
					if($product == "shipping") { echo " SELECTED"; }
					echo ">Shipping Manager</option>\n";
				}
				if($site_managers['members'] == "1") {
					echo "<option value=\"members\"";
					if($product == "members") { echo " SELECTED"; }
					echo ">Members Manager</option>\n";
				}
				if($site_managers['inventory'] == "1") {
					echo "<option value=\"inventory\"";
					if($product == "inventory") { echo " SELECTED"; }
					echo ">Inventory Manager</option>\n";
				}
				if($site_managers['products'] == "1") {
					echo "<option value=\"products\"";
					if($product == "products") { echo " SELECTED"; }
					echo ">Products Manager</option>\n";
				}
				if($site_managers['content'] == "1") {
					echo "<option value=\"content\"";
					if($product == "content") { echo " SELECTED"; }
					echo ">Content Manager</option>\n";
				}
				if($site_managers['lynkstation'] == "1") {
					echo "<option value=\"lynkstation\"";
					if($product == "lynkstation") { echo " SELECTED"; }
					echo ">LynkStation Manager</option>\n";
				}
				if($site_managers['faqs'] == "1") {
					echo "<option value=\"faqs\"";
					if($product == "faqs") { echo " SELECTED"; }
					echo ">FAQs Manager</option>\n";
				}
				if($site_managers['contact_us'] == "1") {
					echo "<option value=\"contact_us\"";
					if($product == "contact_us") { echo " SELECTED"; }
					echo ">Contact Us Manager</option>\n";
				}
				if($site_managers['testimonials'] == "1") {
					echo "<option value=\"testimonials\"";
					if($product == "testimonials") { echo " SELECTED"; }
					echo ">Testimonials Manager</option>\n";
				}
				if($site_managers['leads'] == "1") {
					echo "<option value=\"leads\"";
					if($product == "leads") { echo " SELECTED"; }
					echo ">Leads Manager</option>\n";
				}
				if($site_managers['retailers'] == "1") {
					echo "<option value=\"retailers\"";
					if($product == "retailers") { echo " SELECTED"; }
					echo ">Retailers Manager</option>\n";
				}
				if($site_managers['search_engine'] == "1") {
					echo "<option value=\"search_engine\"";
					if($product == "search_engine") { echo " SELECTED"; }
					echo ">Search Engine Manager</option>\n";
				}
				if($site_managers['email_lists'] == "1") {
					echo "<option value=\"email_lists\"";
					if($product == "email_lists") { echo " SELECTED"; }
					echo ">Email Lists Manager</option>\n";
				}
				if($site_managers['meta_tag'] == "1") {
					echo "<option value=\"meta_tag\"";
					if($product == "meta_tag") { echo " SELECTED"; }
					echo ">Meta Tags Manager</option>\n";
				}
				if($site_managers['photos'] == "1") {
					echo "<option value=\"photos\"";
					if($product == "photos") { echo " SELECTED"; }
					echo ">Photos Manager</option>\n";
				}
				if($site_managers['surveys'] == "1") {
					echo "<option value=\"surveys\"";
					if($product == "surveys") { echo " SELECTED"; }
					echo ">Surveys Manager</option>\n";
				}
				if($site_managers['polls'] == "1") {
					echo "<option value=\"polls\"";
					if($product == "polls") { echo " SELECTED"; }
					echo ">Polls Manager</option>\n";
				}
				if($site_managers['reports'] == "1") {
					echo "<option value=\"reports\"";
					if($product == "reports") { echo " SELECTED"; }
					echo ">Reports Manager</option>\n";
				}
				if($site_managers['users'] == "1") {
					echo "<option value=\"users\"";
					if($product == "users") { echo " SELECTED"; }
					echo ">MyBWMS Users Manager</option>\n";
				}
				if($site_managers['hosting'] == "1") {
					echo "<option value=\"hosting\"";
					if($product == "hosting") { echo " SELECTED"; }
					echo ">Website Hosting Manager</option>\n";
				}
				if($site_managers['support'] == "1") {
					echo "<option value=\"support\"";
					if($product == "support") { echo " SELECTED"; }
					echo ">Support Manager</option>\n";
				}
				if($site_managers['merchant_acct'] == "1") {
					echo "<option value=\"merchant_acct\"";
					if($product == "merchant_acct") { echo " SELECTED"; }
					echo ">Merchant Account Manager</option>\n";
				}
				?>
				</select>
			</li>
			<li>
				<label for="comments">Detailed Question or Comments <em>*</em></label>
				<TEXTAREA id="comments" name="comments" cols="35" rows="7" tabindex="6"><?php echo $comments; ?></TEXTAREA>
			</li>
			<li>
				<label for="severity">Severity of Situation <em>*</em></label>
				<select id="severity" name="severity" tabindex="7">
				<option value="">Please Select</option>
				<option value="1">Unknown</option>
				<option value="2">Minor</option>
				<option value="3">Important</option>
				<option value="4">Major</option>
				<option value="5">Critical</option>
				</select>
			</li>
			<li class="fm-button">
				<input type="submit" id="support" name="support" value="Request Support">
			</li>
		</ol>
	</fieldset>
	</form>
</td></tr>

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