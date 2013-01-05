<?php

/* Simplifies some of the common DB calls */

class DB{
	
	var $error = null;
	var $labels = array();
	
	function DB(){}
	

    /**
     * Execute some sql and return result id
     * @param string $sql to run
     * @param boolean $display_errors [optional] true, false
     * @return object $result of executing the query
     */
    function Execute($sql){
    	$result = @mysql_query($sql);
		// check for errors
        if(!$result && mysql_errno() > 0){
			// log error
			writeToLog(array($sql,mysql_error(),mysql_errno()));
			$this->error = mysql_error();
            //die("Query failed: " . mysql_error());
		}
        return $result;
    }

	/**
	 * Returns a row from the database
	 * @param object $rs result set object
	 * @param string $type [optional] type of array to return, ASSOC or NUMERIC
	 * @return $row a row of data
	 */
    function Fetchrow($rs,$type = MYSQL_ASSOC){
    	$row = array();
    	if(is_resource($rs)){
	        $row = @mysql_fetch_array($rs, $type);
    	}
        return $row;
    }

	/**
	 * Frees up memory used by the resultset
	 * @param object $rs
	 * @return boolean success
	 */
    function FreeResult($rs){
    	if(is_resource($rs)){
	        return @mysql_free_result($rs);
    	}
    	else{
    		return true;
    	}
    }


	/**
	 * gets a single record from the database
	 * @param string $sql command
	 * @param boolean $lowerCase [optional] set field names to lower case
	 * @return array $row one record
	 */
    function GetRecord($sql,$lowerCase = false){
		$rs = $this->Execute($sql);
		$row = $this->Fetchrow($rs);
		$this->FreeResult($rs);
		//log_debug($row,"get record result");
		if($row){
			$this->labels = array_map(array(&$this,'MakeLabels'),array_keys($row));
			if($lowerCase){
	            $row = array_change_key_case($row,CASE_LOWER);
	        }
			return $row;
		}
        return array();
	}
	
	/**
	 * gets a list of records from the database and
	 * can optionally be indexed by a field in the table
	 * @param string $sql command
	 * @param string $keyName [optional] used as index instead of a numeric index
	 * @return array of records
	 */
    function GetRecords($sql,$keyName = null){
        $rs = $this->Execute($sql);
        $rows = array();
		$this->labels = array();
		while($row = $this->Fetchrow($rs)){
			if(empty($this->labels)){
				$this->labels = array_map(array(&$this,'MakeLabels'),array_keys($row));
			}
			if(is_null($keyName)){
				$rows[] = $row;
			}
			else{
				if(isset($row[$keyName])){
					$rows[$row[$keyName]] = $row;
				}
				else{
					$rows[] = $row;
				}
			}
		}	
        $this->FreeResult($rs);
        return $rows;
    }

	/**
	 * gets a 2 column list of records from the database for
	 * dynamically building select boxes, radio buttons, and
	 * checkboxes. Your SQL should only include the 2 fields
	 * you want to set for the value and the label.
	 * @param string $sql command
	 * @return array of records
	 */
	function GetList($sql){
        $rs = $this->Execute($sql);
        $rows = array();
		while($row = $this->Fetchrow($rs,MYSQL_NUM)){
			$rows[$row[0]] = $row[1];
		}	
        $this->FreeResult($rs);
        return $rows;
    }

	/**
	 * makes labels out of field names
	 * @param string $fld name
	 * @param boolean $toUpper [optional] uses ucwords() to capitalize
	 * @return string $label
	 */
	function MakeLabels($fld,$toUpper = true){
		$words = explode('_',$fld);
		if(count($words) > 1){
			if($toUpper){
				$label = ucwords(lc(join(" ",$words)));
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

	/**
	 * Gets a list of field properties from a table or list of tables
	 * @param mixed $tables single table name or array of table names
	 * @return array properties returned from SHOW FULL COLUMNS
	 */
    function GetFieldProperties($tables){
		$fields = array();
		if(is_array($tables) && count($tables) > 0){
			foreach($tables as $i=>$table){
				$fields[$table] = $this->GetTableFieldProperties($table);
			}	
		}
		else{
			$fields = $this->GetTableFieldProperties($tables);
		}
    	return $fields;
    }

	/**
	* Gets field properties for a given table
	* @param $table The table name
	* @return array properties returned from SHOW FULL COLUMNS 
	*/
	function GetTableFieldProperties($table){
		$fields = array();
		$rs = $this->Execute("SHOW FULL COLUMNS FROM $table");
		$fields = array();
		while($row = $this->Fetchrow($rs)){
        	$row = array_change_key_case($row, CASE_LOWER);
			$fields[$row['field']] = $row;
		}
		$this->FreeResult($rs);	
		return $fields;
	}


	/**
	 * allows logging of error info
	 * @param $data string or array of information to log
	 * @return void
	 */
    function LogErrors($data){
        $date = date("m-d-Y");
	    if(is_array($data)){
	        $data = print_r($data,true);
	    }
        @file_put_contents('db.errors.log',"\n\n$date: $data", FILE_APPEND);
    }
	// -------------------------------------------------------------------
	function MakeUpdateFields($table,$data,$idFld){
		
		// values should be escaped before being sent into this function
		
		$fieldProperties = $this->GetFieldProperties($table);
		
		$values = array();

		foreach($fieldProperties as $fldName=>$props){
			if(isset($data[$fldName]) && !is_array($data[$fldName]) && $fldName != $idFld){
				$fldValue = trim($data[$fldName]);
				$values[] = "`$fldName` = '$fldValue'";
			}
		}
		return join(',',$values);
	}
    // -------------------------------------------------------------------
    function MakeAddFields($table,$data,$idFld){

		// values should be escaped before being sent into this function

		$fieldProperties = $this->GetFieldProperties($table);

        $fields = array();
        $values = array();
	
        foreach($fieldProperties as $fldName=>$props){
			if(isset($data[$fldName]) && !is_array($data[$fldName]) && $fldName != $idFld){
				$fields[] = $fldName;
				$values[] = trim($data[$fldName]);
            }
        }
		
		$fldStr = "`" . join("`,`",$fields) . "`";
		$valStr = "'" . join("','",$values) . "'";
		
        return array($fldStr, $valStr);
    }
	
    // ----------------------------------------------------------------
	function GetInsertID($table,$field){
		
		$lastid = mysql_insert_id();
		
		if($lastid == 0){
			$sql = "SELECT MAX($field) as lastid FROM $table";
			$row = $this->fetchrow($this->execute($sql),"ASSOC");
			if(isset($row['lastid']) && $row['lastid'] != 0){
				$lastid = $row['lastid'];
			}
		}
		if($lastid == 0){
			print "<b>ERROR:</b> Could not get last insert id from mysql";
			exit;
		}
		return $lastid;
	}
	
}
//make sure there are no trailing spaces or lines after php closes below or header() commands may break
?>