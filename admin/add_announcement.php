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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../database/connection.php"); // Replace with your database connection file

    $title = $_POST["title"];
    $description = $_POST["description"];
    $time = date("Y-m-d H:i:s"); // Automatically capture the current timestamp

    $stmt = $con->prepare("INSERT INTO announcements (title, description, time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $time);

    if ($stmt->execute()) {
        echo "<script>alert('Announcement added successfully!'); window.location.href='manage_announcements.php';</script>";
    } else {
        echo "<script>alert('Error adding announcement.'); window.location.href='add_announcement.php';</script>";
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="add_announcement.css">
    <title>Add Announcement</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="../homepage/homepage.php">Municipality</a>
        </div>
        <button class="nav-toggle" aria-label="Toggle navigation">
            <span class="hamburger"></span>
        </button>
        <ul class="nav-links">
            <?php if ($role == 3): ?>
                <!-- Normal User Links -->
                <li><a href="view_bills.php">My Bills</a></li>
                <li><a href="view_report.php">Report Status</a></li>
                <li><a href="view_paper_requests.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <!-- Employee Links -->
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="manage_report.php">View Reports</a></li>
                <li><a href="manage_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <!-- Admin Links -->
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Add Announcement Form -->
    <div class="form-container">
        <h1>Add Announcement</h1>
        <form method="POST" action="">
            <label for="title">Announcement Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Announcement Description</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <button type="submit" class="submit-btn">Add Announcement</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    navToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        navToggle.classList.toggle('active');
    });
});
</script>
</html>
