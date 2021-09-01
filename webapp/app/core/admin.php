<?php
include_once('config_init.php');

$PAGE['Title'] 		= "Admin Tools";
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "Settings";

$PAGE['URL']		= 'admin.php';
$PAGE['Barcode']	= 'admin.php';
$PAGE['Body'] 		= 'admin_content.php';
$PAGE['EXE'] 		= '';
$PAGE['EXE'] 		= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Popper'] = true;


if (isAdminUser()){
	include('template/php/components/page_generator.php');
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>