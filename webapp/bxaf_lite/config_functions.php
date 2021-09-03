<?php


//Figure out absolute URL from a relative url
// $reference_url must end with a file name (e.g. http://localhost/myfolder/index.php) or '/' (e.g., http://localhost/myfolder/).
// e.g., bxaf_absolute_url("../test4.php", "http://bioinforx.com/test1/test2/test3.php")
// return: http://bioinforx.com/test1/test4.php
if (!function_exists('bxaf_absolute_url')){
	function bxaf_absolute_url($relative_url = NULL, $reference_url = NULL){

		// Validate URL
		if(! filter_var($reference_url, FILTER_VALIDATE_URL)) return '';

		$url_info = parse_url($reference_url);
		if(! is_array($url_info) || ! isset($url_info['scheme']) || ! isset($url_info['host'])) return '';


		//Make sure the reference url ends with '/'
		if(! preg_match("/\/$/", $url_info['path'])){
			$url_info['path'] = dirname($url_info['path']) . '/';
			$reference_url = $url_info['scheme'] . "://" . $url_info['host'] . ($url_info['port'] == '' ? '' : (':' . $url_info['port'])) . $url_info['path'];
		}


		if(strpos($relative_url, 'http://') === 0 || strpos($relative_url, 'https://') === 0){
			// $relative_url is a full url
		}
		if(strpos($relative_url, '/') === 0){
			$relative_url = $url_info['scheme'] . "://" . $url_info['host'] . ($url_info['port'] == '' ? '' : (':' . $url_info['port'])) . $relative_url;
		}

		while(strpos($relative_url, './') === 0){
			$relative_url = substr($relative_url, 2);
		}

		if(strpos($relative_url, '../') === 0){

			$u_dir = $url_info['path'];

			while(strpos($relative_url, '../') === 0){
				if($u_dir != '/') $u_dir = dirname($u_dir);

				$relative_url = substr($relative_url, 3);
				while(strpos($relative_url, './') === 0){
					$relative_url = substr($relative_url, 2);
				}
			}
			if($u_dir == '/') $u_dir = '';
			$relative_url = $u_dir . '/' . $relative_url;

			$relative_url = $url_info['scheme'] . "://" . $url_info['host'] . ($url_info['port'] == '' ? '' : (':' . $url_info['port'])) . $relative_url;
		}

		if(strpos($relative_url, 'http://') !== 0 && strpos($relative_url, 'https://') !== 0){
			$relative_url = $reference_url . $relative_url;
		}

		return $relative_url;
	}
}

// This function removes magic quotes from all items in an array, e.g., bxaf_remove_magic_quotes($_POST)
if(!function_exists('bxaf_remove_magic_quotes')) {
	function bxaf_remove_magic_quotes(&$array = NULL) {
		if(! get_magic_quotes_gpc()) return false;
		else {
		   if(! is_array($array)){
			   $array = stripslashes($array);
		   }
		   else {
			   foreach($array as $key => $elem) {
				   if(is_array($elem)) bxaf_remove_magic_quotes($elem);
				   else $array[$key] = stripslashes($elem);
			   }
		   }
		}
		return true;
	}
}


if (!function_exists('bxaf_validate_email')){
	function bxaf_validate_email($email_address = NULL) {
		return filter_var($email_address, FILTER_VALIDATE_EMAIL);
	}
}

if (!function_exists('bxaf_validate_url')){
	function bxaf_validate_url($url = NULL) {
		return filter_var($url, FILTER_VALIDATE_URL);
	}
}

//This function validate date and time (e.g., false for 2/31/2015) and whether the format matches, e.g., false for '2222-2-22' due to format 'Y-m-d' (missing leading zeros)
if (!function_exists('bxaf_validate_datetime')) {
	function bxaf_validate_datetime($date = NULL, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}


if (!function_exists('bxaf_get_user_db_connection')) {
	function bxaf_get_user_db_connection(){
		global $BXAF_CONFIG;

		if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

			try {
			    $conn = new SQLite3($BXAF_CONFIG['BXAF_DB_NAME']);
			} catch (Exception $e) {
			    die( $e->getMessage() );
			}

		} elseif ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

			include_once(dirname(__FILE__) . "/bxaf_mysqli.min.php");

			$db_settings = array(
				'user'	=> $BXAF_CONFIG['BXAF_DB_USER'],
				'pass'	=> $BXAF_CONFIG['BXAF_DB_PASSWORD'],
				'db'	=> $BXAF_CONFIG['BXAF_DB_NAME'],
				'host'	=> $BXAF_CONFIG['BXAF_DB_SERVER'],
			);

			$conn = new bxaf_mysqli($db_settings);

			if (!$conn){
				echo "The system could not connect to the MySQL database.";
				exit();
			} else {
				$conn->Execute("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
			}

		}

		return $conn;
	}
}


