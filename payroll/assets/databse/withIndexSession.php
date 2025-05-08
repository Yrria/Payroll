<?php
$currentPage = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['email']) && isset($_SESSION['account_id'])) {
    $account = $_SESSION['email'];

    // Check if email matches any valid account
    $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $admin_result = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $emp_result = $stmt->get_result();
    $stmt->close();

    if ($admin_result->num_rows === 0 && $emp_result->num_rows === 0) {
        // No matching account found — destroy session immediately
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

if (isset($_SESSION['email']) && isset($_SESSION['verifCode'])) {
    $account = $_SESSION['email'];
    $code = $_SESSION['verifCode'];

    $otpMatched = false;

    // --- Check in admin table ---
    $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $account, $code);
    $stmt->execute();
    $admin_result = $stmt->get_result();
    if ($admin_result->num_rows > 0) {
        $otpMatched = true;
    }
    $stmt->close();

    // --- Check in employee table only if not matched in admin ---
    if (!$otpMatched) {
        $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE email = ? AND otp = ?");
        $stmt->bind_param("ss", $account, $code);
        $stmt->execute();
        $emp_result = $stmt->get_result();
        if ($emp_result->num_rows > 0) {
            $otpMatched = true;
        }
        $stmt->close();
    }

    if ($otpMatched) {
        if ($currentPage !== 'otp.php') {
            header('Location: otp.php');
            exit();
        }
    } else {
        // Clear OTP and destroy session only if no match in either table
        $update = $conn->prepare("UPDATE tbl_admin_acc SET otp = '' WHERE email = ?");
        $update->bind_param("s", $account);
        $update->execute();
        $update->close();

        $update = $conn->prepare("UPDATE tbl_emp_acc SET otp = '' WHERE email = ?");
        $update->bind_param("s", $account);
        $update->execute();
        $update->close();

        session_unset();
        session_destroy();

        if ($currentPage !== 'forgot_pass.php') {
            header('Location: forgot_pass.php');
            exit();
        }
    }
} elseif (isset($_SESSION['email']) && isset($_SESSION['account_id'])) {
    $account = $_SESSION['email'];

    // Check if user is an admin
    $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows > 0) {
        header('Location: ./adminpage/dashboard.php');
        exit();
    }
    $stmt->close();

    // Check if user is an employee
    $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $user_result = $stmt->get_result();
    if ($user_result->num_rows > 0) {
        $row = $user_result->fetch_assoc();
        if ($row['status'] === "active") {
            header('Location: ./employee/dashboard.php');
            exit();
        } else {
            $_SESSION['alert'] = 'inactive';
            sleep(2);
            header('Location: ./employee/inactive.php');
            exit();
        }
    }
    $stmt->close();

    // No valid account match
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
