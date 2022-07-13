<?php

include_once(dirname(__FILE__) . "/config.php");

if(! isset($_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']]) || $_SESSION[$BXAF_CONFIG['BXAF_LOGIN_KEY']] != 'admin'){
	header("Location: login_admin.php");
	exit();
}


unset($data);
if ($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'sqlite'){
	$BXAF_CONN = bxaf_get_user_db_connection();

	$sql = "SELECT * FROM {$BXAF_CONFIG['TBL_BXAF_LOGIN']} ORDER BY Name";
	$results = $BXAF_CONN->query($sql);

	if ($results){
		while($row = $results->fetchArray(SQLITE3_ASSOC)){
			$data[] = $row;
		}
	}
	$BXAF_CONN->close();

} else if($BXAF_CONFIG['BXAF_DB_DRIVER'] == 'mysql'){

	$BXAF_CONN = bxaf_get_user_db_connection();

	$sql = "SELECT * FROM `{$BXAF_CONFIG['TBL_BXAF_LOGIN']}` ORDER BY `Name`";
	$data = $BXAF_CONN->get_all($sql);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery-form'] = true;
		$BXAF_CONFIG['BXAF_PAGE_HEADER_LIBRARIES']['jquery-tabledit'] = true;
		include_once('page_header.php');
	?>

	<script type="text/javascript">

	$(document).ready(function(){

		<?php if(is_array($data) && count($data) > 0) { ?>
		$('#list_users').Tabledit({
		    url: 'user_admin_exe.php',
			// rowIdentifier: 'data-id',
			// deleteButton: false,
		    // saveButton: false,
			// editButton: false,
		    // autoFocus: false,
			// hideIdentifier: true,
			// restoreButton: false,
		    buttons: {
		        edit: {
		            class: 'btn btn-sm btn-primary mr-1',
		            html: '<i class="fas fa-edit"></i> EDIT',
		            action: 'edit'
		        },
				delete: {
		            class: 'btn btn-sm btn-danger mr-1',
		            html: '<i class="fas fa-times"></i> DELETE',
		            action: 'delete'
		        },
		        confirm: {
		            class: 'btn btn-sm btn-success mr-1',
		            html: '<i class="fas fa-check-circle"></i> Confirm'
		        }
		    },
		    columns: {
		        identifier: [0, 'ID'],
		        editable: [[1, 'Name'], [2, 'Email'], [3, 'Login_Name'], [4, 'Password'], [5, 'Category']]
		    },
			onAlways: function() {
        		window.location = 'user_admin.php';
    		}
		});

		<?php } ?>

		$('.action_recover').click(function(){

 			var record_id = $(this).attr('record_id');

			$.ajax({
				method: 'post',
				url: 'user_admin_exe.php',
				data: {'action': 'recover', 'ID': record_id },
				success: function(responseText){
        			window.location = 'user_admin.php';
				}
			});

		});

		var options = {
			url: 'user_admin_exe.php?action=new_account',
			type: 'post',
				beforeSubmit: function(formData, jqForm, options) {
					if($('#Name').val() == '' || $('#Email').val() == '' || $('#Login_Name').val() == '' || $('#Password').val() == ''){
						bootbox.alert("Please enter all fields to create an account.");
						return false;
					}
					return true;
				},
				success: function(responseText, statusText){
					if(responseText == ''){
						window.location = 'user_admin.php';
					} else {
						bootbox.alert(responseText);
					}

					return true;
				}
		};
		$('#form_new_account').ajaxForm(options);

	});

	</script>
</head>

<body>
	<?php include_once('page_menu.php'); ?>


<div class="container-fluid">
	<div class="row p-2">

		<h4 class="py-2 w-100">Create New User Account</h4>

		<div class="px-1 mt-2 w-100">
			<form class="form-inline" id="form_new_account">
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="Name" id="Name" placeholder="Name (First Last)">
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" style="min-width: 15rem;" name="Email" id="Email" placeholder="E-mail, e.g., test@test.edu">
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" style="min-width: 15rem;" name="Login_Name" id="Login_Name" placeholder="Login Name">
				<input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="Password" id="Password" placeholder="Password">
				<button type="submit" class="btn btn-primary">Create Account</button>
			</form>
		</div>


<?php if(is_array($data) && count($data) > 0) { ?>

		<h4 class="mt-5 py-2 w-100">List of Existing Accounts</h4>

		<div class="w-100 table-responsive">

			<table class="table table-responsive table-bordered table-hover table-sm" id="list_users">
				<thead>
					<tr class="bg-success table-inverse">
                    	<th class="text-center">ID</th>
						<th>Name</th>
						<th>E-mail</th>
						<th>Login Name</th>
						<th>Password</th>
						<th>Category</th>
						<th>Status</th>
					</tr>

				</thead>

				<tbody>

					<?php
						foreach($data as $row){
							echo "<tr style='" . ($row['bxafStatus'] == 9 ? "text-decoration: line-through;" : "") . "'>";
								echo "<td class='text-center'>" . $row['ID'] . "</td>";
								echo "<td>" . $row['Name'] . "</td>";
								echo "<td>" . $row['Email'] . "</td>";
								echo "<td>" . $row['Login_Name'] . "</td>";
								echo "<td>" . "</td>";
								echo "<td>" . $row['Category'] . "</td>";
								echo "<td>" . ($row['bxafStatus'] == 9 ? "<a href='Javascript: void(0);' record_id='" . $row['ID'] . "' class='action_recover btn btn-sm btn-warning'><i class='fas fa-undo'></i> Recover</a>" : "Active") . "</td>";
							echo "</tr>";
						}
					?>

			    </tbody>
			</table>

		</div>
<?php } ?>


	</div>

</div>

</BODY>
</HTML>