<?php


echo "<br/>";

if (true){

	echo "<div>";
	
		$items = array('Accession', 'Name', 'Species', 'Cell_Count', 'Gene_Count');
		
		$results = array();
		
		$results[] = "<a href='{$URL}' target='_blank'>View in Fullscreen</a>";
		$results[] = "<a href='app_project_review.php?ID={$ID}'>View Project Details</a>";
		
		foreach ($items as $tempKey => $currentColumn){
			$title = $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
			$results[] = "<strong>{$title}</strong>: {$projectInfo[$currentColumn]}";
		}
		
		echo implode(' &nbsp; ', $results);
	
	echo "</div>";
	
	
}

echo "<hr/>";
	
if (true){
	
	echo "<div>";
		echo "<iframe id='iframe' src='{$URL}' allowfullscreen></iframe>";
	echo "</div>";

	
}
	


?>

<script type="text/javascript">

$(document).ready(function(){
	$('#iframe').contents().find('h2').hide();
	
});
</script>

<style type="text/css" media="screen">
<?php if ($PAGE['Full_Screen']){ ?>
	html { 
		overflow: auto; 
	} 
	  
	html, 
	body, 
	div, 
	iframe { 
		margin: 0px; 
		padding: 0px; 
		height: 100%; 
		border: none; 
	} 
	  
	iframe { 
		display: block; 
		width: 100%; 
		border: none; 
		overflow-y: auto; 
		overflow-x: hidden; 
	} 
<?php } else { ?>
	
	iframe { 
		margin: 0px; 
		padding: 0px; 
		height: 100%; 
		border: none; 
	} 
	  
	iframe { 
		display: block; 
		width: 100%; 
		height:100%;
		min-height:1000px;
		border: none; 
		overflow-y: auto; 
		overflow-x: hidden; 
	} 
<?php } ?>

</style>



