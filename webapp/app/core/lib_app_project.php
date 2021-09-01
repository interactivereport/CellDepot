<?php


function createProject($inputArray = array()){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$dataArray = array();
	
	
	//Basic Stuffs
	$dataArray['Date']					= date('Y-m-d');
	$dataArray['Date_Time']	 			= date('Y-m-d H:i:s');
	$dataArray['User_ID'] 				= $_SESSION['User_Info']['ID'];
	$dataArray['User_Name'] 			= "{$_SESSION['User_Info']['First_Name']} {$_SESSION['User_Info']['Last_Name']}";

	if (true){
		
		$dataArray['Accession'] 		= $inputArray['Accession'];
		$dataArray['Name'] 				= $inputArray['Name'];
		$dataArray['Species'] 			= implode(', ', splitCategories($inputArray['Species']));
		$dataArray['Year'] 				= $inputArray['Year'];
		$dataArray['Description'] 		= $inputArray['Description'];
		$dataArray['DOI'] 				= $inputArray['DOI'];
		
		if ($inputArray['Project_link'] != ''){
			$dataArray['URL'] 			= $inputArray['Project_link'];
		} elseif ($inputArray['URL'] != ''){
			$dataArray['URL'] 			= $inputArray['URL'];
		}
		
		$dataArray['Notes'] 			= $inputArray['Notes'];
		$dataArray['PMCID'] 			= $inputArray['PMCID'];
		$dataArray['PMID'] 				= $inputArray['PMID'];
		$dataArray['Title'] 			= $inputArray['Title'];
		$dataArray['Launch_Method'] 	= intval($inputArray['Launch_Method']);
		
		
		$dataArray['File_Server_ID'] 	= intval($inputArray['File_Server_ID']);
		
		if ($dataArray['File_Server_ID'] <= 0){
			$dataArray['File_Server_ID'] = 1;	
		}
		
		$dataArray['File_Directory'] 	= $BXAF_CONFIG['Server'][($dataArray['File_Server_ID'])]['File_Directory'];
		$dataArray['File_Name'] 		= str_replace('/', '', $inputArray['File_Name']);
		$dataArray['File_Size'] 		= $dataArray['File_h5ad_filesize'] = filesize("{$dataArray['File_Directory']}/{$dataArray['File_Name']}");
	}
	
	
	if (!file_exists("{$dataArray['File_Directory']}/{$dataArray['File_Name']}")){
		return false;
	}
	
	if ($dataArray['File_h5ad_filesize'] <= 0){
		return false;	
	}
	
	$get_h5ad_info = get_h5ad_info("{$dataArray['File_Directory']}/{$dataArray['File_Name']}");
	
	if ($get_h5ad_info['cellN'] > 0){
		$dataArray['File_h5ad_status'] 	= 1;
		$dataArray['Cell_Count'] 		= $get_h5ad_info['cellN'];
		$dataArray['Gene_Count'] 		= $get_h5ad_info['geneN'];
		$dataArray['File_h5ad_info'] 	= json_encode($get_h5ad_info);
	}
	
	

	$SQL = getInsertSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray);
	executeSQL($SQL);
	
	
	$projectID	= getLastInsertID();
	
	createColumnIndex($APP_CONFIG['TABLES']['PROJECT'], $APP_CONFIG['CONSTANTS']['TABLES']['Project'], $recordID, 'Species', $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species'], $inputArray['Species']);
	
	
	
	
	
	return $projectID;

}




