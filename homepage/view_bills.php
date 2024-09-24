<?php
session_start();
include("../database/connection.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 3) {
    header("location: ../authantication/login.php");
    exit();
}
$role=$_SESSION['role'];
$id = $_SESSION['id']; // Normal user's ID

// Fetch all unpaid and paid bills for the user
$bills_query = "SELECT * FROM bills WHERE user_id = ? ORDER BY is_paid, due_date ASC";
$stmt = $con->prepare($bills_query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bills</title>
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
    <h1>My Bills</h1>
    
    <table>
        <thead>
            <tr>
                <th>Bill Type</th>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) : 
                $due_date = new DateTime($row['due_date']);
                $current_date = new DateTime();
                $amount = $row['amount'];
                
                // Check if the due date has passed
                if ($current_date > $due_date && !$row['is_paid']) {
                    // Increase the amount by 10%
                    $amount += ($amount * 0.10);
                }
                ?>
                <tr class="<?php echo ($current_date > $due_date && !$row['is_paid']) ? 'overdue' : ''; ?>">
                    <td><?php echo htmlspecialchars($row['bill_type']); ?></td>
                    <td><?php echo number_format($amount, 2); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo $row['is_paid'] ? 'Paid' : 'Unpaid'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

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

<?php
$stmt->close();
$con->close();
?>
