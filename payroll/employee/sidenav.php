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
                <i class="bi bi-person-circle profile-icon"></i> <span class="user-name">Emp0001</span>
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
    <script src="./javascript/main.js"></script>
</body>

</html>