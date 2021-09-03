<?php



// Important: disable login requirement

$BXAF_CONFIG_CUSTOM['PAGE_LOGIN_REQUIRED']	= true;

$BXAF_CONFIG_CUSTOM['BXAF_PAGE_SPLIT']		= false;



include_once(dirname(__FILE__) . "/config.php");







if (is_array($_POST) && count($_POST) > 0){



	$_POST 			= array_map('trim', $_POST);



	$_POST['Email'] = strtolower(trim($_POST['Email']));

	$_POST['Login_Name'] = strtolower(trim($_POST['Login_Name']));



	$info_message = "";

	if($_POST['First_Name'] == '' || $_POST['Last_Name'] == '' || $_POST['Email'] == '' || $_POST['Login_Name'] == '' || $_POST['Password'] == ''){

		$info_message = "These fields are required: First Name, Last Name, E-mail, Login Name, and Password.";

	}

	else if (! bxaf_validate_email($_POST['Email'])){

		$info_message = "The email address ({$_POST['Email']}) is invalid.";

	}

	else if ($_POST['Login_Name'] == $BXAF_CONFIG['ADMIN_ACCOUNT'] || $_POST['Login_Name'] == $BXAF_CONFIG['GUEST_ACCOUNT'] || $_POST['Login_ID'] != intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']])){

		$info_message = "You can not update this account.";

	}

	else if ($_POST['Password1'] != $_POST['Password2']){

		$info_message = "You new passwords do not match each other.";

	}

	else {

		$BXAF_CONN = bxaf_get_user_db_connection();



		if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

			$sql = "SELECT ID FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE (Email = '" . addslashes($_POST['Email']) . "' OR Login_Name = '" . addslashes($_POST['Login_Name']) . "') AND ID != " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]);

			$found_id_from_email = $BXAF_CONN->querySingle($sql);



			if ($found_id_from_email > 0){

				$info_message = "Your email address and/or login name has been taken.";

			}

			else {

				$sql = "SELECT Password FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE ID = " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) . " AND (`bxafStatus` IS NULL OR `bxafStatus` < 5)";

				$password = $BXAF_CONN->querySingle($sql);



				if ($password != md5($_POST['Password'])){

					$info_message = "You current password does not match any system records.";

				}

			}

		}

		else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){



			$sql = "SELECT `ID` FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE (`Email` = '" . addslashes($_POST['Email']) . "' OR `Login_Name` = '" . addslashes($_POST['Login_Name']) . "') AND `ID` != " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]);

			$found_id_from_email = $BXAF_CONN->get_one($sql);



			if ($found_id_from_email > 0){

				$info_message = "Your email address and/or login name has been taken.";

			}

			else {

				$sql = "SELECT `Password` FROM " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " WHERE `ID` = " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) . " AND (`bxafStatus` IS NULL OR `bxafStatus` < 5)";

				$password = $BXAF_CONN->get_one($sql);



				if ($password != md5($_POST['Password'])){

					$info_message = "You current password does not match any system records.";

				}

			}



		}



		if ($info_message == ''){



			$sql = "UPDATE " . $BXAF_CONFIG['TBL_BXAF_LOGIN'] . " SET ";

			$sql .= "  Name = '" . addslashes($_POST['First_Name'] . ' ' . $_POST['Last_Name']) . "'";

			$sql .= ", First_Name = '" . addslashes($_POST['First_Name']) . "'";

			$sql .= ", Last_Name = '" . addslashes($_POST['Last_Name']) . "'";

			$sql .= ", Login_Name = '" . addslashes($_POST['Login_Name']) . "'";

			$sql .= ", Email = '" . addslashes($_POST['Email']) . "'";

			$sql .= ", Phone = '" . addslashes($_POST['Phone']) . "'";

			$sql .= ", Organization = '" . addslashes($_POST['Organization']) . "'";

			$sql .= ", Subdivision = '" . addslashes($_POST['Subdivision']) . "'";

			$sql .= ", Group_Name = '" . addslashes($_POST['Group_Name']) . "'";



			if($_POST['Password1'] == $_POST['Password2'] && $_POST['Password1'] != '') $sql .= ", Password = '" . addslashes(md5($_POST['Password1'])) . "'";



			$sql .= " WHERE ID = " . intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) . " AND Password = '" . addslashes(md5($_POST['Password'])) . "'";



			if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

				$BXAF_CONN->exec($sql);

				$BXAF_CONN->close();

			}

			else if($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

				$BXAF_CONN->Execute($sql);

			}



			$info_message = "Your account information has been saved.";

		}



	}



}











$user_info = array();



$login_id = intval($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]);



$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} WHERE ID = $login_id AND (bxafStatus IS NULL OR bxafStatus < 5)";

$BXAF_CONN = bxaf_get_user_db_connection();



if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){

	$user_info = $BXAF_CONN->querySingle($sql, true);

	$BXAF_CONN->close();

}

else if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

	$user_info = $BXAF_CONN->get_row($sql);

}