if (!function_exists('bxaf_get_app_db_connection')) {
	function bxaf_get_app_db_connection(){
		global $BXAF_CONFIG;

		if ($BXAF_CONFIG['APP_DB_DRIVER'] == 'sqlite'){

			try {
			    $conn = new SQLite3($BXAF_CONFIG['APP_DB_NAME']);
			} catch (Exception $e) {
			    die( $e->getMessage() );
			}

		}
		else if ($BXAF_CONFIG['APP_DB_DRIVER'] == 'mysql'){

			include_once(dirname(__FILE__) . "/bxaf_mysqli.min.php");

			$db_settings = array(
				'user'	=> $BXAF_CONFIG['APP_DB_USER'],
				'pass'	=> $BXAF_CONFIG['APP_DB_PASSWORD'],
				'db'	=> $BXAF_CONFIG['APP_DB_NAME'],
				'host'	=> $BXAF_CONFIG['APP_DB_SERVER'],
			);

			$conn = new bxaf_mysqli($db_settings);
			if (! $conn){
				echo "The system could not connect to the MySQL database.";
				exit();
			} else {
				$conn->Execute("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
				$conn->Execute("SET SESSION sql_mode = ''");
			}

		}
		else {
			die("Database driver is not correct.");
		}

		return $conn;
	}
}





//Note: the string to be encrypted should not end with the first 32 non-printing characters in ASCII table!
// String can include ASCII printable characters (character code 32-127), The extended ASCII codes (character code 128-255), UTF (Unicode encoding), e.g., Chinese

//Encrypt Function - encrypt text with a key
if (!function_exists('bxaf_encrypt')) {
	function bxaf_encrypt($encrypt = NULL, $key = NULL) {
		if (function_exists('mcrypt_create_iv') && function_exists('hash') && function_exists('mcrypt_encrypt') && function_exists('mcrypt_decrypt')) {
			//Remove the first 32 non-printing characters in ASCII table from the end of string
			$encrypt = rtrim($encrypt, "\x00..\x1F");
			$securekey = hash('sha256',$key,TRUE);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
			$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $securekey, $encrypt, MCRYPT_MODE_ECB, $iv);
			$encrypted = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
			return $encrypted;
		}
		else {
			return rtrim(strtr(base64_encode($encrypt), '+/', '-_'), '=');
		}
	}
}

//Decrypt Function - decrypt the encrypted text with a key
if (!function_exists('bxaf_decrypt')) {
	function bxaf_decrypt($decrypt = NULL, $key = NULL) {
		if (function_exists('mcrypt_create_iv') && function_exists('hash') && function_exists('mcrypt_encrypt') && function_exists('mcrypt_decrypt')) {
			$securekey = hash('sha256',$key,TRUE);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
			$decrypted = base64_decode(str_pad(strtr($decrypt, '-_', '+/'), strlen($decrypt) % 4, '=', STR_PAD_RIGHT));
			$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $securekey, $decrypted, MCRYPT_MODE_ECB, $iv);
			//Remove the first 32 non-printing characters in ASCII table
			$decrypted = rtrim($decrypted, "\x00..\x1F");

			return $decrypted;
		}
		else {
			return base64_decode(str_pad(strtr($decrypt, '-_', '+/'), strlen($decrypt) % 4, '=', STR_PAD_RIGHT));
		}
	}
}



//Encrypt Function - encrypt text with a key
if (!function_exists('bxaf_encrypt_for_email_api')) {
	function bxaf_encrypt_for_email_api($encrypt = NULL, $key = NULL) {
		$key = md5($key);
	    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
	    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
	    $encode = base64_encode($passcrypt);
	    return $encode;
	}
}


if (!function_exists('bxaf_get_welcome_email')){
	function bxaf_get_welcome_email($first_name = NULL, $email = NULL, $password = NULL){

		global $BXAF_CONFIG;

		return
		"
			<p>Hello {$first_name},</p>

			<p>Here is your account information on <a href='{$BXAF_CONFIG['BXAF_WEB_URL']}' target='_blank'>{$BXAF_CONFIG['BXAF_PAGE_APP_NAME']}</a>:</p>

			<p>E-mail: <strong>{$email}</strong></p>
			<p>Password: <strong>{$password}</strong></p>

			<p>If you have any questions, please contact us at <a href='mailto:{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}'>{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}</a>.</p>

			<p>Thank you!</p>
			<p>{$BXAF_CONFIG['BXAF_PAGE_AUTHOR']}</p>
		";
	}

}




