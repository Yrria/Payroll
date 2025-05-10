<?php
session_start();
header('Content-Type: application/json'); // Important for JSON response
include '../assets/databse/connection.php';
include './database/session.php';

if (!isset($_SESSION['emp_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_id = $_SESSION['emp_id'];
    $subject = trim($_POST['subject']);
    $start_date = $_POST['startdate'];
    $end_date = $_POST['enddate'];
    $leave_type = $_POST['leavetype'];
    $message = trim($_POST['message']);
    $status = "Pending";
    $date_applied = date('Y-m-d');

    // Basic validation
    if (empty($subject) || empty($start_date) || empty($end_date) || empty($leave_type) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all required fields.'
        ]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_leave (emp_id, subject, start_date, end_date, message, leave_type, status, date_applied) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $emp_id, $subject, $start_date, $end_date, $message, $leave_type, $status, $date_applied);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Your leave request has been recorded.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'There was an error saving your leave application.'
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
