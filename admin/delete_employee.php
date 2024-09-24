<?php
session_start();
if (!isset($_SESSION["islogedin"]) || $_SESSION["islogedin"] != "1") {
    header("location:../authantication/login.php?error1=1");
    exit();
}

$role = $_SESSION["role"];
if ($role != "1") {
    header("location:../authantication/login.php");
    exit();
}

include("../database/connection.php"); // Replace with your database connection file

// Check if an ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $employee_id = intval($_GET['id']);

    // Prepare and execute the deletion query
    $query = "DELETE FROM users WHERE id = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $employee_id);
        if ($stmt->execute()) {
            // Redirect to the manage users page with a success message
            header("Location: manage_users.php?message=Employee%20deleted%20successfully");
        } else {
            // Redirect with an error message if the deletion failed
            header("Location: manage_users.php?error=Failed%20to%20delete%20employee");
        }
        $stmt->close();
    } else {
        // Redirect with an error message if the query preparation failed
        header("Location: manage_users.php?error=Failed%20to%20prepare%20statement");
    }
} else {
    // Redirect with an error message if no ID was provided
    header("Location: manage_users.php?error=No%20employee%20ID%20provided");
}

$con->close();
?>
