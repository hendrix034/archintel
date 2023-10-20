<?php
include('db_config.php');

$sql = "SELECT * FROM users WHERE id = 9";
$result = mysqli_query($connection, $sql);

if($result){
    echo 1;
}else{
    echo 0;
}
?>