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

  <?php include './assets/bootstrap_header.php'?>
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
</body>

</html>