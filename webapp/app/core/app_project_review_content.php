<?php


if (array_size($projectInfo) <= 0){
	
	echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<div>" . printFontAwesomeIcon('fas fa-exclamation-triangle text-warning') . " The record does not exist. Please verify your URL and try again.</div>";
			echo getAlerts($message, 'warning');
		echo "</div>";
	echo "</div>";
	
	return;
	
} else {
	
	
	
	
	if ($_GET['saved']){
		echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-success') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Update_Message']}</div>";
			echo getAlerts($message, 'success ');
		echo "</div>";
		echo "</div>";	
	} elseif ($_GET['started']){
		echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-success') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene_Started_Message']}</div>";
			echo getAlerts($message, 'success ');
		echo "</div>";
		echo "</div>";	
	} elseif ($_GET['stopped']){
		echo "<div class='row'>";
		echo "<div class='col-12'>";
			$message = "<div>" . printFontAwesomeIcon('fas fa-info-circle text-success') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene_Stopped_Message']}</div>";
			echo getAlerts($message, 'success ');
		echo "</div>";
		echo "</div>";	
	}
	
	
	
		

	unset($actions);
	echo "<div class='row'>";
		echo "<div class='col-12'>";

			if (!isGuestUser()){
				$URL = "app_project_update.php?ID={$ID}";
				$actions[] = "<a href='{$URL}'>" . printFontAwesomeIcon('far fa-edit') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Update_Record']}</a>";
			}
			
			if (true){
				$URL = "app_project_launcher.php?ID={$ID}";
				$actions[] = "<a href='{$URL}'>" . printFontAwesomeIcon('fas fa-braille') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Cellxgene']}</a>";
			}
			
			if (isAdminUser()){
				$actions[] = "<a href='javascript:void(0);' data-toggle='modal' data-target='#deleteFromRecordModal'>" . printFontAwesomeIcon('far fa-trash-alt') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Delete_Record']}</a>";
			}
			
			if (isAdminUser() && $projectInfo_Raw['Launch_Method']){
				
				if (isCellxGeneRunning($ID)){
					$URL = "app_project_cellxgene_stop.php?ID={$ID}";
					$actions[] = "<a href='{$URL}'>" . printFontAwesomeIcon('far fa-stop-circle') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Stop_Cellxgene']}</a>";
				} else {
					$URL = "app_project_cellxgene_start.php?ID={$ID}";
					$actions[] = "<a href='{$URL}'>" . printFontAwesomeIcon('far fa-play-circle') . " {$BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Start_Cellxgene']}</a>";
				}
			}
			
			
			
			
			echo "<p>" . implode("&nbsp; &nbsp; &nbsp;", $actions) . "</p>";

		echo "</div>";
	echo "</div>";

	echo "<div id='tabs'>";
	
		echo "<ul class='nav nav-tabs' role='tablist'>";
			if (true){
				$display = $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Record'];
				echo "<li class='nav-item'>
						<a class='nav-link active' href='#Summary' role='tab' data-toggle='tab'>{$display}</a>
					  </li>";
			}
			
			if ($projectInfo['File_h5ad_status']){
				$display = $BXAF_CONFIG['MESSAGE'][$currentTable]['Column']['Project_Groups']['Title'];
				echo "<li class='nav-item'>
						<a class='nav-link' href='#Project_Groups' role='tab' data-toggle='tab'>{$display}</a>
					  </li>";
			}
			
			
			if (array_size($projectInfo['File_h5ad_info']['genes']) > 0){
				$display = $BXAF_CONFIG['MESSAGE'][$currentTable]['Column']['Genes']['Title'];
				echo "<li class='nav-item'>
						<a class='nav-link' href='#Genes' role='tab' data-toggle='tab'>{$display}</a>
					  </li>";
			}
			
			
			
			
			if ($projectInfo['URL_Tab_File'] != ''){
				$display = $projectInfo['URL_Tab_Title'];
				echo "<li class='nav-item'>
						<a class='nav-link' href='#Preview' role='tab' data-toggle='tab'>{$display}</a>
					  </li>";
				
			}
			
			
			if (isAdminUser()){
				$display = $BXAF_CONFIG['MESSAGE'][$currentTable]['General']['Technical_Details'];
				echo "<li class='nav-item'>
						<a class='nav-link' href='#technical_details' role='tab' data-toggle='tab'>{$display}</a>
					  </li>";
				
			}
			
			
			$auditTrailCount = array_size($auditTrails);
			if ($auditTrailCount > 0){
				echo "<li class='nav-item'>
						<a class='nav-link' href='#AuditTrail' role='tab' data-toggle='tab'>Audit Trail</a>
					  </li>";	
			}
			
			
			
		echo "</ul>";
		
		
		
		echo "<div class='tab-content'>";
			if (true){
				echo "<div role='tabpanel' id='Summary' class='tab-pane fade in active show'>";
					$dataArray = $projectInfo;
					include('app_project_review_content_tab_summary.php');
				echo "</div>";
			}
			
			if ($projectInfo['File_h5ad_status']){
				echo "<div role='tabpanel' id='Project_Groups' class='tab-pane fade in'>";
					$dataArray = $projectInfo;
					include('app_project_review_content_tab_project_groups.php');
				echo "</div>";
			}
			
			if (array_size($projectInfo['File_h5ad_info']['genes']) > 0){
				echo "<div role='tabpanel' id='Genes' class='tab-pane fade in'>";
					$dataArray = $projectInfo;
					include('app_project_review_content_tab_genes.php');
				echo "</div>";
			}
			
			
			if ($projectInfo['URL_Tab_File'] != ''){
				echo "<div role='tabpanel' id='Preview' class='tab-pane fade in'>";
					$dataArray = $projectInfo;
					include($projectInfo['URL_Tab_File']);
				echo "</div>";
			}
			
			if (isAdminUser()){
				echo "<div role='tabpanel' id='technical_details' class='tab-pane fade in'>";
					$dataArray = $projectInfo;
					include('app_project_review_content_tab_technical_details.php');
				echo "</div>";
				
			}
			
			
		
			if ($auditTrailCount > 0){
				echo "<div role='tabpanel' id='AuditTrail' class='tab-pane fade in'>";
					//$auditTrails
					include('template/php/components/app_audit_trail_tab.php');
				echo "</div>";
			}
			
			
			
		
			
			
		echo "</div>";
		
	echo "</div>";
		

}

if (isAdminUser()){
	$modalID 	= 'deleteFromRecordModal';
	$modalTitle	= 'Delete Record';
	
	$modalBody	= "<p>You are going to delete this record. Are you sure?</p>";
	
	
	$modalButtonTextAction = 'Yes';
	$modalButtonTextCancel = 'No';
	$modalButtonActionClass = 'deleteFromRecordTrigger btn-danger';
	
	echo printConfirmation($modalID, $modalTitle, $modalBody, $modalButtonTextAction, $modalButtonTextCancel, $modalButtonActionClass);
}





?>
<script type="text/javascript">

$(document).ready(function(){
	
	<?php if ($_GET['tab'] != ''){ ?>
		$('.nav-tabs a[href="#<?php echo $_GET['tab']; ?>"]').tab('show');
	<?php } ?>
	
	
	<?php if (isAdminUser()){ ?>
	$(document).on('click', '.deleteFromRecordTrigger', function(){
		
		$('#deleteFromTableModal').modal('hide');
			
		window.location = 'app_project_delete.php?source=GET&ID=<?php echo $ID; ?>&return=1';
	});
	<?php } ?>
	
	
	
});
</script>