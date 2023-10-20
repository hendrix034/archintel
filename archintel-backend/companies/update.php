<?php
include('./../db_config.php');

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    if(isset($_POST['password'])){
        $password = md5($_POST['password']);
    }
    $sql = "UPDATE users SET name = '$name', email = '$email',  WHERE id = $id";
    if (mysqli_query($connection, $sql)) {
        echo 'Success'; // Or a JSON response indicating success
    } else {
        echo 'Error'; // Or a JSON response indicating an error
    }
?>