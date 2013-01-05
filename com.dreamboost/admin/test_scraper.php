<?
require_once ("./includes/phpscraper/class_scraper.php");
include '../includes/main1.php';

// Get html --------
$scraper = new scraper();
$s_url = 'http://www.barleans.com/locator.asp';

$postvars = 'ddlState='.$state.'&go=go';

$s_html = $scraper->postPage($s_url, $postvars, $s_url, 50000);
$s_html = $scraper->extract2($s_html, '<table width="100%">', 'Copyright', '/\<td class="arialcontent"[^>]*\>(.*)\<br>\<br>/siU', '/\<b\>(.*)\<\/b>\<br>(.*)\<br>(.*),\s([A-Za-z]{2})&nbsp;(.*)\<\/td>\<td width="22%" valign="TOP">(.*)$/siU');



/*
$b_result = $scraper->dup_check($a_result);
if($b_result > 0) {
	$c_result = $scraper->leads_create($a_result);
	
	if($c_result > 0) {
		echo "Lead created\n";
	} else {
		echo "Lead not created\n";
	}
}
*/
?>