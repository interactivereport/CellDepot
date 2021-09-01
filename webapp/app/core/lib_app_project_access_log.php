<?php

function createProjectAccessLog($projectID = 0){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$projectID = intval($projectID);
	if ($projectID <= 0) return false;
		
	
	$dataArray = array();
	$dataArray['Project_ID'] 	= $projectID;
	$dataArray['Date']			= date('Y-m-d');
	$dataArray['Date_Time']	 	= date('Y-m-d H:i:s');
	$dataArray['User_ID'] 		= $_SESSION['User_Info']['ID'];
	$dataArray['User_Name'] 	= "{$_SESSION['User_Info']['First_Name']} {$_SESSION['User_Info']['Last_Name']}";
		
	$SQL = getInsertSQLQuery($APP_CONFIG['TABLES']['PROJECT_ACCESS_LOG'], $dataArray);
	executeSQL($SQL);

	

	return true;

}

?>