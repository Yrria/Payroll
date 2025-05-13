<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check employee account
    $sql_emp = "SELECT emp_id, email FROM tbl_emp_acc WHERE email = '$email' AND password = '$password'";
    $result_emp = $conn->query($sql_emp);

    // Check admin account
    $sql_admin = "SELECT admin_id, email FROM tbl_admin_acc WHERE email = '$email' AND password = '$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_emp->num_rows > 0) {
        $row = $result_emp->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['account_id'] = $row['emp_id'];
        $acc_id = $row['emp_id'];
        date_default_timezone_set('Asia/Manila'); // or your relevant timezone

        $current_date = (new DateTime())->format('Y-m-d');

        // Get shift info
        $shift_check_query = mysqli_query($conn, "SELECT shift FROM tbl_emp_info WHERE emp_id = '$acc_id'");
        if ($shift_check_query && $shift_check_query->num_rows > 0) {
            $shift_fetch = $shift_check_query->fetch_assoc();
            $shift = $shift_fetch['shift'];

            $now = new DateTime();

            $check_attendance = mysqli_query($conn, "SELECT attendance_today FROM tbl_attendance WHERE emp_id = '$acc_id' AND attendance_date = '$current_date'");
            $attendance_data = mysqli_fetch_assoc($check_attendance);

            
            if (!empty($attendance_data['attendance_today'])) {
                return;
            }

            $status = null;

            $today = (new DateTime())->format('Y-m-d');

            $tomorrow = (new DateTime('tomorrow'))->format('Y-m-d');

            if ($shift === "Night") {
                $present_start = new DateTime("$today 18:45:00");
                $present_end = new DateTime("$today 19:15:00");
                $late_end = new DateTime("$tomorrow 03:00:00");
                $absent_after = new DateTime("$tomorrow 03:00:01");

                if ($now < $present_start) {
                    // too early
                } elseif ($now >= $present_start && $now <= $present_end) {
                    $status = 'Present';
                } elseif ($now > $present_end && $now <= $late_end) {
                    $status = 'Late';
                } elseif ($now > $late_end) {
                    $status = 'Absent';
                }
            }elseif ($shift === "Morning") {
                $present_start = new DateTime("$today 06:45:00");
                $present_end = new DateTime("$today 07:15:00");
                $late_end = new DateTime("$today 15:00:00");
                $absent_after = new DateTime("$today 15:00:01");

                if ($now < $present_start) {
                    // too early, maybe do nothing
                } elseif ($now >= $present_start && $now <= $present_end) {
                    $status = 'Present';
                } elseif ($now > $present_end && $now <= $late_end) {
                    $status = 'Late';
                } elseif ($now > $absent_after) {
                    $status = 'Absent';
                }
            }
            if ($status) {
                $current_date = date('Y-m-d');
                $query = "UPDATE tbl_attendance 
                        SET attendance_today = '$status', 
                            attendance_date = '$current_date', 
                            present_days = present_days + 1
                        WHERE emp_id = '$acc_id'";
                
                mysqli_query($conn, $query);
            }
        }

        header("Location: ./employee/dashboard.php");
        exit();
    } elseif ($result_admin->num_rows > 0) {
        $row = $result_admin->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['account_id'] = $row['admin_id'];
        header("Location: ./adminpage/dashboard.php");
        exit();
    } else {
        $error = "Invalid Employee/Admin Email or Password.";
    }
}
$conn->close();
?>