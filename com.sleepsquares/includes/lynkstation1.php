<?php
// BME WMS
// Page: LynkStation build link category display
// Version: 1.1
// Build: 1112
// Date: 03-24-2006

function build_category($postion, $colored_boxes, $links_per_cat, $font, $fontcolor) {
	$query = "SELECT name FROM lynkstation_cats WHERE position='$postion' AND name!=''";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$name = $line["name"];

		if($colored_boxes == "1") {
			echo "<tr><td align=\"center\"><img src=\"../images/links_box_top1.gif\" border=\"0\"><br>\n";
			echo "<table border=\"0\" width=\"620\" bgcolor=\"#78D2E6\">\n";
		}
		echo "<tr><td>";
		if($colored_boxes == "1") {
			echo "&nbsp;</td><td colspan=\"3\">";
		}
		echo "<font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>" . $name . "</b></font></td></tr>\n";
		echo "<tr><td>";
		if($colored_boxes == "1") {
			echo "&nbsp;</td><td NOWRAP>&nbsp; &nbsp; &nbsp; &nbsp;</td><td>";
		} else {
			echo "<ul>";
		}
		echo "<font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">\n";
		$query2 = "SELECT title, website_url, description, email, image_url FROM lynkstation_links WHERE approved='1' AND category='$name' ORDER BY modified DESC LIMIT $links_per_cat";
		$result2 = mysql_query($query2) or die("Query failed : " . mysql_error());
		while ($line2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo "<li><a href=\"";
			echo $line2["website_url"];
			echo "\" target=\"_BLANK\">";
			echo $line2["title"];
			echo "</a> - ";
			echo $line2["description"];
			echo "</li>\n";
		}
		mysql_free_result($result2);
		echo "</font>";
		if($colored_boxes == "1") {
			echo "</td><td>&nbsp;";
		} else {
			echo "</ul>";
		}
		echo "</td></tr>\n";
		if($colored_boxes == "1") {
			echo "</table>\n";
			echo "<img src=\"../images/links_box_bot1.gif\" border=\"0\"></td></tr>\n";
			echo "<tr><td>&nbsp;</td></tr>\n";
		}
	}
	mysql_free_result($result);
	
}

?>