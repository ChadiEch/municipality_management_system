<?php
session_start();
include("../database/connection.php");

if ($_SESSION['role'] != 2) {
    // Only employees can access this page
    header("Location: homepage.php");
    exit();
}
$role= $_SESSION["role"];
$employee_id = $_SESSION['id'];

// Fetch unclaimed reports
$query = "SELECT * FROM public_reports WHERE claimed_by IS NULL";
$result = mysqli_query($con, $query);

// Handle claiming a report
if (isset($_POST['claim_report'])) {
    $report_id = $_POST['report_id'];
    $claim_query = "UPDATE public_reports SET claimed_by = '$employee_id' WHERE id = '$report_id'";
    if (mysqli_query($con, $claim_query)) {
        echo "Report claimed successfully!";
        header("Refresh:0"); // Refresh page after claiming
    } else {
        echo "Error claiming report.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports - Claim Reports</title>
    <link rel="stylesheet" href="manage_report.css">
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
                <li><a href="my_claimed_reports.php">View Reports</a></li>
                <li><a href="manage_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <!-- Admin Links -->
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <h1>Manage Reports - Claim Unclaimed Reports</h1>
    
    <table class="report-table">
        <tr>
            <th>Report Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo "Lat: " . $row['latitude'] . ", Long: " . $row['longitude']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="claim_report">Claim</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
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
