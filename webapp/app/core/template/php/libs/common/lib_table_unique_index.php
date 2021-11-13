<?php

/*
CREATE TABLE `App_Name` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(256) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

function getNameIndexExpress($SQL_TABLE = '', $name = ''){
	
	if ($SQL_TABLE == '') return 0;
	if ($name == '') return 0;
	
	$ID = getNameIndex($SQL_TABLE, $name);
	
	if ($ID <= 0){
		$ID = createNameIndex($SQL_TABLE, $name);	
	}
	
	return $ID;
}



function createNameIndex($SQL_TABLE = '', $name = ''){
	
	$name = trim($name);
	
	if ($name == '') return 0;
	if ($SQL_TABLE == '') return 0;
	
	$dataArray = array('Name' => $name);

	$SQL = getInsertSQLQuery($SQL_TABLE, $dataArray);
	
	executeSQL($SQL);

	return getLastInsertID();

}

function getNameIndex($SQL_TABLE = '', $name = ''){
	
	global $APP_CONFIG;
	
	$name = addslashes(trim(strtoupper($name)));
	
	if ($name == '') return 0;
	if ($SQL_TABLE == '') return 0;
	
	$SQL = "SELECT `ID` FROM {$SQL_TABLE} WHERE `Name` = '{$name}'";

	$results = getSQL_Data($SQL, 'GetOne', 1);

	return $results;
	
}

function getNameIndexes($SQL_TABLE = '', $names = NULL){
	
	global $APP_CONFIG;
	
	$names = string_to_sql_sanitizer($names);
	
	if ($names == '') return 0;
	if ($SQL_TABLE == '') return 0;
	
	$SQL = "SELECT `Name`, `ID` FROM {$SQL_TABLE} WHERE `Name` IN ({$names})";
	
	
	$results = getSQL_Data($SQL, 'GetAssoc', 1);

	return $results;
}

function getNameIndexesByIndexes($SQL_TABLE = '', $indexes = NULL){
	
	global $APP_CONFIG;
	
	$indexes = id_sanitizer($indexes, 0, 1, 0, 2);
	
	if ($indexes == '') return FALSE;
	if ($SQL_TABLE == '') return FALSE;
	
	$SQL = "SELECT `Name`, `ID` FROM {$SQL_TABLE} WHERE `ID` IN ({$indexes})";
	
	
	$results = getSQL_Data($SQL, 'GetAssoc', 1);

	return $results;
}



function getAllNameIndexes($SQL_TABLE = ''){
	
	global $APP_CONFIG;
	
	if ($SQL_TABLE == '') return false;
	
	$SQL = "SELECT `ID`, `Name` FROM {$SQL_TABLE} ORDER BY `Name`";
	
	
	$results = getSQL_Data($SQL, 'GetAssoc', 1);

	return $results;
	
}


?>