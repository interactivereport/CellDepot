<?php

if (!isset($appObj)){
	return;
}


$currentTable	= $appObj['Table'];


$instanceID 	= $appObj['Instance_ID'];
if ($instanceID == ''){
	$instanceID 	= getUniqueID();
}


$IDs					= $appObj['Record_IDs'];
$IDs 					= id_sanitizer($IDs, 1, 1, 0, 1);

//$allRecords 			= $appObj['Records'];
$currentRecordCount 	= $appObj['Records_Count'];
$currentTableID 		= "resultTable_{$instanceID}";
$currentDivID			= "result_{$instanceID}";

$appObj['Record_Per_Page'] = intval($appObj['Record_Per_Page']);

if ($appObj['Record_Per_Page'] <= 0) $appObj['Record_Per_Page'] = 25;

if ($currentRecordCount <= 0){
	echo "There are no records available. Please modify your search conditions and try again.";
	exit();
}

$currentTableHeaders		= $appObj['Table_Headers'];


$columnSettingsForDataTable = $appObj['Table_Headers_Display_Settings'];

$cacheKey = 'IDs_' . id_sanitizer($IDs, 0, 1, 0, 3);
putRedisCache(array($cacheKey => $IDs));


if ($appObj['Enable_Group_Header']){
	$allColumnsWithGroupHeaders = getSearchableColumnsWithGroupHeaders($appObj['Table']);
}

$defaultColumns = getDefaultSearchColumnSettingsForDataTable($appObj['Table']);




echo "<div id='{$currentDivID}'>";

//Buttons
if (($appObj['Column_Settings_Modal']) || (array_size($appObj['Buttons']) > 0)){
	
	echo "<div class='row'>";
		echo "<div class='col-lg-12'>";
			
			echo "<div>";
			
				$buttons = array();
				$errorMessages = array();
				
				if ($appObj['Column_Settings_Modal']){
					$buttons[] = "<a href='javascript:void(0);' class='btn btn-success' data-toggle='modal' data-target='#settingsModal'>" . printFontAwesomeIcon('fas fa-cog') . "&nbsp;Column Settings</a>";
					$errorMessages[] = "<span class='startHidden' id='settingsModalMessage'>" . printFontAwesomeIcon('fa-exclamation-circle text-danger') . "&nbsp;Please select at least a column first.</a></span>";
				}
				
				
				foreach($appObj['Buttons'] as $tempKey => $tempValue){
					$buttons[] = $tempValue;	
				}
				
				foreach($appObj['Button_Error'] as $tempKey => $tempValue){
					$errorMessages[] = $tempValue;	
				}

			
				echo implode(" &nbsp; ", $buttons) . " &nbsp; " . implode('', $errorMessages);
			
			echo "</div>";
			
			echo "<br/>";
		echo "</div>";
	echo "</div>";
}

