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


updateProject($_POST, $_POST['ID']);


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
