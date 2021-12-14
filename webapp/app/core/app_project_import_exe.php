<?php

include_once('config_init.php');


$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

echo "<hr>";



if (true){

	$inputFile = $_FILES['file_project']['tmp_name'];
	
	if (!is_file($inputFile)){
		echo "<div class='row'>";
			echo "<div class='col-6'>";
				$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-circle text-danger') . " <strong>Error</strong>. Please verify your file and try again.</p>";
				echo getAlerts($message, 'danger');
			echo "</div>";
		echo "</div>";
		exit();
	}
	
	
		
	$pathinfo = pathinfo($_FILES['file_project']['name']);
	$pathinfo['extension'] = trim(strtolower($pathinfo['extension']));
	
	if (in_array($pathinfo['extension'], array('xls', 'xlsx'))){
		
		$sheets = getExcelSheetNames($inputFile);
		
		$failed = 0;
		
		if (array_size($sheets) == 1){
			$inputFile2 = excelToCSV($inputFile);	
		} elseif (array_size($sheets) > 1){
			
			$sheetID = array_search(strtolower('Input_Template'), array_map('strtolower', $sheets));
			
			if ($sheetID > 0){
				$inputFile2 = excelToCSV($inputFile, $sheetID);	
			} else {
				$failed = 1;	
			}
		} else {
			$failed = 1;	
		}
		
		if ($failed){
			echo "<div class='row'>";
				echo "<div class='col-6'>";
					$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-circle text-danger') . " <strong>Error</strong>. Your file contains multiple sheets. Please make sure that your file contains one sheet only.</p>";
					echo getAlerts($message, 'danger');
				echo "</div>";
			echo "</div>";
			exit();
		}
		
		if ($inputFile2 != $inputFile){
			$inputFile = $inputFile2;
		}
	}
	
	$inputArray = readFirstFewLinesFromFile_v2($inputFile, 0, 1, '', 'guessProjectColumn');
	

	if (array_size($inputArray['Body']) <= 0){
		echo "<div class='row'>";
			echo "<div class='col-6'>";
				$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-circle text-danger') . " <strong>Error</strong>. The file cannot be parsed. Please verify your file and try again.</p>";
				echo getAlerts($message, 'danger');
			echo "</div>";
		echo "</div>";
		exit();
	} 
}

$serverID = $_POST['File_Server_ID'];

foreach($inputArray['Body'] as $rowID => $row){
	
	$currentFilePath = "{$BXAF_CONFIG['Server'][$serverID]['File_Directory']}/{$row['file']}";
	
	if (!file_exists($currentFilePath)){
		$missingFiles[$rowID] = "Row <strong>{$rowID}</strong>: {$currentFilePath}";
	}
}


if (0 && array_size($missingFiles) > 0){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. The following files are missing:</p>";
	
	$message .= "<ul><li>" . implode("</li><li>", $missingFiles) . "</li></ul>";
	echo getAlerts($message, 'danger');
	//exit();
}


if (0){
	echo printrExpress($_POST);
	echo printrExpress($inputArray);
	exit();	
}

$IDs = array();
$updated = $inserted = 0;
if (array_size($inputArray['Body']) > 0){
	
	clearCache();


	foreach($inputArray['Body'] as $tempKey => $currentInput){
		
		$dataArray = $currentInput;
		
		
		$dataArray['Accession'] 		= $currentInput['Accession'];
		$dataArray['Name'] 				= $currentInput['Name'];
		$dataArray['Species'] 			= $currentInput['Species'];
		$dataArray['Year'] 				= $currentInput['Year'];
		$dataArray['Description'] 		= $currentInput['Description'];
		$dataArray['DOI'] 				= $currentInput['DOI'];
		$dataArray['URL'] 				= $currentInput['Project_link'];
		$dataArray['Cell_Count'] 		= $currentInput['Cell_Count'];
		$dataArray['Gene_Count'] 		= $currentInput['Gene_Count'];
		$dataArray['Project_Groups'] 	= $currentInput['Project_Groups'];
		$dataArray['Notes'] 			= $currentInput['Notes'];
		$dataArray['PMCID'] 			= $currentInput['PMCID'];
		$dataArray['PMID'] 				= $currentInput['PMID'];
		$dataArray['Title'] 			= $currentInput['Titles'];
		$dataArray['Launch_Method'] 	= $_POST['Launch_Method'];
		
		
		$dataArray['File_Server_ID'] 	= $serverID;
		$dataArray['File_Name'] 		= $currentInput['file'];
		

		if ($_POST['update_based_on_filename']){
			$projectID = getProjectIDByFileName($dataArray['File_Name']);
			
			if ($projectID <= 0){
				$projectID = createProject($dataArray);	
				if ($projectID > 0){
					$IDs[] = $projectID;
					$inserted++;
				}
			} else {
				$IDs[] = $projectID;
				updateProject($dataArray, $projectID);	
				$updated++;
			}
			
			
		} else {
			$projectID = createProject($dataArray);
			if ($projectID > 0){
				$IDs[] = $projectID;
				$inserted++;
			}
		}
		
		

	}
	
}


if (true){
	$cacheKey = 'IDs_' . id_sanitizer($IDs, 0, 1, 0, 3);
	putRedisCache(array($cacheKey => $IDs));
	
	$URL = "app_project_search.php?recordIDs={$cacheKey}";
}




if (($inserted == 0) && ($updated == 0)){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " The system did not import any data.</p>";
	echo getAlerts($message, 'danger');
	exit();
}

if (($inserted > 0) && ($updated <= 0)){
	$inserted = number_format($inserted);
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-success') . " The system has added {$inserted} records. Please click <a href='{$URL}'>here</a> to review the records.</p>";
	echo getAlerts($message, 'success');
} elseif (($inserted <= 0) && ($updated > 0)){
	$updated = number_format($updated);
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-success') . " The system has updated {$updated} records. Please click <a href='{$URL}'>here</a> to review the records.</p>";
	echo getAlerts($message, 'success');
} elseif (($inserted > 0) && ($updated > 0)){
	$inserted = number_format($inserted);
	$updated = number_format($updated);
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-success') . " The system has inserted {$inserted} and updated {$updated} records. Please click <a href='{$URL}'>here</a> to review the records.</p>";
	echo getAlerts($message, 'success');
}

?>