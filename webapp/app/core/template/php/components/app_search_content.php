<?php


/*
$appObj = array();
$appObj['Table'] 			= $APP_CONFIG['TABLES']['PROJECT'];

$currentIndex = 1;
$appObj['Search'][$currentIndex]['Field'] 		= 'TSTID';
$appObj['Search'][$currentIndex]['Operator'] 	= 1;
$appObj['Search'][$currentIndex]['Value'] 		= '';
$appObj['Search'][$currentIndex]['Logic'] 		= '';
$appObj['Default_Field'] 						= 'TSTID';
$appObj['Record_IDs']  = array(1, 3, 5, 7, 9);

$appObj['Page']['EXE']		= 'app_project_search_exe.php';
$appObj['Page']['AJAX']		= 'app_project_search_ajax.php';
$appObj['Page']['Reset']	= 'app_project_search.php';
*/

if (!isset($appObj)){
	return;
}




if ($_GET['searchKey'] != ''){
	$temp = getRedisCache($_GET['searchKey']);
	
	
	if (array_size($temp) > 0){
		$appObj['Search'] = $temp;	
		$submitAfterFieldChange = 1;
	}
} elseif ($_GET['recordIDs'] != ''){
	$temp = getRedisCache($_GET['recordIDs']);
	
	
	if (array_size($temp) > 0){
		$appObj['Record_IDs'] = $temp;
		$appObj['Record_IDs_Key'] = $_GET['recordIDs'];
		unset($appObj['Search']);
		$hideSubmitButtons = 1;
	}
	
}



if (true){
	$dataArray = array();
	$dataArray['Search'] = $appObj['Search'];
	$submit = intval($appObj['Submit']) || $_GET['Browse'] || $_GET['ShowAll'] || ($appObj['Record_IDs_Key'] != '');	
}



if ($_GET['ShowAll']){
	$dataArray['Search'][1]['Field'] = '';
	$dataArray['Search'][1]['Value'] = '';
	$submit = 1;
}

if ($appObj['Enable_Group_Header']){
	$allColumnsWithGroupHeaders = getSearchableColumnsWithGroupHeaders($appObj['Table']);
}

$allColumns = getSearchableColumns($appObj['Table']);




echo "<div class='row'>";
	echo "<div class='col-12'>";
		unset($actions);
		echo "<p>" . implode(" &nbsp; &nbsp; ", $actions) . "</p>";
	echo "</div>";
echo "</div>";

	
$rowCount = array_size($dataArray['Search']);


$class = '';

if ($_GET['Browse']){
	$class = 'startHidden';	
}


