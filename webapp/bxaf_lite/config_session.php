<?php

// Session settings
// Comment out the following session settings if you have session setting already included somewhere else.

ini_set('zlib.output_compression','On');
ini_set('display_errors', '0');
ini_set('session.auto_start', '0');
ini_set('session.use_cookies', '1');
ini_set('session.gc_maxlifetime', 43200);
ini_set('register_globals', '0');
ini_set('auto_detect_line_endings', true);


session_set_cookie_params (0);
session_cache_limiter('nocache');


session_name('BIOINFORX_SESSION_' . md5(dirname(__FILE__)));
session_start();
error_reporting(0);


?>