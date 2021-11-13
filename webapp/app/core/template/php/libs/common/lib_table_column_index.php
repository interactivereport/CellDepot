<?php

function createColumnIndex($Table_Name = NULL, $Table_ID = 0, $Record_ID = 0, $Column_Name = '', $Column_ID = 0, $Value = '', $useNA = 1){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$Record_ID = intval($Record_ID);
	if ($Record_ID <= 0) return false;
	
	$Value = splitCategories($Value, $useNA);
	
	foreach($Value as $tempKey => $tempValue){
	
		$dataArray = array();
		$dataArray['Table_Name'] 	= $Table_Name;
		$dataArray['Table_ID'] 		= $Table_ID;
		$dataArray['Record_ID'] 	= $Record_ID;
		$dataArray['Column_Name'] 	= $Column_Name;
		$dataArray['Column_ID'] 	= $Column_ID;
		$dataArray['Value'] 		= $tempValue;
		
		if ($dataArray['Value'] != ''){
			$SQL = getInsertSQLQuery($APP_CONFIG['TABLES']['COLUMN_INDEX'], $dataArray);
			executeSQL($SQL);
		}
	}

	return true;

}

function deleteColumnIndexByRecordID($Table = NULL, $Record_IDs = 0){
	
	global $APP_CONFIG;
	
	$Record_IDs = id_sanitizer($Record_IDs, 0, 1, 0, 2);
	$Table_ID = intval($Table);
	
	if ($Record_IDs == ''){
		return false;	
	}
	
	if ($Table_ID > 0){
		$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_ID` = '{$Table_ID}' AND (`Record_ID` IN ({$Record_IDs}))";
	} elseif ($Table != ''){
		$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_Name` = '{$Table}' AND (`Record_ID` IN ({$Record_IDs}))";
	} else {
		return false;
	}

	
	executeSQL($SQL);
	
	return true;
	
}

function getColumnIndexValueCount($Table = NULL, $Column = NULL, $SQL_CONDITION = '(1)'){
	
	global $APP_CONFIG;

	$Table_ID 	= intval($Table);
	$Column_ID 	= intval($Column);
	
	if ($SQL_CONDITION == ''){
		$SQL_CONDITION = '(1)';
	}
	
	if (($Table_ID > 0) && ($Column_ID > 0)){
		$SQL = "SELECT `Value`, count(`Value`) as 'Count' FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_ID` = '{$Table_ID}' AND (`Column_ID` = '{$Column_ID}') AND ({$SQL_CONDITION}) GROUP BY `Value`";
	} elseif (($Table != '') && ($Column != '')){
		$SQL = "SELECT `Value`, count(`Value`) as 'Count'  FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_Name` = '{$Table}' AND (`Column_Name` = '{$Column}') AND ({$SQL_CONDITION}) GROUP BY `Value`";
	} else {
		return false;
	}
	
	$results = getSQL_Data($SQL, 'GetAssoc', 1);
	
	return $results;
	
}



function getColumnIndexMenu($Table = NULL, $Column = NULL){
	
	global $APP_CONFIG;
	
	$Table_ID 	= intval($Table);
	$Column_ID 	= intval($Column);
	
	if (($Table_ID > 0) && ($Column_ID > 0)){
		$SQL = "SELECT `Value` FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_ID` = '{$Table_ID}' AND (`Column_ID` = '{$Column_ID}') GROUP BY `Value`";
	} elseif (($Table != '') && ($Column != '')){
		$SQL = "SELECT `Value` FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_Name` = '{$Table}' AND (`Column_Name` = '{$Column}') GROUP BY `Value`";
	} else {
		return false;
	}
	
	$results = getSQL_Data($SQL, 'GetCol', 1);
	$results = array_clean($results);
	$results = array_combine($results, $results);

	
	return $results;
	
}

//$operator:
//2: is
//3: is not
function searchColumnIndex($Table = NULL, $Column = NULL, $Value = '', $operator = 2, $output_ID_Only = 0){
	
	global $APP_CONFIG;
	
	$Table_ID 	= intval($Table);
	$Column_ID 	= intval($Column);
	
	if (!is_array($Value)){
		$Value		= addslashes(trim($Value));
		if ($Value == '') return false;
	}	

	
	$Value = string_to_sql_sanitizer($Value);

	if (($Table_ID > 0) && ($Column_ID > 0)){
		$SQL = "SELECT `Record_ID` FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_ID` = '{$Table_ID}' AND (`Column_ID` = '{$Column_ID}') AND (`Value` IN ({$Value}))";
	} elseif (($Table != '') && ($Column != '')){
		$SQL = "SELECT `Record_ID` FROM `{$APP_CONFIG['TABLES']['COLUMN_INDEX']}` WHERE `Table_Name` = '{$Table}' AND (`Column_Name` = '{$Column}') AND (`Value` IN ({$Value}))";
	} else {
		return false;
	}
	
	
	
	$recordIDs = getSQL_Data($SQL, 'GetCol', 1);
	$recordIDs = id_sanitizer($recordIDs, 0, 1, 0, 2);
	
	if ($output_ID_Only){
		return $recordIDs;
	} else {
	
		if ($recordIDs != ''){
			if ($operator != 3){
				//Is
				return "(`ID` IN ({$recordIDs}))";	
			} else {
				return "(`ID` NOT IN ({$recordIDs}))";	
			}
		} else {
			if ($operator != 3){
				//Is
				return "(FALSE)";	
			} else {
				return "(TRUE)";	
			}
		}
	}
	
}


?>