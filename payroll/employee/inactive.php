<?php
session_start();
include '../assets/databse/connection.php';
include './database/inactiveSession.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/logowhite-.png" type="image/svg+xml">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/profile.css">
    <title>Profile</title>
</head>

<body>
    <?php include 'sidenav.php'; ?>
    <div class="container">
        <div id="mainContent" class="main">
            <div class="head-title">
                <h1>Profile</h1>
                <div class="breadcrumb">
                    <h5><a href="./dashboard.php">Dashboard </a></h5>
                    <span> > </span>
                    <h5><a href="./profile.php">Profile </a></h5>
                </div>
                <hr>
            </div>
            <div class="main-content">
                <div class="sub-content">
                    <div class="change_pass_div">
                        <p style="margin-bottom: 10px;">Employee Account</p>
                        <hr style="opacity:0.5;">
                        <div class="input_div">
                            <span style="font-size: 15px;">New Password</span><br>
                            <input type="password" name="newPass" class="input_box" placeholder="Enter New Password" style="margin-top:10px;">
                        </div>
                        <br>
                        <div class="input_div">
                            <span style="font-size: 15px;">Confirm Password</span><br>
                            <input type="password" name="confirmPass" class="input_box" placeholder="Confirm New Password" style="margin-top:10px;">
                        </div>
                        <br>
                        <div class="input_div">
                            <span style="font-size: 15px;">Current Password</span><br>
                            <input type="password" name="currentPass" class="input_box" placeholder="Enter Current Password" style="margin-top:10px;">
                        </div>
                        <br>


                        <button class="update_btn">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
</body>

</html>