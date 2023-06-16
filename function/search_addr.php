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
    $query2 = "SELECT i.*, a.*, ic.*, info.* FROM institution i 
              JOIN ins_address a ON i.ins_num = a.ins_num 
              JOIN ins_capacity ic ON i.ins_num = ic.ins_num
              JOIN ins_info info ON i.ins_num = info.ins_num
              WHERE a.city = '$city'";
    $result2 = mysqli_query($conn, $query2);

    $careCenters = array();
    while ($row = mysqli_fetch_assoc($result2)) {
        $careCenters[] = $row;
    }

    // Combine districts and care centers into one array
    $data = array("districts" => $districts, "careCenters" => $careCenters);

    echo json_encode($data);
}

function getCenters($conn, $city, $district) {
    $query = "SELECT i.*, a.*, ic.*, info.* FROM institution i 
              JOIN ins_address a ON i.ins_num = a.ins_num 
              JOIN ins_capacity ic ON i.ins_num = ic.ins_num
              JOIN ins_info info ON i.ins_num = info.ins_num
              WHERE a.city = ? AND a.dist = ?";
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