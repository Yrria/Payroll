<?php
include '../assets/databse/connection.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'Employee ID missing']);
    exit;
}

$emp_id = $conn->real_escape_string($_GET['emp_id']);

// Summary query with joins but avoid row multiplication by only grouping by emp_id
$sql_summary = "
    SELECT 
        e.firstname, 
        e.lastname, 
        e.middlename, 
        i.rate,
        COALESCE(SUM(a.hours_present), 0) AS total_worked_hours,
        COALESCE(SUM(a.hours_overtime), 0) AS total_overtime_hours,
        COALESCE(SUM(d.pagibig_deduction + d.philhealth_deduction + d.sss_deduction + d.other_deduction), 0) AS total_deductions,
        COALESCE(SUM(s.basic_pay + s.holiday_pay + s.ot_pay), 0) AS total_wage,
        MAX(YEAR(a.attendance_date)) AS last_year -- get latest year with attendance
    FROM tbl_emp_acc e
    LEFT JOIN tbl_emp_info i ON e.emp_id = i.emp_id
    LEFT JOIN tbl_attendance a ON e.emp_id = a.emp_id
    LEFT JOIN tbl_deduction d ON e.emp_id = d.emp_id
    LEFT JOIN tbl_salary s ON e.emp_id = s.emp_id
    WHERE e.emp_id = '$emp_id'
    GROUP BY e.emp_id
";

$result_summary = $conn->query($sql_summary);
if (!$result_summary) {
    echo json_encode(['success' => false, 'message' => 'SQL Error (summary): ' . $conn->error]);
    exit;
}

if ($result_summary->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Employee summary not found']);
    exit;
}

$summary = $result_summary->fetch_assoc();

$year = $summary['last_year'] ?? date('Y'); // Use latest attendance year or current year

// Monthly data query with all months included
$sql_monthly = "
SELECT 
    m.month,
    m.month_name,
    COALESCE(t.worked_hours, 0) AS worked_hours,
    COALESCE(t.overtime_hours, 0) AS overtime_hours,
    COALESCE(t.deductions, 0) AS deductions,
    COALESCE(t.total_wage, 0) AS total_wage
FROM 
    (
        SELECT 1 AS month, 'January' AS month_name UNION ALL
        SELECT 2, 'February' UNION ALL
        SELECT 3, 'March' UNION ALL
        SELECT 4, 'April' UNION ALL
        SELECT 5, 'May' UNION ALL
        SELECT 6, 'June' UNION ALL
        SELECT 7, 'July' UNION ALL
        SELECT 8, 'August' UNION ALL
        SELECT 9, 'September' UNION ALL
        SELECT 10, 'October' UNION ALL
        SELECT 11, 'November' UNION ALL
        SELECT 12, 'December'
    ) AS m
LEFT JOIN
    (
        SELECT 
            MONTH(a.attendance_date) AS month,
            COALESCE(SUM(a.hours_present), 0) AS worked_hours,
            COALESCE(SUM(a.hours_overtime), 0) AS overtime_hours,
            COALESCE(SUM(d.pagibig_deduction + d.philhealth_deduction + d.sss_deduction + d.other_deduction), 0) AS deductions,
            COALESCE(SUM(s.basic_pay + s.holiday_pay + s.ot_pay), 0) AS total_wage
        FROM tbl_attendance a
        LEFT JOIN tbl_deduction d 
            ON a.emp_id = d.emp_id 
            AND MONTH(a.attendance_date) = MONTH(d.deduction_date) 
            AND YEAR(a.attendance_date) = YEAR(d.deduction_date)
        LEFT JOIN tbl_salary s
            ON a.emp_id = s.emp_id
            AND YEAR(a.attendance_date) = s.year
            AND MONTHNAME(a.attendance_date) = s.month
        WHERE a.emp_id = '$emp_id' AND YEAR(a.attendance_date) = $year
        GROUP BY MONTH(a.attendance_date)
    ) AS t ON m.month = t.month
ORDER BY m.month
";

$result_monthly = $conn->query($sql_monthly);
if (!$result_monthly) {
    echo json_encode(['success' => false, 'message' => 'SQL Error (monthly): ' . $conn->error]);
    exit;
}

$monthly_data = [];
while ($row = $result_monthly->fetch_assoc()) {
    $monthly_data[] = [
        'month' => $row['month_name'],
        'year' => $year,
        'worked_hours' => (float)$row['worked_hours'],
        'overtime_hours' => (float)$row['overtime_hours'],
        'deductions' => (float)$row['deductions'],
        'total_wage' => (float)$row['total_wage'],
    ];
}

// Prepare full name
$fullname = trim($summary['lastname'] . ', ' . $summary['firstname'] . ' ' . $summary['middlename']);

echo json_encode([
    'success' => true,
    'fullname' => $fullname,
    'total_worked_hours' => (float)$summary['total_worked_hours'],
    'total_overtime_hours' => (float)$summary['total_overtime_hours'],
    'total_deductions' => (float)$summary['total_deductions'],
    'total_wage' => (float)$summary['total_wage'],
    'monthly_data' => $monthly_data
]);
