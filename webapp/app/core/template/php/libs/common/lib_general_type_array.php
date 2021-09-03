<?php



function natksort(&$array = NULL){
	if (array_size($array) > 0){
		uksort($array, 'strnatcasecmp');
	}
}

function array_size($array = NULL){
	if (is_array($array)){
		return intval(count($array));
	} else {
		return 0;	
	}
}

function in_arrayi($needle = '', $haystack = ''){
    foreach ($haystack as $tempKey => $value){
        if (strtolower($value) == strtolower($needle)) return true;
    }
    return false;
}

function array_clean($array = NULL, $addslashes = 0, $unique = 1, $sort = 0, $preserveKey = 0){

	if (array_size($array) > 0){
		
		$array = array_map('trim', $array);
		if ($addslashes){
			$array = array_map('addslashes', $array);
		}
		$array = array_filter($array, 'strlen');
		if ($unique){
			$array = array_iunique($array);
		}
		
		if ($sort){
			natcasesort($array);	
		}
		
		if (!$preserveKey){
			$array = array_values($array);
		}
	}
	
	return $array;
}



function array_iunique($array = array(), $case = 0) {
	
	if ($case == 0){
		return array_intersect_key(
			$array,
			array_unique( array_map( "strtolower", $array ) )
		);
	} else {
		return array_intersect_key(
			$array,
			array_unique( array_map( "strtoupper", $array ) )
		);
	}
}



function naturalSort2DArray(&$array = NULL){
	
	if (!function_exists('naturalSort2DArrayCompare')){
		function naturalSort2DArrayCompare($a = NULL, $b = NULL){
			global $ORDER_ARRAY;
			$order = $ORDER_ARRAY;
			
			foreach($order as $key => $value){
				
				if (!isset($a[$key])){
					continue;
				}
	
				unset($compareResult);
				
				//strnatcasecmp(A, B) = -1
				//strnatcasecmp(C, B) = 1
				//strnatcasecmp(B, B) = 0
				
				if ($a[$key] === $b[$key]) continue;
				
				if (is_numeric($a[$key]) && is_numeric($b[$key])){
					
					if ($a[$key] == $b[$key]){
						continue;
					} elseif ($a[$key] >= $b[$key]){
						$compareResult = 1;
					} else {
						$compareResult = -1;	
					}
				} else {
					$compareResult = strnatcasecmp($a[$key], $b[$key]);
				}
				
				if ($compareResult === 0) continue;
				
				$value = strtoupper(trim($value));
				
				if ($value === 'DESC'){
					$compareResult = $compareResult*-1;
				}
				
				return $compareResult;
			}
			
			return 0;
		}
	}

	uasort($array, 'naturalSort2DArrayCompare');
	
	return true;
}


function array_merge2($array1 = array(), $array2 = array()){
	
	$arraySize1 = array_size($array1);
	$arraySize2 = array_size($array2);
	
	if (($arraySize1 > 0) && ($arraySize2 > 0)){
		return array_merge($array1, $array2);	
	} elseif ($arraySize1 > 0){
		return $array1;	
	} elseif ($arraySize2 > 0){
		return $array2;	
	} else {
		return false;	
	}
}


if(!function_exists("array_column")){
    function array_column($array = array(), $column_name = ''){
        return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
    }
}


function get_first_array_key($array = array()){
	
	$first_array_key = array_keys($array);
	
	return $first_array_key[0];
	
}

function get_first_array_element($array = array()){
	
	$first_array_key = array_keys($array);
	$first_array_key = $first_array_key[0];
	
	return $array[$first_array_key];
	
}

function get_2d_array_keys($array = array()){

	return array_keys(get_first_array_element($array));
	
}


function sort_array_by_key_with_reference($array = array(), $order = array()){

	uksort($array, function($a, $b) use ($order){
		
		$valA = array_search($a, $order);
		$valB = array_search($b, $order);
	
		if (($valA === false) || ($valB === false)){
			if (($valA === false) && ($valB === false)){
				$results = 0;
			} elseif ($valA === false){
				$results = 1;
			} elseif ($valB === false){
				$results = -1;	
			}
		} else {
			
			if ($valA == $valB){
				$results = 0;
			} elseif ($valA > $valB){
				$results = 1;
			} elseif ($valA < $valB){
				$results = -1;
			}
		}
		
		return $results;
		
		
	});
	
	return $array;
}


?>