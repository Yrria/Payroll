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
                <a href="./leave.php">
                    <i class="bi bi-calendar-check-fill"></i>
                    <span class="link-name">Leave</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Leave</span>
                </ul>
            </li>
            <li>
                <a href="./salary.php">
                    <i class="bi bi-people-fill"></i>
                    <span class="link-name">Salary</span>
                </a>
                <ul class="sub-menu blank">
                    <span class="link-name">Salary</span>
                </ul>
            </li>
        </ul>
    </div>
    <!-- SCRIPT -->
    <script src="./javascript/sidenav.js"></script>
</body>

</html>