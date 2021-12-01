<?php

$BXAF_CONFIG['API'] = 1;

include_once('config_init.php');


if (true){
	$_GET['Genes'] = id_sanitizer($_GET['Genes'], 1, 0, 0, 1);
	$_GET['Genes'] = array_iunique($_GET['Genes'], 1);
	$_GET['Genes'] = array_map('strtoupper', $_GET['Genes']);
	foreach($_GET['Genes'] as $tempKey => $currentGene){
		$_GET['Genes'][$tempKey] = preg_replace( '/[\W]/', '', $currentGene);
	}
	$_GET['Genes_String'] = implode(',', $_GET['Genes']);


	if (array_size($_GET['Genes']) <= 0){
		include('template/php/components/page_generator_header.php');
		echo "<div class='container-fluid'>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " <strong>Error</strong>. A gene is required. Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>API documentation</a> for details.</p></div>";
					echo getAlerts($message, 'danger');
				echo "</div>";
			echo "</div>";
		echo "</div>";
		exit();
	}
}


if ($ID > 0){
	$currentProjectID 		= $ID;
	$currentProject 		= getProjectByID($currentProjectID);
	
	if (array_size($currentProject) <= 0){
		include('template/php/components/page_generator_header.php');
		echo "<div class='container-fluid'>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " <strong>Error</strong>. The project record (ID: {$currentProjectID}) is not available. Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>API documentation</a> for details.</p></div>";
					echo getAlerts($message, 'danger');
				echo "</div>";
			echo "</div>";
		echo "</div>";
		exit();
	} else {
		$currentProjectProcessed = processProjectRecord($currentProjectID, $currentProject, 2);
	
		$annotationGroup 		= $_GET['Project_Group'];
		$defaultAnnotationGroup = getDefaultProjectGroup($currentProject);
		if ($annotationGroup == ''){
			$annotationGroup = $defaultAnnotationGroup;
			$use_default_annotation_group = 1;
		} else {
			$use_default_annotation_group = 0;	
		}
		
		$CSC_Path = $currentProject['File_Directory_CSCh5ad'] . $currentProject['File_Name'];
	}
} elseif ($_GET['Path'] != ''){
	
	if (!file_exists($_GET['Path'])){
		include('template/php/components/page_generator_header.php');
		echo "<div class='container-fluid'>";
			echo "<div class='row'>";
				echo "<div class='col-12'>";
					$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " <strong>Error</strong>. The dataset ({$_GET['Path']}) is not available. Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>API documentation</a> for details.</p></div>";
					echo getAlerts($message, 'danger');
				echo "</div>";
			echo "</div>";
		echo "</div>";
		exit();
	}
	
	$currentProject = array();
	$get_h5ad_info = get_h5ad_info($_GET['Path']);
	
	if ($get_h5ad_info['cellN'] > 0){
		$currentProject['File_h5ad_status'] 	= 1;
		$currentProject['Cell_Count'] 		= $get_h5ad_info['cellN'];
		$currentProject['Gene_Count'] 		= $get_h5ad_info['geneN'];
		$currentProject['File_h5ad_info'] 	= $get_h5ad_info;
		$currentProject['Processed']		= 1;
	}
	
	$CSC_Path =  $_GET['Path'];
	$defaultAnnotationGroup = getDefaultProjectGroup($currentProject);
	$annotationGroup = $defaultAnnotationGroup;
	$use_default_annotation_group = 0;
	
		
}


if (array_size($currentProject) <= 0){
	include('template/php/components/page_generator_header.php');
	echo "<div class='container-fluid'>";
		echo "<div class='row'>";
			echo "<div class='col-12'>";
				$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " <strong>Error</strong>. The dataset is not available. Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>API documentation</a> for details.</p></div>";
				echo getAlerts($message, 'danger');
			echo "</div>";
		echo "</div>";
	echo "</div>";
	exit();
}