if(!function_exists('bxaf_send_email')) {
	function bxaf_send_email($email_param = NULL, $debug = false){

		global $BXAF_CONFIG;

		//From e-mail address is  required
		if(! isset($email_param['From']) || ! bxaf_validate_email($email_param['From'])) return 0;
		if(! isset($email_param['FromName'])) $email_param['FromName'] = $email_param['From'];

		//To e-mail addresses are required
		if(! isset($email_param['To']) ) return 0;

		//To is a sigle address
		if(! is_array($email_param['To'])){
			if(! bxaf_validate_email($email_param['To']))  return 0;
			if(! isset($email_param['ToName'])) $email_param['ToName'] = $email_param['To'];
			$email_param['To'] = array($email_param['To'] => $email_param['ToName']);
		}
		//To is a list of addresses and names in associate array
		else {
			foreach($email_param['To'] as $email=>$name){
				if(! bxaf_validate_email($email)) unset($email_param['To'][$email]);
				if($name == '') $email_param['To'][$email] = $email;
			}
		}

		if(! is_array($email_param['To']) || count($email_param['To']) <= 0) return 0;


		if(! isset($email_param['ReplyTo'])) $email_param['ReplyTo'] = $email_param['From'];
		if(! isset($email_param['ReplyToName'])) $email_param['ReplyToName'] = $email_param['FromName'];

        if(isset($BXAF_CONFIG['EMAIL_FORCE_FROM']) && bxaf_validate_email($BXAF_CONFIG['EMAIL_FORCE_FROM'])) $email_param['From'] = $BXAF_CONFIG['EMAIL_FORCE_FROM'];



		$return = 1;

		if($BXAF_CONFIG['EMAIL_METHOD'] == 'sendmail'){

			include_once($BXAF_CONFIG['EMAIL_PROGRAM']);

			$mail = new PHPMailer(true);

			$mail->IsSendmail();

			if($debug) {
				//Enable debugging
				// 0 = off (for production use)
				// 1 = client messages
				// 2 = client and server messages
				$mail->SMTPDebug  = 2;
				//Ask for HTML-friendly debug output
				$mail->Debugoutput = 'html';
			}
			else {
				$mail->SMTPDebug  = 0;
			}

			$mail->CharSet 	  = "UTF-8";

			$mail->SetFrom($email_param['From'], $email_param['FromName']);
			$mail->AddReplyTo($email_param['ReplyTo'], $email_param['ReplyToName']);

			foreach($email_param['To'] as $email=>$name)	$mail->AddAddress(preg_replace("/[\s]+.*\@/", '@', $email), $name);

			$mail->Subject = $email_param['Subject'];
			$mail->MsgHTML($email_param['Body']);
			$mail->AltBody = strip_tags($email_param['Body']);

			foreach($email_param['files'] as $file)	if(file_exists($file) && is_file($file) && is_readable($file)) $mail->AddAttachment($file);

			if(!$mail->Send()) {
				$error = $mail->ErrorInfo; if(trim($error) == '') $error = 'Unknown error';

				if($debug) echo "Mailer Error: " . $mail->ErrorInfo;

				$return = 0;
			} else {
				if($debug) echo "Mail sent successfully.";
			}

		}

		else if($BXAF_CONFIG['EMAIL_METHOD'] == 'smtp'){

			if(! file_exists($BXAF_CONFIG['EMAIL_PROGRAM'])) return 0;

			include_once($BXAF_CONFIG['EMAIL_PROGRAM']);

			$mail = new PHPMailer(true);

			try {

				$mail->IsSMTP();

				if($debug) {
					//Enable debugging
					// 0 = off (for production use)
					// 1 = client messages
					// 2 = client and server messages
					$mail->SMTPDebug  = 2;
					$mail->Debugoutput = 'html';
				}
				else {
					$mail->SMTPDebug  = 0;
				}

				$mail->Host       = $BXAF_CONFIG['EMAIL_SMTP_SERVER'];
				$mail->Port       = $BXAF_CONFIG['EMAIL_SMTP_PORT'];
				$mail->SMTPAuth   = true;
				$mail->CharSet 	  = "UTF-8";

				$mail->SMTPKeepAlive = TRUE;
				$mail->SMTPSecure = strtolower($BXAF_CONFIG['EMAIL_SMTP_SECURITY']);

				$mail->Username   = $BXAF_CONFIG['EMAIL_SMTP_USER'];
				$mail->Password   = $BXAF_CONFIG['EMAIL_SMTP_PASSWORD'];

				$mail->SetFrom($email_param['From'], $email_param['FromName']);
				$mail->AddReplyTo($email_param['ReplyTo'], $email_param['ReplyToName']);

				foreach($email_param['To'] as $email=>$name)	$mail->AddAddress($email, $name);

				$mail->Subject = $email_param['Subject'];
				$mail->MsgHTML($email_param['Body']);
				$mail->AltBody = strip_tags($email_param['Body']);

				//Attach an files
				foreach($email_param['files'] as $file)	if(file_exists($file) && is_file($file) && is_readable($file)) $mail->AddAttachment($file);

				$mail->Send();

			} catch (phpmailerException $e) {

				$error = $e->errorMessage(); if(trim($error) == '') $error = 'Unknown error';

				$return = 0;

				if($debug) echo "Mailer Error: " . $e->errorMessage(); //Pretty error messages from PHPMailer

			} catch (Exception $e) {

				$error = $e->getMessage(); if(trim($error) == '') $error = 'Unknown error';

				$return = 0;
				if($debug) echo "Mailer Error: " . $e->getMessage(); //Boring error messages from anything else!

			}


		}

        else if($BXAF_CONFIG['EMAIL_METHOD'] == 'sendgrid'){

			if(! file_exists($BXAF_CONFIG['EMAIL_PROGRAM'])) return 0;

			$request_body = array();
			$request_body['from'] = array( 'name'=>$email_param['FromName'], 'email'=>$email_param['From']);
			$request_body['reply_to'] = array( 'name'=>$email_param['ReplyToName'], 'email'=>$email_param['ReplyTo']);

			$request_body['subject'] = $email_param['Subject'];
			$request_body['content'][] = array('type'=>'text/html', 'value'=> $email_param['Body']);

			$request_body['personalizations'] = array();
			$request_body['personalizations'][0] = array();
			$request_body['personalizations'][0]['to'] = array();
			foreach($email_param['To'] as $email=>$name) $request_body['personalizations'][0]['to'][] = array( 'name'=>$name, 'email'=>$email);


			if(array_key_exists('files', $email_param) && is_array($email_param['files']) && count($email_param['files']) > 0) {
				$request_body['attachments'] = array();
				foreach($email_param['files'] as $file)	if(file_exists($file) && is_file($file) && is_readable($file)) {
					$request_body['attachments'][] = array(
						'filename'=> basename($file),
						'content'=>base64_encode(file_get_contents($file)),
					);
				}
			}

			include_once($BXAF_CONFIG['EMAIL_PROGRAM']);

			$sendgrid_mail = new \SendGrid( $BXAF_CONFIG['SENDGRID_KEY'] );

			$response = $sendgrid_mail->client->mail()->send()->post($request_body);

			if($response < 300) $return = 1;
			else $return = 0;

		}

		else if($BXAF_CONFIG['EMAIL_METHOD'] == 'mailgun'){

            global $mail;

            $mailto = array();
            foreach($email_param['To'] as $email=>$name){
            	$mailto[] = "\"$name\" <$email>";
            }

            $result = $mail->sendMessage(
            	"{$BXAF_CONFIG['MAILGUN_DOMAIN']}",
            	array(
            		'from'    => '"' . $email_param['FromName'] . '" <' . $email_param['From'] . '>',
            		'h:Reply-To' => '"' . $email_param['ReplyToName'] . '" <' . $email_param['ReplyTo'] . '>',
            		'to'      => $mailto,
            		'subject' => $email_param['Subject'],
            		'text'    => strip_tags($email_param['Body']),
            		'html'    => $email_param['Body']
            	),
            	array(
            		'attachment' => $email_param['files']
            	)
            );

		}

		else if($BXAF_CONFIG['EMAIL_METHOD'] == 'url'){
			$URL = $BXAF_CONFIG['SENDEMAIL_VIA_URL'] . urlencode( bxaf_encrypt_for_email_api(json_encode($email_param), $BXAF_CONFIG['SENDEMAIL_VIA_URL_KEY']) );
			$return = intval(file_get_contents($URL));
		}

		else if($BXAF_CONFIG['EMAIL_METHOD'] == 'url_bioinforx'){
			$URL = $BXAF_CONFIG['SENDEMAIL_VIA_URL'] . urlencode( bxaf_encrypt(json_encode($email_param), $BXAF_CONFIG['SENDEMAIL_VIA_URL_KEY']) );
			$return = intval(file_get_contents($URL));
		}

		else if($BXAF_CONFIG['EMAIL_METHOD'] == 'posturl'){

			$email_param['To'] = serialize($email_param['To']);

			if(is_array($email_param['files']) && count($email_param['files']) > 0){
				foreach($email_param['files'] as $f){
					if(file_exists($f)){
						$email_param[$f] = new CURLFile($f);
					}
				}
			}
			if(isset($email_param['files'])) unset($email_param['files']);

			$process = curl_init($BXAF_CONFIG['SENDEMAIL_VIA_POSTURL']);
			curl_setopt($process, CURLOPT_HEADER, false);
			curl_setopt($process, CURLOPT_TIMEOUT, 30);
			curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($process, CURLOPT_POSTFIELDS, $email_param );

			$return = curl_exec($process);
			curl_close($process);

		}

		return $return;
	}

}


