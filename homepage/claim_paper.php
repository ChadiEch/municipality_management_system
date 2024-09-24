<?php
session_start();
include("../database/connection.php");

if ($_SESSION['role'] != 2) {
    header("Location: ../authantication/login.php");
    exit();
}

$employee_id = $_SESSION['id']; // Logged-in employee ID
$request_id = $_POST['request_id'];

// Update the request to mark it as claimed by this employee
$query = "UPDATE paper_requests SET claimed_by = ? WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $employee_id, $request_id);
$stmt->execute();

// Redirect back to manage_paper.php
header("Location: manage_paper.php");
?>
