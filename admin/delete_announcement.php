<?php
session_start();
if (!isset($_SESSION["islogedin"]) || $_SESSION["islogedin"] != "1" || $_SESSION["role"] != "1") {
    header("location:../authantication/login.php");
    exit;
}

include("../database/connection.php"); // Database connection

// Check if an announcement ID is provided
if (!isset($_GET['id'])) {
    header("location:manage_announcements.php");
    exit;
}

$id = $_GET['id'];

// Delete the announcement
$query = "DELETE FROM announcements WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("location:manage_announcements.php");
    exit;
} else {
    echo "Error deleting the announcement.";
}
?>
