<?php 
session_start();
if ($_SESSION['islogedin'] != '1' ) {
    header("Location:../authantication/login.php");
    return;
}
$role=$_SESSION['role'];
include("../database/connection.php");
$id=$_SESSION['id'];
$query="SELECT * FROM users WHERE id=$id";
$result=mysqli_query($con,$query);
$row=mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="edit_profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

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
                <!-- Normal User Links -->
                <li><a href="../homepage/view_bills.php">My Bills</a></li>
                <li><a href="../homepage/view_report.php">Report Status</a></li>
                <li><a href="../homepage/view_paper_requests.php">Paper Request Status</a></li>
            <?php elseif ($role == 2): ?>
                <!-- Employee Links -->
                <li><a href="view_paper.php">View Papers</a></li>
                <li><a href="view_report.php">View Reports</a></li>
                <li><a href="view_bills.php">View Bills</a></li>
            <?php elseif ($role == 1): ?>
                <!-- Admin Links -->
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_announcements.php">Manage Announcements</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
            <?php endif; ?>
            <li><a href="../authantication/logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <?php
            echo"<label for='profile-picture'>Profile Picture(not required):</label>";
            echo"<input type='file' id='profile-picture''name='profile_picture' accept='image*'>";
            echo"<label for='first-name'>First Name(not required):</label>";
            echo"<input type='text' id='first-name' name='fname' value='".$row['fname']."'>";
            echo"<label for='last-name'>Last Name (not required):</label>";
            echo"<input type='text' id='last-name' name='lname' value='".$row['lname']."'>";
            echo"<label for='old_pass'>Old Password(not required):</label>";
            echo"<input type='password' id='old_pass' name='old_pass' >";
            echo"<label for='new_pass'>new Password(not required):</label>";
            echo"<input type='password' id='new_pass' name='new_pass' >";
            echo"<input type='submit' value='Update Profile'>";
            ?>
        </form>
    </div>
    <div class="footer">
  <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

  </div>
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
