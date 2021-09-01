<?php

include_once('config_init.php');

$refProject = getProjectByID($_GET['Project']);
	
if (!$refProject['Record']['Is_Project']){
	exit();
}

?>
<script type="text/javascript">

$(document).ready(function(){
	
	<?php foreach($BXAF_CONFIG['Project_Parameters']['Project_Copy_To_Activity'] as $tempKey => $currentSQL){ ?>
		
		$('#<?php echo $currentSQL; ?>').val("<?php echo sanitizeJavaScriptValue($refProject['Record'][$currentSQL]); ?>");
		
	<?php } ?>
	
	$('.selectpicker').selectpicker('render');
	
});
</script>