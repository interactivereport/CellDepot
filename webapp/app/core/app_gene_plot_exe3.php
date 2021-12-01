<?php

include_once('config_init.php');

if (true){
	$key = $_GET['key'];
	if ($key == '') exit();
	
	$_POST = getRedisCache($key);
	if (array_size($_POST) <= 0) exit();
}

$_POST['Genes'] = id_sanitizer($_POST['Genes'], 0, 0, 0, 1);
$_POST['Genes'] = array_map('trim', $_POST['Genes']);
$_POST['Genes'] = array_iunique($_POST['Genes'], 1);
$_POST['Genes'] = array_map('strtoupper', $_POST['Genes']);
foreach($_POST['Genes'] as $tempKey => $currentGene){
	$_POST['Genes'][$tempKey] = preg_replace('/[\W]/', '', $currentGene);
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


if ($_GET['default'] == 1){
	if ($_GET['Subsampling'] == 0){
		if ($currentProject['Cell_Count'] > 10000){
			$_GET['Subsampling'] = 10000;
		}
	}
}


$_GET['Plot_Type'] = strtolower($_GET['Plot_Type']);
if ($_GET['Plot_Type'] == ''){
	if (array_size($_POST['Genes']) <= 1){
		$_GET['Plot_Type'] = 'violin';	
	} else {
		$_GET['Plot_Type'] = 'dot';	
	}
}




$plotContent = getGenePlot( $currentProjectID, 
							$currentProject['File_Directory_CSCh5ad'] . $currentProject['File_Name'], 
							$_POST['Genes'], 
							$_GET['Plot_Type'], 
							$annotationGroup, 
							$use_default_annotation_group,
							$_GET['Subsampling'],
							$_POST['g'],
							$_POST['e_min'],
							$_POST['e_max'],
							$_POST['p'],
							$_POST['l']
							);

$otherInfo = $_POST;
$processGenePlot 	= processGenePlot($plotContent, 'compact', $otherInfo);
$plotDownloadID 	= $processGenePlot['download_id'];
$plotCode 			= $processGenePlot['plot'];




echo "<div class='row'>";
	echo "<div class='col-10'>";
	
		echo "<p>";
				echo "<span class='badge badge-pill badge-success'>" . number_format($currentProject['Cell_Count']) . " Cells</span>";
				echo "&nbsp;<span class='badge badge-pill badge-warning'>" . number_format($currentProject['Gene_Count']) . " Genes</span>";
				
				if ($_POST['g'] > 0){
					echo "&nbsp;<span class='badge badge-pill badge-danger'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}: " . ($_POST['g']) . "</span>";
				}
				
				
				if ($_GET['Plot_Type'] == 'dot'){
					if (($_POST['e_min'] >= 0) && ($_POST['e_max'] > 0)){
						echo "&nbsp;<span class='badge badge-pill badge-primary'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['e']['Title']}: {$_POST['e_min']}-{$_POST['e_max']}</span>";
					}	
					
					if ($_POST['p'] > 0){
						echo "&nbsp;<span class='badge badge-pill badge-dark'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['p_Short_HTML']['Title']}: " . ($_POST['p']) . "%</span>";
					}
					
					if ($_POST['l'] > 0){
						echo "&nbsp;<span class='badge badge-pill badge-secondary'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['l_Short_HTML']['Title']} " . ($_POST['l']) . "</span>";
					}
				}
				
				
				foreach($_POST['Genes'] as $tempKeyX => $currentGene){
					echo "&nbsp;<span class='badge badge-pill badge-info'>{$currentGene}</span>";
				}
				
				if ($plotContent['result']){
					$URL = getGenePlotAPIURL($currentProjectID, $_GET['Plot_Type'], $_POST['Genes'], $annotationGroup, 
											$_GET['Subsampling'], $_POST['g'], 
											$_POST['e_min'], $_POST['e_max'], $_POST['p'], $_POST['l']);
					echo "&nbsp;<span class='badge badge-pill badge-secdonary'><a href='{$URL}' target='_blank'>" . printFontAwesomeIcon('fas fa-expand') . "&nbsp;Advanced Options / Full Screen</a></span>";
				}
				
				if ($plotContent['result']){
					echo "&nbsp;<span class='badge badge-pill badge-secdonary'><a href='javascript:void(0);' id='{$plotDownloadID}'>" . printFontAwesomeIcon('fas fa-file-download') . "&nbsp;Download Plot (SVG)</a></span>";
				}
				
			echo "</p>";
			
		if ($_POST['Debug']){
			echo "<div>";
				echo "<strong>Command Executed</strong>: <pre>{$plotContent['command']}</pre>";
			echo "</div>";			
			
		}
			
	
		echo "<div class='overflow-auto' style='padding:10px;'>";
			echo $plotCode;
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