<?php
session_start();
include("../database/connection.php");

if ($_SESSION['role'] != 2) {
    header("Location: ../authantication/login.php");
    exit();
}

$employee_id = $_SESSION['id']; // Employee ID

// Fetch the employee's claimed paper requests
$query = "SELECT * FROM paper_requests WHERE claimed_by = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claimed Paper Requests</title>
    <link rel="stylesheet" href="view_paper.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="homepage.php">Municipality</a>
        </div>
        <ul class="nav-links">
            <li><a href="manage_paper.php">View Papers</a></li>
            <li><a href="view_paper.php">My Claimed Papers</a></li>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>My Claimed Paper Requests</h2>

        <table class="paper-table">
            <thead>
                <tr>
                    <th>Paper Type</th>
                    <th>User Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Collection Date</th>
                    <th>Purpose of Request</th>
                    <th>Additional Details</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['paper_type']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars(date("d M Y, H:i", strtotime($row['created_at']))) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['date_of_receive'] ? date("d M Y", strtotime($row['date_of_receive'])) : 'Not set') ?></td>
                    <td><?= htmlspecialchars($row['purpose_of_request']) ?></td>
                    <td><?= htmlspecialchars($row['additional_details'] ? $row['additional_details'] : 'None') ?></td>
                    <td>
                        <a href="update_paper.php?request_id=<?= $row['id'] ?>">Update</a>
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
