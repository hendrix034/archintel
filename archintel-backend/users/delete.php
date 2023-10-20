<?php
include('./../db_config.php');

$id = $_POST['id'];
if($id == 1){

 echo json_encode(array('message' => "you can't delete this user"));

}else{

    $sql = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($connection, $sql)) {
        echo json_encode(array('message' => 'Delete Success')); // Or a JSON response indicating success
    } else {
        echo json_encode(array('message' => 'Internal Error')); // Or a JSON response indicating an error
    }

}
?>