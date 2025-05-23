<?php
// SESSION
if (isset($_SESSION['email']) && isset($_SESSION['account_id'])) {
    $account = $_SESSION['email'];

    // Check if user is an admin
    $stmt = $conn->prepare("SELECT * FROM tbl_admin_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows > 0) {
        // User is an admin
        $user = $user_result->fetch_assoc();
    }
    $stmt->close();

    // Check if user is a coordinator
    $stmt = $conn->prepare("SELECT * FROM tbl_emp_acc WHERE email = ?");
    $stmt->bind_param("s", $account);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_result->num_rows > 0) {
        // User is a coordinator
        header('Location: ../employee/dashboard.php');
        exit();
    }
    $stmt->close();
} else {
    // Redirect to login if no matching user found
    session_destroy();
    header('Location: ../index.php');
    exit();
}
?>