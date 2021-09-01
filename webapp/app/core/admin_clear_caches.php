<?php
include_once('config_init.php');

$PAGE['Title'] 		= "Clear Application Caches";
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "Settings";

$PAGE['URL']		= 'admin_clear_caches.php';
$PAGE['Barcode']	= 'admin_clear_caches.php';
$PAGE['Body'] 		= 'admin_clear_caches_content.php';
$PAGE['EXE'] 		= '';

if (isAdminUser()){
	include('template/php/components/page_generator.php');
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>