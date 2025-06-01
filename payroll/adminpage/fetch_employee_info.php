<?php
session_start();
include '../assets/databse/connection.php';  // adjust path if needed

if (!isset($_GET['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'Employee ID missing']);
    exit;
}

$emp_id = $conn->real_escape_string($_GET['emp_id']);

// Get employee full name from tbl_emp_acc
$sql_name = "SELECT lastname, firstname, middlename FROM tbl_emp_acc WHERE emp_id = '$emp_id' LIMIT 1";
$result_name = $conn->query($sql_name);

if ($result_name && $result_name->num_rows > 0) {
    $row = $result_name->fetch_assoc();
    $fullname = $row['lastname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'];
} else {
    echo json_encode(['success' => false, 'message' => 'Employee not found']);
    exit;
}

// Get total worked hours from tbl_attendance
$sql_worked = "SELECT COALESCE(SUM(hours_present), 0) AS total_worked_hours FROM tbl_attendance WHERE emp_id = '$emp_id'";
$result_worked = $conn->query($sql_worked);
$total_worked_hours = 0;
if ($result_worked && $result_worked->num_rows > 0) {
    $row_worked = $result_worked->fetch_assoc();
    $total_worked_hours = $row_worked['total_worked_hours'];
}

// Get total overtime hours from tbl_attendance
$sql_overtime = "SELECT COALESCE(SUM(hours_overtime), 0) AS total_overtime_hours FROM tbl_attendance WHERE emp_id = '$emp_id'";
$result_overtime = $conn->query($sql_overtime);
$total_overtime_hours = 0;
if ($result_overtime && $result_overtime->num_rows > 0) {
    $row_overtime = $result_overtime->fetch_assoc();
    $total_overtime_hours = $row_overtime['total_overtime_hours'];
}

// Get total deductions from tbl_deduction
$sql_deductions = "
    SELECT COALESCE(
        SUM(pagibig_deduction + philhealth_deduction + sss_deduction + other_deduction), 
    0) AS total_deductions 
    FROM tbl_deduction WHERE emp_id = '$emp_id'
";

$result_deductions = $conn->query($sql_deductions);
$total_deductions = 0;
if ($result_deductions && $result_deductions->num_rows > 0) {
    $row_deductions = $result_deductions->fetch_assoc();
    $total_deductions = $row_deductions['total_deductions'];
}

// Get total wage from tbl_salary
$sql_wage = "SELECT COALESCE(SUM(total_salary), 0) AS total_wage FROM tbl_salary WHERE emp_id = '$emp_id'";
$result_wage = $conn->query($sql_wage);
$total_wage = 0;
if ($result_wage && $result_wage->num_rows > 0) {
    $row_wage = $result_wage->fetch_assoc();
    $total_wage = $row_wage['total_wage'];
}



echo json_encode([
    'success' => true,
    'fullname' => $fullname,
    'total_worked_hours' => $total_worked_hours,
    'total_overtime_hours' => $total_overtime_hours,
    'total_deductions' => $total_deductions,
    'total_wage' => $total_wage
]);
