<?php
session_start();
if ($_SESSION['islogedin'] != '1' ) {
    header("Location:../authantication/login.php");
    return;
}
else
$id=$_SESSION['id'];
include("../database/connection.php");


if (isset($_POST['fname']) && isset($_POST['lname'])) {
    $Fname = $_POST['fname'];
    $Lname = $_POST['lname'];

    $query1 = "SELECT * FROM users WHERE id != '$id'";
    $result1 = mysqli_query($con, $query1);
    $num = mysqli_num_rows($result1);

    if ($num == 0) {
        if (isset($_FILES['pic'])) {
            $pic = $_FILES["pic"]["name"];
            move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/$pic");
            $query = "UPDATE users SET lname='$Lname', fname='$Fname', profile_pic='$pic' WHERE id='$id'";
            $result = mysqli_query($con, $query);
            $result ? header("Location:edit_profile.php?done=''") : header("Location:edit_profile.php?error='1'");
        } else {
            $query = "UPDATE users SET lname='$Lname', fname='$Fname' WHERE id='$id'";
            $result = mysqli_query($con, $query);
            $result ? header("Location:edit_profile.php?done=''") : header("Location:edit_profile.php?error='1'");
        }
    } else {
        header("Location:edit_profile.php?error='2'");
    }
}







?>