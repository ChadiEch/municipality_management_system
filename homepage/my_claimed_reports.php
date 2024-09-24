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

// Fetch reports claimed by the employee
$query = "SELECT * FROM public_reports WHERE claimed_by = '$employee_id'";
$result = mysqli_query($con, $query);

// Handle updating the status of a report
if (isset($_POST['update_status'])) {
    $report_id = $_POST['report_id'];
    $new_status = $_POST['status'];
    $update_query = "UPDATE public_reports SET status = '$new_status' WHERE id = '$report_id'";
    if (mysqli_query($con, $update_query)) {
        echo "Report status updated successfully!";
        header("Refresh:0"); // Refresh page after updating
    } else {
        echo "Error updating status.";
    }
}
if (isset($_POST['delete_report'])) {
    $report_id = $_POST['report_id'];
    $delete_query = "DELETE FROM public_reports WHERE id = '$report_id'";
    if (mysqli_query($con, $delete_query)) {
        echo "Report deleted successfully!";
        header("Refresh:0"); // Refresh page after deleting
    } else {
        echo "Error deleting report.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claimed Reports</title>
    <link rel="stylesheet" href="claimed_report.css">
    <style>
        /* Add styles for the modal here */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal img {
            width: 100%;
            height: auto;
        }
        .view-photos-button{
            padding: 6px;
            border-radius: 20px;
            background-color: orange;
            color: white;
        }
        .view-photos-button:hover{
            background-color: #ba7609;
        }
    </style>
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

    <h1>My Claimed Reports</h1>
    
    <table class="claimed-report-table">
        <tr>
            <th>Report Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Update Status</th>
            <th>Delete</th>
            <th>View Photos</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo "Lat: " . htmlspecialchars($row['latitude']) . ", Long: " . htmlspecialchars($row['longitude']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <select name="status">
                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="In Progress" <?php if ($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Resolved" <?php if ($row['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                        </select>
                        <button type="submit" name="update_status" class="update-status-button">Update</button>
                    </form>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit" name="delete_report" class="delete-report-button">Delete</button>
                    </form>
                </td>
                <td>
                    <button onclick="showPhotos('<?php echo htmlspecialchars($row['id']); ?>')" class="view-photos-button">View Photos</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Modal for Viewing Photos -->
    <div id="photoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="photoContainer"></div>
        </div>
    </div>

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
    const photoModal = document.getElementById('photoModal');
    const photoContainer = document.getElementById('photoContainer');
    const closeModal = document.querySelector('.modal .close');

    navToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
        navToggle.classList.toggle('active');
    });

    closeModal.addEventListener('click', function() {
        photoModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == photoModal) {
            photoModal.style.display = 'none';
        }
    });

    window.showPhotos = function(reportId) {
    fetch(`get_photos.php?report_id=${reportId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                photoContainer.innerHTML = `<p>Error: ${data.error}</p>`;
            } else {
                photoContainer.innerHTML = '';
                data.photos.forEach(photo => {
                    const img = document.createElement('img');
                    img.src = "../uploads/reports/"+photo;
                    img.alt = 'Report Photo';
                    photoContainer.appendChild(img);
                });
                photoModal.style.display = 'block';
            }
        })
        .catch(error => console.error('Error fetching photos:', error));
}
});
</script>
</html>
