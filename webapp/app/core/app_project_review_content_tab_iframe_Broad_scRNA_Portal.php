<?php




if (true){
	$message = 
	printFontAwesomeIcon('fas fa-info-circle text-info') . 
	" Below is a snapshot of the study summary from Broad scRNA portal on {$dataArray['URL_Date']}.
	To use any links from the page below,  please go to {$dataArray['URL']} and open the links from there.
	";
	
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			echo getAlerts($message, 'info', 'col-12');
		echo "</div>";
	echo "</div>";
}


echo "<br/>";

if (true){
	
	echo "<div>";
		echo "<iframe id='iframe' src='app_project_Broad_scRNA_Portal.php?ID={$ID}' allowfullscreen></iframe>";
	echo "</div>";

	
}
	


?>

<script type="text/javascript">

$(document).ready(function(){
	
	
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



