<?php
session_start();
if (isset($_SESSION["islogedin"]) && $_SESSION["islogedin"]=="1") {
    $role = $_SESSION["role"];
} else {
    header("location:../authantication/login.php?erro1=1");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="announcements.css">
    <title>Announcements</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="homepage.php">Municipality</a>
        </div>
        <button class="nav-toggle" aria-label="Toggle navigation">
            <span class="hamburger"></span>
        </button>
        <ul class="nav-links">
            <?php if ($role == 3): ?>
                <li><a href="view_bills.php">My Bills</a></li>
                <li><a href="view_report.php">Report Status</a></li>
                <li><a href="view_paper_requests.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="view_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Announcements List -->
    <div class="announcements-list">
        <?php
        include '../database/connection.php';
        $query = "SELECT * FROM announcements ORDER BY time DESC";
        $result = mysqli_query($con, $query);
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='announcement'>";
            echo "<div class='announcement-header'>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<span class='announcement-time'>" . $row['time'] . "</span>";
            echo "</div>";
            echo "<p class='announcement-description'>" . $row['description'] . "</p>";
            echo "</div>";
        }
        ?>
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
