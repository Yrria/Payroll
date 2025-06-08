<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_pass'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
        return;
    }

    // Check complexity
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!]).{8,}$/', $newPassword)) {
        echo "<script>alert('Password must be at least 8 characters and include uppercase, lowercase, number, and special character.');</script>";
        return;
    }

    // Assuming email was stored in session after OTP
    if (!isset($_SESSION['reset_email'])) {
        echo "<script>alert('Session expired. Please restart the password reset process.'); window.location.href='forgot_pass.php';</script>";
        exit();
    }

    $email = $_SESSION['reset_email'];
    $password = $new_password;

    // Update password in database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $password, $email);

    if ($stmt->execute()) {
        unset($_SESSION['reset_email']); // Clean up
        echo "<script>alert('Password changed successfully.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error updating password.');</script>";
    }
}
?>
