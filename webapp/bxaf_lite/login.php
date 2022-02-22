<?php
// Important: disable login requirement
$BXAF_CONFIG_CUSTOM['PAGE_LOGIN_REQUIRED']	= false;
$BXAF_CONFIG_CUSTOM['BXAF_PAGE_SPLIT']		= false;

include_once(dirname(__FILE__) . "/config.php");

if ($BXAF_CONFIG['GUEST_ACCOUNT_AUTO']){
	if ($_GET['action'] == ''){
		if (sizeof($_POST) <= 0){
			$_GET['action'] = 'guest';
		}
	}
}

if ($_GET['action'] == 'out'){
	unset($_SESSION);
	session_destroy();
}


if ($_GET['action'] == 'logout'){

	bxaf_login_log($_SESSION['BXAF_LOGIN_LOG_ID']);

	unset($_SESSION);
	session_destroy();

	header('Location: login.php?action=out');
	exit();
}

// Has logged in already
else if (isset($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']])){
	header('Location: ' . $BXAF_CONFIG['BXAF_LOGIN_SUCCESS']);
	exit();
}

else if (($_GET['action'] == 'guest') && ($BXAF_CONFIG['GUEST_ACCOUNT'] != '')){

	$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE Login_Name = '" . addslashes($BXAF_CONFIG['GUEST_ACCOUNT']) . "' AND (bxafStatus IS NULL OR bxafStatus < 5)";

	if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){
		$BXAF_CONN = bxaf_get_user_db_connection();
		$user_info = $BXAF_CONN->querySingle($sql, true);
		$BXAF_CONN->close();
	}
	else if($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){
		$BXAF_CONN = bxaf_get_user_db_connection();
		$user_info = $BXAF_CONN->get_row($sql);
	}

	$found_id = intval($user_info['ID']);

	if ($found_id > 0){
		$_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']] 	= $found_id;
		$_SESSION['User_Info']		= $user_info;

		bxaf_login_log();

		$forward_url = $BXAF_CONFIG['BXAF_LOGIN_SUCCESS'];
		if(isset($_SESSION['BXAF_USER_LOGIN_FAILED']) && $_SESSION['BXAF_USER_LOGIN_FAILED'] != ''){
			$forward_url = $_SESSION['BXAF_USER_LOGIN_FAILED'];
			unset($_SESSION['BXAF_USER_LOGIN_FAILED']);
		}

		header('Location: ' . $forward_url);
		exit();
	} else {
		$info_message = "The guest account is not available. <BR>Please contact system administrator for details.";
	}

}

