<?php
session_start();
include("../database/connection.php");
$role=$_SESSION['role'];
// Check if user is an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
    header("location:../authantication/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle bill submission
    $user_id = $_POST['user_id'];
    $bill_type = $_POST['bill_type'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];  // Get the due date from the form

    // Insert the new bill into the database
    $query = "INSERT INTO bills (user_id, bill_type, amount, due_date) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isds", $user_id, $bill_type, $amount, $due_date);  // Bind the due date

    if ($stmt->execute()) {
        echo "Bill created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch normal users (role = 3) for bill assignment
$users_query = "SELECT id, fname, lname FROM users WHERE role = 3";
$users = $con->query($users_query);

// Fetch unpaid bills
$bill_query = "SELECT bills.*, users.fname, users.lname FROM bills 
               JOIN users ON bills.user_id = users.id 
               WHERE is_paid = 0";
$bills = $con->query($bill_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bills</title>
    <link rel="stylesheet" href="manage_bills.css">
</head>
<body>
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
                <li><a href="bills.php">My Bills</a></li>
                <li><a href="status.php">Report Status</a></li>
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
    <h2>Create a New Bill</h2>
    <form method="POST" action="manage_bills.php">
        <label for="user_id">Select User:</label>
        <select name="user_id" required>
            <?php while ($user = $users->fetch_assoc()) { ?>
                <option value="<?= $user['id'] ?>"><?= $user['fname'] . " " . $user['lname'] ?></option>
            <?php } ?>
        </select>

        <label for="bill_type">Bill Type:</label>
        <input type="text" name="bill_type" required>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" step="0.01" required>

        <label for="due_date">Due Date (YYYY-MM-DD):</label>
        <input type="date" name="due_date" required>

        <button type="submit">Create Bill</button>
    </form>

    <h2>Unpaid Bills</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Bill Type</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Mark as Paid</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($bill = $bills->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($bill['fname'] . " " . $bill['lname']) ?></td>
                <td><?= htmlspecialchars($bill['bill_type']) ?></td>
                <td><?= htmlspecialchars($bill['amount']) ?></td>
                <td><?= htmlspecialchars($bill['due_date']) ?></td>
                <td>
                    <form method="POST" action="mark_bill_paid.php">
                        <input type="hidden" name="bill_id" value="<?= $bill['id'] ?>">
                        <button type="submit">Mark as Paid</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
        <!-- Chat Support Button -->
        <a href="../support/chat.php?receiver-id=0" class="chat-button">
        <img src="chat_image.png" alt="Chat Support">
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
