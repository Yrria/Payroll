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
        <span class="logo">
            <img src="../assets/logo.png" alt="logo">
        </span>
        <ul>
            <a href="#">
                <li>
                    <i class="bi bi-house-door-fill"></i>
                    <span class="span-text" style="margin-top:2%;">Dashboard</span>
                </li>
            </a>
            <a href="#">
                <li>
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="span-text" style="margin-top:2%;">Attendance</span>
                </li>
            </a>
            <a href="#">
                <li>
                    <i class="bi bi-people-fill"></i>
                    <span class="span-text" style="margin-top:2%;">Employee</span>
                </li>
            </a>
            <a href="#">
                <li>
                    <i class="bi bi-credit-card-fill"></i>
                    <span class="span-text" style="margin-top:2%;">Payroll</span>
                </li>
            </a>
            <li>
                <i class="bi bi-calendar-minus-fill"></i>
                <span class="span-text" style="margin-top:2%;">Leaves</span>
                <i class="bi bi-caret-down-fill indicators"></i>
                <ul class="dropdown second-option">
                    <a href="#">
                        <li>
                            <span class="drop-option">Pending</span>
                        </li>
                    </a>
                    <a href="#">
                        <li>
                            <span class="drop-option">Approved</span>
                        </li>
                    </a>
                    <a href="#">
                        <li>
                            <span class="drop-option">Declined</span>
                        </li>
                    </a>
                </ul>
            </li>
            <li>
                <i class="bi bi-folder-fill"></i>
                <span class="span-text" style="margin-top:2%;">Reports</span>
                <i class="bi bi-caret-down-fill indicators"></i>
                <ul class="dropdown second-option">
                    <a href="#">
                        <li>
                            <span class="drop-option">Leaves</span>
                        </li>
                    </a>
                    <a href="#">
                        <li>
                            <span class="drop-option">Payments</span>
                        </li>
                    </a>
                    <a href="#">
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