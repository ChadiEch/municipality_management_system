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

include("../database/connection.php"); // Replace with your database connection file

// Fetch all announcements from the database
$query = "SELECT * FROM announcements ORDER BY time DESC";
$result = $con->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_announcements.css">
    <title>Manage Announcements</title>
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
                <li><a href="bills.php">My Bills</a></li>
                <li><a href="status.php">Report Status</a></li>
                <li><a href="request_status.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <!-- Employee Links -->
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="view_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <!-- Admin Links -->
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Add Announcement Button -->
    <div class="announcement-actions">
        <a href="add_announcement.php" class="add-announcement-btn">+ Add Announcement</a>
    </div>

    <!-- Announcements List -->
    <div class="announcements-list">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="announcement">
            <div class="announcement-header">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <span class="announcement-time"><?php echo date("Y-m-d H:i A", strtotime($row['time'])); ?></span>
            </div>
            <p class="announcement-description"><?php echo htmlspecialchars($row['description']); ?></p>
            <div class="announcement-actions">
                <a href="edit_announcement.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="delete_announcement.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Chat Support Button -->
    <a href="../support/chat.php?receiver-id=0" class="chat-button">
        <img src="../homepage/chat_image.png" alt="Chat Support">
    </a>

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
