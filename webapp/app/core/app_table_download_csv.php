<?php

include_once('config_init.php');

$dataArray	= getRedisCache($_GET['key']);

include('template/php/components/app_table_download_csv.php');

?>