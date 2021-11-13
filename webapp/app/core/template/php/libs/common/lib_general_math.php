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

function round_science_notation($num = 0, $precision = 0, $mode = PHP_ROUND_HALF_UP){
	
	if (strpos($num, 'e') === false){
		return round($num, $precision, $mode);
	} else {
		
		$position = strpos($num, 'e');
		
		$coefficient = substr($num, 0, $position);
		
		$coefficient = round($coefficient, $precision, $mode);
		
		$base_exponent = substr($num, $position);
		
		return $coefficient . $base_exponent;
		
	}
}


?>