else if ($_GET['action'] == 'request_to_reset' && isset($_GET['Email']) && $_GET['Email'] != ''){

	$login	= addslashes($_GET['Email']);

	$user_info = array();
	$found_id = 0;
	if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

		$BXAF_CONN = bxaf_get_user_db_connection();

		$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (Login_Name = '$login' OR Email = '$login') AND (bxafStatus IS NULL OR bxafStatus < 5)";
		$user_info = $BXAF_CONN->querySingle($sql, true);

		$BXAF_CONN->close();
	} elseif ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

		$BXAF_CONN = bxaf_get_user_db_connection();

		$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (`Login_Name` = '$login' OR `Email` = '$login') AND (`bxafStatus` IS NULL OR `bxafStatus` < 5)";
		$user_info = $BXAF_CONN->get_row($sql);
	}

	if(is_array($user_info) && array_key_exists('ID', $user_info)) $found_id = intval($user_info['ID']);


	if ($found_id > 0){

		$data = urlencode(bxaf_encrypt(serialize( array('e'=>$_GET['Email'], 't'=>time()) ), $BXAF_CONFIG['BXAF_KEY']));
		$reset_url = $BXAF_CONFIG['BXAF_LOGIN_PAGE'] . "?action=reset_password&k=" . $data;

		$email_body =
"<p>Dear {$user_info['First_Name']},</p>
<p>You have just requested to reset your account password. <strong>To reset your account password, please <a href='$reset_url'>click here</a></strong>. Or, copy the following web address to your browser: </p>
<p>$reset_url</p>
<p>Visiting above link will reset your password with a system generated random password. Your link will expire in 1 hour.</p>
<p>If you did not request to reset your account password, it's likely that another user entered your e-mail address by mistake while trying to reset account password. If you didn't initiate the request, you don't need to take any further action and can safely discard this e-mail. </p>
<p>Thank you for using our system. If you have any questions or concerns about your account, please contact us or simply reply this e-mail. </p>
<p>Sincerely,<BR />{$BXAF_CONFIG['BXAF_PAGE_AUTHOR']}<BR />{$BXAF_CONFIG['BXAF_PAGE_APP_NAME']}<BR />{$BXAF_CONFIG['BXAF_WEB_URL']}</p>";

		$email_param = array(
			'From'     => $BXAF_CONFIG['BXAF_PAGE_EMAIL'],
			'FromName' => $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
			'Subject'  => "Password Assistance on " . $BXAF_CONFIG['BXAF_WEB_URL'],
			'Body'     => $email_body,
			'To'       => array($user_info['Email'] => $user_info['Name']),
		);
		bxaf_send_email($email_param);

		$info_message = "Your request has been accepted. Please check your e-mail for instructions to reset your password.";
	}
	else {
		$info_message = "Your account is not found.";
	}

}


else if ($_GET['action'] == 'reset_password' && isset($_GET['k']) && $_GET['k'] != ''){

	$d = unserialize( bxaf_decrypt($_GET['k'], $BXAF_CONFIG['BXAF_KEY']) );

	if(! is_array($d) || ! isset($d['e']) || ! isset($d['t']) ){
		$info_message = "Your link is not correct. Please try again.";
	}
	else {

		$expire_seconds = 3600; // 1 hour
		$time_diff = time() - intval($d['t']);

		if ($time_diff > $expire_seconds){
			$info_message = "Your link has expired. Please try again.";
		}
		else {
			$login	= addslashes($d['e']);

			$user_info = array();
			$found_id = 0;
			$BXAF_CONN = bxaf_get_user_db_connection();

			if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

				$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (Login_Name = '$login' OR Email = '$login') AND (bxafStatus IS NULL OR bxafStatus < 5)";
				$user_info = $BXAF_CONN->querySingle($sql, true);

			} elseif ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){
				$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (`Login_Name` = '$login' OR `Email` = '$login') AND (`bxafStatus` IS NULL OR `bxafStatus` < 5)";
				$user_info = $BXAF_CONN->get_row($sql);
			}

			if(is_array($user_info) && array_key_exists('ID', $user_info)) $found_id = intval($user_info['ID']);

			if ($found_id > 0){

				$new_password = bxaf_random_password(8);
				if(isset($BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD']) && $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] != ''){
					$new_password = $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'];
				}

				if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){
					$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET Password = '" . addslashes(md5($new_password)) . "' WHERE ID = $found_id";
					$BXAF_CONN->exec($sql);
					$BXAF_CONN->close();
				}
				else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){
					$sql = "UPDATE `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` SET `Password` = '" . addslashes(md5($new_password)) . "' WHERE `ID` = $found_id";
					$BXAF_CONN->Execute($sql);
				}

				$body = "
					<p>Dear {$user_info['First_Name']},</p>
					<p>Here is your account information:</p>
					<hr>
					<p>
						System URL: <a href='{$BXAF_CONFIG['BXAF_WEB_URL']}' target='_blank'>{$BXAF_CONFIG['BXAF_WEB_URL']}</a><BR />
						E-mail: {$user_info['Email']}<BR />
						Password: <strong>{$new_password}</strong>
					</p>
					<p>
						Once you've logged in the system, you can change your password by visiting <a href='{$BXAF_CONFIG['BXAF_USER_PROFILE']}'>Your Account Information</a>  ({$BXAF_CONFIG['BXAF_USER_PROFILE']}).
					</p>
					<hr>
					<p>If you have any questions, please contact <a href='mailto:{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}'>Webmaster</a>.</p>
					<p>Sincerely,<BR />{$BXAF_CONFIG['BXAF_PAGE_AUTHOR']}<BR />{$BXAF_CONFIG['BXAF_PAGE_APP_NAME']}<BR />{$BXAF_CONFIG['BXAF_WEB_URL']}</p>
				";

				$email_param = array(
					'From'     => $BXAF_CONFIG['BXAF_PAGE_EMAIL'],
					'FromName' => $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
					'Subject'  => "Reset password for " . $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
					'Body'     => $body,
					'To'       => array($user_info['Email'] => $user_info['Name']),
				);

				bxaf_send_email($email_param);

				$info_message = "Your password has been reset and sent to your e-mail account.";
			}
			else {
				$info_message = "Your account is not found.";
			}

		}
	}

}

