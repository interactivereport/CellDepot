<?php

function getProjectConditionForSearch($currentTable = '', $currentColumn = '', $searchValue = '', $operator = 2){
	global $APP_CONFIG;
	return searchColumnIndex($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']["Project::{$currentColumn}"], $searchValue, $operator);
}

function getProject_Species_MenuForSearch(){
	global $APP_CONFIG;
	return getColumnIndexMenu($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species']);
}

function getProject_Species_Values(){
	global $APP_CONFIG;
	return array_values(getColumnIndexMenu($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species']));
}

function getProject_Diseases_MenuForSearch(){
	global $APP_CONFIG;
	return getColumnIndexMenu($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Diseases']);
}

function getProject_Diseases_Values(){
	global $APP_CONFIG;
	return array_values(getColumnIndexMenu($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Diseases']));
}


?>