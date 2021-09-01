<?php
include_once('config_init.php');

$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene'];
//$PAGE['Header']		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_launcher.php';
$PAGE['Body'] 		= 'app_project_launcher_content.php';
$PAGE['EXE'] 		= '';
$PAGE['Barcode']	= '';

$projectInfo = getProjectByID($ID);


if ($projectInfo['ID'] <= 0){
	echo "Error. Please verify your link and try again.";
	exit();
	
} elseif (!file_exists($BXAF_CONFIG['Server'][($projectInfo['File_Server_ID'])]['File_Directory'] . $projectInfo['File_Name'])){
	echo "Error. The data file ({$projectInfo['File_Name']}) does not exist.";
	exit();
} else {
	
	$projectInfo = processProjectRecord($ID, $projectInfo, 2);
	createProjectAccessLog($ID);
	
	if ($projectInfo['Launch_Method_Raw'] == 0){
		$URL = getCellxgeneURL($projectInfo);
	} else {
		$startCellxgeneProcess = startCellxgeneProcess($ID);	
		$URL = $startCellxgeneProcess['URL'];
	}
	
	if ($URL != ''){
		include('template/php/components/page_generator.php');
	} else {
		
		echo "The system could not start the Cellxgene. Please contact us (info@bioinforx.com) for details.";
		exit();
		
	}
}


?>