<?php
include_once(dirname(__FILE__) . "/config.php");

if(	isset($BXAF_CONFIG['BXAF_LOGIN_SUCCESS']) &&
	$BXAF_CONFIG['BXAF_LOGIN_SUCCESS'] != '' &&
	$BXAF_CONFIG['BXAF_LOGIN_SUCCESS'] != (rtrim($BXAF_CONFIG['BXAF_ROOT_URL'], '/') . $_SERVER['PHP_SELF'])  &&
	$BXAF_CONFIG['BXAF_LOGIN_SUCCESS'] != $_SERVER['PHP_SELF']){

	header("Location: " . $BXAF_CONFIG['BXAF_LOGIN_SUCCESS']);
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
		<div class="row">

			<div class="mx-auto text-center">
				<h3 class="pt-5">Hello, <?php echo $_SESSION['User_Info']['First_Name']; ?>!</h3>
				<p class=" py-3">Please select an action from the Admin menu.</p>
			</div>

		</div>
	</div>
</body>
</html>