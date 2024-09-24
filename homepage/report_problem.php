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
        <link rel="stylesheet" href="report_problem.css">
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
$id= $_SESSION['id'];
$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Management System - Report Problem</title>
    <link rel="stylesheet" href="report_problem.css">
    <!-- Include Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
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

    <main>
        <h1>Report a Public Problem</h1>
        <form action="submit_report.php" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="photos">Upload Photos (optional):</label>
            <input type="file" name="photos[]" id="photos" multiple>

            <label for="location">Select Location on Map:</label>
            <div id="map" style="height: 400px;"></div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <button type="submit">Submit Report</button>
        </form>
    </main>

    <!-- Chat Support Button -->
    <a href="../support/chat.php?receiver-id=0" class="chat-button">
        <img src="chat_image.png" alt="Chat Support">
    </a>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

    <!-- Include Leaflet.js JavaScript -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.querySelector('.nav-toggle');
        const navLinks = document.querySelector('.nav-links');

        navToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            navToggle.classList.toggle('active');
        });

        // Initialize the Leaflet map
        var map = L.map('map').setView([34.4358, 35.8317], 13); // Default to London, change as needed

        // Add the OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add a marker for the chosen location
        var marker = L.marker([51.505, -0.09]).addTo(map);

        // Update latitude and longitude fields when the user clicks on the map
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Move the marker to the clicked location
            marker.setLatLng([lat, lng]);

            // Update hidden form inputs with the selected location
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        });
    });
    </script>
</body>
</html>
