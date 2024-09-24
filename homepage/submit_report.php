<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['id']) || !isset($_SESSION['role'])) {
    echo "You must be logged in to submit a report.";
    exit();
}

$userId = $_SESSION['id'];

// Validate form fields
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
$longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

// Ensure all required fields are filled out
if (empty($title) || empty($description) || empty($latitude) || empty($longitude)) {
    echo "All fields are required.";
    exit();
}

// Process photo upload
$uploadedPhotos = [];
$photoUploadDir = '../uploads/reports/';
if (!is_dir($photoUploadDir)) {
    mkdir($photoUploadDir, 0777, true);
}

if (isset($_FILES['photos']) && count($_FILES['photos']['name']) > 0) {
    foreach ($_FILES['photos']['name'] as $key => $photoName) {
        $photoTmpName = $_FILES['photos']['tmp_name'][$key];
        $photoError = $_FILES['photos']['error'][$key];
        $photoSize = $_FILES['photos']['size'][$key];
        $photoExt = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the file is valid and an allowed image type
        if ($photoError === 0 && in_array($photoExt, $allowedExtensions) && $photoSize <= 5000000) { // Limit to 5MB
            $uniquePhotoName = uniqid('', true) . '.' . $photoExt;
            $photoDestination = $photoUploadDir . $uniquePhotoName;

            if (move_uploaded_file($photoTmpName, $photoDestination)) {
                $uploadedPhotos[] = $uniquePhotoName;
            } else {
                echo "Error uploading photo: " . $photoName;
                exit();
            }
        }
    }
}

// Convert uploaded photos array to JSON for storage in the database
$photosJson = json_encode($uploadedPhotos);

// Prepare the SQL query to insert the report into the database
$sql = "INSERT INTO public_reports (user_id, title, description, latitude, longitude, photos, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())";
$stmt = $con->prepare($sql);
$stmt->bind_param("issdds", $userId, $title, $description, $latitude, $longitude, $photosJson);

// Execute the query and check if it was successful
if ($stmt->execute()) {
    echo "Report submitted successfully!";
    // Redirect to a confirmation or the reports page
    header("Location: homepage.php");
} else {
    echo "Error submitting report: " . $stmt->error;
}

$stmt->close();
$con->close();
?>
