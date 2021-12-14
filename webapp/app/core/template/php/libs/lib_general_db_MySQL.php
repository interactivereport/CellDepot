<?php


function executeSQL($SQL = ''){

	global $APP_CONFIG;
	
	if ($SQL == '') return false;
	
	return $APP_CONFIG['SQL_DATA_CONN']->Execute($SQL);	
}


function getInsertSQLQuery($SQL_TABLE = '', $dataArray = array(), $header = NULL, $trim = 1, $addslashes = 1){
	
	
	$SQL = "INSERT INTO `{$SQL_TABLE}` ";
	
	
	foreach($dataArray as $key => $value){
		if ($trim){
			$value		 = trim($value);
		}
		
		if ($addslashes){
			$value		 = addslashes($value);
		}
		
		$dataArray[$key] = $value;
	}
	
	
	
	if (array_size($header) > 0){
		$SQL_COLUMN_STRING = '(`' . implode('`, `', $header) . '`)';		
	} else {
		$SQL_COLUMN_STRING = '(`' . implode('`, `', array_keys($dataArray)) . '`)';
	}

	$SQL .= "{$SQL_COLUMN_STRING} VALUES ";
	
	$SQL_VALUE_STRING = "('" . implode("', '", array_values($dataArray)) . "')";
	
	$SQL .= "{$SQL_VALUE_STRING}";
	
	return $SQL;
}


function getReplaceSQLQuery($SQL_TABLE = '', $dataArray = array(), $header = '', $trim = 1, $addslashes = 1){
	
	$SQL = "REPLACE INTO `{$SQL_TABLE}` ";
	
	foreach($dataArray as $key => $value){
		if ($trim){
			$value		 = trim($value);
		}
		
		if ($addslashes){
			$value		 = addslashes($value);
		}
		
		$dataArray[$key] = $value;
	}
	
	if (array_size($header) > 0){
		$SQL_COLUMN_STRING = '(`' . implode('`, `', $header) . '`)';		
	} else {
		$SQL_COLUMN_STRING = '(`' . implode('`, `', array_keys($dataArray)) . '`)';
	}

	$SQL .= "{$SQL_COLUMN_STRING} VALUES ";
	
	$SQL_VALUE_STRING = "('" . implode("', '", array_values($dataArray)) . "')";
	
	$SQL .= "{$SQL_VALUE_STRING}";
	
	return $SQL;
}


function getUpdateSQLQuery($SQL_TABLE = '', $dataArray = array(), $ID = NULL, $trim = 1, $ID_Field = 'ID'){
	
	$SQL = "UPDATE `{$SQL_TABLE}` SET ";
	
	foreach($dataArray as $key => $value){
		if ($SQL_VALUE_STRING != '') $SQL_VALUE_STRING .= ', ';
		
		if ($trim){
			$value		 = trim($value);
		}
		
		$value = addslashes($value);
		
		$SQL_VALUE_STRING .= "`{$key}` = '{$value}'";
		
	}
	
	$SQL .= "{$SQL_VALUE_STRING} WHERE `{$ID_Field}` ";
	
	if (is_array($ID)){
		$ID = array_filter($ID);
		$ID = array_unique($ID);
		$ID = array_filter($ID, 'is_numeric');
		$ID = implode(',', $ID);
		
		$SQL .= " IN ({$ID})";
		
	} else {
		
		$ID = intval($ID);
		
		$SQL .= " = {$ID}";
	}
	
	return $SQL;
}


function getTableCount($SQL_TABLE = ''){
	$SQL = "SELECT count(*) FROM `{$SQL_TABLE}`";
	
	$results = getSQL_Data($SQL, 'GetOne');
	
	return intval($results);
}

//Return unique values of all records
function getUniqueColumnValues($SQL_TABLE = '', $SQL_COLUMN = ''){
	$SQL = "SELECT `{$SQL_COLUMN}` FROM `{$SQL_TABLE}` GROUP BY `{$SQL_COLUMN}`";
	
	$results = getSQL_Data($SQL, 'GetCol');
	
	return array_clean($results);
}

//Return # of unique values of all records
function getUniqueColumnValueCount($SQL_TABLE = '', $SQL_COLUMN = '', $SQL_CONDITION = '(1)'){
	$SQL = "SELECT count(distinct `{$SQL_COLUMN}`) FROM `{$SQL_TABLE}` WHERE {$SQL_CONDITION}";
	
	$results = getSQL_Data($SQL, 'GetOne');
	
	return intval($results);
}

//Return unique values and count of all records
function getUniqueColumnValueAndCount($SQL_TABLE = '', $SQL_COLUMN = '', $SQL_CONDITION = '(1)'){
	
	if ($SQL_CONDITION != ''){
		$SQL = "SELECT `{$SQL_COLUMN}`, COUNT(`{$SQL_COLUMN}`) as `Count` FROM `{$SQL_TABLE}` WHERE {$SQL_CONDITION} GROUP BY `{$SQL_COLUMN}`";
	} else {
		$SQL = "SELECT `{$SQL_COLUMN}`, COUNT(`{$SQL_COLUMN}`) as `Count` FROM `{$SQL_TABLE}` GROUP BY `{$SQL_COLUMN}`";
	}
	
	$results = getSQL_Data($SQL, 'GetAssoc');
	
	return $results;
}


