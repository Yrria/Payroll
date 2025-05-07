<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql_emp = "SELECT * FROM tbl_emp_acc WHERE email = '$email' AND password = '$password'";
    $result_emp = $conn->query($sql_emp);

    $sql_admin = "SELECT * FROM tbl_admin_acc WHERE email = '$email' AND password = '$password'";
    $result_admin = $conn->query($sql_admin);

    if ($result_emp->num_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: ./employee/dashboard.php");
        exit();
    } elseif ($result_admin->num_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: ./adminpage/dashboard.php");
        exit();
    } else {
        $error = "Invalid Employee/Admin Email or Password.";
    }
}
$conn->close();
?>