<?php  

function get_data() {
	include("db_connection.php");

	$statement = $connection -> prepare("SELECT * FROM tbl_users");
	$statement -> execute();

	return $statement -> rowCount();

}

function profile_upload() {
	$extension = explode(".", $_FILES['user_image']['name']);
	$new_name = rand() . "." . $extension[1];
	$destination = './upload/' . $new_name;
	move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
	return $new_name;
}

?>