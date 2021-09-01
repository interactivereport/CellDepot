<?php

function getCellxgeneSummary($cache = 1){

	global $BXAF_CONFIG, $APP_CACHE;
	
	if ($cache){
		if (isset($APP_CACHE[__FUNCTION__])){
			return $APP_CACHE[__FUNCTION__];
		}
	}
	
	$BXAF_CONFIG['CELLXGENE_PORT_START'] 	= abs(intval($BXAF_CONFIG['CELLXGENE_PORT_START']));
	$BXAF_CONFIG['CELLXGENE_PORT_END'] 		= abs(intval($BXAF_CONFIG['CELLXGENE_PORT_END']));
	
	if ($BXAF_CONFIG['CELLXGENE_PORT_END'] > 65535) $BXAF_CONFIG['CELLXGENE_PORT_END'] = 65535;
	
	if ($BXAF_CONFIG['CELLXGENE_PORT_START'] <= 0) return false;
	if ($BXAF_CONFIG['CELLXGENE_PORT_END'] <= 0) return false;
	
	
	for ($port = $BXAF_CONFIG['CELLXGENE_PORT_START']; $port <= $BXAF_CONFIG['CELLXGENE_PORT_END']; $port++){
		$output = array();
		
		$cmd = "{$BXAF_CONFIG['NETSTAT_BIN']} -tuplen | grep '0.0.0.0:{$port}'";
		
		exec($cmd, $output);
		
		if (is_array($output) && count($output) > 0){
			$results['Port_Status'][$port] = 'Running';
			$results['Port_Running'][] = $port;
		} else {
			$results['Port_Status'][$port] = 'Available';
			$results['Port_Available'][] = $port;
		}
	}
	
	$APP_CACHE[__FUNCTION__] = $results;
	
	return $results;

}


function startCellxgeneProcess($projectID = 0){
	
	global $BXAF_CONFIG;
	
	$CELLXGENE_CACHE_DIR 	= "{$BXAF_CONFIG['WORK_DIR']}cellxgene/";
	
	$dataArray = getProjectByID($projectID);
	
	if ($dataArray['Launch_Method'] == 0){
		return false;	
	}
	
	if (getCellxgeneProcessID($projectID) > 0){
		$file = $CELLXGENE_CACHE_DIR . $projectID . '.port';
		$port = trim(file_get_contents($file));
		$results['Port'] 	= $port;
		$results['URL'] 	= "{$BXAF_CONFIG['CELLXGENE_URL']}:{$port}/";
		return $results;	
	}
	
	$getCellxgeneSummary = getCellxgeneSummary(0);
	$port = $getCellxgeneSummary['Port_Available'][0];
	if ($port <= 0){
		$results['Error'] 	= "Unable to start the application. No port is available.";
		return $results;
	}
	
	if (array_size($dataArray) <= 0){
		$results['Error'] 	= "The project does not exist.";
		return $results;	
	}
	
	$filePath = $BXAF_CONFIG['Server'][($dataArray['File_Server_ID'])]['File_Directory'] . $dataArray['File_Name'];
	
	
	if (!is_dir($CELLXGENE_CACHE_DIR)){
		mkdir($CELLXGENE_CACHE_DIR, 0777);	
	}
	
	
	$command = custom_get_cellxgene_launch_command($port, realpath ($filePath));
	
	
	$script = $CELLXGENE_CACHE_DIR . $projectID . '.sh';
	file_put_contents($script, $command);
	chmod($script, 0775);
	
	
	shell_exec("nohup {$script} --dataset_index=CellDepot_{$projectID}_CellDepot > {$CELLXGENE_CACHE_DIR}{$projectID}.log 2>&1 &");
	
	
	do {
		sleep(1);
		if (preg_match('/Type CTRL-C at any time to exit/m', file_get_contents("{$CELLXGENE_CACHE_DIR}{$projectID}.log"))){
			break;
		}
		$count++;
	} while ($count <= 15);
	
	
	if (getCellxgeneProcessID($projectID) > 0){
		$file = $CELLXGENE_CACHE_DIR . $projectID . '.port';
		file_put_contents($file, $port);
		chmod($script, 0775);
		
		
		$results['Port'] 	= $port;
		$results['URL'] 	= "{$BXAF_CONFIG['CELLXGENE_URL']}:{$port}/";
	}
	
	return $results;
	
}


function getCellxgeneProcessID($projectID = 0){
	
	global $BXAF_CONFIG;

	if (true){
		$cmd = "{$BXAF_CONFIG['PS_BIN']} -ax | {$BXAF_CONFIG['GREP_BIN']} 'CellDepot_{$projectID}_CellDepot' | {$BXAF_CONFIG['GREP_BIN']} -v grep";
		$processID = trim(shell_exec($cmd));
		
		$processID = explode(' ', $processID);
		$processID = intval($processID[0]);
	}
		
	
	return $processID;
	
}



function isCellxGeneRunning($projectID = 0){

	$processID = getCellxgeneProcessID($projectID);
	
	if ($processID <= 0){
		return false;
	} else {
		return true;	
	}
	
}


function stopCellxGeneProcess($projectID = 0){
	
	global $BXAF_CONFIG;
	
	$processID = getCellxgeneProcessID($projectID);
	
	if ($processID <= 0) return false;
		
	$cmd = "{$BXAF_CONFIG['PKILL_BIN']} -9 -P {$processID}";
	shell_exec($cmd);
	
	
	$CELLXGENE_CACHE_DIR 	= "{$BXAF_CONFIG['WORK_DIR']}cellxgene/";
	$file = $CELLXGENE_CACHE_DIR . $projectID . '.log';
	unlink($file);
	
	$file = $CELLXGENE_CACHE_DIR . $projectID . '.port';
	unlink($file);
	
	$file = $CELLXGENE_CACHE_DIR . $projectID . '.sh';
	unlink($file);
	
	
	
	
	return true;
	
}

?>