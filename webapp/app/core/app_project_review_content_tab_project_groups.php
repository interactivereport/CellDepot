<?php

echo "<br/>";

$defaultKey = getDefaultProjectGroup($dataArray);

if (array_size($dataArray['File_h5ad_info']['annotation']) > 1){
	natksort($dataArray['File_h5ad_info']['annotation']);
	
	echo "<p>";
		echo "<strong>Filter:</strong>&nbsp;";
		echo "<select id='project_group_switcher'>";
			echo "<option value=''>All {$BXAF_CONFIG['MESSAGE'][$currentTable]['Column']['Project_Groups']['Title']}</option>";
			
			foreach($dataArray['File_h5ad_info']['annotation'] as $groupName => $groupArray){
				
				$groupID = "Group_" . md5($groupName);
				$groupNameDisplay = ucwords(str_replace(array('_'), ' ', $groupName));
				
				$selected = '';
				
				if ($groupName == $defaultKey){
					$selected = 'selected';	
				}
				
				echo "<option {$selected} value='{$groupID}'>{$groupNameDisplay}</option>";
			}
			
		echo "</select>";
	echo "</p>";	
}

echo "<br/>";



foreach($dataArray['File_h5ad_info']['annotation'] as $groupName => $groupArray){
	
	$groupID = "Group_" . md5($groupName);
	$groupNameDisplay = ucwords(str_replace(array('_'), ' ', $groupName));
	
	
	if ($groupName == $defaultKey){
		$displayClass = '';	
	} else {
		$displayClass = 'startHidden';	
	}
	
	//echo "<div id='{$groupID}' class='group_section {$displayClass}' style='margin-bottom:20px;'>";
	echo "<div id='{$groupID}' class='group_section {$displayClass}' style='margin-bottom:20px;'>";
	
	
	
		echo "<h3>{$groupNameDisplay}</h3>";
		
		$tableContent = array();
		$tableContent['Header']['Category'] 	= 'Category';
		$tableContent['Header']['Cell_Count'] 	= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column']['Cell_Count']['Title'];
		
		$otherOption = array('Table_Class' => 'project_group_table');
		
		foreach($groupArray as $tempKeyX => $tempValueX){
			$tableContent['Body'][$tempKeyX]['Value']['Category'] = $tempKeyX;
			$tableContent['Body'][$tempKeyX]['Value']['Cell_Count'] = $tempValueX;
		}
		
		//echo printTableHTML($tableContent, 1, 1, 0, 'col-lg-2 col-md-5 col-sm-12', 0, $otherOption);
		
		echo "<div class=' d-flex p-4'>";
		echo printTableHTML($tableContent, 1, 1, 1, '', 0, $otherOption);
		echo "</div>";
		
	
	
	echo "</div>";
	
	
	
	
}



?>


<script type="text/javascript">
$(document).ready(function(){

	
	$('.project_group_table').DataTable({
		"dom": '<<l><f><t><i><p>>',
		"processing": 	true,
		"pageLength":   50,
		"autoWidth":   false,
		"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
		"order": [[ 0, "asc" ]],
		"columnDefs": [
		  { type: 'natural', targets: 0 },
		],
		"oLanguage": {
		  "sLengthMenu": "Display _MENU_ records per page",
		},
		
		"language": {
      		"info": "Showing _START_ to _END_ of _TOTAL_ records",
	    },
    });
	
	
	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });
	
	
	$('#project_group_switcher').change(function (){
	
		var currentValue = $(this).val();
		
		if (currentValue == ''){
			$('.group_section').show();
		} else {
			$('.group_section').hide();
			$('#' + currentValue).show();
		
			$.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
		}
	
	});
	
	
	
});
</script>