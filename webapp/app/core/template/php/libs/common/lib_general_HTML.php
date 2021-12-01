<?php


function printrExpress($array = NULL, $sortKey = 0){
	if ($sortKey){
		natksort($array);
	}
	
	if (php_sapi_name() === 'cli'){
		return print_r($array, true);	
	} else {
		return "<pre>" . print_r($array, true) . "</pre>";
	}
}

function printMsg($string = ''){
	
	if (php_sapi_name() === 'cli'){
		return "{$string}\n";	
	} else {
		return "\n\n\n<p>{$string}</p>\n\n\n";
	}
}


function printTableHTML($tableContent = array(), $compact = 1, $striped = 0, $tableOnly = 1, $divClass = 'col-lg-5 col-sm-12', $bordered = 1, $otherOption = array()){
	
	
	if ((array_size($tableContent['Header']) <= 0) && (array_size($tableContent['Body']) <= 0)) return false;
	

	$result .= "<div>";
		$tableClass = getTableClass($compact, $striped, $bordered);
		
		
		if ($otherOption['Disable_Table_Class']){
			$tableClass = '';	
		}
		
		$tableResponsive = 'table-responsive';
		
		if ($otherOption['Disable_Table_Responsive']){
			$tableResponsive = '';
		}
		
		
		$result .= "<table class='{$tableClass} {$tableResponsive} {$otherOption['Table_Class']}' id='{$otherOption['Table_ID']}' style='{$otherOption['table_style']}'>";
		
		if (isset($tableContent['Header'])){
			$result .= "<thead>";
				$result .= "<tr>";
				foreach($tableContent['Header'] as $tempKey => $tempValue){
					$result .= "<th class='{$otherOption['th_class'][$tempKey]}' style='{$otherOption['th_style'][$tempKey]}'>{$tempValue}</th>";
				}
				$result .= "</tr>";
			$result .= "</thead>";
		}
		
		
		if (isset($tableContent['Header1'])){
			$result .= "<thead>";
			
				if (true){
					$result .= "<tr>";
					foreach($tableContent['Header1'] as $tempKey => $tempValue){
						$rowspan = $otherOption['rowspan'][$tempKey];
						if ($rowspan <= 0) $rowspan = 1;
						
						$colspan = $otherOption['colspan'][$tempKey];
						if ($colspan <= 0) $colspan = 1;
						$result .= "<th class='{$otherOption['th1_class'][$tempKey]}' style='{$otherOption['th1_style'][$tempKey]}' rowspan='{$rowspan}' colspan='{$colspan}'>{$tempValue}</th>";
					}
					$result .= "</tr>";
				}
				
				if (isset($tableContent['Header2'])){
					$result .= "<tr>";
					foreach($tableContent['Header2'] as $tempKey => $tempValue){
						$result .= "<th class='{$otherOption['th2_class'][$tempKey]}' style='{$otherOption['th2_style'][$tempKey]}'>{$tempValue}</th>";
					}
					$result .= "</tr>";
				}
			$result .= "</thead>";
		}
		
		
		
		
		
		if (isset($tableContent['Body'])){
			$result .= "<tbody class='{$otherOption['Body_Class']}' id='{$otherOption['Body_ID']}'>";
				
				foreach($tableContent['Body'] as $row => $rowDetails){
					$result .= "<tr class='{$rowDetails['Class']}'>";
						if (isset($tableContent['Header'])){
							foreach($tableContent['Header'] as $tempKey => $tempValue){
								$result .= "<td style='{$otherOption['td_style'][$tempKey]}'>{$rowDetails['Value'][$tempKey]}</td>";
							}
						} else {
							foreach($rowDetails['Value'] as $tempKey => $tempValue){
								$result .= "<td style='{$otherOption['td_style'][$tempKey]}'>{$tempValue}</td>";
							}
						}
					$result .= "</tr>";
				}

			$result .= "</tbody>";
		}
		
		$result .= "</table>";
		
	$result .= "</div>";
	
	
	if (!$tableOnly){
		$result = "<div class='row'>
					<div class='{$divClass}'>
						{$result}
					</div>
					</div>";
	}
	
	
	return $result;
	
	
}

function getTableClass($compact = 1, $striped = 0, $bordered = 1){

	$classes[] = 'table';
	
	if ($bordered){
		$classes[] = 'table-bordered';
	}
	
	if ($compact){
		$classes[] = 'table-sm';	
	}
	
	if ($striped){
		$classes[] = 'table-striped';
	}
	
	return implode(' ', $classes);
}

