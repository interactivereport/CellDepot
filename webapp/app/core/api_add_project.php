<?php

$BXAF_CONFIG['API'] = 1;

include_once('config_init.php');

$results = array();


if (array_size($_POST) == 1){
	
	if ($_POST['data'] != ''){
		$inputArray = json_decode($_POST['data'], true);	
	} else {
		$inputArray = json_decode(get_first_array_key($_POST), true);
	}
} elseif (array_size($_POST) > 1){
	$inputArray = $_POST;	
}


if ($BXAF_CONFIG['API_Key'] == ''){
	$results['Status'] = false;
	$results['Error_Message'] 	= 'The API key is empty. Please make sure that the API_Key is not empty in the config file.';
} elseif ($_GET['api_key'] != $BXAF_CONFIG['API_Key']){
	$results['Status'] = false;
	$results['Error_Message'] 	= 'Missing api_key. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php';
} elseif (array_size($_POST) <= 0){
	$results['Status'] = false;
	$results['Error_Message'] 	= 'Missing input. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php';
} elseif ($inputArray['Name'] == ''){
	$results['Status'] = false;
	$results['Error_Message'] 	= 'Missing Name. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php';
} elseif ($inputArray['File_Name'] == ''){
	$results['Status'] = false;
	$results['Error_Message'] 	= 'Missing File_Name. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php';
} else {
	
	
	$inputArray['File_Server_ID'] = 1;	
	$inputArray['File_Directory'] 	= $BXAF_CONFIG['Server'][($inputArray['File_Server_ID'])]['File_Directory'];
	$inputArray['File_Name'] 		= str_replace('/', '', $inputArray['File_Name']);
	$inputArray['File_Size'] 		= $inputArray['File_h5ad_filesize'] = intval(filesize("{$inputArray['File_Directory']}/{$inputArray['File_Name']}"));
	
	if (!file_exists("{$inputArray['File_Directory']}/{$inputArray['File_Name']}")){
		$results['Status'] = false;
		$results['Error_Message'] 	= "The input file ({$inputArray['File_Directory']}/{$inputArray['File_Name']}) does not exist. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php";
	} elseif ($inputArray['File_h5ad_filesize'] <= 0){
		$results['Status'] = false;
		$results['Error_Message'] 	= "The input file ({$inputArray['File_Directory']}/{$inputArray['File_Name']}) is empty. Please visit the API documentation for details: https://demo.bxgenomics.com/rshinyapps_manual/api_add_project.php";
	} else {
			
		$inputArray['API'] = 1;
		$ID = createProject($inputArray);
		
		if ($ID > 0){
			$results['Status'] = true;
			
			$results['ID'] = $ID;
			clearCache();
			
		} else {
			$results['Status'] = false;
			$results['Error_Message'] 	= 'Cannot save the project. Please contact us for details.';
		}
		
		
	}

}

if ($results['Status'] == falsE){
	$results['Input'] = $inputArray;	
}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($results);


?>