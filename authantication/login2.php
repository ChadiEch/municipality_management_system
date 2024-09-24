<?php
include("../database/connection.php");
session_start();
if (isset($_POST['email'], $_POST['password'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $_SESSION["islogedin"] = 1;
        $_SESSION["id"] = $row["id"];
        $_SESSION["name"] = $row["fname"]." ".$row["lname"];
        $_SESSION["role"] = $row["role"];
        header("Location:../homepage/homepage.php");
    } else {

        header("Location: login.php?err=1");
    }
} else {

    header("Location: login.php?err=2");
}
?>
