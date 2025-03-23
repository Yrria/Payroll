<?php
session_start();

include './assets/databse/connection.php';
include './assets/databse/withIndexSession.php';
include './assets/databse/mailer.php';

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

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
  <div class="email-container">
    <div class="email-box">
      <a href="./index.php" class="title-tags"><span class="comp-name"><img src="./assets/expense.png" class="title-name" alt="ExPense"></span></a>
      <p>To change your password, enter your registered email, open your inbox to see the reset code sent to you, and follow the instructions to reset your password.</p>
    </div>
    <div class="form-box">
      <form method="POST">
        <label for="email">Email:</label>
        <div class="input-container">
          <i class="fas fa-envelope icon"></i>
          <input type="email" id="email" name="email" placeholder="Enter Email" required>
        </div>

        <div class="buttons">
          <a href="./index.php" class="next">Back to login</a>
          <button type="submit" name="submit" class="next">Next</button>
        </div>

      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // FOR MESSAGEBOX
    function alertMessage(redirectUrl, title, text, icon, iconHtml) {
      Swal.fire({
        icon: icon,
        iconHtml: iconHtml, // Custom icon using Font Awesome
        title: title,
        text: text,
        customClass: {
          popup: 'swal-custom'
        },
        showConfirmButton: true,
        confirmButtonColor: '#4CAF50',
        confirmButtonText: 'OK',
        timer: 5000
      }).then(() => {
        window.location.href = redirectUrl; // Redirect to the desired page
      });
    }

    // WARNING FOR DUPE ACCOUNT
    function warningError(title, text, icon, iconHtml) {
      Swal.fire({
        icon: icon,
        iconHtml: iconHtml, // Custom icon using Font Awesome
        title: title,
        text: text,
        customClass: {
          popup: 'swal-custom'
        },
        showConfirmButton: true,
        confirmButtonColor: '#4CAF50',
        confirmButtonText: 'OK',
        timer: 5000,
      });
    }
  </script>

</body>

</html>