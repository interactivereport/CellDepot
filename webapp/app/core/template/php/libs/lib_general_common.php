<?php


$tempDir = dirname(__FILE__) . "/common/";
$tempFiles = glob("{$tempDir}/*.php");
foreach ($tempFiles as $tempKey => $tempValue){
	include_once($tempValue);
}

?>