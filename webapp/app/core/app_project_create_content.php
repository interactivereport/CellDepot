<?php


echo "<form id='form_application' action='javascript:void(0);' method='post' role='form' class='form-horizontal' enctype='multipart/form-data' autocomplete='off'>";

	foreach($BXAF_CONFIG['Project_Layout']['Create']['Columns'] as $tempKey => $currentColumn){
		
				
			$options = array();
			$options['Column'] 				= $currentColumn;
			$options['Table'] 				= $currentTable;
			$options['First_Option_Empty'] 	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['First_Option_Empty'];
			
			if ($ID > 0){
				$options['Value']	= $dataArray[$currentColumn];	
			} else {
				$options['Value']	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Default'];	
			}
							
			echo "<div id='{$currentColumn}_Section'>";
				$currentFormObj = new HTML_Form($options);
				echo $currentFormObj->printHTMLForm();
			echo "</div>";
				
	}



	//Buttons
	if (true){
		echo "<br/>";
		echo "<div class='row'>";
			echo "<div class='col-lg-6'>";	
		
				echo "<div class='form-group row'>";
					echo "<div class='offset-0 col-6'>";
					
						if ($ID > 0){
							echo "<input type='hidden' name='ID' value='{$ID}'/>";
						}
						
						
						echo "<button id='submitButton' class='btn btn-primary' type='submit'>" . printFontAwesomeIcon('far fa-save') . " Save</button>";
						echo "&nbsp;<span id='busySection' class='startHidden'>" . printFontAwesomeIcon('fas fa-spinner fa-spin'). "</span>";
					echo "</div>";
				echo "</div>";
			
			echo "</div>";
		echo "</div>";
		
	}

echo "</form>";

echo "<div id='ajaxSection' class='startHidden'></div>";
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
	

	$('.selectpicker').selectpicker({
			
	});
	
	$(".select2_Tag").select2({
		tags: true,
		tokenSeparators: [',', ', ']
	});
	
	
	$(".select2_Editable").select2({
		
		tags: true,
		
		width: '100%',

		createTag: function (params) {
			return {
				id: params.term,
				text: params.term,
				newOption: true
			}
		},
		
		templateResult: function (data) {
			var $result = $("<span></span>");
			
			$result.text(data.text);
			
			if (data.newOption) {
				$result.append(" <em>(new)</em>");
			}
			
			return $result;
		}
		  
	});
	
	$(".select2_KeyValue_Large").select2({
		width: '100%',
//		minimumInputLength: 3,
	});
	
	
});


function beforeSubmit() {
	$('#feedbackSection').empty();
	$('#feedbackSection').hide();
	$('#busySection').show();
	$('input').removeClass('is-invalid');
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