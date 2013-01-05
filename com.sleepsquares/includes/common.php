<?php
/**
 * Common utility functions
 *
 * TODO: Replace bad calendar component (no pre-1970 dates supported!) with robust, easy-to-use javascript-based component.
 * TODO: Add new multi-month, multi-select, range-constrained calendar component (extension of component above).
 * TODO: Add appropriate currency formatting for float values.
 * TODO: Scan current PHP file's HTML form (but not from the submitted form, which might have been user-manipulated!) for named values, ensure that appropriate $_POST values are defined.
 *
 **/

//-----------------------------------------------------
// Simple function to print debug statements
function print_d($data,$hidden = true){

    if($hidden){
	print "<!--\n";
    }

    if(is_array($data)){
    	print "<div align='left'><pre>";
        print_r($data);
        print "</pre></div>";
    }
	elseif(strstr($data,'xml')){
		$data = str_replace('<','&lt;',$data);
		$data = str_replace('>','&gt;',$data);
		print "<pre>\n\n$data\n\n</pre>";
	}
    else{
    	print "<div align='left'><pre>";
        print $data;
        print "</pre></div>";
    }
    if($hidden){
	print "\n-->";
    }
}

function writeToLog($data){
	if(is_array($data)){
		$data = print_r($data,true);
	}
	if (!function_exists('file_put_contents')) {
		$f = @fopen("debug.txt", 'a');
		if(!$f){
			return false;
		}
		else{
			$bytes = fwrite($f, $data);
			fclose($f);
			return $bytes;
		}
	}
	else{
		file_put_contents("debug.txt",$data,FILE_APPEND);
	}
}
function br2nl($string){
	$return=eregi_replace('<br[[:space:]]*/?'.'[[:space:]]*>',chr(13).chr(10),$string);
	return $return;
}
function lc($string){
	return strtolower($string);
}
function uc($string){
	return strtoupper($string);
}
function is_hash($var){
	if(!is_array($var)){
		return false;
	}
	return array_keys($var) !== range(0,sizeof($var)-1);
}



// ----------------------------------------------------
// generates a simple list of fields for a select command
function createSqlFieldList($data,$withBreaks = false){
	print "<pre>\n";
	if($withBreaks){
		print '`' . join("`,\n`",array_keys($data)) . '`';
	}
	else{
		print '`' . join('`,`',array_keys($data)) . '`';
	}
	print "</pre>\n";
}

// ----------------------------------------------------
// Cleans up REQUEST variables from SQL injections
function cleanupRequest(){

	// remove get and post since we use request exclusively
	$_POST = array();
	$_GET = array();
	// save request vars in array and remove them from the
	// global request variable for now. They get put back
	// in the cleanupInput routine.
	$vars = $_REQUEST;
	$_REQUEST = array();
	// run cleanUpInput routine.
	$_REQUEST = cleanupInput($vars);

}

//---------------------------------------------------------------
// recursive clean up the keys and values of REQUEST inputs
function cleanupInput($input){
	$output = array();
	foreach($input as $key=>$value){
		$key = escape_string($key);
		if(is_array($value)){
			$output[$key] = cleanUpInput($value);
		}
		else{
			$output[$key] = escape_string($value);
		}
	}
	return $output;
}

//---------------------------------------------------------------
// escape a string before using in a database query
function escape_string($string){

	global $isAdmin,$dbh_master;

	// if we are not an admin, clean up all possible SQL injections
	if(!$isAdmin){
		$string = trim(preg_replace("/<script/i","",$string));
		$string = trim(str_replace(">","",$string));
		$string = trim(str_replace(";","",$string));
		$string = trim(str_replace('--',"",$string));
		$string = trim(str_replace('exec',"",$string));
		$string = trim(preg_replace("/[\"\\\&\`\;\*\^\!\(\)\{\}\<\>\=]/","",$string));
		$string = nl2br($string);
	}

	// if magic quotes are on, no need to further cleanup
	if(get_magic_quotes_gpc()){
		return $string;
	}

	// check mysql escape version and use that to escape string
	if(function_exists('mysql_real_escape_string')){
		$string = mysql_real_escape_string($string,$dbh_master);
	}
	elseif(function_exists('mysql_escape_string')){
		$string = mysql_escape_string($string,$dbh_master);
	}
	else{
		// if all else fails, use addslashes
		$string = addslashes($string);
	}

	return $string;
}