if (!function_exists('bxaf_random_password')) {
	function bxaf_random_password($length = 8, $alphanumeric_only = false, $case_insensitive = false){
		//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		//The following string lacks of these characters: l, L, i, I, o, O, z, Z, 0, 1
		$chars = "abcdefghjkmnpqrstuvwxyABCDEFGHJKMNPQRSTUVWXY23456789!@#$%^&*()_-=+;:,.?";
		if($alphanumeric_only) $chars = "abcdefghjkmnpqrstuvwxyABCDEFGHJKMNPQRSTUVWXY23456789";
		if($case_insensitive) $chars = strtolower($chars);

		return substr( str_shuffle( $chars ), 0, $length );
	}
}


if (!function_exists('bxaf_login_log')) {
	function bxaf_login_log($log_id = 0){
		global $BXAF_CONFIG;

        $conn = bxaf_get_user_db_connection();

        if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

            if(intval($log_id) > 0){
                $sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . " SET Logout_Time = '" . date('Y-m-d H:i:s') . "' WHERE ID = " . intval($log_id);
                $conn->exec($sql);

                unset($_SESSION['BXAF_LOGIN_LOG_ID']);
            }
            else {
                $sql = "SELECT MAX(ID) FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']}";
                $new_id = 1 + intval($conn->querySingle($sql));

                $remote_addr = $_SERVER['REMOTE_ADDR'];
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];

                $sql = "INSERT INTO " . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . " (ID, Login_ID, Remote_Addr, Remote_Port, Login_Time, Logout_Time, Session_ID, SERVER, Status_Time) VALUES ($new_id, " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) . ", '" . $remote_addr . "', '" . $_SERVER['SERVER_PORT'] . "', '" . date('Y-m-d H:i:s') . "', '', '" . $_COOKIE['PHPSESSID'] . "', '" . addslashes(serialize($_SERVER)) . "', '" . date('Y-m-d H:i:s') . "')";

                $ret = $conn->exec($sql);
                if($ret) $_SESSION['BXAF_LOGIN_LOG_ID'] = $new_id;
            }

            $conn->close();
        }

        else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

            if(intval($log_id) > 0){
                $sql = "UPDATE `" . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . "` SET `Logout_Time` = '" . date('Y-m-d H:i:s') . "' WHERE `ID` = " . intval($log_id);
                $conn->Execute($sql);

                unset($_SESSION['BXAF_LOGIN_LOG_ID']);
            }
            else {
                $sql = "SELECT MAX(`ID`) FROM `{$BXAF_CONFIG['TBL_BXAF_LOGIN_LOG']}`";
                $new_id = 1 + intval($conn->get_one($sql));

                $remote_addr = $_SERVER['REMOTE_ADDR'];
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];

                $sql = "INSERT INTO `" . $BXAF_CONFIG['TBL_BXAF_LOGIN_LOG'] . "` (`ID`, `Login_ID`, `Remote_Addr`, `Remote_Port`, `Login_Time`, `Logout_Time`, `Session_ID`, `SERVER`, `Status_Time`) VALUES ($new_id, " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) . ", '" . $remote_addr . "', '" . $_SERVER['SERVER_PORT'] . "', '" . date('Y-m-d H:i:s') . "', '', '" . $_COOKIE['PHPSESSID'] . "', '" . addslashes(serialize($_SERVER)) . "', '" . date('Y-m-d H:i:s') . "')";

                $ret = $conn->Execute($sql);
                if($ret) $_SESSION['BXAF_LOGIN_LOG_ID'] = $new_id;
            }

        }

	}
}


if (!function_exists('bxaf_get_version')){
	function bxaf_get_version() {
		$version_date = '';
		if (($handle = fopen(dirname(__FILE__) . "/version.txt", "r")) !== FALSE) {
		    $version_date = date('Y-m-d H:i:s', strtotime(array_shift(fgetcsv($handle, 100, "\t"))));
		    fclose($handle);
		}
		return $version_date;
	}
}

if (!function_exists('bxaf_test')){
	function bxaf_test($param = ''){

		return true;
	}

}


?>