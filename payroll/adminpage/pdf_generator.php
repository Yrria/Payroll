<?php
ob_start();
session_start();
include '../assets/databse/connection.php';
include './database/session.php';
require '../assets/pdf_vendor/vendor/autoload.php';
use Mpdf\Mpdf;

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id'])) {
    exit('Employee ID not provided.');
}

$emp_id = $_GET['id'];

$query = "
    SELECT 
        e.emp_id,
        e.lastname,
        e.firstname,
        e.middlename,
        e.gender,
        e.email,
        i.position,
        s.cutoff,
        s.basic_pay,
        s.holiday_pay,
        s.ot_pay,
        s.pagibig_deduction,
        s.philhealth_deduction,
        s.sss_deduction,
        s.other_deduction,
        s.gross_pay,
        s.total_salary
    FROM tbl_emp_acc AS e
    INNER JOIN tbl_emp_info AS i ON e.emp_id = i.emp_id
    INNER JOIN tbl_salary AS s ON e.emp_id = s.emp_id
    WHERE e.emp_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $emp_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit('No employee data found.');
}

$employee = $result->fetch_assoc();

// Format employee and salary data
$employee_name = $employee['lastname'] . ', ' . $employee['firstname'] . ' ' . $employee['middlename'];
$employee_id = $employee['emp_id'];
$position = $employee['position'];
$email = $employee['email'];
$cutoff = $employee['cutoff'];

$basic = number_format($employee['basic_pay'], 2);
$holiday = number_format($employee['holiday_pay'], 2);
$ot = number_format($employee['ot_pay'], 2);
$gross = number_format($employee['gross_pay'], 2);
$total = number_format($employee['total_salary'], 2);

$pagibig = number_format($employee['pagibig_deduction'], 2);
$philhealth = number_format($employee['philhealth_deduction'], 2);
$sss = number_format($employee['sss_deduction'], 2);
$other = number_format($employee['other_deduction'], 2);

$deductions_total = number_format($employee['pagibig_deduction'] + $employee['philhealth_deduction'] + $employee['sss_deduction'] + $employee['other_deduction'], 2);

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
    <small>CUTOFF: $cutoff</small>
</div>

<table class='info-table bordered'>
    <tr>
        <td><strong>Employee Name:</strong> $employee_name</td>
        <td><strong>Position:</strong> $position</td>
    </tr>
    <tr>
        <td><strong>Employee ID:</strong> $employee_id</td>
        <td><strong>Email:</strong> $email</td>
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
            <td class='right'>₱$basic</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>Holiday Pay</td>
            <td class='right'>₱$holiday</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>OT Pay</td>
            <td class='right'>₱$ot</td>
            <td class='right'></td>
        </tr>
        <tr>
            <td>Pag-IBIG</td>
            <td class='right'></td>
            <td class='right'>₱$pagibig</td>
        </tr>
        <tr>
            <td>PhilHealth</td>
            <td class='right'></td>
            <td class='right'>₱$philhealth</td>
        </tr>
        <tr>
            <td>SSS</td>
            <td class='right'></td>
            <td class='right'>₱$sss</td>
        </tr>
        <tr>
            <td>Other Deduction</td>
            <td class='right'></td>
            <td class='right'>₱$other</td>
        </tr>
        <tr class='bold'>
            <td>Total</td>
            <td class='right'>₱$total</td>
            <td class='right'>₱$deductions_total</td>
        </tr>
    </tbody>
</table>

<table class='bank-table bordered'>
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

<div class='section-title' style='margin-top: 10px;'>NET PAY: ₱$total</div>
<div style='text-align: center;'>This document is confidential.</div>
";

// Generate PDF
$mpdf = new Mpdf(['format' => 'Letter', 'orientation' => 'L']);
$mpdf->WriteHTML($html);

ob_clean();
$mpdf->Output("Payslip_{$employee['lastname']}.pdf", "D");
exit;
