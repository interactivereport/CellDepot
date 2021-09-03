<?php


function isEmptyDate($date = ''){
	
	$date = trim($date);
	
	if (empty($date)){
		return true;	
	} elseif (strpos($date, '0000-') === 0){
		return true;
	} elseif (strpos($date, '1970-') === 0){
		return true;
	} elseif (strpos($date, '1969-') === 0){
		return true;
	} elseif (strpos($date, '1969-12-31') === 0){
		return true;
	} else {
		return false;	
	}

}

//Dates need to be in SQL format, i.e., YYYY-mm-dd
function dateDiff2($date1 = '', $date2 = '', $mode = 0){

	$timeZoneOrg = date_default_timezone_get();

	date_default_timezone_set('UTC');
	
	if ($date2 == '') $date2 = date('Y-m-d');

	$date1Info = getDateDetails($date1, 0);
	$date2Info = getDateDetails($date2, 0);
	

	//$diff = abs(strtotime($date2) - strtotime($date1));
	$diff = abs($date2Info['Time_PHP'] - $date1Info['Time_PHP']);

	$validChoice = array(0, 1, 2, 3);
	
	if (!in_array($mode, $validChoice)){
		$mode = 0;	
	}

	if ($mode == 0){
		//1y2m27d
		/*
		$years 	= floor($diff / (365*86400));
		$months = floor(($diff - $years * 365*86400) / (30*86400));
		$days 	= floor(($diff - $years * 365*86400 - $months*30*86400)/ (86400));
		
		$details['Year'] 	= $years;
		$details['Month'] 	= $months;
		$details['Day'] 	= $days;
		*/
		
		$datetime1 = date_create($date1);
		$datetime2 = date_create($date2);
	   
		$diff = date_diff($datetime1, $datetime2);
	   
		$details['Year'] 	= $diff->format('%y');
		$details['Month'] 	= $diff->format('%m');
		$details['Day'] 	= $diff->format('%d');
	} elseif ($mode == 1){
		//64w4d
		if ($diff < 604800){
			$days 	= intval($diff / 86400);
		} else {
			$weeks 	= floor($diff/604800);
			$days	= floor(($diff % 604800) / 86400);
			$details['Week'] 	= $weeks;
		}
		$details['Day'] 	= $days;
	} elseif ($mode == 2){
		//452d
		$details['Day'] = intval($diff / 86400);
	} elseif ($mode == 3){
		//64.5w
		if ($diff < 604800){
			$days 	= intval($diff / 86400);
		} else {
			$weeks 	= floor($diff/604800);
			$days	= floor(($diff % 604800) / 86400);
			$details['Week'] 	= $weeks;
		}
		$details['Week'] 	+= $days/7;
	}
	
	date_default_timezone_set($timeZoneOrg);

	return $details;
}


function dateDiffInDays($date1 = '', $date2 = ''){
	
	$details = dateDiff2($date1, $date2, 2);
	
	return $details['Day'] + 1;
}

