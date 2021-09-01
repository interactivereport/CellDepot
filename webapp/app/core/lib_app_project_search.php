<?php

function getProject_Species_MenuForSearch(){
	global $APP_CONFIG;
	return getColumnIndexMenu($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species']);
}

function getProjectConditionForSearch($currentTable = '', $currentColumn = '', $searchValue = '', $operator = 2){
	global $APP_CONFIG;
	
	return searchColumnIndex($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']["Project::{$currentColumn}"], $searchValue, $operator);
}

?>