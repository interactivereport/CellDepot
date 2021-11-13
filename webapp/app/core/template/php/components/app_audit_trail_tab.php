<?php

//Assume $auditTrails

echo "<br/>";

$currentIndex = 0;

$tableContent = array();

$tableContent['Header']['No.'] 			= 'No.';
$tableContent['Header']['Date_Time'] 	= 'Date/Time';
$tableContent['Header']['Column'] 		= 'Column';
$tableContent['Header']['Before'] 		= 'Before';
$tableContent['Header']['After'] 		= 'After';
$tableContent['Header']['User'] 		= 'User';


foreach($auditTrails as $auditTrailsID => $currentAuditTrail){


	if ($APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['AuditTrail_Disable']){
		continue;	
	}
	
	
	if (true){
		$beforeValue 	= $currentAuditTrail['Before'];
		$afterValue		= $currentAuditTrail['After'];
	
		if ($APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['printForm']['New'] == 'printDropDown_Config_KeyValue'){
			$beforeValue 	= translate_key_value($currentAuditTrail['Table'], $currentAuditTrail['Column'], $beforeValue);
			$afterValue 	= translate_key_value($currentAuditTrail['Table'], $currentAuditTrail['Column'], $afterValue);
		}
		
		if ($APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['printForm']['New'] == 'printTag'){
			$beforeValue 	= implode(', ', json_decode($beforeValue, true));
			$afterValue 	= implode(', ', json_decode($afterValue, true));
		}
		
		if ($APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['printForm']['New'] == 'printTagKeyValue'){
			$beforeValue 	= implode(', ', json_decode($beforeValue, true));
			$afterValue 	= implode(', ', json_decode($afterValue, true));
		}
		
		if (strtolower($APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['Type']) == 'date'){
			if (isEmptyDate($beforeValue) && isEmptyDate($afterValue)){
				continue;	
			} elseif (isEmptyDate($beforeValue)){
				$beforeValue = '';	
			} elseif (isEmptyDate($afterValue)){
				$afterValue = '';	
			}
		}
		
		$beforeValue = trim($beforeValue);
		$beforeValueCheck = htmlspecialchars_decode(fixMicrosoftCharacters($beforeValue));
		
		$afterValue = trim($afterValue);
		$afterValueCheck = htmlspecialchars_decode(fixMicrosoftCharacters($afterValue));
		
		
		if ($beforeValue == $afterValue){
			continue;
		} elseif ($beforeValueCheck == $afterValueCheck){
			continue;
		} else {
			//Display
		}
	}

	
	if (true){
		$columnDisplay = $APP_CONFIG['MESSAGE'][($currentAuditTrail['Column'])];
		if ($columnDisplay == ''){
			$columnDisplay = $APP_CONFIG['DICTIONARY'][($currentAuditTrail['Table'])][($currentAuditTrail['Column'])]['Title'];
		}
		
		if ($columnDisplay == ''){
			$columnDisplay = $currentAuditTrail['Column'];
		}
	}
	
	
	
	$currentIndex++;
	$tableContent['Body'][$currentIndex]['Value']['No.'] 		= "{$currentIndex}.";
	$tableContent['Body'][$currentIndex]['Value']['Date_Time'] 	= "<span class='nowrap'>{$currentAuditTrail['Date_Time']}</span>";
	$tableContent['Body'][$currentIndex]['Value']['Column'] 	= $columnDisplay;
	$tableContent['Body'][$currentIndex]['Value']['Before'] 	= $beforeValue;
	$tableContent['Body'][$currentIndex]['Value']['After'] 		= $afterValue;
	$tableContent['Body'][$currentIndex]['Value']['User'] 		= "<span class='nowrap'>{$currentAuditTrail['User_Name']}</span>";
}


if (array_size($tableContent['Body']) > 0){
	echo "<div id='AuditTrail_Section'>";
		echo printTableHTML($tableContent, 1, 1, 0, 'col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12', 0);
	echo "</div>";
} else {
	echo "<div id='AuditTrail_Section'>";
		echo "<p>The audit trail is not available.</p>";
	echo "</div>";
}

unset($tableContent, $currentIndex, $auditTrailsID, $currentAuditTrail);


?>

<style>
.dataTables_length{
	margin-top:3px;	
}

.dataTables_filter {
	margin-left:10px;
	margin-bottom:5px;
	float: left !important;
}
</style>

<script type="text/javascript">
$(document).ready(function(){

	$('#AuditTrail_Section table').DataTable({
        "processing": 	true,
		"pageLength":   100,
		"autoWidth":   false,
		"lengthMenu": [ [10, 25, 50, 100, 200, 500, 1000, 2000, -1], [10, 25, 50, 100, 200, 500, 1000, 2000, "All"] ],
		"order": [[ 0, "asc" ]],
		"columnDefs": [
		  { type: 'natural', targets: 0 },
		],
		
		
		
    });
	
});
</script>