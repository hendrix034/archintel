<?php
include('./../db_config.php');

$id = $_POST['id'];
$sql = "DELETE FROM articles WHERE id = $id";
if (mysqli_query($connection, $sql)) {
    echo json_encode(array('message' => 'Delete Success')); // Or a JSON response indicating success
} else {
    echo json_encode(array('message' => 'Internal Error')); // Or a JSON response indicating an error
}
?>