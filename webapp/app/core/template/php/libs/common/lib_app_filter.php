<?php

function get_filter_column_info($currentTable = NULL, $filter_columns = array(), $SQL_CONDITION = '(1)'){
	
	global $APP_CONFIG, $APP_CACHE, $BXAF_CONFIG;
	
	$cacheKey = __FUNCTION__ . '::' . md5($currentTable . '::' . json_encode($filter_columns)  . '::' .  json_encode($SQL_CONDITION)  . '::' .  $APP_CONFIG['VERSION']['CACHE']);
	
	if (isset($APP_CACHE[__FUNCTION__][$cacheKey])){
		return $APP_CACHE[__FUNCTION__][$cacheKey];
	}
	
	$resultsFromCache = getRedisCache($cacheKey);
	
	if ($resultsFromCache !== false){
		return $resultsFromCache;	
	}
	
	$results = array();

	//For Filter
	foreach($filter_columns as $tempKey => $currentColumn){
		
		$function = $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Count_Function'];
		
		if (function_exists($function)){
			$results[$currentColumn] = $function($SQL_CONDITION);
		} else {
			$results[$currentColumn] = getUniqueColumnValueAndCount($currentTable, $currentColumn, $SQL_CONDITION);
		}
		
		$sortBy = $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Sort_By'];
		if (($sortBy != 'Category') && ($sortBy != 'Reference')){
			$sortBy = 'Value';	
		}
		
		$sortDirection = strtoupper($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Sort_Order']);
		if ($sortDirection != 'ASC'){
			$sortDirection = 'DESC';	
		}
		
		if ($sortBy == 'Category'){
			if ($sortDirection == 'ASC'){
				ksort($results[$currentColumn]);
			} else {
				krsort($results[$currentColumn]);
			}
		} elseif ($sortBy == 'Reference'){
			$results[$currentColumn] = sort_array_by_key_with_reference($results[$currentColumn], $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value']);
		} else {
			//$sortBy = Value
			if ($sortDirection == 'ASC'){
				asort($results[$currentColumn]);
			} else {
				arsort($results[$currentColumn]);	
			}
			
			
		}
	}
	
	
	
	

	$APP_CACHE[__FUNCTION__][$cacheKey] = $results;
	
	putRedisCache(array($cacheKey => $results));
	
	return $results;
	
	
}

?>