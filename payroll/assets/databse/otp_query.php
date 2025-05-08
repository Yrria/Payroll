<?php

use PHPMailer\PHPMailer\PHPMailer;

require './assets/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'] ?? ''; // Use a hidden input or button name to determine the action

  if ($action === 'resend') {
    // === RESEND BUTTON CLICKED ===
    $email = $_SESSION['email'];
    $verification_code = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

    $email = mysqli_real_escape_string($conn, $email);
    $verification_code = mysqli_real_escape_string($conn, $verification_code);

    $check_email_admin_qry = mysqli_query($conn, "SELECT * FROM tbl_admin_acc WHERE email = '$email'");
    $check_email_emp_qry = mysqli_query($conn, "SELECT * FROM tbl_emp_acc WHERE email = '$email'");

    if (mysqli_num_rows($check_email_admin_qry) > 0) {
      mysqli_query($conn, "UPDATE tbl_admin_acc SET otp = '$verification_code' WHERE email = '$email'");
    } elseif (mysqli_num_rows($check_email_emp_qry) > 0) {
      mysqli_query($conn, "UPDATE tbl_emp_acc SET otp = '$verification_code' WHERE email = '$email'");
    } else {
      header("Location: forgot_pass.php");
      exit();
    }

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'alumni.management07@gmail.com';
    $mail->Password   = 'kcio bmde ffvc sfar';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('alumni.management07@gmail.com', 'Alumni Management');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Verification Code';
    $mail->Body    = 'Your verification code is <b>' . $verification_code . '</b>';
    $mail->AltBody = 'Your verification code is ' . $verification_code;
    $mail->send();

    $_SESSION['verifCode'] = $verification_code;

    // WARNING NOT VERIFIED
    $icon = 'success';
    $iconHtml = '<i class="fas fa-check-circle"></i>';
    $title = 'Verification code resend successfully send';
    $text = 'Please check your email.';

    echo "<script>
              document.addEventListener('DOMContentLoaded', function() {
                  alertMessageNolink('$title', '$text', '$icon', '$iconHtml');
              });
          </script>";
  } elseif ($action === 'submit_otp') {
    // === NEXT OR SUBMIT BUTTON CLICKED ===
    $email = $_SESSION['email'];
    $otp = $_POST['otp'] ?? '';

    if (empty($email) || empty($otp)) {
      $icon = 'error';
      $iconHtml = '<i class="fas fa-check-circle"></i>';
      $title = 'Missing email or OTP.';
      $text = 'Please try again';

      echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                alertMessageNolink('$title', '$text', '$icon', '$iconHtml');
            });
        </script>";
      exit(); // stop execution if inputs are missing
    }

    // Check OTP in employee table
    $query_emp = "SELECT * FROM tbl_emp_acc WHERE email = ? AND otp = ?";
    $stmt_emp = $conn->prepare($query_emp);
    $stmt_emp->bind_param("ss", $email, $otp);
    $stmt_emp->execute();
    $result_emp = $stmt_emp->get_result();

    if ($result_emp->num_rows > 0) {
      $_SESSION['inputcode'] = $otp;
      header("Location: new_pass.php");
      exit();
    }

    // Check OTP in admin table
    $query_admin = "SELECT * FROM tbl_admin_acc WHERE email = ? AND otp = ?";
    $stmt_admin = $conn->prepare($query_admin);
    $stmt_admin->bind_param("ss", $email, $otp);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();

    if ($result_admin->num_rows > 0) {
      $_SESSION['inputcode'] = $otp;
      header("Location: new_pass.php");
      exit();
    }

    // If no match found in both tables
    $icon = 'error';
    $iconHtml = '<i class="fas fa-check-circle"></i>';
    $title = 'Invalid or expired code.';
    $text = 'Please try again';

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            alertMessageNolink('$title', '$text', '$icon', '$iconHtml');
        });
    </script>";
  }
}