function updateProject($inputArray = NULL, $ID = 0){
	
	global $APP_CONFIG;
	
	if ($ID <= 0){
		return createProject($inputArray);
	}

	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];

	$SQL = "SELECT * FROM {$SQL_TABLE} WHERE `ID` = '{$ID}'";
	$beforeArray = getSQL_Data($SQL, 'GetRow');

	
	
	$dataArray = array();
	
	if (true){
		
		$dataArray['Accession'] 		= $inputArray['Accession'];
		$dataArray['Name'] 				= $inputArray['Name'];
		$dataArray['Species'] 			= implode(', ', splitCategories($inputArray['Species']));
		$dataArray['Year'] 				= $inputArray['Year'];
		$dataArray['Description'] 		= $inputArray['Description'];
		$dataArray['DOI'] 				= $inputArray['DOI'];
		$dataArray['URL'] 				= $inputArray['URL'];
		$dataArray['Notes'] 			= $inputArray['Notes'];
		$dataArray['PMCID'] 			= $inputArray['PMCID'];
		$dataArray['PMID'] 				= $inputArray['PMID'];
		$dataArray['Title'] 			= $inputArray['Title'];
		
		
	}
	
	
	if (isset($inputArray['Launch_Method'])){
		$dataArray['Launch_Method'] 	= $inputArray['Launch_Method'];
	}
	
	$dataArray['ID'] = $ID;
	
	
	$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
	executeSQL($SQL);
	addAuditTrail($APP_CONFIG['TABLES']['PROJECT'], $ID, $beforeArray, $dataArray);
	

	deleteColumnIndexByRecordID($APP_CONFIG['CONSTANTS']['TABLES']['Project'], $ID);
	createColumnIndex($APP_CONFIG['TABLES']['PROJECT'], $APP_CONFIG['CONSTANTS']['TABLES']['Project'], $recordID, 'Species', $APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species'], $inputArray['Species']);
	

	return true;

}


