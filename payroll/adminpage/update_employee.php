<?php
session_start();
include '../assets/databse/connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$empId = $data['empId'];
$position = $data['position'];
$shift = $data['shift'];
$rate = $data['rate'];

// Remove "P" and "/hr" from rate
$cleanRate = floatval(str_replace([], "", $rate));

// Connect to your database
$conn = new mysqli("localhost", "root", "", "payroll");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

$stmt = $conn->prepare("UPDATE tbl_emp_info SET position = ?, shift = ?, rate = ? WHERE emp_id = ?");
$stmt->bind_param("ssds", $position, $shift, $cleanRate, $empId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>