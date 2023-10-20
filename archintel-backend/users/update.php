<?php
include('./../db_config.php');

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $sql = "UPDATE users SET name = '$name', email = '$email', status = '$status', type = '$type' WHERE id = $id";
    if(isset($_POST['password'])){
        $password = md5($_POST['password']);
        $sql = "UPDATE users SET name = '$name', email = '$email', password = '$password',status = '$status', type = '$type' WHERE id = $id";
    }
    
    if (mysqli_query($connection, $sql)) {
        $return_message['status'] =  true;
        $return_message['message'] =  'User Updated';
    } else {
        $return_message['message'] =  'Internal Error';
    }

    echo json_encode($return_message);
?>