function cleanPhone($phone){
	return preg_replace("/[^0-9]/","",$phone);
}



//---------------------------------------------------------------
// create an html table from an array of data
function createTable($headers,
					 $data,
					 $attributes = array(),
					 $column_format = array(),
					 $extra_columns = array(),
					 $primary_key = null){

	$table = null;

	// sets table attributes like style, cellpadding, etc.
	$table .= '<table';
	foreach($attributes as $att=>$value){
		$table .= " $att=\"$value\"";
	}
	$table .= ">\n";
	$table .= "\t<thead>\n";
	$table .= "\t\t<tr>\n";

	// write out the headers. The makeLabel function
	// below will format the field names.
	foreach($headers as $i=>$label){
		if($label != $primary_key){
			$label = makeLabel($label);
			$table .= "\t\t\t<th>$label</th>\n";
		}
	}
	// if we have extra columns, write headers for them.
	foreach($extra_columns as $i=>$details){
		@list($title,$icon,$func) = $details;
		$table .= "\t\t\t<th style=\"text-align:center\">$title</th>\n";
	}

	$table .= "\t\t</tr>\n";
	$table .= "\t</thead>\n";
	$table .= "\t<tbody>\n";

	// foreach line in the data, write a table row
	foreach($data as $j=>$fields){

		// this allows us to toggle order history icons
		$haveOrders = false;
		if(!empty($fields['order_count']) && $fields['order_count'] > 0){
			$haveOrders = true;
		}

		// set the id of the row to the primary key value if applicable.
		// this way we can get that id using jquery later.
		if($primary_key){
			$id = $fields[$primary_key];
			$table .= "\t\t<tr id=\"$id\">\n";
		}
		else{
			$table .= "\t\t<tr>\n";
		}

		$detail = array();
		$cols = 0;

		// Foreach field in the data, create a cell. You can also
		// assign center and right alignments for a given cell using
		// the column_format input array.
		foreach($fields as $key=>$val){

			if($key != $primary_key){
				if(trim(strtolower($key)) == 'details'){
					$detail = $val;
				}
				else{
					$val = stripslashes($val);

					// column format array sets alignment
					if(!empty($column_format[$key])){
						$style = $column_format[$key];
						$table .= "\t\t\t<td style=\"text-align:$style;\">$val</td>\n";
					}
					else{
						$table .= "\t\t\t<td>$val</td>\n";
					}
				}
				// save a count of columns (colspan) when we add detail below
				$cols++;
			}
		}

		// This allows you to add columns and icons to the table row
		// for things like list, edit, delete, etc.
		// The {id} gets replaced with the current id.
		// The inputs for this are: title, icon, function
		foreach($extra_columns as $i=>$details){

			@list($title,$icon,$func) = $details;

			$func = str_replace('{id}',$id,$func);

			$table .= "\t\t\t<td>";

			// special for history screen:
			// do not show a history icon if there is no history to be displayed
			if(!$haveOrders && strstr(strtolower($title),'history')){
				$table .= "&nbsp;";
			}
			else{
				// otherwise, create a link based on the inputs
				$table .= "<a href='#' onclick=\"$func\">\n";
				$table .= "<img class=\"centeredImage\" height='16' width='16' src=\"/images/grid/$icon\" border='0' title=\"$title\">\n";
				$table .= "</a>\n";
			}
			$table .= "</td>\n";
			// update column count for details below
			$cols++;
		}
		$table .= "\t\t</tr>\n";

		if(count($detail) > 0){
			// this creates a sub-table or detail table by recursively
			// generating the sub table using this function.
			$table .= "\t\t<tr><td class=\"hidden_detail\" colspan=\"$cols\">\n";

			$table .= createTable(array_keys($detail[0]),$detail,
							  array("class"=>'detail_grid',"border"=>'1',"cellpadding"=>'2',"cellspacing"=>'0'),
							  $column_format);

			$table .= "\t\t</td></tr>\n";
		}
	}

	$table .= "\t</tbody>\n";
	$table .= "</table>\n";
	return $table;
}


