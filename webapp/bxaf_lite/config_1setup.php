<?php

//Database for user accounts: sqlite or mysql
$BXAF_CONFIG['BXAF_DB_DRIVER'] 		= 'sqlite';
//The full path to the SQLlite database file
$BXAF_CONFIG['BXAF_DB_NAME']	 	= dirname(dirname(__FILE__)) . "/bxaf_setup/users.db";

// If using MySQL, uncomment and update the following settings
// $BXAF_CONFIG['BXAF_DB_DRIVER'] 		= 'mysql';
// $BXAF_CONFIG['BXAF_DB_SERVER'] 		= 'localhost';
// $BXAF_CONFIG['BXAF_DB_NAME'] 		= 'db_bxaf_lite';
// $BXAF_CONFIG['BXAF_DB_USER'] 		= 'db_bxaf_lite';
// $BXAF_CONFIG['BXAF_DB_PASSWORD'] 	= 'db_bxaf_lite';

$BXAF_CONFIG['TBL_BXAF_LOGIN'] 	= 'tbl_bxaf_login';
$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] 	= 'tbl_bxaf_login_log';

// Include Custom Settings Here
include_once(dirname(dirname(__FILE__)) . "/bxaf_setup/config.php");

// Overwrite settings with custom values
if(is_array($BXAF_CONFIG_CUSTOM) && count($BXAF_CONFIG_CUSTOM) > 0){
	foreach($BXAF_CONFIG_CUSTOM as $key=>$val) $BXAF_CONFIG[$key] = $val;
}

// Include system functions
include_once(dirname(__FILE__) . "/config_functions.php");

if(! in_array($BXAF_CONFIG['BXAF_DB_DRIVER'], array('sqlite', 'mysql'))){
	die("Database driver is not correct. Please report to the system administrator.");
}

else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

	$BXAF_CONN = bxaf_get_user_db_connection();

	$sql = "SELECT ID FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . '';
	$ret = intval($BXAF_CONN->querySingle($sql));

	if ($ret <= 0){

		$sql = "CREATE TABLE {$BXAF_CONFIG['TBL_BXAF_LOGIN']} (
				ID INTEGER PRIMARY KEY AUTOINCREMENT,
				PID INTEGER,
				Login_Name varchar(255) NOT NULL,
				Password varchar(255) NOT NULL,
				Name varchar(255) NOT NULL,
				Category varchar(255),
				First_Name varchar(255),
				Last_Name varchar(255),
				Email varchar(255),
				Phone varchar(255),
				Instant_Message varchar(255),
				Website varchar(255),
				Address varchar(255),
				Organization varchar(255),
				Department varchar(255),
				Subdivision varchar(255),
				Group_Name varchar(255),
				Group_Role varchar(255),
				Status varchar(255),
				Status_Time DATETIME,
				Notes TEXT,
				bxafField1 TEXT,
				bxafField2 TEXT,
				bxafField3 TEXT,
				bxafField4 TEXT,
				bxafField5 TEXT,
				bxafField6 TEXT,
				bxafField7 TEXT,
				bxafField8 TEXT,
				bxafField9 TEXT,
				bxafStatus TINYINT
			)
		";

		$ret = $BXAF_CONN->exec($sql);
		if(! $ret) die("Database table {$BXAF_CONFIG['TBL_BXAF_LOGIN']} can not be created.");

		$sql = "INSERT INTO " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " (ID, Name, Login_Name, Password, First_Name, Last_Name, Email) VALUES (1000001, 'System Admin', 'admin', '" . addslashes(md5($BXAF_CONFIG['BXAF_ADMIN_PASSWORD'])) . "', 'System', 'Admin', 'info@bioinforx.com')";
		$ret = $BXAF_CONN->exec($sql);
		if(! $ret) die("Admin account can not be created.");

		$sql = "INSERT INTO " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " (ID, Name, Login_Name, Password, First_Name, Last_Name, Email) VALUES (1000002, 'System Guest', 'guest', '" . addslashes(md5('guest')) . "', 'System', 'Guest', 'info+1@bioinforx.com')";
		$ret = $BXAF_CONN->exec($sql);
		if(! $ret) die("Guest account can not be created.");

	}


	$sql = "SELECT ID FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . '';
	$ret = intval($BXAF_CONN->querySingle($sql));

	if ($ret <= 0){

		$sql = "CREATE TABLE {$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']} (
					ID INTEGER PRIMARY KEY AUTOINCREMENT,
					Login_ID INTEGER,
					Remote_Addr varchar(255) NOT NULL,
					Remote_Port varchar(255) NOT NULL,
					Login_Time DATETIME,
					Logout_Time DATETIME,
					Session_ID varchar(255),
					SERVER TEXT,
					Status_Time DATETIME,
					Notes TEXT,
					bxafField1 TEXT,
					bxafField2 TEXT,
					bxafField3 TEXT,
					bxafField4 TEXT,
					bxafField5 TEXT,
					bxafField6 TEXT,
					bxafField7 TEXT,
					bxafField8 TEXT,
					bxafField9 TEXT,
					bxafStatus TINYINT
			)
		";

		$ret = $BXAF_CONN->exec($sql);
		if(! $ret) die("Database table {$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']} can not be created.");

		$sql = "INSERT INTO " . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . " (ID, Login_ID, Remote_Addr, Remote_Port, Login_Time, Logout_Time, Session_ID, SERVER, Status_Time) VALUES (1000001, '0', '', '', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "', '', '', '')";
		$ret = $BXAF_CONN->exec($sql);

    }
	$BXAF_CONN->close();

}

