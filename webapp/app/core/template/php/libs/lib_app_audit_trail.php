<?php

function addAuditTrail($table = '', $recordID = 0, $before = array(), $after = array()){
	
	global $APP_CONFIG;
	
	if ($recordID <= 0) return false;
	
	if ($table == '') return false;
	
	if ($APP_CONFIG['TABLES']['AUDIT_TRAIL'] == '') return false;
	
	$auditTrail = array();
	
	foreach($before as $currentSQL => $beforeValue){
		
		$afterValue = $after[$currentSQL];
	
		if ($beforeValue != $afterValue){
			
			if (isset($after[$currentSQL])){
				$auditTrail[$currentSQL]['Column'] 	= $currentSQL;
				$auditTrail[$currentSQL]['Before'] 	= $beforeValue;
				$auditTrail[$currentSQL]['After'] 	= $after[$currentSQL];
			}
		}
	}
	
	foreach($after as $currentSQL => $afterValue){
		
		$beforeValue = $before[$currentSQL];
		
		if ($beforeValue != $afterValue){
			$auditTrail[$currentSQL]['Column'] 	= $currentSQL;
			$auditTrail[$currentSQL]['Before'] 	= $beforeValue;
			$auditTrail[$currentSQL]['After'] 	= $after[$currentSQL];
		}
	}

	unset($auditTrail['ID']);
	
	$dataArray = $commonArray = array();
	
	if (array_size($auditTrail) > 0){
		
		$commonArray['Date_Time'] 	= date('Y-m-d H:i:s');
		$commonArray['User'] 		= $_SESSION['User_Info']['ID'];
		$commonArray['User_Name'] 	= $_SESSION['User_Info']['Name'];
		$commonArray['Table'] 		= $table;
		$commonArray['Record_ID'] 	= $recordID;
		
		$currentIndex = -1;
		foreach($auditTrail as $currentSQL => $currentValue){
			$currentIndex++;
			$dataArray[$currentIndex] = $commonArray;
			$dataArray[$currentIndex]['Column'] = $currentSQL;
			$dataArray[$currentIndex]['Before'] = $currentValue['Before'];
			$dataArray[$currentIndex]['After'] = $currentValue['After'];
		}
		
		$SQL = getInsertMultipleSQLQuery($APP_CONFIG['TABLES']['AUDIT_TRAIL'], $dataArray);
		
		
		executeSQL($SQL);
	}
	
	return true;
	
}


function addMultipleAuditTrails($table = '', $recordIDs = array(), $after = array()){
	
	global $APP_CONFIG;
	
	if ($APP_CONFIG['TABLES']['AUDIT_TRAIL'] == '') return false;
	
	$recordIDs = id_sanitizer($recordIDs, 0, 1, 0, 2);
	
	if ($recordIDs == '') return false;
	
	if ($table == '') return false;

	$SQL = "SELECT * FROM `{$table}` WHERE `ID` IN ({$recordIDs})";
	$before = getSQL_Data($SQL, 'GetAssoc', 0);
	
	$dataArray = array();
	$currentIndex = -1;
	
	$currentDateTime = date('Y-m-d H:i:s');

	foreach($before as $recordID => $beforeArray){
		
		foreach($after as $currentSQL => $afterValue){
			
			$beforeValue = $beforeArray[$currentSQL];
			
			if ($beforeValue != $afterValue){
				
				$currentIndex++;
				$dataArray[$currentIndex]['Date_Time'] 	= $currentDateTime;
				$dataArray[$currentIndex]['User'] 		= $_SESSION['User_Info']['ID'];
				$dataArray[$currentIndex]['User_Name'] 	= $_SESSION['User_Info']['Name'];
				$dataArray[$currentIndex]['Table'] 		= $table;
				$dataArray[$currentIndex]['Record_ID'] 	= $recordID;
				$dataArray[$currentIndex]['Column'] 	= $currentSQL;
				$dataArray[$currentIndex]['Before'] 	= $beforeValue;
				$dataArray[$currentIndex]['After'] 		= $afterValue;
				
			}
			
		}
	}

	
	if (array_size($dataArray) > 0){
		$SQL = getInsertMultipleSQLQuery($APP_CONFIG['TABLES']['AUDIT_TRAIL'], $dataArray);
		executeSQL($SQL);	
	}
	
	return true;
	
}



function addMultipleAuditTrails2($table = '', $recordIDs = array(), $before = array(), $after = array()){
	
	global $APP_CONFIG;
	
	if ($APP_CONFIG['TABLES']['AUDIT_TRAIL'] == '') return false;
	
	$recordIDs = id_sanitizer($recordIDs, 0, 1, 0, 2);
	
	if ($recordIDs == '') return false;
	
	if ($table == '') return false;


	$dataArray = array();
	$currentIndex = -1;
	
	$currentDateTime = date('Y-m-d H:i:s');
	
	$recordIDs = id_sanitizer($recordIDs, 0, 1, 0, 1);
	
	foreach($recordIDs as $tempKey => $recordID){

		foreach($after as $currentSQL => $afterValue){
			
			$beforeValue = $before[$currentSQL];
			
			if ($beforeValue != $afterValue){
				
				$currentIndex++;
				$dataArray[$currentIndex]['Date_Time'] 	= $currentDateTime;
				$dataArray[$currentIndex]['User'] 		= $_SESSION['User_Info']['ID'];
				$dataArray[$currentIndex]['User_Name'] 	= $_SESSION['User_Info']['Name'];
				$dataArray[$currentIndex]['Table'] 		= $table;
				$dataArray[$currentIndex]['Record_ID'] 	= $recordID;
				$dataArray[$currentIndex]['Column'] 	= $currentSQL;
				$dataArray[$currentIndex]['Before'] 	= $beforeValue;
				$dataArray[$currentIndex]['After'] 		= $afterValue;
				
			}
			
		}
	}


	
	if (array_size($dataArray) > 0){
		$SQL = getInsertMultipleSQLQuery($APP_CONFIG['TABLES']['AUDIT_TRAIL'], $dataArray);
		executeSQL($SQL);	
	}
	
	return true;
	
}

function getAuditTrails($table = '', $recordID = 0){
	global $APP_CONFIG;
	
	if ($recordID <= 0) return false;
	
	if ($table == '') return false;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['AUDIT_TRAIL'];

	$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE (`Table` = '{$table}') AND (`Record_ID` = '{$recordID}') ORDER BY `ID` DESC";
	
	return getSQL_Data($SQL, 'GetAssoc', 0);
	
}


?>