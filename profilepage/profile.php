<?php
session_start();
include("../database/connection.php");
$role=$_SESSION['role'];
if(!isset($_SESSION['islogedin'])&& $_SESSION['islogedin'] != "true"){
    header("location:../authantication/login.php");
}
else{
  $id=$_SESSION['id'];
  $query="SELECT * FROM users WHERE id=$id";
  $result=mysqli_query($con,$query);
  $row=mysqli_fetch_assoc($result);
  $full_name=$row['fname']." ".$row['lname'];
}
?>
<html lang="en">
<head>
  <title>Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="profile.css">
</head>
<body>
<header>
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

    </header>
  
  <div class="container">
        <?php
        
        if (isset($_GET['id']) && $_SESSION['id'] == $_GET['id']) {
            echo "<div class='profilelink'>";
            echo '<a href="edit_profile.php">Edit Profile</a>';
            echo'</div>';
        }
        ?>

        
        <?php
        if(!isset($_GET['id'])){
          header("location:../homepage/homepage.php");
        }
        $id=$_GET['id'];
        $query="SELECT * FROM users WHERE id=$id";
        $result=mysqli_query($con,$query);
        $row=mysqli_fetch_assoc($result);
        
        $profilePicture = $row['profile_pic'];
        $firstName = $row['fname']; 
        $lastName = $row['lname']; 
        $tab1=explode("-",$row['date_of_birth']);
        $age=date("Y")-$tab1[0];
        
        
        echo '<img src="../images/' . $profilePicture . '" alt="Profile Picture">';
        echo '<p>First Name: ' . $firstName . '</p>';
        echo '<p>Last Name: ' . $lastName . '</p>';
        echo '<p>Age: ' . $age . '</p>';
        ?>
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