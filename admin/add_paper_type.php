<?php
session_start();
if ($_SESSION["role"] != "1") {
    header("location:../authantication/login.php");
}

include("../database/connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paper_type_name = $_POST['paper_type_name'];

    $query = "INSERT INTO paper_types (paper_name) VALUES (?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $paper_type_name);
    $stmt->execute();

    header("Location: manage_papers_types.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_papers_types.css">
    <title>Add Paper Type</title>
</head>
<body>
    <div class="container">
        <h2>Add Paper Type</h2>
        <form method="POST">
            <label for="paper_type_name">Paper Type Name:</label>
            <input type="text" id="paper_type_name" name="paper_type_name" required>
            <button type="submit">Add Paper Type</button>
        </form>
    </div>
</body>
</html>
