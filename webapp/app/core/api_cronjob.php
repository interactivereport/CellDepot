<?php

$BXAF_CONFIG['API'] = 1;

include_once('config_init.php');

$lockDateTimeCutoff = date("Y-m-d H:i:s", strtotime("5 hours ago"));


$SQL = "SELECT `ID` FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`Lock_Status` = 0) OR ((`Lock_Status` = 1) AND (`Lock_DateTime` < '{$lockDateTimeCutoff}')) ORDER BY RAND()";

$IDs = getSQL_Data($SQL, 'GetCol', 0);

$count = 0;
foreach($IDs as $tempKey => $ID){
	

	$SQL = "SELECT * FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`ID` = {$ID}) AND ((`Lock_Status` = 0) OR ((`Lock_Status` = 1) AND (`Lock_DateTime` < '{$lockDateTimeCutoff}')))";
	$currentProject = getSQL_Data($SQL, 'GetRow', 0);
	
	if (array_size($currentProject) <= 0){
		continue;
	}
	
	
	$filesize_h5ad_db	= $currentProject['File_h5ad_filesize'];
	$filesize_h5ad_file = filesize("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
	$filesize_csc_db	= $currentProject['File_CSCh5ad_filesize'];
	$filesize_csc_file	= filesize("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
	$csc_status			= $currentProject['File_h5ad_filesize'];
	
	$needToBuildCSC 	= 0;
	$needToGetH5ADInfo 	= 0;
	$is_csc_file		= NULL;
	
	
	if ($filesize_h5ad_db == $filesize_h5ad_file){
		//File has not been changed after project creation
		echo printMsg("[{$ID}]File has not been changed after project creation");
		
		if ($csc_status){
			//CSC file is already created
			//Do nothing
			echo printMsg("[{$ID}]CSC file is already created");
			continue;	
		} else {
			//CSC file is not available, new project
			echo printMsg("[{$ID}]CSC file is not available");
			
			$is_csc_file = checkCSCh5ad("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
			if ($is_csc_file){
				echo printMsg("[{$ID}]The soruce file is already in CSC format. Copying the info.");
				//H5AD file is in CSC format - Just copy the info
				$dataArray = array();
				$dataArray['File_CSCh5ad_status'] 		= 1;
				$dataArray['File_CSCh5ad_filesize'] 	= $filesize_h5ad_file;
				$dataArray['File_Directory_CSCh5ad'] 	= $currentProject['File_Directory'];
				$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
				executeSQL($SQL);
				addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $currentProject, $dataArray);
				clearCache();
				continue;
			} else {
				echo printMsg("[{$ID}]Need to create CSC file.");
				$needToBuildCSC = 1;
			}
		}
	} else {
		//File has been changed after project creation
		
		$get_h5ad_info = get_h5ad_info("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
		echo printMsg("[{$ID}]Running get_h5ad_info().");
		if ($get_h5ad_info['cellN'] > 0){
			echo printMsg("[{$ID}]Saving h5ad info.");
			$dataArray['File_h5ad_status'] 		= 1;
			$dataArray['Cell_Count'] 			= $get_h5ad_info['cellN'];
			$dataArray['Gene_Count'] 			= $get_h5ad_info['geneN'];
			$dataArray['File_h5ad_info'] 		= json_encode($get_h5ad_info);
			$dataArray['File_Size'] 			= $dataArray['File_h5ad_filesize'] = $filesize_h5ad_file;
			$dataArray['File_CSCh5ad_status'] 	= 0;
			$dataArray['File_CSCh5ad_filesize'] = 0;
			$dataArray['File_Directory_CSCh5ad'] = '';
			$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
			executeSQL($SQL);
			addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $currentProject, $dataArray);
			
			
			$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']} WHERE `Project_ID` = '{$ID}'";
			executeSQL($SQL);
			clearCache();
		}
		
		
		$is_csc_file = checkCSCh5ad("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
		if ($is_csc_file){
			echo printMsg("[{$ID}]The soruce file is already in CSC format. Copying the info.");
			
			//H5AD file is in CSC format - Just copy the info
			$dataArray = array();
			$dataArray['File_CSCh5ad_status'] 		= 1;
			$dataArray['File_CSCh5ad_filesize'] 	= $filesize_h5ad_file;
			$dataArray['File_Directory_CSCh5ad'] 	= $currentProject['File_Directory'];
			$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
			executeSQL($SQL);
			addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $currentProject, $dataArray);
			clearCache();
			continue;
		} else {
			echo printMsg("[{$ID}]Need to create CSC file.");
			$needToBuildCSC = 1;
		}
	}
	
	
	

	

	
	if ($buildCSC){
		$outputDir 		= $BXAF_CONFIG['Server'][($currentProject['File_Server_ID'])]['File_Directory_toCSCh5ad'];
		$output			= "{$outputDir}/{$currentProject['File_Name']}";
	
		$dataArray = array();
		$dataArray['Lock_Status'] 	= 1;
		$dataArray['Lock_DateTime'] = date('Y-m-d H:i:s');
		$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
		executeSQL($SQL);
		
		echo printMsg("[{$ID}]CSC-Started: {$output}");
		
		$results = to_CSC_h5ad($currentProject['File_Name'], $currentProject['File_Directory'], $outputDir);
		
		if (file_exists($output)){
			echo printMsg("[{$ID}]CSC-Finished: {$output}");
			
			$dataArray = array();
			$dataArray['File_Directory_CSCh5ad'] 	= $outputDir;
			$dataArray['File_CSCh5ad_status'] 		= 1;
			$dataArray['File_CSCh5ad_filesize'] 	= $filesize_csc_file;
			$dataArray['Lock_Status'] 				= 0;
			$dataArray['Lock_DateTime'] 			= '';
			
			$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
			executeSQL($SQL);
			addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $currentProject, $dataArray);
			
			
			$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']} WHERE `Project_ID` = '{$ID}'";
			executeSQL($SQL);
			clearCache();

		}
	}
	
	//Move to the next project	
}




?>