<?php

function update_key_value($key = '', $value = ''){
	
	global $APP_CONFIG;
	
	if ($APP_CONFIG['TABLES']['INFO'] == '') return false;
	
	if ($key == '') return false;
	
	$dataArray = array();
	
	$dataArray['Key'] 	= $key;

	if (is_array($value)){
		$dataArray['Value'] = json_encode($value);
		$dataArray['JSON'] 	= 1;
	} else {
		$dataArray['Value'] = $value;
		$dataArray['JSON'] 	= 0;
	}
	
		
	$SQL = getReplaceSQLQuery($APP_CONFIG['TABLES']['INFO'], $dataArray);
	
	executeSQL($SQL);
	
	return true;
	
}

function get_key_value($key = ''){
	
	global $APP_CONFIG;
	
	if ($APP_CONFIG['TABLES']['INFO'] == '') return false;
	
	if ($key == '') return false;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['INFO'];

	$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE (`Key` = '{$key}') LIMIT 1";
	
	$results = getSQL_Data($SQL, 'GetRow', 0);
	
	if ($results['JSON']){
		return json_decode($results['Value'], true);	
	} else {
		return $results['Value'];
	}
	
	
}


?>