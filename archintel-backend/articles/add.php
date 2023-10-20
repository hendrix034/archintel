<?php
include('./../db_config.php');

$return_message = [];
$return_message['status'] = false;

// Retrieve data from POST parameters
$title = $_POST['title'];
$link = $_POST['link'];
$date = $_POST['date'];
$content = $_POST['content'];
$status = $_POST['status'];
$writer = $_POST['writer'];
$editor = $_POST['editor'];
$company = $_POST['company'];

// Check if a file was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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

        // Insert the data into the "article" table
        $insertQuery = "INSERT INTO articles (title, link, date, content, status, writer, editor, company, image) 
                        VALUES ('$title', '$link', '$date', '$content', '$status', '$writer', '$editor', '$company', '$imagePath')";

        if (mysqli_query($connection, $insertQuery)) {
            $return_message['status'] = true;
            $return_message['message'] = 'New article added';
        } else {
            $return_message['message'] = 'Internal Error';
        }
    } else {
        $return_message['message'] = 'Failed to upload the image';
    }
} else {
    $return_message['message'] = 'No image uploaded or an error occurred.';
}

echo json_encode($return_message);
?>
