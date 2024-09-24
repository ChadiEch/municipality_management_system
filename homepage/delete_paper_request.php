<?php
session_start();
include("../database/connection.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../authantication/login.php");
    exit();
}

// Check if the request ID is passed
if (isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];

    // Ensure only the request's owner, employee, or admin can delete the request
    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    // If normal user, ensure they are only deleting their own requests
    if ($role == 3) {
        $stmt = $con->prepare("DELETE FROM paper_requests WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $request_id, $user_id);
    } else {
        // Employees and Admins can delete any request
        $stmt = $con->prepare("DELETE FROM paper_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
    }

    if ($stmt->execute()) {
        // Redirect back to the view page after successful deletion
        header("Location: view_paper_requests.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $con->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
