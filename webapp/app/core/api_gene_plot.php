<?php

$BXAF_CONFIG['API'] = 1;

include_once('config_init.php');


if (true){
	$_GET['Genes'] = id_sanitizer($_GET['Genes'], 1, 0, 0, 1);
	$_GET['Genes'] = array_iunique($_GET['Genes'], 1);
	$_GET['Genes'] = array_map('strtoupper', $_GET['Genes']);


	if (array_size($_GET['Genes']) <= 0){
		echo "<p><strong>Error</strong>. A gene is required.</p>";
		echo "<p>Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>documentation</a> for details.</p>";
		exit();
	}
}


if ($ID > 0){
	$currentProjectID 		= $ID;
	$currentProject 		= getProjectByID($currentProjectID);
	
	if (array_size($currentProject) <= 0){
		echo "<p><strong>Error</strong>. The project record (ID: {$currentProjectID}) is not available.</p>";
		echo "<p>Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>documentation</a> for details.</p>";
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
		echo "<p><strong>Error</strong>. The dataset ({$_GET['Path']}) is not available.</p>";
		echo "<p>Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>documentation</a> for details.</p>";
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
	echo "<p><strong>Error</strong>. The dataset is not available.</p>";
	echo "<p>Please refer to the <a href='https://celldepot.bxgenomics.com/celldepot_manual/api_gene_plot.php' target='_blank'>documentation</a> for details.</p>";
	exit();	
}


if (($_GET['Plot_Type'] != 'violin') && ($_GET['Plot_Type'] != 'dot')){
	if (array_size($_GET['Genes']) <= 1){
		$_GET['Plot_Type'] = 'violin';	
	} else {
		$_GET['Plot_Type'] = 'dot';	
	}
}



$_GET['Subsampling'] 	= abs(intval($_GET['Subsampling']));
$_GET['g'] 				= abs(floatval($_GET['g']));
$_GET['e_min'] 			= abs(floatval($_GET['e_min']));
$_GET['e_max'] 			= abs(floatval($_GET['e_max']));
$_GET['p'] 				= abs(intval($_GET['p']));
$_GET['l'] 				= abs(floatval($_GET['l']));



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
							$_GET['l']
							);
							



if (!$_GET['json']){

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
					
				echo "</p>";
				
				
				
			
				echo "<div class='overflow-auto' style='padding:10px;'>";
					echo processGenePlot($plotContent['plot'], 'fullsize');
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	echo "</div>";
} else {
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($plotContent);	
}


?>