echo "<form id='form_application' action='javascript:void(0);' method='post' role='form' class='form-horizontal {$class}' enctype='multipart/form-data' autocomplete='off'>";

	if (true){
		echo "<div class='row'>";
			echo "<div class='col-12'>";
				foreach($dataArray['Search'] as $currentSearchCount => $currentSearchInfo){

					
					echo "<div class='form-group row' id='searchRow_{$currentSearchCount}'>";
						
						if ($currentSearchCount == 1){
							echo "<label class='col-1 col-form-label'>Search:</label>";
						} else {
							
							$name 				= "Logic_{$currentSearchCount}";
							$value 				= $currentSearchInfo['Logic'];
							$placeHolderText 	= '';
							unset($checkAnd, $checkOr);
							
							if ($value == 'or'){
								$checkOr = 'checked';
							} else {
								$checkAnd = 'checked';
							}
							
							echo "<div class='col-1'>";
								echo "<div class='form-check form-check-inline'>";
									
									echo "<input type='radio' class='form-check-input' name='Logic_{$currentSearchCount}' value='and' {$checkAnd}/>";
									echo "<label class='form-check-label'> And</label>";
	
									echo "&nbsp; &nbsp;";
									
									echo "<input type='radio' class='form-check-input' name='Logic_{$currentSearchCount}' value='or' {$checkOr}/>";
									echo "<label class='form-check-label'> Or</label>";
								echo "</div>";
							echo "</div>";
						}
						
						
						if (true){
							$name 				= "Field_{$currentSearchCount}";
							$value 				= $currentSearchInfo['Field'];
							$placeHolderText 	= '';
							
							echo "<div class='col-2'>";
								echo "<div class='form-group'>";
									echo "<input type='hidden' id='Field_{$currentSearchCount}_init' value='{$currentSearchInfo['Field']}'/>";
									echo "<input type='hidden' id='Operator_{$currentSearchCount}_init' value='{$currentSearchInfo['Operator']}'/>";
									echo "<input type='hidden' id='Value_{$currentSearchCount}_init' value='" . htmlspecialchars($currentSearchInfo['Value'], ENT_QUOTES) . "'/>";
									echo "<select class='form-control field_group' name='{$name}' id='{$name}' rowcount='{$currentSearchCount}'>";
										echo "<option value=''>Please select a field to search:</option>";
										
										
										if ($appObj['Enable_Group_Header']){
											
											foreach($allColumnsWithGroupHeaders as $currentGroupHeader => $currentColumns){
												echo "<optgroup label='{$currentGroupHeader}'>";
												
												foreach($currentColumns as $currentSQL => $currentTitle){
													
													unset($selected);
													
													if ($currentSQL == $value){
														$selected = 'selected';	
													}
												
													echo "<option value='{$currentSQL}' {$selected}>{$currentTitle}</option>";
													
												}
												
												echo "</optgroup>";
												
											}
										} else {
											foreach($allColumns as $currentSQL => $currentTitle){
												
												unset($selected);
												
												if ($currentSQL == $value){
													$selected = 'selected';	
												}
											
												echo "<option value='{$currentSQL}' {$selected}>{$currentTitle}</option>";
												
											}
										}
									echo "</select>";
								echo "</div>";
							echo "</div>";
						}
						
						
						if (true){
							$name 				= "Operator_{$currentSearchCount}";
							$value 				= $currentSearchInfo['Operator'];
							$placeHolderText 	= '';
							
							echo "<div class='col-1'>";
								echo "<div class='form-group'>";
									echo "<select class='form-control operator_group' name='{$name}' id='{$name}'>";
										foreach($APP_CONFIG['APP']['Search']['Operator'] as $tempKey1 => $tempValue1){
											
											echo "<optgroup label='{$tempKey1}'>";
											
											foreach($tempValue1 as $tempKey2 => $tempValue2){
												unset($selected);
												
												if ($tempKey2 == $value){
													$selected = 'selected';	
												}
												echo "<option value='{$tempKey2}' {$selected}>{$tempValue2}</option>";
											}
											
											echo "</optgroup>";
											
											
										}
									echo "</select>";
								echo "</div>";
							echo "</div>";
						}
						
						
						
						if (true){
							$name 				= "Value_{$currentSearchCount}";
							$value 				= htmlspecialchars($currentSearchInfo['Value'], ENT_QUOTES);
							$placeHolderText 	= '';
							
							echo "<div class='col-3'>";
								echo "<div class='form-group' id='Value_Section_{$currentSearchCount}'>";
									echo "<input class='form-control' name='{$name}' {$placeHolderText} value='{$value}'/>";
								echo "</div>";
							echo "</div>";
						}
						
						
						if ($currentSearchCount == 1){
							echo "<label class='col-2 col-form-label'>";
								echo "<a href='javascript:void(0);' class='addSearchRowTrigger'>" . printFontAwesomeIcon('fas fa-plus') . " Add Search Condition</a>";
							echo "</label>";
						} else {
							echo "<label class='col-1 col-form-label'>";
								echo "<a href='javascript:void(0);' class='deleteSearchRowTrigger' rowid='searchRow_{$currentSearchCount}'>" . printFontAwesomeIcon('far fa-trash-alt') . " Remove</a>";
							echo "</label>";	
						}
						
			
					echo "</div>";
		
				}
				echo "<div id='searchAppendArea'></div>";
			echo "</div>";
		echo "</div>";
	}


	if ($appObj['Show_Add_Search_Condition']){
		echo "<div class='form-group row'>";
			echo "<div class='col-6'>";		
				echo "<a href='javascript:void(0);' class='addSearchRowTrigger'>" . printFontAwesomeIcon('fas fa-plus') . " Add Search Condition</a>";
			echo "</div>";
		echo "</div>";		
	}

	if (true){
		echo "<div class='form-group row'>";
			echo "<div class='col-6'>";
				echo "<input type='hidden' name='rowCount' id='rowCount' value='{$rowCount}'/>";
				echo "<input type='hidden' name='sessionID' value='{$sessionID}'/>";
				
				echo "<input type='hidden' id='Default_Field' value='{$appObj['Default_Field']}'/>";
				
				
				if ($appObj['Record_IDs_Key'] != ''){
					echo "<input type='hidden' name='Record_IDs_Key' value='{$appObj['Record_IDs_Key']}'/>";
				}
			
				if ($hideSubmitButtons){	
					echo "<div class='startHidden'>";
				}
				
					echo "<button id='submitButton' class='btn btn-primary' type='submit'>" . printFontAwesomeIcon('fas fa-search') . " Search</button>";
					
					if ($appObj['Page']['Reset'] != ''){
						echo "&nbsp; &nbsp;";
						echo "<a href='{$appObj['Page']['Reset']}?ShowAll=1'>" . printFontAwesomeIcon('far fa-clone') . " Browse All Records</a>";
					}
					
					if ($appObj['Page']['Reset'] != ''){
						echo "&nbsp; &nbsp;";
						echo "<a href='{$appObj['Page']['Reset']}'>" . printFontAwesomeIcon('fas fa-sync-alt') . " Reset</a>";
					}
					
				if ($hideSubmitButtons){	
					echo "</div>";
				}
			echo "</div>";
		echo "</div>";
	}
	