function makeLabel($fld,$toUpper = true){
	$words = explode('_',$fld);
	if(count($words) > 1){
		if($toUpper){
			$label = ucwords(strtolower(join(" ",$words)));
		}
		else{
			$label = join(" ",$words);
		}
	}
	elseif($toUpper){
		$label = ucwords($fld);
	}
	else{
		$label = $fld;
	}
	return $label;
}

// Creates a select box from a 2 column list of data
// --------------------------------------------------------------
function makeSelectBox($fldName,$data,$default = null,$attributes = array()){

	if(count($data) == 0){
		return "Missing data for makeSelectBox ($fldName)";
	}

	$selected = null;
	if(!empty($default) && !is_null($default)){
		$selected = $default;
	}
	elseif(!empty($_REQUEST[$fldName])){
		$selected = $_REQUEST[$fldName];
	}

	$select = "<select id=\"$fldName\" name=\"$fldName\"";

	foreach($attributes as $att=>$val){
		$select .= " $att=\"$val\"";
	}
	$select .= ">\n";

	//$select .= "\t<option value=\"\"></option>\n";

	foreach($data as $i=>$fields){

		if(is_hash($fields)){
			$fields = array_values($fields);
		}

		$value = $fields[0];
		$name = $fields[1];
		if(!is_null($selected) && $value == $selected){
			$select .= "\t<option value=\"$value\" selected>$name</option>\n";
		}
		else{
			$select .= "\t<option value=\"$value\">$name</option>\n";
		}
	}
	$select .= "</select>\n";
	return $select;
}

// Creates radio buttons from a 2 column list of data
// --------------------------------------------------------------
function makeRadioButtons($fldName,$data,$default = null,$attributes = array(),$useBreaks = false){

	if(count($data) == 0){
		return "Missing data for makeRadioButtons ($fldName)";
	}

	$checked = null;
	if(!empty($default) && !is_null($default)){
		$checked = $default;
	}
	elseif(!empty($_REQUEST[$fldName])){
		$checked = $_REQUEST[$fldName];
	}

	$attribs = null;
	foreach($attributes as $att=>$val){
		$attribs .= " $att=\"$val\"";
	}

	$radios = array();

	foreach($data as $i=>$fields){
		$value = $fields[0];
		$text = $fields[1];
		if(!is_null($checked) && $value == $checked){
			$radios[] = "<input type=\radio\" name=\"$fldName\" value=\"$value\" checked=\"true\"$attribs/> $text";
		}
		else{
			$radios[] = "<input type=\radio\" name=\"$fldName\" value=\"$value\"$attribs/> $text";
		}
	}
	if(count($radios) > 0){
		if($useBreaks){
			return join("<br />\n",$radios);
		}
		else{
			return join("\n",$radios);
		}
	}
	return "Unable to create radio Buttons using the supplied data";
}