//Table
if (true){
	echo "<div class='row'>";
		echo "<div class='col-lg-12'>";
		
			$currentRecordCount_HTML = number_format($currentRecordCount);
			
			if ($currentRecordCount == 1){
				echo "<h6>Showing one record:</h6>";
			} else {
				echo "<h6>Showing {$currentRecordCount_HTML} records:</h6>";
			}
			
			
		
			echo "<div class='table-responsive'>";
				echo "<table id='{$currentTableID}' class='table table-sm table-bordered table-striped' width='99%'>";
					echo "<thead>";
					
						if ($appObj['Enable_Group_Header']){
							echo "<tr>";
							
								if (!$appObj['Disable_Checkboxes']){
									echo "<th class='no-sort' rowspan='2'><div class='text-center'><input type='checkbox' id='selectAllTrigger'/></div></th>";
								} else {
									echo "<th class='no-sort' rowspan='2'></th>";
								}
							
								foreach($allColumnsWithGroupHeaders as $currentGroupHeader => $tempValueX){
									$currentGroupHeaderCount = array_size($tempValueX);
									echo "<th class='text-left nowrap' colspan='{$currentGroupHeaderCount}'>{$currentGroupHeader}</th>";
								}
							echo "</tr>";
							
							echo "<tr>";
								foreach($currentTableHeaders as $currentHeader => $currentDisplay){
									echo "<th class='text-left nowrap'>{$currentDisplay}</th>";
								}
							echo "</tr>";
							
						} else {
							echo "<tr>";
								if (!$appObj['Disable_Checkboxes']){
									echo "<th class='no-sort'><div class='text-center'><input type='checkbox' id='selectAllTrigger'/></div></th>";
								} else {
									echo "<th class='no-sort'><div class='text-center'></div></th>";
								}
							
								foreach($currentTableHeaders as $currentHeader => $currentDisplay){
									echo "<th class='text-left nowrap'>{$currentDisplay}</th>";
								}
							echo "</tr>";
						}
					echo "</thead>";
					
					echo "<tbody>";
					
						
					echo "</tbody>";
					
					
				echo "</table>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}


if ($appObj['Column_Settings_Modal'] && !$appObj['Enable_Group_Header']){
	$modalID 	= "settingsModal";
	$modalTitle	= '<h4>Column Settings</h4>';
	
	$modalBody 	= '<p>Please select the columns you like to display:';

	if (array_size($defaultColumns) > 0){
		$modalBody	.= "<div class='row'>";
			$modalBody	.= "<div class='col-4'>";
				$modalBody .= "<a href='javascript:void(0);' id='columnSettingsCheckboxResetAllTrigger'> Reset to Default Settings</a>";
			$modalBody	.= "</div>";
		$modalBody	.= "</div>";
	}
	
	$modalBody	.= "<div class='row'>";
		$modalBody	.= "<div class='col-4'>";
			$modalBody .= "<input type='checkbox' id='columnSettingsCheckboxSelectAllTrigger'/> Select All";
		$modalBody	.= "</div>";
	$modalBody	.= "</div>";
	
	

	$currentIndex = 0;
	$columnID = 0;
	
	$currentTableHeadersModal = $currentTableHeaders;
	
	$columnID = 0;
	$currentTableHeadersModalSorted = array();
	foreach($currentTableHeadersModal as $currentSQL => $currentDisplay){
		$currentTableHeadersModalSorted[$currentSQL]['Title'] = getHeaderDisplayName($currentTable, $currentSQL, 1);
		$currentTableHeadersModalSorted[$currentSQL]['ColumnID'] = ++$columnID;
	}
	
	$ORDER_ARRAY = array('Title' => 'ASC');
	naturalSort2DArray($currentTableHeadersModalSorted);
	
	$columnID = 0;

	foreach($currentTableHeadersModalSorted as $currentSQL => $currentHeader){
		
		//$columnID = $columnID + 1;
		$columnID = $currentHeader['ColumnID'];
		
		$currentIndex++;
		
		$currentInfo = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL];

		
		if ($currentIndex % 3 == 1){
			$modalBody .= "<div class='row'>";	
		}
		
		if ($columnSettingsForDataTable[$columnID]){
			$checked = 'checked';
		} else {
			$checked = '';
		}
		
		$currentTitle = getHeaderDisplayName($currentTable, $currentSQL, 1);
		
		if ((array_size($defaultColumns) > 0) && (in_array($currentSQL, $defaultColumns))){
			$defaultColumnClass = 'defaultColumn';
		} else {
			$defaultColumnClass = '';
		}
		
		$modalBody .= "<div class='col-4'>";
			$modalBody .= "<input type='checkbox' id='{$currentSQL}_checkbox' class='columnSettingsCheckbox {$defaultColumnClass}' data-column='{$columnID}' value='{$currentSQL}' {$checked}/> {$currentTitle}";
		$modalBody .= "</div>";
		
		if ($currentIndex % 3 == 0){
			$modalBody .= "</div>";
		}
			
	}
	
	if ($currentIndex % 3 == 1){
		$modalBody .= "</div>";	
	}
	
	
	echo printModal($modalID, $modalTitle, $modalBody, 'Apply', '', 'saveColumnSettingsTrigger', 'modal-body-full-width');

}

