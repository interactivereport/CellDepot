<?php

include_once(dirname(__FILE__) . "/config.php");


$_POST = array_map('trim', $_POST);



$BXAF_CONN = bxaf_get_user_db_connection();

if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

	if(isset($_POST['action']) && $_POST['action'] == 'edit'){

		$_POST['ID'] = intval($_POST['ID']);
		$_POST['Name'] = trim($_POST['Name']);
		$_POST['Email'] = strtolower(trim($_POST['Email']));
		$_POST['Password'] = trim($_POST['Password']);
		$_POST['Login_Name'] = strtolower(trim($_POST['Login_Name']));
		$_POST['Category'] = trim($_POST['Category']);

		if($_POST['ID'] <= 0 || ! bxaf_validate_email($_POST['Email']) || $_POST['Name'] == '' || $_POST['Login_Name'] == '') exit();

		$sql = "SELECT ID FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE (Email = '" . addslashes($_POST['Email']) . "' OR Login_Name = '" . addslashes($_POST['Login_Name']) . "') AND ID != " . $_POST['ID'];
		$found_id_from_email = $BXAF_CONN->querySingle($sql);
		if ($found_id_from_email > 0){
			exit();
		}

		$firstname = array_shift(preg_split("/\s+/", $_POST['Name']));
		$lastname = array_pop(preg_split("/\s+/", $_POST['Name']));

		$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET Name = '" . addslashes($_POST['Name']) .
			"', First_Name = '" . addslashes($firstname) . "', Last_Name = '" . addslashes($lastname) .
			"', Login_Name = '" . addslashes($_POST['Login_Name']) . "', Email = '" . addslashes($_POST['Email']) .
			"', Category = '" . addslashes($_POST['Category']) . "'";

		if ($_POST['Password'] != '') $sql .= ", Password = '" . addslashes(md5($_POST['Password'])) . "'";

		$sql .= " WHERE ID=" . intval($_POST['ID']);

		$ret = $BXAF_CONN->exec($sql);

		exit();
	}

	else if(isset($_POST['action']) && $_POST['action'] == 'delete'){

		$_POST['ID'] = intval($_POST['ID']);

		if($_POST['ID'] <= 0) exit();

		$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET Status = 'Deleted', Status_Time = '" . date('Y-m-d H:i:s') . "', bxafStatus = 9 WHERE ID=" . $_POST['ID'];
		$ret = $BXAF_CONN->exec($sql);

		exit();
	}


	else if(isset($_POST['action']) && $_POST['action'] == 'recover'){

		$_POST['ID'] = intval($_POST['ID']);

		if($_POST['ID'] <= 0) exit();

		$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET Status = '', Status_Time = '" . date('Y-m-d H:i:s') . "', bxafStatus = 0 WHERE ID=" . $_POST['ID'];
		$ret = $BXAF_CONN->exec($sql);

		exit();
	}



	else if(isset($_GET['action']) && $_GET['action'] == 'new_account'){

		$_POST['Name'] = trim($_POST['Name']);
		$_POST['Email'] = strtolower(trim($_POST['Email']));
		$_POST['Login_Name'] = strtolower(trim($_POST['Login_Name']));
		$_POST['Password'] = trim($_POST['Password']);

		if(! bxaf_validate_email($_POST['Email']) || $_POST['Name'] == '' || $_POST['Login_Name'] == '' || $_POST['Password'] == '' ){
			echo "The account information is invalid. Please try again.";
			exit();
		}

		$sql = "SELECT ID FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE (Email = '" . addslashes($_POST['Email']) . "' OR Login_Name = '" . addslashes($_POST['Login_Name']) . "')";
		$found_id = $BXAF_CONN->querySingle($sql);
		if($found_id && intval($found_id) > 0){
			echo "An account with the email address/login name already exists. Please recover the account.";
			exit();
		}

		$firstname = array_shift(preg_split("/\s+/", $_POST['Name']));
		$lastname = array_pop(preg_split("/\s+/", $_POST['Name']));

		$sql = "INSERT INTO " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " (Name, Login_Name, Password, First_Name, Last_Name, Email) VALUES ('" .
			addslashes($_POST['Name']) . "', '" .
			addslashes($_POST['Login_Name']) . "', '" .
			addslashes(md5($_POST['Password'])) . "', '" .
			addslashes($firstname) . "', '" .
			addslashes($lastname) . "', '" .
			addslashes($_POST['Email']) . "' );";

		$ret = $BXAF_CONN->exec($sql);
		if(!$ret){
		   echo $BXAF_CONN->lastErrorMsg();
		} else {

			$body = "
				<p>Hello {$firstname},</p>

				<p>Here is your account information on <a href='{$BXAF_CONFIG['BXAF_WEB_URL']}' target='_blank'>{$BXAF_CONFIG['BXAF_PAGE_APP_NAME']}</a>:</p>

				<p>E-mail: <strong>{$_POST['Email']}</strong></p>
				<p>Password: <strong>{$_POST['Password']}</strong></p>

				<p>If you have any questions, please contact us at <a href='mailto:{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}'>{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}</a>.</p>

				<p>Thank you!</p>
				<p>{$BXAF_CONFIG['BXAF_PAGE_AUTHOR']}</p>
			";

			$email_param = array(
				'From'     => $BXAF_CONFIG['BXAF_PAGE_EMAIL'],
				'FromName' => $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
				'Subject'  => "Welcome to " . $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
				'Body'     => $body,
				'To'       => array($_POST['Email'] => $_POST['Name']),
			);

			$result = bxaf_send_email($email_param);

		}

		exit();
	}


	$BXAF_CONN->close();

}

