<?php 
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "municipality_management_sys";

$con = mysqli_connect($hostname, $username, $password, $dbname);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