function printFontAwesomeIcon($icon = '', $quoteType = 1, $fixWidth = 1){
	
	if ($fixWidth){
		$fixWidthClass = 'fa-fw';
	}

	if ($quoteType == 1){
		return "<i class='fa {$fixWidthClass} {$icon}' aria-hidden='true'></i>";
	} else {
		return "<i class=\"fa {$fixWidthClass} {$icon}\" aria-hidden=\"true\"></i>";
	}
}


function getAlerts($message = '', $type = 'danger', $class='col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12'){

	$results .= "<div class='row'>";
		$results .=  "<div class='{$class}'>";
			$results .=  "<br/>";
			$results .=  "<div class='alert alert-{$type}' role='alert'>";
				$results .=  $message;	
			$results .=  "</div>";
		$results .=  "</div>";
	$results .=  "</div>";	
	
	return $results;
}


function printModal($modalID = '', $modalTitle = '', $modalBody = '', $modalButtonText = 'Close', $modalBodyClass = '', $modalButtonActionClass = '', $modalClass = ''){
	
	$results = '';
	
	if ($modalButtonText == '') $modalButtonText = 'Close';
	
	
	$results .= "<div id='{$modalID}' class='modal fade' role='dialog'>";
		$results .= "<div class='modal-dialog {$modalClass}' role='document'>";
			$results .= "<div class='modal-content'>";
				$results .= "<div class='modal-header'>";
					$results .= "{$modalTitle}";
					$results .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
				$results .= "</div>";
				
				$results .= "<div class='modal-body {$modalBodyClass}'>{$modalBody}</div>";
				
				$results .= "<div class='modal-footer' style='border:none;'>";
					$results .= "<button type='button' class='btn btn-primary {$modalButtonActionClass}' data-dismiss='modal'>{$modalButtonText}</button>";
				$results .= "</div>";
				
			$results .= "</div>";
		$results .= "</div>";
	$results .= "</div>";	
	
	return $results;
	
}


function printConfirmation($modalID = '', $modalTitle = '', $modalBody = '', $modalButtonTextAction = 'Close', $modalButtonTextCancel = 'Cancel', $modalButtonActionClass = 'actionTrigger'){
	
	$results = '';
	
	$results .= "<div id='{$modalID}' class='modal fade' role='dialog'>";
		$results .= "<div class='modal-dialog' role='document'>";
			$results .= "<div class='modal-content'>";
				$results .= "<div class='modal-header'>";
					$results .= "{$modalTitle}";
					$results .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
				$results .= "</div>";
				
				$results .= "<div class='modal-body'>{$modalBody}</div>";
				
				$results .= "<div class='modal-footer' style='border:none;'>";
					$results .= "<a href='javascript:void(0);' class='btn {$modalButtonActionClass}'>{$modalButtonTextAction}</a>";
					$results .= "<button type='button' class='btn btn-default' data-dismiss='modal'>{$modalButtonTextCancel}</button>";
				$results .= "</div>";
				
			$results .= "</div>";
		$results .= "</div>";
	$results .= "</div>";	
	
	return $results;
	
}

function getColor($currentKey = NULL, $colorset = 1){
	
	global $COLOR_INDEX;

	$allColors[1]		= array('#23C6C8', '#1C84C6', '#FFA555', '#1AB394');
	$allColors[2]		= array('#d0b7d5', '#a0b3dc', '#90e190', '#9bd8de', '#eaa2a2', 'f6c384', '#dad4a2', '#D0D0D0', '#f0ec86');
	
	
	//Top: #525252
	//Bottom: #016590
	$allColors[3]		= array('#F77459', '#FFD803', '#76D5C8', '#F77459');
	
	
	
	$colorDictionary 	= $allColors[$colorset];
	$colorSize			= array_size($colorDictionary);
	$currentKey			= abs(intval($currentKey));
	
	if ($currentKey > $colorSize - 1){
		$currentKey = $currentKey % $colorSize;
	}
	
	return $colorDictionary[$currentKey];
	
}


function printJSON($data = NULL){
	
	if (is_array($data)){
		return printrExpress($data);
	} else {
		
		$json = json_decode($data, true);
		
		if (json_last_error() == JSON_ERROR_NONE){
			if (is_array($json)){
				return printrExpress($json);
			} else {
				return "<pre>{$json}</pre>";	
			}
		}
	}
	
	if (is_string($data)){
		return "<pre>{$data}</pre>";		
	} else {
		return $data;	
	}
	
}


