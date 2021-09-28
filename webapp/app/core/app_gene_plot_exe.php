<?php

include_once('config_init.php');

echo "<hr/>";

$_POST['Genes'] = id_sanitizer($_POST['Genes'], 1, 0, 0, 1);
$_POST['Genes'] = array_iunique($_POST['Genes'], 1);
$_POST['Genes'] = array_map('strtoupper', $_POST['Genes']);





if ($_POST['preselected'] != ''){
	$_POST['preselected'] = getRedisCache($_POST['preselected']);
}


$_POST['n'] = abs(floatval($_POST['n']));
$_POST['g'] = abs(floatval($_POST['g']));

$uniqueID = getUniqueID();



if (array_size($_POST['Genes']) <= 0){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. Please enter at least a gene.</p>";
	echo getAlerts($message, 'danger');
	echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';
			echo '$("#Genes").addClass("is-invalid");';
		echo '});';
	echo '</script>';
	exit();
}



if (true){
	$allProjects = getProjectsForGenePlot($_POST);
	$projectCount = array_size($allProjects);
	$projectCountDisplay = number_format($projectCount);
	$_POST['ProjectIDs'] = array_keys($allProjects);
	
	
	
	if ($projectCount <= 0){
		$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. There are no projects available. Please modify the input and try again.</p>";
		echo getAlerts($message, 'danger');
		exit();
	}
	
	$totalPage = ceil($projectCount / $BXAF_CONFIG['SETTINGS']['Gene_Plot_Project_Count_Per_Page']);
	
	$cacheKey = 'Temp_' . md5(json_encode($_POST));
	putRedisCache(array($cacheKey => $_POST), 1);

}

echo "<script type='text/javascript' src='js/plotly.violin.js'></script>";
echo "<script type='text/javascript' src='js/plotly.scatter.js'></script>";

if ($projectCount == 1){
	echo "<p>There is 1 project available.</p>";
} else {
	echo "<p>There are <strong>{$projectCountDisplay}</strong> projects available.</p>";
}

if (true){
	echo "<nav aria-label='Page'>";
		echo "<ul class='pagination justify-content-center'>";
			echo "<li class='page-item'>";
				echo "<a class='page-link firstPageTrigger_{$uniqueID}' href='javascript:void(0);' title='First Page'>" . printFontAwesomeIcon('fas fa-angle-double-left') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link previousPageTrigger_{$uniqueID}' href='javascript:void(0);' title='Previous Page'>" . printFontAwesomeIcon('fas fa-angle-left') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Page <span class='pageNo'>1</span> of {$totalPage}</a></li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link nextPageTrigger_{$uniqueID}' href='javascript:void(0);' title='Next Page'>" . printFontAwesomeIcon('fas fa-angle-right') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link lastPageTrigger_{$uniqueID}' href='javascript:void(0);'  title='Last Page'>" . printFontAwesomeIcon('fas fa-angle-double-right') . "</a>";
			echo "</li>";
	
		echo "</ul>";
	echo "</nav>";
}


echo "<div id='resultSection'></div>";


if ($_POST['Hide_Empty']){
	echo "<p class='pagination justify-content-center'>
			Projects without results are hidden. Please move to the next page to continue.
		</p>";	
}


if (true){
	echo "<nav aria-label='Page'>";
		echo "<ul class='pagination justify-content-center'>";
			echo "<li class='page-item'>";
				echo "<a class='page-link firstPageTrigger_{$uniqueID}' href='javascript:void(0);' title='First Page'>" . printFontAwesomeIcon('fas fa-angle-double-left') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link previousPageTrigger_{$uniqueID}' href='javascript:void(0);' title='Previous Page'>" . printFontAwesomeIcon('fas fa-angle-left') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item disabled'><a class='page-link' href='#'>Page <span class='pageNo'>1</span> of {$totalPage}</a></li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link nextPageTrigger_{$uniqueID}' href='javascript:void(0);' title='Next Page'>" . printFontAwesomeIcon('fas fa-angle-right') . "</a>";
			echo "</li>";
			
			echo "<li class='page-item'>";
				echo "<a class='page-link lastPageTrigger_{$uniqueID}' href='javascript:void(0);'  title='Last Page'>" . printFontAwesomeIcon('fas fa-angle-double-right') . "</a>";
			echo "</li>";
	
		echo "</ul>";
	echo "</nav>";
}



?>
<script type="text/javascript">
$(document).ready(function(){
	
	loadPlot_<?php echo $uniqueID; ?>(1);
	
	$(document).on('click', '<?php echo ".firstPageTrigger_{$uniqueID}"; ?>', function(){
		loadPlot_<?php echo $uniqueID; ?>(1);
	});
	
	$(document).on('click', '<?php echo ".previousPageTrigger_{$uniqueID}"; ?>', function(){
		currentPage = $('#currentPage').val();
		currentPage = parseInt(currentPage);
		
		if (currentPage > 1){
			loadPlot_<?php echo $uniqueID; ?>(currentPage-1);
		}
	});
	
	$(document).on('click', '<?php echo ".nextPageTrigger_{$uniqueID}"; ?>', function(){
		currentPage = $('#currentPage').val();
		currentPage = parseInt(currentPage);
		
		
		if (currentPage < <?php echo $totalPage; ?>){
			loadPlot_<?php echo $uniqueID; ?>(currentPage+1);
		}
	});
	
	$(document).on('click', '<?php echo ".lastPageTrigger_{$uniqueID}"; ?>', function(){
		loadPlot_<?php echo $uniqueID; ?>(<?php echo $totalPage; ?>);
	});
	
	$('#key').val('<?php echo $cacheKey; ?>');
	
});


function loadPlot_<?php echo $uniqueID; ?>(page){
	
	$.ajax({
		type: 'GET',
		url: 'app_gene_plot_exe2.php?key=<?php echo $cacheKey; ?>&page=' + page,
		success: function(responseText){
			$('#resultSection').html(responseText);
			$('#currentPage').val(page);
			$('.pageNo').html(page);
		}
	});	
}

</script>


