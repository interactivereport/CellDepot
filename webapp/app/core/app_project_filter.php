<?php
include_once('config_init.php');


$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG_CUSTOM['MESSAGE'][$currentTable]['General']['Filters'];
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_filter.php';
$PAGE['Body'] 		= 'app_project_filter_content.php';
$PAGE['EXE'] 		= 'app_project_filter_exe.php';
$PAGE['Barcode']	= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['DataTable'] = true;
$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Popper']	= true;


include('template/php/components/page_generator.php');

?>