if (($_GET['Plot_Type'] != 'violin') && ($_GET['Plot_Type'] != 'dot')){
	if (array_size($_GET['Genes']) <= 1){
		$_GET['Plot_Type'] = 'violin';	
	} else {
		$_GET['Plot_Type'] = 'dot';	
	}
}



$_GET['Subsampling'] 	= positiveInt($_GET['Subsampling']);
$_GET['g'] 				= zero2null(abs(floatval($_GET['g'])));
$_GET['plot_height'] 	= zero2null(positiveInt($_GET['plot_height']));
$_GET['plot_width'] 	= zero2null(positiveInt($_GET['plot_width']));
$_GET['export_height'] 	= zero2null(positiveInt($_GET['export_height']));
$_GET['export_width'] 	= zero2null(positiveInt($_GET['export_width']));

if ($_GET['Plot_Type'] == 'dot'){
	$_GET['e_min'] 			= zero2null(abs(floatval($_GET['e_min'])));
	$_GET['e_max'] 			= zero2null(abs(floatval($_GET['e_max'])));
	$_GET['p'] 				= zero2null(positiveInt($_GET['p']));
	$_GET['l'] 				= zero2null(abs(floatval($_GET['l'])));
	$_GET['d'] 				= trim(strtolower($_GET['d']));
} else {
	$_GET['e_min'] 			= NULL;
	$_GET['e_max'] 			= NULL;
	$_GET['p'] 				= NULL;
	$_GET['l'] 				= NULL;
	$_GET['d'] 				= NULL;
}


$plotContent = getGenePlot($currentProjectID, 
							$CSC_Path, 
							$_GET['Genes'], 
							$_GET['Plot_Type'], 
							$annotationGroup, 
							$use_default_annotation_group,
							$_GET['Subsampling'],
							$_GET['g'],
							$_GET['e_min'],
							$_GET['e_max'],
							$_GET['p'],
							$_GET['l'],
							$_GET['d']
							);
							

$processGenePlot 	= processGenePlot($plotContent, 'fullsize', $_GET);
$plotDownloadID 	= $processGenePlot['download_id'];
$plotCode 			= $processGenePlot['plot'];

//JSON
if ($_GET['format']){
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($plotContent);	
	exit();
}


