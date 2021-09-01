<?php

include_once('config_init.php');

if ($_GET['key'] != ''){
	$IDs = getRedisCache($_GET['key']);
} elseif ($_GET['ids'] != ''){
	$IDs = $_GET['ids'];
}

$IDs = id_sanitizer($IDs, 0, 1, 0, 2);
$SQL = "SELECT * FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`ID` IN ({$IDs}))";

$appObj = array();
$appObj['All_Records']				= getSQL_Data($SQL, 'GetAssoc', 1);
$appObj['Table_Headers']			= getSearchTablePreferences2($APP_CONFIG['TABLES']['PROJECT'], 'export');
$appObj['Table_Headers_To_Display'] = $BXAF_CONFIG['SETTINGS']["{$APP_CONFIG['TABLES']['PROJECT']}_Column_Order"];
$appObj['File_Name']				= 'projects.csv';

include('template/php/components/app_search_download.php');

?>