<?php
include_once('config_init.php');

$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Import_Record'];
$PAGE['Header']		= $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Import_Record'];
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_import.php';
$PAGE['Body'] 		= 'app_project_import_content.php';
$PAGE['EXE'] 		= 'app_project_import_exe.php';
$PAGE['Barcode']	= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Select2'] 				= true;



if (!isGuestUser()){
	include('template/php/components/page_generator.php');
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}


?>