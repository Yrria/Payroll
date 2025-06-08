<?php
session_start();
include '../assets/databse/connection.php';

$emp_id = $_SESSION['emp_id'];
$year = $_GET['year'];
$month = $_GET['month'];
$cutoff = $_GET['cutoff'];

if (!$emp_id) exit('Unauthorized');

// Get Salary Data
$salary_query = mysqli_query($conn, "
    SELECT 
        s.*, 
        CONCAT(e.firstname, ' ', e.middlename, ' ', e.lastname) AS employee_name 
    FROM tbl_salary s
    JOIN tbl_emp_acc e ON s.emp_id = e.emp_id
    WHERE s.emp_id = '$emp_id' 
      AND s.year = '$year' 
      AND s.month = '$month' 
      AND s.cutoff = '$cutoff'
");
$salary_data = mysqli_fetch_assoc($salary_query);

if (!$salary_data) exit('No data found');

// Get Attendance Data
$attendance_query = mysqli_query($conn, "
    SELECT 
        SUM(hours_present) AS total_hours, 
        AVG(hours_present) AS avg_hours, 
        SUM(hours_overtime) AS overtime 
    FROM tbl_attendance 
    WHERE emp_id = '$emp_id'  
");
$attendance = mysqli_fetch_assoc($attendance_query);

// Parse hours
$total_regular = floor($attendance['total_hours']);
$avg_minutes = floor(($attendance['avg_hours'] - floor($attendance['avg_hours'])) * 60);
$avg_hours = floor($attendance['avg_hours']);
$overtime = floor($attendance['overtime']);

echo "
<div class='salary-details'>
    <h2>More Info</h2>
    <div class='info-box'>
        <div class='employee-info'>
            <h3><i class='avatar'></i> {$salary_data['employee_name']}</h3>
            <p><strong>Avg. Daily Hours:</strong> {$avg_hours}hrs {$avg_minutes}m</p>
            <p><strong>Total Regular Hours Worked:</strong> {$total_regular}hrs</p>
            <p><strong>Total Overtime Hours Worked:</strong> {$overtime}h</p>
            <p><strong>Pay Type:</strong> Hourly</p>
            <p><strong>Rate:</strong> ₱{$salary_data['basic_pay']}/hr</p>
            <p><strong>Total Wage:</strong> ₱" . number_format($salary_data['total_salary'], 2) . "</p>
        </div>

        <table class='summary-table'>
            <thead>
                <tr>
                    <th>Months</th>
                    <th>Regular Hours</th>
                    <th>Overtime Hours</th>
                    <th>Total Worked Hours</th>
                    <th>Total Wage</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{$month}</td>
                    <td>" . floor($attendance['total_hours']) . "h " . round(($attendance['total_hours'] - floor($attendance['total_hours'])) * 60) . "m</td>
                    <td>" . floor($attendance['overtime']) . "h " . round(($attendance['overtime'] - floor($attendance['overtime'])) * 60) . "m</td>
                    <td>" . floor($attendance['total_hours'] + $attendance['overtime']) . "h " . round((($attendance['total_hours'] + $attendance['overtime']) - floor($attendance['total_hours'] + $attendance['overtime'])) * 60) . "m</td>
                    <td>₱" . number_format($salary_data['total_salary'], 2) . "</td>
                </tr>
            </tbody>
        </table>

        <form method='GET' action='generate_payslip.php' style='display: inline;'>
            <input type='hidden' name='emp_id' value='{$emp_id}'>
            <input type='hidden' name='year' value='{$year}'>
            <input type='hidden' name='month' value='{$month}'>
            <input type='hidden' name='cutoff' value='{$cutoff}'>
            <button type='submit' class='btn-green'>Generate Pay Slip PDF</button>
        </form> 
    </div>
</div>
";
?>