else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

	$BXAF_CONN = bxaf_get_user_db_connection();
	$sql = "SHOW TABLES";
	$tables = $BXAF_CONN->get_col($sql);

	if(! is_array($tables) || ! in_array($BXAF_CONFIG['TBL_BXAF_LOGIN'], $tables)){

		$sql = "CREATE TABLE IF NOT EXISTS " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " (
				`ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`PID` int(11) unsigned NOT NULL DEFAULT '0',

				`Login_Name` varchar(255) NOT NULL DEFAULT '',
				`Password` varchar(255) NOT NULL DEFAULT '',

				`Name` varchar(255) NOT NULL DEFAULT '',
				`Category` varchar(255) NOT NULL DEFAULT '',
				`First_Name` varchar(255) NOT NULL DEFAULT '',
				`Last_Name` varchar(255) NOT NULL DEFAULT '',
				`Email` varchar(255) NOT NULL DEFAULT '',
				`Phone` varchar(255) NOT NULL DEFAULT '',
				`Instant_Message` varchar(255) NOT NULL DEFAULT '',
				`Website` varchar(255) NOT NULL DEFAULT '',
				`Address` varchar(1023) NOT NULL DEFAULT '',
				`Organization` varchar(255) NOT NULL DEFAULT '',
				`Department` varchar(255) NOT NULL DEFAULT '',
				`Subdivision` varchar(255) NOT NULL DEFAULT '',
				`Group_Name` varchar(255) NOT NULL DEFAULT '',
				`Group_Role` varchar(255) NOT NULL DEFAULT 'Owner',
				`Status` varchar(255) NOT NULL DEFAULT '',
				`Status_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`Notes` text,
				`bxafField1` text,
				`bxafField2` text,
				`bxafField3` text,
				`bxafField4` text,
				`bxafField5` text,
				`bxafField6` text,
				`bxafField7` text,
				`bxafField8` text,
				`bxafField9` text,
				`bxafStatus` tinyint(4) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`ID`),
				KEY `Name` (`Name`),
				KEY `First_Name` (`First_Name`),
				KEY `Last_Name` (`Last_Name`),
				KEY `bxafStatus` (`bxafStatus`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000001
		";

		$ret = $BXAF_CONN->Execute($sql);
		if(! $ret) die("Database table {$BXAF_CONFIG['TBL_BXAF_LOGIN']} can not be created.");

		$sql = "INSERT INTO `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` (`ID`, `Name`, `Login_Name`, `Password`, `First_Name`, `Last_Name`, `Email`) VALUES (1000001, 'System Admin', 'admin', '" . addslashes(md5($BXAF_CONFIG['BXAF_ADMIN_PASSWORD'])) . "', 'System', 'Admin', 'info@bioinforx.com')";
		$ret = $BXAF_CONN->Execute($sql);
		if(! $ret) die("Admin account can not be created.");

		$sql = "INSERT INTO `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` (`ID`, `Name`, `Login_Name`, `Password`, `First_Name`, `Last_Name`, `Email`) VALUES (1000002, 'System Guest', 'guest', '" . addslashes(md5('guest')) . "', 'System', 'Guest', 'info+1@bioinforx.com')";
		$ret = $BXAF_CONN->Execute($sql);
		if(! $ret) die("Guest account can not be created.");

	}


	if(! is_array($tables) || ! in_array($BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'], $tables)){

		$sql = "CREATE TABLE {$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']} (
			`ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`Login_ID` int(11) unsigned NOT NULL DEFAULT '0',
			`Remote_Addr` varchar(255) NOT NULL DEFAULT '',
			`Remote_Port` varchar(255) NOT NULL DEFAULT '',
			`Login_Time` datetime NOT NULL DEFAULT '0000-00-00',
			`Logout_Time` datetime NOT NULL DEFAULT '0000-00-00',
			`Session_ID` varchar(255) NOT NULL DEFAULT '',
			`SERVER` text,
			`Status_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`Notes` text,
			`bxafField1` text,
			`bxafField2` text,
			`bxafField3` text,
			`bxafField4` text,
			`bxafField5` text,
			`bxafField6` text,
			`bxafField7` text,
			`bxafField8` text,
			`bxafField9` text,
			`bxafStatus` tinyint(4) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`ID`),
			KEY `Login_ID` (`Login_ID`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000001
		";

		$ret = $BXAF_CONN->Execute($sql);
		if(! $ret) die("Database table {$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']} can not be created.");

		$sql = "INSERT INTO `" . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . "` (`ID`, `Login_ID`, `Remote_Addr`, `Remote_Port`, `Login_Time`, `Logout_Time`, `Session_ID`, `SERVER`, `Status_Time`) VALUES (1000001, '0', '', '', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "', '', '', '" . date('Y-m-d H:i:s') . "')";
		$ret = $BXAF_CONN->exec($sql);

	}

}

echo "Done with database setup.";

?>