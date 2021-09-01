<?php


function getTablePreferences($table = '', $mode = 'browse'){
	
	
	if ($mode == 'browse'){
		$getBrowsableColumns = getBrowsableColumns($table);
	} elseif ($mode == 'export'){
		$getBrowsableColumns = getExportableColumns($table);	
	}
	
	foreach($getBrowsableColumns as $currentColumn => $displayName){

		$results[$currentColumn] = getHeaderDisplayName($table, $currentColumn, 1);	
		
	}
	
	return $results;
	
}


function saveColumnSettings($table = '', $settings = NULL){
	
	global $APP_CONFIG;
	
	if (array_size($settings) > 0){
		
		if ($table == $APP_CONFIG['TABLES']['MEASUREMENT']){
			update_user_settings("{$APP_CONFIG['TABLES']['MEASUREMENT']}_Column_Order", $settings);
		}
		
		if ($table == $APP_CONFIG['TABLES']['PROJECT']){
			update_user_settings("{$APP_CONFIG['TABLES']['PROJECT']}_Column_Order", $settings);
		}
	}
	
	return true;
}

function getColumnSettingsForDataTable($table = ''){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	$standardSettings = getTablePreferences($table);

	if ($table == $APP_CONFIG['TABLES']['MEASUREMENT']){
		$preferences = $BXAF_CONFIG['SETTINGS']["{$APP_CONFIG['TABLES']['MEASUREMENT']}_Column_Order"];
	}
	
	if ($table == $APP_CONFIG['TABLES']['PROJECT']){
		$preferences = $BXAF_CONFIG['SETTINGS']["{$APP_CONFIG['TABLES']['PROJECT']}_Column_Order"];
	}
	

	
	$columnID = 0;
	foreach ($standardSettings as $currentSQL => $display){
		
		$columnID = $columnID + 1;
		
		if (in_array($currentSQL, $preferences)){
			$results[$columnID] = 1;
		} else {
			$results[$columnID] = 0;
		}
	}
	
	return $results;
}


?>