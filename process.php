<?php
	session_start();
	if(!isset($_SESSION['member']) or $_SESSION['member'] == false){
		header('location:member_login.php');
	}
	
	$_SESSION['err'] = 1;
	foreach($_POST as $key => $value){
		if(trim($value) == ''){
			$_SESSION['err'] = 0;
		}
		break;
	}

	if($_SESSION['err'] == 0){
		header("Location: purchase.php");
	} else {
		unset($_SESSION['err']);
	}

	require_once "./function/database_function.php";
	// print out header here
	$title = "加入喜愛列表";
	require "./template/header.php";

    if($_POST['ins_num']) $ins_num = $_POST['ins_num'];
	$customerid = $_SESSION['member']['member_id'];

	$conn = db_connect();
	$sql_check = "SELECT * FROM `member_favorite` WHERE member_id = '{$customerid}' AND ins_num = '{$ins_num}'";
	$result_check = mysqli_query($conn, $sql_check);
	if (mysqli_num_rows($result_check) > 0) {
		// The record already exists, so you can handle this case as you want.
		// For example, redirect back to the previous page with an error message.
		$_SESSION['err_msg'] = 'The item is already in the favorite list.';
		header("Location:  member_favorite.php"); // Replace 'previous_page.php' with the page you want to redirect to
		exit;
	}
	
	
	$sql = "INSERT INTO `member_favorite` (member_id, ins_num) VALUES ('{$customerid}', '{$ins_num}')";
	$result = mysqli_query($conn, $sql);
	
	if(!$result){
		echo "Insert value false!" . mysqli_error($conn);
		exit;
	}else{
		header ("Location: member_favorite.php");
	}
	
?>

<?php
	if(isset($conn)){
		mysqli_close($conn);
	}
	require_once "./template/footer.php";
?>