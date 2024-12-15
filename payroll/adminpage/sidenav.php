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
    <div class="sidenav" id="sidenav">
            <a href="dashboard.php" class="logo">
                <img src="../assets/logo.png" alt="logo">
            </a>
        <ul>
            <a href="./dashboard.php">
                <li>
                    <i class="bi bi-house-door-fill"></i>
                    <span class="span-text">Dashboard</span>
                </li>
            </a>
            <a href="./attendance.php">
                <li>
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="span-text">Attendance</span>
                </li>
            </a>
            <a href="./employee.php">
                <li>
                    <i class="bi bi-people-fill"></i>
                    <span class="span-text">Employee</span>
                </li>
            </a>
            <a href="./payroll.php">
                <li>
                    <i class="bi bi-credit-card-fill"></i>
                    <span class="span-text">Payroll</span>
                </li>
            </a>
            <li class="three-option">
                <i class="bi bi-calendar-minus-fill"></i>
                <span class="span-text">Leaves</span>
                <i class="bi bi-caret-down-fill indicators"></i>
                <ul class="dropdown second-option">
                    <a href="./leave_pending.php">
                        <li>
                            <span class="drop-option">Pending</span>
                        </li>
                    </a>
                    <a href="./leave_approved.php">
                        <li>
                            <span class="drop-option">Approved</span>
                        </li>
                    </a>
                    <a href="./leave_declined.php">
                        <li>
                            <span class="drop-option">Declined</span>
                        </li>
                    </a>
                </ul>
            </li>
            <li class="three-option">
                <i class="bi bi-folder-fill"></i>
                <span class="span-text">Reports</span>
                <i class="bi bi-caret-down-fill indicators"></i>
                <ul class="dropdown second-option">
                    <a href="./report_leave.php">
                        <li>
                            <span class="drop-option">Leaves</span>
                        </li>
                    </a>
                    <a href="./report_payment.php">
                        <li>
                            <span class="drop-option">Payments</span>
                        </li>
                    </a>
                    <a href="./report_yearwise.php">
                        <li>
                            <span class="drop-option">Year Wise</span>
                        </li>
                    </a>
                </ul>
            </li>
        </ul>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/sidenav.js"></script>
</body>

</html>