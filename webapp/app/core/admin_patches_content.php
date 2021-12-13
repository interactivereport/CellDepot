<?php

if (isAdminUser()){
	
	$allPatches = array();
	$allPatches[] = 'admin_patches_script-2021-12-12-App_Project_Add_Disease.php';
	
	
	foreach($allPatches as $tempKey => $currentPatchFile){
		
		echo "<p>Running: <strong>{$currentPatchFile}</strong></p>";
		echo "<div style='margin-left:20px;'>";
			if (file_exists($currentPatchFile)){
				include($currentPatchFile);
			} else {
				echo "<div>File is missing.</div>";	
			}
		echo "</div>";
		echo "<hr/>";
	}
	
} else {
	echo "<p>You do not have permissions to access this tool.</p>";	
}

?>