function getColumnClass($width = array(), $visible = array(), $offset = array()){
	
	$results = array();
	
	$sizes = array('xs', 'sm', 'md', 'lg', 'xl');
	foreach($sizes as $tempKey => $size){
		
		if (array_size($width) > 0){
			if (isset($width[$size])){
				$results[] = "col-{$size}-" . positiveInt($width[$size]);
			}
		}
		
		if (array_size($visible) > 0){
			//$results[] = 'd-none';
			if ($visible[$size]){
				$results[] = "d-{$size}-block";
			} else {
				$results[] = "d-{$size}-none";
			}
		}
		
		if (array_size($offset) > 0){
			if ($offset[$size] > 0){
				$results[] = "offset-{$size}-" . positiveInt($offset[$size]);	
			}
		}
	}
	
	if (is_int($width)){
		$results[] = "col-{$width}";
	}
	
	
	$results = array_clean($results);
	
	return implode(' ', $results);
}

function printWizard($wizard = array()){

	$size = array_size($wizard);
	
	if ($size <= 0) return false;
	
	$results = '';
	
		$results .= "<div class='col-12'>";
			
			$results .= "<br/>";
		
			$results .= "<table>";
			
				$results .= "<tr>";
					for ($i = 1; $i <= $size; $i++){
						
						if ($wizard[$i]['State'] == 0){
							$buttonClass 	= 'btn-outline-secondary';
							$disabled		= 'disabled';
						} elseif ($wizard[$i]['State'] == 1){
							$buttonClass 	= 'btn-primary';
							$disabled		= '';
						} elseif ($wizard[$i]['State'] == 2){
							$buttonClass 	= 'btn-outline-secondary';
							$disabled		= '';
						}
						
						$results .= "<td class='text-center'>";
						
							if ($wizard[$i]['Link'] == ''){
								$results .= "<button type='button' class='btn {$buttonClass} btn-circle btn-lg' {$disabled}>{$wizard[$i]['Icon']}</button>";
							} else {
								$results .= "<a href='{$wizard[$i]['Link']}' class='{$wizard[$i]['Link-Class']}' xstyle='cursor:pointer;'>";
									$results .= "<button role='button' class='btn {$buttonClass} btn-circle btn-lg' {$disabled}>{$wizard[$i]['Icon']}</button>";
								$results .= "</a>";
							}
						$results .= "</td>";
						
						
						if ($i != $size){
							$results .= "<td class='text-center'>";
								$results .= "<hr/>";
							$results .= "</td>";
						}
					}
				$results .= "</tr>";
				
				$results .= "<tr>";
					for ($i = 1; $i <= $size; $i++){
						
						unset($textIcon);
						
						if ($wizard[$i]['State'] == 2){
							$textIcon = printFontAwesomeIcon('fas fa-check text-success');
						}
						
						$results .= "<td class='text-center form-text'>";
							if ($wizard[$i]['Link'] == ''){
								$results .= "{$textIcon} {$wizard[$i]['Title']}";
							} else {
								$results .= "<a href='{$wizard[$i]['Link']}' class='{$wizard[$i]['Link-Class']}'>";
									$results .= "{$textIcon} {$wizard[$i]['Title']}";
								$results .= "</a>";
							}
						$results .= "</td>";
						
						
						if ($i != $size){
							$results .= "<td class='text-center'>";
								$results .= "<div style='width:100px;'>&nbsp;</div>";
							$results .= "</td>";
						}
					}

				$results .= "</tr>";
				
			$results .= "</table>";
			
			$results .= "<br/>";
			
		$results .= "</div>";


	return $results;	
	
}

function getFullScreenSpinner($id = '', $icon = '', $text = ''){
	
	if ($id == '') $id = 'full_screen_spinner';
	
	if ($icon == '') $icon = 'fas fa-spinner fa-spin';
	$fontAwesomeIcon = printFontAwesomeIcon($icon);
	
	if ($text == '') $text = 'Loading...';
	
	$results = "<div id='{$id}'>
					<div id='{$id}_content'>
						{$fontAwesomeIcon} {$text}
					</div>
			    </div>";
				
	$results .='			
				<style>

			#full_screen_spinner {
			  position: fixed;
			  top: 0; left: 0; z-index: 9999;
			  width: 100vw; height: 100vh;
			  background: rgba(0, 0, 0, 0.7);
			  transition: opacity 0.2s;
			}

			#full_screen_spinner_content {
			  position: absolute;
			  top: 50%; left: 50%;
			  transform: translate(-50%);
			  color:#FFF;
			  font-size:35px;
			}
			 
			
			#full_screen_spinner {
			  visibility: hidden;
			  opacity: 0;
			}
			
			#full_screen_spinner.show {
			  visibility: visible;
			  opacity: 1;
			}
			
			</style>';
			
	return $results;
	
}

?>