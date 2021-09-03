<?php

$allRecords 			= $appObj['All_Records'];
$currentTableHeaders	= $appObj['Table_Headers'];
$currentSettings		= $appObj['Table_Headers_To_Display'];


if (array_size($allRecords) <= 0){
	echo "There are no records available. Please modify your search conditions and try again.";
	exit();
}


header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename={$appObj['File_Name']}");
header("Pragma: no-cache");
header("Expires: 0");
$fp = fopen('php://output', 'w');


$currentLine = array();
foreach($currentTableHeaders as $currentSQL => $currentHeaderDisplay){
	
	$useCurrentColumn = 0;
	if ($_GET['all_columns']){
		$useCurrentColumn = 1;
	} elseif (in_array($currentSQL, $currentSettings)){
		$useCurrentColumn = 1;
	}

	if ($useCurrentColumn){
		$currentLine[] = $currentHeaderDisplay;
	}
}
fputcsv($fp, $currentLine);


foreach($allRecords as $currentRecordID => $currentRecord){
	$currentLine = array();
	foreach($currentTableHeaders as $currentSQL => $currentHeaderDisplay){
		
		
		$useCurrentColumn = 0;
		if ($_GET['all_columns']){
			$useCurrentColumn = 1;
		} elseif (in_array($currentSQL, $currentSettings)){
			$useCurrentColumn = 1;
		}
		
		if ($useCurrentColumn){
			$currentLine[] = $currentRecord[$currentSQL];
		}
	}
	fputcsv($fp, $currentLine);
}


	
fclose($fp);

exit();

?>