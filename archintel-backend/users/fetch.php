<?php
include('./../db_config.php');

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        echo json_encode($row); // Return the selected data as JSON
    } else {
        echo json_encode(['error' => 'Data not found']);
    }
}else{

    $sql = "SELECT * FROM users";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $data = array(); // Create an array to store the results

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (!empty($data)) {
            echo json_encode($data); // Return all data as JSON
        } else {
            echo json_encode(['error' => 'No data found']);
        }
    } else {
        echo json_encode(['error' => 'Error in the SQL query']);
    }
}



?>