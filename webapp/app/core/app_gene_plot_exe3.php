<?php

include_once('config_init.php');

if (true){
	$key = $_GET['key'];
	if ($key == '') exit();
	
	$_POST = getRedisCache($key);
	if (array_size($_POST) <= 0) exit();
}

$currentProjectID 		= $_GET['ID'];
$currentProject 		= getProjectByID($currentProjectID);
$currentProjectProcessed = processProjectRecord($currentProjectID, $currentProject, 2);

$annotationGroup 		= $_GET['Project_Group'];
$defaultAnnotationGroup = getDefaultProjectGroup($currentProject);
if ($annotationGroup == '' || $annotationGroup == 'Undefined'){
	$annotationGroup = $defaultAnnotationGroup;
	$use_default_annotation_group = 1;
} else {
	$use_default_annotation_group = 0;	
}

$_GET['Subsampling'] = intval($_GET['Subsampling']);


if ($_GET['Plot_Type'] == ''){
	if (array_size($_POST['Genes']) <= 1){
		$_GET['Plot_Type'] = 'violin';	
	} else {
		$_GET['Plot_Type'] = 'dot';	
	}
}




$plotContent 			= getGenePlot($currentProjectID, 
										$currentProject['File_Directory_CSCh5ad'] . $currentProject['File_Name'], 
										$_POST['Genes'], 
										$_GET['Plot_Type'], 
										$annotationGroup, 
										$use_default_annotation_group,
										$_GET['Subsampling'],
										$_POST['n'], 
										$_POST['g']);




echo "<div class='row'>";
	echo "<div class='col-10'>";
	
		echo "<p>";
				echo "<span class='badge badge-pill badge-success'>" . number_format($currentProject['Cell_Count']) . " Cells</span>";
				echo "&nbsp;<span class='badge badge-pill badge-warning'>" . number_format($currentProject['Gene_Count']) . " Genes</span>";
				
				if ($_POST['n'] > 0){
					echo "&nbsp;<span class='badge badge-pill badge-primary'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['n']['Title']}: " . ($_POST['n']) . "</span>";
				}
				
				if ($_POST['g'] > 0){
					echo "&nbsp;<span class='badge badge-pill badge-danger'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}: " . ($_POST['g']) . "</span>";
				}
				
				foreach($_POST['Genes'] as $tempKeyX => $currentGene){
					echo "&nbsp;<span class='badge badge-pill badge-info'>{$currentGene}</span>";
				}
				
				$URL = getGenePlotAPIURL($currentProjectID, $_POST['Genes'], $_GET['Plot_Type'], $_GET['Subsampling'], $_POST['n'], $_POST['g'], $annotationGroup);
				
				echo "&nbsp;<span class='badge badge-pill badge-secdonary'><a href='{$URL}' target='_blank'>Share</a></span>";
				
			echo "</p>";
			
		if ($_POST['Debug']){
			echo "<div>";
				echo "<strong>Command Executed</strong>: <pre>{$plotContent['command']}</pre>";
			echo "</div>";			
			
		}
			
	
		echo "<div class='overflow-auto' style='padding:10px;'>";
			echo $plotContent['plot'];
		echo "</div>";
	echo "</div>";
	
	
	echo "<div class='col-2'>";
	
		if (array_size($currentProjectProcessed['File_h5ad_info']['annotation']) > 0){
			echo "<div class='row'>";
				echo "<label for='project_group_switcher_{$currentProjectID}' class='col-12'>
						<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Project_Groups']['Title']}</strong>
						</label>";
						
				echo "<select id='project_group_switcher_{$currentProjectID}' class='Project_Group_Switcher form-control col-12' project_id='{$currentProjectID}'>";
					
					foreach($currentProjectProcessed['File_h5ad_info']['annotation'] as $groupName => $groupArray){
						
						$groupNameDisplay = ucwords(str_replace(array('_'), ' ', $groupName));
						
						$selected = '';
						
						if ($groupName == $annotationGroup){
							$selected = 'selected';	
						}
						
						echo "<option {$selected} value='{$groupName}'>{$groupNameDisplay}</option>";
					}
					
				echo "</select>";
			echo "</div>";
			echo "<br/>";
		}
		
		if (true){
			echo "<div class='row'>";
				echo "<label for='subsampling_switcher_{$currentProjectID}' class='col-12'>
						<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Subsampling']['Title']}</strong>
						</label>";
						
				echo "<select id='subsampling_switcher_{$currentProjectID}' class='Subsampling_Switcher form-control col-12' project_id='{$currentProjectID}'>";
					
					foreach($BXAF_CONFIG['SETTINGS']['Gene_Plot_SubSampling'] as $tempKeyX => $tempValueX){
						
						
						$selected = '';
						
						if ($tempKeyX == $_GET['Subsampling']){
							$selected = 'selected';	
						}
						
						echo "<option {$selected} value='{$tempKeyX}'>{$tempValueX}</option>";
					}
					
				echo "</select>";
			echo "</div>";	
			echo "<br/>";
		}
		
		if (true){
			echo "<div class='row'>";
				echo "<label for='plot_type_switcher_{$currentProjectID}' class='col-12'>
						<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Plot_Type']['Title']}</strong>
						</label>";
						
				echo "<select id='plot_type_switcher_{$currentProjectID}' class='Plot_Type_Switcher form-control col-12' project_id='{$currentProjectID}'>";
					
					foreach($BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Plot_Type']['Value'] as $tempKeyX => $tempValueX){
						
						
						$selected = '';
						
						if ($tempKeyX == $_GET['Plot_Type']){
							$selected = 'selected';	
						}
						
						echo "<option {$selected} value='{$tempKeyX}'>{$tempValueX}</option>";
					}
					
				echo "</select>";
			echo "</div>";	
			echo "<br/>";
		}

		
	echo "</div>";

echo "</div>";



?>

<script type="text/javascript">
$(document).ready(function(){
	
	<?php if ($_POST['Hide_Empty'] && ($plotContent['result'] == 0) && ($annotationGroup == $defaultAnnotationGroup)){ ?>
		$('#Project_Result_Section_<?php echo $currentProjectID; ?>').empty();
		$('#Project_Result_Section_<?php echo $currentProjectID; ?>').hide();
	<?php } ?>	
});
</script>