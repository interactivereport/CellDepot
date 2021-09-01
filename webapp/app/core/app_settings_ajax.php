<?php

include_once('config_init.php');

if ($_GET['key'] != ''){
	update_user_settings($_GET['key'], $_GET['value']);
}

?>