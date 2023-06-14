<?php
require_once 'database_function.php';

$conn = db_connect();

$action = $_GET['action'];
$city = $_GET['city']; // 更改 county 為 city

switch ($action) {
    case 'getCounties':
        getCounties($conn);
        break;
    case 'getDistricts':
        getDistricts($conn, $city);
        break;
    case 'getCenters':
        $district = $_GET['dist'];
        getCenters($conn, $city, $district);
        break;
    default:
        http_response_code(404);
        break;
}


function getCounties($conn) {
    $query = "SELECT DISTINCT city FROM Taiwan_city_dist";
    $result = mysqli_query($conn, $query);

    $counties = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $counties[] = $row['city'];
    }

    echo json_encode($counties);
}

function getDistricts($conn, $city) {
    $city = mysqli_real_escape_string($conn, $city); // 避免 SQL Injection
    $query = "SELECT dist, latitude, longitude FROM Taiwan_city_dist WHERE city = '$city'";
    $result = mysqli_query($conn, $query);

    $districts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $districts[] = $row;
    }

    echo json_encode($districts);
}

function getCenters($conn, $city, $district) {
    $query = "SELECT * FROM ins_address WHERE city = ? AND dist = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $city, $district);
    $stmt->execute();
    $result = $stmt->get_result();
    $centers = array();
    while ($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }
    echo json_encode($centers);
}
?>
