<?php

$BXAF_CONFIG_CUSTOM['SETTINGS']["App_Project_Column_Order_Default"]
= $BXAF_CONFIG_CUSTOM['SETTINGS']['App_Project_Column_Order'] 
= array('Actions', 'Accession','Name', 'Species', 'Cell_Count', 'Gene_Count');

//Annotation Group Limit
//Browse Multiple Records
$BXAF_CONFIG_CUSTOM['SETTINGS']['Annotation_Group_Limit']['Browse'] = 100;

//Review Single Record
$BXAF_CONFIG_CUSTOM['SETTINGS']['Annotation_Group_Limit']['Review'] = 100;

$BXAF_CONFIG_CUSTOM['SETTINGS']['Annotation_Group_Default'] = 
array('celltype', 'CellTypes', 'Annotation', 'Anot', 'Cluster', 'Clustering');

$BXAF_CONFIG_CUSTOM['SETTINGS']['Annotation_Group_Edit_Distance'] = 1;

$BXAF_CONFIG_CUSTOM['SETTINGS']['Gene_Plot_Project_Count_Per_Page'] = 5;

$BXAF_CONFIG_CUSTOM['SETTINGS']['Gene_Plot_SubSampling'] = array(0 => 'All Cells', 1000 => '1,000', 5000 => '5,000', 10000 => '10,000', 20000 => '20,000');


$BXAF_CONFIG_CUSTOM['SETTINGS']['Project_Filter_Candidates'] = array('Year', 'Species');


?>