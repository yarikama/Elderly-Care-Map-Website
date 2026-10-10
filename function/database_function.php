<?php
require_once __DIR__ . "/../config.php";
    function db_connect(){
		$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		if(!$conn){
			echo "無法使用資料庫" . mysqli_connect_error($conn);
			exit;
		}
		return $conn;
	}

?>