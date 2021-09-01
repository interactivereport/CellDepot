<?php
include_once('config_init.php');

$currentTable 		= $APP_CONFIG['TABLES']['PROJECT'];

$PAGE['Title'] 		= 'Crawler';
$PAGE['Header']		= 'Crawler';
$PAGE['Category']	= "";

$PAGE['URL']		= 'app_project_crawler.php';
$PAGE['Body'] 		= 'app_project_crawler_content.php';
$PAGE['EXE'] 		= '';
$PAGE['Barcode']	= '';




if (isAdminUser()){
	include('template/php/components/page_generator.php');
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}


?>