<?php

class HTML_Form {
	
	private $currentSQL, $currentTable, $currentValue, $currentTitle, 
			$currentDropDownValue,
			$currentPlaceHolder, $currentPlaceHolderReadOnly, 
			$currentRequired, $currentFunction, $printHidden, $selectpicker, $currentRows, $TinyMCE,
			$currentLabelClass, $currentValueClass, $currentValueByClass, $currentValuesDictionary, $currentValueFunction,
			$currentOptions,
			$componentOnly;

	
	
	public function __construct($options = array()){
		
		global $APP_CONFIG;
	
	
		$this->currentOptions			= $options;
		
		if (true){
			$currentSQL 				= $options['Column'];
			$this->currentSQL 			= $currentSQL;
		}
		
		if (true){
			$currentTable				= $options['Table'];
			$this->currentTable 		= $currentTable;
		}

		
		if (true){
			if (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['selectpicker'])){
				$selectpicker = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['selectpicker'];
			}
			
			if (isset($options['selectpicker'])){
				$selectpicker = $options['selectpicker'];
			}
			
			$this->selectpicker 		= $selectpicker;
		}
		
		if (true){
			if (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['TinyMCE'])){
				$TinyMCE = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['TinyMCE'];
			}
			
			if (isset($options['TinyMCE'])){
				$TinyMCE = $options['TinyMCE'];
			}
			