if (!$_GET['format']){

	include('template/php/components/page_generator_header.php');
	echo "<script type='text/javascript' src='js/plotly.violin.js'></script>";
	echo "<script type='text/javascript' src='js/plotly.scatter.js'></script>";
	
	echo "<div class='container-fluid'>";
		//echo "<br/>";
		echo "<div class='row'>";
			echo "<div class='col-12'>";
			
				echo "<p>";
				
					if ($ID > 0){
					
						if ($currentProject['Accession'] == ''){
							$title = $currentProject['Name'];
						} else {
							$title = "[{$currentProject['Accession']}] {$currentProject['Name']}";	
						}
						echo "<h5 class='card-title'>{$title}&nbsp;<a href='app_project_review.php?ID={$currentProjectID}' target='_blank' class='small' title='Review Project'>". printFontAwesomeIcon('fas fa-external-link-alt') . "</a></h5>";
					} else {
						echo "<h5 class='card-title'>{$CSC_Path}</h5>";
					}
		
				
					echo "<span class='badge badge-pill badge-success'>" . number_format($currentProject['Cell_Count']) . " Cells</span>";
					echo "&nbsp;<span class='badge badge-pill badge-warning'>" . number_format($currentProject['Gene_Count']) . " Genes</span>";
					
					if ($_GET['g'] > 0){
						echo "&nbsp;<span class='badge badge-pill badge-danger'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}: " . ($_GET['g']) . "</span>";
					}
					
					if ($_GET['Subsampling'] > 0){
						echo "&nbsp;<span class='badge badge-pill badge-dark'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Subsampling']['Title']}: " . ($_GET['Subsampling']) . "</span>";
					}
					
					if ($_GET['Plot_Type'] == 'dot'){
						if (($_GET['e_min'] >= 0) && ($_GET['e_max'] > 0)){
							echo "&nbsp;<span class='badge badge-pill badge-primary'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['e']['Title']}: {$_GET['e_min']}-{$_GET['e_max']}</span>";
						}	
						
						if ($_GET['p'] > 0){
							echo "&nbsp;<span class='badge badge-pill badge-dark'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['p_Short_HTML']['Title']}: " . ($_GET['p']) . "%</span>";
						}
						
						if ($_GET['l'] > 0){
							echo "&nbsp;<span class='badge badge-pill badge-secondary'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['l_Short_HTML']['Title']} " . ($_GET['l']) . "</span>";
						}
					}

					
					foreach($_GET['Genes'] as $tempKeyX => $currentGene){
						echo "&nbsp;<span class='badge badge-pill badge-info'>{$currentGene}</span>";
					}
					
					if ($plotContent['result']){
						echo "&nbsp;<span class='badge badge-pill badge-secdonary'><a href='javascript:void(0);' id='{$plotDownloadID}'>" . printFontAwesomeIcon('fas fa-file-download') . "&nbsp;Download Plot (SVG)</a></span>";
					}
					
					if (true){
						echo "&nbsp;<span class='badge badge-pill badge-secdonary'><a href='#advancedOptionsModal' data-toggle='modal'>" . printFontAwesomeIcon('fas fa-cog') . " Advanced Options</a></span>";
					}
					
				echo "</p>";
				
				
				//Advanced Options Modal
				if (true){
					$modalID 	= 'advancedOptionsModal';
					$modalTitle = '<h3>Advanced Options</h3>';
					$modalBody  = '';
					
					
					$modalBody  .= "<div class='row'>";
					
					if (true){
						$modalBody  .= "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>";
						//Genes
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='Genes' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Genes']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12' id='Genes' name='Genes' value='{$_GET['Genes_String']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Expression Cutoff
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='g' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['g']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='g' name='g' value='{$_GET['g']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Annotation Group
						if (array_size($currentProjectProcessed['File_h5ad_info']['annotation']) > 0){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='Project_Group' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'>
													<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Project_Groups']['Title']}:</strong>
												</label>";
										
								$modalBody .=  "<select id='Project_Group' class='form-control form-control-sm col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12'>";
									
									foreach($currentProjectProcessed['File_h5ad_info']['annotation'] as $groupName => $groupArray){
										
										$groupNameDisplay = ucwords(str_replace(array('_'), ' ', $groupName));
										
										$selected = '';
										
										if ($groupName == $annotationGroup){
											$selected = 'selected';	
										}
										
										$modalBody .=  "<option {$selected} value='{$groupName}'>{$groupNameDisplay}</option>";
									}
									
								$modalBody .=  "</select>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Subsampling
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='Subsampling' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'>
										<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Subsampling']['Title']}:</strong>
										</label>";
										
								$modalBody .=  "<select id='Subsampling' class='form-control form-control-sm col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12'>";
									
									foreach($BXAF_CONFIG['SETTINGS']['Gene_Plot_SubSampling'] as $tempKeyX => $tempValueX){
										
										
										$selected = '';
										
										if ($tempKeyX == $_GET['Subsampling']){
											$selected = 'selected';	
										}
										
										$modalBody .=  "<option {$selected} value='{$tempKeyX}'>{$tempValueX}</option>";
									}
									
								$modalBody .=  "</select>";
							$modalBody .=  "</div>";	
							$modalBody .=  "<br/>";
						}
						
						//Plot Type
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='Plot_Type' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'>
												<strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Plot_Type']['Title']}:</strong>
												</label>";
										
								$modalBody .=  "<select id='Plot_Type' class='form-control form-control-sm col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12'>";
									
									foreach($BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['Plot_Type']['Value'] as $tempKeyX => $tempValueX){
										
										
										$selected = '';
										
										if ($tempKeyX == $_GET['Plot_Type']){
											$selected = 'selected';	
										}
										
										$modalBody .=  "<option {$selected} value='{$tempKeyX}'>{$tempValueX}</option>";
									}
									
								$modalBody .=  "</select>";
							$modalBody .=  "</div>";	
							$modalBody .=  "<br/>";
						}
						
						$modalBody  .= "</div>";
					}
					
					
					if (true){
						$modalBody  .= "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>";
						
						//Plot Dimension Text
						if (true){
							$modalBody .= "<div class='form-row'>";
								$modalBody .= "<div class='col-xl-12 col-lg-12 col-md-12'>";
									$modalBody .= "<div class='row'>";
										$modalBody .= "<div class='col-12'>
															<strong>Plot Dimensions</strong>
															<br/>
															
															<span class='text-muted'>
															Enter 0 or leave it empty to trigger the auto mode. Notice that if your value is smaller than the default value, it will be discarded because the chart library does not support downscaling.
															</span>
													   </div>";
									$modalBody .= "</div>";
								$modalBody .= "</div>";
							$modalBody .= "</div>";
						}
						
						//Plot Height
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='plot_height' class='col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['plot_height']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='plot_height' name='plot_height' value='{$_GET['plot_height']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Plot Width
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='plot_width' class='col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['plot_width']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='plot_width' name='plot_width' value='{$_GET['plot_width']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Image Height
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='export_height' class='col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['export_height']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='export_height' name='export_height' value='{$_GET['export_height']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						//Image Width
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='export_width' class='col-xl-5 col-lg-5 col-md-12 col-sm-12 col-xs-12'><strong>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['export_width']['Title']}:</strong></label>";
								$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='export_width' name='export_width' value='{$_GET['export_width']}'>";
							$modalBody .=  "</div>";
							$modalBody .=  "<br/>";
						}
						
						$modalBody  .= "</div>";
					
					}
					
					$modalBody  .= "</div>";
					
					
					if (true){
						$modalBody .= "<div class='row'>";
						$modalBody .= "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12'>";
						
						if ($_GET['Plot_Type'] == 'dot'){
							$class = '';	
						} else {
							$class = 'startHidden';	
						}
						
						$modalBody .= "<div id='Dot_Plot_Section' class='{$class}'>";
						
						//Dot Plot Options
						if (true){
							$modalBody .= "<div class='form-row'>";
								$modalBody .= "<div class='col-xl-12 col-lg-12 col-md-12'>";
									$modalBody .= "<div class='row'>";
										$modalBody .= "<div class='col-12'><strong>Dot Plot Options:</strong></div>";
									$modalBody .= "</div>";
								$modalBody .= "</div>";
							$modalBody .= "</div>";
							$modalBody .= "<br/>";
							
						}
						
						
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<label for='Plot_Type' class='col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12'>
												{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['d']['Title']}:
												</label>";
										
								$modalBody .=  "<select id='d' class='form-control form-control-sm col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12'>";
									
									foreach($BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['d']['Value'] as $tempKeyX => $tempValueX){
										
										
										$selected = '';
										
										if ($tempKeyX == $_GET['d']){
											$selected = 'selected';	
										}
										
										$modalBody .=  "<option {$selected} value='{$tempKeyX}'>{$tempValueX}</option>";
									}
									
								$modalBody .=  "</select>";
							$modalBody .=  "</div>";	
							$modalBody .=  "<br/>";
						}
						
						//Gene Expression Color Scale
						if (true){
							$modalBody .=  "<div class='row'>";
								$modalBody .=  "<div class='col-xl-12 col-lg-12 col-md-12'>";
									$modalBody .=  "<div class='row'>";
										$modalBody .=  "<label class='col-12 col-form-label'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['e']['Title']} (0 to 100):</label>";
									$modalBody .=  "</div>";
								$modalBody .=  "</div>";
							$modalBody .=  "</div>";
						}
						
						
						//Gene Expression Color Scale
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<div class='form-group col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4'>";
									$modalBody .=  "<label for='e_min'>From:</label>";
									$modalBody .=  "<input type='text' class='form-control form-control-sm' id='e_min' name='e_min' value='{$_GET['e_min']}'>";
								$modalBody .=  "</div>";
								
								
								$modalBody .=  "<div class='form-group col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4'>";
									$modalBody .=  "<label for='e_max'>To:</label>";
									$modalBody .=  "<input type='text' class='form-control form-control-sm' id='e_max' name='e_max' value='{$_GET['e_max']}'>";
								$modalBody .=  "</div>";
							$modalBody .=  "</div>";
				
						}
						
						
						//Percentage Represented by the Largest Dot (1-100):
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<div class='form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
									$modalBody .=  "<label for='p'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['p']['Title']} (1-100):</label>";
									$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='p' name='p' value='{$_GET['p']}'>";
								$modalBody .=  "</div>";
							$modalBody .=  "</div>";
						}
						
						
						//Perform log2 transforomation
						if (true){
							$modalBody .=  "<div class='form-row'>";
								$modalBody .=  "<div class='form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12'>";
									$modalBody .=  "<label for='l'>{$BXAF_CONFIG['MESSAGE'][$APP_CONFIG['TABLES']['PROJECT']]['Column']['l_HTML']['Title']}:</label>";
									$modalBody .=  "<input type='text' class='form-control form-control-sm col-xl-3 col-lg-3 col-md-4 col-sm-4 col-xs-4' id='l' name='l' value='{$_GET['l']}'>";
								$modalBody .=  "</div>";
							$modalBody .=  "</div>";
						}

						$modalBody .= "</div>";
						$modalBody .= "</div>";
						$modalBody .= "</div>";
						
					}
					

					echo printModal($modalID, $modalTitle, $modalBody, 'Apply Changes', '', 'Apply_Changes_Trigger', 'modal-body-half-width');	

				}

			
				echo "<div class='overflow-auto' style='padding:10px;'>";
					echo $plotCode;
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</div>";
	
	
}

