<?php

$dataArray_Org = $dataArray;



$dataArray = processProjectRecord($dataArray['ID'], $dataArray, 2);


$currentTable_Org = $currentTable;
$currentTable = $APP_CONFIG['TABLES']['PROJECT'];

	echo "<br/>";
	echo "<div class='row'>";
		echo "<div class='col-12'>";
		
		
			echo "<dl class='row'>";
	
				foreach($BXAF_CONFIG['Project_Layout']['Review']['Columns'] as $tempKey => $currentColumn){
					$displayTitle	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'];
					$displayValue	= $dataArray[$currentColumn];
					
					if ($displayValue == '') $displayValue = '-';
					
					
					
					echo "<dt class='col-xl-2  col-lg-2  col-md-5 col-sm-12 col-xs-12 text-xl-right text-lg-right text-md-right'>{$displayTitle}:</dt>";
					echo "<dd class='col-xl-10 col-lg-10  col-md-7 col-sm-12 col-xs-12'>{$displayValue}</dd>";
				}
			
			echo "</dl>";
		
		
		
		echo "</div>";
	echo "</div>";
	







?>