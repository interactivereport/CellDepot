<?php

function update_user_settings($key = '', $value = '', $userID = 0){
	
	global $APP_CONFIG;
	
	if ($APP_CONFIG['TABLES']['SETTINGS'] == '') return false;
	
	if ($key == '') return false;
	
	if ($userID == 0){
		$userID = $_SESSION['User_Info']['ID'];	
	}
	
	$dataArray = array();
	
	$dataArray['User'] 	= $userID;
	$dataArray['Key'] 	= $key;
	
	if (is_array($value)){
		$dataArray['Value'] = json_encode($value);
		$dataArray['JSON'] 	= 1;
	} else {
		$dataArray['Value'] = $value;
		$dataArray['JSON'] 	= 0;
	}

	$SQL = getReplaceSQLQuery($APP_CONFIG['TABLES']['SETTINGS'], $dataArray);
	
	executeSQL($SQL);
	
	return true;
	
}


function get_all_user_settings($userID = 0){
	
	global $APP_CONFIG;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['SETTINGS'];
	
	if ($SQL_TABLE == '') return false;
	
	if ($userID == 0){
		$userID = $_SESSION['User_Info']['ID'];	
	}

	$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE (`User` = '{$userID}')";
	
	$results = getSQL_Data($SQL, 'GetAssoc', 0);
	
	
	foreach($results as $recordID => $currentRecord){
		
		if ($currentRecord['JSON']){
			$settings[$currentRecord['Key']] = json_decode($currentRecord['Value'], true);
		} else {
			$settings[$currentRecord['Key']] = $currentRecord['Value'];
		}
	}
	
	return $settings;
	
}