if ($appObj['Column_Settings_Modal'] && $appObj['Enable_Group_Header']){
	$modalID 	= "settingsModal";
	$modalTitle	= '<h4>Column Settings</h4>';
	
	$modalBody 	= '<p>Please select the columns you like to display:';
	
	if (array_size($defaultColumns) > 0){
		$modalBody	.= "<div class='row'>";
			$modalBody	.= "<div class='col-4'>";
				$modalBody .= "<a href='javascript:void(0);' id='columnSettingsCheckboxResetAllTrigger'> Reset to Default Settings</a>";
			$modalBody	.= "</div>";
		$modalBody	.= "</div>";
	}
	
	$modalBody	.= "<div class='row'>";
		$modalBody	.= "<div class='col-4'>";
			$modalBody .= "<input type='checkbox' id='columnSettingsCheckboxSelectAllTrigger'/> Select All";
		$modalBody	.= "</div>";
	$modalBody	.= "</div>";
	
	
	
	

	$currentIndex = 0;
	$columnID = 0;
	
	$currentTableHeadersModal = $currentTableHeaders;

	
	$columnID = 0;
	$currentTableHeadersModalSorted = array();
	foreach($currentTableHeadersModal as $currentSQL => $currentDisplay){
		$currentTableHeadersModalSorted[$currentSQL]['Title'] 		= getHeaderDisplayName($currentTable, $currentSQL, 1);
		$currentTableHeadersModalSorted[$currentSQL]['ColumnID'] 	= ++$columnID;
	}
	$ORDER_ARRAY = array('Title' => 'ASC');
	naturalSort2DArray($currentTableHeadersModalSorted);
	
	
	
	
	foreach($allColumnsWithGroupHeaders as $currentGroupHeader => $currentColumns){
		
		$modalBody .= "<div class='row'><div class='col-12'><br/><h4>{$currentGroupHeader}</h4></div></div>";
		

		$currentIndex = 0;
		foreach($currentTableHeadersModalSorted as $currentSQL => $currentHeader){
			
			if (isset($currentColumns[$currentSQL])){
			
				//$columnID = $columnID + 1;
				$columnID = $currentHeader['ColumnID'];
				
				$currentIndex++;
				
				$currentInfo = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL];
		
				
				if ($currentIndex % 3 == 1){
					$modalBody .= "<div class='row'>";	
				}
				
				if ($columnSettingsForDataTable[$columnID]){
					$checked = 'checked';
				} else {
					$checked = '';
				}
				
				$currentTitle = getHeaderDisplayName($currentTable, $currentSQL, 1);
				
				
				if ((array_size($defaultColumns) > 0) && (in_array($currentSQL, $defaultColumns))){
					$defaultColumnClass = 'defaultColumn';
				} else {
					$defaultColumnClass = '';
				}
				
				
				$modalBody .= "<div class='col-4'>";
					$modalBody .= "<input type='checkbox' id='{$currentSQL}_checkbox' class='columnSettingsCheckbox {$defaultColumnClass}' data-column='{$columnID}' value='{$currentSQL}' {$checked}/> {$currentTitle}";
				$modalBody .= "</div>";
				
				if ($currentIndex % 3 == 0){
					$modalBody .= "</div>";
				}
			
			}
				
		}
		
		if ($currentIndex % 3 == 1){
			$modalBody .= "</div>";	
		}
		
	}
	
	

	
	
	
	echo printModal($modalID, $modalTitle, $modalBody, 'Apply', '', 'saveColumnSettingsTrigger', 'modal-body-full-width');

}
echo "</div>";

?>

<style>
#<?php echo $currentTableID; ?> td{
	vertical-align:top;
}

.dataTables_length{
	margin-top:3px;	
}

#resultTable_filter {
	margin-left:10px;
	margin-bottom:5px;
	float: left !important;
}


</style>

