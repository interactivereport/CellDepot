<?php


function translate_key_value($table = '', $column = '', $value = ''){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$reference = $APP_CONFIG['DICTIONARY'][$table][$column]['Value'];
	
	if (!is_array($reference)){
		
		if (isset($BXAF_CONFIG['MESSAGE'][$table]['Column'][$column]['Value'])){

			$reference = $BXAF_CONFIG['MESSAGE'][$table]['Column'][$column]['Value'];
		} else {
			$function = $APP_CONFIG['DICTIONARY'][$table][$column]['Value_Function'];
			if ($function != '' && function_exists($function)){
				$reference = $function();
			}
		}
	}

	if (isset($reference[$value])){
		return $reference[$value];
	} else {
		return $value;	
	}
	
	
}


function auto_translate($table = '', $column = '', $value = ''){
	
	global $APP_CONFIG;
	
	$type = strtolower($APP_CONFIG['DICTIONARY'][$table][$column]['Type']);

	if ($type == 'date'){
		if (isEmptyDate($value)){
			$value = '';	
		}
	} elseif ($type == 'array_key_value'){
		$value = translate_key_value($table, $column, $value);	
	} elseif ($type == 'paragraph'){
		$value = nl2br($value);	
	}
	
	return $value;
}


function auto_format_html($table = '', $column = '', $value = ''){
	
	global $APP_CONFIG;
	
	$type = strtolower($APP_CONFIG['DICTIONARY'][$table][$column]['Type']);

	if ($type == 'paragraph'){
		$value = displayLongText($value);
	} elseif ($type == 'url'){
		if ($value != ''){
			$value = "<a href='{$value}' target='_blank'>{$value}</a>";	
		}
	} elseif ($type == 'email'){
		if ($value != ''){
			$value = "<a href='mailto:{$value}' target='_blank'>{$value}</a>";	
		}
	}
	
	return $value;
}

function checkIfValueIsUnique($table = '', $column = '', $value = '', $ID = 0){
	
	$value = addslashes(trim($value));
	
	if ($value == '') return true;
	
	$SQL = "SELECT `ID` FROM `{$table}` WHERE `{$column}` = '{$value}'";
	
	$results = abs(intval(getSQL_Data($SQL, 'GetOne')));
	
	if ($results > 0){
		
		if ($ID > 0){
			if ($results == $ID){
				return true;	
			} else {
				return false;	
			}
		} else {
			return false;
		}
		
	} else {
		return true;	
	}
	
}

?>