//type
//0: Raw / Minimal processing / Export
//1: Review Multiple / Browse
//2: Review Single
//3: Update
function processProjectRecord($recordID = 0, $dataArray = NULL, $type = 0){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	if ($BXAF_CONFIG['WRAP_LONG_TEXT_LENGTH_PER_LINE'] <= 0){
		$BXAF_CONFIG['WRAP_LONG_TEXT_LENGTH_PER_LINE'] = 100;
	}
	
	$currentTable = $APP_CONFIG['TABLES']['PROJECT'];
	
	$shouldProcessed = 0;
	
	if (!$dataArray['Processed']){
		$shouldProcessed = 1;	
	} elseif (($type > 0) && ($dataArray['Processed_Type'] != $type)){
		$shouldProcessed = 1;
	}
	

	if ($shouldProcessed && (array_size($dataArray) > 0)){
		
		if (($recordID > 0) && ($dataArray['ID'] <= 0)){
			$dataArray['ID'] = $recordID;
		}
		
		$dataArray['Processed'] 		= 1;
		$dataArray['Processed_Type'] 	= $type;
		
		
		
		
		$dataArray['Launch_Method_Raw'] 	= $dataArray['Launch_Method'];
		$dataArray['Project_Groups_Raw'] 	= $dataArray['Project_Groups'];
		
		
		if ($type == 0){
			//Export
			foreach($dataArray as $currentSQL => $tempValue){
				$dataArray[$currentSQL] = auto_translate($currentTable, $currentSQL, $dataArray[$currentSQL]);
			}
			
			
			
		} elseif ($type == 1){
			//Browse Multiple
			if ($dataArray['Name'] != ''){
				$dataArray['Name'] = "<a title='{$dataArray['Name']}' href='app_project_review.php?ID={$recordID}'>{$dataArray['Name']}</a>";
			}
			
			if ($dataArray['URL'] != ''){
				$dataArray['URL'] = "<a href='{$dataArray['URL']}' target='_blank'>{$dataArray['URL']}</a>";
			}
			
			if ($dataArray['DOI'] != ''){
				$dataArray['DOI'] = "<a href='https://doi.org/{$dataArray['DOI']}' target='_blank'>{$dataArray['DOI']}</a>";
			}
			
			if ($dataArray['PMID'] != ''){
				$dataArray['PMID'] = "<a href='https://pubmed.ncbi.nlm.nih.gov/{$dataArray['PMID']}' target='_blank'>{$dataArray['PMID']}</a>";
			}
			
			if ($dataArray['PMCID'] != ''){
				$dataArray['PMCID'] = "<a href='https://www.ncbi.nlm.nih.gov/pmc/articles/{$dataArray['PMCID']}' target='_blank'>{$dataArray['PMCID']}</a>";
			}
			
			
			if ($dataArray['Description'] != ''){
				$dataArray['Description'] = wrapLongText($dataArray['Description'], $BXAF_CONFIG['WRAP_LONG_TEXT_LENGTH_PER_LINE']);
			}
			
			
			
			if ($dataArray['Notes'] != ''){
				$dataArray['Notes'] = wrapLongText($dataArray['Notes'], $BXAF_CONFIG['WRAP_LONG_TEXT_LENGTH_PER_LINE']);
			}
			
			if ($dataArray['Gene_Count'] > 0){
				$dataArray['Gene_Count'] = number_format($dataArray['Gene_Count']);
			}

			if ($dataArray['Cell_Count'] > 0){
				$dataArray['Cell_Count'] = number_format($dataArray['Cell_Count']);
			}
			

			if ($dataArray['File_h5ad_status'] && ($dataArray['File_h5ad_info'] != '')){
				$dataArray['File_h5ad_info'] = json_decode($dataArray['File_h5ad_info'], true);
				$dataArray['Project_Groups'] = array();
				
				foreach($dataArray['File_h5ad_info']['annotation'] as $tempKeyX => $tempValueX){
					
					if (array_size($tempValueX) <= $BXAF_CONFIG['SETTINGS']['Annotation_Group_Limit']['Browse']){
						$dataArray['Project_Groups'][] = "<strong>$tempKeyX</strong>: " . implode(', ', array_keys($tempValueX));
					}
				}
				$dataArray['Project_Groups'] = implode('<br/>', $dataArray['Project_Groups']);
				
				
				
			} elseif ($dataArray['Project_Groups_Raw'] != ''){
				$dataArray['Project_Groups'] = ltrim($dataArray['Project_Groups_Raw'], '[');
				$dataArray['Project_Groups'] = rtrim($dataArray['Project_Groups'], ']');
				$dataArray['Project_Groups'] = str_replace('] [', ';', $dataArray['Project_Groups']);
				$dataArray['Project_Groups'] = explode(';', $dataArray['Project_Groups']);
				$dataArray['Project_Groups'] = implode('<br/>', $dataArray['Project_Groups']);
			}
			
			$dataArray['Project_Groups'] =  wrapLongText($dataArray['Project_Groups'], $BXAF_CONFIG['WRAP_LONG_TEXT_LENGTH_PER_LINE']);
			
			
			
			if ($dataArray['File_Size'] > 0){
				$dataArray['File_Size']	 /= 1e+9;
				$dataArray['File_Size'] = round($dataArray['File_Size'], 2);
				$dataArray['File_Size'] = "{$dataArray['File_Size']} GB";
			}
			
			
			if (true){
				$actions = array();
				
				$URL = "app_project_review.php?ID={$recordID}";
				$actions[] = "<span class='nowrap'><a record='{$dataArray['Accession']}' href='{$URL}'>" . printFontAwesomeIcon('far fa-file-alt') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Review']}</a></span>";
				
				
				$URL = "app_project_launcher.php?ID={$recordID}";
				$actions[] = "<span class='nowrap'><a record='{$dataArray['Accession']}' href='{$URL}' target='_blank'>" . printFontAwesomeIcon('fas fa-braille') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene']}</a></span>";
				
				if (!isGuestUser()){
					$URL = "app_project_update.php?ID={$recordID}";
					$actions[] = "<span class='nowrap'><a record='{$dataArray['Accession']}' href='{$URL}' target='_blank'>" . printFontAwesomeIcon('far fa-edit') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Update']}</a></span>";
				}
				
				
				if (isAdminUser() && $dataArray['Launch_Method_Raw']){
					if (isCellxGeneRunning($recordID)){
						$URL = "app_project_cellxgene_start.php?ID={$recordID}";
						$actions[] = "<span class='nowrap'><a record='{$dataArray['Accession']}' href='{$URL}' target='_blank'>" . printFontAwesomeIcon('far fa-stop-circle') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Stop_Cellxgene']}</a></span>";
					} else {
						$URL = "app_project_cellxgene_stop.php?ID={$recordID}";
						$actions[] = "<span class='nowrap'><a record='{$dataArray['Accession']}' href='{$URL}' target='_blank'>" . printFontAwesomeIcon('far fa-play-circle') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Start_Cellxgene']}</a></span>";
					}
				}
				
				
				$dataArray['Actions'] = implode("&nbsp; &nbsp;", $actions);
				
			}
			
			
			foreach($dataArray as $currentSQL => $tempValue){
				$dataArray[$currentSQL] = auto_translate($currentTable, $currentSQL, $dataArray[$currentSQL]);
			}
			
		} elseif ($type == 2){
			//2: Review Single
			
			if ($dataArray['URL'] != ''){
				$dataArray['URL'] = "<a href='{$dataArray['URL']}' target='_blank'>{$dataArray['URL']}</a>";
			}
			
			if ($dataArray['DOI'] != ''){
				$dataArray['DOI'] = "<a href='https://doi.org/{$dataArray['DOI']}' target='_blank'>{$dataArray['DOI']}</a>";
			}
			
			if ($dataArray['PMID'] != ''){
				$dataArray['PMID'] = "<a href='https://pubmed.ncbi.nlm.nih.gov/{$dataArray['PMID']}' target='_blank'>{$dataArray['PMID']}</a>";
			}
			
			if ($dataArray['PMCID'] != ''){
				$dataArray['PMCID'] = "<a href='https://www.ncbi.nlm.nih.gov/pmc/articles/{$dataArray['PMCID']}' target='_blank'>{$dataArray['PMCID']}</a>";
			}
			
			if ($dataArray['Gene_Count'] > 0){
				$dataArray['Gene_Count'] = number_format($dataArray['Gene_Count']);
			}

			if ($dataArray['Cell_Count'] > 0){
				$dataArray['Cell_Count'] = number_format($dataArray['Cell_Count']);
			}
			
			if ($dataArray['Description'] != ''){
				$dataArray['Description'] = displayLongText($dataArray['Description'], 100, 0);
			}
			
			if ($dataArray['Notes'] != ''){
				$dataArray['Notes'] = displayLongText($dataArray['Notes'], 100, 0);
			}
			
			if ($dataArray['URL_Date'] != ''){
				$dataArray['URL_Date'] = date("F d, Y", strtotime($dataArray['URL_Date']));
			}
			
			if ($dataArray['File_h5ad_status'] && ($dataArray['File_h5ad_info'] != '')){
				$dataArray['File_h5ad_info'] = json_decode($dataArray['File_h5ad_info'], true);
				$dataArray['Project_Groups'] = array();
				
				foreach($dataArray['File_h5ad_info']['annotation'] as $tempKeyX => $tempValueX){
					if (array_size($tempValueX) <= $BXAF_CONFIG['SETTINGS']['Annotation_Group_Limit']['Review']){
						$dataArray['Project_Groups'][] = "{$tempKeyX}: " . implode(', ', array_keys($tempValueX));
					}
				}
				$dataArray['Project_Groups'] = implode('<br/>', $dataArray['Project_Groups']);
				
				
				
			} elseif ($dataArray['Project_Groups_Raw'] != ''){
				$dataArray['Project_Groups'] = ltrim($dataArray['Project_Groups_Raw'], '[');
				$dataArray['Project_Groups'] = rtrim($dataArray['Project_Groups'], ']');
				$dataArray['Project_Groups'] = str_replace('] [', ';', $dataArray['Project_Groups']);
				$dataArray['Project_Groups'] = explode(';', $dataArray['Project_Groups']);
				$dataArray['Project_Groups'] = '<p>' . implode('</p><p>', $dataArray['Project_Groups']) . "</p>";
			}
			
			$dataArray['Project_Groups'] = displayLongText($dataArray['Project_Groups'], 100, 0);

			
			if ($dataArray['File_Size'] > 0){
				$dataArray['File_Size']	 /= 1e+9;
				$dataArray['File_Size'] = round($dataArray['File_Size'], 2);
				$dataArray['File_Size'] = "{$dataArray['File_Size']} GB";
			}
			
			foreach($dataArray as $currentSQL => $tempValue){
				$dataArray[$currentSQL] = auto_translate($currentTable, $currentSQL, $dataArray[$currentSQL]);
			}
			

			if ($dataArray['URL_Body'] != ''){
				if (strpos($dataArray['URL'], 'https://singlecell.broadinstitute.org/single_cell/study/') !== FALSE){
					$dataArray['URL_Tab_Title'] = 'Broad scRNA Portal';
					$dataArray['URL_Tab_File'] = 'app_project_review_content_tab_iframe_Broad_scRNA_Portal.php';
				}
			}
			
			
			
		}  elseif ($type == 3){
			//3: Update
			
			
		}
		
		
		
	}
	
	
	return $dataArray;
	
	
}

