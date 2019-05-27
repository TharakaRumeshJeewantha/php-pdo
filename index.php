<!DOCTYPE html>
<html>
<head>
	<title>PHP PDO</title>

	<script src="js/jquery.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script src="js/jquery.dataTables.min.js" type="text/javascript"></script>
	
	<script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.min.css">
	<script src="js/bootstrap.min.js" type="text/javascript"></script>

<style type="text/css">
	body {
		margin: 0;
		padding: 0;
		background-color: #f1f1f1;
	}
	.box {
		width: 1270px;
		padding: 20px;
		background-color: #fff;
		border:1px solid #ccc;
		border-radius: 5px;
		margin-top: 25px;
	}
</style>

</head>
<body>

	<div class="container box">
		<h1 align="center">PHP PDO CRUD Application</h1><hr>

	<div align="right">
		<button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-xs">+ Add</button> 
	</div>
	<br>
	
	<div class="table-responsive">
		<table id="user_data" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th width="10%">Image</th>
					<th width="35%">First Name</th>
					<th width="35%">Last Name</th>
					<th width="10%">Edit</th>
					<th width="10%">Delete</th>
				</tr>
			</thead>
		</table>
	</div>

	</div>

</body>
</html>

<script type="text/javascript" language="javascript">
	$(document).ready(function() {
		$('#add_button').click(function() {
			$('#user_form')[0].reset();
			$('.modal-title').text("Add User");
			$('#action').val("Add");
			$('#operation').val("Add");
			$('#user_uploaded_image').html('');
		});
		
		var dataTable = $('#user_data').DataTable({
			"processing" : true,
			"serverSide" : true,
			"order"		 : [],
			"ajax"       : {
				url    : "fetch.php",
				method : "POST"
			},
			"columnDefs" : [
				{
					"target"    : [0, 3, 4],
					"orderable" : false
				},
			],
		});

		$(document).on('submit','#user_form',function(event){
			event.preventDefault();
			var first_name = $('#first_name').val();
			var last_name  = $('#last_name').val();
			var extension  = $("#user_image").val().split('.').pop().toLowerCase();
			if (extension != '') {
				if (jQuery.inArray(extension,['gif','png','jpg','jpeg']) == -1 ) {
					alert("Invalid Image Type");
					$('#user_image').val('');
					return false;
				}
			}
			if (first_name != '' && last_name != '') {
				$.ajax ({
					url 		: "add-data.php",
					method 		: "POST",
					data 		: new FormData(this),
					contentType : false,
					processData : false,
					success 	: function(data)
					{
						alert(data);
						$('#user_form')[0].reset();
						$('#userModal').modal('hide');
						dataTable.ajax.reload();
					}
				});
			}
			else {
				alert("All Fields are Required");
			}
		});

		$(document).on('click', '.update', function() {
			var user_id = $(this).attr("id");
			$.ajax({
				url 	: "fetch_single.php",
				method 	: "POST",
				data 	: 
					{	
						user_id:user_id
					},
				dataType: "json",
				success : function(data)
				{
					$('#userModal').modal('show');
					$('#first_name').val(data.first_name);
					$('#last_name').val(data.last_name);
					$('.modal-title').text("Edit User");
					$('#user_id').val(user_id);
					$('#user_uploaded_image').html(data.user_image);
					$('#action').val("Edit");
					$('#operation').val("Edit");
				}
			});
		});

		$(document).on('click','.delete', function() {
			var user_id = $(this).attr("id");
			if (confirm("Are you sure you want to delete this ?")) {
				$.ajax({
					url 	: "data_delete.php",
					method 	: "POST",
					data    : 
						{
							user_id:user_id
						},
					success : function(data) {
						alert(data);
						dataTable.ajax.reload();
					}
				})
			}
			else {
				return false;
			}
		});

	});
</script>

<div id="userModal" class="modal fade">
	<div class="modal-dialog">
		<form id="user_form" method="POST" enctype="multipart/form-data">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add User</h4>
				</div>
				<div class="modal-body">
					<label>First Name</label>
					<input id="first_name" name="first_name" type="text" class="form-control"></input><br>
					<label>Last Name</label>
					<input id="last_name" name="last_name" type="text" class="form-control"></input><br>
					<label>Profile Image</label>
					<input id="user_image" name="user_image" type="file"></input>
					<span id="user_uploaded_image"></span>
				</div>
				<div class="modal-footer">
					<input id="user_id" name="user_id" type="hidden"></input>
					<input id="operation" name="operation" type="hidden"></input>
					<input id="action" type="submit" name="action" class="btn btn-success" value="Add"></input>
				</div>
			</div>
		</form>
	</div>
</div>