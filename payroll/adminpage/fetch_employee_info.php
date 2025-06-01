<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../assets/databse/connection.php';  // adjust path if needed

// Log the request
error_log("Fetch employee info called with emp_id: " . (isset($_GET['emp_id']) ? $_GET['emp_id'] : 'NOT SET'));

if (!isset($_GET['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'Employee ID missing']);
    exit;
}

$emp_id = $conn->real_escape_string($_GET['emp_id']);

// Check if connection exists
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get employee full name from tbl_emp_acc
$sql_name = "SELECT lastname, firstname, middlename FROM tbl_emp_acc WHERE emp_id = '$emp_id' LIMIT 1";
$result_name = $conn->query($sql_name);

if (!$result_name) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . $conn->error]);
    exit;
}

if ($result_name->num_rows > 0) {
    $row = $result_name->fetch_assoc();
    $fullname = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'];
} else {
    echo json_encode(['success' => false, 'message' => 'Employee not found with ID: ' . $emp_id]);
    exit;
}

// Get current year (you can make this dynamic)
$year = 2024;

// Check if your tables have the date column - adjust column name if needed
// Common date column names: date, created_at, attendance_date, etc.
$date_column_attendance = 'date'; // Change this to your actual date column name
$date_column_deduction = 'date';  // Change this to your actual date column name  
$date_column_salary = 'date';     // Change this to your actual date column name

// Initialize monthly data array
$monthly_data = [];
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

// Initialize totals
$total_worked_hours = 0;
$total_overtime_hours = 0;
$total_deductions = 0;
$total_wage = 0;

// First, let's try a simpler approach - get all data without monthly breakdown for testing
try {
    // Get total worked hours and overtime from tbl_attendance
    $sql_attendance = "SELECT 
        COALESCE(SUM(hours_present), 0) AS total_worked_hours,
        COALESCE(SUM(hours_overtime), 0) AS total_overtime_hours
        FROM tbl_attendance WHERE emp_id = '$emp_id'";
    
    $result_attendance = $conn->query($sql_attendance);
    if ($result_attendance && $result_attendance->num_rows > 0) {
        $row_attendance = $result_attendance->fetch_assoc();
        $total_worked_hours = $row_attendance['total_worked_hours'];
        $total_overtime_hours = $row_attendance['total_overtime_hours'];
    }

    // Get total deductions from tbl_deduction
    $sql_deductions = "SELECT COALESCE(
        SUM(pagibig_deduction + philhealth_deduction + sss_deduction + other_deduction), 
        0) AS total_deductions 
        FROM tbl_deduction WHERE emp_id = '$emp_id'";

    $result_deductions = $conn->query($sql_deductions);
    if ($result_deductions && $result_deductions->num_rows > 0) {
        $row_deductions = $result_deductions->fetch_assoc();
        $total_deductions = $row_deductions['total_deductions'];
    }

    // Get total wage from tbl_salary
    $sql_wage = "SELECT COALESCE(SUM(total_salary), 0) AS total_wage FROM tbl_salary WHERE emp_id = '$emp_id'";
    $result_wage = $conn->query($sql_wage);
    if ($result_wage && $result_wage->num_rows > 0) {
        $row_wage = $result_wage->fetch_assoc();
        $total_wage = $row_wage['total_wage'];
    }

    // For now, create dummy monthly data (you can replace this with real monthly queries later)
    for ($month = 1; $month <= 12; $month++) {
        $monthly_data[] = [
            'month' => $months[$month],
            'worked_hours' => round($total_worked_hours / 12, 2),
            'overtime_hours' => round($total_overtime_hours / 12, 2),
            'deductions' => round($total_deductions / 12, 2),
            'wage' => round($total_wage / 12, 2)
        ];
    }

    echo json_encode([
        'success' => true,
        'fullname' => $fullname,
        'total_worked_hours' => $total_worked_hours,
        'total_overtime_hours' => $total_overtime_hours,
        'total_deductions' => $total_deductions,
        'total_wage' => $total_wage,
        'monthly_data' => $monthly_data,
        'year' => $year,
        'debug' => 'Query executed successfully'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage(),
        'debug' => 'Exception caught'
    ]);
}
?>