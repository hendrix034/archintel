<?php
include('db_config.php');
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND status = 'active'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    $return_data = array();
    $return_data['success'] = true;
    
    if ($row) {
        $return_data['data'] = $row;
       
    } else {
        $return_data['success'] = false;
    }
    echo json_encode($return_data);
?>