// Creates checkboxes from a 2 column list of data
// --------------------------------------------------------------
function makeCheckBoxes($fldName,$data,$default = null,$attributes = array(),$useBreaks = false){

	if(count($data) == 0){
		return "Missing data for makeCheckBoxes ($fldName)";
	}

	$checked = null;
	if(!empty($default) && !is_null($default)){
		$checked = $default;
	}
	elseif(!empty($_REQUEST[$fldName])){
		$checked = $_REQUEST[$fldName];
	}

	$attribs = null;
	foreach($attributes as $att=>$val){
		$attribs .= " $att=\"$val\"";
	}

	$boxes = array();

	foreach($data as $i=>$fields){
		$value = $fields[0];
		$text = $fields[1];
		if(!is_null($checked) && $value == $checked){
			$boxes[] = "<input type=\checkbox\" name=\"$fldName"."[]"."\" value=\"$value\" checked=\"true\"$attribs/> $text";
		}
		else{
			$boxes[] = "<input type=\checkbox\" name=\"$fldName"."[]"."\" value=\"$value\"$attribs/> $text";
		}
	}
	if(count($boxes) > 0){
		if($useBreaks){
			return join("<br />\n",$boxes);
		}
		else{
			return join("\n",$boxes);
		}
	}
	return "Unable to create checkboxes using the supplied data";
}

	// --------------------------------------------------------------
    function getPageBody($template){

            // Pull out the body text
        $bodymatches = array();
        $match = preg_match("/<body[^>]*>(.*)<\/body>/i",$template,$bodymatches);
        if(isset($bodymatches[1])){
            $body = $bodymatches[1];
        }
        else{
            $lines = explode("\n",$template);
            $body = "";
            $start = false;

            foreach($lines as $line){
                if(stristr($line,"<body")){
                    $start = true;
                    continue;
                }
                if(stristr($line,"</body")){
                    $start = false;
                    break;
                }
                if($start){
                    $body .= "$line\n";
                }
            }
        }

    return $body;
    }

	function formatUSDate($date){
		list($y,$m,$d) = explode('-',$date);
		return "$m/$d/$y";
	}


	// add onchange to this to fill the box back in.

	function makeCalendar($mid = "month", $did = "day", $yid = "year", $mval, $dval, $yval){

		if(empty($mval)) $mval = date("m");
		if(empty($dval)) $dval = date("d");
		if(empty($yval)) $yval = date("Y");

		$startyear = date("Y") - 5;
		$endyear = date("Y") + 5;

		if($yval == '0000'){
			$startyear = '1970';
		}

		$months = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
		$out = "<select name='$mid' id='$mid'>\n";
		foreach($months as $val => $text){
			if($val == $mval){
				$out .= "\t<option value='$val' selected>$text</option>\n";
			}
			else{
				$out .= "\t<option value='$val'>$text</option>\n";
			}
		}
		$out .= "</select>\n";

		$out .= "<select name='$did' id='$did'>\n";

		for($i = 1; $i <= 31; $i++){
			if($i < 10){
				$i = '0'.$i;
			}
			if(intval($i) == intval($dval)){
				$out .= "\t<option value='$i' selected>$i</option>\n";
			}
			else{
				$out .= "\t<option value='$i'>$i</option>\n";
			}
		}
		$out .= "</select>\n";

		$out .= "<select name='$yid' id='$yid'>\n";

		for($i = $startyear; $i <= $endyear; $i++){
			if($i == $yval){
				$out.= "\t<option value='$i' selected>$i</option>\n";
			}
			else{
				$out.= "\t<option value='$i'>$i</option>\n";
			}
		}
		$out .= "</select>\n";

		return $out;
	}

	function makeCCMonths($mid = "month",$mval = null){

		//if(empty($mval)) $mval = date("m");

		$months = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
		$out = "<select name='$mid' id='$mid'>\n";
		$out .= "\t<option value=''></option>\n";
		foreach($months as $val => $text){
			if($val < 10){
				$val = '0'.$val;
			}

			if($val == $mval){
				$out .= "\t<option value='$val' selected>$val - $text</option>\n";
			}
			else{
				$out .= "\t<option value='$val'>$val - $text</option>\n";
			}
		}
		$out .= "</select>\n";
		return $out;
	}


	function makeCCYears($yid = "year", $yval){

		//if(empty($yval)) $yval = date("Y");

		$startyear = date("Y");
		$endyear = date("Y") + 10;

		$out .= "<select name='$yid' id='$yid'>\n";
		$out .= "\t<option value=''></option>\n";

		for($i = $startyear; $i <= $endyear; $i++){
			if($i == $yval){
				$out.= "\t<option value='$i' selected>$i</option>\n";
			}
			else{
				$out.= "\t<option value='$i'>$i</option>\n";
			}
		}
		$out .= "</select>\n";

		return $out;
	}

if(!function_exists('file_get_contents')){
	function file_get_contents($filename) {
		$fhandle = fopen($filename, "r");
		$fcontents = fread($fhandle, filesize($filename));
		fclose($fhandle);
		return $fcontents;
	}
}

function registerHtmlForm($path){
    $vars = preg_replace("/ name=\"(\w+)\"/e","$1",file_get_contents($path));
    for($i=0;$i<count($vars);$i++){
	    if(!_GLOBALS($vars[$i])){
		    if(_POST($vars[$i])){
			    $GLOBALS[$vars[$i]] = $_POST[$vars[$i]];
		    }
	    }
    }
}
?>
