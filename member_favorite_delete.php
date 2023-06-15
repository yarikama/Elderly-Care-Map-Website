<?php
    $ins_num = $_GET['insnum'];
    echo "insnum=".$ins_num;
    require_once "./function/database_function.php";
    $conn = db_connect();

    // Delete
    $query = "DELETE FROM `member_favorite` WHERE ins_num = '$ins_num';";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: member_favorite.php");
        exit;
    } else {
        $error_message = mysqli_error($conn);
        error_log("Failed to delete list: " . $error_message);
        echo "Failed to delete list. Please check the error logs for more information.";
    }
?>