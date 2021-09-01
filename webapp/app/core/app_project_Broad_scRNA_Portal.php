<?php

include_once('config_init.php');

$projectInfo = getProjectByID($ID);

$URL = $projectInfo['URL'];
$HTML = $projectInfo['URL_Body'];



$removeKeywords = array();
$removeKeywords[] = 'https://us.jsagent.tcell.insight.rapid7.com/tcellagent.min.js';
$removeKeywords[] = 'https://us.agent.tcell.insight.rapid7.com/api/v1';

//$removeKeywords[] = 'https://www.google-analytics.com/analytics.js';


$HTML = str_replace($removeKeywords, '', $HTML);

$HTML = str_replace('id="single-cell-navbar"', 'id="single-cell-navbar" style="display:none;"', $HTML);

$HTML = str_replace('src="/single_cell/', 'src="https://singlecell.broadinstitute.org/single_cell/', $HTML);
$HTML = str_replace('href="/single_cell/', 'href="https://singlecell.broadinstitute.org/single_cell/', $HTML);


$HTML = str_replace('<a href="#study-visualize" data-toggle="tab">', "<a href='{$URL}' target='_blank'>", $HTML);
$HTML = str_replace('<a href="#study-download"', "<a href='{$URL}' target='_blank'", $HTML);

$HTML = str_replace('https://www.google-analytics.com/analytics.js', '/celldepot/bxaf_lite/library/fontawesome5/5.15.2/js/all.min.js', $HTML);

$HTML = str_replace('study-nav disabled', 'study=nav', $HTML);

$HTML = str_replace('<h4 class="help-block text-center">Please sign in using a Google account</h4>', 'This project is not available.', $HTML);
// <p class="text-center"><a class="btn btn-lg btn-danger" id="google-auth" rel="nofollow" data-method="post" href="https://singlecell.broadinstitute.org/single_cell/users/auth/google_oauth2">Sign in with <span class='fab fa-google'></span></a></p

//$HTML = str_replace('<a', '<aa', $HTML);
//$HTML = str_replace('</a>', '</aa>', $HTML); 

$HTML = str_replace('<a href=', "<a href_disabled=", $HTML);




echo $HTML;


?>