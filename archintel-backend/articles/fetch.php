<?php
include('./../db_config.php');

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    $sql = "SELECT 
    a.id,
    a.image,
    a.title,
    a.link,
    a.date,
    a.content,
    a.status,
    c.name as company,
    w.name as writer,
    e.name as editor
    FROM articles a
    LEFT JOIN company c 
    ON c.id = a.company
    LEFT JOIN users AS e ON a.editor = e.id
    LEFT JOIN users AS w ON a.writer = w.id WHERE w.id = $user_id";
    $result = mysqli_query($connection, $sql);
    if ($result) {
        $data = array(); 

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
}else{

    $sql = "SELECT 
    a.id,
    a.image,
    a.title,
    a.link,
    a.date,
    a.content,
    a.status,
    c.name as company,
    w.name as writer,
    e.name as editor
    FROM articles a
    LEFT JOIN company c 
    ON c.id = a.company
    LEFT JOIN users AS e ON a.editor = e.id
    LEFT JOIN users AS w ON a.writer = w.id";
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