<?php
require_once './function/database_function.php';

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if request method is POST and variables are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($data["selectArea"]) && isset($data["typeAddress"])) {
    $area = $data["selectArea"];
    $address = $data["typeAddress"];
} else {
    http_response_code(400);
    exit();
}

// Create database connection
$conn = db_connect();

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare SQL statement
$stmt = $conn->prepare(
    "SELECT * FROM ins_address, institution WHERE
    ins_address.add_id = institution.ins_add_id
    and ((city = ? and dist = ?) or `add` like ?)"
);

// Bind parameters
$city = substr($area, 0, 3);
$dist = substr($area, 3, 3);
$address = "%$address%";

$stmt->bind_param("sss", $city, $dist, $address);

// Execute statement
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Check result
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Create result array
$ResultData = array();

while ($row = $result->fetch_assoc()) {
    $ResultData[] = $row;
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($ResultData);

    /*require_once 'database_function.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $area = $_POST["selectArea"];
        $address = $_POST["typeAddress"];
    }
    $conn = db_connect();

    $sql = "SELECT * FROM ins_address, institution WHERE
    ins_address.add_id = institution.ins_add_id
    and ( (city = substr($area, 0, 3) and dist = substr($area, 3, 3) ) or `add` like '%$address%')";

    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $ResultData = array();

    while($row = mysqli_fetch_assoc($result)){
        $ResultData[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($ResultData);

    
    */

?>