function getProjectsForBrowse($sessionID = '', $ids = '', $type = 0){
	global $APP_CONFIG;
	
	$allRecords = getProjectData($sessionID, $ids);
	
	foreach($allRecords as $projectID => $projectInfo){
		$allRecords[$projectID] = processProjectRecord($projectID, $projectInfo, $type);
	}
	
	return $allRecords;
	
}


function getProjectData($sessionID = '', $ids = ''){
	
	global $APP_CONFIG;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];
	
	$ids = id_sanitizer($ids, 0, 1, 0, 2);
	
	$SEARCH_CONDTION = getSearchConditions($sessionID);
	
	if ($ids == ''){
		$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE ({$SEARCH_CONDTION})";
	} else {
		$SQL = "SELECT * FROM `{$SQL_TABLE}` WHERE ({$SEARCH_CONDTION}) AND (`ID` IN ({$ids}))";
	}

	
	$results = getSQL_Data($SQL, 'GetAssoc', 1);
	
	
	return $results;
	
}



function getProjectByID($ID = 0){
	
	global $APP_CONFIG;
	
	$ID = intval($ID);
	
	if ($ID <= 0) return false;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];
	$SQL = "SELECT * FROM {$SQL_TABLE} WHERE `ID` = '{$ID}'";
	$results = getSQL_Data($SQL, 'GetRow');

	return $results;
	
}




