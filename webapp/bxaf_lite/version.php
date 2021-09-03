<?php

$version['primary'] 	= '4';
$version['secondary'] 	= '00';
$version['date']        = '2021-02-11';
$version['time']        = '00:00:00';

$version['version'] 	= $version['primary'] . $version['secondary'];
$version['datetime'] 	= $version['date'] . ' ' . $version['time'];

if ($_GET['output']) echo json_encode($version);

/*
2018-02-15 16:00:00	version=4.00
	1) Major upgrade with bootstrap 4 and font-awesome 5.
	2) Added file management tool for both private and public file sharing.
	3) Miscellaneous minor improvement and bug fixes.
2017-05-11 16:00:00	version=3.16
	1) Updated Settings in the entire system (/bxaf_setup/config.php, /bxaf_lite/login.php, /bxaf_lite/user_signup.php): change BXAF_SIGNUP_PASSWORD to BXAF_USER_DEFAULT_PASSWORD, change BXAF_USER_RPOFILE to BXAF_USER_PROFILE
	2) Updated system to support both with and without settings of BXAF_USER_DEFAULT_PASSWORD.
	3) Updated /bxaf_lite/page_left.php to use $BXAF_CONFIG['BXAF_URL'] as reference URL, therefore, all URLs in $BXAF_CONFIG['LEFT_MENU_ITEMS'] should be full URL or relative to $BXAF_CONFIG['BXAF_URL'].
	4) Added functions to record login logs.
	5) Files updated: /bxaf_setup/config.php (BXAF_SIGNUP_PASSWORD to BXAF_USER_DEFAULT_PASSWORD), /bxaf_lite/config_functions.php (added bxaf_random_password(), bxaf_login_log()),  /bxaf_lite/login.php, /bxaf_lite/login.php, /bxaf_lite/user_signup.php, /bxaf_lite/user_profile.php, and /bxaf_lite/page_left.php
2017-04-12 22:00:00	version=3.15
	1) Added /bxaf_lite/bxfiles/: set up file management target folder in /bxaf_setup/config.php with this variable: $BXAF_CONFIG_CUSTOM['BXAF_BXFILES_SUBDIR']. To set up file system readonly, set $BXAF_CONFIG_CUSTOM['BXFILES_READONLY'] = true;
	2) Supported shortened URL with http://x2y.me/, e.g., http://x2y.me/Ad0P?f=I-Gw2acjckri_ogSTv0hAgqFQseRD1y1goxCHqG3RrI&u=1000002 is the same as http://analysis5.bxgenomics.com/bxaf_lite_demo/bxaf_lite/bxfiles/f.php?f=I-Gw2acjckri_ogSTv0hAgqFQseRD1y1goxCHqG3RrI&u=1000002
2017-04-07 22:00:00	version=3.14
	Updated /bxaf_lite/config.php, /bxaf_lite/user_profile.php, /bxaf_lite/config_functions.php, /bxaf_lite/js/page.js, /bxaf_lite/user_admin_exe.php, /bxaf_lite/user_signup.php, /bxaf_lite/login.php, /bxaf_lite/page_menu.php, /bxaf_lite/config_functions.php, /bxaf_lite/js/page.js, /bxaf_lite/user_admin_exe.php:
	1) If no account is found during login, forward to sign up page.
	2) On login page, added link to request resetting password.
	3) Show user name on menu after login; Click the link to view profile details.
	4) Allow to edit profile after login
2017-03-22 15:00:00	version=3.13
	Updated /bxaf_lite/user_admin.php and /bxaf_lite/user_admin_exe.php to separate Login Name and E-mail. Change "Save" to "Confirm".
	Updated /bxaf_lite/version.php
	Updated /bxaf_lite/config_functions.php: revised bxaf_get_welcome_email()
2017-03-18 14:00:00	version=3.12
	/bxaf_lite/config.php: Added $BXAF_CONFIG['BXAF_PAGE_SPLIT'], $BXAF_CONFIG['BXAF_PAGE_CSS_LEFT_FIXED_WIDTH'] to enable fixed left panel. Added isset() to many variables to avoid error reporting.
	/bxaf_lite/page_header.php, /bxaf_lite/page_menu.php, /app/example4.php: Add codes to enable and give example how to set up fixed left panel
2017-03-18 11:00:00	version=3.11
	/bxaf_lite/version.php: return json_ecode(array('primary'='', 'second'='', 'version'='', 'date'='', 'time'='' 'datetime'=''))
2017-03-17 18:00:00
	/bxaf_lite/config.php: Added $BXAF_CONFIG['BXAF_SETUP_DIR'], $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] (default signup password)
	/bxaf_lite/user_signup.php: updated the content and takes $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] if defined
	/bxaf_setup/config.php: Added: $BXAF_CONFIG_CUSTOM['BXAF_USER_DEFAULT_PASSWORD'] = 'xxx';
2017-03-15 20:33:00 updated /bxaf_lite/config.php to auto-detect folder and URL settings. No need to add custom settings in /bxaf_setup/config.php
2017-03-08 11:33:00 updated /bxaf_lite/page_left.php to auto-detect links, added auto-open and auto-close function.
*/
?>