<?php
include('./../db_config.php');

$return_message = [];
$return_message['status'] = false;

// Retrieve data from POST parameters
$id = $_POST['id']; // You need to pass the article's ID to identify which article to update
$title = $_POST['title'];
$link = $_POST['link'];
$date = $_POST['date'];
$content = $_POST['content'];
$status = $_POST['status'];
$editor = $_POST['editor'];
$company = $_POST['company'];

// Initialize $imagePath as null
$imagePath = null;

// Check if a file was uploaded and is not empty
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image']['size'] > 0) {
    // Define the directory where you want to store the uploaded images
    $uploadDirectory = './../images/article/'; // Adjust the path to your desired directory

    $originalFileName = $_FILES['image']['name'];
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

    $newImageName = time() . '_' . rand(1000, 9999); // Unique name without extension
    $imageName = $newImageName . '.' . $fileExtension;

    // Set the full path for the image
    $imagePath = $uploadDirectory . $imageName;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // File was successfully uploaded
        $imagePath = '/images/article/' . $imageName;
    }
}

// Update the data in the "article" table
$updateQuery = "UPDATE articles 
                SET title = '$title', link = '$link', date = '$date', content = '$content', 
                status = '$status', editor = '$editor', company = '$company'";

// Only update the image path if a new image was provided
if ($imagePath !== null) {
    $updateQuery .= ", image = '$imagePath'";
}

$updateQuery .= " WHERE id = $id"; // Update the article with the specified ID

if (mysqli_query($connection, $updateQuery)) {
    $return_message['status'] = true;
    $return_message['message'] = 'Article updated';
} else {
    $return_message['message'] = 'Internal Error';
}

echo json_encode($return_message);
?>
