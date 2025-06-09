<?php
session_start();
include '../../assets/databse/connection.php';

header('Content-Type: application/json');

$userId = $_SESSION['emp_id']; // assuming session holds user_id
$current = $_POST['currentPass'];
$new = $_POST['newPass'];

if (!$userId || !$current || !$new) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// Get current password
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($dbPassword);
$stmt->fetch();
$stmt->close();

if ($current !== $dbPassword) {
    echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    exit;
}

// Update password
$update = "UPDATE tbl_emp_acc SET password = ?, status = 'inactive' WHERE emp_id = ?";
$stmt = $conn->prepare($update);
$stmt->bind_param("si", $new, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
}
?>