function getLastInsertID(){
	
	global $APP_CONFIG;
	
	return $APP_CONFIG['SQL_DATA_CONN']->Insert_ID();
	
	
}


function getSQL_Data($SQL = '', $type = '', $cache = 0){
	
	global $APP_CONFIG, $APP_CACHE, $BXAF_CONFIG;
	
	$cacheKey = __FUNCTION__ . '::' . md5($SQL . '::' . $type);
	
	if ($cache && !$APP_CONFIG['CACHE_OFF']){
		
		if (isset($APP_CACHE[__FUNCTION__][$cacheKey])){
			return $APP_CACHE[__FUNCTION__][$cacheKey];
		}
		
		$resultsFromCache = getRedisCache($cacheKey);
	
		if (!(is_null($resultsFromCache) || $resultsFromCache == false)){
			return $resultsFromCache;	
		}
	}


	if ($type == 'GetOne'){
		$results = $APP_CONFIG['SQL_DATA_CONN']->GetOne($SQL);
		$json_decode_assoc = 0;
	} elseif ($type == 'GetAssoc'){
		$results = $APP_CONFIG['SQL_DATA_CONN']->GetAssoc($SQL);
		$json_decode_assoc = 1;
	} elseif ($type == 'GetArray'){
		$results = $APP_CONFIG['SQL_DATA_CONN']->GetArray($SQL);
		$json_decode_assoc = 1;
	} elseif ($type == 'GetCol'){
		$results = $APP_CONFIG['SQL_DATA_CONN']->GetCol($SQL);
		$json_decode_assoc = 1;
	} elseif ($type == 'GetRow'){
		$results = $APP_CONFIG['SQL_DATA_CONN']->GetRow($SQL);
		$json_decode_assoc = 1;
	}
	

	
	$APP_CACHE[__FUNCTION__][$cacheKey] = $results;
	
	if ($results !== NULL){
		
		if ($APP_CONFIG['APP']['Cache_Expiration_Length_getSQL_Data'] > 0){
			putRedisCache(array($cacheKey => $results), $APP_CONFIG['APP']['Cache_Expiration_Length_getSQL_Data']);
		} else {
			putRedisCache(array($cacheKey => $results));
		}
	}
	
	return $results;
}

function getInsertMultipleSQLQuery($SQL_TABLE = '', $dataArray = array(), $header = ''){
	
	$SQL = "INSERT INTO `{$SQL_TABLE}` ";
	
	
	if (array_size($header) > 0){
		$SQL_COLUMN_STRING = '(`' . implode('`, `', $header) . '`)';		
	} else {
		$SQL_COLUMN_STRING = '(`' . implode('`, `', array_keys($dataArray[0])) . '`)';
	}
	
	$SQL .= "{$SQL_COLUMN_STRING} VALUES ";
	
	foreach($dataArray as $tempKey => $tempValue){
		
		foreach($tempValue as $tempKey2 => $tempValue2){
			$tempValue[$tempKey2] = addslashes($tempValue2);
		}		
		
		$SQL_VALUE_STRING[] = "('" . implode("', '", array_values($tempValue)) . "')";
	}
	
	$SQL .= implode(',', $SQL_VALUE_STRING);
	
	return $SQL;
}

function getSQLTableStructure($SQL_TABLE = ''){
	
	$SQL = "SHOW CREATE TABLE `{$SQL_TABLE}`";
	
	$SQL_RESULT = getSQL_Data($SQL, 'GetAssoc');
	
	$structure	= preg_replace("/ AUTO_INCREMENT=[0-9]*/i", "", $SQL_RESULT[$SQL_TABLE]);

	return $structure;
}

function getSQLColumnStructure($SQL_TABLE = ''){
	
	$SQL = "SHOW COLUMNS FROM `{$SQL_TABLE}`";
	
	$SQL_RESULT = getSQL_Data($SQL, 'GetAssoc');
	
	return $SQL_RESULT;
}


function getSQLDataByIDs($SQL_TABLE = '', $ids = '', $id_field = 'ID', $columns = '*', $cache = 0){
	
	$ids = id_sanitizer($ids, 0, 1, 0, 2);
	
	if ($ids == '') return false;
	
	$SQL = "SELECT {$columns} FROM `{$SQL_TABLE}` WHERE (`{$id_field}` IN ({$ids}))";
	
	return getSQL_Data($SQL, 'GetAssoc', $cache);
	
}

function disableSQLTableIndex($SQL_TABLE = ''){
	
	$SQL = "ALTER TABLE `{$SQL_TABLE}` DISABLE KEYS";
	
	executeSQL($SQL);
	
	return true;
	
}

function resumeSQLTableIndex($SQL_TABLE = ''){
	
	$SQL = "ALTER TABLE `{$SQL_TABLE}` ENABLE KEYS";
	
	executeSQL($SQL);
	
	return true;
	
}

?>