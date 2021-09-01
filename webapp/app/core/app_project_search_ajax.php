<?php

include_once('config_init.php');

$appObj = array();
$appObj['Table'] 		= $APP_CONFIG['TABLES']['PROJECT'];
$appObj['Column'] 		= $_GET['column'];
$appObj['Component']	= $_GET['valueID'];
$appObj['Key']			= $_GET['key'];
$appObj['Row']			= $_GET['row'];

include('template/php/components/app_search_ajax.php');

?>