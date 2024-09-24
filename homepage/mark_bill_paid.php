<?php
session_start();
include("../database/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bill_id = $_POST['bill_id'];
    
    // Update the bill status to 'paid'
    $query = "UPDATE bills SET is_paid = 1 WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $bill_id);
    
    if ($stmt->execute()) {
        header("location:manage_bills.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
