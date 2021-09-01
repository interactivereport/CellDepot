<?php

include_once('config_init.php');


$action = $_GET['action'];

if ($action = 'Columns'){
	if (array_size($_POST['Columns']) > 0){
		saveColumnSettings($_GET['table'], $_POST['Columns']); 	
	}
}


exit();


?>