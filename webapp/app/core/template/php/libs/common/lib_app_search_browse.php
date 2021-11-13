<?php

//$mode:
//0: Default
//1: Browse
//2: Search
function getHeaderDisplayName($currentTable = '', $currentSQL = '', $mode = 0){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	
	if ($mode == 1){
		if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title_Browse'] != ''){	
			return $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title_Browse'];	
		} elseif ($BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title_Browse'] != ''){
			return $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title_Browse'];
		}
	} elseif ($mode == 2){
		if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title_Search'] != ''){	
			return $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title_Search'];	
		} elseif ($BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title_Search'] != ''){
			return $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title_Search'];
		}
	}
	
	if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title'] != ''){
		
		return $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title'];	
		
	} elseif ($APP_CONFIG['MESSAGE'][$currentSQL] != ''){
		
		return $APP_CONFIG['MESSAGE'][$currentSQL];
		
	} elseif ($BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title'] != ''){
		
		return $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentSQL]['Title'];
		
	} else {
		return ucwords(str_replace('_', ' ', strtolower($currentSQL)));
	}
}


function getBrowsableColumns($currentTable = ''){

	global $APP_CONFIG;
	
	$results = array();
	
	foreach($APP_CONFIG['DICTIONARY'][$currentTable] as $currentColumn => $columnInfo){
		if ($columnInfo['Browse']){
			$results[] = $currentColumn;
		}
	}
	
	return $results;
	
}


function getSearchableColumns($currentTable = ''){

	global $APP_CONFIG;
	
	$results = array();
	
	foreach($APP_CONFIG['DICTIONARY'][$currentTable] as $currentColumn => $columnInfo){
		
		if ($columnInfo['Search']){
			$results[$currentColumn] = getHeaderDisplayName($currentTable, $currentColumn, 2);
		}
	}
	
	natcasesort($results);
	
	return $results;
	
}

//The columns are sorted by name
function getSearchableColumnsWithGroupHeaders($currentTable = '', $hideColumnByPreference = 0){

	global $APP_CONFIG;
	
	$results = array();
	
	if ($hideColumnByPreference){
		$allColumns = getSearchTablePreferences($currentTable);
		$preferences = getSearchColumnSettingsForDataTable($currentTable);
		$preferences = array_combine(array_keys($allColumns), $preferences);
	}
	
	foreach($APP_CONFIG['DICTIONARY'][$currentTable] as $currentColumn => $columnInfo){

		$groupHeader = trim($columnInfo['Group_Header']);
		
		if ($groupHeader == ''){
			$groupHeader = 'Others';	
		}
		
		if ($columnInfo['Search']){
			
			if ($hideColumnByPreference){
				if ($preferences[$currentColumn]){
					$results[$groupHeader][$currentColumn] = getHeaderDisplayName($currentTable, $currentColumn, 2);
					natcasesort($results[$groupHeader]);
				}
			} else {
				$results[$groupHeader][$currentColumn] = getHeaderDisplayName($currentTable, $currentColumn, 2);
				natcasesort($results[$groupHeader]);
			}
		}
	}
	
	natcasesort($results);
	
	return $results;
	
}

//Same as getSearchableColumnsWithGroupHeaders()
//The columsn are sorted by display order
function getSearchableColumnsWithGroupHeadersForDisplay($currentTable = ''){

	global $APP_CONFIG;
	
	$results = array();
	
	
	$allColumns = getSearchTablePreferences($currentTable);
	$preferences = getSearchColumnSettingsForDataTable($currentTable);
	$preferences = array_combine(array_keys($allColumns), $preferences);
	$getSearchTablePreferences = getSearchTablePreferences($currentTable);

	
	foreach($getSearchTablePreferences as $currentColumn => $displayName){
		
		if ($preferences[$currentColumn]){
			$columnInfo = $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn];
			$groupHeader = trim($columnInfo['Group_Header']);
			
			if ($groupHeader == ''){
				$groupHeader = 'Others';	
			}
			
			$results[$groupHeader][$currentColumn] = getHeaderDisplayName($currentTable, $currentColumn, 2);
		}
		
		
	}
	
	
	
	natcasesort($results);
	
	return $results;
	
}





function getExportableColumns($currentTable = ''){

	global $APP_CONFIG;
	
	$results = array();
	
	foreach($APP_CONFIG['DICTIONARY'][$currentTable] as $currentColumn => $columnInfo){
		if (($columnInfo['Browse']) && (!$columnInfo['Export_Disable'])){
			$results[$currentColumn] = getHeaderDisplayName($currentTable, $currentColumn, 2);
		}
	}
	
	
	
	return $results;
	
}


