<?php
	session_start();

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

	require_once "./functions/database_functions.php";
	// print out header here
	$title = "加入喜愛列表";
	require "./template/header.php";

    if($_POST['ins_num']) $ins_num = $_POST['ins_num'];
	$customerid = $_SESSION['member']['member_ID'];

	$conn = db_connect();
	$sql = "INSERT INTO `member_favorite` (member_ID, ins_num) VALUES ('{$customerid}', '{$ins_num}')";
	$result = mysqli_query($conn, $sql);

	// take orderid from order to insert order items
	
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