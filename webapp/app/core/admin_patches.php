<?php
include_once('config_init.php');

$PAGE['Title'] 		= 'Upgrade Database';
$PAGE['Header']		= $PAGE['Title'];
$PAGE['Category']	= "System Settings";

$PAGE['URL']		= 'admin_patches.php';
$PAGE['Barcode']	= 'admin_patches.php';
$PAGE['Body'] 		= 'admin_patches_content.php';
$PAGE['EXE'] 		= '';

$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Popper'] = true;


if (isAdminUser()){
	include('template/php/components/page_generator.php');
} else {
	echo "<p>This tool is available for admin users only. Please log in as admin user and try again.</p>";	
}

?>