function getSearchSQLResults($currentTable = '', $inputArray = array(), $otherInfo = NULL){
	
	global $APP_CONFIG;

	$allColumns = getSearchableColumns($currentTable);
	$rowCount 	= abs(intval($inputArray['rowCount']));
	$dataArray 	= array();
	
	
	if ($inputArray['Record_IDs_Key'] != ''){
		$recordIDs = getRedisCache($inputArray['Record_IDs_Key']);
		
		
		
		$SQL_CONDITIONS = "(`ID` IN (" . id_sanitizer($recordIDs, 0, 1, 0, 2) . "))";
		
		if ($otherInfo['bxafStatus']){
			$SQL_CONDITIONS = "({$SQL_CONDITIONS}) AND (`bxafStatus` = 0)";	
		}
		
		
		if (array_size($recordIDs) > 0){
			$SQL 				= "SELECT `ID` FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
			$SQL_Count 			= "SELECT count(*) FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
			$results['All']	= 0;
			
		}
	} else {
		for ($i = 1; $i <= $rowCount; $i++){
			
			unset($currentSQLCondition);
			
			$currentColumn = $inputArray["Field_{$i}"];
	
			if (!isset($allColumns[$currentColumn])) continue;
			
	
			if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
				if (!is_array($value)){
					$value = floatval($_POST["Value_{$i}"]);
				}
			} elseif (is_array($_POST["Value_{$i}"])){
				$value = $_POST["Value_{$i}"];
			} else {
				$value = addslashes($_POST["Value_{$i}"]);
				$value = str_replace('**', '*', $value);
			}
			
					
			if (function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search_Function'])){
				$searchFunction = $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search_Function'];
				
				$currentSQLCondition = $searchFunction($currentTable, $currentColumn, $value, intval($inputArray["Operator_{$i}"]));
				
				unset($searchRow);
				$searchRow['Field'] 			= $currentColumn;
				$searchRow['Operator'] 			= $operator;
				$searchRow['Value'] 			= $value;
				$searchRow['Logic'] 			= $inputArray["Logic_{$i}"];
				$searchRow['SQL']				= $currentSQLCondition;
				$searchRow['Search_Function']	= $searchFunction;
				
				
				if (!isset($dataArray['Search'])){
					$dataArray['Search'][1] = $searchRow;
				} else {
					$dataArray['Search'][] = $searchRow;
				}
			} elseif (is_string($value) && strpos($value, '*') !== FALSE){
				
				$tempValue = str_replace('*', '%', $value);
				$currentSQLCondition = "(`{$currentColumn}` LIKE '{$tempValue}')";
				
				unset($searchRow);
				$searchRow['Field'] 	= $currentColumn;
				$searchRow['Operator'] 	= 0;
				$searchRow['Value'] 	= $value;
				$searchRow['Logic'] 	= $inputArray["Logic_{$i}"];
				$searchRow['SQL']		= $currentSQLCondition;
				
				if (!isset($dataArray['Search'])){
					$dataArray['Search'][1] = $searchRow;
				} else {
					$dataArray['Search'][] = $searchRow;
				}
			} elseif ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'tag_key_value'){
				
				$tempValue = id_sanitizer($value, 0, 1, 0, 2);
				$operator = intval($inputArray["Operator_{$i}"]);

				
				if ($tempValue != ''){
					if ($operator == 3){
						//IS NOT
						$currentSQLCondition = "(`{$currentColumn}` NOT IN ({$tempValue}))";
					} else {
						// = 2
						//IS
						$currentSQLCondition = "(`{$currentColumn}` IN ({$tempValue}))";
					}
					
					unset($searchRow);
					$searchRow['Field'] 	= $currentColumn;
					$searchRow['Operator'] 	= 0;
					$searchRow['Value'] 	= $value;
					$searchRow['Logic'] 	= $inputArray["Logic_{$i}"];
					$searchRow['SQL']		= $currentSQLCondition;
					
					if (!isset($dataArray['Search'])){
						$dataArray['Search'][1] = $searchRow;
					} else {
						$dataArray['Search'][] = $searchRow;
					}
					
				}
				
			
			} else {
				
				$tempValue = $value;
				
				$operator = intval($inputArray["Operator_{$i}"]);
				
				if ($operator == 1){
					//Contain
					$currentSQLCondition = "(`{$currentColumn}` LIKE '%{$tempValue}%')";
					
					if ($tempValue == '') continue;
					
				} elseif ($operator == 2){
					//Is
					$currentSQLCondition = "(`{$currentColumn}` = '{$tempValue}')";
					
				} elseif ($operator == 3){
					//Is Not
					$currentSQLCondition = "(`{$currentColumn}` != '{$tempValue}')";
				} elseif ($operator == 4){
					//Starts with
					$currentSQLCondition = "(`{$currentColumn}` LIKE '{$tempValue}%')";
					if ($tempValue == '') continue;
					
				} elseif ($operator == 5){
					//Ends With
					$currentSQLCondition = "(`{$currentColumn}` LIKE '%{$tempValue}')";
					if ($tempValue == '') continue;
				} elseif ($operator == 10){
					//=
					$currentSQLCondition = "(`{$currentColumn}` = '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) = {$tempValue})";
					}
					
					
				} elseif ($operator == 11){
					//>
					$currentSQLCondition = "(`{$currentColumn}` > '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) > {$tempValue})";
					}
					
				} elseif ($operator == 12){
					//>=
					$currentSQLCondition = "(`{$currentColumn}` >= '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) >= {$tempValue})";
					}
					
				} elseif ($operator == 13){
					//<
					$currentSQLCondition = "(`{$currentColumn}` < '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) < {$tempValue})";
					}
					
				} elseif ($operator == 14){
					//<=
					$currentSQLCondition = "(`{$currentColumn}` <= '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) <= {$tempValue})";
					}
					
				} elseif ($operator == 15){
					//!=
					$currentSQLCondition = "(`{$currentColumn}` != '{$tempValue}')";
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) != {$tempValue})";
					}
					
					
				} else {
					$currentSQLCondition = "(`{$currentColumn}` = '{$tempValue}')";
					
					
					if ($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] == 'number'){
						$currentSQLCondition = "(CAST(`{$currentColumn}` as double) = {$tempValue})";
					}
					
				}
				
				unset($searchRow);
				$searchRow['Field'] 	= $currentColumn;
				$searchRow['Operator'] 	= $operator;
				$searchRow['Value'] 	= $value;
				$searchRow['Logic'] 	= $inputArray["Logic_{$i}"];
				$searchRow['SQL']		= $currentSQLCondition;
				
				if (!isset($dataArray['Search'])){
					$dataArray['Search'][1] = $searchRow;
				} else {
					$dataArray['Search'][] = $searchRow;
				}
			}
		}
		
		
		$SQL_CONDITIONS = '';
		foreach($dataArray['Search'] as $i => $currentSearchInfo){
			$SQL_CONDITIONS .= " {$currentSearchInfo['Logic']} {$currentSearchInfo['SQL']}";
		}
		
		if ($SQL_CONDITIONS == ''){
			$SQL 			= "SELECT `ID` FROM `{$currentTable}`";
			$SQL_Count 		= "SELECT count(*) FROM `{$currentTable}`";
			
			if ($otherInfo['bxafStatus']){
				$SQL 			= "SELECT `ID` FROM `{$currentTable}` WHERE (`bxafStatus` = 0)";
				$SQL_Count 		= "SELECT count(*) FROM `{$currentTable}` WHERE (`bxafStatus` = 0)";
			} else {
				$SQL 			= "SELECT `ID` FROM `{$currentTable}`";
				$SQL_Count 		= "SELECT count(*) FROM `{$currentTable}`";
			}
			$results['All']	= 1;
					
		} else {
			if ($otherInfo['bxafStatus']){
				$SQL_CONDITIONS = "({$SQL_CONDITIONS}) AND (`bxafStatus` = 0)";	
			}
			
			
			$SQL 				= "SELECT `ID` FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
			$SQL_Count 			= "SELECT count(*) FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
			$results['All']	= 0;
		}
	}
	
	$results['SQL_CONDITIONS']	= $SQL_CONDITIONS;
	$results['SQL_ID'] 			= $SQL;
	$results['SQL_Count'] 		= $SQL_Count;
	$results['Record_Count'] 	= getSQL_Data($SQL_Count, 'GetOne', 1);
	$results['dataArray'] = $dataArray;
	
	return $results;
	
	
}

