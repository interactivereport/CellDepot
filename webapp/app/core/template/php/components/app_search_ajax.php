<?php


/*
$appObj = array();
$appObj['Table'] 		= $APP_CONFIG['TABLES']['MEASUREMENT'];
$appObj['Column'] 		= $_GET['column'];
$appObj['Component']	= $_GET['valueID'];
$appObj['Key']			= $_GET['key'];
$appObj['Row']			= $_GET['row'];
*/

if (!isset($appObj)){
	return;
}

$currentTable 	= $appObj['Table'];
$currentColumn 	= $appObj['Column'];
$componentID	= $appObj['Component'];
$cacheKey		= $appObj['Key'];
$row			= intval($appObj['Row']);

if ($cacheKey != ''){
	$cache = getRedisCache($cacheKey);
}


if (!isset($APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn])) exit();

if (true){
	$options = array();
	$options['Column'] 				= $currentColumn;
	$options['Table'] 				= $currentTable;
	$options['First_Option_Empty'] 	= $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['First_Option_Empty'];
	$options['Component_Only']		= true;
	$options['Search']				= true;
	$options['Value']				= $cache[$row]['Value'];
	$options['Value_Function']		= $APP_CONFIG['DICTIONARY'][$currentTable][$currentColumn]['Value_Function'];
	$options['ComponentID']			= $componentID;

	
	
	
	$currentFormObj = new HTML_Form($options);
	$results =  $currentFormObj->printHTMLForm();
	
	$results = str_replace("id='{$currentColumn}'", "id='{$componentID}'", $results);
	$results = str_replace("name='{$currentColumn}", "name='{$componentID}", $results);
	
	echo $results;
	
}

?>


<script type="text/javascript">

$(document).ready(function(){
	
	

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




</script>