function load_common_config(){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	ini_set('memory_limit', -1);
	error_reporting(0);
	
	if (true){
		$BXAF_CONFIG_DEFAULT['GZIP_BIN']						= '/bin/pigz -p 3';
		$BXAF_CONFIG_DEFAULT['GUNZIP_BIN']						= '/bin/unpigz -p 3';
		$BXAF_CONFIG_DEFAULT['UNZIP_BIN']						= '/usr/bin/unzip';
		$BXAF_CONFIG_DEFAULT['PHP_BIN']							= '/bin/php';
		$BXAF_CONFIG_DEFAULT['SORT_BIN']						= '/bin/sort --parallel=4';
		$BXAF_CONFIG_DEFAULT['CAT_BIN']							= '/bin/cat';
		$BXAF_CONFIG_DEFAULT['TAIL_BIN']						= '/bin/tail';
		$BXAF_CONFIG_DEFAULT['RM_BIN']							= '/bin/rm';
		$BXAF_CONFIG_DEFAULT['FIND_BIN']						= '/bin/find';
		$BXAF_CONFIG_DEFAULT['BGZIP_DIR'] 						= '/public/programs/tabix/latest/bgzip';
		$BXAF_CONFIG_DEFAULT['TABIX_BIN'] 						= '/public/programs/tabix/latest/tabix';
		$BXAF_CONFIG_DEFAULT['HOMER_PATH']						= '/public/programs/homer/bin';
		$BXAF_CONFIG_DEFAULT['RSCRIPT_BIN'] 					= '/bin/Rscript';
		$BXAF_CONFIG_DEFAULT['RSYNC_BIN'] 						= '/bin/rsync';
		$BXAF_CONFIG_DEFAULT['NETSTAT_BIN'] 					= '/bin/netstat';
		$BXAF_CONFIG_DEFAULT['PS_BIN'] 							= '/bin/ps';
		$BXAF_CONFIG_DEFAULT['PKILL_BIN'] 						= '/bin/pkill';
		$BXAF_CONFIG_DEFAULT['PGREP_BIN']						= '/bin/pgrep';
		$BXAF_CONFIG_DEFAULT['GREP_BIN']						= '/usr/bin/grep';
		$BXAF_CONFIG_DEFAULT['ZIP_BIN']							= '/bin/zip';
		$BXAF_CONFIG_DEFAULT['ENV_BIN']							= '/usr/bin/env';
		$BXAF_CONFIG_DEFAULT['CURL_BIN']						= '/usr/bin/curl';
		$BXAF_CONFIG_DEFAULT['CURL']['Bin']						= $BXAF_CONFIG_DEFAULT['CURL_BIN'];
		
		
		$BXAF_CONFIG_DEFAULT['XLSX2CSV']['Bin']					= '/usr/bin/xlsx2csv';
		$BXAF_CONFIG_DEFAULT['XLSX2CSV']['Path']				= '/tmp/';
		
		$BXAF_CONFIG_DEFAULT['BAD_BROWSERS']['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299']['Year'] = '2017';
		$BXAF_CONFIG_DEFAULT['BAD_BROWSERS']['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/18.17763']['Year'] = '2018';

		
		if ($BXAF_CONFIG['COOKIE_PREFIX'] != ''){
			$BXAF_CONFIG_DEFAULT['XLSX2CSV']['Prefix']				= $BXAF_CONFIG['COOKIE_PREFIX'];
		}
		
		if ($BXAF_CONFIG_DEFAULT['XLSX2CSV']['Prefix'] != ''){
			$BXAF_CONFIG_DEFAULT['XLSX2CSV']['Prefix']				= $BXAF_CONFIG['REDIS_PREFIX'];
		}
		
		// Bad Browsers. Please visit https://control36s.bxgenomics.com/ to determine the browser string.
		$BXAF_CONFIG_DEFAULT['BAD_BROWSERS']['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299']['Year'] = '2017';
		$BXAF_CONFIG_DEFAULT['BAD_BROWSERS']['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36 Edge/18.17763']['Year'] = '2018';

		
		foreach($BXAF_CONFIG_DEFAULT as $tempKey => $tempValue){
			
			if (!isset($BXAF_CONFIG[$tempKey])){
				$BXAF_CONFIG[$tempKey] =  $tempValue;
			}
		}
	}
	
	
	
	if (true){
		$APP_CONFIG['TABLES']['AUDIT_TRAIL']	= 'Template_Audit_Trail';
		$APP_CONFIG['TABLES']['COLUMN_INDEX']	= 'Template_Column_Index';
		$APP_CONFIG['TABLES']['EMAIL']			= 'Template_Email';
		$APP_CONFIG['TABLES']['INFO']			= 'Template_Info';
		$APP_CONFIG['TABLES']['MESSAGE']		= 'Template_Message';
		$APP_CONFIG['TABLES']['SETTINGS']		= 'Template_Settings';
	}
	
	if (true){
		$APP_CONFIG['APP']['Cache_Expiration_Length'] = 86400;
	}
	
	
	if (true){
		$APP_CONFIG['APP']['Search']['Operator']['String'][1]	= 'Contains';
		$APP_CONFIG['APP']['Search']['Operator']['String'][2]	= 'Is';
		$APP_CONFIG['APP']['Search']['Operator']['String'][3]	= 'Is not';
		$APP_CONFIG['APP']['Search']['Operator']['String'][4]	= 'Starts With';
		$APP_CONFIG['APP']['Search']['Operator']['String'][5]	= 'Ends With';
		
		
		$APP_CONFIG['APP']['Search']['Operator']['Number'][10]	= '=';
		$APP_CONFIG['APP']['Search']['Operator']['Number'][11]	= '>';
		$APP_CONFIG['APP']['Search']['Operator']['Number'][12]	= '>=';
		$APP_CONFIG['APP']['Search']['Operator']['Number'][13]	= '<';
		$APP_CONFIG['APP']['Search']['Operator']['Number'][14]	= '<=';
		$APP_CONFIG['APP']['Search']['Operator']['Number'][15]	= '!=';
		
		
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['String'] = $APP_CONFIG['APP']['Search']['Operator']['String'];
		
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Number'] = $APP_CONFIG['APP']['Search']['Operator']['Number'];
		
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Date']['10'] = 'On';
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Date']['14'] = 'Before';
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Date']['12'] = 'After';
		
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Category']['2'] = 'Is';
		$APP_CONFIG['APP']['Search']['Operator_By_Type']['Category']['3'] = 'Is not';
	}
	
	
	
	
	
	
	return true;
	
}



function autoSetDictionaryVariable($currentTable = ''){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	if ($currentTable == '') return false;
	
	$getSQLColumnStructure = getSQLColumnStructure($currentTable);
	
	foreach($getSQLColumnStructure as $currentColumn => $currentColumnDetails){
	
		if (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn])){
			continue;	
		}
		
		$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
		$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
		$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
		$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= false;
		
		if (strpos($currentColumnDetails['Type'], 'int(') !== FALSE){
			$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] = 'number';
		} elseif (strpos($currentColumnDetails['Type'], 'double(') !== FALSE){
			$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] = 'number';
		} elseif (strpos($currentColumnDetails['Type'], 'blob') !== FALSE){
			$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] = 'paragraph';
		} else {
			$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type'] = 'text';
		}
	}
	
	return true;
	
	
}


?>