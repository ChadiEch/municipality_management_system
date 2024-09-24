<?php
session_start();
include("../database/connection.php");

if ($_SESSION['role'] != 2) {
    header("Location: ../authantication/login.php");
    exit();
}
$role = $_SESSION["role"];
$request_id = $_GET['request_id'];

// Fetch request details
$query = "SELECT * FROM paper_requests WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $date_of_receive = !empty($_POST['date_of_receive']) ? $_POST['date_of_receive'] : null;
    $response_message = $_POST['response_message'];

    // Update the request status, collection date, and response message
    $query = "UPDATE paper_requests SET status = ?, date_of_receive = ?, response_message = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssi", $status, $date_of_receive, $response_message, $request_id);
    $stmt->execute();

    header("Location: view_paper.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Paper Request</title>
    <link rel="stylesheet" href="update_paper.css">
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

    <div class="container">
        <h2>Update Paper Request</h2>
        <form method="post">
            <div>
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="Pending" <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Approved" <?= $request['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="Rejected" <?= $request['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div>
                <label for="date_of_receive">Collection Date:</label>
                <input type="date" name="date_of_receive" id="date_of_receive" value="<?= $request['date_of_receive'] ? date("Y-m-d", strtotime($request['date_of_receive'])) : '' ?>">
            </div>
            <div>
                <label for="response_message">Response Message:</label>
                <textarea name="response_message" id="response_message" rows="4"><?= htmlspecialchars($request['response_message']) ?></textarea>
            </div>
            <div>
                <button type="submit">Update Request</button>
            </div>
        </form>
    </div>
    
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>
</body>
</html>
