<?php  

	include("db_connection.php");
	include("functions.php");

	if (isset($_POST["operation"])) {

		if ($_POST["operation"] == "Add") {

		$image = '';
		if ($_FILES["user_image"]["name"] != '') {
			$image = profile_upload();
		}
		$statement = $connection -> prepare('
			INSERT INTO tbl_users (first_name, last_name, image)
			VALUES (:first_name, :last_name, :image)
			');
		$result = $statement -> execute(
			array(
				':first_name' 	=> $_POST["first_name"],
				':last_name' 	=> $_POST["last_name"],
				':image' 		=> $image
			)
		);
		if (!empty($result)) {
			echo "Data Inserted";
		}
	}

	if ($_POST["operation"] == "Edit") {
		$image = '';
		if ($_FILES["user_image"]["name"] != '') {
			$image = profile_upload();
		}
		else {
			$image = $_POST["hidden_user_image"];
		}

		$statement = $connection -> prepare(
			"UPDATE tbl_users
			SET first_name = :first_name, last_name = :last_name, image = :image
			WHERE id = :id
			"
		);
		$statement -> execute(
			array(
				':first_name' 	=> $_POST["first_name"],
				':last_name'  	=> $_POST["last_name"],
				':image'		=> $image,
				':id'			=> $_POST["user_id"]
			)
		);
		echo 'Data Updated';
	}

}
?>