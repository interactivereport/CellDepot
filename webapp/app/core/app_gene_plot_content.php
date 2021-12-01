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


	if (true){
		echo "<div class='row'>";
			echo "<div class='col-12'>";
				echo "<table>";
				
					if (true){
						echo "<tr>";
							echo "<td style='width:400px;'>";
								echo "<label for='Genes'><strong>Genes:</strong></label>";
							echo "</td>";
							
							echo "<td>";
								echo "&nbsp;";
							echo "</td>";
							
							
							
							echo "<td>";
								echo "<label for='Cell_Count'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}:</strong></label>";
							echo "</td>";
							
				
							echo "<td>";
								echo "&nbsp;";
							echo "</td>";
						echo "</tr>";
					}
					
					if (true){
						echo "<tr>";
							echo "<td>";
								echo "<input type='text' class='form-control ' id='Genes' name='Genes'/>";
							echo "</td>";
							
							echo "<td>";
								echo "&nbsp;";
							echo "</td>";
							
							
						
							
							
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
					}
					
					
					if (true){
						echo "<tr>";
							echo "<td class='text-muted small'>";
								echo "For multiple genes, please seperate them by comma, e.g., CREB1, IRAK4";
							echo "</td>";
							
							echo "<td>";
								echo "&nbsp;";
							echo "</td>";
							

							
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
					}
					
				echo "</table>";
			echo "</div>";
		echo "</div>";
	}
	
	
	//Advanced Options
	if (true){
		echo "<div class='row'>";
		echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
			
			echo "<span class='h4'><a href='javascript:void(0);' id='advancedOptionsTrigger'>" . printFontAwesomeIcon('fas fa-cog') . " Advanced Options</a></span>";
			
		echo "</div>";
		echo "</div>";
	}

	
	if (true){
		echo "<div id='advancedOptionsSection' class='startHidden'>";
		
		echo "<br/>";
		
		//Dot Plot Options
		if (true){
			echo "<div class='row'>";
				echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
					echo "<div class='row'>";
						echo "<span class='col-12'><strong>Dot Plot Options</strong></span>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		
		
		//Gene Expression Color Scale
		if (true){
			echo "<div class='row'>";
				echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
					echo "<div class='row'>";
						echo "<label class='col-12 col-form-label'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['e']['Title']} (0 to 100):</label>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
		
		
		//Gene Expression Color Scale
		if (true){
			
			echo "<div class='form-row'>";
				echo "<div class='form-group col-xl-1 col-lg-2 col-md-3 col-sm-4 col-xs-6'>";
					echo "<label for='e_min'>From:</label>";
					echo "<input type='text' class='form-control form-control-sm' id='e_min' name='e_min' placeholder=''>";
				echo "</div>";
				
				
				echo "<div class='form-group col-xl-1 col-lg-2 col-md-3 col-sm-4 col-xs-6'>";
					echo "<label for='e_max'>To:</label>";
					echo "<input type='text' class='form-control form-control-sm' id='e_max' name='e_max' placeholder=''>";
				echo "</div>";
			echo "</div>";

		}
		
		
		//Percentage Represented by the Largest Dot (1-100):
		if (true){
			echo "<div class='form-row'>";
				echo "<div class='form-group col-xl-2 col-lg-4 col-md-6 col-sm-8 col-xs-12'>";
					echo "<label for='p'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['p']['Title']} (1-100):</label>";
					echo "<input type='text' class='form-control form-control-sm' id='p' name='p' placeholder=''>";
				echo "</div>";
			echo "</div>";
		}
		
		
		//Perform log2 transforomation
		if (true){
			echo "<div class='form-row'>";
				echo "<div class='form-group col-xl-2 col-lg-4 col-md-6 col-sm-8 col-xs-12'>";
					echo "<label for='l'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['l_HTML']['Title']}:</label>";
					echo "<input type='text' class='form-control form-control-sm' id='l' name='l' placeholder=''>";
				echo "</div>";
			echo "</div>";
		}
		
		
		
		if (isManagerUser()){
			echo "<br/>";
			
			//Other Options
			if (true){
				echo "<div class='row'>";
					echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
						echo "<div class='row'>";
							echo "<span class='col-12'><strong>Other Options</strong></span>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
			
			
			//Hide Projects with no results
			if (true){
				echo "<div class='row'>";
				echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
					echo "<div class='form-group row'>";
					echo "<div class='form-check' style='padding-left:30px;'>";
					
						$checked = "";		
					
						echo "<input class='form-check-input' type='checkbox' id='Hide_Empty' name='Hide_Empty' value='1' {$checked}>";
						echo "<label class='form-check-label' for='Hide_Empty'>";
							echo "&nbsp;Hide projects with no results";
						echo "</label>";
					echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
			}
			
			//Display Technical Information
			if (true){
				echo "<div class='row'>";
				echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
					echo "<div class='form-group row'>";
					echo "<div class='form-check' style='padding-left:30px;'>";
					
						$checked = "";		
					
						echo "<input class='form-check-input' type='checkbox' id='Debug' name='Debug' value='1' {$checked}>";
						echo "<label class='form-check-label' for='Debug'>";
							echo "&nbsp;Display technical information";
						echo "</label>";
					echo "</div>";
					echo "</div>";
				echo "</div>";
				echo "</div>";
			}
		}
		
	
		echo "</div>";	
	}
	
	
	
	echo "<br/>";
	
	echo "<div class='row'>";
		echo "<div class='col-12'>";
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
	
	$("#advancedOptionsTrigger").click(function(){
		$('#advancedOptionsSection').toggle();
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