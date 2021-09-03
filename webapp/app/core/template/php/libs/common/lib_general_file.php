<?php

function extractZipFiles($path){
	
	global $BXAF_CONFIG;
	
	if (!(file_exists($path) && is_file($path) && is_file($BXAF_CONFIG['UNZIP_BIN']) && file_exists($BXAF_CONFIG['UNZIP_BIN']))){
		return false;
	}
	
	$destinationDirectory = "/tmp/" . getUniqueID() . "/";
	
	mkdir($destinationDirectory, 0777, true);
	
	$cmd = "cd {$destinationDirectory}; {$BXAF_CONFIG['UNZIP_BIN']} -j {$path}";
	
	shell_exec($cmd);
	
	$scanDir = scandir($destinationDirectory);
	foreach($scanDir as $tempKey => $tempValue){
			
		$currentFile = "{$destinationDirectory}/{$tempValue}";
		$currentFile = str_replace('//', '/', $currentFile);
		
		if (is_dir($currentFile)) continue;
		
		$pathInfo = pathinfo($currentFile);
		$filename = $pathInfo['filename'];

		$results[$filename]['name'] 	= $filename;
		$results[$filename]['type'] 	= mime_content_type($currentFile);
		$results[$filename]['tmp_name'] = $currentFile;
		$results[$filename]['error'] 	= 0;
		$results[$filename]['size'] 	= filesize($currentFile);
	}
	
	return $results;
}



function excelToCSV($file = NULL, $sheetID = 1){
	//https://github.com/dilshod/xlsx2csv
	
	global $BXAF_CONFIG;
	
	if (!(file_exists($file) && is_file($file) && is_file($BXAF_CONFIG['XLSX2CSV']['Bin']) && file_exists($BXAF_CONFIG['XLSX2CSV']['Bin']))){
		return $file;
	}
	
	
	$path = $BXAF_CONFIG['XLSX2CSV']['Path'];
	if ($BXAF_CONFIG['XLSX2CSV']['Path'] == ''){
		$path = '/tmp';
	}
	
	
	$prefix = $BXAF_CONFIG['XLSX2CSV']['Prefix'];
	if ($BXAF_CONFIG['XLSX2CSV']['Prefix'] == ''){
		$prefix = 'Temp';
	}

	$csvFile = tempnam("{$path}/", "{$prefix}_") . '.csv';
	
	$sheetID = abs(intval($sheetID));
	
	if ($sheetID > 1){
		$cmd = "{$BXAF_CONFIG['XLSX2CSV']['Bin']} -s {$sheetID} {$file} {$csvFile}";
	} else {
		$cmd = "{$BXAF_CONFIG['XLSX2CSV']['Bin']} {$file} {$csvFile}";
	}
	
	exec($cmd);
	
	if (file_exists($csvFile) && is_file($csvFile)){
		return $csvFile;	
	} else {
		return $file;	
	}
}


function getExcelSheetNames($file = NULL){
	global $BXAF_CONFIG;

	if (!(file_exists($file) && is_file($file) && is_file($BXAF_CONFIG['XLSX2CSV']['Bin']) && file_exists($BXAF_CONFIG['XLSX2CSV']['Bin']))){
		return $file;
	}

	$cmd = "{$BXAF_CONFIG['XLSX2CSV']['Bin']} -a {$file} | grep '\-\-\-\-\-\-\-\-'";
	
	$results = trim(shell_exec($cmd));
	$results = explode("\n", $results);
	
	$currentIndex = 0;
	foreach($results as $tempKey => $tempValue){
		$currentIndex++;
		$sheets[$currentIndex] = trim(str_replace("-------- {$currentIndex} - ", '', $tempValue));
	}
	
	return $sheets;
	
}

function CSV2Array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = PHP_EOL) {

    $string = DOS2Unix($string);
    $array = array();
    $size = strlen($string);
    $columnIndex = 0;
    $rowIndex = 0;
    $fieldValue="";
    $isEnclosured = false;
    for($i=0; $i<$size;$i++) {

        $char = $string{$i};
        $addChar = "";

        if($isEnclosured) {
            if($char==$enclosureChar) {

                if($i+1<$size && $string{$i+1}==$enclosureChar){
                    // escaped char
                    $addChar=$char;
                    $i++; // dont check next char
                }else{
                    $isEnclosured = false;
                }
            }else {
                $addChar=$char;
            }
        }else {
            if($char==$enclosureChar) {
                $isEnclosured = true;
            }else {

                if($char==$separatorChar) {

                    $array[$rowIndex][$columnIndex] = $fieldValue;
                    $fieldValue="";

                    $columnIndex++;
                }elseif($char==$newlineChar) {
                    echo $char;
                    $array[$rowIndex][$columnIndex] = $fieldValue;
                    $fieldValue="";
                    $columnIndex=0;
                    $rowIndex++;
                }else {
                    $addChar=$char;
                }
            }
        }
        if($addChar!=""){
            $fieldValue.=$addChar;

        }
    }

    if ($fieldValue) { // save last field
        $array[$rowIndex][$columnIndex] = $fieldValue;
    }
    return $array;
}

