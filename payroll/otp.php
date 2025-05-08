<?php
session_start();

include './assets/databse/connection.php';
include './assets/databse/withIndexSession.php';
include './assets/databse/otp_query.php';

$email = $_SESSION['email'];
if ($email == 0) {
    header('Location: index.php');
    exit();
}
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
    <?php include './assets/bootstrap_header.php' ?>

    <style>
        .buttons {
            display: flex;
            justify-content: space-between;
            width: 1100px;
        }

        button {
            flex: 1;
            margin: 5px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.5s ease;
        }

        a {
            flex: 1;
            margin: 5px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.5s ease;
            text-align: center;
            text-decoration: none;
        }

        a:hover {
            transform: translateY(-0px);
        }

        .resend-code {
            background-color: #f39c12;
            color: #FFFFFF;
        }

        .back-to-login {
            background-color: #555555;
            color: #FFFFFF;
            margin-left: 500px;
        }

        .next {
            background-color: #3498db;
            color: #FFFFFF;
            margin-left: 40px;
        }

        button:hover {
            transform: translateY(-0px);
        }

        .resend-code:hover {
            background-color: #f7ce1c;
        }

        .next:hover {
            background-color: #47cbff;
        }

        .back-to-login:hover {
            background-color: #000000;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-box">
            <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
            <p>Enter the reset code sent to your email; it’s valid for 5 minutes, and you can reset your password only once within 24 hours.</p>
        </div>
        <div class="form-box">
            <form id="otpForm" method="POST">
                <label for="otp" style="color: white;">Reset Code</label>
                <div class="input-container">
                    <i class="fas fa-key icon"></i>
                    <input type="hidden" name="action" id="form-action" value="submit_otp">
                    <input type="text" id="otp" name="otp" placeholder="Enter The Code" required>
                </div>

                <div class="buttons">
                    <!-- Resend button triggers JS to submit the form with 'resend' action -->
                    <button type="button" name="resend" class="resend-code" onclick="submitResend()">Resend Code</button>

                    <!-- This is the real submit button that respects required fields -->
                    <button type="submit" class="next">Next</button>

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
            if (e.target.value.length > 6) {
                e.target.value = e.target.value.slice(0, 6);
            }
        });

        function submitResend() {
            document.getElementById('form-action').value = 'resend';
            document.forms[0].submit(); // Submit the form
        }
    </script>

</body>

</html>