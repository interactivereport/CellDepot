<?php

include_once('config_init.php');

$appObj = array();
$appObj['Table'] 			= $APP_CONFIG['TABLES']['PROJECT'];

$currentIndex = 1;
$appObj['Search'][$currentIndex]['Field'] 		= 'name';
$appObj['Search'][$currentIndex]['Operator'] 	= 1;
$appObj['Search'][$currentIndex]['Value'] 		= '';
$appObj['Search'][$currentIndex]['Logic'] 		= '';

$appObj['Page']['EXE']		= $PAGE['EXE'];
$appObj['Page']['AJAX']		= 'app_project_search_ajax.php';
$appObj['Page']['Reset']	= $PAGE['URL'];




include('template/php/components/app_search_content.php');

?>