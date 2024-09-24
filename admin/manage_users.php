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

// Fetch all employees and supports from the database
$employee_query = "SELECT * FROM users WHERE role = 2 ORDER BY fname ASC";
$support_query = "SELECT * FROM users WHERE role = 4 ORDER BY fname ASC";

$employees = $con->query($employee_query);
$supports = $con->query($support_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="manage_users.css">
    <title>Manage Users</title>
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
                <li><a href="bills.php">My Bills</a></li>
                <li><a href="status.php">Report Status</a></li>
                <li><a href="request_status.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="view_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Add Employee Button -->
    <?php if ($role == 1): ?>
        <div class="add-employee-btn-container">
            <a href="../authantication/signup.php" class="add-employee-btn">+ Add Employee</a>
        </div>
    <?php endif; ?>

    <!-- Employee and Support Columns -->
    <div class="user-columns">
        <!-- Employees Column -->
        <div class="user-column">
            <h2>Employees</h2>
            <?php while ($row = $employees->fetch_assoc()): ?>
                <div class="user-card">
                    <h3><?php echo htmlspecialchars($row['fname'].' '.$row['lname']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($row['phone_number']); ?></p>
                    <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
                    <div class="user-card-actions">
                        <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Support Column -->
        <div class="user-column">
            <h2>Support Personnel</h2>
            <?php while ($row = $supports->fetch_assoc()): ?>
                <div class="user-card">
                    <h3><?php echo htmlspecialchars($row['fname'].' '.$row['lname']); ?></h3>
                    <p>Email: <?php echo htmlspecialchars($row['email']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($row['phone_number']); ?></p>
                    <p>Address: <?php echo htmlspecialchars($row['address']); ?></p>
                    <div class="user-card-actions">
                        <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
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