else if($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

		if(isset($_POST['action']) && $_POST['action'] == 'edit'){

			$_POST['ID'] = intval($_POST['ID']);
			$_POST['Name'] = trim($_POST['Name']);
			$_POST['Email'] = strtolower(trim($_POST['Email']));
			$_POST['Login_Name'] = strtolower(trim($_POST['Login_Name']));
			$_POST['Password'] = trim($_POST['Password']);
			$_POST['Category'] = trim($_POST['Category']);

			if($_POST['ID'] <= 0 || ! bxaf_validate_email($_POST['Email']) || $_POST['Name'] == '') exit();

			$sql = "SELECT `ID` FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE (`Email` = '" . addslashes($_POST['Email']) . "' OR `Login_Name` = '" . addslashes($_POST['Login_Name']) . "') AND `ID` != " . $_POST['ID'];
			$found_id_from_email = $BXAF_CONN->get_one($sql);
			if ($found_id_from_email > 0){
				exit();
			}

			$firstname = array_shift(preg_split("/\s+/", $_POST['Name']));
			$lastname = array_pop(preg_split("/\s+/", $_POST['Name']));

			$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET `Name` = '" . addslashes($_POST['Name']) .
				"', `First_Name` = '" . addslashes($firstname) . "', `Last_Name` = '" . addslashes($lastname) .
				"', `Login_Name` = '" . addslashes($_POST['Login_Name']) . "', `Email` = '" . addslashes($_POST['Email']) . "', `Category` = '" . addslashes($_POST['Category']) . "'";

			if($_POST['Password'] != '') $sql .= ", `Password` = '" . addslashes(md5($_POST['Password'])) . "'";

			$sql .= " WHERE `ID` = " . intval($_POST['ID']);

			$BXAF_CONN->Execute($sql);

			exit();
		}

		else if(isset($_POST['action']) && $_POST['action'] == 'delete'){

			$_POST['ID'] = intval($_POST['ID']);

			if($_POST['ID'] <= 0) exit();

			$sql = "UPDATE `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` SET `Status` = 'Deleted', `Status_Time` = '" . date('Y-m-d H:i:s') . "', `bxafStatus` = 9 WHERE `ID` = " . $_POST['ID'];

			$BXAF_CONN->Execute($sql);

			exit();
		}


		else if(isset($_POST['action']) && $_POST['action'] == 'recover'){

			$_POST['ID'] = intval($_POST['ID']);

			if($_POST['ID'] <= 0) exit();

			$sql = "UPDATE `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` SET `Status` = '', `Status_Time` = '" . date('Y-m-d H:i:s') . "', `bxafStatus` = 0 WHERE `ID` = " . $_POST['ID'];

			$BXAF_CONN->Execute($sql);

			exit();
		}



		else if(isset($_GET['action']) && $_GET['action'] == 'new_account'){

			$_POST['Name'] = trim($_POST['Name']);
			$_POST['Email'] = strtolower(trim($_POST['Email']));
			$_POST['Login_Name'] = strtolower(trim($_POST['Login_Name']));
			$_POST['Password'] = trim($_POST['Password']);

			if(! bxaf_validate_email($_POST['Email']) || $_POST['Name'] == ''  || $_POST['Password'] == '' ){
				echo "The account information is invalid. Please try again.";
				exit();
			}

			$sql = "SELECT `ID` FROM `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` WHERE (`Email` = '" . addslashes($_POST['Email']) . "' OR `Login_Name` = '" . addslashes($_POST['Login_Name']) . "')";
			$found_id = $BXAF_CONN->get_one($sql);

			if($found_id && intval($found_id) > 0){
				echo "An account with the email address/login name already exists. Please recover the account.";
				exit();
			}

			$firstname = array_shift(preg_split("/\s+/", $_POST['Name']));
			$lastname = array_pop(preg_split("/\s+/", $_POST['Name']));

			$sql = "INSERT INTO `" . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . "` (`Name`, `Login_Name`, `Password`, `First_Name`, `Last_Name`, `Email`) VALUES ('" .
				addslashes($_POST['Name']) . "', '" .
				addslashes($_POST['Login_Name']) . "', '" .
				addslashes(md5($_POST['Password'])) . "', '" .
				addslashes($firstname) . "', '" .
				addslashes($lastname) . "', '" .
				addslashes($_POST['Email']) . "' );";

			$BXAF_CONN->Execute($sql);

			$body = "
				<p>Hello {$firstname},</p>

				<p>Here is your account information on <a href='{$BXAF_CONFIG['BXAF_WEB_URL']}' target='_blank'>{$BXAF_CONFIG['BXAF_PAGE_APP_NAME']}</a>:</p>

				<p>E-mail: <strong>{$_POST['Email']}</strong></p>
				<p>Password: <strong>{$_POST['Password']}</strong></p>

				<p>If you have any questions, please contact us at <a href='mailto:{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}'>{$BXAF_CONFIG['BXAF_PAGE_EMAIL']}</a>.</p>

				<p>Thank you!</p>
				<p>{$BXAF_CONFIG['BXAF_PAGE_AUTHOR']}</p>
			";

			$email_param = array(
				'From'     => $BXAF_CONFIG['BXAF_PAGE_EMAIL'],
				'FromName' => $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
				'Subject'  => "Welcome to " . $BXAF_CONFIG['BXAF_PAGE_APP_NAME'],
				'Body'     => $body,
				'To'       => array($_POST['Email'] => $_POST['Name']),
			);
			$result = bxaf_send_email($email_param);

			exit();
		}


}

exit();

?>