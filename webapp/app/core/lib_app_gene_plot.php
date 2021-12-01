<?php

function getGenePlot($projectID = 0, $h5ad_file = '', $genes = array(), $plotType = '', $annotation_group = '', $use_default_annotation_group = 0, 
					$subSampling = 0, $g = 0, $e_min = 0, $e_max = 0, $p = 0, $l = 0, $d = 'v'){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	$version = '2021-11-29 18:00';
	
	if (!file_exists($h5ad_file)){
		return false;	
	}
	
	
	
	$genes = id_sanitizer($genes, 0, 0, 0, 1);
	$genes = array_map('trim', $genes);
	$genes = array_iunique($genes, 1);
	$genes = array_map('strtoupper', $genes);
	
	foreach($genes as $tempKey => $currentGene){
		$genes[$tempKey] = preg_replace( '/[\W]/', '', $currentGene);
	}
	
	$genes = array_filter($genes, 'trim');
	


	if (array_size($genes) <= 0){
		return false;	
	}
	
	$plotType = trim(strtolower($plotType));
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
	$cmd[] = str_replace(' ', '', implode(',', $genes));
	
	if ($annotation_group != ''){
		$cmd[] = "'{$annotation_group}'";
	}
	
	$subSampling = abs(intval($subSampling));
	if ($subSampling > 0){
		$cmd[] = "-n {$subSampling}";
	}
	
	
	$g = abs(floatval($g));
	if ($g > 0){
		$cmd[] = "-g {$g}";
	}
	
	
	if ($plotType == 'dot'){
		$e_min = abs(floatval($e_min));
		$e_max = abs(floatval($e_max));
		if (($e_min >= 0) && ($e_max > 0) && ($e_max > $e_min)){
			$cmd[] = "-e {$e_min},{$e_max}";
		}
		
		$p = abs(intval($p));
		
		if ($p > 100){
			$p = 100;	
		}
		
		if ($p > 0){
			$cmd[] = "-p {$p}";
		}
		
		$l = abs(floatval($l));
		if ($l > 0){
			$cmd[] = "-l {$l}";
		}
		
		$d = trim(strtolower($d));
		if ($d != ''){
			if ($d != 'h') $d = 'v';
			$cmd[] = "-d {$d}";
		}
	}
	
	
	$cmd = implode(' ', $cmd);
	
	
	$cacheKey = __FUNCTION__ . '::' . md5($cmd . '::' . $version);
	
	$resultsFromCache = getRedisCache($cacheKey);
	
	$results = array();
	$results['command'] = $cmd;
	
	
	
	if (($resultsFromCache !== false) && ($resultsFromCache !== NULL)){
		$results = $resultsFromCache;
		$results['source'] 	= 'cache';
	} else {
		$cmd_results = trim(shell_exec($cmd));
		$results['source'] 	= 'executed command';

		
		if (($cmd_results == '') || (strpos($cmd_results, '</script>') === false)){
			$cmd_results = '';
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
		$dataArray['g'] 					= $g;
		$dataArray['Command'] 				= $cmd;
		$dataArray['Command_Checksum'] 		= md5($cmd . '::' . $version);
		$dataArray['Parameters_Checksum'] 	= getGenePlotParameterChecksum($genes, $annotation_group, $use_default_annotation_group, $subSampling, $g, $e_min, $e_max, $p, $l);
		
		$dataArray['Results'] 				= $results['plot'];
		$dataArray['Result_Status'] 		= $results['result'];
		
		$SQL = getReplaceSQLQuery($APP_CONFIG['TABLES']['PROJECT_GENE_PLOT'], $dataArray);
		
		
		executeSQL($SQL);
	}
	
	


	
	return $results;
	
}

function getGenePlotParameterChecksum($genes = array(), $annotation_group = '', $use_default_annotation_group = 0, 
										$subSampling = 0, $g = 0, $e_min = 0, $e_max = 0, $p = 0, $l = 0){
	
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
	$g = abs(intval($g));
	
	$checksum = array();
	$checksum['Genes'] = $genes;
	$checksum['Plot_Type'] = $plotType;
	
	if ($use_default_annotation_group){
		$checksum['use_default_annotation_group'] = 1;
	} else {
		$checksum['annotation_group'] = $annotation_group;
	}
	$checksum['subSampling'] 	= $subSampling;
	$checksum['g'] 				= $g;

	
	if ($plotType == 'dot'){
		$checksum['e_min'] 			= $e_min;
		$checksum['e_max'] 			= $e_max;
		$checksum['p'] 				= $p;
		$checksum['l'] 				= $l;
	}
	
	
	
	
	return md5(json_encode($checksum));
}


function getProjectsForGenePlot($inputArray = NULL){
	
	global $APP_CONFIG;

	$SQL_TABLE = $APP_CONFIG['TABLES']['PROJECT'];
	
	$geneIndexes = getNameIndexes($APP_CONFIG['TABLES']['GENE_INDEX'], $inputArray['Genes']);
	$geneIndexes = id_sanitizer($geneIndexes, 0, 1, 0, 2);
	
	
	if (true){
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


function getGenePlotAPIURL($ID = 0, $Plot_Type = '', $Genes = NULL, $Project_Group = '',
							$Subsampling = '', $g = 0, $e_min = 0, $e_max = 0, $p = 0, $l = 0){
	
	$Genes = implode(',', $Genes);
	
	$parameters = array();
	$parameters['ID'] 				= $ID;
	$parameters['Plot_Type'] 		= strtolower($Plot_Type);
	$parameters['Genes'] 			= trim($Genes);
	$parameters['Project_Group'] 	= trim($Project_Group);
	$parameters['Subsampling'] 		= $Subsampling;
	$parameters['g'] 				= $g;
	
	if ($parameters['Plot_Type'] == 'dot'){
		$parameters['e_min'] 			= $e_min;
		$parameters['e_max'] 			= $e_max;
		$parameters['p'] 				= $p;
		$parameters['l'] 				= $l;
	}
	
	$http_build_query = http_build_query($parameters);
	

	return "api_gene_plot.php?" . $http_build_query;
	
}


//$getGenePlot: output from getGenePlot()
function processGenePlot($getGenePlot = NULL, $type = 'compact', $otherInfo = NULL){
	
	global $BXAF_CONFIG, $APP_CONFIG;
	
	$results = array();
	
	$string = $getGenePlot['plot'];

	if ((!$getGenePlot['result']) || ($string == '') || ($string == $BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Gene_Search_No_Result'])){
		$results['plot'] = $BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Gene_Search_No_Result'];
		
		return $results;
	}
	
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
		preg_match("/getElementById\(\"(.*?)\"\)/", $string, $matches);
		$graphDivID = trim($matches[1]);
	}
	
	

	if (true){
		
		if ($plot_type == 'violin'){
			$svg_height = 800;
		} elseif ($plot_type == 'scatter'){
			$svg_height = 700;
			
			if (array_size($otherInfo['Genes']) > 0){
				if (array_size($otherInfo['Genes']) <= 5){
					$height_per_gene = 55;		
				} elseif (array_size($otherInfo['Genes']) <= 10){
					$height_per_gene = 50;					
				} elseif (array_size($otherInfo['Genes']) <= 20){
					$height_per_gene = 45;
				} else {
					$height_per_gene = 40;
				}
				
				$svg_height = 225 + array_size($otherInfo['Genes'])*$height_per_gene;
			}
		}
		
		$svg_width	= ceil($svg_height*($width/$height));
		
		
		$otherInfo['export_height'] = positiveInt($otherInfo['export_height']);
		if ($otherInfo['export_height'] > 50000) $otherInfo['export_height'] = 50000;
		
		$otherInfo['export_width'] 	= positiveInt($otherInfo['export_width']);
		if ($otherInfo['export_width'] > 50000) $otherInfo['export_width'] = 50000;
		
		if ($otherInfo['export_height'] > 0){
			$svg_height	= $otherInfo['export_height'];
			$svg_width	= ceil($svg_height*($width/$height));
		} elseif ($otherInfo['export_width'] > 0){
			$svg_width	= $otherInfo['export_width'];
			$svg_height	= ceil($svg_width*($height/$width));
		}
		
		
		if ($svg_width < $width){
			$svg_width = $width;
			$svg_height = $height;
		}
		
		$string = str_replace('{"responsive": true}', 'config', $string);
		
		$string = str_replace(
						'Plotly.newPlot(', 
						
						"var config = {
						  toImageButtonOptions: {
							format: 'png', // one of png, svg, jpeg, webp
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
			
		} elseif ($plot_type == 'scatter'){
			
			$plot_height 	= 700;
			
			if (array_size($otherInfo['Genes']) > 0){
				if (array_size($otherInfo['Genes']) <= 5){
					$height_per_gene = 55;		
				} elseif (array_size($otherInfo['Genes']) <= 10){
					$height_per_gene = 50;					
				} elseif (array_size($otherInfo['Genes']) <= 20){
					$height_per_gene = 45;
				} else {
					$height_per_gene = 40;
				}
				
				$plot_height = 225 + array_size($otherInfo['Genes'])*$height_per_gene;
			}
			
			$plot_width		= ceil($plot_height*($width/$height));
		}
		
		if (true){
			$otherInfo['plot_height'] = positiveInt($otherInfo['plot_height']);
			if ($otherInfo['plot_height'] > 50000) $otherInfo['plot_height'] = 50000;
			
			$otherInfo['plot_width'] 	= positiveInt($otherInfo['plot_width']);
			if ($otherInfo['plot_width'] > 50000) $otherInfo['plot_width'] = 50000;
			
			if ($otherInfo['plot_height'] > 0){
				$plot_height	= $otherInfo['plot_height'];
				$plot_width	= ceil($plot_height*($width/$height));
			} elseif ($otherInfo['plot_width'] > 0){
				$plot_width	= $otherInfo['plot_width'];
				$plot_height	= ceil($plot_width*($height/$width));
			}
		}
		
		
		if ($plot_width > $width){
			$string = str_replace("height:{$height}px;", "height:{$plot_height}px;", $string);
			$string = str_replace("width:{$width}px;", "width:{$plot_width}px;", $string);
			
			
			$string = str_replace("\"height\": {$height},", "\"height\": {$plot_height},", $string);
			$string = str_replace("\"width\": {$width},", "\"width\": {$plot_width},", $string);
		}
	}
	
	
	
	
	
	
	$downloadImageCode = '	
								$(document).ready(function(){
									$(document).on("click", "#downloadTrigger_' . $graphDivID . '", function(){
										var graphDiv = document.getElementById("' . $graphDivID . '");
										Plotly.downloadImage(graphDiv, {format: "svg", width: ' . $svg_width . ', height: ' . $svg_height . ', filename: "gene_search"});
									});	
								});
							
							';
	
	$string = str_replace('</script>', "{$downloadImageCode}</script>", $string);

	$results['plot'] = $string;
	$results['download_id'] = "downloadTrigger_{$graphDivID}";
	$results['download'] = "<a href='javascript:void(0); id='downloadTrigger_{$graphDivID}'>Download</a>";
	
	
	return $results;
	
}

?>