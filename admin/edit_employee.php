<?php
session_start();
if (!isset($_SESSION["islogedin"]) || $_SESSION["islogedin"] != "1") {
    header("location:../authantication/login.php?error1=1");
    exit();
}

$role = $_SESSION["role"];
if ($role != "1") {
    header("location:../authantication/login.php");
    exit();
}

include("../database/connection.php"); // Replace with your database connection file

// Fetch employee data if ID is provided
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $employee_id = intval($_GET['id']);

    // Prepare and execute the query to fetch employee details
    $query = "SELECT * FROM users WHERE id = ?";
    if ($stmt = $con->prepare($query)) {
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();
    } else {
        header("Location: manage_users.php?error=Failed%20to%20prepare%20statement");
        exit();
    }

    if (!$employee) {
        header("Location: manage_users.php?error=Employee%20not%20found");
        exit();
    }
} else {
    header("Location: manage_users.php?error=No%20employee%20ID%20provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css">
    <title>Edit Employee</title>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="../homepage/homepage.php">Municipality</a>
        </div>
    </nav>

    <div class="container">
        <div class="back-button">
            <a href="manage_users.php"><button>Back to Manage Users</button></a>
        </div>
        
        <h2>Edit Employee</h2>

        <form id="editForm" action="update_employee.php?id=<?php echo $employee_id?>" method="post" oninput="validateForm()">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($employee['id']); ?>" />

            <div>
                <label>First Name:</label>
                <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($employee['fname']); ?>" required />
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="lname" id="lname" value="<?php echo htmlspecialchars($employee['lname']); ?>" required />
            </div>
            <div>
                <label>Date Of Birth:</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($employee['date_of_birth']); ?>" required />
            </div>
            <div>
                <label>Address:</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($employee['address']); ?>" required />
            </div>
            <div>
                <label>Phone Number:</label>
                <input type="text" name="p_number" id="p_number" value="<?php echo htmlspecialchars($employee['phone_number']); ?>" required />
            </div>

            <div class="employee">
                <label>Role:</label>
                <input type="radio" name="role" value="2" <?php echo ($employee['role'] == 'employee') ? 'checked' : ''; ?>> Employee
                <input type="radio" name="role" value="4" <?php echo ($employee['role'] == 'support') ? 'checked' : ''; ?>> Support
            </div>

            <div>
                <label>Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email." />
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" id="password" />
            </div>
            <div>
                <label>Confirm Password:</label>
                <input type="password" id="confirmPassword" />
            </div>
            <div class="error" id="error"></div>

            <button type="submit" id="submitBtn" disabled>Update</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

    <!-- JavaScript for Form Validation -->
    <script>
    function validateForm() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const email = document.getElementById('email').value;
        const errorDiv = document.getElementById('error');
        const submitBtn = document.getElementById('submitBtn');

        errorDiv.textContent = '';

        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            errorDiv.textContent = 'Please enter a valid email address.';
            submitBtn.disabled = true;
            return;
        }

        if (password !== confirmPassword) {
            errorDiv.textContent = 'Passwords do not match.';
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }
    </script>

</body>
</html>
