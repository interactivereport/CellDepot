<?php

if (isAdminUser()){
	

	unset($currentIndex);
	
	
	
	$currentIndex++;
	$tools[$currentIndex]['Title'] 	= 'Clear system cache';
	$tools[$currentIndex]['URL'] 	= 'admin_clear_caches.php';
	$tools[$currentIndex]['target'] = '_blank';
	
	$currentIndex++;
	$tools[$currentIndex]['Title'] 	= 'Cellxgene Launching Command Documenation';
	$tools[$currentIndex]['URL'] 	= 'admin_cellxgene_docs.php';
	$tools[$currentIndex]['target'] = '_blank';	
	
	
	$currentIndex++;
	$tools[$currentIndex]['Title'] 	= 'API Documenation';
	$tools[$currentIndex]['URL'] 	= 'https://celldepot.bxgenomics.com/celldepot_manual/api.php';
	$tools[$currentIndex]['target'] = '_blank';
	
	
	
	echo "<ul>";
		
		foreach($tools as $tempKey => $tempValue){
			
			echo "<li>";
				echo "<a href='{$tempValue['URL']}' target='{$tempValue['target']}'>{$tempValue['Title']}</a>";
			echo "</li>";
			
			
		}
		
		
	
	
	echo "</ul>";
	
	
	
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>

