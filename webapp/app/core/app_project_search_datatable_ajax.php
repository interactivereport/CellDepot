<?php
include_once('config_init.php');

$appObj = array();
$appObj['Table']				= $APP_CONFIG['TABLES']['PROJECT'];
$appObj['Table_Preferences']	= getSearchTablePreferences($APP_CONFIG['TABLES']['PROJECT']);
$appObj['Converter_Function']	= 'convertProject_DataTable';

include('template/php/components/app_search_datatable_ajax.php');

?>
