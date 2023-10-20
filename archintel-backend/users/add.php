<?php
include('./../db_config.php');

// Retrieve the email from the POST data
$email = $_POST['email'];

// Check if the email already exists in the database
$checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($connection, $checkEmailQuery);

$return_message = [];
$return_message['status'] = false;
if (mysqli_num_rows($result) > 0) {
    // Email already exists, so you should handle this as an error
    $return_message['message'] =  'Email already exist';
} else {
    // Email is not in the database, proceed with the insertion
    $name = $_POST['name'];
    $password = md5($_POST['password']);
    $type = $_POST['type'];
    $status = $_POST['status'];
    $insertQuery = "INSERT INTO users (name, email, password, type, status) VALUES ('$name', '$email', '$password', '$type', '$status')";
    
    if (mysqli_query($connection, $insertQuery)) {
        $return_message['status'] =  true;
        $return_message['message'] =  'New user added';
    } else {
        $return_message['message'] =  'Internal Error';
    }
}

echo json_encode($return_message)
?>
