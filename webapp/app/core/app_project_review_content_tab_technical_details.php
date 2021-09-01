<?php


$dataArray['File_h5ad_info'] = "<pre>" . printrExpress($dataArray['File_h5ad_info']) . "</pre>";

echo "<div class='row'>";
	echo "<div class='col-12'>";
		$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-info') . " This tab is visible to admin users only.</div>";
		echo getAlerts($message, 'info');
	echo "</div>";
echo "</div>";
echo "<br/>";

if (true){
	echo "<br/>";
	echo "<div class='row'>";
		echo "<div class='col-12'>";
		
		
			echo "<dl class='row'>";
	
				foreach($BXAF_CONFIG['Project_Layout']['Review_Technical_Details']['Columns'] as $tempKey => $currentColumn){
					$displayTitle	= getHeaderDisplayName($currentTable, $currentColumn, 0);				
					$displayValue	= auto_translate($currentTable, $currentColumn, $dataArray[$currentColumn]);
					
					if ($displayValue == '') $displayValue = '-';
					
					
					
					echo "<dt class='col-xl-2  col-lg-2  col-md-5 col-sm-12 col-xs-12 text-xl-right text-lg-right text-md-right'>{$displayTitle}:</dt>";
					echo "<dd class='col-xl-10 col-lg-10  col-md-7 col-sm-12 col-xs-12'>{$displayValue}</dd>";
				}
			
			echo "</dl>";
		
		
		
		echo "</div>";
	echo "</div>";	
	
	echo "<hr/>";
}




?>