<?php
session_start();

include './assets/databse/connection.php';
include './assets/databse/withIndexSession.php';

function sendOTP($email) {
    global $conn;

    if (empty($email)) {
        echo "<script>alert('Email field cannot be empty!');</script>";
        return;
    }

    $otp = rand(1000, 9999);

    $updateAdmin = $conn->prepare("UPDATE admin_acc SET otp_code = ? WHERE email = ?");
    $updateEmp = $conn->prepare("UPDATE emp_acc SET otp_code = ? WHERE email = ?");
    
    $updateAdmin->bind_param("is", $otp, $email);
    $updateEmp->bind_param("is", $otp, $email);

    $adminUpdated = $updateAdmin->execute();
    $empUpdated = $updateEmp->execute();

    if (!$adminUpdated && !$empUpdated) {
        echo "<script>alert('Email not found in records. Please check and try again.');</script>";
        return;
    }

    $subject = "Your OTP Code for Password Reset";
    $message = "Your OTP code is: $otp";
    $headers = "From: no-reply@yourdomain.com";

    if (mail($email, $subject, $message, $headers)) {
        $_SESSION['email'] = $email;
        header("Location: otp.php");
        exit();
    } else {
        echo "<script>alert('Failed to send OTP. Please try again later.');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    sendOTP($email);
}

if (isset($_POST['resend_otp'])) {
    $email = $_SESSION['email'] ?? '';
    sendOTP($email);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="shortcut icon" href="./assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/forgot_pass.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="email-container">
        <div class="email-box">
        <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
            <p>To change your password, enter your registered email, open your inbox to see the reset code sent to you, and follow the instructions to reset your password.</p>
        </div>
        <div class="form-box">
        <form method="POST" action="forgot_password.php">
            <label for="email">Email:</label>
            <div class="input-container">
                <i class="fas fa-envelope icon"></i>
                <input type="email" id="email" name="email" placeholder="Enter Email" required>
            </div>

            <div class="buttons">
                <a href="./index.php" class="next">Back to login</a>
                <button type="submit" class="next">Next</button>
            </div>

            <div class="resend-container">
                <button type="submit" name="resend_otp" class="resend-btn">Resend Code</button>
            </div>
        </form>
        </div>
    </div>
</body>
</html>
