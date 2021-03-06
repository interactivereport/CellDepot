<?php

$currentTable = 'App_Project';

//Project Title/Wording
if (true){
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['New_Record']
		= 'Create Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Review']
		= 'View Details';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Review_Record']
		= 'Review Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Update']
		= 'Update';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Update_Record']
		= 'Update Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Update_Message']
		= 'The project has been saved.';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Copy_Record']
		= 'Copy Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Delete_Record']
		= 'Delete Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Dashboard']
		= 'Dashboard';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Search_Records']
		= 'Search Projects';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Browse_Records']
		= 'Browse Projects';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Record']
		= 'Project';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Records']
		= 'Projects';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Export Selected Tooltip']
		= 'Export all projects with selected columns';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Export All Tooltip']
		= 'Export all projects with all available columns';	
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Import_Records']
		= 'Import Projects';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Cellxgene']
		= 'Cellxgene VIP';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Technical_Details']
		= 'Technical Details';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Start_Cellxgene']
		= 'Start Cellxgene';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Stop_Cellxgene']
		= 'Stop Cellxgene';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Cellxgene_Started_Message']
		= 'The Cellxgene instance has been started.';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Cellxgene_Stopped_Message']
		= 'The Cellxgene instance has been stopped.';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Filters']
		= 'Project Filters';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Gene_Plot']
		= 'Search Genes';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Gene_Plot_Tooltip']
		= 'Search genes within selected projects';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Gene_Search_No_Result']
		= 'The result is not available.';
		
		


}

//***************************
// Table Columns
//***************************
if (true){
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Date']['Title'] 			
		= 'Uploaded On';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Date_Time']['Title'] 			
		= 'Date/Time';

	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['User_ID']['Title'] 			
		= 'User ID';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['User_Name']['Title'] 			
		= 'Uploaded By';

	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Accession']['Title']		= 'Custom Accession';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Accession']['Required'] 	= true;
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Name']['Title'] 			= 'Name';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Species']['Title'] 		= 'Species';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Species_Raw']['Title'] 	= 'Species';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Diseases']['Title'] 		= 'Diseases';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Diseases_Raw']['Title'] 	= 'Diseases';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Year']['Title']			= 'Year';
			
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Description']['Title']		= 'Description';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['DOI']['Title']				= 'DOI';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['URL']['Title']				= 'URL';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Cell_Count']['Title']		= 'Cell Count';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Gene_Count']['Title']		= 'Gene Count';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Project_Groups']['Title']	= 'Annotation Groups';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Genes']['Title']			= 'Genes';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Subsampling']['Title']		= 'Subsampling';
	
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Notes']['Title']			= 'Notes';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['PMCID']['Title']			= 'PMC ID';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['PMID']['Title']			= 'PubMed ID';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Title']['Title']			= 'Publication Title';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_Name']['Title']		= 'File Name';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_Directory']['Title']	= 'File Directory';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_Size']['Title']		= 'File Size';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Launch_Method']['Title']			= 'Cellxgene VIP Launch Method';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Launch_Method']['Value'][0] 		= 'Standard';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Launch_Method']['Value'][1] 	 	= 'Preload in Memory';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_Directory']['Title']			= 'h5ad File Location';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_Directory_CSCh5ad']['Title']	= 'h5ad (CSC) File Location';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_h5ad_info']['Title']			= 'getH5adInfo() Output';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_h5ad_status']['Title']		= 'getH5adInfo() Status';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_h5ad_status']['Value'][0] 	= 'Not Started / Not Available';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_h5ad_status']['Value'][1] 	= 'Completed';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_CSCh5ad_status']['Title']		= 'h5ad (CSC) Conversion Status';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_CSCh5ad_status']['Value'][0] 	= 'Not Started / Not Available';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['File_CSCh5ad_status']['Value'][1] 	= 'Completed';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Plot_Type']['Title']				= 'View as';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Plot_Type']['Value']['violin']		= 'Violin';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['Plot_Type']['Value']['dot']		= 'Dot Plot';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['n']['Title']						= 'Cell Count Cutoff';
		
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['g']['Title']						= 'Expression Cutoff';	
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['e']['Title']						= 'Gene Expression Color Scale';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['p']['Title']						= 'Percentage Represented by the Largest Dot';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['p_Short_HTML']['Title']			= '% of Largest Dots';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['l']['Title']						= 'Perform natural-log transformation, log(x+1) of a data set if the maximum gene expression value in the data set is greater than';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['l_HTML']['Title']					= 'Perform natural-log transformation, log(x+1) of a data set if the maximum gene expression value in the data set is greater than';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['l_Short_HTML']['Title']			= 'ln transformation &gt;';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['plot_height']['Title']				= 'Height of Plot';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['plot_width']['Title']				= 'Width of Plot';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['export_height']['Title']			= 'Height of Download Image';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['export_width']['Title']			= 'Width of Download Image';
	
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['d']['Title']						= 'Position of the Size Legend';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['d']['Value']['h']					= 'Upper Left';
	$BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['Column']['d']['Value']['v']					= 'Right';

	

}



?>