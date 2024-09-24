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
$id= $_SESSION['id'];
$role = $_SESSION['role'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Management System - Homepage</title>
    <link rel="stylesheet" href="homepage.css">
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
                <li><a href="../admin/manage_users.php">Manage Users</a></li>
                <li><a href="../admin/manage_announcements.php">Manage Announcements</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Main Section -->
    <main>
    <h1>Welcome to the Municipality Management System</h1>
        <div class="card-container">
            <?php if ($role == 3): ?>
               
                <div class="card">
                    <h2>Request a Paper</h2>
                    <p>Submit a request for important documents such as birth certificates, property papers, etc.</p>
                    <a href="request_paper.php" class="button">Request Now</a>
                </div>

                <div class="card">
                    <h2>Report a Public Issue</h2>
                    <p>Report issues like road problems, water leakages, and more.</p>
                    <a href="report_problem.php" class="button">Report Now</a>
                </div>

                <div class="card">
                    <h2>Municipality Announcements</h2>
                    <p>Stay updated with the latest announcements from the municipality.</p>
                    <a href="announcements.php" class="button">View Announcements</a>
                </div>
<?php

            echo  " <div class='card'>
                    <h2>Your Profile</h2>
                    <p>View and update your personal details and settings.</p>
                    <a href='../profilepage/profile.php?id=$id' class='button'>Go to Profile</a>
                </div>"
?>
                <div class="card">
                    <h2>Interactive Map</h2>
                    <p>View a map of ongoing public issues and report new ones.</p>
                    <a href="interactive_map.php" class="button">View Map</a>
                </div>
            <?php elseif ($role == 2): ?>
                <!-- Employee Cards -->
                <div class="card">
                    <h2>Manage Papers</h2>
                    <p>View and manage the documents requested by users.</p>
                    <a href="manage_paper.php" class="button">View Papers</a>
                </div>

                <div class="card">
                    <h2>Manage Reports</h2>
                    <p>View and handle the public issues reported by users.</p>
                    <a href="manage_report.php" class="button">View Reports</a>
                </div>

                <div class="card">
                    <h2>Manage Bills</h2>
                    <p>Check and manage the bills submitted by users.</p>
                    <a href="manage_bills.php" class="button">Manage Bills</a>
                </div>
                <div class="card">
                    <h2>Interactive Map</h2>
                    <p>View a map of ongoing public issues and report new ones.</p>
                    <a href="interactive_map.php" class="button">View Map</a>
                </div>
            <?php elseif ($role == 1): ?>
                <!-- Admin Cards -->
                <div class="card">
                    <h2>Manage Users</h2>
                    <p>Control user accounts, permissions, and roles.</p>
                    <a href="../admin/manage_users.php" class="button">Manage Users</a>
                </div>

                <div class="card">
                    <h2>Manage Announcements</h2>
                    <p>Create and edit municipality announcements.</p>
                    <a href="../admin/manage_announcements.php" class="button">Manage Announcements</a>
                </div>

                <div class="card">
                    <h2>Manage Papers Types</h2>
                    <p>Create and edit municipality papers type.</p>
                    <a href="../admin/manage_papers_types.php" class="button">Manage Papers Types</a>
                </div>

            <?php elseif ($role == 4): ?>
                <!-- support -->
                <div class="chat-list">
        <?php
        $query = "SELECT DISTINCT * FROM users where id IN (SELECT id_sender FROM messages where id_receiver=0 OR id_receiver=$id)" ;
        $result = mysqli_query($con, $query);
        $num = mysqli_num_rows($result);
        if($num>0){
        while ($row = mysqli_fetch_assoc($result)) {
            $name = $row['fname'].' '.$row['lname'];
            
            $profile_pic= $row['profile_pic'];
            $idS= $row['id'];
            $sup_id=$_SESSION['id'];
          
         $query1="SELECT * FROM messages WHERE id_sender=$idS";
         $result1= mysqli_query($con, $query1);  
         $num1 = mysqli_num_rows($result1);
         if($num1> 0){
            $row1 = mysqli_fetch_assoc($result1);
                $idM= $row1["id"];
                $receiver = $row1['id_sender'];
        }
            if ($receiver != $id) {
                echo "
                <a href='../support/update_receiver.php?receiver-id=$receiver&sup_id=$sup_id'>
                    <div class='chat-item'>
                        <img src='../images/$profile_pic' alt='Profile Picture'>
                        <div class='chat-details'>
                            <h3>$name</h3>              
                        </div>
                    </div>
                </a>";
            }
        }
    }
      
      else{
        echo"<h1 class='nomsg'>You have no messages yet!</h1>";
      }
        ?>
    </div>
            <?php endif; ?>
        </div>
    </main>

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
