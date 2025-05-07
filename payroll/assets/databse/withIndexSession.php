<?php
// SESSION
if (isset($_SESSION['email'])) {
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
        // header('Location: ./employee/dashboard.php');
        // exit();

        $sql = "SELECT * FROM tbl_emp_acc WHERE emp_id=$account";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if ($row['status'] == "active") {
            // User is a verified alumni
            header('Location: ./employee/dashboard.php');
            exit();
        } else {

            $_SESSION['email'] = $account_email;
            $_SESSION['alert'] = 'inactive';
            sleep(2);
            header('Location: ./employee/inactive.php');
            exit();
        }
    }
    $stmt->close();
}
?>