<?php

//Output Format
//0: Both
//1: Array
//2: String
//3: Checksum
//For SQL IDs:		id_sanitizer($id, 0, 1, 0, 2);   	e.g.,    1,2,3
//For Array: 		id_sanitizer($id, 0, 1, 0, 1);   	e.g.,    array(1,2,3)
//For Storage: 		id_sanitizer($id, 1, 1, 1, 2);   	e.g.,    ,1,2,3,
//For Checksum		id_sanitizer($id, 0, 1, 0, 3);   	e.g.,    MD5(1,2,3)
//For Array(k=v):	id_sanitizer($id, 0, 1, 0, 4);   	e.g.,    array(1=>1, 2=2, 3=>3)
function id_sanitizer($id = NULL, $sort = 1, $numericOnly = 1, $forStorage = 0, $outputFormat = 0){
	if (!is_array($id)){
		$id = trim($id);
		
		if ($numericOnly){
			$id = str_replace(' ', '', $id);
		}
		
		$idAry = explode(',', $id);
	} else {
		$idAry = $id;
	}
	
	$idAry = array_filter($idAry);
	$idAry = array_unique($idAry);
	
	if ($numericOnly){
		$idAry = array_filter($idAry, 'is_numeric');
		$idAry = array_filter($idAry, function($v){ return $v > 0; });
	}
	
	if ($sort){
		sort($idAry);
	}
	$idAry = array_values($idAry);
	
	$result['Array'] 	= $idAry;
	$result['String'] 	= implode(',', $idAry);
	
	if (($forStorage) && ($result['String'] != '')){
		$result['String'] = ',' . $result['String'] . ',';
	}
	
	if ($outputFormat == 1){
		$result = $result['Array'];
	} elseif ($outputFormat == 2){
		$result = $result['String'];
	} elseif ($outputFormat == 3){
		$result = md5($result['String']);
	} elseif ($outputFormat == 4){
		$result = array_combine($result['Array'], $result['Array']);
	}
	
	return $result;
}


function string_to_sql_sanitizer($strings = NULL){
	$strings = id_sanitizer($strings, 0, 0, 0, 1);
	
	
	if (array_size($strings) <= 0) return false;
	
	$results = array();
	foreach($strings as $tempKey => $tempValue){
		$tempValue = strtoupper(addslashes(trim($tempValue)));
		$results[] = "'{$tempValue}'";
	}
	
	$results = array_clean($results);
	sort($results);
	
	$results = implode(',', $results);
	
	return $results;
	
}


function displayLongText($string = '', $length = 140, $start = 0){
	
	$length = intval($length);
	$start  = intval($start);
	
	$string = trim($string);
	if (strlen($string) >= $length){
		
		$ID_Original 		= 'ID_' . getUniqueID($string);
		$string_original	= $string;
		$results = "<div id='{$ID_Original}' style='display:none;'>{$string_original}</div>";
		
		$string_short		= substr($string, $start, $length) . '...';
		
		$ID_Short 			= 'ID_' . getUniqueID($string_short);
		
		$results .= "<div id='{$ID_Short}'>{$string_short} " . 
					"(<a href='#' onclick='$(\"#{$ID_Original}\").show(); $(\"#{$ID_Short}\").hide(); '>Show More</a>)" . 
					"</div>";
		
		return $results;
	}
	
	return $string;
}

function wrapLongText($string = '', $length = 50){
	$string = trim($string);
	if (strlen($string) >= $length){
		$string = wordwrap($string, $length, "<br/>", false);
	}
	
	return $string;
}




function getEncode($strToEncode = NULL){
   if ($strToEncode == '') return FALSE;
   else return base64_encode($strToEncode);
}

function getDecode($strToDecode = NULL){
   if ($strToDecode == '') return FALSE;
   else return base64_decode($strToDecode);
}

function sanitizeString($string = NULL){
	return 	htmlspecialchars($string, ENT_QUOTES);
}

function correctWords($string = NULL){
	
	return titleCase($string);
}

