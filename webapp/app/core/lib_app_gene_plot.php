<?php

function getGenePlot($projectID = 0, $h5ad_file = '', $genes = array(), $plotType = '', $annotation_group = '', $use_default_annotation_group = 0, $subSampling = 0, $n = 0, $g = 0){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	$version = '2021-07-02 19:00';
	
	
	if (!file_exists($h5ad_file)){
		return false;	
	}
	
	
	$genes = id_sanitizer($genes, 1, 0, 0, 1);
	$genes = id_sanitizer($genes, 1, 0, 0, 1);
	$genes = array_iunique($genes, 1);
	$genes = array_map('strtoupper', $genes);
	
	if (array_size($genes) <= 0){
		return false;	
	}
	
	
	if (($plotType != 'violin') && ($plotType != 'dot')){
		if (array_size($genes) == 1){
			$plotType = 'violin';	
		} else {
			$plotType = 'dot';
		}
	}
	
	$cmd[] = $BXAF_CONFIG['CELLXGENE_plotH5ad'];
	$cmd[] = $h5ad_file;
	$cmd[] = $plotType;
	$cmd[] = implode(',', $genes);
	
	if ($annotation_group != ''){
		$cmd[] = "'{$annotation_group}'";
	}
	
	$subSampling = abs(intval($subSampling));
	if ($subSampling > 0){
		$cmd[] = $subSampling;	
	}
	
	$n = abs(floatval($n));
	if ($n > 0){
		$cmd[] = "-n {$n}";
	}
	
	$g = abs(floatval($g));
	if ($g > 0){
		$cmd[] = "-g {$g}";
	}
	
	$cmd = implode(' ', $cmd);
	
	
	$cacheKey = __FUNCTION__ . '::' . md5($cmd . '::' . $version);
	
	$resultsFromCache = getRedisCache($cacheKey);
	
	$results = array();
	$results['command'] = $cmd;
	
	if ($resultsFromCache !== false){
		$results = $resultsFromCache;
		$results['source'] 	= 'cache';
	} else {
		$cmd_results = trim(shell_exec($cmd));
		$results['source'] 	= 'executed command';
		
		if ($cmd_results == ''){
			$cmd_results = 'The result is not available.';
			$results['result'] = 0;	
		} else {
			$results['result'] = 1;	
		}
		$results['plot'] 	= $cmd_results;
		
		if (true){
			putRedisCache(array($cacheKey => $results));
		}
		
		
		$dataArray = array();
		$dataArray['Project_ID'] 			= $projectID;
		$dataArray['plotH5ad_version'] 		= $version;
		$dataArray['File'] 					= $h5ad_file;
		$dataArray['Genes'] 				= implode(',', $genes);
		$dataArray['Plot_Type'] 			= $plotType;
		$dataArray['Annotation_Group'] 		= $annotation_group;
		$dataArray['SubSampling'] 			= $subSampling;
		$dataArray['n'] 					= $n;
		$dataArray['g'] 					= $g;
		$dataArray['Command'] 				= $cmd;
		$dataArray['Command_Checksum'] 		= md5($cmd);
		$dataArray['Parameters_Checksum'] 	= getGenePlotParameterChecksum($genes, $annotation_group, $use_default_annotation_group, $subSampling, $n, $g);
		
		$dataArray['Results'] 				= $results['plot'];
		$dataArray['Result_Status'] 		= $results['result'];
		
		$SQL = getReplaceSQLQuery($APP_CONFIG['TABLES']['PROJECT_GENE_PLOT'], $dataArray);
		
		
		executeSQL($SQL);
	}
	
	


	
	return $results;
	
}

function getGenePlotParameterChecksum($genes = array(), $annotation_group = '', $use_default_annotation_group = 0, $subSampling = 0, $n = 0, $g = 0){
	
	$genes = id_sanitizer($genes, 1, 0, 0, 1);
	$genes = id_sanitizer($genes, 1, 0, 0, 1);
	$genes = array_iunique($genes, 1);
	$genes = array_map('strtoupper', $genes);
	$genes = implode(',', $genes);

	if (array_size($genes) == 1){
		$plotType = 'violin';	
	} else {
		$plotType = 'dot';
	}
	
	$subSampling = abs(intval($subSampling));
	$n = abs(floatval($n));
	$g = abs(intval($g));
	
	
	$checksum['Genes'] = $genes;
	$checksum['Plot_Type'] = $plotType;
	
	if ($use_default_annotation_group){
		$checksum['use_default_annotation_group'] = 1;
	} else {
		$checksum['annotation_group'] = $annotation_group;
	}
	$checksum['subSampling'] = $subSampling;
	$checksum['n'] = $n;
	$checksum['g'] = $g;
	
	return md5(json_encode($checksum));
}


