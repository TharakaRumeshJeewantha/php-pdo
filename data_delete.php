<?php  
	include("db_connection.php");
	include("functions.php");

	if (isset($_POST["user_id"])) {
		$statement = $connection -> prepare(
			"DELETE FROM tbl_users WHERE id = :id"
		);
		$result = $statement -> execute(
			array (
				':id' => $_POST["user_id"]
			)
		);
		echo "Data Deleted";
	}

?>