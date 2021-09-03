<?php

echo "<!DOCTYPE html>\n";
echo "<html lang='en'>\n";
echo "<head>\n";
    

	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery2']){
		echo "<script type='text/javascript' src='./template/js/jquery/2.1.4/jquery.min.js'></script>\n";
	} elseif ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery3']){
		echo "<script type='text/javascript' src='./template/js/jquery/3.4.1/jquery.min.js'></script>\n";
	} else {
		echo "<script type='text/javascript' src='./template/js/jquery/1.11.3/jquery.min.js'></script>\n";
		echo "<script type='text/javascript' src='./template/js/jquery-migrate/jquery-migrate-1.2.1.min.js'></script>\n";	
	}
	

	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapSelect'] || $BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['App_Chat']){
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Popper'] = true;
	}


	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Popper']){
		echo "<script type='text/javascript' src='./template/js/popper/1.14.3/popper.min.js'></script>\n";
	}

	if (true){
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery'] 			= false;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['bootstrap'] 		= true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['font-awesome'] 		= true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['tether'] 			= true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['bootbox'] 			= true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery-md5']		= false;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery-form']		= true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery-tabledit']	= false;

		include_once($BXAF_CONFIG['BXAF_PAGE_HEADER']);
	}

    
    //Application
	if (true){
	    echo "<link type='text/css' rel='stylesheet' href='./template/css/style.css'>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['style']){
		echo "<link type='text/css' rel='stylesheet' href='app_style.css'>\n";
	}
    
   
	//js-md5
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['js-md5']){
		echo "<script type='text/javascript' src='./template/js/js-md5/0.7.3/js-md5.min.js'></script>\n";
	}
	
	//jqDoubleScroll
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jqDoubleScroll']){
		echo "<script type='text/javascript' src='./template/js/jqDoubleScroll/0.5/jquery.doubleScroll.js'></script>\n";
	}
	
	
    //d3 v3
    if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['d3v3']){
        echo "
            <link type='text/css' rel='stylesheet' href='./template/js/dc/2.2.2/dc.min.css'>
            
            <script type='text/javascript' src='./template/js/d3/d3-3.5.17.js'></script>
            <script type='text/javascript' src='./template/js/crossfilter/crossfilter-1.4.6.js'></script>
            
            <script type='text/javascript' src='./template/js/dc/2.2.2/dc.min.js'></script>
        ";
    }

	//d3 v4
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['d3v4']){
		echo "
			<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/d3/4.9.1/d3.min.js'></script>
			<script type='text/javascript' src='./template/js/d3/d3.tip.stable.js'></script>
		";
	}

	//d3 v5
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['d3v5']){
		
		echo "
			<link type='text/css' rel='stylesheet' href='./template/js/dc/4.2.3/dc.css'>

			<script type='text/javascript' src='./template/js/d3/d3-5.15.0.js'></script>
			<script type='text/javascript' src='./template/js/crossfilter/crossfilter-1.5.2.js'></script>
			
			<script type='text/javascript' src='./template/js/dc/4.2.3/dc.js'></script>
			
		";
	}
		
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['d3v5-DC']){
		echo "
			<link type='text/css' rel='stylesheet' href='./template/js/dc/4.2.3/dc.css'>
			
			<script type='text/javascript' src='./template/js/d3/d3-5.15.0-dc.js'></script>
			<script type='text/javascript' src='./template/js/crossfilter/crossfilter-1.5.2.js'></script>
			
			<script type='text/javascript' src='./template/js/dc/4.2.3/dc.js'></script>
		";
	}
 
	//DataTables
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['DataTable']){
		echo "<link rel='stylesheet' type='text/css' href='./template/js/dataTables/core/1.10.24/dataTables.bootstrap4.min.css'>";
		echo "<script type='text/javascript'          src='./template/js/dataTables/core/1.10.24/jquery.dataTables.min.js'></script>";
		echo "<script type='text/javascript'          src='./template/js/dataTables/core/1.10.24/dataTables.bootstrap4.min.js'></script>";
		echo "<script type='text/javascript'          src='./template/js/dataTables/natural/1.0.0/natural.js'></script>";
	}

	//DataTables-Buttons: Add Copy/CSV	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['DataTable-Buttons']){
		echo "<link rel='stylesheet' type='text/css' href='./template/js/dataTables/buttons/1.7.0/buttons.dataTables.min.css'>";
		echo "<script type='text/javascript'          src='./template/js/dataTables/buttons/1.7.0/dataTables.buttons.min.js'></script>";
		echo "<script type='text/javascript'          src='./template/js/dataTables/buttons/1.7.0/buttons.html5.min.js'></script>";
	}
	
	
	
		
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapSelect']){
		echo "<link rel='stylesheet' href='./template/js/bootstrap-select/1.13.14/bootstrap-select.min.css'/>\n";
		echo "<script type='text/javascript' src='./template/js/bootstrap-select/1.13.14/bootstrap-select.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapDatePicker']){
		echo "<link rel='stylesheet' href='./template/js/boostrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css'/>\n";
		echo "<script type='text/javascript' src='./template/js/boostrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Select2']){
		echo "<link rel='stylesheet' href='./template/js/select2/4.0.13/select2.min.css'/>\n";
		echo "<script type='text/javascript' src='./template/js/select2/4.0.13/select2.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jsTree']){
		echo "<link rel='stylesheet' href='./template/js/jsTree/3.10/dist/themes/default/style.min.css'/>\n";
		echo "<script type='text/javascript' src='./template/js/jsTree/3.10/dist/jstree.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BootstrapSlider']){
		echo "<link rel='stylesheet' href='./template/js/boostrap-slider/11.0.2/bootstrap-slider.min.css'/>\n";
		echo "<script type='text/javascript' src='./template/js/boostrap-slider/11.0.2/bootstrap-slider.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['BoostrapExtension']){
		echo "<link rel='stylesheet' href='./template/js/bootstrap-extension/4.6.1/bootstrap-extension.min.css'></script>\n";
		echo "<script src='./template/js/bootstrap-extension/4.6.1/bootstrap-extension.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['TinyMCE']){
		echo "<script src='./template/js/tinymce/5.7.0/tinymce.min.js'></script>\n";
		echo "<script src='./template/js/tinymce/5.7.0/jquery.tinymce.min.js'></script>\n";
	}
	
	if ($BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['Venn']){
		echo "<script type='text/javascript' src='./template/js/d3/d3-3.5.6.js'></script>";
		echo "<script src='./template/js/venn/0.2/venn.js'></script>\n";
	}
   
   	if (true){ 
		echo '<!--[if lt IE 9]>' . "\n";
        echo "<script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>\n";
        echo "<script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>\n";
   		echo '<![endif]-->' . "\n";
	}

echo "</head>";

?>