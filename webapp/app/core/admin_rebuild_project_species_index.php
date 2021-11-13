<?php
include_once('config_init.php');

if (isAdminUser()){

	$SQL = "SELECT `ID`, `Species` FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`Species` != '')";
	$allRecords = getSQL_Data($SQL, 'GetAssoc', 0);
		
	foreach($allRecords as $projectID => $species){
		deleteColumnIndexByRecordID($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $projectID);
		createColumnIndex($APP_CONFIG['TABLES']['PROJECT'], $APP_CONFIG['CONSTANTS']['TABLES']['Project'], $projectID, 'Species', $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species'], $species, 0);
	}
	
	echo printMsg("The project-species index table has been rebuilt.");
	
	clearCache();
	
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>