echo "</form>";				


echo "<div id='busySection' class='startHidden'>" . printFontAwesomeIcon('fas fa-spinner fa-spin'). " Loading...</div>";
echo "<div id='feedbackSection'></div>";
	

?>

<script type="text/javascript">


$(document).ready(function(){
	
	
	$('.addSearchRowTrigger').click(function(){
		var text = '';
		var rowCount = $('#rowCount').val();
		rowCount++;
		$('#rowCount').val(rowCount);
		
		
		text += "<div class='form-group row' id='searchRow_" + rowCount + "'>";
		
		if (true){
			text += "<div class='col-1'>";
				text += "<div class='form-check form-check-inline'>";
					text += "<input type='radio' class='form-check-input' name='Logic_" + rowCount + "' value='and' checked/>";
					text += "<label class='form-check-label'> And</label>";
					
					text += "&nbsp; &nbsp;";
					
					text += "<input type='radio' class='form-check-input' name='Logic_" + rowCount + "' value='or'/>";
					text += "<label class='form-check-label'> Or</label>";
				text += "</div>";
			text += "</div>";
		}
		
		<?php if ($appObj['Enable_Group_Header']){ ?>
		if (true){
			
			text += "<div class='col-2'>";
				text += "<div class='form-group'>";
					text += "<select class='form-control field_group' name='Field_" + rowCount + "' id='Field_" + rowCount + "' rowcount='" + rowCount + "'>";
						<?php foreach($allColumnsWithGroupHeaders as $currentGroupHeader => $currentColumns){ ?>
							text += "<?php echo "<optgroup label='{$currentGroupHeader}'>"; ?>";
							<?php
								foreach($currentColumns as $currentSQL => $currentTitle){
									unset($selected);
									
									if ($currentSQL == $APP_CONFIG['APP']['List_Category'][$category]['Column_Default']){
										$selected = 'selected';
									}
									
							?>
								text += "<?php echo "<option value='{$currentSQL}' {$selected}>{$currentTitle}</option>"; ?>";
							<?php } ?>
							text += "</optgroup>";
						<?php } ?>
					text += "</select>";
				text += "</div>";
			text += "</div>";	
		}
						
		<?php } else { ?>
		if (true){
			
			text += "<div class='col-2'>";
				text += "<div class='form-group'>";
					text += "<select class='form-control field_group' name='Field_" + rowCount + "' id='Field_" + rowCount + "' rowcount='" + rowCount + "'>";
						<?php
							foreach($allColumns as $currentSQL => $currentTitle){
 	 							unset($selected);
								
								if ($currentSQL == $APP_CONFIG['APP']['List_Category'][$category]['Column_Default']){
									$selected = 'selected';
								}
								
						?>
							
							text += "<?php echo "<option value='{$currentSQL}' {$selected}>{$currentTitle}</option>"; ?>";
							
						<?php } ?>
					text += "</select>";
				text += "</div>";
			text += "</div>";	
		}
		<?php } ?>
		
		
		
		if (true){
					
			text += "<div class='col-1'>";
				text += "<div class='form-group'>";
					text += "<select class='form-control operator_group' name='Operator_" + rowCount + "' id='Operator_" + rowCount + "'>";
					<?php
						foreach($APP_CONFIG['APP']['Search']['Operator'] as $tempKey1 => $tempValue1){
					?>
							text += "<optgroup label='<?php echo $tempKey1; ?>'>";
								
							<?php		
								foreach($tempValue1 as $tempKey2 => $tempValue2){
									unset($selected);
									
									if ($tempKey2 == 1){
										$selected = 'selected';	
									}
							?>
									text += "<?php echo "<option value='{$tempKey2}' {$selected}>{$tempValue2}</option>"; ?>";
							<?php
								}
							?>
							
							text += "</optgroup>";
					<?php
						}
					?>
					text += "</select>";
				text += "</div>";
			text += "</div>";
		}
		
		
		if (true){
		
			text += "<div class='col-3'>";
				text += "<div class='form-group' id='Value_Section_" + rowCount + "'>";
					text += "<input class='form-control' name='Value_" + rowCount + "'/>";
				text += "</div>";
			text += "</div>";	
			
		}
		
		
		if (true){
		
			text += "<label class='col-1 col-form-label'>";
				text += "<a href='javascript:void(0);' class='deleteSearchRowTrigger' rowid='searchRow_" + rowCount + "'><?php echo printFontAwesomeIcon('far fa-trash-alt'); ?> Remove</a>";
			text += "</label>";
			
		}
		
		text += "</div>";
		
		
		
		$('#searchAppendArea').append(text);
		
		$('#Field_' + rowCount).change();
		
	});
	
	$(document).on('change', '.field_group', function(){
		var currentColumn = $(this).val();
		var rowCount = $(this).attr('rowcount');
		var operatorHTML = '';
		

		if (currentColumn == '') return false;
		
		<?php
			foreach($allColumns as $currentSQL => $currentTitle){
				$type = $APP_CONFIG['DICTIONARY'][($appObj['Table'])][$currentSQL]['Type'];

				if (strtolower($type) == 'date'){
					$operators = $APP_CONFIG['APP']['Search']['Operator_By_Type']['Date'];
				} elseif (strpos($type, 'array_') === 0){
					$operators = $APP_CONFIG['APP']['Search']['Operator_By_Type']['Category'];
				} elseif ($type == 'tag_key_value'){
					$operators = $APP_CONFIG['APP']['Search']['Operator_By_Type']['Category'];
				} elseif ($type == 'number'){
					$operators = $APP_CONFIG['APP']['Search']['Operator_By_Type']['Number'];
				} else {
					$operators = $APP_CONFIG['APP']['Search']['Operator_By_Type']['String'];
				}
				
				echo "if (currentColumn == '{$currentSQL}'){\n";
				
					foreach($operators as $tempKey => $tempValue){
						echo "operatorHTML += \"<option value='{$tempKey}'>{$tempValue}</option>\";\n";
					}
				
				echo "}\n";
				
			}
		?>
		
		$('#Operator_' + rowCount).html(operatorHTML);
		

		
		$.ajax({
				type: 'POST',
				url: '<?php echo "{$appObj['Page']['AJAX']}?key={$appObj['Search_Cache_Key']}"; ?>&row=' + rowCount + '&column=' + currentColumn + '&valueID=Value_' + rowCount,
				//data: data,
				success: function(responseText){
					$('#Value_Section_' + rowCount).html(responseText);
					
					if (typeof $('#Value_' + rowCount + '_init').val() !== 'undefined'){
						$('#Value_' + rowCount).val( $('#Value_' + rowCount + '_init').val() );
						$('#Value_' + rowCount + '_init').remove();
					}
					
					if (typeof $('#Operator_' + rowCount + '_init').val() !== 'undefined'){
						$('#Operator_' + rowCount).val( $('#Operator_' + rowCount + '_init').val() );
						
						
						
						<?php
							if ($submitAfterFieldChange) { ?>
								if (<?php echo array_size($dataArray['Search']); ?> == rowCount){
									$('#submitButton').click();	
								}
						<?php } ?>
						
					}
					

					
				}
			});
		
	
	});
	
	$(document).on('click', '.deleteSearchRowTrigger', function(){
		var rowID = $(this).attr('rowid');
		$('#' + rowID).empty();
		$('#' + rowID).hide();
	});
	
	
	
	$("#submitButton").click(function(){
		beforeSubmit();
				
		$.ajax({
			type: 'POST',
			url: '<?php echo "{$appObj['Page']['EXE']}?Browse={$_GET['Browse']}"; ?>',
			data: $('#form_application').serialize(), 
			success: function(response) {
				showResponse(response);
				
				<?php if (($_GET['ShowAll']) && ($appObj['Default_Field'] != '')){ ?>
				var defaultField = $('#Default_Field').val();
				
				if (defaultField != ''){
					$('#Field_1').val(defaultField);
					$('#Field_1').change();
					$('#Default_Field').val('');
				}
				<?php } ?>
				
				
			},
			error: function() {
				
			}
		 });
	});
	
	$('#Field_1').change();	
	<?php 
	foreach($dataArray['Search'] as $tempKey => $tempValue){
		if ($tempKey > 1){
	?>
		$('#Field_<?php echo $tempKey; ?>').change();
	<?php 
		} 
	}
		?>


	<?php if ($submit){ ?>
	$('#submitButton').click();
	<?php } ?>
	
});


function beforeSubmit() {
	$('#feedbackSection').empty();
	$('#busySection').show();

	return true;
}


function showResponse(responseText, statusText) {
	responseText = $.trim(responseText);

	$('#busySection').hide();
	$('#feedbackSection').html(responseText);
	$('#feedbackSection').show();
	
	/*
	$('html,body').animate({
		scrollTop: $('#feedbackSection').offset().top
	});
	*/
	
	return true;

}
</script>