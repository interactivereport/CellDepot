<?php

function getRedisCache($key = ''){
	
	global $APP_CONFIG;
	
	if (!$APP_CONFIG['REDIS_ENABLE']) return false;
	
	if ($APP_CONFIG['CACHE_OFF']) return false;
	
	$key = trim($key);
	
	if ($key == '') return false;
	
	$redisKey = getRedisKey($key);
	
	$results = $APP_CONFIG['REDIS_CONN']->get($redisKey);

	return $results;
	
}

function getRedisKey($key = ''){
	
	global $APP_CONFIG, $BXAF_CONFIG;
	
	$key = str_replace(' ', '_', "{$BXAF_CONFIG['REDIS_PREFIX']}::{$key}");

	return $key;
	
}

function putRedisCache($dataArray = array(), $expire = 0){
	
	global $APP_CONFIG;
	
	if (!$APP_CONFIG['REDIS_ENABLE']) return false;
	
	if ($APP_CONFIG['CACHE_OFF']) return false;
	
	$expire = intval(abs($expire));
	
	if (($expire == 1) && ($APP_CONFIG['APP']['Cache_Expiration_Length'] > 0)){
		$expire = $APP_CONFIG['APP']['Cache_Expiration_Length'];
	}
	
	if (array_size($dataArray) <= 0) return false;
	
	foreach($dataArray as $key => $value){
		$key = trim($key);
		if ($key == '') continue;
		if ($value == FALSE) continue;
	
		$redisKey = getRedisKey($key);
		$candidates[$redisKey] = $value;
	}
	
	if (array_size($candidates) > 0){
		
		if ($expire == 0){
			return $APP_CONFIG['REDIS_CONN']->mset($candidates);
		} else {
			foreach($candidates as $key => $value){
				$APP_CONFIG['REDIS_CONN']->set($key, $value);
				$APP_CONFIG['REDIS_CONN']->setTimeout($key, $expire);
			}
			
			return true;
		}
		
	} else {
		return false;	
	}
}


function clearCache(){
	
	global $APP_CONFIG, $APP_CACHE, $BXAF_CONFIG;
	
	unset($APP_CACHE['getSQL_Data']);
	
	if (!$APP_CONFIG['REDIS_ENABLE']) return false;

	$cmd = "redis-cli KEYS '{$BXAF_CONFIG['REDIS_PREFIX']}::*' | xargs redis-cli DEL";

	
	shell_exec($cmd);
	
	return true;

}

function turnOffCache(){
	
	global $APP_CONFIG;
	
	$APP_CONFIG['CACHE_OFF'] = 1;
	
	return true;
	
}

function turnOnCache(){
	
	global $APP_CONFIG;
	
	$APP_CONFIG['CACHE_OFF'] = 0;
	
	return true;
	
}

?>