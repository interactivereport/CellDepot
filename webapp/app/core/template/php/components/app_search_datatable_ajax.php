<?php

/*
$appObj = array();
$appObj['Table'] 				= $currentTable;
$appObj['Converter_Function'] 	= 'processMeasurementRecordForBrowse';
$appObj['Table_Preferences']	= getTablePreferences($APP_CONFIG['TABLES']['MEASUREMENT']);
*/

if (!isset($appObj)){
	return;
}

$currentTable 		= $appObj['Table'];
$function			= $appObj['Converter_Function'];
$tablePreferences	= $appObj['Table_Preferences'];

$results = array();

$key = $_GET['temp'];
if (strpos($key, 'Temp_') === 0){
	$dataArray = getRedisCache($key);
	$IDs = id_sanitizer($dataArray['id'], 0, 1, 0, 2);
}

if ($IDs != ''){
	
	$allColumns						= array_keys($tablePreferences);
	
	//$results['POST'] 				= $_POST;
	

	$SQLs 							= getSQLFromDataTable($currentTable, "(`ID` IN ({$IDs}))", $allColumns, $_POST);
	
	//$results['SQLs'] 				= $SQLs;
	
	$results['recordsTotal'] 		= getSQL_Data($SQLs['Base']['Count'], 'GetOne', 1);
	
	$results['recordsFiltered'] 	= getSQL_Data($SQLs['Search']['Count'], 'GetOne', 1);
	
	if ($results['recordsFiltered'] > 0){

		$allRecords						= getSQL_Data($SQLs['Current']['Data'], 'GetArray', 1);
		
		foreach($allRecords as $tempKey => $currentRecord){
			
			$currentRecordID = $currentRecord['ID'];
			
			if (function_exists($function)){
				$currentRecord = $function($currentRecordID, $currentRecord);
			}
			
			$recordToReport = array();
			
			if (!$appObj['Disable_Checkboxes']){
				$recordToReport[0] = "<div class='text-center'><input type='checkbox' class='recordCheckbox' value='{$currentRecordID}'/></div>";
			} else {
				$recordToReport[0] = "<div class='text-center'></div>";
			}
			
			
			foreach($allColumns as $tempKeyX => $currentHeader){
				$recordToReport[] = $currentRecord[$currentHeader];
			}
	
			$results['data'][] = $recordToReport;
		}
	} else {
		$results['data'] = array();	
	}
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($results,  JSON_PARTIAL_OUTPUT_ON_ERROR );
	exit();
	
} else {
	exit();	
}




?>