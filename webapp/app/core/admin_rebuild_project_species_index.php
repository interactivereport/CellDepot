<?php
include_once('config_init.php');

if (!isAdminUser()){
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";		
	return;
}


if (true){
	$SQL = "SELECT `ID`, `Diseases`, `Species` FROM `{$APP_CONFIG['TABLES']['PROJECT']}`";
	$allRecords = getSQL_Data($SQL, 'GetAssoc', 0);
	
	foreach($allRecords as $projectID => $dataArray){
		
		$diseases = $dataArray['Diseases'];
		$species = $dataArray['Species'];
		
		deleteColumnIndexByRecordID($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $projectID);
		createColumnIndex($APP_CONFIG['TABLES']['PROJECT'], $APP_CONFIG['CONSTANTS']['TABLES']['Project'], $projectID, 'Species',  $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species'],  $species, 0);
		createColumnIndex($APP_CONFIG['TABLES']['PROJECT'], $APP_CONFIG['CONSTANTS']['TABLES']['Project'], $projectID, 'Diseases', $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Diseases'], $diseases, 0);
	}
	
	echo printMsg("The project-species and project-diseases index table has been rebuilt.");
	
}

clearCache();

?>