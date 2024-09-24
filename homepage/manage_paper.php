<?php
session_start();
include("../database/connection.php");

// Check if employee is logged in
if ($_SESSION['role'] != 2) {
    header("Location: ../authantication/login.php");
    exit();
}

$id = $_SESSION['id']; // Employee ID
$role= $_SESSION['role'];
// Fetch unclaimed paper requests
$query = "SELECT * FROM paper_requests WHERE claimed_by IS NULL";
$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Paper Requests</title>
    <link rel="stylesheet" href="manage_paper.css">
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
                <li><a href="../homepage/view_paper_requests.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <!-- Employee Links -->
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="manage_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <!-- Admin Links -->
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Available Paper Requests</h2>

        <table class="paper-table">
            <thead>
                <tr>
                    <th>Paper Type</th>
                    <th>User Name</th>
                    <th>Request Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['paper_type']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($row['created_at']))) ?></td>
                    <td>
                        <form action="claim_paper.php" method="post">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <button type="submit">Claim</button>
                        </form>
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
