<?php
/*
$appObj = array();
$appObj['Table'] 			= $APP_CONFIG['TABLES']['PROJECT'];
$appObj['Columns']			= $BXAF_CONFIG['SETTINGS']['Project_Filter_Candidates'];
$appObj['Search_URL']		= 'app_project_search.php';
$appObj['EXE']				= $PAGE['EXE'];
*/


$recordTotal				= getTableCount($appObj['Table']);
$getFilterInfo				= get_filter_column_info($appObj['Table'], $appObj['Columns']);

echo "<div class='row'>";
	echo "<div class='col-lg-2'>";
		echo "<form id='form_application' action='javascript:void(0);' method='post' role='form' enctype='multipart/form-data' autocomplete='off'>";
		
			if ($appObj['Search_URL'] != ''){
				echo "<h6><a href='{$appObj['Search_URL']}'>" . printFontAwesomeIcon('fas fa-search') . "&nbsp;Advanced Search</a></h6>";
			}
			
			foreach($getFilterInfo as $currentColumn => $tempValue1){

				$currentTable	= $appObj['Table'];
				$label 			= getHeaderDisplayName($currentTable, $currentColumn, 0);
				$sql_name		= $currentColumn;
				$sql_id			= $sql_name;
				$values			= $getFilterInfo[$currentColumn];
				$openStatus		= $tempValue1['OpenStatus'];
				
				
				
				if (isset($urlValue[$sql_name])){
					$sql_value = $urlValue[$sql_name];
				} elseif ($_GET[$sql_name] != ''){
					$sql_value	= array($_GET[$sql_name]);
				}
				
				if (array_size($sql_value) > 0){
					$openStatus = 1;	
				}
				
				
				echo "<div class='card'>";
					echo "<div class='card-block'>";
						
						if (true){
							echo "<div class='card-title'>";
								if ($openStatus){
									$classOpened = '';	
									$classClosed = 'startHidden';
									$opened		 = 1;
								} else {
									$classOpened = 'startHidden';	
									$classClosed = '';
									$opened		 = 0;
								}
							
								echo "<a href='javascript:void(0);' opened='{$opened}' class='h6 filterTreeTrigger' childrenname='{$sql_name}'>";
								echo "<span id='{$sql_name}_menu_opened' class='{$classOpened}'>" . printFontAwesomeIcon('fa-caret-down') . "</span>";
								echo "<span id='{$sql_name}_menu_closed' class='{$classClosed}'>" . printFontAwesomeIcon('fa-caret-right') . "</span>";
								echo "{$label}</a>";
							echo "</div>";
						}
						
						if ($openStatus){
							$class = '';	
						} else {
							$class = 'startHidden';	
						}
						
						if (true){
							echo "<div class='card-text card-text-overflow {$class}' id='{$sql_name}_section'>";
								if (true){
									echo "<table class='table table-sm table-striped'>";
										echo "<thead>";
											echo "<tr>";
												echo "<th style='width:20px;'><input id='{$currentColumn}_SelectAll' class='selectAllTrigger' type='checkbox' value='1' children_class='{$currentColumn}'/></th>";
												echo "<th style='width:90%;'><span class='small'><strong>Select All</strong></span></th>";
												
												
												$currentCount		= $recordTotal;
												$currentCountDisplay = number_format($currentCount);
												
												$countID = "{$sql_name}_Total";
												
												echo "<th><span class='badge badge-secondary badge-pill'><span id='{$countID}' class='{$sql_name}_Count'></span>{$currentCountDisplay}</span></th>";
		
											echo "</tr>";
										echo "</thead>";
										
										
										echo "<tbody>";
											foreach($values as $currentCategory => $currentCount){
											
											unset($checked);
											if (in_array($currentCategory, $sql_value)){
												$checked = 'checked';	
											}
											
											$currentCountDisplay = number_format($currentCount);
											
											$countID = "{$currentColumn}_" . md5(strtolower($currentCategory));
											
											$currentCategoryDisplay = auto_translate($appObj['Table'], $currentColumn, $currentCategory);
											
											if ($currentCategoryDisplay == ''){
												$currentCategoryDisplay = 'Blank / No Value';	
											}
											
											
											$currentCategory = htmlentities($currentCategory, ENT_QUOTES);
		
		
											
											echo "<tr>";
												echo "<td style='width:20px;'><input parentcheckbox='{$currentColumn}_SelectAll' class='user_checkbox {$sql_name}' type='checkbox' value='{$currentCategory}' name='{$sql_name}[]' {$checked}/></td>";
												echo "<td style='width:90%;'><span title='{$currentCategory}' class='small'>{$currentCategoryDisplay}</span></td>";
												echo "<td><span class='badge badge-secondary badge-pill'><span id='{$countID}' class='{$sql_name}_Count'></span>{$currentCountDisplay}</span></td>";
											echo "</tr>";
										}
										echo "</tbody>";
										
									echo "</table>";
								}
							echo "</div>";
						}
						
					echo "</div>";
				echo "</div>";
			}

		
		echo "</form>";
	echo "</div>";
	
	
	echo "<div class='col-lg-10'>";
		echo "<div id='feedbackSection'></div>";
		echo "<h4 id='busySection' class='startHidden' style='margin-top:50px;'>" . printFontAwesomeIcon('fa-spinner fa-spin'). " Loading...</h4>";
	echo "</div>";
echo "</div>";

?>


<style>
.card{
	margin-bottom:10px;	
}

.card-block{
	padding:5px;	
}

.card-text{
	max-height:300px;	
}

.card-text-overflow{
	overflow-y:auto;
}

.table{
	margin-bottom:2px;	
}

</style>

<script type="text/javascript">

$(document).ready(function(){
	
	$('#form_application').on('change', '.selectAllTrigger', function(){
		var childrenClass 	= $(this).attr('children_class');
		var checkStatus		= $(this).prop('checked');
		
		$('.' + childrenClass).prop('checked', checkStatus);
		
		submitData();
	});
	
	
	$('#form_application').on('click', '.filterTreeTrigger', function(){
		
		var childrenName		= $(this).attr('childrenname');
		var currentOpenedStatus = parseInt($(this).attr('opened'));

		if (currentOpenedStatus == 1){
			
			$('#' + childrenName + '_section').hide();
			$('#' + childrenName + '_menu_opened').hide();
			$('#' + childrenName + '_menu_closed').show();
			
			$(this).attr('opened', '0');
		} else {
			
			$('#' + childrenName + '_section').show();
			$('#' + childrenName + '_menu_opened').show();
			$('#' + childrenName + '_menu_closed').hide();
			
			$(this).attr('opened', '1');
		}

	});
	
	
	
	$('#form_application').on('change', '.user_checkbox', function(){
		
		var parentCheckbox = $(this).attr('parentcheckbox');
		
		$('#' + parentCheckbox).prop('checked', false);
		
		submitData();
	});
	
	
	
	$('#form_application').on('change', '.inputBox', function(){
		submitData();
	});
	
	
	$('#feedbackSection').on('click', '#searchResultTrigger', function(){
		$('#searchSummary').toggle();
	});
	

	submitData();
	
});


function submitData(){
	var data = new Object();
	var hasSelected = false;
	
	
	data = $('#form_application').serializeArray();
	
	
	$('#feedbackSection').empty();			
	$('#busySection').show();

	$.ajax({
		type: 'POST',
		url: '<?php echo $appObj['EXE']; ?>',
		data: data,
		success: function(responseText){
			$('#busySection').hide();
			$('#feedbackSection').empty();
			$('#feedbackSection').html(responseText);
		}
	});	

	return true;
}

</script>