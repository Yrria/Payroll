<?php
session_start();

include './assets/databse/connection.php';
include './assets/databse/session.php';
include './assets/databse/login.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="shortcut icon" href="./assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets//css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
            <p>Welcome to [Company Name]'s Payroll System! Easily manage your payroll, access pay slips, and keep track of your payment 
                details all in one secure place. Log in with your employee ID and password to get started.</p>
            <form action="" method="POST">
    
                <label for="employee-id">Employee/Admin ID:</label>
                <div class="input-container">
                    <i class="fas fa-user icon"></i>
                    <input type="text" name="email" id="id" placeholder="Enter Employee or Admin ID" required>
                </div>

                <label for="password">Password:</label>
                <div class="input-container">
                    <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                    <input type="password" name="password" id="password" placeholder="Enter Password" required>
                </div>

                <div class="buttons">
                    <a href="./forgot_pass.php" class="forgot-password">Forgot Password</a>
                    <button type="submit" class="login">Login</button>
                </div>

                <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
