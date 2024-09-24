<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="auth.css">
    <title>Login</title>
    <style>
        .error {
            color: red;
            margin-top: 10px;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        button:disabled {
            background-color: grey;
            cursor: not-allowed;
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

    <?php
    if(isset($_GET["err"]) && $_GET["err"] == "1") {
      echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        let msg1 = 'Email or password must be incorrect';
        let error = document.getElementById('error');
        if (error) error.textContent = msg1;
      });
      </script>";
    }
    else if(isset($_GET["err"]) && $_GET["err"] == "2") {
      echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        let msg2 = 'Internal error';
        let error = document.getElementById('error');
        if (error) error.textContent = msg2;
      });
      </script>";
    }
    else if(isset($_GET["succ"]) && $_GET["succ"] == "1") {
      echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        let msg3 = 'Successful Sign Up, please login';
        let success = document.getElementById('success');
        if (success) success.textContent = msg3;
      });
      </script>";
    }
    ?>

    <div class="container">
        <div class="back-button">
           <a href="../homepage/homepage.php"><button>Back to Home</button></a> 
        </div>
        <h1>Login</h1>
        <form id="loginForm" action="login2.php" method="post" oninput="validateForm()">
            <div>
              <label>Email:</label>
              <input type="email" name="email" id="email" required pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"/>
            </div>
            <div>
              <label>Password:</label>
              <input type="password" name="password" id="password" required minlength="8" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$"/>
            </div>
            <div class="error" id="error"></div>
            <div class="success" id="success"></div>
            <button type="submit" id="submitBtn" disabled>Login</button>
        </form>
        
        <div class="signup-redirect">
            <p>Don't have an account?</p>
            <a href="signup.php"><button>Sign Up</button></a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Municipality Management System</p>
    </footer>

    <!-- JavaScript for Form Validation -->
    <script>
    function validateForm() {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const errorDiv = document.getElementById('error');
        const submitBtn = document.getElementById('submitBtn');

        let emailIsValid = email.validity.valid;
        let passwordIsValid = password.validity.valid;

        let errorMessage = '';
        if (!emailIsValid) {
            errorMessage += 'Invalid email format. ';
        }
        if (!passwordIsValid) {
            errorMessage += 'Password must be at least 8 characters, contain at least one letter and one number. ';
        }
        errorDiv.textContent = errorMessage;

        submitBtn.disabled = !(emailIsValid && passwordIsValid);
    }
    </script>
</body>
</html>
