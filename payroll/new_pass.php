<?php
session_start();
include './assets/databse/connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: forgot_pass.php');
    exit();
}

$email = $_SESSION['email'];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];

    if ($newPass !== $confirmPass) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPass) < 8 || !preg_match('/[A-Z]/', $newPass) || !preg_match('/[0-9]/', $newPass)) {
        $error = "Password must be at least 8 characters, include a number and an uppercase letter.";
    } else {
        $plainPass = $newPass;

        $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $adminResult = $stmt->get_result();

        if ($adminResult->num_rows > 0) {
            $update = $conn->prepare("UPDATE tbl_admin_acc SET password = ?, otp = NULL WHERE email = ?");
        } else {
            $update = $conn->prepare("UPDATE tbl_emp_acc SET password = ?, otp = NULL WHERE email = ?");
        }

        $update->bind_param("ss", $plainPass, $email);
        $update->execute();

        //  Set toast flag for next page
        $_SESSION['password_reset'] = true;

        //  Clean up session
        unset($_SESSION['email']);

        //  Redirect to login page
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <link rel="shortcut icon" href="./assets/logowhite-.png" type="image/svg+xml">
  <link rel="stylesheet" href="./assets/css/new_pass.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .toast { position: fixed; top: 1rem; right: 1rem; z-index: 9999; }
    .alert { color: red; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-box">
      <a href="./index.php" class="title-tags">
        <span class="comp-name">
          <img src="./assets/expense.png" class="title-name" alt="ExPense">
        </span>
      </a>
      <p>Enter your new password. Make sure it’s strong and secure.</p>

      <?php if (!empty($error)): ?>
        <div class="alert"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" id="resetForm" onsubmit="return validateForm()">
        <label for="new-password">New Password:</label>
        <div class="input-container">
          <i class="fas fa-eye toggle-password"></i>
          <input type="password" name="new_password" id="new-password" required placeholder="Enter Password">
        </div>

        <label for="confirm-password">Confirm Password:</label>
        <div class="input-container">
          <i class="fas fa-eye toggle-password"></i>
          <input type="password" name="confirm_password" id="confirm-password" required placeholder="Re-enter Password">
        </div>

        <div id="strength-message" style="font-size: 13px; color: white; margin-bottom: 10px;"></div>

        <div class="buttons">
          <a href="./index.php" class="change">Back to Login</a>
          <button type="submit" class="change" name="change_pass">Reset Password</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Show/hide password toggle
    document.querySelectorAll('.toggle-password').forEach(icon => {
      icon.addEventListener('click', function () {
        const input = this.nextElementSibling;
        const type = input.type === 'password' ? 'text' : 'password';
        input.type = type;
        this.classList.toggle('fa-eye-slash');
      });
    });

    // Password strength check
    document.getElementById('new-password').addEventListener('input', function () {
      const value = this.value;
      const message = document.getElementById('strength-message');
      const strong = /^(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
      if (strong.test(value)) {
        message.textContent = "Strong password ✅";
        message.style.color = "#00ff88";
      } else {
        message.textContent = "Use 8+ characters with uppercase and number";
        message.style.color = "#ffcc00";
      }
    });

    // Match confirmation
    function validateForm() {
      const pass = document.getElementById('new-password').value;
      const confirm = document.getElementById('confirm-password').value;
      if (pass !== confirm) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Passwords do not match!'
        });
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
