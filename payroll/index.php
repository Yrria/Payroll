<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="shortcut icon" href="./assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets//css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
            <p>Welcome to [Company Name]'s Payroll System! Easily manage your payroll, access pay slips, and keep track of your payment details all in one secure place. Log in with your employee ID and password to get started.</p>
            <form action="#">
                <label for="employee-id">Employee ID:</label>
                <div class="input-container">
                    <i class="fas fa-user icon"></i>
                    <input type="text" id="employee-id" placeholder="Enter Employee ID" required>
                </div>

                <label for="password">Password:</label>
                <div class="input-container">
                    <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                    <input type="password" id="password" placeholder="Enter Password" required>
                </div>

                <div class="buttons">
                    <!-- <button type="button" class="forgot-password">Forgot Password</button>
                        <button type="submit" class="login">Login</button> -->

                    <a href="./forgot_pass.php" class="forgot-password">Forgot Password</a>
                    <a href="./adminpage/dashboard.php" class="login">Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute using getAttribute and setAttribute methods
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle the eye icon
            this.classList.toggle('fa-eye-slash'); // Change to slash eye when visible
        });
    </script>
</body>

</html>