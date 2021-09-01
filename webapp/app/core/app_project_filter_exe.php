<?php
include_once('config_init.php');

$getSearchSQLResults = getFilterSQLResults($APP_CONFIG['TABLES']['PROJECT'], $_POST);


if ($getSearchSQLResults['Record_Count'] <= 0){
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-warning') . " There are no records match your search conditions. Please revise the search filter and try again.</p>";
			echo getAlerts($message, 'warning');
		echo "</div>";
	echo "</div>";
	exit();
}



include('app_project_search_exe.php');

$getFilterInfo = get_filter_column_info($APP_CONFIG['TABLES']['PROJECT'], $BXAF_CONFIG['SETTINGS']['Project_Filter_Candidates'], $getSearchSQLResults['SQL_CONDITIONS']);




?>
<script type="text/javascript">

$(document).ready(function(){
	
	<?php foreach($BXAF_CONFIG['SETTINGS']['Project_Filter_Candidates'] as $tempKey => $currentColumn){ ?>
		$('.<?php echo $currentColumn; ?>_Count').html('0 | ');
	<?php } ?>
	
	<?php 
		foreach($getFilterInfo as $currentColumn => $values){
			
			$getFilterInfoTotal[$currentColumn] = array_sum($values);
			
			foreach($values as $currentCategory => $currentCount){
				$countID = "{$currentColumn}_" . md5(strtolower($currentCategory));
				$currentCountDisplay = number_format($currentCount);
	?>
		$('#<?php echo $countID; ?>').html('<?php echo $currentCountDisplay; ?> | ');
	<?php }} ?>
	
	
	<?php
		foreach($getFilterInfoTotal as $currentColumn => $currentCount){
			$currentCountDisplay = number_format($currentCount);
	 ?>
		$('#<?php echo $currentColumn; ?>_Total').html('<?php echo $currentCountDisplay; ?> | ');
	<?php } ?>
	
});


</script>
