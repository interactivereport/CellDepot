<?php
include_once('config_init.php');


if (isAdminUser()){
	
	$projectInfo = getProjectByID($ID);
	
	if (array_size($projectInfo) <= 0){
		echo "<div class='row'>";
			echo "<div class='col-12'>";
				$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-warning') . " The record does not exist. Please verify your URL and try again.</div>";
				echo getAlerts($message, 'warning');
			echo "</div>";
		echo "</div>";
		exit();
	}
	
	if ($projectInfo['Launch_Method'] != 1){
		echo "<div class='row'>";
			echo "<div class='col-12'>";
				$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-warning') . " The launching method of this project is set to <strong>Standard</strong>. Please update the method to <strong>Preload in Memory</strong> first.</div>";
				echo getAlerts($message, 'warning');
			echo "</div>";
		echo "</div>";
		exit();
	}
	
	startCellxgeneProcess($ID);
	header("Location: app_project_review.php?ID={$ID}&started=1");
	exit();
	
}

header("Location: index.php");
exit();




?>