<?php
	$ins_num = $_GET['insnum'];

	require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();

	$query = "DELETE FROM ins_address WHERE ins_num = '$ins_num';
            DELETE FROM ins_capacity WHERE ins_num = '$ins_num';
            DELETE FROM ins_info WHERE ins_num = '$ins_num';
            DELETE FROM type_func WHERE ins_num = '$ins_num';
            DELETE FROM member_favorite WHERE ins_num = '$ins_num';
            DELETE FROM institution WHERE ins_num = '$ins_num';";
	$result = mysqli_multi_query($conn, $query);
	if(!$result){
		echo "delete data unsuccessfully " . mysqli_error($conn);
		exit;
	}
	header("Location: manage_system.php");
?>