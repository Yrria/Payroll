<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

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

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $email = $employee['email'];
        $phone_num = $employee['phone_no'];
        $cutoff = $employee['cutoff'];
        $position_name = $employee['position'];
        $present_days = $employee['present_days'];
        $basic_pay = $employee['basic_pay'];
        $holiday_pay = $employee['holiday_pay'];
        $ot_pay = $employee['ot_pay'];
        $total_salary = $employee['total_salary'];
        $pagibig_deduction = $employee['pagibig_deduction'];
        $philhealth_deduction = $employee['philhealth_deduction'];
        $sss_deduction = $employee['sss_deduction'];
        $other_deduction = $employee['other_deduction'];

        // computations
        $total_deductions = $pagibig_deduction + $philhealth_deduction + $sss_deduction + $other_deduction;
        
        // Define fullname properly
        $fullname = $employee['firstname'] . ' ' . $employee['middlename'] . ' ' . $employee['lastname'];
    } else {
        echo "No employee data found.";
    }
    
}
$now = new DateTime();

$month_now = $now->format('F');
$year_now = $now->format('Y');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/view_slips.css">
    <title>Payroll</title>
    <style>


    </style>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Payroll</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./payslips.php">Payslip List </a></h5>
                    <span> > </span>
                    <h5>Payslip</h5>
                </div>
                <div class="download-container">
                    <a href="pdf_generator.php?id=<?php echo $emp_id; ?>" class="download-btn">
                        <i class="fas fa-download"></i> Download
                    </a>
                    </div>
                <hr>
            </div>

            <div class="selection_div">
                <table>
                    <tr>
                        <td> 
                            <div style="display:flex; align-items:center;">
                                <img src="../assets/logoblack-.png" alt="logo" style="height:40px; margin-right:2%;"><img src="../assets/title.png" alt="ExPense" style="height:15px;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $cutoff?></td>
                        <td style="text-align:right;">Salary Month: <?php echo $month_now; echo $year_now?></td>
                    </tr>
                </table>
                <hr style="opacity:0.5;">
                <table class="comp_infos">
                    <thead>
                        <tr>
                            <td style="font-size: 13px;font-weight:700;">From</td>
                            <td style="font-size: 13px;font-weight:700;">To</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-size: 15px;font-weight:600;">Expense</td>
                            <td style="font-size: 15px;font-weight:600;"><?php echo htmlspecialchars($fullname)?></td>
                        </tr>
                        <tr>
                            <td><span>CVSu 9632</span></td>
                            <td><span><?php echo $position_name ?></span></td>
                        </tr>
                        <tr>
                            <td><span >Email:</span>expensep@email.com</td>
                            <td><span>Email:</span><?php echo $email ?></td>
                        </tr>
                        <tr>
                            <td><span>Phone:</span> +1 936 281 832</td>
                            <td><span>Phone:</span><?php echo $phone_num ?></td>
                        </tr>
                    </tbody>
                </table>    
                <hr style="opacity:0.5;">
                <p>Payslip of the Month <?php echo $month_now?></p>
                <div class="earn_deduc_div">
                    <div class="earnings-deductions-table">
                        <table class="earnings">
                            <thead>
                                <tr>
                                    <th>Earnings</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Basic Pay</td>
                                    <td>₱<?php echo $basic_pay ?></td>
                                </tr>
                                <tr>
                                    <td>Holiday Pay</td>
                                    <td>₱<?php echo $holiday_pay ?></td>
                                </tr>
                                <tr>
                                    <td>Ot Pay</td>
                                    <td>$<?php echo $ot_pay ?></td>
                                </tr>
                                <tr>
                                    <td>Total Earnings</td>
                                    <td>₱<?php echo $total_salary ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="deductions">
                            <thead>
                                <tr>
                                    <th>Deductions</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PAGIBIG</td>
                                    <td>₱<?php echo $pagibig_deduction ?></td>
                                </tr>
                                <tr>
                                    <td>SSS</td>
                                    <td>₱<?php echo $sss_deduction ?></td>
                                </tr>
                                <tr>
                                    <td>PhilHealth</td>
                                    <td>₱<?php echo $philhealth_deduction ?></td>
                                </tr>
                                <tr>
                                    <td>Others</td>
                                    <td>₱<?php echo $other_deduction ?></td>
                                </tr>
                                <tr>
                                    <td>Total Deductions</td>
                                    <td>$<?php echo $total_deductions?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/payroll.js"></script>
</body>

</html>