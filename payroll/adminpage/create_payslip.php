<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

include '../assets/databse/connection.php';

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
            a.position_name,
            a.emp_shift,
            s.basic_pay,
            s.ot_pay
        FROM tbl_emp_acc AS e
        INNER JOIN tbl_attendance AS a ON e.emp_id = a.emp_id
        INNER JOIN tbl_salary AS s ON e.emp_id = s.emp_id
        WHERE e.emp_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $emp_id = $employee['emp_id'];
        $gender = $employee['gender'];
        $address = $employee['address'];
        $position_name = $employee['position_name'];
        $present_days = $employee['present_days'];
    
        // Define fullname properly
        $fullname = $employee['firstname'] . ' ' . $employee['middlename'] . ' ' . $employee['lastname'];
    } else {
        echo "No employee data found.";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/create_payslip.css">
    <title>Payroll</title>
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
                    <h5>Create Payslip</h5>
                </div>
                <hr>
            </div>
                <p style="margin-left:1%;">Salary for January</p>
            <div class="selection_div">
                <table>
                    <tr>
                        <td>Employee ID:</td>
                        <td><?php echo $emp_id  ?></td>
                        <td>Bank Name:</td>
                        <td>BPO Bank</td>
                    </tr>
                    <tr>
                        <td>Employee Name:</td>
                        <td><?php echo htmlspecialchars($fullname)?></td>
                        <td>Bank Account:</td>
                        <td>01236183</td>
                    </tr>
                    <tr>
                        <td>Gender:</td>
                        <td><?php echo $gender ?></td>
                        <td>Payable Working Days:</td>
                        <td><?php echo $present_days ?></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><?php echo $address ?></td>
                    </tr>
                    <tr>
                        <td>Position:</td>
                        <td><?php echo $position_name ?></td>
                    </tr>
                    <tr>
                        <td>Date Joined:</td>
                        <td>7-21-24</td>
                    </tr>
                </table>
            </div>
            <div class="inc_dec_div">
                <div class="income_div">
                    <span>Income</span><br>
                    <span>Pay Method:</span><input class="income_inputs" type="text" name="" id=""><span>Rate per Day:</span><input class="income_inputs" type="text" name="" id=""><br>
                    <span>No. Of Days:</span><input class="income_inputs" type="text" name="" id=""><span>Rate Wage:</span><input class="income_inputs" type="text" name="" id=""><br>
                    <span>OT hr/Day:</span><input class="income_inputs" type="text" name="" id=""><span>OT hr/Day:</span><input class="income_inputs" type="text" name="" id=""><br>
                    <span>Holiday Pay (day):</span><input class="income_inputs" type="text" name="" id=""><span>Holiday Pay:</span><input class="income_inputs" type="text" name="" id=""><br>
                </div>
                <div class="deduction_div">
                    <span style="margin-right:40%;">Deductions</span> <span>Other Deductions</span><br>
                    <span>Philhealth:</span><input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 6%;" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 0;" type="text" name="" id=""><br>
                    <span>PAGIBIG:</span><input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 9%;" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 0;" type="text" name="" id=""><br>
                    <span>SSS:</span><input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 15%;" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 0;" type="text" name="" id=""><br>
                    <span>Loan:</span><input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 13%;" type="text" name="" id=""><input class="deduction_inputs" style="margin-left: 0;" type="text" name="" id="">
                </div>  
            </div>
            <div class="res_can_pay_div">
                <div class="res_can_div">
                    <button class="payall_btn">Reset</button>
                    <a href="./payroll.php"><button class="cancel_btn">Cancel</button></a>
                </div>
                <div>
                    <button class="pay_btn">Generate Slip</button>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/payroll.js"></script>
</body>

</html>