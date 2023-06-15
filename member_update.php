<?php
session_start();

if (isset($_SESSION['member']) && $_SESSION['member'] == true) {
    // connect to database
    require_once "./function/database_function.php";
    $conn = db_connect();

    // Check if the form was submitted
    if (isset($_POST['member_submit'])) {
        $member_email = $_POST['member_email'];
        $member_name = $_POST['member_name'];
        $member_gender = $_POST['member_gender'];
        $member_phone = $_POST['member_phone'];
        $member_address = $_POST['member_address'];
        $member_birthday = $_POST['member_birthday'];

        // Prepare the SQL statement
        $sql = "UPDATE `member` SET 
            `member_email` = '{$_SESSION['member']['member_email']}',
            `member_name` = '{$_SESSION['member']['member_name']}',
            `member_gender` = '{$_SESSION['member']['member_gender']}',
            `member_phone` = '{$_SESSION['member']['member_phone']}',
            `member_address` = '{$_SESSION['member']['member_address']}',
            `member_birthday` = '{$_SESSION['member']['member_birthday']}'";

        // Check if there are new values for each field and update the SQL statement
        if (!empty($member_email)) {
            $_SESSION['member']['member_email'] = $member_email;
            $sql .= ", `member_email` = '$member_email'";
        }
        if (!empty($member_name)) {
            $_SESSION['member']['member_name'] = $member_name;
            $sql .= ", `member_name` = '$member_name'";
        }
        if (!empty($member_gender)) {
            $_SESSION['member']['member_gender'] = $member_gender;
            $sql .= ", `member_gender` = '$member_gender'";
        }
        if (!empty($member_phone)) {
            $_SESSION['member']['member_phone'] = $member_phone;
            $sql .= ", `member_phone` = '$member_phone'";
        }
        if (!empty($member_address)) {
            $_SESSION['member']['member_address'] = $member_address;
            $sql .= ", `member_address` = '$member_address'";
        }
        if (!empty($member_birthday)) {
            $_SESSION['member']['member_birthday'] = $member_birthday;
            $sql .= ", `member_birthday` = '$member_birthday'";
        }
        $sql .= " WHERE `member_id` = '{$_SESSION['member']['member_id']}'";
        // Execute the SQL statement
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success_message'] = "會員資訊已更新";
        } else {
            $_SESSION['error_message'] = "更新會員資訊時發生錯誤：" . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    }
}

header("Location: member_info.php");