<script type="text/javascript">
$('#<?php echo $currentDivID; ?>').ready(function(){

	<?php if (true){ ?>
	var tableObj = $('#<?php echo $currentTableID; ?>').DataTable({
		
		"oLanguage": {
		  "sLengthMenu": "Display _MENU_ records per page",
		},
		
		"language": {
      		"info": "Showing _START_ to _END_ of _TOTAL_ records",
	    },

		"scrollX":      true,

        "scroller":     true,

		//225: Height of Nav bar + buttons + Show # entries + Search box
        //"scrollY":      $(window).height() - 225,
		"scrollCollapse": true,
		"serverSide": 	true,
		"scrollY":      $(window).height() - 270 - 200,
		
		
		"ajax": {
				"url": 	"<?php echo "{$appObj['ajax']}?temp={$IDKey}"; ?>",
				"type": "POST",
		},
		
		
        "processing": 	true,
		
		"pageLength":   <?php echo $appObj['Record_Per_Page']; ?>,
		
		"lengthMenu": [ [10, 25, 50, 100, 200, 500, 1000, 2000, -1], [10, 25, 50, 100, 200, 500, 1000, 2000, "All"] ],		
		
		<?php if ($appObj['Sort_Order'] != ''){ ?>
			"order": [<?php echo $appObj['Sort_Order']; ?>],
		<?php } else { ?>
			"order": [[ 1, 'asc']],
		<?php } ?>
		
		<?php if ($appObj['Search_Keyword'] != ''){ ?>
		"search": {
			"search": "<?php echo $appObj['Search_Keyword']; ?>"
		  },
		<?php } ?>
		
		"columnDefs": [
		  { type: 'natural', 
		    targets: 0,
		  },
		  {
			"targets": 'no-sort',
            "orderable": false,  
		  },
		  <?php 
		  	foreach($columnSettingsForDataTable as $columnID => $show){
				
				if ($show){
					$visible 	= 'true';
				} else {
					$visible 	= 'false';
				}
				
		  ?>
		  {
                "targets": [ <?php echo $columnID; ?> ],
                "visible": <?php echo $visible; ?>,
          },
		  <?php } ?>
		  
		  
		  
		],

		
    });
	
	
	$($.fn.dataTable.tables(true)).css('width', '100%');
	$($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
		<?php if ($appObj['Disable_Checkboxes']){ ?>
			tableObj.column(0).visible(false);
		<?php } else { ?>
			tableObj.column(0).visible(true);
		<?php } ?>
	
	<?php } ?>
	
	
	$('#<?php echo $currentDivID; ?>').on('change', '#selectAllTrigger', function(){
		var isChecked = $(this).prop('checked');
		
		if (isChecked){
			$('.recordCheckbox').prop('checked', true);	
		} else {
			$('.recordCheckbox').prop('checked', false);	
		}
	});

	
	
	
	

	
	
	<?php if ($appObj['Column_Settings_Modal']){ ?>
	
	
	$('#<?php echo $currentDivID; ?>').on('change', '#columnSettingsCheckboxSelectAllTrigger', function(){
		var isChecked = $(this).prop('checked');
		
		if (isChecked){
			$('.columnSettingsCheckbox').prop('checked', true);	
		} else {
			$('.columnSettingsCheckbox').prop('checked', false);	
		}
	});
	
	$('#<?php echo $currentDivID; ?>').on('click', '#columnSettingsCheckboxResetAllTrigger', function(){
		$('.columnSettingsCheckbox').prop('checked', false);	
		$('#columnSettingsCheckboxSelectAllTrigger').prop('checked', false);	
		$('.defaultColumn').prop('checked', true);	
		
	});
	
	
	
	$('#<?php echo $currentDivID; ?>').on('click', '.saveColumnSettingsTrigger', function(){
		
		$('#settingsModalMessage').hide();
		
		$('#settingsModal').modal('hide');
		
		var data = new Object();
		var count = 0;
		data['Columns'] = [];
		
		
		$('.columnSettingsCheckbox:checked').each(function(){
			count++;
		});
		
		if (count <= 0){
			$('#settingsModalMessage').show();	
		}
		

		
		if (count > 0){
			$('.columnSettingsCheckbox').each(function(){
				
				var isChecked 		= $(this).prop('checked');
				var currentValue	= $(this).val();
				var dataColumn		= $(this).attr('data-column');
				var columnObj		= tableObj.column(dataColumn);
				
				if (isChecked){
					count++;
					data['Columns'].push($(this).val());
					columnObj.visible(true);
				} else {
					columnObj.visible(false);
				}
			});
			
			<?php if ($appObj['Disable_Checkboxes']){ ?>
				tableObj.column(0).visible(false);
			<?php } ?>
			
			<?php if ($appObj['Settings_Handler'] != ''){ ?>
			$.ajax({
				type: 'POST',
				url: '<?php echo "{$appObj['Settings_Handler']}?table={$currentTable}&action=Columns"; ?>',
				data: data,
				success: function(responseText){
					
					
					
				}
			});
			<?php } ?>

		} 
	});
	<?php } ?>
	
	
	
	

});





</script>