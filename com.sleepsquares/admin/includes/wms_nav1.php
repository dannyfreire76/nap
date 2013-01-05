<?php
// BME WMS
// Page: WMS Navigation Included Functions
// Path/File: /admin/includes/wms_nav1.php
// Version: 1.8
// Build: 1801
// Date: 01-26-2007

function get_parent_page($thisURL, $firstParent="", $currentParent="") {
    global $base_url;

    $where = "ap.admin_page_url = '".$thisURL."'";
    if ( $currentParent != "" ) {
        $where = "ap.admin_page_id = '".$currentParent."'";
    }

    $query = "SELECT ap.* FROM admin_pages ap WHERE ".$where." LIMIT 1";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());
	while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ( $line["parent_id"] == "" ) {
            $parentPage = $line;
        } else {//if there's a parent_id, call recursively until we get to the highest level without parent_id
            return get_parent_page($line["admin_page_url"], $line["admin_page_id"], $line["parent_id"]);
        }
    }
	mysql_free_result($result);
    $results = array($parentPage, $firstParent);
    return $results;
}

function create_nav_dropdowns() {
    global $user_id;
    global $base_url;
    global $URL, $dbh_master;

    if ( $user_id != "" ) {
		echo "
			<script language='Javascript'>
				function changeSite() {
					var post_url = window.location.href;
					if ( post_url.indexOf('?')!=-1 ) {
						post_url = post_url.substring(0, post_url.indexOf('?'));
					}

					var theSelect = $('#wms_site_select').get(0);
					var newSiteKey = theSelect.options[ theSelect.selectedIndex ].value;
					var post_data = {};
					post_data.newSiteKey = newSiteKey;
					";

					foreach ($_REQUEST as $rName=>$rVal) {
						echo "eval('post_data[\"".$rName."\"] = \"".$rVal."\"');";
					}

					echo "
					$.post(post_url, post_data, function(){
						$('#site_load').html('Loading...');
						window.location.href = window.location.href;//same page, but don't do a reload in case form was submitted
					});

				}
			</script>";
        echo '<select name="wms_site_select" id="wms_site_select" onChange="changeSite()">';

		$queryAll = "SELECT * FROM wms_users_sites, sites WHERE user_id='".$user_id."' AND wms_users_sites.site_id=sites.site_id";
		$resultAll = mysql_query($queryAll, $dbh_master) or die("queryAll failed: " . mysql_error());

		while ($lineAll = mysql_fetch_array($resultAll, MYSQL_ASSOC)) {
			echo '<option value="'.$lineAll["site_key_name"].'" ';

			if ($_SESSION['active_site']==$lineAll["site_key_name"]) {
				echo 'selected';
			} else if (!$_SESSION['active_site'] && $lineAll["site_url"]==$_SERVER["HTTP_HOST"]) {
				echo 'selected';
			}

			echo '>'.$lineAll["site_title"].'</option>';
		}

		echo '</select>';

		echo '<span class="white bold" style="font-size: 16px;">&#160;Admin</span>';
		echo '</td>';
		echo '<td align="right" valign="middle" class="mybwmshead" style="padding-left: 40px">';

		$this_page_url = substr( $URL, strrpos($URL, "/")+1 );//TODO: Verify this use of strrpos(), because in PHP5, strrpos() and strripos() now use the entire string as a needle.
        if ( strpos($this_page_url, "?") ) {
            $this_page_url = substr( $this_page_url, 0, strpos($this_page_url, "?") );
        }

        $selectedSection = get_parent_page($this_page_url);

        $selectedParentPage = $selectedSection[0]["admin_page_id"];

        echo "<select name=\"wms_nav_manager\" onChange=\"window.location.href=$(this).val()\">\n";

        echo '<option value="index2.php">Homepage</option>';

        $query = "SELECT * FROM admin_pages WHERE parent_id IS NULL AND display_in_nav='1' ORDER BY sequence";
        echo $query;
        $result = mysql_query($query) or die("Query failed : " . mysql_error());
        while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ( in_array($line["admin_page_id"], $_SESSION["pages_for_this_user"]) ) {
                echo '<option value="'.$line["admin_page_url"].'"';
                if( $selectedParentPage == $line["admin_page_id"] ) { echo " SELECTED"; }
                echo '>'.$line["admin_page_name"].'</option>\n';
            }
        }
        mysql_free_result($result);

        echo "</select>";

        echo '</td><td align="right" valign="middle" class="mybwmshead">';

        if ( $selectedParentPage ) {
            //sub pages:
            echo "<select name=\"wms_nav_page\" onChange=\"window.location.href=$(this).val()\">\n";
            echo '<option value="'.$selectedSection[0]["admin_page_url"].'">'.Homepage.'</option>';

            $query = "SELECT * FROM admin_pages WHERE parent_id='".$selectedParentPage."' AND display_in_nav='1' ORDER BY sequence";
            $result = mysql_query($query) or die("Query failed : " . mysql_error());
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<option value="'.$line["admin_page_url"].'"';
                if( $line["admin_page_id"]==$selectedSection[1] || $line["parent_id"]==$selectedSection[1] ) { echo " SELECTED"; }
                echo '>'.$line["admin_page_name"].'</option>\n';
            }
            mysql_free_result($result);

            echo "</select>";
        }
    }
    ?><script type="text/javascript" src="<?=$base_url?>includes/jquery.js"></script>
    <script type="text/javascript">
	$(function(){
	    var staging=(/\/staging\//i).test(window.location.href);
	    if(staging){
		$('#wms_site_select').after("<big><bold><blink> STAGING </blink></bold></big>");
	    }
	    //$('#wms_site_select').each(function(){this.disabled=true});
	});
    </script>
    <?php
}

//Not using this anymore, just hear to prevent errors
function wms_manager_nav2($manager) {
}

//Not using this anymore, just hear to prevent errors
function wms_page_nav2($manager) {
}

function bug_report($page) {
}

function bug_report2($page) {
}
?>
