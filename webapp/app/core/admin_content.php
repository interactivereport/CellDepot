<?php

if (isAdminUser()){
	
	
	echo "<p>If you have performed any changes at the database level manually, you will need to run the following tools.</p>";
	
	unset($currentIndex);
	
	
	
	$currentIndex++;
	$tools[$currentIndex]['Title'] 	= 'Clear system cache';
	$tools[$currentIndex]['URL'] 	= 'admin_clear_caches.php';
	$tools[$currentIndex]['target'] = '_blank';
	
	$currentIndex++;
	$tools[$currentIndex]['Title'] 	= 'Cellxgene Launching Command Documenation';
	$tools[$currentIndex]['URL'] 	= 'admin_cellxgene_docs.php';
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

