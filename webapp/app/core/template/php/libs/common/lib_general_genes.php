<?php

function getStatScaleColor($value, $type) {
	
	if (is_numeric($value)){
		
		$value = floatval($value);
	
		if ($type == 'Log2FoldChange') {
			if ($value >= 1) {
			  return '#FF0000';
			} elseif ($value > 0) {
			  return '#FF8989';
			} elseif ($value == 0) {
			  return '#E5E5E5';
			} elseif ($value > -1) {
			  return '#7070FB';
			} else {
			  return '#0000FF';
			}
		} elseif ($type == 'FoldChange') {
			
			$value = log($value, 2);
			
			if ($value >= 1) {
			  return '#FF0000';
			} elseif ($value > 0) {
			  return '#FF8989';
			} elseif ($value == 0) {
			  return '#E5E5E5';
			} elseif ($value > -1) {
			  return '#7070FB';
			} else {
			  return '#0000FF';
			}
		} elseif ($type == 'AdjustedPValue') {
			if ($value > 0.05) {
			  return '#9CA4B3';
			} elseif ($value <= 0.01) {
			  return '#015402';
			} else {
			  return '#5AC72C';
			}
		} elseif ($type == 'PValue') {
			if ($value >= 0.01) {
			  return '#9CA4B3';
			} else {
			  return '#5AC72C';
			}
		} elseif ($type == 'ZScore') {
			if ($value > 1) {
			  return '#FF0000';
			} elseif ($value > 0) {
			  return '#FF9C9C';
			} elseif ($value == 0) {
			  return '#979797';
			} elseif ($value > -1) {
			  return '#81C86E';
			} else {
			  return '#02CA2D';
			}
		}
	}
	
	return '#000';
}

?>