function getFilterSQLResults($currentTable = '', $inputArray = array(), $otherInfo = NULL){
	
	global $APP_CONFIG;

	$allColumns = getSearchableColumns($currentTable);
	$dataArray 	= array();
	
	
	foreach($inputArray as $currentColumn => $value){
		
		unset($currentSQLCondition);

		if (!isset($allColumns[$currentColumn])) continue;
		
		if (function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search_Function'])){
			
			$searchFunction = $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search_Function'];
			
			//2 = IS
			$currentSQLCondition = $searchFunction($currentTable, $currentColumn, $value, 2);
			
			unset($searchRow);
			$searchRow['Field'] 	= $currentColumn;
			$searchRow['Operator'] 	= 2;
			$searchRow['Value'] 	= $value;
			$searchRow['Logic'] 	= 'AND';
			$searchRow['SQL']		= $currentSQLCondition;

			if (!isset($dataArray['Search'])){
				$dataArray['Search'][1] = $searchRow;
			} else {
				$dataArray['Search'][] = $searchRow;
			}
			
			
		} else {
			
			$tempValue 		= $value;
			
			$operator 		= intval($inputArray["Operator_{$i}"]);
			$valueString 	= string_to_sql_sanitizer($value);
			

			if ($valueString == FALSE){
				$currentSQLCondition = "(`{$currentColumn}` = '')";
			} else {
				$currentSQLCondition = "(`{$currentColumn}` IN ({$valueString}))";
			}
			
			unset($searchRow);
			$searchRow['Field'] 	= $currentColumn;
			$searchRow['Operator'] 	= 2;
			$searchRow['Value'] 	= $value;
			$searchRow['Logic'] 	= 'AND';
			$searchRow['SQL']		= $currentSQLCondition;
			
			if (!isset($dataArray['Search'])){
				$dataArray['Search'][1] = $searchRow;
			} else {
				$dataArray['Search'][] = $searchRow;
			}
		}
	}
	
	
	$SQL_CONDITIONS = '';
	foreach($dataArray['Search'] as $i => $currentSearchInfo){
		if ($i == 1){
			$SQL_CONDITIONS .= " {$currentSearchInfo['SQL']}";
		} else {
			$SQL_CONDITIONS .= " {$currentSearchInfo['Logic']} {$currentSearchInfo['SQL']}";
		}
	}
	
	if ($SQL_CONDITIONS == ''){
		if ($otherInfo['bxafStatus']){
			$SQL 			= "SELECT `ID` FROM `{$currentTable}` WHERE (`bxafStatus` = 0)";
			$SQL_Count 		= "SELECT count(*) FROM `{$currentTable}` WHERE (`bxafStatus` = 0)";
		} else {
			$SQL 			= "SELECT `ID` FROM `{$currentTable}`";
			$SQL_Count 		= "SELECT count(*) FROM `{$currentTable}`";
		}
		$results['All']	= 1;
				
	} else {
		if ($otherInfo['bxafStatus']){
			$SQL_CONDITIONS = "({$SQL_CONDITIONS}) AND (`bxafStatus` = 0)";	
		}
			
		$SQL 				= "SELECT `ID` FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
		$SQL_Count 			= "SELECT count(*) FROM `{$currentTable}` WHERE {$SQL_CONDITIONS}";
		$results['All']	= 0;
	}
	
	$results['SQL_CONDITIONS']	= $SQL_CONDITIONS;
	$results['SQL_ID'] 			= $SQL;
	$results['SQL_Count'] 		= $SQL_Count;
	$results['Record_Count'] 	= getSQL_Data($SQL_Count, 'GetOne', 1);
	
	return $results;
	
	
}