function readFirstFewLinesFromFile($file = NULL, $rowCount = 5, $combine = 1, $delimiter = ' ', $function = ''){

	
	if (!file_exists($file)){
		return false;
	}
	
	if (!is_file($file)){
		return false;
	}
	
	$fp = fopen($file, 'r');
	
	$delimiter = trim($delimiter);
	
	
	if ($delimiter == 'tab'){
		$delimiter = "\t";	
	} elseif ($delimiter == 'csv'){
		$delimiter = ',';	
	} elseif ($delimiter == 'space'){
		$delimiter = ' ';	
	} else {
		$delimiter = guessFileDelimiter($file);	
	}
	
	
	while (!feof($fp)){
		$currentLine = fgets($fp, 1000000);
			
		if (trim($currentLine) == '') continue;
		
		if (strpos('#', $currentLine) === 0) continue;
		
		
		if ($delimiter == ''){
			$csv = str_getcsv(trim($currentLine), ',');
			$csv = array_map('trim', $csv);
			
			$tab = str_getcsv(trim($currentLine), "\t");
			$tab = array_map('trim', $tab);
			
			if (array_size($csv) > array_size($tab)){
				$delimiter = ',';
			} else {
				$delimiter = "\t";
			}
		}
		
		
		if (!isset($header)){
			
			$header = str_getcsv(trim($currentLine), $delimiter);
			
			$header = array_map('trim', $header);
			
			if ($delimiter == ' '){
				$header = array_filter($header, 'trim');
				$header = array_values($header);
			}
			
			$headerCount = array_size($header);
			
			
			if (($function != '') && function_exists($function)){
				foreach($header as $headerKey => $tempValue){
					$column = $function($tempValue, $headerKey);
					if ($column != ''){
						$header[$headerKey] = $column;	
					}
				}
			}
			
			$results['Header'] = $header;
			
			continue;
		} else {
			
			if ($delimiter == ' '){
				$currentRow = explode($delimiter, $currentLine);
				$currentRow = array_filter($currentRow, 'trim');
				$currentRow = array_values($currentRow);
			} else {
				$currentRow = str_getcsv($currentLine, $delimiter);
			}
			
			if ($headerCount == array_size($currentRow)){
				
				if ($rowCount > 0){
					if (++$currentRowCount <= $rowCount){
						if ($combine){
							$results['Body'][$currentRowCount] = array_combine($header, $currentRow);
						} else {
							$results['Body'][$currentRowCount] = $currentRow;
						}
					} else {
						break;	
					}
				} else {
					if ($combine){
						$results['Body'][] = array_combine($header, $currentRow);
					} else {
						$results['Body'][] = $currentRow;
					}
				}
			}
		}
	}
	
	
	fclose($fp);
	
	return $results;
	
}


function readFirstFewLinesFromFile_v2($file = NULL, $rowCount = 5, $combine = 1, $delimiter = '', $function = ''){

	
	if (!file_exists($file)){
		return false;
	}
	
	if (!is_file($file)){
		return false;
	}
	
	
	$delimiter = trim($delimiter);
	
	if ($delimiter == 'tab'){
		$delimiter = "\t";	
	} elseif ($delimiter == 'csv'){
		$delimiter = ',';	
	} else {
		$delimiter = guessFileDelimiter($file);	
	}
	
	
	$array = CSV2Array(file_get_contents($file), $delimiter);
	
	
	foreach($array as $tempKey => $currentRow){
		if (!isset($header)){
			$header = array_map('trim', $currentRow);
			$headerCount = array_size($header);
			
			if (($function != '') && function_exists($function)){
				foreach($header as $headerKey => $tempValue){
					$column = $function($tempValue, $headerKey);
					if ($column != ''){
						$header[$headerKey] = $column;	
					}
				}
			}
			
			$results['Header'] = $header;
			
			continue;
			
		} else {
			if ($headerCount == array_size($currentRow)){
				
				if ($rowCount > 0){
					if (++$currentRowCount <= $rowCount){
						if ($combine){
							$results['Body'][$currentRowCount] = array_combine($header, $currentRow);
						} else {
							$results['Body'][$currentRowCount] = $currentRow;
						}
					} else {
						break;	
					}
				} else {
					if ($combine){
						$results['Body'][] = array_combine($header, $currentRow);
					} else {
						$results['Body'][] = $currentRow;
					}
				}
				
				
			}
			
		}
		
	}
	
	
	
	
	return $results;
	
}

function guessFileDelimiter($file = ''){
	
	if (!file_exists($file)){
		return false;
	}
	
	if (!is_file($file)){
		return false;
	}
	
	$fp = fopen($file, 'r');
	
	
	while (!feof($fp)){
		$currentLine = fgets($fp, 1000000);
			
		if (trim($currentLine) == '') continue;
		
		if (strpos('#', $currentLine) === 0) continue;
		
		if (true){
			$csv = str_getcsv(trim($currentLine), ',');
			$csv = array_map('trim', $csv);
			$csv_size = array_size($csv);
		}
		
		if (true){
			$tab = str_getcsv(trim($currentLine), "\t");
			$tab = array_map('trim', $tab);
			$tab_size = array_size($tab);
		}
		
		if (false){
			$space = str_getcsv(trim($currentLine), ' ');
			$space = array_map('trim', $space);
			$space_size = array_size($space);
		}
		
		$max = max($csv_size, $tab_size, $space_size);
		
		if ($csv_size == $max){
			$delimiter = ',';
		} elseif ($tab_size == $max){
			$delimiter = "\t";
		} else {
			$delimiter = ' ';	
		}
		
		break;

	}
	
	
	fclose($fp);
	
	return $delimiter;
	
}

function moveFile($sourceFile = '', $destinationFolder = '', $prefix = ''){
	
	if (!(file_exists($sourceFile) && is_file($sourceFile))){
		return false;
	}
	
	
	if (!is_dir($destinationFolder)){
		mkdir($destinationFolder, 0775, 1);	
	}
	
	if ($prefix == ''){
		$prefix = 'File_';
	}
	
	$filename = tempnam($destinationFolder, $prefix);
	
	rename($sourceFile, $filename);
	
	return $filename;
	
}


?>