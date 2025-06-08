<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$account = $_SESSION['email'];

$profile_qry = mysqli_query($conn, "
    SELECT 
        e.emp_id,
        e.firstname,
        e.lastname,
        e.middlename,
        e.gender,
        e.email,
        e.address,
        e.phone_no,
        i.shift,
        i.rate,
        i.position,
        i.bank_acc,
        i.pay_type
    FROM tbl_emp_acc AS e
    INNER JOIN tbl_emp_info AS i ON e.emp_id = i.emp_id
    WHERE e.email = '$account'
");

if (!$profile_qry) {
    die("Query failed: " . mysqli_error($conn));
}

$emp = mysqli_fetch_assoc($profile_qry);

if (!$emp) {
    die("Employee data not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="./css/main.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f1f3f6;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        .main-header {
            padding-left: 260px;
            padding-top: 30px;
            box-sizing: border-box;
        }

        .page-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .breadcrumb {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }

        .top-divider {
            margin: 20px 0 0 260px;
            border: none;
            border-top: 1px solid #ccc;
            width: calc(100% - 260px);
        }

        .container {
            width: 100%;
            max-width: 1100px;
            padding: 40px;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
            margin-top: 40px;
            margin-bottom: 40px;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 35px;
        }

        .profile-header img {
            width: 85px;
            height: 85px;
            border-radius: 50%;
            margin-right: 25px;
        }

        .profile-header h2 {
            font-size: 24px;
            margin: 0;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            border-left: 4px solid #AAC7D8;
            padding-left: 10px;
            font-weight: 600;
            color: #333;
        }

        .form-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 25px;
        }

        .form-group {
            flex: 1 1 30%;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 14px;
            color: #444;
        }

        .form-group input {
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f5f5f5;
            font-size: 14px;
            color: #333;
        }

        .update_btn {
            margin-top: auto;
            padding: 10px 25px;
            background: #AAC7D8;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            height: 42px;
        }

        .horizontal-line {
            margin: 35px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
        .head-title {
            margin: 10px;
        }

    </style>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div>
        <div id="mainContent" class="main">
             <!-- Header beside sidenav -->
            <div class="head-title">
                <h1>Welcome Admin!</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>>
                    <h5>Profile</h5>
                </div>
            </div>

            <div class="container">
                <div>
                    <div class="profile-header">
                    <img src="../assets/user_icon.png" alt="Profile Picture">
                    <h2><?= htmlspecialchars($emp['firstname'] . ' ' . $emp['lastname']); ?></h2>
                </div>
                <div class="section-title">Employee Account</div>
                    <div class="form-section">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" value="<?= htmlspecialchars($emp['emp_id']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Pay Type</label>
                            <input type="text" value="<?= htmlspecialchars($emp['pay_type']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Rate</label>
                            <input type="text" value="₱<?= htmlspecialchars($emp['rate']); ?>/hr" readonly>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="text" value="<?= htmlspecialchars($emp['phone_no']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" value="<?= htmlspecialchars($emp['position']); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label>Bank Name</label>
                            <input type="text" value="Power Bank" readonly>
                        </div>
                        <div class="form-group">
                            <label>Bank Account</label>
                            <input type="text" value="<?= htmlspecialchars($emp['bank_acc']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="update_btn">Update</button>
                        </div>
                    </div>

                    <hr class="horizontal-line">

                    <div class="section-title">Others</div>
                    <div class="form-section">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" value="<?= htmlspecialchars($emp['email']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="update_btn">Update</button>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" value="*********" readonly>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="update_btn">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="./javascript/main.js"></script>
</body>

</html>