			$this->TinyMCE 		= $TinyMCE;
		}
		
		

		if (true){
			$currentValue			= $options['Value'];
			$this->currentValue 	= $currentValue;
		}
		
		
		if (true){
			$currentValueFunction		= $options['ValueFunction'];
			
			if ($currentValueFunction == ''){
				$currentValueFunction	= $options['Value_Function'];
			}
			
			if ($currentValueFunction == ''){
				$currentValueFunction	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			}
			
			if (($currentValueFunction != '') && function_exists($currentValueFunction)){
				$this->currentValueFunction	= $currentValueFunction;
			}
			
			
		}
		
		
		if (true){
			$currentPlaceHolder		= $options['PlaceHolder'];
			if ($currentPlaceHolder == ''){
				$currentPlaceHolder	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['PlaceHolder'];
			}
			$this->currentPlaceHolder	= $currentPlaceHolder;
		}
		
		if (true){
			$currentPlaceHolderReadOnly		= $options['PlaceHolderReadOnly'];
			if ($currentPlaceHolderReadOnly == ''){
				$currentPlaceHolderReadOnly	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['PlaceHolderReadOnly'];
			}
			$this->currentPlaceHolderReadOnly	= $currentPlaceHolderReadOnly;
		}
		
		
		if (true){
			$currentRows			= intval($options['Rows']);

			if ($options['Rows'] == 0){
				$currentRows		= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Rows'];		
			}
			
			
			if ($currentRows <= 0){
				$currentRows		= 8;	
			}
			$this->currentRows		= $currentRows;
			
		}
		
		
		if (true){
			
			$currentTitle				= $options['Title'];
			if ($currentTitle == ''){
				$currentTitle			= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Title'];
			}
			
			if ($currentTitle == ''){
				$currentTitle			= getHeaderDisplayName($currentTable, $currentSQL, 0);
			}

			
			if ((isset($options['Required']) && $options['Required']) || ($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Required'])){
				$currentRequired		= '*';
			} else {
				$currentRequired		= '';	
			}
			
			if (!endsWith($currentTitle, '?')){
				$currentTitle			= trim("{$currentTitle} {$currentRequired}") . ':';
			} else {
				$currentTitle			= trim("{$currentTitle} {$currentRequired}");
			}
			
			$this->currentTitle 		= $currentTitle;
			$this->currentRequired		= $currentRequired;
		}
		
		
		if (true){
			$currentFunction		= $options['Function'];
			
			if ($currentFunction == ''){
				$currentFunction	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['printForm']['New'];
			}
			
			if ($currentFunction == ''){
				if (strtolower($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Type']) == 'date'){
					$currentFunction	= 'printDate';
				}
			}
			
			
			if ($currentFunction == ''){
				$currentFunction	= 'printInput';
			}
			
			
			
			$this->currentFunction	= $currentFunction;
		}
		
		
		if (true){
			$currentLabelClass		= $options['Label_Class'];
			
			if ($currentLabelClass == ''){
				$currentLabelClass 	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Label_Class'];
			}
			
			if ($currentLabelClass == ''){
				$currentLabelClass 	= 'col-2';
			}
			$this->currentLabelClass	= $currentLabelClass;
		}
		
		
		if (true){
			$currentValueClass		= $options['Value_Class'];
			if ($currentValueClass == ''){
				$currentValueClass 	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Class'];
			}
			
			if ($currentValueClass == ''){
				$currentValueClass 	= 'col-8';
			}
			$this->currentValueClass	= $currentValueClass;
		}
		
		if (true){
			$currentValueByClass	= $options['Value_By_Class'];
			if (array_size($currentValueByClass) <= 0){
				$currentValueByClass 	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_By_Class'];	
			}
			
			
			
			$this->currentValueByClass	= $currentValueByClass;
			
			
			$currentValuesDictionary = $this->currentValuesDictionary;
			
			
			if (array_size($currentValueByClass) > 0){
				
				
				foreach($currentValueByClass as $tempKey1 => $tempValue1){
					
					foreach($tempValue1 as $tempKey2 => $tempValue2){
						$currentValuesDictionary[$tempValue2]['Class'][] = $tempKey1;
					}
				}
				
				
				
				$this->currentValuesDictionary = $currentValuesDictionary;
			}
			
			
		}
		
		
		if (true){
			$printHidden 			= $options['Print_Hidden'];
			$this->printHidden		= $printHidden;
		}
		
		if (true){
			$componentOnly 			= $options['Component_Only'];
			$this->componentOnly	= $componentOnly;
		}
		
		
		if (true){
			$currentDropDownValue	= $options['Drop_Down_Value'];
			
			if (array_size($currentDropDownValue) <= 0){
				$currentDropDownValue = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'];
			}
			$this->currentDropDownValue	= $currentDropDownValue;
		}

  	}
	
	public function printHTMLForm(){
		$currentFunction 	= $this->currentFunction;
		$currentOptions		= $this->currentOptions;
		
		$results = '';
		
		if ($currentFunction == 'printInput'){
			$results = $this->printInput();	
		} elseif ($currentFunction == 'printReadOnly_Text'){
			$results = $this->printReadOnly_Text();	
		} elseif ($currentFunction == 'printReadOnly_Category'){
			$results = $this->printReadOnly_Category();	
		} elseif ($currentFunction == 'printDropDown_From_DB'){
			$results = $this->printDropDown_From_DB();
		} elseif ($currentFunction == 'printDropDown_From_DB_Editable'){
			$results = $this->printDropDown_From_DB_Editable();
		} elseif ($currentFunction == 'printDropDown_Config_KeyValue'){
			$results = $this->printDropDown_Config_KeyValue();
		} elseif ($currentFunction == 'printDropDown_Config_KeyValue_Large'){
			$results = $this->printDropDown_Config_KeyValue_Large();
		} elseif ($currentFunction == 'printDropDown_Config_KeyValue_OPT_Group'){
			$results = $this->printDropDown_Config_KeyValue_OPT_Group();
		} elseif ($currentFunction == 'printDropDown_Config_Value'){
			$results = $this->printDropDown_Config_Value();
		} elseif ($currentFunction == 'printCheckbox'){
			$results = $this->printCheckbox();
		} elseif ($currentFunction == 'printRadio'){
			
			
			if ($currentOptions['Search']){
				$results = $this->printDropDown_Config_KeyValue();
			} else {
				$results = $this->printRadio();
			}
			
		} elseif ($currentFunction == 'printTextArea'){
			
			if ($currentOptions['Search']){
				$results = $this->printInput();
			} else {
				$results = $this->printTextArea();
			}
		} elseif ($currentFunction == 'printReadOnly_Config_KeyValue'){
			$results = $this->printReadOnly_Config_KeyValue();
		} elseif ($currentFunction == 'printFile'){
			$results = $this->printFile();
		} elseif ($currentFunction == 'printDate'){
			$results = $this->printDate();
		} elseif ($currentFunction == 'printTag'){
			$results = $this->printTag();
		} elseif ($currentFunction == 'printTagKeyValue'){
			$results = $this->printTag_KeyValue();
		} elseif ($currentFunction == 'printSlider'){
			$results = $this->printSlider();
		} else {
			$results = $this->printInput();
		}
		
		if ($this->printHidden){
			$results .= $this->printHidden();
		}
		
		
		
		return $results;
		
	}
	
	public function printInput(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= "<input type='text' class='form-control' id='{$componentID}' name='{$componentID}' value='{$currentValue}'>";
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= "<input type='text' class='form-control' id='{$componentID}' name='{$componentID}' value='{$currentValue}'>";
		}
		
		return $results;
			
	}
	
	public function printSlider(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = '';
		
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= "<input type='text' class='form-control slider' id='{$componentID}' name='{$componentID}' value='{$currentValue}' data-slider-min='{$currentOptions['Slider-Min']}' data-slider-max='{$currentOptions['Slider-Max']}' data-slider-step='{$currentOptions['Slider-Step']}' data-slider-value='{$currentValue}'>";
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= "<input type='text' class='form-control slider' id='{$componentID}' name='{$componentID}' value='{$currentValue}' data-slider-min='{$currentOptions['Slider-Min']}' data-slider-max='{$currentOptions['Slider-Max']}' data-slider-step='{$currentOptions['Slider-Step']}' data-slider-value='{$currentValue}'>";
		}
		
		return $results;
			
	}
	
	public function printHidden(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = "<div><input type='hidden' id='{$componentID}' name='{$componentID}' value='{$currentValue}'></div>";
		
		return $results;
			
	}
	
	public function printReadOnly_Text(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentPlaceHolderReadOnly	= $this->currentPlaceHolderReadOnly;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= $currentValue;
					
					if ($currentPlaceHolderReadOnly != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolderReadOnly}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= $currentValue;
		}
		
		return $results;
			
	}
	
	public function printReadOnly_Config_KeyValue(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentPlaceHolderReadOnly	= $this->currentPlaceHolderReadOnly;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		if (($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'] != '') && function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$function = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			$referenceValues	= $function();
		} else {
			$referenceValues	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'];
		}
		
		$currentValue = $referenceValues[$currentValue];
		
		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= $currentValue;
					if ($currentPlaceHolderReadOnly != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolderReadOnly}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= $currentValue;
		}
		
		return $results;
			
	}
	
	public function printReadOnly_Category(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentPlaceHolderReadOnly	= $this->currentPlaceHolderReadOnly;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		
		$currentValue 		= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'][$currentValue];
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= $currentValue;
					if ($currentPlaceHolderReadOnly != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolderReadOnly}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= $currentValue;	
		}
		
		return $results;
			
	}
	
	public function printDropDown_Config_KeyValue(){
		
		global $APP_CONFIG;
		
		$currentSQL 			= $this->currentSQL;
		$currentTable			= $this->currentTable;
		$currentTitle			= $this->currentTitle;
		$currentValue			= $this->currentValue;
		$currentPlaceHolder		= $this->currentPlaceHolder;
		$currentLabelClass		= $this->currentLabelClass;
		$currentValueClass		= $this->currentValueClass;
		$selectpicker			= $this->selectpicker;
		$currentValueFunction 	= $this->currentValueFunction;
		$componentOnly			= $this->componentOnly;
		$currentDropDownValue	= $this->currentDropDownValue;
		$currentOptions			= $this->currentOptions;

		$componentID			= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		
		if (($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'] != '') && function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$function 			= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			$referenceValues	= $function();
		} elseif (($currentValueFunction != '') && function_exists($currentValueFunction)){
			$function 			= $currentValueFunction;
			$referenceValues	= $function();
		} else {
			$referenceValues 	= $currentDropDownValue;
		}
		
		if (($currentValue == '') && (!isset($referenceValues[$currentValue])) && (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default']))){
			$currentValue = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default'];	
		}
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			if ($selectpicker){
				$results .= "<select class='form-control selectpicker' id='{$componentID}' name='{$componentID}' data-live-search='true'>";
			} else {
				$results .= "<select class='form-control' id='{$componentID}' name='{$componentID}'>";
			}
			
				if ($currentOptions['First_Option_Empty']){
					$results .= "<option value=''>&nbsp;</option>";	
				}
			
				foreach($referenceValues as $currentKey => $currentDisplay){
					
					if ($currentKey == $currentValue){
						$selected = 'selected';	
					} else {
						$selected = '';	
					}
					
					$results .= "<option value='{$currentKey}' {$selected}>{$currentDisplay}</option>";
				}
			
			$results .= "</select>";
		}
					
					
		if (!$componentOnly){			
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printDropDown_Config_KeyValue_Large(){
		
		global $APP_CONFIG;
		
		$currentSQL 			= $this->currentSQL;
		$currentTable			= $this->currentTable;
		$currentTitle			= $this->currentTitle;
		$currentValue			= $this->currentValue;
		$currentPlaceHolder		= $this->currentPlaceHolder;
		$currentLabelClass		= $this->currentLabelClass;
		$currentValueClass		= $this->currentValueClass;
		$selectpicker			= $this->selectpicker;
		$currentValueFunction 	= $this->currentValueFunction;
		$componentOnly			= $this->componentOnly;
		$currentDropDownValue	= $this->currentDropDownValue;
		$currentOptions			= $this->currentOptions;
		

		$componentID			= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		
		if (($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'] != '') && function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$function 			= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			$referenceValues	= $function();
		} elseif (($currentValueFunction != '') && function_exists($currentValueFunction)){
			$function 			= $currentValueFunction;
			$referenceValues	= $function();
		} else {
			$referenceValues 	= $currentDropDownValue;
		}
		
		if (($currentValue == '') && (!isset($referenceValues[$currentValue])) && (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default']))){
			$currentValue = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default'];	
		}
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			$results .= "<select class='form-control select2_KeyValue_Large' id='{$componentID}' name='{$componentID}'>";
			
				if ($currentOptions['First_Option_Empty']){
					$results .= "<option value=''>&nbsp;</option>";	
				}
			
			
				foreach($referenceValues as $currentKey => $currentDisplay){
					
					if ($currentKey == $currentValue){
						$selected = 'selected';	
					} else {
						$selected = '';	
					}
					
					$results .= "<option value='{$currentKey}' {$selected}>{$currentDisplay}</option>";
				}
			
			$results .= "</select>";
		}
					
					
		if (!$componentOnly){			
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printDropDown_Config_KeyValue_OPT_Group(){
		
		global $APP_CONFIG;
		
		$currentSQL 			= $this->currentSQL;
		$currentTable			= $this->currentTable;
		$currentTitle			= $this->currentTitle;
		$currentValue			= $this->currentValue;
		$currentPlaceHolder		= $this->currentPlaceHolder;
		$currentLabelClass		= $this->currentLabelClass;
		$currentValueClass		= $this->currentValueClass;
		$selectpicker			= $this->selectpicker;
		$currentValueFunction 	= $this->currentValueFunction;
		$componentOnly			= $this->componentOnly;
		$currentDropDownValue	= $this->currentDropDownValue;
		$currentOptions			= $this->currentOptions;
		

		$componentID			= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		
		if (($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'] != '') && function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$function 			= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			$referenceValues	= $function();
		} elseif (($currentValueFunction != '') && function_exists($currentValueFunction)){
			$function 			= $currentValueFunction;
			$referenceValues	= $function();
		} else {
			$referenceValues 	= $currentDropDownValue;	
		}
		
		if (($currentValue == '') && (!isset($referenceValues[$currentValue])) && (isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default']))){
			$currentValue = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Default'];	
		}
		
		$currentCategories = $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Category'];
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			if ($selectpicker){
				$results .= "<select class='form-control selectpicker' id='{$componentID}' name='{$componentID}' data-live-search='true'>";
			} else {
				$results .= "<select class='form-control' id='{$componentID}' name='{$componentID}'>";
			}
			
				foreach($currentCategories as $currentCategory => $currentList){
					
					$results .= "<optgroup label='{$currentCategory}'>";
			
					foreach($currentList as $tempKey => $currentKey){
						
						
						if (!isset($referenceValues[$currentKey])) continue;
						
						$currentDisplay = $referenceValues[$currentKey];
						
						if ($currentKey == $currentValue){
							$selected = 'selected';	
						} else {
							$selected = '';	
						}
						
						$results .= "<option value='{$currentKey}' {$selected}>{$currentDisplay}</option>";
					}
					
					$results .= "</optgroup>";
				}
			
			$results .= "</select>";
		}
					
					
		if (!$componentOnly){			
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printDropDown_Config_Value(){
		
		global $APP_CONFIG;
		
		$currentSQL 				= $this->currentSQL;
		$currentTable				= $this->currentTable;
		$currentTitle				= $this->currentTitle;
		$currentValue				= $this->currentValue;
		$currentPlaceHolder			= $this->currentPlaceHolder;
		$currentLabelClass			= $this->currentLabelClass;
		$currentValueClass			= $this->currentValueClass;
		$currentValuesDictionary	= $this->currentValuesDictionary;
		$selectpicker				= $this->selectpicker;
		$componentOnly				= $this->componentOnly;
		$currentDropDownValue		= $this->currentDropDownValue;
		$currentOptions				= $this->currentOptions;
		$currentValueFunction 		= $this->currentValueFunction;

		$componentID			= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		if (($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'] != '') && function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$function 			= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'];
			$referenceValues	= $function();
		} elseif (($currentValueFunction != '') && function_exists($currentValueFunction)){
			$function 			= $currentValueFunction;
			$referenceValues	= $function();
		} else {
			$referenceValues 	= $currentDropDownValue;
		}
		
		
		if ($currentValue != ''){
			if (!in_arrayi($currentValue, $referenceValues)){
				$referenceValues[$currentValue] = $currentValue;
			}
		}
		natcasesort($referenceValues);
		
	

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			if ($selectpicker){
				$results .= "<select class='form-control selectpicker' id='{$componentID}' name='{$componentID}' data-live-search='true'>";
			} else {
				$results .= "<select class='form-control' id='{$componentID}' name='{$componentID}'>";
			}
			
				$results .= "<option value=''></option>";
				
				foreach($referenceValues as $currentKey => $currentDisplay){
					
					$valueClass = '';
					if (is_array($currentValuesDictionary[$currentDisplay]['Class'])){
						$valueClass = implode(' ', $currentValuesDictionary[$currentDisplay]['Class']);
					}
					
					if (strtolower($currentValue) == strtolower($currentDisplay)){
						$selected = 'selected';	
					} else {
						$selected = '';	
					}
					
					$results .= "<option value='{$currentDisplay}' class='{$valueClass}' {$selected}>{$currentDisplay}</option>";
				}
			
			$results .= "</select>";
		
		}
		
		
		if (!$componentOnly){
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printDropDown_From_DB(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$selectpicker		= $this->selectpicker;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		$referenceValues	= getUniqueColumnValues($currentTable, $currentSQL);
		
		
		
		if (is_array($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'])){
			$referenceValues	= array_merge2($referenceValues, $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value']);
		}
		
		
		if ($currentValue != ''){
			if (!in_arrayi($currentValue, $referenceValues)){
				$referenceValues[$currentValue] = $currentValue;
			}
		}
		
		$referenceValues = array_clean($referenceValues);
		natcasesort($referenceValues);
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			if ($selectpicker){
				$results .= "<select class='form-control selectpicker' id='{$componentID}' name='{$componentID}' data-live-search='true'>";
			} else {
				$results .= "<select class='form-control' id='{$componentID}' name='{$componentID}'>";
			}
			
				$results .= "<option value=''></option>";
			
				foreach($referenceValues as $currentKey => $currentDisplay){
					
					if (strtolower($currentValue) == strtolower($currentDisplay)){
						$selected = 'selected';	
					} else {
						$selected = '';	
					}
					
					$results .= "<option value='{$currentDisplay}' {$selected}>{$currentDisplay}</option>";
				}
			
			$results .= "</select>";
		}
		
		if (!$componentOnly){
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printDropDown_From_DB_Editable(){
		//Require Select2
		//Require select2_Editable event
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$selectpicker		= $this->selectpicker;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		$referenceValues	= getUniqueColumnValues($currentTable, $currentSQL);
		
		
		
		if (is_array($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'])){
			$referenceValues	= array_merge2($referenceValues, $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value']);
		}
		
		
		if ($currentValue != ''){
			if (!in_arrayi($currentValue, $referenceValues)){
				$referenceValues[$currentValue] = $currentValue;
			}
		}
		
		$referenceValues = array_clean($referenceValues);
		natcasesort($referenceValues);
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			$results .= "<select class='form-control select2_Editable' id='{$componentID}' name='{$componentID}'>";
			
			$results .= "<option value=''></option>";
		
			foreach($referenceValues as $currentKey => $currentDisplay){
				
				if (strtolower($currentValue) == strtolower($currentDisplay)){
					$selected = 'selected';	
				} else {
					$selected = '';	
				}
				
				$results .= "<option value='{$currentDisplay}' {$selected}>{$currentDisplay}</option>";
			}
			
			$results .= "</select>";
		}
		
		if (!$componentOnly){
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printTag(){
		//Require Select2
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		if (function_exists($APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function'])){
			$referenceValues	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value_Function']();
		}
		
		if (is_array($currentValue)){
			$referenceValues	= array_merge2($referenceValues, $currentValue);
		}
		
		$referenceValues = array_clean($referenceValues, 0, 1, 1, 0);
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			$results .= "<select class='form-control select2_Tag' id='{$componentID}' name='{$componentID}[]' multiple='multiple'>";
		
			$results .= "<option value=''></option>";
		
			foreach($referenceValues as $currentKey => $currentDisplay){
				
				if (in_arrayi($currentDisplay, $currentValue)){
					$selected = 'selected';	
				} else {
					$selected = '';	
				}
				
				$results .= "<option value='{$currentDisplay}' {$selected}>{$currentDisplay}</option>";
			}
		
			$results .= "</select>";
		}
		
		if (!$componentOnly){
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printTag_KeyValue(){
		//Require Select2
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentValueFunction = $this->currentValueFunction;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		if (function_exists($currentValueFunction)){
			$referenceValues	= $currentValueFunction();
			
			
			foreach($currentValue as $tempKeyX => $tempValueX){
				if (!isset($referenceValues[$tempValueX])){
					$referenceValues[$tempValueX] = $tempValueX;
				}
			}
			
		}
		

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
		}
		
		if (true){
			$results .= "<select class='form-control select2_Tag' id='{$componentID}' name='{$componentID}[]' multiple='multiple'>";
		
			$results .= "<option value=''></option>";
		
			foreach($referenceValues as $currentKey => $currentDisplay){
				
				if (in_array($currentKey, $currentValue)){
					$selected = 'selected';	
				} else {
					$selected = '';	
				}
				
				$results .= "<option value='{$currentKey}' {$selected}>{$currentDisplay}</option>";
			}
		
			$results .= "</select>";
		}
		
		if (!$componentOnly){
				if ($currentPlaceHolder != ''){
					$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
				}
			
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}

	public function printCheckbox(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		$referenceValues	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'];
		
		$orientation		= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Orientation'];
		
		if ($orientation == 'horizontal'){
			$orientationClass = 'form-check-inline';
		} else {
			$orientationClass = '';
		}
		
		
		$results = '';
		
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
		
			if ($currentTitle != ''){
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
			}
			$results .= "<div class='{$currentValueClass}'>";
		}
			

		foreach($referenceValues as $currentKey => $currentDisplay){
			if (in_array($currentKey, $currentValue)){
				$checked = 'checked';	
			} else {
				$checked = '';	
			}
			
			if (!$componentOnly){
				$results .= "<div class='form-check {$orientationClass}'>";
			}
			
				$results .= "<input class='form-check-input' type='checkbox' value='{$currentKey}' name='{$componentID}[]' {$checked}>";
				
			if (!$componentOnly){
					$results .= "<label class='form-check-label'>{$currentDisplay}</label>";
				$results .= "</div>";
			}
		}
		
		
		if (!$componentOnly){
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printRadio(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}
		
		$referenceValues	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Value'];
		
		$orientation		= $APP_CONFIG['DICTIONARY'][$currentTable][$currentSQL]['Orientation'];
		
		if ($orientation == 'horizontal'){
			$orientationClass = 'form-check-inline';
		} else {
			$orientationClass = '';
		}

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
		
			if ($currentTitle != ''){
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
			}
			
			$results .= "<div class='{$currentValueClass}'>";
		}
		
		foreach($referenceValues as $currentKey => $currentDisplay){
			if (strtolower($currentValue) == strtolower($currentDisplay)){
				$checked = 'checked';	
			} else {
				$checked = '';	
			}
			
			if (!$componentOnly){
				$results .= "<div class='form-check {$orientationClass}'>";
			}
			
				$results .= "<input class='form-check-input' type='radio' value='{$currentDisplay}' name='{$componentID}' {$checked}>";
				
			if (!$componentOnly){
					$results .= "<label class='form-check-label'>{$currentDisplay}</label>";
				$results .= "</div>";
			}
		}
		
		if (!$componentOnly){
				$results .= "</div>";
			$results .= "</div>";
		}
		
		return $results;
			
	}
	
	public function printTextArea(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTable		= $this->currentTable;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$currentRows		= $this->currentRows;
		$componentOnly		= $this->componentOnly;
		$TinyMCE			= $this->TinyMCE;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = '';
		
		$textAreaClass = '';
		if ($TinyMCE){
			$textAreaClass = 'TinyMCE';	
		}
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= "<textarea class='form-control {$textAreaClass}' id='{$componentID}' name='{$componentID}' rows='{$currentRows}'>{$currentValue}</textarea>";
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= "<textarea class='form-control' id='{$componentID}' name='{$componentID}' rows='{$currentRows}'>{$currentValue}</textarea>";	
		}
		
		return $results;
			
	}
	
	
	public function printDate(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = '';
		
		if (isEmptyDate($currentValue)){
			$currentValue = '';
		}
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass} date'>";
					$results .= "<input type='text' class='form-control' id='{$componentID}' name='{$componentID}' value='{$currentValue}'>";
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= "<input type='text' class='form-control' id='{$componentID}' name='{$componentID}' value='{$currentValue}'>";	
		}
		
		if (true){
			$results .= "<script type='text/javascript'>";
				$results .= "$(document).ready(function(){";
					$results .= "$('#{$componentID}').datepicker({";
						$results .= "format: 'yyyy-mm-dd',";
						$results .= "todayHighlight: true";
					$results .= "});";
				$results .= "});";
			$results .= "</script>";
		}
		
		return $results;
			
	}
	
	
	public function printFile(){
		
		global $APP_CONFIG;
		
		$currentSQL 		= $this->currentSQL;
		$currentTitle		= $this->currentTitle;
		$currentValue		= $this->currentValue;
		$currentPlaceHolder	= $this->currentPlaceHolder;
		$currentLabelClass	= $this->currentLabelClass;
		$currentValueClass	= $this->currentValueClass;
		$componentOnly		= $this->componentOnly;
		$currentOptions		= $this->currentOptions;
		

		$componentID		= $currentOptions['ComponentID'];
		if ($componentID == ''){
			$componentID = $currentSQL;
		}

		$results = '';
		
		if (!$componentOnly){
			$results .= "<div class='form-group row'>";
				$results .= "<label for='{$componentID}' class='{$currentLabelClass} col-form-label'><strong>{$currentTitle}</strong></label>";
				$results .= "<div class='{$currentValueClass}'>";
					$results .= "<input type='file' class='form-control-file' id='{$componentID}' name='{$componentID}'>";
					if ($currentPlaceHolder != ''){
						$results .= "<small class='form-text text-muted'>{$currentPlaceHolder}</small>";
					}
				$results .= "</div>";
			$results .= "</div>";
		} else {
			$results .= "<input type='file' class='form-control-file' id='{$componentID}' name='{$componentID}'>";
		}
		
		return $results;
			
	}
	
}

?>