function getSQLFromDataTable($SQL_TABLE = '', $SQL_FILTER_BASE = '', $columnMapping = NULL, $dataTableInput = array(), $otherInfo = NULL){
	
	global $APP_CONFIG;
	
	$searchKeyword 	= addslashes(trim($dataTableInput['search']['value']));
	
	$start			= abs(intval($dataTableInput['start']));
	
	$count			= intval($dataTableInput['length']);

	
	$orderByID		= $dataTableInput['order'][0]['column'] - 1;
	$orderByColumn	= $columnMapping[$orderByID];
	
	
	if ($orderByColumn == ''){
		$orderByColumn = 'ID';	
	}
	
	if ($APP_CONFIG['DICTIONARY'][$SQL_TABLE][$orderByColumn]['Type'] == 'number'){
		$orderByColumnSection = "CAST(`{$orderByColumn}` as double)";
	} else {
		$orderByColumnSection = "`{$orderByColumn}`";	
	}
	
	
	$orderDirection = $_POST['order'][0]['dir'];
	$orderDirection = strtoupper($orderDirection);
	if ($orderDirection != 'DESC'){
		$orderDirection = 'ASC';	
	}
	
	
	if ($SQL_FILTER_BASE == ''){
		$SQL_FILTER_BASE = '(1)';
		
		if ($otherInfo['bxafStatus']){
			$SQL_FILTER_BASE = "(`bxafStatus` = 0)";	
		}
			
	} else {
		$SQL_FILTER_BASE = "({$SQL_FILTER_BASE})";	
		
		if ($otherInfo['bxafStatus']){
			$SQL_FILTER_BASE = "({$SQL_FILTER_BASE}) AND (`bxafStatus` = 0)";	
		}
	}
	
	
	
	
	if ($searchKeyword != ''){
		$SQL_FILTER_SEARCH = array();
		foreach($columnMapping as $tempKey => $currentColumn){
			$queries[] = "(`{$currentColumn}` LIKE '%{$searchKeyword}%')";
		}
		$SQL_FILTER_SEARCH[] = '(' . implode(' OR ', $queries) . ')';
		$SQL_FILTER_SEARCH = '(' . implode(' AND ', $SQL_FILTER_SEARCH) . ')';
	} else {
		$SQL_FILTER_SEARCH = '(1)';	
	}
	
	$results = array();
	
	
	if ($count > 0){
		$results['Current']['Data'] 	= "SELECT * FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH} ORDER BY {$orderByColumnSection} {$orderDirection} LIMIT {$start}, {$count}";
		$results['Current']['Count'] 	= "SELECT count(*) FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH} LIMIT {$start}, {$count}";
	} elseif ($count == -1){
		$results['Current']['Data'] 	= "SELECT * FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH} ORDER BY {$orderByColumnSection} {$orderDirection}";
		$results['Current']['Count'] 	= "SELECT count(*) FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH}";
	}
	
	if (true){
		$results['Base']['Data'] 		= "SELECT * FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} ORDER BY {$orderByColumnSection} {$orderDirection}";
		$results['Base']['Count'] 		= "SELECT count(*) FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE}";
	}
	
	if (true){
		$results['Search']['Data'] 		= "SELECT * FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH} ORDER BY {$orderByColumnSection} {$orderDirection}";
		$results['Search']['Count'] 	= "SELECT count(*) FROM `{$SQL_TABLE}` WHERE {$SQL_FILTER_BASE} AND {$SQL_FILTER_SEARCH}";
	}
	
	
	
	
	
	return $results;
	
}


