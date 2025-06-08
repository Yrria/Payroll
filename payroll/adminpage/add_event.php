<?php
header('Content-Type: application/json'); // Tell browser you're returning JSON

// Show errors (for development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connection
$host = "localhost";
$username = "root";
$password = "";
$database = "payroll";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Auto-delete past events (events older than today)
$today = date('Y-m-d');
$conn->query("DELETE FROM tbl_events WHERE event_date < '$today'");

// Get POST data from JavaScript
$title = $_POST['title'] ?? '';
$type = $_POST['type'] ?? '';
$date = $_POST['date'] ?? '';

if ($title && $type && $date) {
    $stmt = $conn->prepare("INSERT INTO tbl_events (title, type, event_date) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sss", $title, $type, $date);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Event added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert event: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
}

$conn->close();
?>
