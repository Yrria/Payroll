<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/main.css">
    <title>Side Navigation</title>
</head>

<body>
    <div class="sidenav" id="sidenav">

        <!-- header -->
        <div class="header">
            <a href="dashboard.php" class="title-tags"><span class="comp-name"><img src="../assets/title.png" class="title-name" alt="ExPense"></span></a>
            <div class="toggle-btn" onclick="toggleNav()"><span class="menu-icon">☰</span></div>
            <span class="current-date-time">
                <span id="current-date"></span> - <span id="current-time"></span>
            </span>
            <div class="profile" onclick="toggleDropdown()">
                <i class="bi bi-person-circle profile-icon"></i> <span class="user-name">Admin</span>
                <!-- Dropdown Menu -->
                <ul class="dropdown-menu">
                    <a href="./profile.php">
                        <li><span>Profile</span></li>
                    </a>
                    <a href="../index.php">
                        <li><span>Logout</span></li>
                    </a>
                </ul>
            </div>
        </div>

        <!-- SIDENAV START -->
        <div class="logodetails">
            <a href="dashboard.php" class="logo">
                <img src="../assets/logowhite-.png" alt="logo">
            </a>
        </div>
        <ul class="nav-links">
            <li>
                <a href="./dashboard.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="link-name">Dashboard</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Dashboard</span>
                </ul>
            </li>
            <li>
                <a href="./attendance.php">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="link-name">Attendance</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Attendance</span>
                </ul>
            </li>
            <li>
                <a href="./employee.php">
                    <i class="bi bi-people-fill"></i>
                    <span class="link-name">Employee</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Employee</span>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                        <a href="" class="disabled-link">
                        <i class="bi bi-credit-card-fill"></i>
                        <span class="link-name">Payroll</span>
                    </a>
                    <i class="bi bi-caret-down-fill arrow"></i>
                </div>
                <ul class="sub-menu">
                    <span class="link-name">Leave</span>
                    <li><a href="./payroll.php"><span>Create Payslip</span></a></li>
                    <li><a href="./payslips.php"><span>Payslip list</span></a></li>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="" class="disabled-link">
                        <i class="bi bi-calendar-minus-fill"></i>
                        <span class="link-name">Leave</span>
                    </a>
                    <i class="bi bi-caret-down-fill arrow"></i>
                </div>
                <ul class="sub-menu">
                    <span class="link-name">Leave</span>
                    <li><a href="./leave_pending.php"><span>Pending</span></a></li>
                    <li><a href="./leave_approved.php"><span>Approved</span></a></li>
                    <li><a href="./leave_declined.php"><span>Declined</span></a></li>
                </ul>
            </li>
            <li>
                <div class="icon-link">
                    <a href="" class="disabled-link">
                        <i class="bi bi-folder-fill"></i>
                        <span class="link-name">Report</span>
                    </a>
                    <i class="bi bi-caret-down-fill arrow"></i>
                </div>
                <ul class="sub-menu">
                    <span class="link-name">Report</span>
                    <li><a href="./report_leave.php"><span>Leave</span></a></li>
                    <li><a href="./report_payment.php"><span>Payment</span></a></li>
                    <li><a href="./report_yearwise.php"><span>Year wise</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/main.js"></script>
</body>

</html>