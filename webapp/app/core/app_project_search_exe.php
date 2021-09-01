<?php
include_once('config_init.php');

if (!$_GET['Browse']){
	echo "<hr/>";
}

$currentTable = $APP_CONFIG['TABLES']['PROJECT'];

if (!isset($getSearchSQLResults)){
	$getSearchSQLResults = getSearchSQLResults($currentTable, $_POST);
}


if (!$getSearchSQLResults['All'] && $getSearchSQLResults['Record_Count'] <= 0){
	echo "There are no records available. Please modify your search conditions and try again.";
	exit();
}


$sessionID = getUniqueID();

$appObj = array();
$appObj['Table'] 							= $currentTable;
$appObj['Record_IDs']						= getSQL_Data($getSearchSQLResults['SQL_ID'], 'GetCol', 1);
$appObj['Cache_Key']						= 'IDs_' . id_sanitizer($appObj['Record_IDs'], 0, 1, 0, 3);
putRedisCache(array($appObj['Cache_Key'] => $appObj['Record_IDs']), 1);

$appObj['Records_Count']					= $getSearchSQLResults['Record_Count'];
$appObj['Table_Headers']					= getSearchTablePreferences($currentTable);
$appObj['Table_Headers_Display_Settings']	= getSearchColumnSettingsForDataTable($currentTable);
$appObj['Column_Settings_Modal']			= true;
$appObj['Record_Per_Page']					= $BXAF_CONFIG['Table_Record_Per_Page'];
$appObj['Search_Keyword']					= '';
$appObj['Settings_Handler']					= 'app_common_search_settings.php';
$appObj['ajax']								= 'app_project_search_datatable_ajax.php';

$IDAry = array('id' => $appObj['Record_IDs']);
$IDKey = 'Temp_' . md5(json_encode($IDAry));
putRedisCache(array($IDKey => $IDAry), 604800);

if (true){
	$appObj['Buttons'] = array();
	$appObj['Button_Error'] = array();
	
	
	if (true){
		$appObj['Buttons'][] = "<a href='app_project_search_download.php?key={$appObj['Cache_Key']}&all_columns=0' class='btn btn-dark' target='_blank' data-toggle='tooltip' data-placement='top' title='{$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Export Selected Tooltip']}'>" . printFontAwesomeIcon('fas fa-file-download') . "&nbsp;Export Selected</a>";
	}
	
	
	if (true){
		$appObj['Buttons'][] = "<a href='app_project_search_download.php?key={$appObj['Cache_Key']}&all_columns=1' class='btn btn-secondary' target='_blank' data-toggle='tooltip' data-placement='top' title='{$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Export All Tooltip']}'>" . printFontAwesomeIcon('fas fa-file-download') . "&nbsp;Export All</a>";
	}
	
	if (isAdminUser()){
		$appObj['Buttons'][] = "<a href='javascript:void(0);' class='deleteRecordTrigger btn btn-danger'>" . printFontAwesomeIcon('far fa-trash-alt') . "&nbsp;{$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Delete_Record']}</a>";
		$errorMessages[] = "<span class='startHidden' id='deleteSelectedTriggerMessage'>" . printFontAwesomeIcon('fa-exclamation-circle text-danger') . "&nbsp;Please select at least a record first.</a></span>";
	}
	
	if (true){
		$appObj['Buttons'][] = "<a href='app_gene_plot.php?preselected={$appObj['Cache_Key']}' class='btn btn-warning' data-toggle='tooltip' data-placement='top' title='{$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Gene_Plot_Tooltip']}'>" . printFontAwesomeIcon('fas fa-dna') . "&nbsp;{$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Gene_Plot']}</a>";
	}
	
	
	
	
}




include('template/php/components/app_search_result_serverside.php');



?>
<script type="text/javascript">
$(document).ready(function(){
	
	$('[data-toggle="tooltip"]').tooltip();
	
	<?php if (isAdminUser()){ ?>
	$(document).on('click', '.deleteRecordTrigger', function(){
		
		$('#deleteFromTableModal').modal('hide');
		
		var data = new Object();
		var count = 0;
		data['ID'] = [];

		$('#deleteSelectedTriggerMessage').hide();
		
		$('.recordCheckbox:checked').each(function(){
			count++;
			data['ID'].push($(this).val());
		});
		
		if (count > 0){
			
			$.ajax({
				type: 'POST',
				url: 'app_project_delete.php?source=POST&return=0',
				data: data,
				success: function(responseText){
					window.location = 'index.php';
				}
			});

		} else {
			$('#deleteSelectedTriggerMessage').show();
		}
	});
	<?php } ?>
	
	
});
</script>