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

// Fetch public reports from the database
$query = "SELECT id, title, description, latitude, longitude FROM public_reports";
$result = $con->query($query);
$reports = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Management System - Map</title>
    <link rel="stylesheet" href="interactive_map.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
                <li><a href="view_paper_requests.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="manage_report.php">View Reports</a></li>
                <li><a href="manage_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Map Section -->
    <div id="map"></div>

    <!-- Chat Support Button -->
    <a href="../support/chat.php?receiver-id=0" class="chat-button">
        <img src="chat_image.png" alt="Chat Support">
    </a>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reports = <?php echo json_encode($reports); ?>;

        // Initialize the map
        const map = L.map('map').setView([34.4358, 35.8317], 13);  // Set to default coordinates (Jerusalem for example)

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Loop through reports and add markers to the map
        reports.forEach(report => {
            const marker = L.marker([report.latitude, report.longitude]).addTo(map);
            marker.bindPopup(`<strong>${report.title}</strong><br>${report.description}`);
        });
    });
    </script>
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