function endsWith($haystack = NULL, $needle = NULL){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function splitData($string = NULL, $seperator = ','){
	
	$string = trim($string);
	
	$array = explode("\n", $string);
	$array = array_clean($array);
	
	foreach($array as $tempKey => $tempValue){
		$tempArray = explode($seperator, $tempValue);
		
		foreach($tempArray as $tempKey2 => $tempValue2){
			$results[] = $tempValue2;	
		}
	}
	
	$results = array_clean($results);
	
	return $results;

}

function splitDataExpress($string = NULL, $seperator = array(',', ' ', ';')){
	
	$string = trim($string);
	$string = str_replace($seperator, "\n", $string);
	$string = trim($string);
	
	$results = explode("\n", $string);
	$results = array_clean($results);
	
	return $results;
}

function fixPersonName($string = ''){
	
	$string = trim($string);

	$string_lower = strtolower($string);
	$string_upper = strtoupper($string);
	
	if ($string_lower == $string){
		return ucwords($string_lower);
	} elseif ($string_upper == $string){
		return ucwords($string_lower);
	} else {
		return $string;	
	}
		
	
}

function titleCase($string = NULL){
	
	$tempValue = ucwords($string);
	$tempValue = str_replace(' Of ', ' of ', $tempValue);
	$tempValue = str_replace(' The ', ' the ', $tempValue);
	$tempValue = str_replace(' And ', ' and ', $tempValue);
	$tempValue = str_replace(' In ', ' in ', $tempValue);
	$tempValue = str_replace(' On ', ' on ', $tempValue);
	
	return $tempValue;
}


function fixMicrosoftCharacters($string = '', $output = 0){
	$search = array(
                    chr(212),
                    chr(213),
                    chr(210),
                    chr(211),
                    chr(209),
                    chr(208),
                    chr(201),
                    chr(145),
                    chr(146),
                    chr(147),
                    chr(148),
                    chr(151),
                    chr(150),
                    chr(133),
                    chr(194),
					chr(160),
					"\r\n",
                );

	if ($output == 0){
     	$replace = array(  
                        '&#8216;',
                        '&#8217;',
                        '&#8220;',
                        '&#8221;',
                        '&#8211;',
                        '&#8212;',
                        '&#8230;',
                        '&#8216;',
                        '&#8217;',
                        '&#8220;',
                        '&#8221;',
                        '&#8211;',
                        '&#8212;',
                        '&#8230;',
                        '',
						' ',
						"\n",
                    );
	} elseif ($output == 1){
		$replace = array(  
                        "'",
                        "'",
                        '"',
                        '"',
                        '-',
                        '-',
                        '...',
                        "'",
                        "'",
                        '"',
                        '"',
                        '-',
                        '-',
                        '...',
                        '',
						' ',
						"\n",
                    );	
		
	}
					
	$string = trim($string);
    $string = str_replace($search, $replace, $string);
	
	
	return $string;	
}

function removeNonPrintableCharacters($string = ''){
	
	return trim(preg_replace('/[[:^print:]]/', '', $string));
	
}

function sanitizeJavaScriptValue($string = ''){
	$string = trim($string);
	$string = str_replace('"', '', $string);
	return $string;	
}

function sanitizeShellCommand($string = ''){
	$string = trim($string);
	$string = fixMicrosoftCharacters($string, 1);
	$string = str_replace(array('"', "'", '#', '&', ';', '`', '|', '*', '?', '~', '<', '>', '^', '(', ')','[', ']', '{', '}', '$', '\\', ',', '\x0A', '\xFF'), '', $string);
	return $string;	
}


function getUniqueID($string = ''){
	$string = trim($string);
	
	if ($string == ''){
		return md5(uniqid(mt_rand(), true));
	} else {
		return md5($string);	
	}
}

function isUniqueID($string = ''){
	return !empty($string) && preg_match('/^[a-f0-9]{32}$/', $string);
}


function getRandomString($length = 10){
	
	$length = intval(abs($length));
	
	if ($length == 0) $length = 10;
	
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}



//method = 'key'
//$dictionary['bad_value'] = 'good_value';
//
//method = 'value'
//$dictionary[] = 'Value1';
//$dictionary[] = 'Value2';
function autoCorrectWord($string = '', $dictionary = array(), $method = 'key'){
	
	$string = trim($string);
	
	if ($method == 'key'){
		foreach($dictionary as $tempKey => $tempValue){
			if ($dictionary[$tempValue] == ''){
				$dictionary[$tempValue] = $tempValue;	
			}
		}
	}
	
	
	foreach($dictionary as $tempKey => $tempValue){
		
		if ($method == 'key'){
			if (strtolower($string) == strtolower($tempKey)){
				return $tempValue;
			}
		} elseif ($method == 'value'){

			if (strtolower($string) == strtolower($tempValue)){
				return $tempValue;
			}
		}
	}
	
	return $string;
	
}

function findClosestValue($string = '', $dictionary = array()){
	
	$string = trim($string);
	
	if ($string != ''){
		
		$string_cleaned = str_replace(array(' ', ':', '/', '.', ';', '#', '%'), '_', $string);
		
		foreach($dictionary as $tempKey => $reference){
			
			$reference = trim($reference);
			$reference_cleaned = str_replace(array(' ', ':', '/', '.', ';', '#', '%'), '_', $reference);
			
			if (strtolower($string_cleaned) == strtolower($reference_cleaned)){
				return $reference;
			}
		}
	}
	
	return $string;
	
}




function isValidDNASequence($string){
	
	$string = trim(strtoupper($string));
	
	$valid = array('A', 'C', 'T', 'G');
	
	$string = str_replace($valid, '', $string);
	
	if ($string == ''){
		return true;
	} else {
		return false;	
	}
}

function positiveInt($number = 0){
	return abs(intval(trim($number)));
}

function zero2null($string = ''){
	
	$string = trim($string);
	
	if ($string == 0) $string = '';
	
	return $string;
}



function DOS2Unix($s) {
    $s = str_replace("\r\n", "\n", $s);
    $s = str_replace("\r", "\n", $s);
    $s = preg_replace("/\n{2,}/", "\n\n", $s);
	
    return $s;
}

function splitCategories($string = '', $useNA = 1){
	
	if (!is_array($string)){
		$string = trim($string);
		
		if (isNA($string)){
			$string = array();
		} else {
			$string = str_replace(array(';', ',', '/', ' and '), '__BXAF_SEPERATOR__', $string);
			$string = str_replace('  ', ' ', $string);
			$string = trim($string);
			$string = explode('__BXAF_SEPERATOR__', $string);
		}
		
	}
	
	$string = array_clean($string);
	
	if (array_size($string) <= 0){
		if ($useNA){
			$string = array('N/A');	
		} else {
			$string = array();	
		}
	}
	
	
	return $string;
}

function isNA($string = ''){
	
	$NA = array('NA', 'N/A', 'N.A', 'N.A.');
	
	if (in_arrayi($string, $NA)){
		return true;
	} else {
		return false;	
	}
}


?>