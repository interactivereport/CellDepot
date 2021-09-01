<?php

function isManager(){
	
	global $BXAF_CONFIG;
	
	if (isAdminUser() || isPowerUser()){
		return true;	
	}

	$userEmail = $_SESSION['User_Info']['Email'];
	
	if (in_array($userEmail, $BXAF_CONFIG['Manager_User_Email'])){
		return true;	
	} else {
		return false;	
	}
	
}

function getAllUserNames(){
	
	global $APP_CONFIG, $BXAF_CONFIG, $APP_CACHE;
	
	if (isset($APP_CACHE[__FUNCTION__])){
		return $APP_CACHE[__FUNCTION__];
	}
	
	if (true){
		$db_conn = bxaf_get_user_db_connection();
		$SQL = "SELECT `Name` FROM `{$BXAF_CONFIG['TBL_BXAF_LOGIN']}` WHERE (`bxafStatus` IS NULL) OR (`bxafStatus` < 5)";
		$login_users = $db_conn->query($SQL);

		while($row = $login_users->fetchArray(SQLITE3_ASSOC)){
			$results[] = $row['Name'];
		}
	}
	
	$results = array_clean($results, 0, 1, 1, 0);
	
	$APP_CACHE[__FUNCTION__] = $results;
	
	return $results;
	
}




function getAllUsersGroupByEmail($includeAllPossibleEmails = 1){
	
	global $APP_CONFIG, $BXAF_CONFIG, $APP_CACHE;
	
	if (true){
		$cacheKey = __FUNCTION__ . '::' . $APP_CONFIG['VERSION']['CACHE'] . '::' . $includeAllPossibleEmails;
		
		if (isset($APP_CACHE[$cacheKey])){
			return $APP_CACHE[$cacheKey];
		}
		
		$resultsFromCache = getRedisCache($cacheKey);
	
		if (!(is_null($resultsFromCache) || $resultsFromCache == false)){
			return $resultsFromCache;	
		}
	}
	
	

	$results = array();

	if (true){
		$getAllUsersFromSQLiteDB = getAllUsersFromSQLiteDB();
		
		foreach($getAllUsersFromSQLiteDB as $userID => $userInfo){
			$results[$userInfo['Email']] = trim($userInfo['Name']);
		}
	}
	
	
	if (true){	
		$SQL = "SELECT * FROM `{$APP_CONFIG['TABLES']['WORKFRONT_USER']}` WHERE (`bxafStatus` = 0)";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $userID => $userInfo){
			if ($results[$userInfo['Email']] == ''){
				$results[$userInfo['Email']] = trim($userInfo['Name']);
			}
		}
		
	}
	
	
	
	
	
	if ($includeAllPossibleEmails){
		$SQL = "SELECT `Email`, `Name` FROM `{$APP_CONFIG['TABLES']['Additional_Email']}`";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $email => $name){
			if ($results[$email] == ''){
				$results[$email] = trim($name);
			}
		}
		
	} else {
		$SQL = "SELECT `Email`, `Name` FROM `{$APP_CONFIG['TABLES']['Additional_Email']}` WHERE (`Internal` = 1)";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $email => $name){
			if ($results[$email] == ''){
				$results[$email] = trim($name);
			}
		}
		
	}
	
	
	foreach($results as $email => $name){
		$finalResults[trim(strtolower($email))]	= ucwords(trim($name));
	}
	

	
	if (true){
		natcasesort($finalResults);
		
		$APP_CACHE[$cacheKey] = $finalResults;
		
		putRedisCache(array($cacheKey => $finalResults));
		
		return $finalResults;
	}
	
	
	
}

function getInternalUsersGroupByEmail(){
	return getAllUsersGroupByEmail(0);
}

function getAllUsersGroupByName($includeAllPossibleEmails = 1){
	
	global $APP_CONFIG, $BXAF_CONFIG, $APP_CACHE;
	
	if (true){
		$cacheKey = __FUNCTION__ . '::' . $APP_CONFIG['VERSION']['CACHE'] . '::' . $includeAllPossibleEmails;
		
		if (isset($APP_CACHE[$cacheKey])){
			return $APP_CACHE[$cacheKey];
		}
		
		$resultsFromCache = getRedisCache($cacheKey);
	
		if (!(is_null($resultsFromCache) || $resultsFromCache == false)){
			return $resultsFromCache;	
		}
	}
	
	

	$results = array();

	if (true){
		$getAllUsersFromSQLiteDB = getAllUsersFromSQLiteDB();
		
		foreach($getAllUsersFromSQLiteDB as $userID => $userInfo){
			$results[trim($userInfo['Name'])] = trim($userInfo['Email']);
		}
	}
	
	
	if (true){	
		$SQL = "SELECT * FROM `{$APP_CONFIG['TABLES']['WORKFRONT_USER']}` WHERE (`bxafStatus` = 0)";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $userID => $userInfo){
			$results[trim($userInfo['Name'])] = trim($userInfo['Email']);
		}
		
	}
	
	

	
	if ($includeAllPossibleEmails){
		$SQL = "SELECT `Name`, `Email` FROM `{$APP_CONFIG['TABLES']['Additional_Email']}`";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $name => $email){
			$results[trim($name)] = trim($email);
		}
		
	} else {
		$SQL = "SELECT `Name`, `Email` FROM `{$APP_CONFIG['TABLES']['Additional_Email']}` WHERE (`Internal` = 1)";
		$SQL_RESULTS = getSQL_Data($SQL, 'GetAssoc', 1);
		
		foreach($SQL_RESULTS as $name => $email){
			$results[trim($name)] = trim($email);
		}
		
	}
	
	
	
	
	foreach($results as $name => $email){
		$finalResults[ucwords(trim($name))]	= trim(strtolower($email));
	}
	

	
	if (true){
		natcasesort($finalResults);
		
		$APP_CACHE[$cacheKey] = $finalResults;
		
		putRedisCache(array($cacheKey => $finalResults));
		
		return $finalResults;
	}
	
	
	
}

function getInternalUsersGroupByName(){
	return getAllUsersGroupByName(0);
}

?>