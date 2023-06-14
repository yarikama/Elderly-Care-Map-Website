<?php
    //echo $_SERVER["REQUEST_METHOD"]."<br>";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $area = $_POST["selectArea"];
        $address = $_POST["typeAddress"];
    }
    $area = $_POST["selectArea"];
    $address = $_POST["typeAddress"];
    if($address!=''){
        echo address;
    }
    if($area!=''){
        echo area;
    }
    $conn = mysqli_connect("hostname", "username", "password", "database");

    $sql = "SELECT * FROM ins_address, institution WHERE
    ins_address.add_id = institution.ins_add_id
    and ( (city = substr($area, 0, 3) and dist = substr($area, 3, 3) ) or `add` like '%$address%')";

    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    /*check connection*/
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
    
    /*check query*/
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    /*Create result array*/
    $ResultData = array();

    while($row = mysqli_fetch_assoc($result)){
        $ResultData[] = $row;
    }
    
    /* Output JSON*/
    header('Content-Type: application/json');
    echo json_encode($ResultData);

    
    

?>