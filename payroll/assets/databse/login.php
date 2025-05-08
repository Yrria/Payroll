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
