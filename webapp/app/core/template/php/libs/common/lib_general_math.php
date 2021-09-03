<?php


function roundNearestHundredUp($number = 0){
	return ceil($number / 100 + 1) * 100;
}

function divide($x = NULL, $y = NULL){

	$x = floatval($x);
	$y = floatval($y);
	
	if ($y == 0){
		return 0;	
	} else {
		return $x / $y;	
	}
}

?>