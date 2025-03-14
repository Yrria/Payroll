<?php
session_start();

include './assets/databse/connection.php';
include './assets/databse/withIndexSession.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shortcut icon" href="./assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/otp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="email-container">
        <div class="email-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
            <p>Enter the reset code sent to your email; it’s valid for 5 minutes, and you can reset your password only once within 24 hours.</p>
        </div>
        <div class="form-box">
            <form action="#">
                <label for="otp">Reset Code</label>
                <div class="input-container">
                    <i class="fas fa-key icon"></i>
                    <input type="text" id="otp" placeholder="Enter The Code" required>
                </div>

                <div class="buttons">
                    <!-- <button type="button" class="resend-code">Resend Code</button>
                    <button type="submit" class="next">Next</button>
                    <button type="button" class="back-to-login">Back to Login</button> -->

                    <a href="./index.php" class="resend-code">Resend Code</a>
                    <a href="./new_pass.php" class="next">Next</a>
                    <a href="./index.php" class="back-to-login">Back to login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript to allow only numeric input and limit the length to 6 digits
        document.getElementById('otp').addEventListener('input', function(e) {
            // Remove any non-digit characters
            e.target.value = e.target.value.replace(/\D/g, '');

            // Limit input to 6 digits
            if (e.target.value.length > 4) {
                e.target.value = e.target.value.slice(0, 4);
            }
        });
    </script>


</body>

</html>