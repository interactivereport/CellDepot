<?php


//***************************
// Form Layout
//***************************
if (true){
	
	$BXAF_CONFIG_CUSTOM['Project_Layout']['Update']['Columns']
		= array(
		'Name',
		'Description', 
		'Accession',
		'Species',  
		'URL',
		'Notes', 
		'Title', 
		'Year',
		'DOI',
		'PMCID', 
		'PMID',
		'Launch_Method',
		 );
		 
	$BXAF_CONFIG_CUSTOM['Project_Layout']['Create']['Columns']
		= array(
		'Name',
		'File_Name',
		'Description',
		'Accession',
		'Species',
		'URL',
		'Notes', 
		'Title',
		'Year',
		'DOI',
		'PMCID',
		'PMID',
		'Launch_Method',
		 );
		
	$BXAF_CONFIG_CUSTOM['Project_Layout']['Review']['Columns']
		= array(
		
		'User_Name', 'Date',
		
		'Accession', 'Name', 'Species',  'Description', 
		
		 'URL', 'Cell_Count', 'Gene_Count', 'Project_Groups', 'Notes',  
		 'Title', 'Year', 'DOI', 'PMCID', 'PMID',
		 
		 'Launch_Method',
		 );
		 
		 
	$BXAF_CONFIG_CUSTOM['Project_Layout']['Review_Technical_Details']['Columns']
		= array(
		'User_Name', 
		'Date',
		'Launch_Method',
		'File_Name',
		'File_Size',
		'File_Directory', 
		'File_Directory_CSCh5ad',  
		'File_CSCh5ad_status',
		'File_h5ad_status',
		'File_h5ad_info'
		 );
	
	
}


?>