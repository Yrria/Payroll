<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

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

// Insert into tbl_emp_acc
$sql1 = "INSERT INTO tbl_emp_acc (firstname, middlename, lastname, email, address, phone_no, gender, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 'inactive')";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("sssssss", $fname, $mname, $lname, $email, $address, $phone, $gender);

if ($stmt1->execute()) {
    $emp_id = $conn->insert_id;

    // Insert into tbl_emp_info
    $sql2 = "INSERT INTO tbl_emp_info (emp_id, emp_info_id, rate, position, shift) VALUES (?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("iidss", $emp_id, $emp_id, $rate, $position, $shift);

    if ($stmt2->execute()) {
        echo "Employee successfully added.";
    } else {
        echo "Error inserting into tbl_emp_info: " . $stmt2->error;
    }

    $stmt2->close();
} else {
    echo "Error inserting into tbl_emp_acc: " . $stmt1->error;
}

$stmt1->close();
$conn->close();
