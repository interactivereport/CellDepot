<?php

$settingFiles = glob("{$BXAF_CONFIG['BXAF_SETUP_DIR']}/default/*.php");
sort($settingFiles);
foreach ($settingFiles as $tempKey => $settingFile){
	include_once($settingFile);
}



$settingFiles = glob("{$BXAF_CONFIG['BXAF_SETUP_DIR']}/override/*.php");
sort($settingFiles);
foreach ($settingFiles as $tempKey => $settingFile){
	include_once($settingFile);
}


unset($settingFiles, $tempKey, $settingFile);


?>