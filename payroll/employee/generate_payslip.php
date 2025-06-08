<?php
require '../assets/pdf_vendor/vendor/autoload.php';
use Mpdf\Mpdf;

include '../assets/databse/connection.php';

$emp_id = $_GET['emp_id'] ?? '';
$year = $_GET['year'] ?? '';
$month = $_GET['month'] ?? '';
$cutoff = $_GET['cutoff'] ?? '';

if (!$emp_id || !$year || !$month || !$cutoff) {
    die('Missing required parameters.');
}

// Get employee info
$emp_sql = "
    SELECT 
        a.lastname, 
        a.firstname, 
        a.middlename, 
        a.emp_id AS employee_id, 
        a.status, 
        a.email, 
        i.position 
    FROM tbl_emp_acc a
    LEFT JOIN tbl_emp_info i ON a.emp_id = i.emp_id
    WHERE a.emp_id = '$emp_id'
";
$emp_result = mysqli_query($conn, $emp_sql);
$emp = mysqli_fetch_assoc($emp_result);

// Get salary info
$salary_sql = "SELECT * FROM tbl_salary 
               WHERE emp_id = '$emp_id' 
               AND year = '$year' 
               AND month = '$month' 
               AND cutoff = '$cutoff'";
$salary_result = mysqli_query($conn, $salary_sql);
$salary = mysqli_fetch_assoc($salary_result);

if (!$emp || !$salary) {
    die('Data not found.');
}

// Format data
$employee_name = $emp['lastname'] . ', ' . $emp['firstname'] . ' ' . $emp['middlename'];
$employee_id = $emp['employee_id'];
$status = $emp['status'];
$position = $emp['position'];
$email = $emp['email'];

$pagibig = number_format($salary['pagibig_deduction'], 2);
$philhealth = number_format($salary['philhealth_deduction'], 2);
$sss = number_format($salary['sss_deduction'], 2);
$other = number_format($salary['other_deduction'], 2);

$basic = number_format($salary['basic_pay'], 2);
$holiday = number_format($salary['holiday_pay'], 2);
$ot = number_format($salary['ot_pay'], 2);  
$total = number_format($salary['total_salary'], 2);

// Build HTML
$html = "
<style>
    body { font-family: sans-serif; font-size: 12px; }
    .header, .footer { background: #0c2340; color: white; padding: 10px; text-align: center; }
    .section-title { background: #0c2340; color: white; font-weight: bold; padding: 5px; }
    .info-table, .salary-table, .bank-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .info-table td, .salary-table td, .salary-table th, .bank-table td { padding: 5px; }
    .salary-table th { background: #0c2340; color: white; }
    .bordered { border: 1px solid #ddd; }
    .right { text-align: right; }
    .bold { font-weight: bold; }
</style>

<div class='header'>
    <h2>Expense</h2>
    <h3>Salary Slip</h3>
    <small>PERIOD: $month $year | Cutoff: $cutoff</small>
</div>

<table class='info-table bordered'>
    <tr>
        <td><strong>Employee Name:</strong> $employee_name</td>
        <td><strong>Status:</strong> {$emp['status']}</td>
    </tr>
    <tr>
        <td><strong>Employee ID:</strong> {$emp['employee_id']}</td>
        <td><strong>Position:</strong> {$emp_info['position']}</td>
    </tr>
    <tr>
        <td colspan='2'><strong>Email:</strong> {$emp['email']}</td>
    </tr>
</table>

<table class='salary-table bordered'>
    <thead>
        <tr>
            <th>Description</th>
            <th>Earnings</th>
            <th>Deductions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Basic Pay</td>
            <td class='right'>₱" . number_format($salary['basic_pay'], 2) . "</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>Holiday Pay</td>
            <td class='right'>₱" . number_format($salary['holiday_pay'], 2) . "</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>OT Pay</td>
            <td class='right'>₱" . number_format($salary['ot_pay'], 2) . "</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>Pag Ibig</td>
            <td class='right'></td>
            <td class='right'>₱" . number_format($salary['pagibig_deduction'], 2) . "</td>
        </tr>
        <tr>
            <td>SSS</td>
            <td class='right'></td>
            <td class='right'>₱" . number_format($salary['sss_deduction'], 2) . "</td>
        </tr>
        <tr>
            <td>PhilHealth</td>
            <td class='right'></td>
            <td class='right'>₱" . number_format($salary['philhealth_deduction'], 2) . "</td>
        </tr>
        <tr>
            <td>Other Deduction</td>
            <td class='right'></td>
            <td class='right'>₱" . number_format($salary['other_deduction'], 2) . "</td>
        </tr>
        <tr class='bold'>
            <td>Total</td>
            <td class='right'>₱" . number_format($salary['total_salary'], 2) . "</td>
            <td class='right'>₱" . number_format($salary['pagibig_deduction'] + $salary['philhealth_deduction'] + $salary['sss_deduction'] + $salary['other_deduction'], 2) . "</td>
        </tr>
    </tbody>
</table>

<table class='bank-table bordered'>
    <tr>
        <td><strong>Payment Date:</strong></td><td>$payment_date</td>
    </tr>
    <tr>
        <td><strong>Bank Name:</strong></td><td>Power Bank</td>
    </tr>
    <tr>
        <td><strong>Bank Account Name:</strong></td><td>$employee_name</td>
    </tr>
    <tr>
        <td><strong>Bank #:</strong></td><td>012345678999</td>
    </tr>
</table>

<div class='section-title' style='margin-top: 10px;'>NET PAY: ₱$net_pay</div>
<div style='text-align: center;'>This document is confidential.</div>
";

// Generate PDF
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Payslip_{$emp['employee_id']}_{$month}_{$year}.pdf", "D");
exit;
?>
