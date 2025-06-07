<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Employee login
    $sql_emp = "SELECT emp_id, email FROM tbl_emp_acc WHERE email = '$email' AND password = '$password'";
    $result_emp = $conn->query($sql_emp);

    // Admin login
    $sql_admin = "SELECT admin_id, email FROM tbl_admin_acc WHERE email = '$email' AND password = '$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_emp->num_rows > 0) {
        $emp_row = $result_emp->fetch_assoc();
        $_SESSION['email'] = $emp_row['email'];
        $_SESSION['account_id'] = $emp_row['emp_id'];
        $emp_id = $emp_row['emp_id'];

        // Get full employee info
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
                i.shift,
                i.rate,
                i.position
            FROM tbl_emp_acc AS e
            INNER JOIN tbl_emp_info AS i ON e.emp_id = i.emp_id
            WHERE e.emp_id = ?
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $emp_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $emp_data = $result->fetch_assoc();

        if ($emp_data) {
            date_default_timezone_set('Asia/Manila');
            
            // Use current date/time for month, cutoff, and year instead of first attendance date
            date_default_timezone_set('Asia/Manila');
            $now = new DateTime();

            $month_str = $now->format('F');  // e.g. "June"
            $day = (int)$now->format('d');
            $year = (int)$now->format('Y');
            $cutoff = ($day <= 15) ? "First Cutoff" : "Second Cutoff";


            // Now use these $year, $month, $cutoff values in your salary checking and inserting code

            // Check salary record using these values
            $check_salary = $conn->prepare("SELECT * FROM tbl_salary WHERE emp_id = ? AND year = ? AND month = ? AND cutoff = ?");
            $check_salary->bind_param("iiis", $emp_id, $year, $month_str, $cutoff);
            $check_salary->execute();
            $salary_result = $check_salary->get_result();

            if ($salary_result->num_rows === 0) {
                $l_name = $emp_data['lastname'];
                $f_name = $emp_data['firstname'];
                $m_name = $emp_data['middlename'];
                $position = $emp_data['position'];
                $shift = $emp_data['shift'];
                $basic_pay = floatval($emp_data['rate']);

                $holiday_pay = 0.00;
                $ot_pay = 0.00;
                $pagibig_deduction = 0.00;
                $philhealth_deduction = 0.00;
                $sss_deduction = 0.00;
                $other_deduction = 0.00;
                $gross_pay = $basic_pay + $holiday_pay + $ot_pay;
                $total_deductions = $pagibig_deduction + $philhealth_deduction + $sss_deduction + $other_deduction;
                $total_salary = $gross_pay - $total_deductions;
                $status = 'Unpaid';

                $insert_salary = $conn->prepare("INSERT INTO tbl_salary (
                    emp_id, l_name, f_name, m_name, position_name, emp_shift,
                    year, month, cutoff, status,
                    basic_pay, holiday_pay, ot_pay,
                    pagibig_deduction, philhealth_deduction, sss_deduction, other_deduction,
                    gross_pay, total_salary
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $insert_salary->bind_param(
                    "isssssisssddddddddd",
                    $emp_id, $l_name, $f_name, $m_name, $position, $shift,
                    $year, $month_str, $cutoff, $status,
                    $basic_pay, $holiday_pay, $ot_pay,
                    $pagibig_deduction, $philhealth_deduction, $sss_deduction, $other_deduction,
                    $gross_pay, $total_salary
                );
                $insert_salary->execute();
            }

            // Attendance logic
            $now = new DateTime();
            $today = $now->format('Y-m-d');
            $hour = (int)$now->format('H');
            if ($emp_data['shift'] === 'Night' && $hour < 6) {
                $today = (new DateTime('yesterday'))->format('Y-m-d');
            }

            $attendance_check = $conn->prepare("SELECT * FROM tbl_attendance WHERE emp_id = ? AND attendance_date = ?");
            $attendance_check->bind_param("is", $emp_id, $today);
            $attendance_check->execute();
            $attendance_result = $attendance_check->get_result();

            if ($attendance_result->num_rows === 0) {
                // No attendance record yet for today - create initial record
                $insert_attendance = $conn->prepare("INSERT INTO tbl_attendance (emp_id, attendance_date) VALUES (?, ?)");
                $insert_attendance->bind_param("is", $emp_id, $today);
                $insert_attendance->execute();

                // Now compute status and update once, only after first insert

                $status = null;
                $now_ts = $now->getTimestamp();

                if ($emp_data['shift'] === 'Morning') {
                    $present_start = strtotime("$today 06:45:00");
                    $present_end = strtotime("$today 07:15:00");
                    $late_end = strtotime("$today 15:00:00");

                    if ($now_ts < $present_start) {
                        $status = null;
                    } elseif ($now_ts <= $present_end) {
                        $status = "Present";
                    } elseif ($now_ts <= $late_end) {
                        $status = "Late";
                    } else {
                        $status = "Absent";
                    }
                } elseif ($emp_data['shift'] === 'Night') {
                    $night_start = strtotime("$today 18:45:00");
                    $present_end = strtotime("$today 19:15:00");
                    $late_end = strtotime("$today +1 day 03:00:00");

                    if ($now_ts < $night_start) {
                        $status = null;
                    } elseif ($now_ts <= $present_end) {
                        $status = "Present";
                    } elseif ($now_ts <= $late_end) {
                        $status = "Late";
                    } else {
                        $status = "Absent";
                    }
                }

                if ($status !== null) {
                    $default_hours = 8;
                    $hours_late = 0;
                    $hours_present = 0;

                    if ($status === "Present") {
                        $hours_present = $default_hours;
                    } elseif ($status === "Late") {
                        $late_time = ($now_ts - $present_end) / 3600;
                        $hours_late = round(min(max($late_time, 0), 8), 2);
                        $hours_present = max($default_hours - $hours_late, 0);
                    } elseif ($status === "Absent") {
                        $hours_late = $default_hours;
                    }

                    $present_inc = $status !== "Absent" ? 1 : 0;
                    $absent_inc = $status === "Absent" ? 1 : 0;

                    // Update the newly inserted attendance record
                    $update_attendance = $conn->prepare("
                        UPDATE tbl_attendance 
                        SET attendance_today = ?, 
                            present_days = present_days + ?, 
                            absent_days = absent_days + ?, 
                            hours_late = ?, 
                            hours_present = ?
                        WHERE emp_id = ? AND attendance_date = ?
                    ");
                    $update_attendance->bind_param("siiddis", $status, $present_inc, $absent_inc, $hours_late, $hours_present, $emp_id, $today);
                    $update_attendance->execute();
                }
            } else {
                // Attendance record already exists for today, do nothing to avoid multiple increments
            }


            header("Location: ./employee/dashboard.php");
            exit();
        }
    } elseif ($result_admin->num_rows > 0) {
        $admin_row = $result_admin->fetch_assoc();
        $_SESSION['email'] = $admin_row['email'];
        $_SESSION['account_id'] = $admin_row['admin_id'];
        header("Location: ./adminpage/dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}
?>
