<?php
session_start();
include '../assets/databse/connection.php';
include './database/session.php';
$emp_id = $_SESSION['emp_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/salary.css">
    <title>Salary</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Salary</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./salary.php">Salary</a></h5>
                </div>
                <hr>
            </div>

            <div class="main-content">
                <div class="sub-content">
                    <div class="content">
                        <div class="filterDiv">
                            <select name="yearFilter" id="yearFilter" class="textbox">
                                <option value="">Select Year</option>
                                <?php
                                $yearResult = mysqli_query($conn, "SELECT DISTINCT year FROM tbl_salary WHERE emp_id = '$emp_id' ORDER BY year DESC");
                                while ($row = mysqli_fetch_assoc($yearResult)) {
                                    echo "<option value='{$row['year']}'>{$row['year']}</option>";
                                }
                                ?>
                            </select>
                            <select name="monthFilter" id="monthFilter" class="textbox">
                                <option value="">Select Month</option>
                                <?php
                                $monthResult = mysqli_query($conn, "SELECT DISTINCT month FROM tbl_salary WHERE emp_id = '$emp_id' ORDER BY FIELD(month,
                                    'January', 'February', 'March', 'April', 'May', 'June',
                                    'July', 'August', 'September', 'October', 'November', 'December')");
                                while ($row = mysqli_fetch_assoc($monthResult)) {
                                    echo "<option value='{$row['month']}'>{$row['month']}</option>";
                                }
                                ?>
                            </select>
                            <select name="cutoffFilter" id="cutoffFilter" class="textbox">
                                <option value="">Select Cutoff</option>
                                <option value="Cutoff 1">First Cutoff</option>
                                <option value="Cutoff 2">Second Cutoff</option>
                            </select>
                            <select name="statusFilter" id="statusFilter" class="textbox">
                                <option value="">Select Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="content">
                        <table id="salaryTable">
                            <tr>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Cutoff</th>
                                <th>Status</th>
                                <th>Total Salary</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="salaryData">
                                <!-- Dynamic rows go here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="overlay" id="overlay">
                        <div class="info-container">
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
    <script src="./javascript/salary.js"></script>
</body>

</html>