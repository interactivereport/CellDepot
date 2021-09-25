<?php

include_once('config_init.php');

if (true){
	$key = $_GET['key'];
	if ($key == '') exit();
	
	$_POST = getRedisCache($key);
	if (array_size($_POST) <= 0) exit();
}

$page = abs(intval($_GET['page']));
if ($page <= 0) $page = 1;

$chunk = array_chunk($_POST['ProjectIDs'], $BXAF_CONFIG['SETTINGS']['Gene_Plot_Project_Count_Per_Page']);

$currentProjectIDs = $chunk[$page-1];
if (array_size($currentProjectIDs) <= 0) exit();



foreach($currentProjectIDs as $tempKey => $currentProjectID){
	
	$currentProject 		= getProjectByID($currentProjectID);
	
	echo "<div class='card' id='Project_Result_Section_{$currentProjectID}' style='margin-bottom:15px;'>";
		echo "<div class='card-body'>";
		
			if ($currentProject['Accession'] == ''){
				$title = $currentProject['Name'];
			} else {
				$title = "[{$currentProject['Accession']}] {$currentProject['Name']}";	
			}
    		echo "<h5 class='card-title'>{$title}&nbsp;<a href='app_project_review.php?ID={$currentProjectID}' target='_blank' class='small' title='Review Project'>". printFontAwesomeIcon('fas fa-external-link-alt') . "</a></h5>";
			echo "<h6 class='card-subtitle mb-2 text-muted'>" . displayLongText($currentProject['Description'], 100, 0) . "</h6>";
			
			
			echo "<div id='busySection_{$currentProjectID}'>" . printFontAwesomeIcon('fas fa-spinner fa-spin'). " Loading...</div>";
			echo "<div id='plotSection_{$currentProjectID}'></div>";
			
		echo "</div>";
	echo "</div>";
	
	
	
}



?>
<script type="text/javascript">
$(document).ready(function(){
	
	<?php foreach($currentProjectIDs as $tempKey => $currentProjectID){ ?>
	$.ajax({
		type: 'GET',
		url: 'app_gene_plot_exe3.php?default=1&key=<?php echo $key; ?>&ID=<?php echo $currentProjectID; ?>',
		success: function(responseText){
			$('#plotSection_<?php echo $currentProjectID; ?>').html(responseText);
			$('#busySection_<?php echo $currentProjectID; ?>').hide();
		}
	});	
	<?php } ?>

		
});



</script>


