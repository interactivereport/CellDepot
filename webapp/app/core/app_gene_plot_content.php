<?php

echo "<form id='form_application' action='javascript:void(0);' method='post' role='form' class='form-horizontal xform-inline' enctype='multipart/form-data' autocomplete='off'>";


	if ($_GET['preselected'] != ''){
		$preselected = getRedisCache($_GET['preselected']);
		$preselectedCount = array_size($preselected);
		
		if ($preselectedCount > 0){
			echo "<div class='row'>";
				echo "<div class='col-8'>";
					$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-info') . 
								" You have limited the gene search to {$preselectedCount} selected projects. If you like to search from all projects, please click <a href='app_gene_plot.php'>here</a>.</div>";
					echo getAlerts($message, 'info');
				echo "</div>";
			echo "</div>";
		} else {
			unset($_GET['preselected']);	
		}
		
	}


	echo "<div class='row'>";
	echo "<div class='col-12'>";
	echo "<table>";
	
		echo "<tr>";
			echo "<td style='width:400px;'>";
				echo "<label for='Genes'><strong>Genes:</strong></label>";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			
			/*
			echo "<td>";
				echo "<label  for='Cell_Count'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['n']['Title']}:</strong></label>";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			*/
			
			echo "<td>";
				echo "<label  for='Cell_Count'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}:</strong></label>";
			echo "</td>";
			

			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
			echo "<td>";
				echo "<input type='text' class='form-control ' id='Genes' name='Genes'/>";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			
			/*
			echo "<td>";
				echo "<input type='text' class='form-control' id='n' name='n'/>";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			*/
			
			echo "<td>";
				echo "<input type='text' class='form-control' id='g' name='g' />";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			
			
			echo "<td>";
				echo "<button id='submitButton' type='submit' class='btn btn-primary xmb-2'>Search</button>";
			echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
			echo "<td class='text-muted small'>";
				echo "For multiple genes, please seperate them by comma, e.g., CREB1, IRAK4";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			
			/*
			echo "<td class='text-muted small'>";
				echo "It can range from 0-100";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			*/
			
			
			echo "<td class='text-muted small'>";
				echo "Enter 0.1 to remove cells <br/>with 0 expression.";
			echo "</td>";
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
			
			
			
			
			echo "<td>";
				echo "&nbsp;";
			echo "</td>";
		echo "</tr>";
		
	echo "</table>";
	echo "</div>";
	echo "</div>";
	
	echo "<br/>";
	
	echo "<div class='row'>";
		echo "<div class='col-12'>";
		
			$actions = array();
		
			if (0){
				$actions[] = "<input type='checkbox' class='xform-control' id='Hide_Empty' name='Hide_Empty' value='1'/> Hide projects with no results";
			}
			
			if (isManagerUser()){
				$actions[] = "<input type='checkbox' class='xform-control' id='Debug' name='Debug' value='1'/> Display Technical Information";
			}
			
			if (array_size($actions) > 0){
				echo implode("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $actions);
			}
			
			echo "<input type='hidden' name='preselected' value='{$_GET['preselected']}'/>";
			echo "<input type='hidden' id='key' name='key' value=''/>";
			echo "<input type='hidden' id='currentPage' value='1'/>";
			
		echo "</div>";
	echo "</div>";
	
	


echo "</form>";


echo "<div id='feedbackSection' class='startHidden'></div>";
?>


<script type="text/javascript">


$(document).ready(function(){
	
	$("#submitButton").click(function(){
		beforeSubmit();
				
		$.ajax({
			type: 'POST',
			url: "<?php echo $PAGE['EXE']; ?>",
			data: $('#form_application').serialize(), 
			success: function(response) {
				showResponse(response);
			},
			error: function() {
				
			}
		 });
	});
	
	
	$(document).on('change', '.Project_Group_Switcher', function(){
		var currentProject 	= parseInt($(this).attr('project_id'));
		reloadPlot(currentProject);
	});
	
	$(document).on('change', '.Subsampling_Switcher', function(){
		var currentProject 	= parseInt($(this).attr('project_id'));
		reloadPlot(currentProject);
	});
	
	$(document).on('change', '.Plot_Type_Switcher', function(){
		var currentProject 	= parseInt($(this).attr('project_id'));
		reloadPlot(currentProject);
	});
	
});



function beforeSubmit() {
	$('#feedbackSection').empty();
	$('#feedbackSection').hide();
	$('#busySection').show();
	return true;
}


function showResponse(responseText) {
	responseText = $.trim(responseText);

	$('#busySection').hide();
	$('#feedbackSection').html(responseText);
	$('#feedbackSection').show();
	
	
	return false;

}

function reloadPlot(currentProject){
	if (currentProject > 0){
		
		var Project_Group 	= $('#project_group_switcher_' + currentProject).val();
		var subsampling		= parseInt($('#subsampling_switcher_' + currentProject).val());
		var Plot_Type	 	= $('#plot_type_switcher_' + currentProject).val();
		var key = $('#key').val();
		
		$('#plotSection_' + currentProject).empty();
		$('#busySection_' + currentProject).show();
		
		$.ajax({
			type: 'GET',
			url: 'app_gene_plot_exe3.php?key=' + key + '&ID=' + currentProject + '&Project_Group=' + Project_Group + '&Subsampling=' + subsampling + '&Plot_Type=' + Plot_Type,
			success: function(responseText){
				$('#plotSection_' + currentProject).html(responseText);
				$('#busySection_' + currentProject).hide();
			}
		});	
	}
	
	return true;
	
}



</script>