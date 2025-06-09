<?php
session_start();
include '../assets/databse/connection.php';
include './database/inactiveSession.php';

$emp_id = $row['emp_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $newPass = $_POST['newPass'] ?? '';
    $confirmPass = $_POST['confirmPass'] ?? '';
    $currentPass = $_POST['currentPass'] ?? '';

    if (empty($newPass) || empty($confirmPass) || empty($currentPass)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif ($newPass !== $confirmPass) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $stmt = $conn->prepare("SELECT password FROM tbl_emp_acc WHERE emp_id = ?");
        $stmt->bind_param("s", $emp_id);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        if ($stmt->fetch()) {  // only if a password was fetched
            $stmt->close();
            if (trim($dbPassword) !== trim($currentPass)) {
                echo "<script>alert('Current password is incorrect.');</script>";
            } else {
                $update = $conn->prepare("UPDATE tbl_emp_acc SET password = ?, status = 'active' WHERE emp_id = ?");
                $update->bind_param("ss", $newPass, $emp_id);
                if ($update->execute()) {
                    echo "<script>alert('Password successfully updated.'); window.location.href='dashboard.php?status=success';</script>";
                } else {
                    echo "<script>alert('Failed to update password.');</script>";
                }
                $update->close();
            }
        } else {
            echo "<script>alert('Account not found.')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml" />
    <link rel="stylesheet" href="./css/main.css" />
    <link rel="stylesheet" href="./css/profile.css" />
    <title>Profile</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Profile</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard</a></h5>
                    <span> > </span>
                    <h5><a href="./profile.php">Profile</a></h5>
                </div>
                <hr />
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="change_pass_div">
                        <p style="margin-bottom: 10px; color: red; font-weight: bold;">
                            Your account is inactive. You must change your password to proceed.
                        </p>
                        <hr style="opacity: 0.5" />
                        <form method="POST">
                            <div class="input_div">
                                <span style="font-size: 15px;">Current Password</span><br />
                                <input
                                    type="password"
                                    name="currentPass"
                                    class="input_box"
                                    placeholder="Enter Current Password"
                                    style="margin-top:10px;"
                                    required
                                />
                            </div>
                            <br />
                            <div class="input_div">
                                <span style="font-size: 15px;">New Password</span><br />
                                <input
                                    type="password"
                                    name="newPass"
                                    class="input_box"
                                    placeholder="Enter New Password"
                                    style="margin-top:10px;"
                                    required
                                />
                            </div>
                            <br />
                            <div class="input_div">
                                <span style="font-size: 15px;">Confirm Password</span><br />
                                <input
                                    type="password"
                                    name="confirmPass"
                                    class="input_box"
                                    placeholder="Confirm New Password"
                                    style="margin-top:10px;"
                                    required
                                />
                            </div>
                            <br />
                            <button class="update_btn" name="update_password">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
