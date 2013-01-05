<?php
function buildStore($line, $font, $fontcolor) {
	echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><b>";
	echo $line["store_name_website"];
	echo "</b></td></tr>\n";
	if($line["address1"] != "") { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
		echo $line["address1"];
		echo "</font></td></tr>\n";
	}
	if($line["address2"] != "") { 
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
		echo $line["address2"];
		echo "</font></td></tr>\n";
	}
	echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">";
	echo $line["city"] . ", " . $line["state"] . " " . $line["zip"];
	if($line["country"] != "US") { echo " " . $line["country"]; }
	echo "</font></td></tr>\n";
	if($line["contact_phone_website"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">Phone: ";
		echo $line["contact_phone_website"];
		echo "</font></td></tr>\n";
	}
	if($line["contact_fax_website"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">Fax: ";
		echo $line["contact_fax_website"];
		echo "</font></td></tr>\n";
	}
	if($line["contact_name_website"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><u>Contact</u>: ";
		echo $line["contact_name_website"];
		echo "</font></td></tr>\n";
	}
	if($line["contact_website_website"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">Website: <a href=\"http://";
		echo $line["contact_website_website"];
		echo "\">";
		echo $line["contact_website_website"];
		echo "</a></font></td></tr>\n";
	}
	if($line["contact_email_website"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\">Email: <a href=\"mailto:";
		echo $line["contact_email_website"];
		echo "\">";
		echo $line["contact_email_website"];
		echo "</a></font></td></tr>\n";
	}
	if($line["hours_days_operation"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><u>Hours/Days Open</u>: ";
		echo $line["hours_days_operation"];
		echo "</font></td></tr>\n";
	}
	if($line["items_sold"] != "") {
		echo "<tr><td><font face=\"$font\" color=\"#$fontcolor\" size=\"+1\"><u>Items Sold</u>: ";
		echo $line["items_sold"];
		echo "</font></td></tr>\n";
	}
	echo "<tr><td>&nbsp;</td><tr>\n";
}
?>