<?php
include_once('config_init.php');


$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Gene_Plot'];
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_gene_plot.php';
$PAGE['Body'] 		= 'app_gene_plot_content.php';
$PAGE['EXE'] 		= 'app_gene_plot_exe.php';
$PAGE['Barcode']	= '';



include('template/php/components/page_generator.php');

?>