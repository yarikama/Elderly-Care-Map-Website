<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'database_function.php';

$conn = db_connect();

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/getCounties' :
        getCounties($conn);
        break;
    case strpos($request,'/getDistricts/') !== false:
        getDistricts($conn, explode('/',$request)[2]);
        break;
    default:
        http_response_code(404);
        break;
}

function getCounties($conn) {
    $query = "SELECT DISTINCT city FROM ins_address";
    $result = mysqli_query($conn, $query);

    $counties = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $counties[] = $row['city'];
    }

    echo json_encode($counties);
}

function getDistricts($conn, $county) {
    $query = "SELECT DISTINCT dist FROM ins_address WHERE city = '".mysqli_real_escape_string($conn, $county)."'";
    $result = mysqli_query($conn, $query);

    $districts = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $districts[] = $row['dist'];
    }

    echo json_encode($districts);
}
?>
