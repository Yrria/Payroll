<?php
if (isset($_SESSION['email']) && isset($_SESSION['account_id'])) {
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

    // Check if user is a employee
    $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows > 0) {
        // User is a employee
        header('Location: ./employee/dashboard.php');
        exit();
    }
    $stmt->close();
} else {
    // Check if input code is provided in the session
    if (isset($_SESSION['inputcode']) && !empty($_SESSION['inputcode'])) {
        $inputcode = $_SESSION['inputcode'];

        // Check if the entered input code matches any value in tbl_admin_acc
        $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE otp = ?");
        $stmt->bind_param("s", $inputcode);
        $stmt->execute();
        $admin_result = $stmt->get_result();

        if ($admin_result->num_rows > 0) {
            // Code is valid for admin, fetch and display data
            $user = $admin_result->fetch_assoc();
        }

        // Check if the entered input code matches any value in tbl_emp_acc
        $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE otp = ?");
        $stmt->bind_param("s", $inputcode);
        $stmt->execute();
        $emp_result = $stmt->get_result();

        if ($emp_result->num_rows > 0) {
            // Code is valid for employee, fetch and display data
            $user = $emp_result->fetch_assoc();
        }

        $stmt->close();
    } elseif (empty($_SESSION['inputcode'])) {
        // If input code is empty in session, redirect to index.php
        unset($_SESSION['inputcode']);
        header('Location: ./index.php');
        exit();
    }
}
