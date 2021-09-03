<?php

function isGuestUser(){
	
	global $BXAF_CONFIG;
	
	if ($BXAF_CONFIG['GUEST_ACCOUNT'] != ''){
		return ($BXAF_CONFIG['GUEST_ACCOUNT'] == $_SESSION['User_Info']['Login_Name']);
	} else {
		return false;	
	}
	
}


function isDeveloperUser(){
	
	global $BXAF_CONFIG;

	$userEmail = $_SESSION['User_Info']['Email'];
	
	if (in_array($userEmail, $BXAF_CONFIG['Developer_User_Email'])){
		return true;	
	} else {
		return false;	
	}
	
}

function isAdminUser(){
	
	global $BXAF_CONFIG;
	
	if (isDeveloperUser()){
		return true;	
	}

	$userEmail = $_SESSION['User_Info']['Email'];
	
	if (in_array($userEmail, $BXAF_CONFIG['Admin_User_Email'])){
		return true;	
	} else {
		return false;	
	}
	
}

function isManagerUser(){
	
	global $BXAF_CONFIG;
	
	if (isDeveloperUser() || isAdminUser()){
		return true;	
	}

	$userEmail = $_SESSION['User_Info']['Email'];
	
	if (in_array($userEmail, $BXAF_CONFIG['Manager_User_Email'])){
		return true;	
	} else {
		return false;	
	}
	
}



function isPowerUser(){
	
	global $BXAF_CONFIG;
	
	if (isAdminUser() || isDeveloperUser()){
		return true;	
	}

	$userEmail = $_SESSION['User_Info']['Email'];
	
	if (in_array($userEmail, $BXAF_CONFIG['Power_User_Email'])){
		return true;	
	} else {
		return false;	
	}
	
}

function getUserInfoFromEmails($emails = NULL){

	global $APP_CONFIG;
	
	if (!is_array($emails)){
		$emails = array($emails);	
	}
	
	$emailString = "'" . implode("','", $emails) . "'";
	
	if (array_size($emails) <= 0) return false;
	
	$SQL = "SELECT `ID`, `Login_Name`, `Name`, `Email` FROM `tbl_bxaf_login` WHERE `Email` IN ({$emailString})";
	
	$rawData = $APP_CONFIG['SQL_USER_CONN']->query($SQL);
		
	while($row = $rawData->fetchArray(SQLITE3_ASSOC)){
		$results[] = $row;
	}	
	
	return $results;
}


function getAllUsersFromSQLiteDB(){
	
	global $APP_CONFIG, $BXAF_CONFIG, $APP_CACHE;
	
	if (isset($APP_CACHE[__FUNCTION__])){
		return $APP_CACHE[__FUNCTION__];
	}

	if (true){
		$db_conn = bxaf_get_user_db_connection();
		$SQL = "SELECT * FROM `{$BXAF_CONFIG['TBL_BXAF_LOGIN']}` WHERE (`bxafStatus` IS NULL) OR (`bxafStatus` < 5)";
		$login_users = $db_conn->query($SQL);

		while($row = $login_users->fetchArray(SQLITE3_ASSOC)){
			$results[$row['ID']] = $row;
		}
	}
	
	
	$APP_CACHE[__FUNCTION__] = $results;
	
	return $results;
	
}

function getUserButtonClass($userID = 0){
	
	global $APP_CACHE;
	
	if ($userID == $_SESSION['User_Info']['ID']){
		return 'btn-primary';	
	}
	
	if (isset($APP_CACHE[__FUNCTION__][$userID])){
		return $APP_CACHE[__FUNCTION__][$userID];
	}
	
	$allClasses = array('btn-primary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info', 'btn-dark', 'btn-secondary',  'btn-light', 
						'btn-outline-primary', 'btn-outline-secondary', 'btn-outline-success', 'btn-outline-danger', 'btn-outline-warning', 'btn-outline-info', 'btn-outline-dark'
				);
				
	unset($allClasses[0]);
	
	$totalAvailable = array_size($allClasses);
	
	$currentSize = array_size($APP_CACHE[__FUNCTION__]);
	
	if ($currentSize < $totalAvailable){
		$class = $allClasses[$currentSize + 1];
	} else {
		$currentSize = $currentSize % $totalAvailable;
		
		$class = $allClasses[$currentSize + 1];
	}
	
	
	
	$APP_CACHE[__FUNCTION__][$userID] = $class;
	
	return $class;
	
}


?>