<?php
include_once('config_init.php');


$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Review_Record'];
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_review.php';
$PAGE['Body'] 		= 'app_project_review_content.php';
$PAGE['EXE'] 		= '';
$PAGE['Barcode']	= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['DataTable'] = true;

if ($ID > 0){
	$projectInfo_Raw = $projectInfo = getProjectByID($ID);
	

	if ($projectInfo['ID'] > 0){
		$projectInfo = processProjectRecord($ID, $projectInfo, 2);
		
		if (isManagerUser()){
			$auditTrails = getAuditTrails($APP_CONFIG['TABLES']['PROJECT'], $projectInfo['ID']);
		}
	}
	
	
	
}


include('template/php/components/page_generator.php');

?>