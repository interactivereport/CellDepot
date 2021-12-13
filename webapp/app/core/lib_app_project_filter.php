<?php

function getProject_Species_ValueCountForFilter($SQL_CONDITION = ''){
	global $APP_CONFIG;
	
	$SQL_CONDITION = str_replace("(`ID` IN", "(`Record_ID` IN", $SQL_CONDITION);

	return getColumnIndexValueCount($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species'], $SQL_CONDITION);
}

function getProject_Diseases_ValueCountForFilter($SQL_CONDITION = ''){
	global $APP_CONFIG;
	
	$SQL_CONDITION = str_replace("(`ID` IN", "(`Record_ID` IN", $SQL_CONDITION);

	return getColumnIndexValueCount($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Diseases'], $SQL_CONDITION);
}

?>