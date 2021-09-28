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
	
	echo printMsg('');
	
	
	$filesize_h5ad_db	= $currentProject['File_h5ad_filesize'];
	$filesize_h5ad_file = filesize("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
	$filesize_csc_db	= $currentProject['File_CSCh5ad_filesize'];
	$filesize_csc_file	= filesize("{$currentProject['File_Directory']}/{$currentProject['File_Name']}");
	$csc_status			= $currentProject['File_CSCh5ad_status'];
	$h5ad_status		= $currentProject['File_h5ad_status'];
	$h5ad_version		= $currentProject['File_h5ad_info_version'];
	
	$needToBuildCSC 	= 0;
	$needToGetH5ADInfo 	= 0;
	$needToResetCSCInfo	= 0;
	$is_csc_file		= NULL;
	
	
	echo printMsg("[{$ID}]Processing Project (ID: {$ID})");
	
	
	if ($filesize_h5ad_db == $filesize_h5ad_file){
		echo printMsg("[{$ID}]File has not been since project was created");
		
		if ($h5ad_status == 0){
			
			//Need to run getH5ad
			echo printMsg("[{$ID}]h5ad_info is not available.");

			$needToGetH5ADInfo 	= 1;
			$needToResetCSCInfo	= 1;
			
			
		} elseif ($h5ad_version != $APP_CONFIG['CELLXGENE_getH5adInfo_version']){
			
			//Need to run getH5ad
			echo printMsg("[{$ID}]h5adInfo is outdated.");
			
			$needToGetH5ADInfo 	= 1;
			
		} elseif (!$csc_status){

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
				echo printMsg("[{$ID}]Need to create CSC file..");
				$needToBuildCSC = 1;
			}
		}
	} else {
		echo printMsg("[{$ID}]File has been changed since project was created");
		
		$needToGetH5ADInfo 	= 1;
		
		
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
			
		} else {
			echo printMsg("[{$ID}]Need to create CSC file.");
			$needToBuildCSC = 1;
		}
	}
	
	if (!$needToResetCSCInfo && !$needToGetH5ADInfo && !$needToBuildCSC){
		
		echo printMsg("[{$ID}]No need to take any action. Move to the next record.");
		
		continue;
		
	} else {
		if ($needToResetCSCInfo){
			echo printMsg("[{$ID}]Resetting CDC info.");
			
			$dataArray = array();
			$dataArray['File_CSCh5ad_status'] 	= 0;
			$dataArray['File_CSCh5ad_filesize'] = 0;
			$dataArray['File_Directory_CSCh5ad'] = '';	
			$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
			executeSQL($SQL);
			clearCache();
		}
	
		if ($needToGetH5ADInfo){
			
			echo printMsg("[{$ID}]Running get_h5ad_info()");
			
			$get_h5ad_info = get_h5ad_info("{$currentProject['File_Directory']}/{$currentProject['File_Name']}", true);
			
			if ($get_h5ad_info['cellN'] > 0){
				echo printMsg("[{$ID}]get_h5ad_info is good. Saving h5ad info.");
				
				$dataArray = array();
				$dataArray['File_h5ad_status'] 			= 1;
				$dataArray['Cell_Count'] 				= $get_h5ad_info['cellN'];
				$dataArray['Gene_Count'] 				= $get_h5ad_info['geneN'];
				$dataArray['File_h5ad_info'] 			= json_encode($get_h5ad_info);
				$dataArray['File_h5ad_info_version'] 	= $APP_CONFIG['CELLXGENE_getH5adInfo_version'];
				$dataArray['File_Size'] 				= $dataArray['File_h5ad_filesize'] = $filesize_h5ad_file;
				
	
				if ($get_h5ad_info['csc']){
					$dataArray['File_CSCh5ad_status'] 		= 1;
					$dataArray['File_CSCh5ad_filesize'] 	= $dataArray['File_Size'];
					$dataArray['File_Directory_CSCh5ad'] 	= $currentProject['File_Directory'];
				}
	
				if (true){
					//Project
					$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
					executeSQL($SQL);
					addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $currentProject, $dataArray);
				}
				
				if (true){
					//Search Gene Cache
					$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']}` WHERE `Project_ID` = '{$ID}'";
					executeSQL($SQL);
				}
				
				if (array_size($get_h5ad_info['genes']) > 0){
					
					echo printMsg("[{$ID}]Indexing gene expression");
					
					//Project Gene Index
					$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_INDEX']}` WHERE `Project_ID` = '{$ID}'";
					executeSQL($SQL);
					
					
					$allGeneExpressions = array();
					foreach($get_h5ad_info['genes'] as $currentGenes => $currentExpression){
						$currentGenes 		= trim(strtoupper($currentGenes));
						$currentExpression	= abs(floatval($currentExpression));
						
						if ($currentGenes == '') continue;
						if ($currentExpression <= 0) continue;
						
						$currentGenes = array_clean(explode('|', $currentGenes));
						
						foreach($currentGenes as $tempKeyX => $currentGene){
							$allGeneExpressions[$currentGene] = $currentExpression;
						}
						
					}
					
					
					$bulkArray = array();
					$currentIndex = -1;
					
					foreach($allGeneExpressions as $currentGene => $currentExpression){
						$currentIndex++;
						$bulkArray[$currentIndex]['Project_ID'] 		= $ID;
						$bulkArray[$currentIndex]['Gene_Index'] 		= getNameIndexExpress($APP_CONFIG['TABLES']['GENE_INDEX'], $currentGene);
						$bulkArray[$currentIndex]['Gene_Expression'] 	= $currentExpression;
					}
					
					$SQL = getInsertMultipleSQLQuery($APP_CONFIG['TABLES']['PROJECT_GENE_INDEX'], $bulkArray);
					executeSQL($SQL);
				}
				
				
				clearCache();
			}	
		}
		
		if ($needToBuildCSC){
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
				
				
				$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']}` WHERE `Project_ID` = '{$ID}'";
				executeSQL($SQL);
				clearCache();
	
			}
	
		}
	
	}
}




?>