echo getFullScreenSpinner(NULL, NULL, 'Please wait while the system is regenerating the plot...');


?>

<style>
.modal-body-half-width{
    max-width: 60% !important;
    width: auto !important;
    overflow-x: auto !important;
}
</style>


<script type="text/javascript">
$(document).ready(function(){
	
	
	
	$(document).on('change', '#Plot_Type', function(){

		var currentValue = $(this).val();
		
		if (currentValue == 'dot'){
			$('#Dot_Plot_Section').show();	
		} else {
			$('#Dot_Plot_Section').hide();	
		}
	});
	
	
	
	
	$(document).on('click', '.Apply_Changes_Trigger', function(){
		
		<?php if ($ID > 0){ ?>
			var URL = 'api_gene_plot.php?ID=<?php echo $ID; ?>';
		<?php } elseif ($_GET['Path'] != ''){ ?>
			var URL = 'api_gene_plot.php?Path=<?php echo $_GET['Path']; ?>';
		<?php } ?>		
		
		URL += '&Genes=' + $('#Genes').val();
		URL += '&g=' + $('#g').val();
		URL += '&Project_Group=' + $('#Project_Group').val();
		URL += '&Subsampling=' + $('#Subsampling').val();
		URL += '&Plot_Type=' + $('#Plot_Type').val();
		URL += '&plot_height=' + $('#plot_height').val();
		URL += '&plot_width=' + $('#plot_width').val();
		URL += '&export_height=' + $('#export_height').val();
		URL += '&export_width=' + $('#export_width').val();
		URL += '&d=' + $('#d').val();
		URL += '&e_min=' + $('#e_min').val();
		URL += '&e_max=' + $('#e_max').val();
		URL += '&p=' + $('#p').val();
		URL += '&l=' + $('#l').val();
		
		
		$('#full_screen_spinner').addClass('show');
		window.location = URL;
	
	});
	
	
	
});

</script>