function guessProjectColumn($input = '', $headerKey = 0){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$input = trim($input);
	
	if ($input == '') return false;
	
	$input_lower 			= strtolower($input);
	$input_lower_stripped 	= str_replace('_', '', $input_lower);
	
	foreach($BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['Column'] as $column => $columnInfo){
		
		if ($APP_CONFIG['DICTIONARY'][($APP_CONFIG['TABLES']['PROJECT'])][$column]['Import_Disable']) continue;
		
		$display_lower 			= trim(strtolower($columnInfo['Title']));
		$column_lower			= trim(strtolower($column));
		$column_lower_stripped = str_replace('_', '', $column_lower);
		
		if ($input_lower == $display_lower) return $column;
		if ($input_lower == $column_lower) return $column;
		if ($input_lower == $column_lower_stripped) return $column;
		
		if ($input_lower_stripped == $column_lower_stripped) return $column;
		
		
		if (array_size($columnInfo['Nicknames']) > 0){
			if (in_arrayi($input_lower, $columnInfo['Nicknames'])){
				return $column;
			}
		}
	}

	return false;
	
}

function getProjectYearForSearch(){
	
	global $APP_CONFIG;
	
	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];
	
	$results = getUniqueColumnValues($SQL_TABLE, 'Year');
	

	
	$results = array_reverse(array_clean($results));
	$results = array_combine($results, $results);
	unset($results[0]);
	
	return $results;
	
}


