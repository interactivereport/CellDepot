<?php

function initialize(){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$APP_CONFIG['StartTime'] 			= microtime(true);
	
	
	$APP_CONFIG['APP_CURRENT_DIR'] 		= dirname(__FILE__);
	$APP_CONFIG['APP_CURRENT_DIR'] 		= explode('/', $APP_CONFIG['APP_CURRENT_DIR']);
	$APP_CONFIG['APP_CURRENT_DIR'] 		= array_pop($APP_CONFIG['APP_CURRENT_DIR']);
	$APP_CONFIG['APP_DIR'] 				= __DIR__ . '/';
	$APP_CONFIG['APP_URL'] 				= "//{$_SERVER['HTTP_HOST']}" . dirname($_SERVER['REQUEST_URI']) . '/';
	
	$BXAF_CONFIG['BXAF_PAGE_TITLE'] 	= '';
	$BXAF_CONFIG['BXAF_PAGE_CSS_RIGHT'] = 'col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex align-content-between flex-wrap';
	$BXAF_CONFIG['BXAF_PAGE_SPLIT']		= false;
	
	if (true){
		try {
			$APP_CONFIG['SQL_USER_CONN'] = new SQLite3($BXAF_CONFIG['BXAF_DB_NAME']);
		} catch (Exception $e) {
			die( $e->getMessage() );
		}
	}
	
	if (true){
		try {
			$APP_CONFIG['SQL_DATA_CONN'] = bxaf_get_app_db_connection();
		} catch (Exception $e) {
			die( $e->getMessage() );
		}
	}
	
	if ($BXAF_CONFIG['REDIS_ENABLE']){
		try {
			$APP_CONFIG['REDIS_CONN'] 		= new Redis();
			$APP_CONFIG['REDIS_CONN']->pconnect('localhost', 6379);
			$APP_CONFIG['REDIS_CONN']->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
			$APP_CONFIG['REDIS_ENABLE'] 	= true;
		} catch(Exception $e){
			$APP_CONFIG['REDIS_CONN'] 		= FALSE;
			$APP_CONFIG['REDIS_ENABLE'] 	= FALSE;
			$BXAF_CONFIG['REDIS_ENABLE'] 	= FALSE;
		}
	}
	
	load_common_config();
	
	$allSettings = get_all_user_settings();
	
	foreach($allSettings as $tempKey => $tempValue){
		$BXAF_CONFIG['SETTINGS'][$tempKey] = $tempValue;	
	}
	
	
	
	return true;
}




?>