if(! is_array($user_info) || count($user_info) <= 0){
	header("Location: " . $BXAF_CONFIG['BXAF_LOGIN_PAGE']);
	exit();
}



?>

<!DOCTYPE html>

<html lang="en">

<head>

	<?php

		include_once('page_header.php');

	?>

</head>



<body>





<?php include_once('page_menu.php'); ?>





<div class="container-fluid">

	<div class="row mt-5 p-2">



        <div class="mx-auto" style="width: 350px;">



			<h3 class="w-100 py-2 text-center">My account information</h3>



			<?php if ($login_id > 0 && $user_info['Login_Name'] != $BXAF_CONFIG['GUEST_ACCOUNT'] && $user_info['Login_Name'] != $BXAF_CONFIG['ADMIN_ACCOUNT'] && isset($_GET['action']) && $_GET['action'] == 'update'){ ?>



				<form action='<?php echo $_SERVER['PHP_SELF']; ?>?action=update' method='post' role='form'>



					<div class="row mt-3">

						<div class="col-4 text-right px-0 pt-2 font-weight-bold">First Name:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='First_Name' value='<?php echo $user_info['First_Name']; ?>' autofocus required></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Last Name:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Last_Name' value='<?php echo $user_info['Last_Name']; ?>' required></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">E-mail:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Email' value='<?php echo $user_info['Email']; ?>' required></div>



						<div class="col-4 text-right px-0 pt-2">Phone:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Phone' value='<?php echo $user_info['Phone']; ?>'></div>



						<div class="w-100">&nbsp;</div>



						<div class="col-4 text-right px-0 pt-2">Organization:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Organization' value='<?php echo $user_info['Organization']; ?>'></div>



						<div class="col-4 text-right px-0 pt-2">Department:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Subdivision' value='<?php echo $user_info['Subdivision']; ?>'></div>



						<div class="col-4 text-right px-0 pt-2">Group Name:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Group_Name' value='<?php echo $user_info['Group_Name']; ?>'></div>



						<div class="w-100">&nbsp;</div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Login Name:</div>

						<div class="col-8 mt-2"><input type='text' class='form-control form-control-sm' name='Login_Name' value='<?php echo $user_info['Login_Name']; ?>' required></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Current Password:</div>

						<div class="col-8 mt-2"><input type='password' class='form-control form-control-sm' name='Password' value='' placeholder="* Your current password" required></div>



						<div class="col-4 text-right px-0 pt-2">New Password:</div>

						<div class="col-8 mt-2"><input type='password' class='form-control form-control-sm' name='Password1' value='' placeholder="Your new password"></div>



						<div class="col-4 text-right px-0 pt-2">Re-enter:</div>

						<div class="col-8 mt-2"><input type='password' class='form-control form-control-sm' name='Password2' value='' placeholder="Re-enter your new password"></div>



						<div class="col-12 text-right mt-2 text-muted">Note: your profile won't be updated unless you enter the correct current password.</div>



						<div class="w-100">&nbsp;</div>



						<div class="col-4">&nbsp;</div>

						<div class="col-8 mt-2">

							<input type='hidden' name='Login_ID' value='<?php echo $login_id; ?>'>

							<button type='submit' class='btn btn-primary'>Save Changes</button>

							<a class='btn btn-sm btn-default' href='<?php echo $_SERVER['PHP_SELF']; ?>'><i class='fas fa-undo'></i> Cancel</a>

						</div>



					</div>



					<?php

						if ($info_message != ''){

							echo "<div class='row mt-3 alert alert-danger'>{$info_message}</div>";

						}

					?>

				</form>

			<?php } ?>





			<?php if (! isset($_GET['action']) || $_GET['action'] != 'update'){ ?>



					<div class="row mt-3">

						<div class="col-4 text-right px-0 pt-2 font-weight-bold">First Name:</div>

						<div class="col-8 mt-2"><?php echo $user_info['First_Name']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Last Name:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Last_Name']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">E-mail:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Email']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Phone:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Phone']; ?></div>



						<div class="w-100">&nbsp;</div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Organization:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Organization']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Department:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Subdivision']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Group:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Group_Name']; ?></div>



						<div class="w-100">&nbsp;</div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Login Name:</div>

						<div class="col-8 mt-2"><?php echo $user_info['Login_Name']; ?></div>



						<div class="col-4 text-right px-0 pt-2 font-weight-bold">Password:</div>

						<div class="col-8 mt-2">***</div>



						<?php if($login_id > 0 && $user_info['Login_Name'] != $BXAF_CONFIG['GUEST_ACCOUNT'] && $user_info['Login_Name'] != $BXAF_CONFIG['ADMIN_ACCOUNT']){ ?>

							<div class="w-100">&nbsp;</div>

							<div class="col-4">&nbsp;</div>

							<div class="col-8 mt-2"><a class='btn btn-sm btn-primary' href='<?php echo $_SERVER['PHP_SELF']; ?>?action=update'><i class='fas fa-edit'></i> Update</a></div>

						<?php } ?>

					</div>



			<?php } ?>



        </div>



	</div>



</div>



</BODY>

</HTML>