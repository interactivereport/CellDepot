<?php


$SQL = "SELECT `ID`, `URL` FROM `{$APP_CONFIG['TABLES']['PROJECT']}` WHERE (`URL` != '') AND (`URL_Body` = '')";

$allProjects = getSQL_Data($SQL, 'GetAssoc', 0);


foreach($allProjects as $ID => $URL){
	
	
	$HTML = file_get_contents($URL);
	
	if ($HTML != ''){
		$dataArray = array();
		
		$dataArray['URL_Date'] = date("Y-m-d");
		
		$dataArray['URL_Body'] = $HTML;
		
		$SQL = getUpdateSQLQuery($APP_CONFIG['TABLES']['PROJECT'], $dataArray, $ID);
		executeSQL($SQL);

	}
	
}





?>