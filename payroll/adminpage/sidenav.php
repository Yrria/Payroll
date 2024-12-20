<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/sidenav.css">
    <title>Side Navigation</title>
</head>

<body>
    <!-- <div class="header">
        <div class="toggle-btn" onclick="toggleNav()">☰</div>
    </div> -->

    <div class="sidenav" id="sidenav">
        <div class="logodetails">
            <a href="dashboard.php" class="logo">
                <img src="../assets/logo.png" alt="logo">
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
                <a href="./payroll.php">
                    <i class="bi bi-credit-card-fill"></i>
                    <span class="link-name">Payroll</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Payroll</span>
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
    <script src="./javascript/sidenav.js"></script>
</body>

</html>