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

// Check if form data is submitted
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $p_number = $_POST['p_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = isset($_POST['role']) ? $_POST['role'] : 'employee';

    // Hash password if provided
    if (!empty($password)) {
        
        $query = "UPDATE users SET fname = ?, lname = ?, date_of_birth = ?, address = ?, phone_number = ?, email = ?, password = ?, role = ? WHERE id = ?";
    } else {
        $query = "UPDATE users SET fname = ?, lname = ?, date_of_birth = ?, address = ?, phone_number = ?, email = ?, role = ? WHERE id = ?";
    }

    if ($stmt = $con->prepare($query)) {
        if (!empty($password)) {
            $stmt->bind_param("ssssssssi", $fname, $lname, $dob, $address, $p_number, $email, $password, $role, $employee_id);
        } else {
            $stmt->bind_param("sssssssi", $fname, $lname, $dob, $address, $p_number, $email, $role, $employee_id);
        }
        if ($stmt->execute()) {
            header("Location: manage_users.php?message=Employee%20updated%20successfully");
        } else {
            header("Location: edit_employee.php?id=$employee_id&error=Failed%20to%20update%20employee");
        }
        $stmt->close();
    } else {
        header("Location: edit_employee.php?id=$employee_id&error=Failed%20to%20prepare%20statement");
    }
} else {
    header("Location: manage_users.php?error=No%20employee%20ID%20provided");
}

$con->close();
?>