function getSearchTablePreferences($table = '', $mode = 'browse'){
	
	if (($mode == '') || ($mode == 'browse')){
		$getBrowsableColumns = getBrowsableColumns($table);
	} else {
		$getBrowsableColumns = getExportableColumns($table);
	}
	
	foreach($getBrowsableColumns as $tempKey => $currentColumn){
		$results[$currentColumn] = getHeaderDisplayName($table, $currentColumn, 1);	
	}
	
	return $results;
	
}


function getSearchTablePreferences2($table = '', $mode = 'browse'){
	
	if (($mode == '') || ($mode == 'browse')){
		$getBrowsableColumns = getBrowsableColumns($table);
	} else {
		$getBrowsableColumns = getExportableColumns($table);
	}
	
	foreach($getBrowsableColumns as $currentColumn => $tempValue){
		$results[$currentColumn] = getHeaderDisplayName($table, $currentColumn, 1);	
	}
	
	return $results;
	
}

function getSearchColumnIDForDataTable($column = NULL, $table = ''){
	
	global $BXAF_CONFIG;
	
	if ($column == '') return 0;
	
	$key = array_search($column, getTablePreferences($table));
	
	return $key + 1;
}

function getSearchColumnSettingsForDataTable($table = ''){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	$standardSettings = getSearchTablePreferences($table);

	$preferences = $BXAF_CONFIG['SETTINGS']["{$table}_Column_Order"];
		
	$columnID = 0;
	foreach ($standardSettings as $currentSQL => $display){
		
		$columnID = $columnID + 1;
		
		if (array_size($preferences) > 0){
			if (in_array($currentSQL, $preferences)){
				$results[$columnID] = 1;
			} else {
				$results[$columnID] = 0;
			}
		} else {
			$results[$columnID] = 1;
		}
	}
	
	return $results;
}

function getDefaultSearchColumnSettingsForDataTable($table = ''){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	return $BXAF_CONFIG['SETTINGS']["{$table}_Column_Order_Default"];

}


?>