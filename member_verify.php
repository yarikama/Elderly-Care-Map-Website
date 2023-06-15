<?php
	session_start();
	if(!isset($_POST['member_submit'])){
		echo "發生錯誤！";
		exit;
	}
	require_once "./function/database_function.php";
	$conn = db_connect();

	$name = trim($_POST['member_email']);
	$pass = trim($_POST['member_password']);

	if($name == "" || $pass == ""){
		echo "Name or Pass is empty!";
		exit;
	}

	$name = mysqli_real_escape_string($conn, $name);
	$pass = mysqli_real_escape_string($conn, $pass);
	$pass = sha1($pass);

	// get from db
	$query = "SELECT * from `member` where `member_email` = '{$name}' and `member_password` ='{$pass}'";
	$result = mysqli_query($conn, $query);
	if($result->num_rows <= 0){
		$_SESSION['err_login'] = "Incorrect Username or Password";
		header("Location: member_login.php");
		exit;
	}

    $row = mysqli_fetch_assoc($result);

	if(isset($conn)) {mysqli_close($conn);}
	$_SESSION['member'] = $row;

	if(isset($_SESSION['member']['member_type']) && $_SESSION['member']['member_type'] == 2):
		header("Location: manage_system.php");
	elseif(isset($_SESSION['member']) && $_SESSION['member'] == true):
		header("Location: index.php");
	endif;		
?>