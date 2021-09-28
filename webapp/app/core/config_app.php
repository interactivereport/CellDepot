<?php

//Tables
if (true){
	$APP_CONFIG['TABLES']['PROJECT'] 						= 'App_Project';
	$APP_CONFIG['TABLES']['PROJECT_ACCESS_LOG']				= 'App_Project_Access_Log';
	$APP_CONFIG['TABLES']['PROJECT_GENE_PLOT']				= 'App_Project_Gene_Plot_Results';
	$APP_CONFIG['TABLES']['PROJECT_GENE_INDEX']				= 'App_Project_Gene_Index';
	$APP_CONFIG['TABLES']['GENE_INDEX']						= 'App_Gene_Index';
}

//Constant
if (true){
	$APP_CONFIG['CONSTANTS']['TABLES']['Project']			= 1;
	$APP_CONFIG['CONSTANTS']['COLUMNS']['Project::Species']	= 101;
}

//Version
if (true){
	$APP_CONFIG['CELLXGENE_getH5adInfo_version']			= '2021-09-27';
}


include_once('config_app_project.php');


//Menu Items
if (true){
	$BXAF_CONFIG['LEFT_MENU_ITEMS'] = array();

	if (!isGuestUser()){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
					'Name'	=> $BXAF_CONFIG_CUSTOM['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Import_Records'],
					'URL'	=> 'app_project_import.php',
					'Icon' 	=> 'fas fa-file-upload',
				);
	}
	
	if (isAdminUser()){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> $BXAF_CONFIG_CUSTOM['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['New_Record'],
				'URL'	=> 'app_project_create.php',
				'Icon' 	=> 'far fa-file',
			);
	}
	
	if (true){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> $BXAF_CONFIG_CUSTOM['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Browse_Records'],
				'URL'	=> 'app_project_browse.php',
				'Icon' 	=> 'fas fa-table',
			);
	}
	
	if (true){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> $BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Search_Records'],
				'URL'	=> 'app_project_search.php',
				'Icon' 	=> 'fas fa-search',
			);
	}
	
	if (true){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> $BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Filters'],
				'URL'	=> 'app_project_filter.php',
				'Icon' 	=> 'fas fa-columns',
			);
	}
	
	if (true){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> $BXAF_CONFIG['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Gene_Plot'],
				'URL'	=> 'app_gene_plot.php',
				'Icon' 	=> 'fas fa-dna',
			);
	}
	
	
	
	
	if (isAdminUser()){
		$BXAF_CONFIG['PAGE_MENU_ITEMS'][] = 
			array(
				'Name'	=> 'Admin Tools',
				'URL'	=> 'admin.php',
				'Icon' 	=> 'fas fa-cog',
			);
	}
}





//Other Settings
if (true){
	$APP_CONFIG['APP']['Cache_Expiration_Length'] = 86400;
	
	$APP_CONFIG['APP']['Search']['Operator']['String'][1]	= 'Contains';
	$APP_CONFIG['APP']['Search']['Operator']['String'][2]	= 'Is';
	$APP_CONFIG['APP']['Search']['Operator']['String'][3]	= 'Is not';
	$APP_CONFIG['APP']['Search']['Operator']['String'][4]	= 'Starts With';
	$APP_CONFIG['APP']['Search']['Operator']['String'][5]	= 'Ends With';
	
	
	$APP_CONFIG['APP']['Search']['Operator']['Number'][10]	= '=';
	$APP_CONFIG['APP']['Search']['Operator']['Number'][11]	= '>';
	$APP_CONFIG['APP']['Search']['Operator']['Number'][12]	= '>=';
	$APP_CONFIG['APP']['Search']['Operator']['Number'][13]	= '<';
	$APP_CONFIG['APP']['Search']['Operator']['Number'][14]	= '<=';
	$APP_CONFIG['APP']['Search']['Operator']['Number'][15]	= '!=';

}

		
?>