function getDateDetails($SQL_DATE = '', $includeDayOfWeekOfMonth = 1){
	
	$result = array();
	$result['Input'] 		= $SQL_DATE;
	
	$SQL_DATE = trim($SQL_DATE);
	$SQL_DATE = str_replace(array('/', '.', '_'), '-', $SQL_DATE);
	
	if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $SQL_DATE)){
		//YYYY-MM-DD
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[2]);
		$month 	= intval($temp[1]);
		$year 	= intval($temp[0]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY-MM-DD';
	} elseif (preg_match("/^[0-9]{4}-([1-9]|1[0-2])-([1-9]|[1-2][0-9]|3[0-1])$/", $SQL_DATE)){
		//YYYY-M-D
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[2]);
		$month 	= intval($temp[1]);
		$year 	= intval($temp[0]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY-M-D';
	} elseif (preg_match("/^(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-[0-9]{4}$/", $SQL_DATE)){
		//MM-DD-YYYY
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[1]);
		$month 	= intval($temp[0]);
		$year 	= intval($temp[2]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'MM-DD-YYYY';
	} elseif (preg_match("/^([1-9]|1[0-2])-([1-9]|[1-2][0-9]|3[0-1])-[0-9]{4}$/", $SQL_DATE)){
		//M-D-YYYY
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[1]);
		$month 	= intval($temp[0]);
		$year 	= intval($temp[2]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'M-D-YYYY';
	} elseif (preg_match("/^(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])-[0-9]{2}$/", $SQL_DATE)){
		//MM-DD-YY
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[1]);
		$month 	= intval($temp[0]);
		$year 	= 2000+intval($temp[2]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'MM-DD-YY';
	} elseif (preg_match("/^([1-9]|1[0-2])-([1-9]|[1-2][0-9]|3[0-1])-[0-9]{2}$/", $SQL_DATE)){
		//M-D-YY
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= intval($temp[1]);
		$month 	= intval($temp[0]);
		$year 	= 2000+intval($temp[2]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'M-D-YY';
	} elseif (preg_match("/^[0-9]{4}$/", $SQL_DATE)){
		//YYYY
		//OK
		$day  	= 1;
		$month 	= 1;
		$year 	= intval($SQL_DATE);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY';
	} elseif (preg_match("/^[0-9]{2}$/", $SQL_DATE)){
		//YY
		//OK
		$day  	= 1;
		$month 	= 1;
		$year 	= 2000+intval($SQL_DATE);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY';
	} elseif (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])$/", $SQL_DATE)){
		//YYYY-MM
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= 1;
		$month 	= intval($temp[1]);
		$year 	= intval($temp[0]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY-MM';
	} elseif (preg_match("/^[0-9]{4}-([1-9]|1[0-2])$/", $SQL_DATE)){
		//YYYY-M
		//OK
		$temp 	= explode('-', $SQL_DATE);
		$day  	= 1;
		$month 	= intval($temp[1]);
		$year 	= intval($temp[0]);
		$time	= mktime(0, 0, 0, $month, $day, $year);
		$result['Pattern'] = 'YYYY-M';
	} else {
		$result['Pattern'] = 'Unknown';
		$strtotime	= strtotime($SQL_DATE);	
		$day  		= date('d', $strtotime);
		$month 		= date('m', $strtotime);
		$year 		= date('Y', $strtotime);
		$time		= mktime(0, 0, 0, $month, $day, $year);
	}
	
	
	
	
	$dayOfWeek 		= date("N", $time);
	$maxDay			= date("t", $time);
	$day_D			= date("D", $time);
	$day_Recurring	= strtoupper(substr(date("D", $time), 0, 2));
	$quarter		= ceil(date("n", $time) / 3);
	
	if ($includeDayOfWeekOfMonth){
		unset($currentIndex);
		for ($i = 1; $i <= $maxDay; $i++){
			$currentTime 		= mktime(0, 0, 0, $month, $i, $year);
	
			$currentDayOfWeek	= date("N", $currentTime);
			
			if ($currentDayOfWeek != $dayOfWeek){
				continue;
			}
			
			$result['DayOfWeekOfMonth'][$i] = ++$currentIndex;
		}
		
		$result['DayOfWeekOfMonth'] 	= $result['DayOfWeekOfMonth'][$day];
	}
	
	$result['Year'] 		= $year;
	$result['Month'] 		= $month;
	$result['Day_D']		= $day_D;
	$result['Day_R']		= $day_Recurring;
	$result['Day'] 			= $day;
	$result['DayOfWeek'] 	= $dayOfWeek;
	$result['DayMax'] 		= $maxDay;
	$result['Time_PHP']		= $time;
	$result['Quarter']	 	= 'Q' . $quarter;
	$result['Quarter_N'] 	= $quarter;
	
	$result['Standard'] 	= date('Y-m-d', $time);
	$result['Calendar'] 	= date('F d, Y', $time);
	$result['Empty'] 		= isEmptyDate($result['Standard']);
	
	
	
	return $result;
	
}


function getTimeDetails($time, $unit){
	
	$time = floatval($time);
	$unit = trim(strtolower($unit));
	
	
	if (strpos($unit, 'd') === 0){
		//Day
		$day = $time;
	} elseif (strpos($unit, 'w') === 0){
		//Week
		$day = $time*7;
	} elseif (strpos($unit, 'm') === 0){
		//Month
		$day = $time*30;
	} elseif (strpos($unit, 'y') === 0){
		//Year
		$day = $time*365;	
	} elseif (strpos($unit, 'h') === 0){
		//Hour
		$day = $time / 24;	
	}
	
	$results['Day'] 	= $day;
	$results['Week'] 	= $day/7;
	$results['Month'] 	= $day*0.032855;
	$results['Year'] 	= $day/365;
	$results['Hour'] 	= $day*24;
	
	return $results;
	
}


?>