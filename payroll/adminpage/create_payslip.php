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
            i.shift,
            i.rate,
            i.position,
            s.basic_pay,
            s.pagibig_deduction,
            s.philhealth_deduction,
            s.sss_deduction,
            s.ot_pay
        FROM tbl_emp_acc AS e
        INNER JOIN tbl_attendance AS a ON e.emp_id = a.emp_id
        INNER JOIN tbl_salary AS s ON e.emp_id = s.emp_id
        INNER JOIN tbl_emp_info AS i ON i.emp_id = i.emp_id
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
        $position_name = $employee['position'];
        $present_days = $employee['present_days'];
        $rate_pday = $employee['rate'];
        $hours_overtime = $employee['hours_overtime'];
        $holiday_present = $employee['holiday'];
        $pagibig_deduction = $employee['pagibig_deduction'];
        $philhealth_deduction = $employee['philhealth_deduction'];
        $sss_deduction = $employee['sss_deduction'];

        // computations
        $compute_present_holiday = $present_days - $holiday_present;
        $compute_ot = $rate_pday / 8;
        $computed_ot = $compute_ot * $hours_overtime;
        $computed_rate_wage = $rate_pday * $present_days;
        $compute_holiday = $holiday_present * 2;
        $computed_holiday = $compute_holiday * $rate_pday;
        $computed_present_holiday = ($compute_present_holiday * $rate_pday) + $computed_holiday;
        $all_deduction = $pagibig_deduction + $philhealth_deduction + $sss_deduction;
        $net_income = $computed_present_holiday -  $all_deduction;
        
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
                    <span>Rate per Day:</span><input class="income_inputs" type="text" readonly value="<?php echo $rate_pday?>" name="" id=""><br>
                    <span>No. Of Days:</span><input class="income_inputs" value="<?php echo $present_days?>" type="text" name="days_input" id=""><span>Rate Wage:</span><input class="income_inputs" readonly value="<?php echo $computed_present_holiday ?>" type="text" name="" id=""><br>
                    <span>OT hr/Day:</span><input class="income_inputs" readonly value="<?php echo $hours_overtime?>" type="text" name="" id=""><span>OT hr/Day:</span><input class="income_inputs" readonly value="<?php echo $computed_ot?>" type="text" name="" id=""><br>
                    <span>Holiday Pay (day):</span><input class="income_inputs" type="text" readonly value="<?php echo $holiday_present?>" name="" id=""><span>Holiday Pay:</span><input class="income_inputs" readonly value="<?php echo $computed_holiday?>"  type="text" name="" id=""><br>
                    <span>Net Income:</span><input class="income_inputs" type="text" readonly value="<?php echo $net_income?>" value="" name="" id="">
                </div>
                <div class="deduction_div">
                    <span style="margin-right:40%;">Deductions</span> <span>Other Deductions</span><br>
                    <div style="display: flex; flex-direction: row;">
                        <div style="display: flex; flex-direction: column;">
                            <div>
                                <span>Philhealth:</span><input class="deduction_inputs" readonly value="<?php echo $pagibig_deduction?>"  type="text" name="" id="">
                            </div>
                            <div>
                                <span>PAGIBIG:</span><input class="deduction_inputs" readonly value="<?php echo $philhealth_deduction?>"  type="text" name="" id="">
                            </div>
                            <div>
                                <span>SSS:</span><input class="deduction_inputs" readonly value="<?php echo $sss_deduction?>"  type="text" name="" id="">
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <div style="display: flex; justify-content: space-between; width: 230px;">
                                <span>Deduction Name</span>
                                <span>Value</spans>
                            </div>
                            <div>
                                <input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs"  type="text" name="" id="">
                            </div>
                            <div>
                                <input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs"  type="text" name="" id="">
                            </div>
                            <div>
                                <input class="deduction_inputs" type="text" name="" id=""><input class="deduction_inputs"  type="text" name="" id="">
                            </div>
                            <div>
                                <input class="deduction_inputs"  type="text" name="" id=""><input class="deduction_inputs" type="text" name="" id="">
                            </div>
                        </div>
                    </div>
                    <span>Total Deductions:</span><input class="deduction_inputs" readonly value=""  type="text" name="" id="">
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
    <script>
        // JavaScript to allow only numeric input and limit the length to 6 digits
        document.getElementById('days_input').addEventListener('input', function(e) {
            // Remove any non-digit characters
            e.target.value = e.target.value.replace(/\D/g, '');

            // Limit input to 6 digits
            if (e.target.value.length > 6) {
                e.target.value = e.target.value.slice(0, 6);
            }
        });
    </script>
</body>

</html>