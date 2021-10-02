<?php

echo "<br/>";

$tableContent = array();
$tableContent['Header']['Gene'] 	= 'Gene';
$tableContent['Header']['Expression'] 	= 'Gene Expression';
$otherOption = array('Table_ID' => 'geneExpressionTable');

foreach($dataArray['File_h5ad_info']['genes'] as $gene => $expression){
	
	$tableContent['Body'][$gene]['Value']['Gene'] = $gene;
	$tableContent['Body'][$gene]['Value']['Expression'] = $expression;

	
}

echo "<div class='d-flex p-4'>";
	echo printTableHTML($tableContent, 1, 1, 0, '', 0, $otherOption);
echo "</div>";


?>


<script type="text/javascript">
$(document).ready(function(){

	
	$('#geneExpressionTable').DataTable({
		"dom": '<<l><f><t><i><p>>',
		"processing": 	true,
		"pageLength":   50,
		"autoWidth":   false,
		"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
		"order": [[ 0, "asc" ]],
		"columnDefs": [
		  { type: 'natural', targets: 0 },
		],
		"oLanguage": {
		  "sLengthMenu": "Display _MENU_ records per page",
		},
		
		"language": {
      		"info": "Showing _START_ to _END_ of _TOTAL_ records",
	    },
    });
	
	
	$('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });
	
	
	
	
});
</script>