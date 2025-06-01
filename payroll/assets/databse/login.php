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

        date_default_timezone_set('Asia/Manila'); // Set correct timezone
        $now = new DateTime();
        $nowFormatted = $now->format('Y-m-d H:i:s');
        error_log("Login detected at $nowFormatted");

        // Determine $today and $tomorrow correctly based on time (for night shift span)
        $hour = (int)$now->format('H');
        $today = ($hour < 6) ? (new DateTime('yesterday'))->format('Y-m-d') : $now->format('Y-m-d');
        $tomorrow = (new DateTime($today . ' +1 day'))->format('Y-m-d');
        $current_date = $now->format('Y-m-d');

        // Get shift info
        $shift_check_query = mysqli_query($conn, "SELECT shift FROM tbl_emp_info WHERE emp_id = '$acc_id'");
        if ($shift_check_query && $shift_check_query->num_rows > 0) {
            $shift_fetch = $shift_check_query->fetch_assoc();
            $shift = $shift_fetch['shift'];
            error_log("Shift: $shift");

            // Check if attendance record exists
            $check_attendance = mysqli_query($conn, "SELECT * FROM tbl_attendance WHERE emp_id = '$acc_id' AND attendance_date = '$current_date'");
            $attendance_data = mysqli_fetch_assoc($check_attendance);

            // Insert record if it doesn't exist
            if (!$attendance_data) {
                mysqli_query($conn, "INSERT INTO tbl_attendance (emp_id, attendance_date) VALUES ('$acc_id', '$current_date')");
                $attendance_data = ['attendance_today' => null];
                error_log("Inserted new attendance record for $current_date");
            }

            // Skip if already marked
            if (!empty($attendance_data['attendance_today'])) {
                error_log("Already marked: " . $attendance_data['attendance_today']);
            } else {
                $status = null;

                if ($shift === "Night") {
                    $present_start = new DateTime("$today 18:45:00");
                    $present_end = new DateTime("$today 19:15:00");
                    $late_end = new DateTime("$tomorrow 03:00:00");

                    if ($now < $present_start) {
                        error_log("Too early to log night shift attendance.");
                    } elseif ($now >= $present_start && $now <= $present_end) {
                        $status = 'Present';
                    } elseif ($now > $present_end && $now <= $late_end) {
                        $status = 'Late';
                    } else {
                        $status = 'Absent';
                    }
                } elseif ($shift === "Morning") {
                    $present_start = new DateTime("$today 06:45:00");
                    $present_end = new DateTime("$today 07:15:00");
                    $late_end = new DateTime("$today 15:00:00");

                    if ($now < $present_start) {
                        error_log("Too early to log morning shift attendance.");
                    } elseif ($now >= $present_start && $now <= $present_end) {
                        $status = 'Present';
                    } elseif ($now > $present_end && $now <= $late_end) {
                        $status = 'Late';
                    } else {
                        $status = 'Absent';
                    }
                }

                if ($status) {
                    $query = "UPDATE tbl_attendance 
                              SET attendance_today = '$status', 
                                  attendance_date = '$current_date', 
                                  present_days = present_days + 1
                              WHERE emp_id = '$acc_id'";
                    if (mysqli_query($conn, $query)) {
                        error_log("Attendance updated to $status for emp_id $acc_id");
                    } else {
                        error_log("Failed to update attendance: " . mysqli_error($conn));
                    }
                } else {
                    error_log("Status was not set due to unmatched time range.");
                }
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
