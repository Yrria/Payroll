<?php
ob_start(); // Start output buffering to avoid any accidental output

session_start();
include '../assets/databse/connection.php';
include './database/session.php';

require '../assets/pdf_vendor/vendor/autoload.php';

// Enable error reporting (for debugging; disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $emp_id = $_GET['id'];

    $query = "
        SELECT 
            e.emp_id,
            e.lastname,
            e.firstname,
            e.middlename,
            e.gender,
            e.email,
            e.address,
            e.phone_no,
            a.present_days,
            a.absent_days,
            a.hours_present,
            a.hours_late,
            a.hours_overtime,
            a.holiday,
            i.shift,
            i.rate,
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
        INNER JOIN tbl_attendance AS a ON e.emp_id = a.emp_id
        INNER JOIN tbl_emp_info AS i ON e.emp_id = i.emp_id
        INNER JOIN tbl_salary AS s ON e.emp_id = s.emp_id
        WHERE e.emp_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // No data found
        exit('No employee data found.');
    }

    $employee = $result->fetch_assoc();

    // Assign data
    $fullname = $employee['firstname'] . ' ' . $employee['middlename'] . ' ' . $employee['lastname'];
    $lastname = $employee['lastname'];
    $position_name = $employee['position'];
    $emp_id = $employee['emp_id'];
    $pagibig_deduction = $employee['pagibig_deduction'];
    $philhealth_deduction = $employee['philhealth_deduction'];
    $sss_deduction = $employee['sss_deduction'];
    $other_deduction = $employee['other_deduction'];
    $total_salary = $employee['total_salary'];
    $gross_pay = $employee['gross_pay'];

    $total_deductions = $pagibig_deduction + $philhealth_deduction + $sss_deduction + $other_deduction;

    // HTML content
    $html = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
            }
            .payslip {
                width: 100%;
                margin: 20px auto;
                background: white;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                margin: 0;
                font-size: 20px;
            }
            .header p {
                margin: 0;
                font-size: 12px;
            }
            .details, .salary {
                margin-bottom: 20px;
            }
            .details p {
                margin: 5px 0;
            }
            .salary h3 {
                margin-bottom: 10px;
            }
            .salary table {
                width: 100%;
                border-collapse: collapse;
            }
            .salary table th, .salary table td {
                padding: 8px;
                text-align: left;
            }
            .salary table th {
                background-color: #f2f2f2;
            }
            .amount {
                text-align: right;
            }
        </style>
    </head>
    <body>

    <div class="payslip">
        <div class="header">
            <h1>Expense</h1>
            <h2>Payslip</h2>
        </div>

        <div class="details">
            <p><strong>Name:</strong> '.$fullname.'</p>
            <p><strong>Position:</strong> '.$position_name.'</p>
            <p><strong>Emp. ID:</strong> '.$emp_id.'</p>
        </div>

        <div class="salary">
            <table>
                <thead>
                    <tr>
                        <th><h3>GROSS PAY</h3></th>
                        <th class="amount"><h3>AMOUNT</h3></th>
                    </tr>
                    <tr>
                        <td>'.$gross_pay.'</td>
                        <td class="amount">'.$gross_pay.'</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><h3>DEDUCTIONS</h3></td>
                    </tr>
                    <tr>
                        <td>PAGIBIG</td>
                        <td class="amount">₱'.$pagibig_deduction.'</td>
                    </tr>
                    <tr>
                        <td>Philhealth</td>
                        <td class="amount">₱'.$philhealth_deduction.'</td>
                    </tr>
                    <tr>
                        <td>SSS</td>
                        <td class="amount">₱'.$sss_deduction.'</td>
                    </tr>
                    <tr>
                        <td>Total other deductions</td>
                        <td class="amount">₱'.$other_deduction.'</td>
                    </tr>
                    <tr>
                        <td><strong>NET PAY:</strong></td>
                        <td class="amount"><strong>₱'.$total_salary.'</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    </body>
    </html>
    ';

    // Generate PDF
    $mpdf = new \Mpdf\Mpdf(['format' => 'Letter', 'orientation' => 'L']);
    $mpdf->WriteHTML($html);

    ob_clean(); // Clean output buffer before outputting PDF
    $mpdf->Output("$lastname-payslip.pdf", "D"); // "D" for download, "I" for inline view
    exit;
} else {
    exit('Employee ID not provided.');
}