function getProjectsForGenePlot($inputArray = NULL){
	
	global $APP_CONFIG;

	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];
	
	$geneIndexes = getNameIndexes($APP_CONFIG['TABLES']['GENE_INDEX'], $inputArray['Genes']);
	$geneIndexes = id_sanitizer($geneIndexes, 0, 1, 0, 2);
	
	
	
	//if ($geneIndexes == ''){
	if (0){
		if ($inputArray['Hide_Empty']){
			
			$parameterChecksum = getGenePlotParameterChecksum($inputArray['Genes'], '', 1, 0, $inputArray['n'], $inputArray['g']);
			
			$SQL = "SELECT `Project_ID` FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']}` WHERE
				(`Parameters_Checksum` = '{$parameterChecksum}') AND (`Result_Status` = 0)";
			$badProjectIDs = getSQL_Data($SQL, 'GetCol');	
			$badProjectIDs = id_sanitizer($badProjectIDs, 0, 1, 0, 2);
		}
	} else {
		$projectWithResultIDs = '';
		
		$inputArray['g'] = floatval($inputArray['g']);
		
		if ($inputArray['g'] <= 0){
			$SQL = "SELECT `Project_ID` FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_INDEX']}` WHERE (`Gene_Index` IN ({$geneIndexes}))";
		} else {
			$SQL = "SELECT `Project_ID` FROM `{$APP_CONFIG['TABLES']['PROJECT_GENE_INDEX']}` WHERE (`Gene_Index` IN ({$geneIndexes})) AND (`Gene_Expression` >= {$inputArray['g']})";
		}
		
		$projectWithResultIDs = getSQL_Data($SQL, 'GetCol');	
		$projectWithResultIDs = id_sanitizer($projectWithResultIDs, 0, 1, 0, 2);

	}

	
	

	$SQL = "SELECT `ID`, `Name`, `Accession` FROM {$SQL_TABLE} WHERE (`File_CSCh5ad_status` = 1)";
	
	$preSelectedIDs = id_sanitizer($inputArray['preselected'], 0, 1, 0, 2);
	if ($preSelectedIDs != ''){
		$SQL = "{$SQL} AND (`ID` IN ({$preSelectedIDs}))";	
	}
	
	
	if ($projectWithResultIDs != ''){
		$SQL = "{$SQL} AND (`ID` IN ({$projectWithResultIDs}))";	
	}
	
	
	
	
	if ($badProjectIDs != ''){
		$SQL = "{$SQL} AND (`ID` NOT IN ({$badProjectIDs}))";	
	}
	
	
	
	$results = getSQL_Data($SQL, 'GetAssoc');

	return $results;
	
	
	
}

function getGenePlotAPIURL($ID = 0, $Genes = NULL, $Plot_Type = '', $Subsampling = '', $n = 0, $g = 0, $Project_Group = ''){
	
	$Genes = implode(',', $Genes);

	return "api_gene_plot.php?ID={$ID}&Genes={$Genes}&Plot_Type={$Plot_Type}&Subsampling={$Subsampling}&n={$n}&g={$g}&Project_Group={$Project_Group}";
	
}


function processGenePlot($string = '', $type = 'compact'){
	
	if ($type != 'compact'){
		$type = 'fullsize';	
	}
	
	
	if (strpos($string, '"type": "violin"') !== FALSE){
		$plot_type = 'violin';
	} elseif (strpos($string, '"type": "scatter"') !== FALSE){
		$plot_type = 'scatter';
	} else {
		return $string;	
	}
	
	
	if (true){
		preg_match("/height:(.*?)px;/", $string, $matches);
		$height = intval($matches[1]);
		
		preg_match("/width:(.*?)px;/", $string, $matches);
		$width = intval($matches[1]);	
		
		if (($height <= 0) || ($width <= 0)){
			return $string;
		}
	}

	if (true){
		
		$svg_height = 1000;
		$svg_width	= ceil($svg_height*($width/$height));
		
		$string = str_replace('{"responsive": true}', 'config', $string);
		
		$string = str_replace(
						'Plotly.newPlot(', 
						
						"var config = {
						  toImageButtonOptions: {
							format: 'svg', // one of png, svg, jpeg, webp
							filename: 'gene_search',
							height: {$svg_height},
							width: {$svg_width},
							'responsive': true,
							scale: 1 // Multiply title/legend/axis/canvas sizes by this factor
						  },
						  
						  responsive: true
						};
						
						Plotly.newPlot(
						",

						$string);
	}
	
	if ($type == 'fullsize'){
		
		if ($plot_type == 'violin'){
			
			$plot_height 	= 800;
			$plot_width		= ceil($plot_height*($width/$height));
		
			$string = str_replace("height:{$height}px;", '', $string);
			$string = str_replace("width:{$width}px;", '', $string);
			
			/*
			$string = str_replace("\"height\": {$height},", "\"height\": {$plot_height},", $string);
			$string = str_replace("\"width\": {$width},", "\"width\": {$plot_width},", $string);
			*/
			
			$string = str_replace("\"height\": {$height},", '', $string);
			$string = str_replace("\"width\": {$width},", '', $string);
		}
		
		if ($plot_type == 'scatter'){
			
			$plot_height 	= 800;
			$plot_width		= ceil($plot_height*($width/$height));
		
			$string = str_replace("height:{$height}px;", "height:{$plot_height}px;", $string);
			$string = str_replace("width:{$width}px;", "width:{$plot_width}px;", $string);
			
			
			$string = str_replace("\"height\": {$height},", "\"height\": {$plot_height},", $string);
			$string = str_replace("\"width\": {$width},", "\"width\": {$plot_width},", $string);
			
			/*
			$string = str_replace("\"height\": {$height},", '', $string);
			$string = str_replace("\"width\": {$width},", '', $string);
			*/
		}

	}
	
	
	
	
	return $string;
	
}

?>