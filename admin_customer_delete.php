<?php
	$member_id = $_GET['memberid'];

	require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();

	$query = "DELETE FROM member_favorite WHERE member_id = '$member_id';
            DELETE FROM `member` WHERE member_id = '$member_id';";
	$result = mysqli_multi_query($conn, $query);
	if(!$result){
		echo "delete data unsuccessfully " . mysqli_error($conn);
		exit;
	}
	header("Location: manage_system.php");
?>