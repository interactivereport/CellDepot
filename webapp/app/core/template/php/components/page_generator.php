<?php

//Variables
/*
$PAGE['Title']
$PAGE['Body']
*/

$BXAF_CONFIG['BXAF_PAGE_TITLE'] = $PAGE['Title'];

include('page_generator_header.php');

echo "<body>";
	if (file_exists($BXAF_CONFIG['BXAF_PAGE_MENU'])){
		include_once($BXAF_CONFIG['BXAF_PAGE_MENU']);
	}

	echo "<div id='bxaf_page_content' class='row no-gutters h-100'>";
		if (file_exists($BXAF_CONFIG['BXAF_PAGE_LEFT'])){
			include_once($BXAF_CONFIG['BXAF_PAGE_LEFT']);
		}

		echo "<div id='bxaf_page_right' class='{$BXAF_CONFIG['BXAF_PAGE_CSS_RIGHT']}'>";

			echo "<div id='bxaf_page_right_content' class='w-100 xp-2'>";
            
            	echo "<div class='container-fluid'>";
					if (($PAGE['Header'] != '') ||($PAGE['Header2'] != '')){
						echo "<br/>";
						echo "<div class='row'>";
							echo "<div class='col-12'>";
							
								if ($PAGE['Header'] != ''){
									echo "<div><h3>{$PAGE['Header']}</h3></div>";
								}
								
								if ($PAGE['Header2'] != ''){
									echo "<div>{$PAGE['Header2']}</div>";
								}
								
							echo "</div>";
						echo "</div>";
						
						echo "<hr/>";	
					
					}
					
					

					if (is_file($PAGE['Body'])){
						include($PAGE['Body']);
					} else {
						$message = printFontAwesomeIcon('fas fa-exclamation-triangle text-danger') . ' The $PAGE[Body] variable is empty. Please verify your code and try again.';
						echo getAlerts($message, 'warning');
					}
                   
                echo "</div>";
				
			echo "</div>";

		    if (file_exists($BXAF_CONFIG['BXAF_PAGE_FOOTER'])){
				include_once($BXAF_CONFIG['BXAF_PAGE_FOOTER']);
			}

		echo "</div>";

	echo "</div>";


echo "</body>";
echo "</html>";

?>