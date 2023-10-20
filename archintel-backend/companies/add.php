<?php
include('./../db_config.php');

$return_message = [];
$return_message['status'] = false;
$name = $_POST['name'];
$status = $_POST['status'];

$checkNameQuery = "SELECT * FROM company WHERE name = '$name'";
$result = mysqli_query($connection, $checkNameQuery);

if(mysqli_num_rows($result) > 0){
    $return_message['message'] =  'Company name already exist';
}
else
{
// Check if a file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Define the directory where you want to store the uploaded images
        $uploadDirectory = './../images/logo/'; // Adjust the path to your desired directory

        $originalFileName = $_FILES['image']['name'];
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        $newImageName = time() . '_' . rand(1000, 9999); // Unique name without extension
        $imageName = $newImageName . '.' . $fileExtension;
        // Set the full path for the image
        $imagePath = $uploadDirectory . $imageName;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // File was successfully uploaded, you can now insert the record into the database
            $imagePath = '/images/logo/'. $imageName;
            $insertQuery = "INSERT INTO company (name, logo, status) VALUES ('$name', '$imagePath', '$status')";

            if (mysqli_query($connection, $insertQuery)) {
                $return_message['status'] = true;
                $return_message['message'] = 'New company added';
            } else {
                $return_message['message'] = 'Internal Error';
            }
        } else {
            $return_message['message'] = 'Failed to upload the image';
        }
    } else {
        $return_message['message'] = 'No image uploaded or an error occurred.';
    }
}

echo json_encode($return_message);
?>