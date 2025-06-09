<!-- add_employee.php -->
<?php

session_start();
header('Content-Type: application/json');
require '../assets/databse/connection.php';
require './database/session.php';

// Helper function to sanitize input
function sanitize($conn, $data)
{
    return mysqli_real_escape_string($conn, trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = sanitize($conn, $_POST['firstname'] ?? '');
    $middlename = sanitize($conn, $_POST['middlename'] ?? '');
    $lastname = sanitize($conn, $_POST['lastname'] ?? '');
    $email = sanitize($conn, $_POST['email'] ?? '');
    $address = sanitize($conn, $_POST['address'] ?? '');
    $phone_no = sanitize($conn, $_POST['phone_no'] ?? '');
    $gender = sanitize($conn, $_POST['gender'] ?? '');
    $position = sanitize($conn, $_POST['position'] ?? '');
    $shift = sanitize($conn, $_POST['shift'] ?? '');
    $rate = sanitize($conn, $_POST['rate'] ?? '');

    // Validate required fields
    if (!$firstname || !$lastname || !$email || !$address || !$phone_no || !$gender || !$position || !$shift) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Begin transaction
    mysqli_begin_transaction($conn);

    try {

        if (!empty($middlename)) {
            // Middlename is provided
            $sql_acc = "INSERT INTO tbl_emp_acc (firstname, middlename, lastname, email, address, phone_no, gender, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'inactive')";
            $stmt_acc = mysqli_prepare($conn, $sql_acc);
            mysqli_stmt_bind_param($stmt_acc, "ssssiss", $firstname, $middlename, $lastname, $email, $address, $phone_no, $gender);
        } else {
            // Middlename is empty or null – exclude it from insert
            $sql_acc = "INSERT INTO tbl_emp_acc (firstname, lastname, email, address, phone_no, gender, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'inactive')";
            $stmt_acc = mysqli_prepare($conn, $sql_acc);
            mysqli_stmt_bind_param($stmt_acc, "sssiss", $firstname, $lastname, $email, $address, $phone_no, $gender);
        }

        mysqli_stmt_execute($stmt_acc);

        // Get the last inserted employee id
        $emp_acc_id = mysqli_insert_id($conn);

        // Insert into tbl_emp_info
        $sql_info = "INSERT INTO tbl_emp_info (emp_id, rate, position, shift) VALUES (?, ?, ?, ?)";
        $stmt_info = mysqli_prepare($conn, $sql_info);
        mysqli_stmt_bind_param($stmt_info, "idss", $emp_acc_id, $rate, $position, $shift);
        mysqli_stmt_execute($stmt_info);

        mysqli_commit($conn);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
