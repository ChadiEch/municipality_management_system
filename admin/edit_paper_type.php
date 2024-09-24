<?php
session_start();
if ($_SESSION["role"] != "1") {
    header("location:../authantication/login.php");
}

include("../database/connection.php");

$id = $_GET['id'];
$query = "SELECT * FROM paper_types WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paper_type = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paper_type_name = $_POST['paper_type_name'];

    $update_query = "UPDATE paper_types SET paper_name = ? WHERE id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("si", $paper_type_name, $id);
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
    <title>Edit Paper Type</title>
</head>
<body>
    <div class="container">
        <h2>Edit Paper Type</h2>
        <form method="POST">
            <label for="paper_type_name">Paper Type Name:</label>
            <input type="text" id="paper_type_name" name="paper_type_name" value="<?= htmlspecialchars($paper_type['paper_name']) ?>" required>
            <button type="submit">Update Paper Type</button>
        </form>
    </div>
</body>
</html>
