<?php



if (array_size($dataArray['Body']) <= 0){
	echo printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Please verify your URL and try again.";
	exit();
}

$filename = $_GET['filename'];

if ($filename == ''){
	$filename = 'data.csv';	
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');


$fp = fopen('php://output', 'w');
fwrite($fp, 'sep=,' . "\n");
	
fputcsv($fp, $dataArray['Headers']);


foreach($dataArray['Body'] as $tempKey => $tempValue){
	fputcsv($fp, $tempValue);
}
	
fclose($fp);

exit();
	
	
?>