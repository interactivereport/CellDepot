<?php
include_once('config_init.php');

if (!isAdminUser()){
	echo printMsg("You do not have permissions to access this tool.");	
	return;
}


$SQLs = array();
if (true){
	
	$tableName = $APP_CONFIG['TABLES']['PROJECT'];;
	$currentColumns = getSQLColumnStructure($tableName);
	
	if (!isset($currentColumns['Diseases'])){
		$SQLs[] = "ALTER TABLE `{$tableName}` ADD `Diseases` varchar(512) NOT NULL AFTER `Title`";
		echo "<p>[Executed] Table <strong>{$tableName}</strong> has been patched.</p>";
	}
	

	
	
	if (array_size($SQLs) > 0){
		$patchExecedGlobal++;	
		
		foreach($SQLs as $tempKey => $SQL){
			executeSQL($SQL);
		}
		
		clearCache();
	} else {
		echo "<p>[Skipped] The tables have already been patched.</p>";			
		
	}
}





?>



