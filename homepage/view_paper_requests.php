<?php
session_start();
include("../database/connection.php");

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../authantication/login.php");
    exit();
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Fetch paper requests for the logged-in user
$query = $role == 3 
    ? "SELECT * FROM paper_requests WHERE user_id = ?" 
    : "SELECT * FROM paper_requests"; // Employees and Admins can see all requests
$stmt = $con->prepare($query);
if ($role == 3) {
    $stmt->bind_param("i", $id);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Paper Requests</title>
    <link rel="stylesheet" href="view_paper_request.css">
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
            <li><a href="request_paper.php">Request Paper</a></li>
        <?php elseif ($role == 2): ?>
            <li><a href="view_paper.php">View Papers</a></li>
        <?php elseif ($role == 1): ?>
            <li><a href="manage_users.php">Manage Users</a></li>
        <?php endif; ?>
        <li><a href="../authantication/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Your Paper Requests</h2>

    <table class="paper-table">
        <thead>
            <tr>
                <th>Paper Type</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Response Message</th>
                <th>Collection Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['paper_type']) ?></td>
                <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($row['created_at']))) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['response_message']) ?></td>
                
                <!-- Show collection date only if the status is "Approved" -->
                <td>
                    <?php if ($row['status'] == 'Approved'): ?>
                        <?= htmlspecialchars(date("d M Y", strtotime($row['date_of_receive']))) ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                
                <td>
                    <form action="delete_paper_request.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Municipality Management System</p>
</footer>

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
