<?php
    function db_connect(){
		$conn = mysqli_connect("localhost", "root", "eee396296", "Final_Project");
		if(!$conn){
			echo "無法使用資料庫" . mysqli_connect_error($conn);
			exit;
		}
		return $conn;
	}

?>