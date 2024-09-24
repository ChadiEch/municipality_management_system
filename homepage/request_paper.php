<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['role'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Municipality Management System - Login</title>
        <link rel="stylesheet" href="homepage.css">
    </head>
    <body class="login-page">
        <div class="login-container">
            <a href="../authantication/login.php" class="login-button">Login</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Fetch paper types from the database
$query = "SELECT * FROM paper_types";
$result = $con->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Paper - Municipality Management System</title>
    <link rel="stylesheet" href="request_paper.css">
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
                <!-- Normal User Links -->
                <li><a href="view_bills.php">My Bills</a></li>
                <li><a href="view_report.php">Report Status</a></li>
                <li><a href="view_paper_requests.php">Paper Request Status</a></li>
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

    <!-- Chat Support Button -->
    <a href="../support/chat.php?receiver-id=0" class="chat-button">
        <img src="chat_image.png" alt="Chat Support">
    </a>

    <!-- Request Paper Form -->
    <div class="container">
        <h2>Request a Paper</h2>
        <form action="submit_request.php" method="post">
            <div>
                <label for="paperType">Select Paper Type:</label>
                <select name="paperType" id="paperType" required>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['paper_name']) ?>">
                            <?= htmlspecialchars($row['paper_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="fullName">Full Name:</label>
                <input type="text" name="fullName" id="fullName" required>
            </div>
            <div>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required>
            </div>
            <div>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
            </div>
            <div>
                <label for="purpose">Purpose of Request:</label>
                <textarea name="purpose" id="purpose" rows="4" required></textarea>
            </div>
            <div>
                <label for="additionalDetails">Additional Details (if any):</label>
                <textarea name="additionalDetails" id="additionalDetails" rows="4"></textarea>
            </div>
            <button type="submit">Submit Request</button>
        </form>
    </div>

   <div>
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>
    </div>
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
</body>
</html>
