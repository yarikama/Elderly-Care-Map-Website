<?php
session_start();
require_once "./function/database_function.php";

if (isset($_POST['member_submit'])) {

    // Fetch data from form
    $member_email = $_POST['member_email'];
    $member_password = $_POST['member_password'];
    $member_pass_confirm = $_POST['member_pass_confirm'];
    $member_name = $_POST['member_name'];
    $member_gender = $_POST['member_gender'];
    $member_phone = $_POST['member_phone'];
    $member_address = $_POST['member_address'];
    $member_birthday = $_POST['member_birthday'];
    $member_type = 1; // Set default member type here

    // Check if passwords match
    if ($member_password == $member_pass_confirm) {

        // Hash the password
        $member_password = sha1($member_password);

        // Get database connection
        $conn = db_connect();

        // Check if the email has already been registered
        $checkEmail = "SELECT * FROM `member` WHERE `member_email` = '{$member_email}'";
        $result = $conn->query($checkEmail);
        if ($result->num_rows > 0) {
            $_SESSION['error_message'] = "已經註冊過了，註冊失敗";
            header("Location: member_register.php");
            exit;
        }

        // Prepare the SQL statement
        $sql = "INSERT INTO `member` (member_email, member_password, member_name, member_gender, member_phone, 
        member_address, member_birthday, member_type) VALUES ('{$member_email}', '{$member_password}', '{$member_name}', 
        '{$member_gender}', '{$member_phone}', '{$member_address}', '{$member_birthday}', '{$member_type}')";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "成功註冊";

            $conn = db_connect();
            $sql = "SELECT * FROM `member` ORDER BY member_id DESC LIMIT 1;";
            $result1 = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result1);
            $member_id = $row['member_id'];
            if(!$result1){
                echo "select value false!" . mysqli_error($conn);
                exit;
            }
            header("Location: member_login.php"); // Redirect user to the login page
            exit;
        } else {
            $_SESSION['error_message'] = "註冊失敗： " . $conn->error;
        }

        $stmt->close();
        $conn->close();

    } else {
        $_SESSION['error_message'] = "密碼不一致";
    }
}
header("Location: member_register.php"); // Redirect back to the register page in case of error
exit;
?>
