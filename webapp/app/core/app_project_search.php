<?php
include_once('config_init.php');

$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Search'];
$PAGE['Header']		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Search'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_search.php';
$PAGE['Body'] 		= 'app_project_search_content.php';
$PAGE['EXE'] 		= 'app_project_search_exe.php';
$PAGE['Barcode']	= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['js-md5'] 			= true;
$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['DataTable'] 		= true;
$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapSelect'] 		= true;
$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapDatePicker']	= true;
$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Select2'] 				= true;


include('template/php/components/page_generator.php');


?>