else if (isset($_POST['Login_Name']) && (isset($_POST['Password']) || isset($_POST['Use_Default_Password']))){

	$login	= addslashes($_POST['Login_Name']);

	$user_info = array();
	$found_id = 0;
	if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

		$BXAF_CONN = bxaf_get_user_db_connection();

		$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (Login_Name = '$login' OR Email = '$login') AND (bxafStatus IS NULL OR bxafStatus < 5)";
		$user_info = $BXAF_CONN->querySingle($sql, true);

		$BXAF_CONN->close();
	} elseif ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

		$BXAF_CONN = bxaf_get_user_db_connection();

		$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE (`Login_Name` = '$login' OR `Email` = '$login') AND (`bxafStatus` IS NULL OR `bxafStatus` < 5)";
		$user_info = $BXAF_CONN->get_row($sql);
	}

	if(is_array($user_info) && array_key_exists('ID', $user_info)) $found_id = intval($user_info['ID']);

	if ($found_id > 0){

		if(	isset($BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD']) && $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] != '' &&
			isset($_POST['Use_Default_Password']) && $_POST['Use_Default_Password'] == 1){
			$_POST['Password'] = $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'];
		}

		if ($user_info['Password'] == md5($_POST['Password'])){

			$_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']] 	= $found_id;
			$_SESSION['User_Info']		= $user_info;
			if($_SESSION['User_Info']['Category'] == 'Advanced') $_SESSION['BXAF_ADVANCED_USER'] = true;

			bxaf_login_log();

			$forward_url = $BXAF_CONFIG['BXAF_LOGIN_SUCCESS'];
			if(isset($_SESSION['BXAF_USER_LOGIN_FAILED']) && $_SESSION['BXAF_USER_LOGIN_FAILED'] != ''){
				$forward_url = $_SESSION['BXAF_USER_LOGIN_FAILED'];
				unset($_SESSION['BXAF_USER_LOGIN_FAILED']);
			}

			header('Location: ' . $forward_url);
			exit();

		}
		else { // Password does not match
			$info_message = "Your e-mail/password do not match any record. Please try again.";
		}
	}

	else { //No account is found, forword to sign up page
		$forward_url = $BXAF_CONFIG['BXAF_SIGNUP_PAGE'] . '?New_Account=1&Email=' . urlencode($login);
		header('Location: ' . $forward_url);
		exit();
	}

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once('page_header.php'); ?>

	<script type="text/javascript">

		$(document).ready(function(){

			$('#btn_forgot_password').click(function(){
				var Email = $('#Login_Name').val();

				if (Email == '' || ! bxaf_validate_email(Email)){
					var alert = bootbox.alert("<h4 class='my-3'><i class='fas fa-exclamation-triangle text-danger'></i> Please enter a valid account email and try again.</h4>");
				} else {
					bootbox.confirm("<h4 class='my-3 w-100'><i class='fas fa-exclamation-triangle text-danger'></i> Please confirm!</h4><p>This request is to reset your password.</p> <p class='text-danger'>Are you sure you want to reset your password? </p>", function(result){
						if (result){
							window.location = '<?php echo $_SERVER['PHP_SELF']; ?>?action=request_to_reset&Email=' + encodeURIComponent(Email);
						}
					});

				}

			});

		});

	</script>
</head>

<body>

	<?php include_once('page_menu.php'); ?>


	<div class="container-fluid">
		<div class="row mt-5 p-2">

			<div class="mx-auto" style="width: 350px;">

				<?php if (isset($_GET['action']) && $_GET['action'] == 'out') echo "<h5 class='text-warning my-5'>You have signed out successfully.</h5>"; ?>

				<h2><?php echo $BXAF_CONFIG['BXAF_PAGE_LOGIN_NAME_WELCOME_MESSAGE']; ?></h2>

					<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' role='form'>

						<label for="Login_Name" class="mt-3 font-weight-bold"><?php echo $BXAF_CONFIG['BXAF_PAGE_LOGIN_NAME_TITLE']; ?>:</label>
						<input class="form-control mb-2" name="Login_Name" id="Login_Name" type="text" value="<?php if(isset($_POST['Login_Name'])) echo $_POST['Login_Name']; else if(isset($_GET['Login_Name'])) echo $_GET['Login_Name']; else if(isset($_POST['Email'])) echo $_POST['Email']; else if(isset($_GET['Email'])) echo $_GET['Email']; ?>" autofocus required placeholder="<?php echo $BXAF_CONFIG['BXAF_PAGE_LOGIN_NAME_PLACEHOLDER']; ?>">

						<div id="section_password" class="w-100<?php if(isset($BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD']) && $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] != '') echo ' hidden'; ?>">

							<label for="Password" class="mt-3 font-weight-bold">Password:</label>

							<input class="form-control mb-2" name="Password" id="Password" type="password" value="" placeholder="<?php echo $BXAF_CONFIG['BXAF_PAGE_LOGIN_PASSWORD_PLACEHOLDER']; ?>">

							<label class="mb-3"><a class="ml-1" style="font-size: 0.9rem;" href="Javascript: void(0);" id="btn_forgot_password"><i class='fas fa-caret-right'></i> Forgot password? Request to reset it.</a></label>

						</div>

						<div class="form-group form-inline">
							<button type='submit' class="btn btn-primary">Sign In</button>

							<?php if(isset($BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD']) && $BXAF_CONFIG['BXAF_USER_DEFAULT_PASSWORD'] != ''){ ?>
								<input type='checkbox' name='Use_Default_Password' id='Use_Default_Password' class='form-control ml-3 mr-1' value='1' checked='checked' onClick="if(! $(this).prop('checked') ) { $('#section_password').removeClass('hidden'); } else { $('#section_password').addClass('hidden'); } "> <span>Use default password</span>
							<?php }  ?>

							<?php
								if ($BXAF_CONFIG['GUEST_ACCOUNT'] != '' || $BXAF_CONFIG['USER_SIGNUP_ENABLE']){

									echo "<div class='pt-3 w-100'>";

									if ($BXAF_CONFIG['USER_SIGNUP_ENABLE']){
										echo "<a class='btn btn-link' href='" . $BXAF_CONFIG['BXAF_SIGNUP_PAGE'] . "'><i class='fas fa-user-plus'></i> Sign Up</a>";
									}

									if ($BXAF_CONFIG['GUEST_ACCOUNT'] != ''){
										echo "<a class='btn btn-link' href='" . $BXAF_CONFIG['BXAF_LOGIN_PAGE'] . "?action=guest'><i class='fas fa-sign-in-alt'></i> Sign In as Guest</a>";
									}

									echo "</div>";
								}
							?>

						</div>

						<?php
							if ($info_message != '') {
								echo "<div class='alert alert-danger my-3 py-3 w-100'>{$info_message}</div>";
							}
						?>

					</form>



	        </div>

		</div>

	</div>

</BODY>
</HTML>