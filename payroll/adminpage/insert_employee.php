<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

use PHPMailer\PHPMailer\PHPMailer;

require '../assets/vendor/autoload.php';

// Collect and sanitize input
$fname     = htmlspecialchars(trim($_POST['fname'] ?? ''));
$mname     = htmlspecialchars(trim($_POST['mname'] ?? ''));
$lname     = htmlspecialchars(trim($_POST['lname'] ?? ''));
$email     = htmlspecialchars(trim($_POST['email'] ?? ''));
$address   = htmlspecialchars(trim($_POST['address'] ?? ''));
$phone_raw = $_POST['phone'] ?? '';
$gender    = htmlspecialchars(trim($_POST['gender'] ?? ''));
$rate_raw  = $_POST['rate'] ?? '';
$position  = htmlspecialchars(trim($_POST['position'] ?? ''));
$shift     = htmlspecialchars(trim($_POST['shift'] ?? ''));

// Sanitize phone to contain only digits
$phone = preg_replace('/[^0-9]/', '', $phone_raw);

// Convert rate to float
$rate = floatval($rate_raw);

// Validate required fields
if (!$fname || !$lname || !$email || !$address || !$phone || !$gender || !$position || !$shift || $rate <= 0) {
    echo "Invalid input. Please fill in all required fields correctly.";
    exit;
}

$temporaryPass = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);

// Insert into tbl_emp_acc
$sql1 = "INSERT INTO tbl_emp_acc (firstname, middlename, lastname, email, address, phone_no, gender, password, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'inactive')";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("ssssssss", $fname, $mname, $lname, $email, $address, $phone, $gender, $temporaryPass);

if ($stmt1->execute()) {
    $emp_id = $conn->insert_id;

    // Insert into tbl_emp_info
    $pay_type = 'Cash';

    $sql2 = "INSERT INTO tbl_emp_info (emp_id, emp_info_id, rate, position, shift, pay_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("iidsss", $emp_id, $emp_id, $rate, $position, $shift, $pay_type);


    if ($stmt2->execute()) {
        echo "Employee successfully added.";

        // SEND EMAIL TO INFORM ALUMNI

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'alumni.management07@gmail.com'; //NOTE gawa ka ng new email account nyo gaya nito, yan kasi ang magiging bridge/ sya ang mag sesend ng email
        $mail->Password   = 'kcio bmde ffvc sfar';           // di ako sude dito pero eto ata ung password ng email / pagdi tanong mo nalang kay Nyel
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // pwede nyo rin naman gamitin nayang email namin pero hingi kalang muna permission kay dhaniel pre, 
        $mail->Port       = 587;

        $mail->setFrom('alumni.management07@gmail.com', 'Payroll Management'); // eto ung email at yung name ng email na makikita sa una
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Payroll Account'; // eto ung mga laman ng email na isesend
        $mail->Body = 'Your account has been <b>Created</b> by administrator. 
               <br>Please login and change your password to continue the system.
               <br>Your temporary password is: <b>' . $temporaryPass . '</b>.
               <br>Thank you and have a nice day.
               <br><br>This is an automated message please do not reply.';
        $mail->AltBody = 'Your account has been <b>Created</b> by administrator.';

        $mail->send();
    } else {
        echo "Error inserting into tbl_emp_info: " . $stmt2->error;
    }

    $stmt2->close();
} else {
    echo "Error inserting into tbl_emp_acc: " . $stmt1->error;
}

$stmt1->close();
$conn->close();
