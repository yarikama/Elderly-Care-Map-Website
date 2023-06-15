<?php
require_once 'database_function.php';

$conn = db_connect();

$action = $_GET['action'];
$county = $_GET['county'];

switch ($action) {
    case 'getCounties':
        getCounties($conn);
        break;
    case 'getDistricts':
        getDistricts($conn, $county);
        break;
    case 'getCenters':
        $district = $_GET['district'];
        getCenters($conn, $county, $district);
        break;
    default:
        http_response_code(404);
        break;
}


function getCounties($conn) {
    echo "getCounties";
    $query = "SELECT DISTINCT city FROM ins_address";
    $result = mysqli_query($conn, $query);

    $counties = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $counties[] = $row['city'];
    }

    echo json_encode($counties);
}

function getDistricts($conn, $county) {
    $county = mysqli_real_escape_string($conn, $county); // 避免 SQL Injection
    $query = "SELECT DISTINCT dist FROM ins_address WHERE city = '$county'";
    $result = mysqli_query($conn, $query);

    $districts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $districts[] = $row['dist'];
    }

    echo json_encode($districts);
}

function getCenters($conn, $county, $district) {
    $query = "SELECT * FROM ins_address WHERE city = ? AND dist = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $county, $district);
    $stmt->execute();
    $result = $stmt->get_result();
    $centers = array();
    while ($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }
    echo json_encode($centers);
}