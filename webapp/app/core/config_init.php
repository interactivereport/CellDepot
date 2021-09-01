<?php

include_once(dirname(dirname(__FILE__)) . "/config.php");

include_once('./template/php/libs/lib_general_common.php');

include_once('./template/php/libs/lib_general_db_MySQL.php');
include_once('./template/php/libs/lib_general_db_Redis.php');
include_once('./template/php/libs/lib_app_audit_trail.php');

include_once('config_version.php');
include_once('config_app.php');

include_once('lib_app_init.php');
include_once('lib_app_gene_plot.php');
include_once('lib_app_project.php');
include_once('lib_app_project_filter.php');
include_once('lib_app_project_search.php');
include_once('lib_app_project_access_log.php');
include_once('lib_app_cellxgene.php');

include_once('lib_app_settings.php');
include_once('lib_app_user.php');

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['style'] = true;


//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


initialize();

if (isset($_GET)){
	foreach($_GET as $tempKey => $tempValue){
		if (!is_array($tempValue)){
			$_GET[$tempKey] = trim($tempValue);	
		}
	}
	
	$_GET['ID'] = intval($_GET['ID']);
	$_GET['id'] = intval($_GET['id']);
	
	if ($_GET['ID'] <= 0){
		$_GET['ID'] = $_GET['id'];	
	}
	$id = $ID = $_GET['ID'];
	unset($_GET['id']);
}

if (isset($_POST)){
	foreach($_POST as $tempKey => $tempValue){
		if (!is_array($tempValue)){
			$_POST[$tempKey] = trim($tempValue);	
		}
	}
}





$PAGE = array();
$PAGE['Title'] = $BXAF_CONFIG['BXAF_PAGE_TITLE'];

?>