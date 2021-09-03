<?php

function createTemplateMessage($inputArray = array()){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['MESSAGE'];
	
	$dataArray = array();
	

	$dataArray['Date']					= date('Y-m-d');
	$dataArray['Date_Time']	 			= date('Y-m-d H:i:s');
	$dataArray['User_ID'] 				= $_SESSION['User_Info']['ID'];
	$dataArray['User_Name'] 			= "{$_SESSION['User_Info']['First_Name']} {$_SESSION['User_Info']['Last_Name']}";
	$dataArray['User_Name_Initial']		= substr($_SESSION['User_Info']['First_Name'], 0, 1) . substr($_SESSION['User_Info']['Last_Name'], 0, 1);
	$dataArray['Table'] 				= $SQL_TABLE;
	
	for ($i = 1; $i <= 3; $i++){
		$dataArray["Record_Table_{$i}"]	= $inputArray["Record_Table_{$i}"];
		$dataArray["Record_ID_{$i}"]	= $inputArray["Record_ID_{$i}"];
	}
	
	$dataArray['Parent_Message_ID']		= $inputArray['Parent_Message_ID'];
	$dataArray['Title']					= trim($inputArray['Title']);
	$dataArray['Body']					= trim($inputArray['Body']);
	$dataArray['Recipients']			= json_encode($inputArray['Recipients']);
	
	if (($dataArray['Title'] == '') && ($dataArray['Body'] == '')){
		return false;	
	}

	
	$SQL = getInsertSQLQuery($SQL_TABLE, $dataArray);
	executeSQL($SQL);

	return getLastInsertID();

}


function getTemplateMessagesByIndexes($Record_Table_1 = '', $Record_ID_1 = 0, $Record_Table_2 = '', $Record_ID_2 = 0, $Record_Table_3 = '', $Record_ID_3 = 0){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['MESSAGE'];
	
	$SQL_CONDITIONS = array();
	
	if (true){
		$Record_Table_1 = trim($Record_Table_1);
		$Record_ID_1	= abs(intval($Record_ID_1));
		if (($Record_Table_1 != '') && ($Record_ID_1 > 0)){
			$SQL_CONDITIONS[] = "((`Record_Table_1` = '{$Record_Table_1}') AND (`Record_ID_1` = '{$Record_ID_1}'))";
		}
	}
	
	if (true){
		$Record_Table_2 = trim($Record_Table_2);
		$Record_ID_2	= abs(intval($Record_ID_2));
		if (($Record_Table_2 != '') && ($Record_ID_2 > 0)){
			$SQL_CONDITIONS[] = "((`Record_Table_2` = '{$Record_Table_2}') AND (`Record_ID_2` = '{$Record_ID_2}'))";
		}
	}
	
	if (true){
		$Record_Table_3 = trim($Record_Table_3);
		$Record_ID_3	= abs(intval($Record_ID_3));
		if (($Record_Table_3 != '') && ($Record_ID_3 > 0)){
			$SQL_CONDITIONS[] = "((`Record_Table_3` = '{$Record_Table_3}') AND (`Record_ID_3` = '{$Record_ID_3}'))";
		}
	}
	
	if (array_size($SQL_CONDITIONS) > 0){
		$SQL_CONDITIONS = implode(' AND ', $SQL_CONDITIONS);
		$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE {$SQL_CONDITIONS} ORDER BY `ID` ASC";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 0);	
		
		foreach($SQL_RESULTS as $recordID => $recordInfo){
			$SQL_RESULTS[$recordID]['Recipients_Raw'] = $recordInfo['Recipients'];
			$SQL_RESULTS[$recordID]['Recipients'] = json_decode($recordInfo['Recipients']);
		}
		
		
		return $SQL_RESULTS;
	} else {
		return false;	
	}
	
}



?>