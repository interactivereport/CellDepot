<?php

$appObj = array();
$appObj['Table'] 			= $APP_CONFIG['TABLES']['PROJECT'];
$appObj['Columns']			= $BXAF_CONFIG['SETTINGS']['Project_Filter_Candidates'];
$appObj['Search_URL']		= 'app_project_search.php';
$appObj['EXE']				= $PAGE['EXE'];

include('template/php/components/app_filter_content.php');

?>