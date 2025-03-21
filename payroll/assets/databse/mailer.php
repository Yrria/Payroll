<?php 

use PHPMailer\PHPMailer\PHPMailer;
require './assets/vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $verification_code = sprintf("%06d", mt_rand(1, 999999));
    $email = mysqli_real_escape_string($conn, $email);
    $verification_code = mysqli_real_escape_string($conn, $verification_code);

    $check_email_admin_qry = mysqli_query($conn, "SELECT * FROM admin_acc WHERE email = '$email'");
    $check_email_emp_qry = mysqli_query($conn, "SELECT * FROM emp_acc WHERE email = '$email'");
    
    $alumni_archive_checkstatus = mysqli_fetch_assoc($check_email_alumniarchive_qry);

    if (mysqli_num_rows($check_email_admin_qry) > 0 || mysqli_num_rows( $check_email_emp_qry) > 0) {
      // Email exists and is verified
      $insert_verifcodes_qry = mysqli_query($conn, "INSERT INTO emp_acc(otp)
              VALUES('$email','$verification_code')");
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
      $_SESSION['email'] = $email;

      // WARNING NOT VERIFIED
      $icon = 'success';
      $iconHtml = '<i class="fas fa-check-circle"></i>';
      $title = 'Verification code successfully send';
      $text = 'You will be redirected shortly to verify the email.';
      $redirectUrl = './verification_code.php';

      echo "<script>
              document.addEventListener('DOMContentLoaded', function() {
                  alertMessage('$redirectUrl', '$title', '$text', '$icon', '$iconHtml');
              });
          </script>";
    } else {

        // ERROR NOT EXIST
        $icon = 'error';
        $iconHtml = '<i class=\"fas fa-exclamation-circle\"></i>';
        $title = 'The email you input does not exist!';
        $text = 'Please create an account first or try again.';
  
        echo "<script>
              document.addEventListener('DOMContentLoaded', function() {
                  warningError('$title', '$text', '$icon', '$iconHtml');
              });
          </script>";
        sleep(2);
      }
}
?>
