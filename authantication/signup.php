<?php
if(isset($_GET["err"]) && $_GET["err"] == "1") {
  echo "<script>
  document.addEventListener('DOMContentLoaded', function() {
    let msg = 'Some information must be incorrect';
    let error = document.getElementById('error');
    if (error) error.innerHTML = msg;
  });
  </script>";
}
else if(isset($_GET["err"]) && $_GET["err"] == "2") {
  echo "<script>
  document.addEventListener('DOMContentLoaded', function() {
    let msg = 'Internal error';
    let error = document.getElementById('error');
    if (error) error.innerHTML = msg;
  });
  </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css">
    <title>Sign Up</title>
    <style>
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <a href="../homepage/homepage.php">Municipality</a>
        </div>
    </nav>



    <div class='container'>
        <div class="back-button">
            <a href="../homepage/homepage.php"><button>Back to Home</button></a>
        </div>
        <?php
    session_start();
    if (isset($_SESSION["role"]) && ($_SESSION["role"] == 1 || $_SESSION["role"] == 0)) {
        echo "<h2>Register an Employee</h2>";
    }
    else{
      echo "<h2>Sign Up</h2>";
    }
    ?>
        
        <form id="signupForm" action="signup2.php" method="post" oninput="validateForm()">
            <div>
                <label>First Name:</label>
                <input type="text" name="fname" id="fname" required />
            </div>
            <div>
                <label>Last Name:</label>
                <input type="text" name="lname" id="lname" required />
            </div>
            <div>
                <label>Date Of Birth:</label>
                <input type="date" name="dob" id="dob" required />
            </div>
            <div>
                <label>Address:</label>
                <input type="text" name="address" id="address" required />
            </div>
            <div>
                <label>Phone Number:</label>
                <input type="text" name="p_number" id="p_number" required />
            </div>

            <?php
            if (isset($_SESSION["role"]) && ($_SESSION["role"] == 1 || $_SESSION["role"] == 0)) {
                echo "<div class='employee'>
                    <label>Employee:</label>
                    <input type='radio' name='role' value='employee'>
                    <label>Support:</label>
                    <input type='radio' name='role' value='support'>
                </div>";
            }
            ?>

            <div>
                <label>Email:</label>
                <input type="email" name="email" id="email" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email." />
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" id="password"  />
            </div>
            <div>
                <label>Confirm Password:</label>
                <input type="password" id="confirmPassword" required />
            </div>
            <div class="error" id="error"></div>

            <?php
            if (!isset($_SESSION["role"]) || ($_SESSION["role"] != 1 && $_SESSION["role"] != 0)) {
                echo "<button type='submit' id='submitBtn' disabled>Sign Up</button>";
            } else {
                echo "<button type='submit' id='submitBtn' disabled>Register</button>";
            }
            ?>
        </form>
        <?php
            if (!isset($_SESSION["role"]) || ($_SESSION["role"] != 1 && $_SESSION["role"] != 0)) {
                echo "        <div class='signup-redirect'>
            <p>Already have an account?</p>
            <a href='login.php'><button>Login</button></a>
        </div>";
            } else {
                echo "";
            }
            ?>

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

        // const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
        // if (!passwordPattern.test(password)) {
        //     errorDiv.textContent = 'Password must be at least 8 characters, include a capital letter, a number, and a special character.';
        //     submitBtn.disabled = true;
        //     return;
        // }

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
