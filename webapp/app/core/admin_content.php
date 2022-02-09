<?php

if (isAdminUser()){
	unset($currentIndex);
	
	if (true){
		$currentGroup = "Run the following if you have modified the configuration files:";
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Clear Application Cache';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'admin_clear_caches.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
	}
	
	
	if (true){
		$currentGroup = "Run all of the following if you have performed any changes at the database level manually (e.g., update records via SQL):";
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Clear Application Cache';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'admin_clear_caches.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
		
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Rebuild Project-Species and Project-Disease Indexes';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'admin_rebuild_project_species_index.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
	}
	
	
	if (true){
		$currentGroup = "Documentations";
		
		if ($BXAF_CONFIG['SETTINGS']['Tutorial_URL'] != ''){
			$currentIndex++;
			$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Tutorial';
			$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= $BXAF_CONFIG['SETTINGS']['Tutorial_URL'];
			$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';	
		}
		
		
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Cellxgene Launching Command Documenation';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'admin_cellxgene_docs.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';	
		
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Installation Guide';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'https://celldepot.bxgenomics.com/celldepot_manual/install_environment.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'API Documenation';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'https://celldepot.bxgenomics.com/celldepot_manual/api.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
	
		
	}
	
	
	if (true){
		$currentGroup = "Run all of the following if you have installed/updated/patched the application code:";
		
		
		$currentIndex++;
		$tools[$currentGroup]['Items'][$currentIndex]['Title'] 	= 'Upgrade Database Structures';
		$tools[$currentGroup]['Items'][$currentIndex]['URL'] 	= 'admin_patches.php';
		$tools[$currentGroup]['Items'][$currentIndex]['target'] = '_blank';
			
	}
	
	
	foreach($tools as $currentGroup => $items){
		
		echo "<h5>{$currentGroup}</h5>";
		
		if (array_size($items['Items']) == 1){
			echo "<ul>";	
		} else {
			echo "<ol>";	
		}
			
			foreach($items['Items'] as $tempKey => $tempValue){
				echo "<li>";
					echo "<a href='{$tempValue['URL']}' target='{$tempValue['target']}'>{$tempValue['Title']}</a>";
				echo "</li>";
			}
			
			
		if (array_size($items['Items']) == 1){
			echo "</ul>";	
		} else {
			echo "</ol>";	
		}
		
		echo "<br/>";
	}
	
	
	
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>

