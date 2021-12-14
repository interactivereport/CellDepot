<?php

if (true){
	
	$serverID = 1;
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-info') . 
			" Please make sure that the h5ad files are available here: <strong>{$BXAF_CONFIG['Server'][$serverID]['File_Directory']}</strong></div>";
			echo getAlerts($message, 'info');
		echo "</div>";
	echo "</div>";
}


if (true){
	$actions = array();
	$actions[] = "<a href='download/Project_Example.csv' target='_blank'>" . printFontAwesomeIcon('far fa-file-excel') . " Download Example File</a>";
	
	echo "<br/>";
	echo "<p>" . implode(' &nbsp; &nbsp; ', $actions) . "</p>";
	echo "<br/>";
}

echo "<form id='form_application' action='javascript:void(0);' method='post' role='form' class='form-horizontal' enctype='multipart/form-data' autocomplete='off'>";


	if (true){
		echo "<div class='row'>";
			echo "<div class='col-lg-12'>";

				$options = array();
				$options['Column'] 		= 'file_project';
				$options['Title'] 		= $BXAF_CONFIG_CUSTOM['MESSAGE'][($APP_CONFIG['TABLES']['PROJECT'])]['General']['Records'];
				$options['Function'] 	= 'printFile';
				$options['Label_Class']	= "col-12";
				$options['Value_Class']	= "col-12";
				$options['Required'] 	= true;
								
				echo "<div id='{$currentSQL}_Section'>";
					$currentFormObj = new HTML_Form($options);
					echo $currentFormObj->printHTMLForm();
				echo "</div>";

			echo "</div>";
		echo "</div>";
		echo "<br/>";
	}
	
	
	if (isAdminUser){
		echo "<div class='row'>";
			echo "<div class='col-lg-12'>";

				$options = array();
				$options['Column'] 		= 'Launch_Method';
				$options['Table'] 		= $currentTable;
				
								
				echo "<div id='{$currentSQL}_Section'>";
					$currentFormObj = new HTML_Form($options);
					echo $currentFormObj->printHTMLForm();
				echo "</div>";

			echo "</div>";
		echo "</div>";
		echo "<br/>";
	}
	
	
	if (isAdminUser){
		echo "<div class='row'>";
		echo "<div class='col-xl-12 col-lg-12 col-md-12'>";
			echo "<div class='form-group row'>";
			echo "<div class='form-check' style='padding-left:30px;'>";
				echo "<input class='form-check-input' type='checkbox' id='update_based_on_filename' name='update_based_on_filename' value='1'>";
				echo "<label class='form-check-label' for='update_based_on_filename'>";
					echo "&nbsp;Update project records based on dataset file name";
				echo "</label>";
			echo "</div>";
			echo "</div>";
		echo "</div>";
		echo "</div>";
	}
	
	
	
	if (true){
		
		
		echo "<div class='row'>";
			echo "<div class='col-lg-6'>";	
		
				echo "<div class='form-group row'>";
					echo "<div class='offset-0 col-6'>";
						echo "<input type='hidden' name='File_Server_ID' value='1'/>";
						echo "<button id='submitButton' class='btn btn-primary' type='submit'>" . printFontAwesomeIcon('far fa-save') . " Submit</button>";
						echo "&nbsp;<a href='{$PAGE['URL']}'>" . printFontAwesomeIcon('fas fa-redo-alt'). " Start Over</a>";
						echo "&nbsp;<span id='busySection' class='startHidden'>" . printFontAwesomeIcon('fas fa-spinner fa-spin'). "</span>";
					echo "</div>";
				echo "</div>";
			
			echo "</div>";
		echo "</div>";
		
	}
	

echo "</form>";

echo "<div id='feedbackSection' class='startHidden'></div>";

?>

<script type="text/javascript">

$(document).ready(function(){
	$('#form_application').ajaxForm({ 
        target: '#feedbackSection',
        url: '<?php echo $PAGE['EXE']; ?>',
        type: 'post',
		beforeSubmit: beforeSubmit,
        success: showResponse
    });
	
});



function beforeSubmit() {
	$('#feedbackSection').empty();
	$('#feedbackSection').hide();
	$('#busySection').show();
	return true;
}


function showResponse(responseText, statusText) {
	responseText = $.trim(responseText);

	$('#busySection').hide();
	$('#feedbackSection').html(responseText);
	$('#feedbackSection').show();
	
	$('html,body').animate({
		scrollTop: $('#feedbackSection').offset().top
	});
	
	return true;

}


</script>