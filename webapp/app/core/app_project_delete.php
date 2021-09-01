<?php
include_once('config_init.php');

if (isAdminUser()){

	if ($_GET['source'] == 'POST'){
		$SQL_IDs = id_sanitizer($_POST['ID'], 0, 1, 0, 2);
	} else {
		$SQL_IDs = id_sanitizer($_GET['ID'], 0, 1, 0, 2);
	}
	
	if ($SQL_IDs != ''){
		$SQL = "DELETE FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`ID` IN ({$SQL_IDs}))";
		executeSQL($SQL);
		deleteColumnIndexByRecordID($APP_CONFIG['TABLES']['PROJECT'], $SQL_IDs);
		clearCache();
	}
}


if ($_GET['return']){
	header("Location: index.php");
} else {
	echo '&nbsp;';	
}


?>