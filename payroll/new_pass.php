<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="shortcut icon" href="./assets/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/new_pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/title.png" class="title-name" alt="ExPense"></span></a>
            <p>To change your password, ensure it’s complex, containing a mix of uppercase letters, lowercase letters, numbers, and special characters.</p>
            <form action="#">
                <label for="employee-id">Password:</label>
                <div class="input-container">
                    <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                    <input type="text" id="employee-id" placeholder="Enter Password" required>
                </div>

                <label for="password">Confirm Password:</label>
                <div class="input-container">
                    <i class="fas fa-eye toggle-password" id="toggle-password"></i>
                    <input type="password" id="password" placeholder="Re-Enter Password" required>
                </div>

                <div class="buttons">
                    <!-- <button type="submit" class="change">Change</button> -->
                     
                    <a href="./index.php" class="change">Change</a>
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