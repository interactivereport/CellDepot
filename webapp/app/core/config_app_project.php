<?php

//Project
if (true){
	$currentTable = $APP_CONFIG['TABLES']['PROJECT'];
	
	

	$currentColumn = 'ID';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printReadOnly_Text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;


	


	$currentColumn = 'Date_Time';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'Date';
	

	$currentColumn = 'User_ID';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	

	
	$currentColumn = 'Actions';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printReadOnly_Text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Export_Disable']			= true;
	
	
	

	$currentColumn = 'Accession';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Required']				= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	
	
	
	
	
	$currentColumn = 'Name';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Required']				= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';

	
	$currentColumn = 'Species';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printDropDown_Config_KeyValue';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Function']			= 'getProject_Species_MenuForSearch';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Count_Function']	= 'getProject_Species_ValueCountForFilter';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search_Function']			= 'getProjectConditionForSearch';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['selectpicker']			= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['First_Option_Empty']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_value';
	
	
	$currentColumn = 'Species_Raw';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printTag';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Function']			= 'getProject_Species_Values';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['First_Option_Empty']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'tag';
	


	$currentColumn = 'Year';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printDropDown_From_DB';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_value';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Sort_By']					= 'Category';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Sort_Order']				= 'DESC';


	$currentColumn = 'Description';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printTextArea';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Rows']					= 4;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'paragraph';
	
	
	$currentColumn = 'DOI';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';

	
	$currentColumn = 'URL';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	
	
	$currentColumn = 'Cell_Count';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'number';
	
	
	$currentColumn = 'Gene_Count';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'number';
	

	$currentColumn = 'Project_Groups';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printTextArea';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Rows']					= 4;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'paragraph';
	
	
	$currentColumn = 'Notes';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printTextArea';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Rows']					= 4;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'paragraph';
	
	
	$currentColumn = 'PMCID';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	
	
	$currentColumn = 'PMID';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	
	
	$currentColumn = 'Title';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	
	
	$currentColumn = 'File_Name';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= "h5ad or CSC file. Please make sure that the files locates in: {$BXAF_CONFIG['Server'][1]['File_Directory']}";
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Required']				= true;
	
	
	
	
	$currentColumn = 'File_Size';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'number';
	
	
	$currentColumn = 'File_Directory';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	
	
	$currentColumn = 'File_Directory_CSCh5ad';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printInput';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'text';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	
	
	$currentColumn = 'Launch_Method';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printDropDown_Config_KeyValue';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-2 col-lg-3 col-md-4 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Default']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Default'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_key_value';
	
	$currentColumn = 'File_h5ad_info';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	
	$currentColumn = 'File_h5ad_status';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_key_value';
	
	$currentColumn = 'File_CSCh5ad_status';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_key_value';
	
	$currentColumn = 'File_h5ad_info_version';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	
	$currentColumn = 'Gene_Max_Expression';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	
	$currentColumn = 'File_CSCh5ad_filesize';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['AuditTrail_Disable']		= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= false;
	
	$currentColumn = 'User_Name';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['printForm']['New']		= 'printDropDown_From_DB';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value']					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Value'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['PlaceHolder'] 			= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['PlaceHolder'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Label_Class']				= 'col-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Class']				= 'col-xl-4 col-lg-6 col-md-10 col-sm-12 col-xs-12';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'array_value';
	
	$currentColumn = 'Date';
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Title'] 					= $BXAF_CONFIG['MESSAGE'][$currentTable]['Column'][$currentColumn]['Title'];
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Search']					= false;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Browse']					= true;
	$APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Type']					= 'Date';
	

}

?>