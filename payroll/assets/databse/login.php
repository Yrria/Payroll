<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $emp_id = mysqli_real_escape_string($conn, $_POST['id']);
    $admin_id = mysqli_real_escape_string($conn, $_POST['id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql_emp = "SELECT * FROM tbl_emp_acc WHERE emp_id = '$emp_id' AND password = '$password'";
    $result_emp = $conn->query($sql_emp);

    $sql_admin = "SELECT * FROM tbl_admin_acc WHERE admin_id = '$admin_id' AND password = '$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_emp->num_rows > 0) {
        $_SESSION['user_id'] = $emp_id;
        header("Location: ./employee/dashboard.php");
        exit();
    } elseif ($result_admin->num_rows > 0) {
        $_SESSION['user_id'] = $admin_id;
        header("Location: ./adminpage/dashboard.php");
        exit();
    } else {
        $error = "Invalid Employee/Admin ID or Password.";
    }
}
$conn->close();
?>