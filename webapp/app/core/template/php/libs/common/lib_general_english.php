<?php

function print_is_or_are($count = 0){
	
	$count = abs(intval($count));
	
	if ($count >= 2){
		return 'are';	
	} else {
		return 'is';	
	}
}

function print_singular_or_plural($singular = '', $plural = '', $count = 0){
	$count = abs(intval($count));
	
	if ($count >= 2){
		return $plural;	
	} else {
		return $singular;	
	}
	
}


?>