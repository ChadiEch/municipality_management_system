<?php
session_start();
include("../database/connection.php");

// Check if the user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("Location: ../authantication/login.php");
    exit();
}

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paperType = $_POST['paperType'];
    $purposeOfRequest = $_POST['purpose'];
    $full_name= $_POST['fullName'];
    $additionalDetails = !empty($_POST['additionalDetails']) ? $_POST['additionalDetails'] : null;

    // Insert the request into the database
    $stmt = $con->prepare("INSERT INTO paper_requests (user_id, paper_type, purpose_of_request, additional_details, full_name, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("issss", $userId, $paperType, $purposeOfRequest, $additionalDetails, $full_name);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: view_paper_requests.php?success=1");
    } else {
        header("Location: request_paper.php?err=1");
    }

    $stmt->close();
    $connection->close();
}
?>
