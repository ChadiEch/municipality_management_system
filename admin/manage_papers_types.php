<?php
session_start();
if (isset($_SESSION["islogedin"]) && $_SESSION["islogedin"] == "1") {
    $role = $_SESSION["role"];
    if ($role != "1") {
        header("location:../authantication/login.php");
    }
} else {
    header("location:../authantication/login.php?erro1=1");
}

include("../database/connection.php");

// Handle Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM paper_types WHERE id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_papers_types.php");
}

// Fetch paper types from the database
$query = "SELECT * FROM paper_types";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_papers_types.css">
    <title>Manage Paper Types</title>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><a href="../homepage/homepage.php">Municipality</a></div>
        <ul class="nav-links">
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Manage Paper Types</h2>
        <a href="add_paper_type.php" class="btn">Add New Paper Type</a>
        <table class="paper-table">
            <thead>
                <tr>
                    <th>Paper Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['paper_name']) ?></td>
                    <td>
                        <a href="edit_paper_type.php?id=<?= $row['id'] ?>">Edit</a> |
                        <a href="manage_papers_types.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>
</body>
</html>
