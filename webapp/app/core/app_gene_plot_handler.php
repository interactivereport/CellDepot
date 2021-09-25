<?php
include_once('config_init.php');



$IDs = id_sanitizer($_POST['searchGeneIDs'], 0, 1, 0, 1);

if (array_size($IDs) > 0){
	$key = 'IDs_' . id_sanitizer($IDs, 0, 1, 0, 3);
	putRedisCache(array($key => $IDs), 1);
	header("Location: app_gene_plot.php?preselected={$key}");
	exit();
} elseif ($_GET['preselected'] != ''){
	header("Location: app_gene_plot.php?preselected={$_GET['preselected']}");
	exit();
} else {
	header("Location: app_gene_plot.php");
	exit();
		
}





?>