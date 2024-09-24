<?php
session_start();
if (!isset($_SESSION["islogedin"]) || $_SESSION["islogedin"] != "1" || $_SESSION["role"] != "1") {
    header("location:../authantication/login.php");
    exit;
}

include("../database/connection.php"); // Database connection

// Check if an announcement ID is provided
if (!isset($_GET['id'])) {
    header("location:manage_announcements.php");
    exit;
}

$id = $_GET['id'];

// Fetch the announcement details
$query = "SELECT * FROM announcements WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    header("location:manage_announcements.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update announcement in the database
    $updateQuery = "UPDATE announcements SET title = ?, description = ?, time = NOW() WHERE id = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("ssi", $title, $description, $id);
    
    if ($updateStmt->execute()) {
        header("location:manage_announcements.php");
        exit;
    } else {
        echo "Error updating the announcement.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit_announcement.css">
    <title>Edit Announcement</title>
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
            <?php if ($_SESSION['role'] == 3): ?>
                <li><a href="bills.php">My Bills</a></li>
                <li><a href="status.php">Report Status</a></li>
                <li><a href="request_status.php">Paper Request Status</a></li>
            <?php elseif ($_SESSION['role'] == 2): ?>
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="view_bills.php">View Bills</a></li>
            <?php elseif ($_SESSION['role'] == 1): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <main>
        <h1>Edit Announcement</h1>
        <form method="POST" class="edit-form">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($announcement['title']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($announcement['description']); ?></textarea>

            <button type="submit">Save Changes</button>
            <a href="manage_announcements.php" class="cancel-btn">Cancel</a>
        </form>
    </main>

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