function getCellxgeneURL($dataArray = array()){
	
	global $BXAF_CONFIG;
	
	
	
	if ($dataArray['Launch_Method_Raw'] == 0){
		$serverID = $dataArray['File_Server_ID'];
		
		$URL = $BXAF_CONFIG['Server'][$serverID]['URL'];
		
		$URL = str_replace('{File_Name}', $dataArray['File_Name'], $URL);
	}
	
	
	
	return $URL;
	
	
	
}

function convertProject_DataTable($recordID = 0, $dataArray = array()){
	
	global $APP_CONFIG;
	
		
	return processProjectRecord($recordID, $dataArray, 1);
	
}

function canUpdateProject(){
	
	return !isGuestUser();
	
}

function get_h5ad_info($file = ''){
	
	global $BXAF_CONFIG;
	
	
	if (!file_exists($file)){
		return false;
	}
	
	$cmd = "{$BXAF_CONFIG['CELLXGENE_getH5adInfo']} {$file}";
	
	$results = shell_exec($cmd);
	
	if ($results == ''){
		$results = array('Command' => $cmd);
	} else {
		$results = json_decode($results, true);
		$results['Command'] = $cmd;
	}
	
	return $results;
	
}


function to_CSC_h5ad($inputFile = '', $inputDir = '', $outputDir = ''){
	
	global $BXAF_CONFIG;
	
	$input 	= "{$inputDir}/{$inputFile}";
	$output	= "{$outputDir}/{$inputFile}";
	
	
	if (!file_exists($input)){
		return false;
	}
	
	if (!is_dir($outputDir)){
		mkdir($outputDir, 0777);	
	}
	
	if (file_exists($output)){
		return false;
	}
	
	$cmd = "{$BXAF_CONFIG['CELLXGENE_toCSCh5ad']} {$input} {$outputDir}";
	shell_exec($cmd);
	
	chmod($output, 0775);
	
	return true;
	
}


function checkCSCh5ad($file = ''){
	
	global $BXAF_CONFIG;
	
	
	if (!file_exists($file)){
		return false;
	}
	
	$cmd = "{$BXAF_CONFIG['CELLXGENE_checkCSCh5ad']} {$file}";
	
	$results = strtolower(trim(shell_exec($cmd)));
	
	if ($results == 'true'){
		return true;
	} else {
		return false;
	}
	
}


function getDefaultProjectGroup($dataArray = array()){
	
	global $BXAF_CONFIG;
	
	if (!$dataArray['Processed']){
		$dataArray['File_h5ad_info'] = json_decode($dataArray['File_h5ad_info'], true);	
	}
	
	$allKeys = array_keys($dataArray['File_h5ad_info']['annotation']);
	
	foreach($allKeys as $tempKeyX => $currentKey){
		
		$currentKeyToTest = strtolower($currentKey);
		$currentKeyToTest = str_replace(array(' ', '_', '.'), '', $currentKeyToTest);
		
		foreach ($BXAF_CONFIG['SETTINGS']['Annotation_Group_Default'] as $tempKeyY => $priorityKey){
			
			$priorityKey = strtolower($priorityKey);
			
			if ($currentKeyToTest == $priorityKey){
				return $currentKey;
			}
		}
		
		
		for ($i = 1; $i <= $BXAF_CONFIG['SETTINGS']['Annotation_Group_Edit_Distance']; $i++){
			foreach ($BXAF_CONFIG['SETTINGS']['Annotation_Group_Default'] as $tempKeyY => $priorityKey){
			
				$priorityKey = strtolower($priorityKey);
				
				if (!isset($allDistances[$currentKeyToTest][$priorityKey])){
					$editDistance = $allDistances[$currentKeyToTest][$priorityKey] = levenshtein($currentKeyToTest, $priorityKey);
				} else {
					$editDistance = $allDistances[$currentKeyToTest][$priorityKey];
				}
			
				if ($editDistance == $i){
					return $currentKey;
				}
			}
		}
		
		
	}
	
	return get_first_array_element($allKeys);
	
	
}
	


?>