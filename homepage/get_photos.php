<?php
header('Content-Type: application/json');
session_start();
include("../database/connection.php");

if (!isset($_GET['report_id'])) {
    echo json_encode(['error' => 'No report ID provided.']);
    exit();
}

$report_id = $_GET['report_id'];

// Query to fetch the report details
$query = "SELECT photos FROM public_reports WHERE id = '$report_id'";
$result = mysqli_query($con, $query);

if (!$result) {
    echo json_encode(['error' => 'Database query failed.']);
    exit();
}

$row = mysqli_fetch_assoc($result);
if (!$row) {
    echo json_encode(['error' => 'Report not found.']);
    exit();
}

// Decode JSON photos
$photos = json_decode($row['photos'], true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Error decoding JSON.']);
    exit();
}

echo json_encode(['photos' => $photos]);
?>
