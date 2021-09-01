<?php

include_once('config_init.php');

if (isGuestUser()){
	exit();
}


if ($_POST['Name'] == ''){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. The {$APP_CONFIG['DICTIONARY'][$APP_CONFIG['TABLES']['PROJECT']]['Name']['Title']} is required.</p>";
	echo getAlerts($message, 'danger');
	echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';
			echo '$("#Study_Short_Title").addClass("is-invalid");';
		echo '});';
	echo '</script>';
	exit();
}

$_POST['File_Name'] = str_replace('/', '', $_POST['File_Name']);


if ($_POST['File_Name'] == ''){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. The {$APP_CONFIG['DICTIONARY'][$APP_CONFIG['TABLES']['PROJECT']]['File_Name']['Title']} is required.</p>";
	echo getAlerts($message, 'danger');
	echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';
			echo '$("#File_Name").addClass("is-invalid");';
		echo '});';
	echo '</script>';
	exit();
}

$_POST['File_Directory'] 	= $BXAF_CONFIG['Server'][1]['File_Directory'];
if (!file_exists("{$_POST['File_Directory']}/{$_POST['File_Name']}") || $_POST['File_Name'] == ''){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. The file ({$_POST['File_Directory']}/{$_POST['File_Name']}) does not exist.</p>";
	echo getAlerts($message, 'danger');
	echo '<script type="text/javascript">';
		echo '$(document).ready(function(){';
			echo '$("#File_Name").addClass("is-invalid");';
		echo '});';
	echo '</script>';
	exit();
}

$_POST['ID'] = createProject($_POST);


clearCache();

unset($_GET['ID'], $_GET['id']);


if ($_POST['ID'] <= 0){
	$message = "<p>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . " Error. The record cannot be saved. Please contact the system admin for details.</p>";
	echo getAlerts($message, 'danger');
	exit();
}



?>
<script type="text/javascript">
$(document).ready(function(){
	window.location = 'app_project_review.php?ID=<?php echo $_POST['ID']; ?>&saved=1';
});
</script>
